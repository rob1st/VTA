<?php
echo "<h1 style='color: blue'>You've reached updateDefCommit</h1>";
use Mailgun\Mailgun;

session_start();
include 'vendor/autoload.php';
include 'SQLFunctions.php';
include 'uploadImg.php';

// // prepare POST and sql string for commit
$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
$defID = $post['defID'];
$userID = $_SESSION['userID'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// validate POST data
// if it's empty then file upload exceeds post_max_size
// bump user back to form
if (!count($post) || !$defID) {
    include('js/emptyPostRedirect.php');
    exit;
}

// if photo in POST it will be committed to a separate table
if ($_FILES['CDL_pics']['size']
    && $_FILES['CDL_pics']['name']
    && $_FILES['CDL_pics']['tmp_name']
    && $_FILES['CDL_pics']['type']) {
    $CDL_pics = $_FILES['CDL_pics'];
} else $CDL_pics = null;

// hold onto comments separately
$cdlCommText = trim($post['cdlCommText']);

// unset keys that will not be updated before imploding back to string
unset(
    $post['defID'],
    $post['cdlCommText']
);

// if Closed, set dateClosed
// if Closure Requested, record by whom
if ($post['status'] === '2') {
    $post['dateClosed'] = 'NOW()';
} elseif ($post['status'] === '1') {
    $closureReq = $post['closureRequested'] = 0;
    $closeReqBy = $post['closureRequestedBy'] = null;
} elseif ($post['status'] === '4') {
    $post['status'] = 1;
    $closureReq = $post['closureRequested'] = 1;
    $closeReqBy = $post['closureRequestedBy'] = $userID;
}

// append keys that do not or may not come from html form
// or whose values may be ambiguous in $_POST (e.g., checkboxes)
$post['updated_by'] = $username;

echo "<h1 style='color: green;'>begin updateDefCommit</h1>";
var_dump($post);

try {
    echo "<h1 style='color: red'>try link connect</h1>";
    $link = connect();
    // update CDL table
    $link->where('defID', $defID);
    $link->update('CDL', $post);

    // if INSERT succesful, prepare, upload, and INSERT photo
    if ($CDL_pics) {
        // $sql = "INSERT CDL_pics (defID, pathToFile) values (?, ?)";

        // execute save image and hold onto its new file path
        try {
            $pathToFile = saveImgToServer($_FILES['CDL_pics'], $defID);

            $fileData = [
                'pathToFile' => $pathToFile,
                'defID' => $defID
            ];

            $link->insert('CDL_pics', $fileData);
        } catch (uploadException $e) {
            header("Location: updateDef.php?defID=$defID");
            $_SESSION['errorMsg'] = "There was an error uploading your file: $e";
        } catch (Exception $e) {
            header("Location: updateDef.php?defID=$defID");
            $_SESSION['errorMsg'] = "There was a problem recording your file: $e";
        }
    }

    // if comment submitted commit it to a separate table
    if (strlen($cdlCommText)) {
        // $sql = "INSERT cdlComments (defID, cdlCommText, userID) VALUES (?, ?, ?)";
        try {
            $commentData = [
                'defID' => $defID,
                'cdlCommText' => filter_var($cdlCommText, FILTER_SANITIZE_SPECIAL_CHARS),
                'userID' => $userID
            ];

            $link->insert('cdlComments', $commentData);
        } catch (Exception $e) {
            header("Location: updateDef.php?defID=$defID");
            $_SESSION['errorMsg'] = "There was a problem recording your comment: $e";
        }
    }
    
    echo "<h3>commit ok</h3>";

    // if closure requested, try to email system lead    
    if (!empty($closureReq)) {
        echo "<h4>Closure requested</h4>";
        // instantiate new mailgun client
        $mgClient = new Mailgun($mailgunKey);
        $domain = $mailgunDomain;
    //     try {
    //         // if (isset($post['groupToResolve'])) {
    //             $systemID = $post['groupToResolve'];
    //         // } else {
    //         //     $link->where('defID', $defID);
    //         //     $systemID = $link->getOne('CDL', 'groupToResolve');
    //         // }
    //         // $link->where('systemID', $systemID)
    //         // $result = $link->getOne('system', ['lead', 'systemName']);
    //         // $systemName = $result['systemName'];
    //         // if ($link->count) {
    //         //     use mailgun to email sys lead
    //             $msg = "$closeReqBy has requested deficiency number $defID be closed
    //                 \nView this deficiency at https://$_SERVER['DOCUMENT_ROOT']/defs.php?search=1&groupToResolve=$systemID&closureRequested=1";
                
    //             $mgClient->sendMessage($domain, [
    //                 'from' => 'no_reply@mail.svbx.org',
    //                 'to' => 'ckingbailey@gmail.com',
    //                 'subject' => "New closure request for your system: $systemID",
    //                 'text' => $msg
    //             ]);
    //         // }
    //     } catch (Exception $e) {
    //         echo "catch mail error";
    //         // header("Location: updateDef.php?defID=$defID")
    //         // $_SESSION['errorMsg'] = "There was a problem sending the email";
    //     }
        echo "<h4>Mailgun client instantiated</h4>";
    }

    // header("Location: viewDef.php?defID=$defID");
} catch (Exception $e) {
    echo "<h1>catch commit error</h1>";
    // header("Location: updateDef.php?defID=$defID");
    // $_SESSION['errorMsg'] = "There was an error in committing your submission: $e";
} finally {
    $link->disconnect();
    // exit;
}

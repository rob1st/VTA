<?php
include('sql_functions/sqlFunctions.php');
session_start();

$timestamp = date('Y-m-d H:i:s');
$link = f_sqlConnect();

if ($idrID = intval($_POST['idrID'])) {
    if ($_POST['approve'] === 'true') {
        if ($userID = intval($_POST['userID'])) {
            if ($comment = $link->escape_string($_POST['comment'])) {
                $commentQry = "INSERT idrComments (userID, comment, idrID, commentDate)
                    VALUES ('{$_POST['userID']}', '{$_POST['comment']}', {$idrID}, '{$timestamp}')";
                $result = $link->query($commentQry);
                if (!$result) {
                    http_response_code(500);
                    $code = http_response_code();
                    echo "There was a problem adding your comment";
                    return;
                }
            }
            $idrQry = "UPDATE IDR SET approvedBy='{$userID}' where idrID={$idrID}";
            $result = $link->query($idrQry);
            if (!$result) {
                http_response_code(500);
                $code = http_response_code();
                echo "There was a problem handling your form submission\n{$link->error}";
                return;
            } else {
                http_response_code(200);
                $code = http_response_code();
                echo "Daily report approved\nreport #{$idrID}\n{$timestamp}";
                return;
            }
        }
    } elseif ($comment = $_POST['comment']) {
        $commentQry = "INSERT idrComments (userID, comment, idrID, commentDate)
            VALUES ('{$_POST['userID']}', '{$_POST['comment']}', {$idrID}, '{$timestamp}')";
        $result = $link->query($commentQry);
        if (!$result) {
            http_response_code(500);
            $code = http_response_code();
            echo "There was a problem adding your comment\n{$link->error}";
            return;
        } else {
            http_response_code(201);
            $code = http_response_code();
            echo "Comment added";
            return;
        }
    } else {
        http_response_code(409);
        $code = http_response_code();
        echo "There was a problem with the request:\nNo data received";
    }
}
?>
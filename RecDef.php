<?PHP
session_start();
include('SQLFunctions.php');
include('uploadImg.php');

$date = date('Y-m-d');
$userID = $_SESSION['UserID'];
$username = $_SESSION['Username'];
$nullVal = null;

$link = f_sqlConnect();

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

        
// prepare POST and sql string for commit
$post = array(
  'safetyCert' => intval($_POST['safetyCert']),
  'systemAffected' => intval($_POST['systemAffected']),
  'location' => intval($_POST['location']),
  'specLoc' => filter_var($link->escape_string($_POST['specLoc']), FILTER_SANITIZE_STRING),
  'status' => intval($_POST['status']),
  'severity' => intval($_POST['severity']),
  'dueDate' => filter_var($link->escape_string($_POST['dueDate']), FILTER_SANITIZE_STRING),
  'groupToResolve' => intval($_POST['groupToResolve']),
  'requiredBy' => intval($_POST['requiredBy']),
  'contractID' => intval($_POST['contractID']),
  'identifiedBy' => filter_var($link->escape_string($_POST['identifiedBy']), FILTER_SANITIZE_STRING),
  'defType' => intval($_POST['defType']),
  'description' => filter_var($link->escape_string($_POST['description']), FILTER_SANITIZE_STRING),
  'spec' => filter_var($link->escape_string($_POST['spec']), FILTER_SANITIZE_STRING),
  'actionOwner' => filter_var($link->escape_string($_POST['actionOwner']), FILTER_SANITIZE_STRING),
  'oldID' => filter_var($link->escape_string($_POST['oldID']), FILTER_SANITIZE_STRING),
  'evidenceType' => intval($_POST['evidenceType']),
  'repo' => intval($_POST['repo']),
  'evidenceLink' => filter_var($link->escape_string($_POST['evidenceLink']), FILTER_SANITIZE_STRING),
  'closureComments' => filter_var($link->escape_string($_POST['closureComments']), FILTER_SANITIZE_STRING),
  'created_by' => $username,
  'dateCreated' => $date,
  'dateClosed' => $nullVal
);

// validate POST data
// if it's empty then file upload exceeds post_max_size
// bump user back to form
if (!count($post)) {
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

// not yet accepting comments on new defs
$cdlCommText = '';
    
// prepare parameterized string from external .sql file
$fieldList = preg_replace('/\s+/', '', file_get_contents('UpdateDef.sql'));
$fieldsArr = array_fill_keys(explode(',', $fieldList), '?');

// unset keys that will not be updated before imploding back to string
unset(
    $fieldsArr['defID'],
    $fieldsArr['updated_by'],
    $fieldsArr['lastUpdated']
);

$assignmentList = implode(' = ?, ', array_keys($fieldsArr)).' = ?';
$sql = 'INSERT INTO CDL ('
  . implode(', ', array_keys($post))
  . ') VALUES ('
  . implode(',', array_fill(0, count($post), '?'))
  . ')';

// append keys that do not or may not come from html form
// or whose values may be ambiguous in $_POST (e.g., checkboxes)
$post += ['created_by' => $username];

// if photo in POST it will be committed to a separate table
if ($_FILES['CDL_pics']['size']
    && $_FILES['CDL_pics']['name']
    && $_FILES['CDL_pics']['tmp_name']
    && $_FILES['CDL_pics']['type']) {
    $CDL_pics = $_FILES['CDL_pics'];
} else $CDL_pics = null;

$userID = $_SESSION['UserID'];

// echo "<p style='font-family: monospace'>SQL string: $sql</p>";
// echo "<pre style='color: blue'>";
// var_dump($post);
// echo "</pre>";

try {
    $success = "<div style='background-color: gold; background-clip: padding-box; border: 5px dashed limeGreen;'>%s</div>";
    $successFormat = "<p style='color: %s'>%s</p>";
    $linkBtn = "<a href='UpdateDef.php?defID=%s' style='text-decoration: none; border: 2px solid plum; padding: .35rem;'>Back to Update Def</a>";
    
    if (!$stmt = $link->prepare($sql)) throw new Exception($link->error);
    
    $success = sprintf($success, sprintf($successFormat, 'blue', '&#x2714; CDL stmt prepared') . '%s');
    
    $types = 'iiisiisiiisissssiisssss';
    
    print "
    <p id='strlenTypes' style='font-family: monospace'>" . strlen($types) . "</p>
    <p id='countPost' style='font-family: monospace'>" . count($post) . "</p>
    <pre>";
    var_dump($post);
    print "</pre>";
    
    if (!$stmt->bind_param('iiisiisiiisissssiisssss',
        $post['safetyCert'],
        $post['systemAffected'],
        $post['location'],
        $post['specLoc'],
        $post['status'],
        $post['severity'],
        $post['dueDate'],
        $post['groupToResolve'],
        $post['requiredBy'],
        $post['contractID'],
        $post['identifiedBy'],
        $post['defType'],
        $post['description'],
        $post['spec'],
        $post['actionOwner'],
        $post['oldID'],
        $post['evidenceType'],
        $post['repo'],
        $post['evidenceLink'],
        $post['closureComments'],
        $post['created_by'],
        $post['dateCreated'],
        $nullVal
    )) throw new mysqli_sql_exception($stmt->error);
    
    $success= sprintf($success, sprintf($successFormat, 'forestGreen', '&#x2714; CDL params bound') . '%s');
    
    if (!$stmt->execute()) throw new mysqli_sql_exception($stmt->error);
    
    $defID = $stmt->insert_id;
    
    $success = sprintf($success, sprintf($successFormat, 'dodgerBlue', '&#x2714; CDL insert executed') . '%s');
    
    $stmt->close();
    
    $success = sprintf($success, sprintf($successFormat, 'indigo', '&#x2714; CDL stmt closed') . '%s');
    
    // if INSERT succesful, prepare, upload, and INSERT photo
    if ($CDL_pics) {
        $sql = "INSERT CDL_pics (defID, pathToFile) values (?, ?)";
        if (!$stmt = $link->prepare($sql)) throw new Exception($link->error);
        $success = sprintf($success, sprintf($successFormat, 'cadetBlue', '&#x2714; cdlPics stmt prepared') . '%s');
        if (!$stmt->bind_param('is', $defID, $pathToFile)) throw new mysqli_sql_exception($stmt->error);
        $success = sprintf($success, sprintf($successFormat, 'cornFlower', '&#x2714; cdlPics params bound') . '%s');
        if (!$stmt->execute()) throw new mysqli_sql_exception($stmt->error);
        $success = sprintf($success, sprintf($successFormat, 'aqua', '&#x2714; cdlPics insert executed') . '%s');
        $stmt->close();
        $success = sprintf($success, sprintf($successFormat, 'aquamarine', '&#x2714; cdlPics stmt closed') . '%s');
    } else {
        $success = sprintf($success, sprintf($successFormat, 'cyan', '&#x2718; no cdlPics found') . '%s');
    }
    
    // if comment submitted commit it to a separate table
    if (strlen($cdlCommText)) {
        $sql = "INSERT cdlComments (defID, cdlCommText, userID) VALUES (?, ?, ?)";
        $commentText = filter_var(
            filter_var(
                $cdlCommText,
                FILTER_SANITIZE_STRING,
                FILTER_FLAG_NO_ENCODE_QUOTES
            ), FILTER_SANITIZE_SPECIAL_CHARS);
        if (!$stmt = $link->prepare($sql)) throw new Exception($link->error);
        $success = sprintf($success, sprintf($successFormat, 'darkCyan', '&#x2714; cdlComments stmt prepared') . '%s');
        if (!$stmt->bind_param('isi',
            intval($defID),
            $commentText,
            intval($userID))) throw new mysqli_sql_exception($stmt->error);
        $success = sprintf($success, sprintf($successFormat, 'darkBlue', '&#x2714; cdlComments params bound') . '%s');
        if (!$stmt->execute()) throw new mysqli_sql_exception($stmt->error);
        $success = sprintf($success, sprintf($successFormat, 'darkTurquoise', '&#x2714; cdlComments stmt executed') . '%s');
        $stmt->close();
        $success = sprintf($success, sprintf($successFormat, 'deepSkyBlue', '&#x2714; cdlComments stmt closed') . '%s');
    } else {
        $success = sprintf($success, sprintf($successFormat, 'mediumAquamarine', '&#x2718; no cdlComments found') . '%s');
    }

    $link->close();
    
    $success = sprintf($success, sprintf($successFormat, 'lightSteelBlue', '&#x2714; link closed') . '%s');
    $success = sprintf($success, sprintf($linkBtn, $defID));
    print $success;
    
    // header("Location: ViewDef.php?defID=$defID");
} catch (mysqli_sql_exception $e) {
    print "
        <div style='margin-top: 5.5rem;'>
            <p style='min-width: 7.5rem; min-height: 6rem; background-color: indianRed;'>{$e->getMessage()}</p>
        </div>";
    $stmt->close();
    $link->close();
    exit;
} catch (Exception $e) {
    print "
        <div style='margin-top: 5.5rem;'>
            <p style='min-width: 9rem; min-height: 5rem; background-color: orchid;'>{$e->getMessage()}</p>
        </div>";
    $link->close();
    exit;
}
// if ($stmt = $link->prepare($sql)) {
//   $types = 'iiisiisiiisisssssiisssss';
//   if ($stmt->bind_param($types,
//     $post['SafetyCert'],
//     $post['SystemAffected'],
//     $post['Location'],
//     $link->escape_string($post['SpecLoc']),
//     $post['Status'],
//     $post['Severity'],
//     $link->escape_string($post['DueDate']),
//     $post['GroupToResolve'],
//     $post['RequiredBy'],
//     $post['contractID'],
//     $link->escape_string($post['IdentifiedBy']),
//     $post['defType'],
//     $link->escape_string($post['Description']),
//     $link->escape_string($post['Spec']),
//     $link->escape_string($post['ActionOwner']),
//     $link->escape_string($post['OldID']),
//     $link->escape_string($post['comments']),
//     $post['EvidenceType'],
//     $post['Repo'],
//     $link->escape_string($post['EvidenceLink']),
//     $link->escape_string($post['ClosureComments']),
//     $link->escape_string($post['username']),
//     $date,
//     $nullVal
//   )) {
//     echo "<p style='color: lightSeaGreen; font-family: monospace'>{$stmt->param_count}</p>";
//     if ($stmt->execute()) {
//       $newDefID = $stmt->insert_id;
//       echo "<p style='color: tomato; font-family: cursive'>AFFECTED ROWS: ";
//       echo $stmt->affected_rows;
//       echo "</p>";
//       $stmt->close();
//       // if INSERT succesful, prepare, upload, and INSERT photo
//       if ($newDefID) {
//         echo "<p style='color: teal'>INSERT ID: $newDefID</p>";
//         // echo "<pre style='color: royalBlue'>";
//         // echo var_dump($_FILES);
//         // echo "</pre>";

//         if ($CDL_pics) {
//           $pathToFile = $link->escape_string(saveImgToServer($_FILES['CDL_pics'], $newDefID));
//           $qs .= "&$pathToFile";
//           $sql = "INSERT CDL_pics (defID, pathToFile) values (?, ?)";
//           if ($stmt = $link->prepare($sql)) {
//             echo "
//               <p style='font-family: sans-serif; color: chocolate'>
//               $sql<br>
//               $newDefID<br>
//               $pathToFile
//               </p>";
//               if ($stmt->bind_param('is', $newDefID, $pathToFile)) {
//                 echo "<p style='color: grey'>CDL_pics PARAM_CT: {$stmt->param_count}</p>";
//                   if ($stmt->execute()) {
//                     echo "
//                       <p id='CDL_picINSERTsuccess' style='color: oliveDrab'>new CDL_pic id: {$stmt->insert_id}<br>
//                       affected CDL_pic rows: {$stmt->affected_rows}</p>";
//                   } else {
//                     echo "<pre id='CDL_picINSERT->error' style='color: olive; font-size: 1.2rem'>";
//                     echo $stmt->error;
//                     echo "</pre>";
//                   }
//                   $stmt->close();
//               } else {
//                 echo "<p style='color: orangeRed'>";
//                 echo $stmt->error;
//                 echo "</p>";
//               }
//           } else {
//             echo "<p style='color: forestGreen'>";
//             echo $link->error;
//             echo "</p>";
//             exit;
//           }
//         }
//       }
//       $link->close();
//       header("Location: ViewDef.php?defID=$newDefID");
//     } elseif ($stmt->error) {
//       $stmt->close();
//       printSqlErrorAndExit($link, $sql);
//     } else {
//       $stmt->close();
//       printSqlErrorAndExit($link, $sql);
//     }
//     // echo "<h4>did it execute? what was the result? who knows?</h4>";
//   } else {
//     printSqlErrorAndExit($link, $sql);
//   }
// } else {
//   printSqlErrorAndExit($link, $sql);
// }

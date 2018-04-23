<?php
include('SQLFunctions');
session_start();

$timestamp = date('H-i-s').' '.$curDateNum;
$link = f_sqlConnect();

if ($idrID = $_POST['idrID']) {
    if ($_POST['approve'] === 'true') {
        if ($userID = $_POST['userID']) {
            if ($comment = $_POST['comment']) {
                $commentQry = "INSERT idrComments (userID, comment, idrID, commentDate)
                    VALUES ('{$userID}', '{$_POST['comment']}', {$idrID}, '{$timestamp}')";
                $result = $link->query($commentQry);
                if (!result) {
                    http_response_code(500);
                    $code = http_response_code();
                    echo "There was a problem adding your comment";
                    return;
                }
            }
            $idrQry = "INSERT IDR (approvedBy) VALUES ('{$userID}')";
            $result = $link->query($idrQry);
            if (!$result) {
                http_response_code(500);
                $code = http_response_code();
                echo "There was a problem handling your form submission";
                return;
            } else {
                http_response_code(201);
                $code = http_response_code();
                echo "Daily report approved";
                return;
            }
        }
    } elseif ($comment = $_POST['comment']) {
        $commentQry = "INSERT idrComments (userID, comment, idrID, commentDate)
            VALUES ('{$userID}', '{$_POST['comment']}', {$idrID}, '{$timestamp}')";
        $result = $link->query($commentQry);
        if (!result) {
            http_response_code(500);
            $code = http_response_code();
            echo "There was a problem adding your comment";
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
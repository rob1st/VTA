<?php
session_start();
include('SQLFunctions.php');
include('error_handling/sqlErrors.php');
include('utils/utils.php');
// include('uploadImg.php');
$link = f_sqlConnect();

$date = date('Y-m-d');
$nullVal = null;

// prepare POST and sql string for commit
$post = $_POST;
$defID = $post['id'];
// hold onto comments and attachments separately
$commentText = $post['bdComments'];
$attachment = $post['bdAttachments'];
// unset keys that will not be UPDATE'd on BARTDL
unset($post['id'], $post['bdComments'], $post['bdAttachments']);
// append keys that do not or may not come from html form
$post = ['updated_by' => $_SESSION['UserID']] + $post;
$post['resolution_disputed'] || $post['resolution_disputed'] = 0;
$post['structural'] || $post['structural'] = 0;
$assignmentList = implode(' = ?, ', array_keys($post)).' = ?';
$sql = "UPDATE BARTDL SET $assignmentList WHERE id=$defID";

if ($stmt = $link->prepare($sql)) {
    $types = 'iiiisssiiiiiiisssssssi';
    echo "
        <div style='margin-top: 3.5rem; color: darkSlateBlue'>
            <p>$sql</p>
            <p>link is a mysqli ".boolToStr(testForMysqliClass($link))."</p>";
    echo "<pre>";
    var_dump($post);
    echo "</pre>";

    if ($stmt->bind_param($types,
        intval($post['updated_by']),
        intval($post['creator']),
        intval($post['next_step']),
        intval($post['bic']),
        $link->escape_string($post['descriptive_title_vta']),
        $link->escape_string($post['root_prob_vta']),
        $link->escape_string($post['resolution_vta']),
        intval($post['status_vta']),
        intval($post['priority_vta']),
        intval($post['agree_vta']),
        intval($post['safety_cert_vta']),
        intval($post['resolution_disputed']),
        intval($post['structural']),
        intval($post['id_bart']),
        $link->escape_string($post['description_bart']),
        $link->escape_string($post['cat1_bart']),
        $link->escape_string($post['cat2_bart']),
        $link->escape_string($post['cat3_bart']),
        $link->escape_string($post['level_bart']),
        $link->escape_string($post['dateOpen_bart']),
        $link->escape_string($post['dateClose_bart']),
        intval($post['status_bart'])
    )) {
    echo "
            <p style='color: darkCyan'>{$stmt->affected_rows}</p>
            <p style='color: darkCyan'>{$stmt->insert_id}</p>
            <p style='color: darkCyan'>$types</p>";
    //     if ($stmt->execute()) {
    //         echo "
    //             <div style='margin-top: 3.5rem; color: purple'>
    //                 <p>{$stmt->affected_rows}</p>
    //                 <p>{$stmt->insert_id}</p>
    //                 <p>$fieldList</p>
    //                 <p>$sql</p>
    //                 <p>$types</p>
    //             </div>";
    //         echo "<pre>";
    //         var_dump($post);
    //         echo "</pre>";
            // header("Location: ViewDef.php?bartDefID={$stmt->insert_id}");
    //     } else {
    //         echo "<pre style='margin-top: 3.5rem; color: deepPink'>{$stmt->error}</pre>";
    //         $stmt-close();
    //         $link->close();
    //         exit;
    //     }
    // } else {
    //     echo "<pre style='margin-top: 3.5rem; color: limeGreen'>{$stmt->error}</pre>";
    //     $stmt-close();
    //     $link->close();
    //     exit;
    }
    echo "</div>";
} else {
    printSqlErrorAndExit($link, $sql);
}
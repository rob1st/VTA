<?php
include('session.php');
include('html_functions/bootstrapGrid.php');
include('sql_functions/stmtBindResultArray.php');
include('error_handling/sqlErrors.php');

$title = "SVBX - Update BART Deficiency";

$defID = $_GET['bartDefID'];
$fieldList = file_get_contents('bartdl.sql');
$fieldsArr = array_fill_keys(explode(',', $fieldList), '?');
$fieldList = implode(',', array_keys($fieldsArr));

$link = f_sqlConnect();

if ($result = $link->query('SELECT bdPermit from users_enc where userID='.$_SESSION['UserID'])) {
    if ($row = $result->fetch_row()) {
        $bdPermit = $row[0];
    }
    $result->close();
}

include('filestart.php');

$sql = "SELECT $fieldList FROM BARTDL WHERE id=$defID";

if ($stmt = $link->prepare($sql)) {
    // echo "
    //     <div style='margin-top: 3.5rem; color: darkCyan'>
    //         <p>$sql</p>";
    if ($stmt->execute()) {
        echo "<p style='color: cadetBlue'>execute success</p>";
        if ($result = stmtBindResultArray($stmt)[0]) {
            $topFields = [
                [
                    returnRow([
                        "<label for='creator'>Creator</label>",
                        [
                            'tagName' => 'select',
                            'element' => "<select name='creator' id='creator' class='form-control'>%s</select>",
                            'value' => $result['creator'],
                            'query' => 'SELECT bdCreatorID, bdCreatorName from bdCreator ORDER BY bdCreatorID'
                        ]
                    ]).
                    returnRow([
                        "<label for='next_step'>Next step</label>",
                        [
                            'tagName' => 'select',
                            'element' => "<select name='next_step' id='next_step' class='form-control'>%s</select>",
                            'query' => 'SELECT bdNextStepID, nextStepName FROM bdNextStep ORDER BY bdNextStepID',
                            'value' => $result['next_step']
                        ]
                    ]).
                    returnRow([
                        "<label for='bic'>Ball in court</label>",
                        [
                            'tagName' => 'select',
                            'element' => "<select name='bic' id='bic' class='form-control'>%s</select>",
                            'query' => 'SELECT bdCreatorID, bdCreatorName from bdCreator ORDER BY bdCreatorID',
                            'value' => $result['bic']
                        ]
                    ]),
                    'descriptive_title_vta' => [
                        'label' => "<label for='descriptive_title_VTA'>Description</label>",
                        'tagName' => 'textarea',
                        'element' => "<textarea name='descriptive_title_VTA' id='descriptive_title_VTA' class='form-control'>{$result['descriptive_title_vta']}</textarea>",
                        'query' => null
                    ]
                ]
            ];
        
            $vtaFields = [
                [
                    'root_prob_vta' => [
                        'label' => "<label for='root_prob_vta'>Root problem</label>",
                        'tagName' => 'textarea',
                        'element' => "<textarea name='root_prob_vta' id='root_prob_vta' class='form-control'>{$result['root_prob_vta']}</textarea>",
                        'query' => null
                    ]
                ],
                [
                    'resolution_vta' => [
                        'label' => "<label for='resolution_vta'>Resolution</label>",
                        'tagName' => 'textarea',
                        'element' => "<textarea name='resolution_vta' id='resolution_vta' class='form-control'>{$result['resolution_vta']}</textarea>",
                        'query' => null
                    ]
                ],
                [
                    returnRow([
                        "<label for='status_vta'>Status</label>",
                        [
                            'tagName' => 'select',
                            'element' => "<select name='status_vta' id='status_vta' class='form-control'>%s</select>",
                            'value' => $result['status_vta'],
                            'query' => "SELECT statusID, status from Status WHERE status <> 'Deleted'"
                        ]
                    ]).
                    returnRow([
                        "<label for='priority_vta'>Priority</label>",
                        [
                            'tagName' => 'select',
                            'element' => "<select name='priority_vta' id='priority_vta' class='form-control'>%s</select>",
                            'value' => $result['priority_vta'],
                            'query' => [ 1, 2, 3 ]
                        ]
                    ]).
                    returnRow([
                        "<label for='agree_vta'>Agree</label>",
                        [
                            'tagName' => 'select',
                            'element' => "<select name='agree_vta' id='agree_vta' class='form-control'>%s</select>",
                            'value' => $result['agree_vta'],
                            'query' => 'SELECT agreeDisagreeID, agreeDisagreeName from agreeDisagree'
                        ]
                    ]).
                    returnRow([
                        "<label for='safety_cert_vta'>Safety Certiable?</label>",
                        [
                            'tagName' => 'select',
                            'element' => "<select name='safety_cert_vta' id='safety_cert_vta' class='form-control'>%s</select>",
                            'value' => $result['safety_cert_vta'],
                            'query' => 'SELECT yesNoID, yesNo from YesNo'
                        ]
                    ]).
                    returnRow([ // will need sep table
                        "<label for='bdAttachments'>Upload attachment</label>",
                        [
                            'tagName' => 'input',
                            'type' => 'file',
                            'element' => "<input name='bdAttachments' id='bdAttachments' type='file' class='form-control'>"
                        ]
                    ]),
                    returnRow([
                        [
                            'label' => "<label for='bdComments'>Comment</label>",
                            'tagName' => 'textarea',
                            'element' => "<textarea name='bdComments' id='bdComments' class='form-control'>%s</textarea>",
                            'value' => ''
                        ]
                    ]).
                    // comments will need sep table
                    returnRow([
                        "<div class='form-check form-check-inline'>
                            <label for='resolution_disputed' class='form-check-label mr-2'>Resolution disputed</label>
                            <input name='resolution_disputed' id='resolution_disputed' type='checkbox' value='1' class='form-check-input'>
                        </div>",
                        "<div class='form-check form-check-inline'>
                            <label for='structural' class='form-check-label mr-2'>Structural</label>
                            <input name='structural' id='structural' type='checkbox' value='1' class='form-check-input'>
                        </div>"
                    ])
                ]
            ];
        
            $bartFields = [
                [
                    "<label for='id_bart'>BART ID</label>
                    <input name='id_bart' id='id_bart' type='text' value='{$result['id_bart']}' class='form-control'>"
                ],
                [
                    "<label for='description_bart'>Description</label>
                    <textarea name='description_bart' id='description_bart' maxlength='1000' class='form-control'>{$result['description_bart']}</textarea>"
                ],
                [
                    returnRow([
                        "<label for='cat1_bart'>Cat1</label>",
                        "<input name='cat1_bart' id='cat1_bart' type='text' maxlength='3' value='{$result['cat1_bart']}' class='form-control'>"
                    ]).
                    returnRow([
                        "<label for='cat2_bart'>Cat2</label>",
                        "<input name='cat2_bart' id='cat2_bart' type='text' maxlength='3' value='{$result['cat2_bart']}' class='form-control'>"
                    ]).
                    returnRow([
                        "<label for='cat3_bart'>Cat3</label>",
                        "<input name='cat3_bart' id='cat3_bart' type='text' maxlength='3' value='{$result['cat3_bart']}' class='form-control'>"
                    ]),
                    returnRow([
                        "<label for='level_bart'>Level</label>",
                        [
                            'tagName' => 'select',
                            'element' => "<select name='level_bart' id='level_bart' class='form-control'>%s</select>",
                            'value' => $result['level_bart'],
                            'query' => [ 'PROGRAM', 'PROJECT' ]
                        ]
                    ]).
                    returnRow([
                        "<label for='dateOpen_bart'>Date open</label>",
                        "<input name='dateOpen_bart' id='dateOpen_bart' type='date' value='{$result['dateOpen_bart']}' class='form-control'>"
                    ]).
                    returnRow([
                        "<label for='dateClose_bart'>Date closed</label>",
                        "<input name='dateClose_bart' id='dateClose_bart' type='date' value='{$result['dateClose_bart']}' class='form-control'>"
                    ]).
                    returnRow([
                        "<label for='status_bart'>Status</label>",
                        [
                            'tagName' => 'select',
                            'element' => "<select name='status_bart' id='status_bart' class='form-control'>%s</select>",
                            'value' => $result['status_bart'],
                            'query' => "SELECT statusID, status from Status WHERE status <> 'Deleted'"
                        ]
                    ])
                ]
            ];
            echo "
                <header class='container page-header'>
                    <h1 class='page-title'>Add New Deficiency</h1>
                </header>
                <main role='main' class='container main-content'>
                    <form action='recBartDef.php' method='POST' enctype='multipart/form-data'>
                        <input type='hidden' name='created_by' value='{$_SESSION['UserID']}' />
                        <h5 class='grey-bg pad'>General Information</h5>";
                        foreach ($topFields as $gridRow) {
                            print returnRow($gridRow);
                        }
            echo "
                        <h5 class='grey-bg pad'>VTA Information</h5>";
                        foreach ($vtaFields as $gridRow) {
                            print returnRow($gridRow);
                        }
            echo "
                        <h5 class='grey-bg pad'>BART Information</h5>";
                        foreach ($bartFields as $gridRow) {
                            print returnRow($gridRow);
                        }
            echo "
                        <div class='center-content'>
                            <button type='submit' class='btn btn-primary btn-lg'>Submit</button>
                            <button type='reset' class='btn btn-primary btn-lg'>Reset</button>
                        </div>
                    </form>
                </main>";
                
            echo "<pre>";
            var_dump($result);
            echo "</pre>";
        } else {
            printSqlErrorAndExit($stmt, $sql);
        }
    } else {
        printSqlErrorAndExit($stmt, $sql);
    }
    // echo "</div>";
} else {
    printSqlErrorAndExit($link, $sql);
}

$link->close();
include('fileend.php');

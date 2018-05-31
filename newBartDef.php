<?php
include('session.php');
include('SQLFunctions.php');
include('html_functions/bootstrapGrid.php');
$link = f_sqlConnect();
$title = 'SVBX - New BART Deficiency';

include('filestart.php');

if ($result = $link->query('SELECT bdPermit from users_enc where userID='.$_SESSION['UserID'])) {
    if ($row = $result->fetch_row()) {
        $bdPermit = $row[0];
    }
    $result->close();
}
if ($bdPermit) {
    $topFields = [
        [
            returnRow([
                "<label for='creator'>Creator</label>",
                "<select name='creator' id='creator' class='form-control' >
                    <option></option>
                    <option>VTA</option>
                    <option>BART</option>
                </select>"
            ]).
            returnRow([
                "<label for='next_step'>Next step</label>",
                "<input name='next_step' id='next_step' type='text' maxlength='25' class='form-control'>"
            ]).
            returnRow([
                "<label for='bic'>Ball in court</label>",
                "<select name='bic' id='bic' class='form-control'>
                    <option></option>
                    <option>VTA</option>
                    <option>BART</option>
                </select>"
            ]),
            'Descriptive_title_VTA' => [
                'label' => "<label for='Descriptive_title_VTA'>Description</label>",
                'tagName' => 'textarea',
                'element' => "<textarea name='Descriptive_title_VTA' id='Descriptive_title_VTA' class='form-control'></textarea>",
                'value' => '',
                'query' => null
            ]
        ]
    ];

    $vtaFields = [
        [
            'Root_Prob_VTA' => [
                'label' => "<label for='root_prob_vta'>Root problem</label>",
                'tagName' => 'textarea',
                'element' => "<textarea name='root_prob_vta' id='root_prob_vta' class='form-control'></textarea>",
                'value' => '',
                'query' => null
            ]
        ],
        [
            'Resolution_VTA' => [
                'label' => "<label for='resolution_vta'>Resolution</label>",
                'tagName' => 'textarea',
                'element' => "<textarea name='resolution_vta' id='resolution_vta' class='form-control'></textarea>",
                'value' => '',
                'query' => null
            ]
        ],
        [
            returnRow([
                "<label for='Status_VTA'>Status</label>",
                [
                    'tagName' => 'select',
                    'element' => "<select name='Status_VTA' id='Status_VTA' class='form-control'>%s</select>",
                    'value' => '',
                    'query' => "SELECT statusID, status from Status WHERE status <> 'Deleted'"
                ]
            ]).
            returnRow([
                "<label for='Priority_VTA'>Priority</label>",
                "<select name='Priority_VTA' id='Priority_VTA' class='form-control'>
                    <option></option>
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                </select>"
            ]).
            returnRow([
                "<label for='Agree_VTA'>Agree</label>",
                [
                    'tagName' => 'select',
                    'element' => "<select name='Agree_VTA' id='Agree_VTA' class='form-control'>%s</select>",
                    'value' => '',
                    'query' => 'SELECT agreeDisagreeID, agreeDisagreeName from agreeDisagree'
                ]
            ]).
            returnRow([
                "<label for='Safety_Cert_VTA'>Safety Certiable?</label>",
                [
                    'tagName' => 'select',
                    'element' => "<select name='Safety_Cert_VTA' id='Safety_Cert_VTA' class='form-control'>%s</select>",
                    'value' => '',
                    'query' => 'SELECT yesNoID, yesNo from YesNo'
                ]
            ]).
            returnRow([ // will need sep table
                "<label for='Attachments'>Upload attachment</label>",
                [
                    'tagName' => 'input',
                    'type' => 'file',
                    'element' => "<input name='Attachments' id='Attachments' type='file' class='form-control'>"
                ]
            ]),
            returnRow([
                [
                    'label' => "<label for='Comments_VTA'>Comment</label>",
                    'tagName' => 'textarea',
                    'element' => "<textarea name='Comments_VTA' id='Comments_VTA' class='form-control'>%s</textarea>",
                    'value' => ''
                ]
            ]).
            // comments will need sep table
            returnRow([
                "<div class='form-check form-check-inline'>
                    <label for='Resolution_disputed' class='form-check-label mr-2'>Resolution disputed</label>
                    <input name='Resolution_disputed' id='Resolution_disputed' type='checkbox' value='1' class='form-check-input'>
                </div>",
                "<div class='form-check form-check-inline'>
                    <label for='Structural' class='form-check-label mr-2'>Structural</label>
                    <input name='Structural' id='Structural' type='checkbox' value='1' class='form-check-input'>
                </div>"
            ])
        ]
    ];

    $bartFields = [
        [
            "<label for='ID_BART'>BART ID</label>
            <input name='ID_BART' id='ID_BART' type='number' class='form-control'>"
        ],
        [
            "<label for='Description_BART'>Description</label>
            <textarea name='Description_BART' id='Description_BART' class='form-control'></textarea>"
        ],
        [
            returnRow([
                "<label for='Cat1_BART'>Cat1</label>",
                "<input name='Cat1_BART' id='Cat1_BART' type='text' class='form-control'>"
            ]).
            returnRow([
                "<label for='Cat2_BART'>Cat2</label>",
                "<input name='Cat2_BART' id='Cat2_BART' type='text' class='form-control'>"
            ]).
            returnRow([
                "<label for='Cat3_BART'>Cat3</label>",
                "<input name='Cat3_BART' id='Cat3_BART' type='text' class='form-control'>"
            ]),
            returnRow([
                "<label for='Level_BART'>Level</label>",
                "<input name='Level_BART' id='Level_BART' type='text' class='form-control'>"
            ]).
            returnRow([
                "<label for='DateOpen_BART'>Date open</label>",
                "<input name='DateOpen_BART' id='DateOpen_BART' type='date' class='form-control'>"
            ]).
            returnRow([
                "<label for='DateClose_BART'>Date closed</label>",
                "<input name='DateClose_BART' id='DateClose_BART' type='date' class='form-control'>"
            ]).
            returnRow([
                "<label for='Status_BART'>Status</label>",
                [
                    'tagName' => 'select',
                    'element' => "<select name='Status_BART' id='Status_BART' class='form-control'>%s</select>",
                    'value' => '',
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
                <input type='hidden' name='created_by' value='{$_SESSION['Username']}' />
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
} else {
    header('Location: unauthorised.php');
    exit;
}

$link->close();
include('fileend.php');

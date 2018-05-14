<?php
include('session.php');
session_start();
$title = "SVBX - Update Deficiency";
$role = $_SESSION['Role'];
include('filestart.php'); 

$link = f_sqlConnect();
$link2 = f_sqlConnect();
$q = $_POST['q'];
$Def = file_get_contents("UpdateDef.sql").$q;
error_reporting(E_ALL);  
ini_set("display_errors", 1);

// $locQry = "SELECT LocationID, LocationName FROM Location ORDER BY LocationName";
// $locResult = mysqli_query($link,$locQry);
// $sysQry = "SELECT SystemID, System FROM System ORDER BY System";
// $sysResult = $link->query($sysQry);
// $sql3 = "SELECT StatusID, Status FROM Status WHERE StatusID <> 3 ORDER BY StatusID";
// $list3 = mysqli_query($link,$sql3);
// $sql4 = "SELECT SeverityID, SeverityName FROM Severity ORDER BY SeverityName";
// $list4 = mysqli_query($link,$sql4);
// $sql5 = "SELECT EviTypeID, EviType FROM EvidenceType ORDER BY EviType";
// $list5 = mysqli_query($link,$sql5);
// $sql6 = "SELECT ReqByID, RequiredBy FROM RequiredBy ORDER BY RequiredBy";
// $list6 = mysqli_query($link,$sql6);
// $sql7 = "SELECT RepoID, Repo FROM Repo ORDER BY Repo";
// $list7 = mysqli_query($link,$sql7);
// $yesNoQry = "SELECT YesNoID, YesNo FROM YesNo ORDER BY YesNo";
// $yesNoResult = $link->query($yesNoQry);

function returnSelectInput($cnxn, $data) {
    $selectEl = "<{$data['tagName']} name='{$data['name']}' id='{$data['id']}' class='form-control'>";
    $options = "<option value=''></option>";
    // if val @ [query] is a string, use it query db
    // if val @ [query] is a sql query result, use it
    $result = is_string($data['query']) ? $cnxn->query($data['query']) : $data['query'];
    if ($result) {
        while ($row = $result->fetch_row()) {
            $selected = $row[0] == $data['value'] ? ' selected' : ' data-notSelected';
            $options .= "<option value='{$row[0]}'{$selected}>{$row[1]}</option>";
        }
    } elseif ($cnxn->error) {
        $options .= "<option selected>{$cnxn->error}</option>";
    } else $options .= "<option selected>There was a problem with the query</option>";
    $selectEl .= $options;
    $selectEl .= "</select>";
    $result->close();
    return $selectEl;
}

function returnTextInput($cnxn, $data) {
    $inputEl = sprintf($data['element'], $data['value']);
    return $inputEl;
}

function returnDateInput($cnxn, $data) {
    $dateEl = sprintf($data['element'], $data['value']);
    return $dateEl;
}

function returnTextarea($cnxn, $data) {
    $textarea = sprintf($data['element'], $data['value']);
    return $textarea;
}

function returnCol($cnxn, $element, $wd) {
    $col = "<div class='col-md-$wd'>";
    $col .= $element['label'];
    if ($element['tagName'] === 'select') {
        $col .= returnSelectInput($cnxn, $element);
    } elseif ($element['tagName'] === 'input') {
        if ($element['type'] === 'text') {
            $col .= returnTextInput($cnxn, $element);
        } elseif ($element['type'] === 'date') {
            $col .= returnDateInput($cnxn, $element);
        }
    } elseif ($element['tagName'] === 'textarea') {
        $col .= returnTextarea($cnxn, $element);
    }
    $col .= "</div>";
    return $col;
}

function returnRow($cnxn, $elements) {
    $elRow = "<div class='row'>";
    $numEls = count($elements);
    // if number of elements don't divide evenly by 12 substract out the remainder
    // this number will be added to the last col
    $extraCols = 12 % $numEls;
    $colWd = 12 / ($numEls - $extraCols);
    foreach ($elements as $el) {
        $elRow .= returnCol($cnxn, $el, $colWd);
    }
    $elRow .= "</div>";
    return $elRow;
}

$formCtrls = [
    'SafetyCert' => [
        "label" => "<label for='SafetyCert'>Safety Certifiable</label>",
        "tagName" => 'select',
        "type" => '',
        "name" => 'SafetyCert',
        "id" => 'SafetyCert',
        "query" => "SELECT YesNoID, YesNo FROM YesNo ORDER BY YesNo"
    ],
    'SystemAffected' => [
        "label" => "<label for='SystemAffected'>System Affected</label>",
        "tagName" => 'select',
        "type" => '',
        "name" => 'SystemAffected',
        "id" => 'SystemAffected',
        "query" => "SELECT SystemID, System FROM System ORDER BY System"
    ],
    'LocationName' => [
        "label" => "<label for='LocationName'>General Location</label>",
        "tagName" => 'select',
        "type" => '',
        "name" => 'LocationName',
        "id" => 'LocationName',
        "query" => "SELECT LocationID, LocationName FROM Location ORDER BY LocationName"
    ],
    'SpecLoc' => [
        "label" => "<label for='SpecLoc'>Specific Location</label>",
        "tagName" => "input",
        "element" => "<input type='text' name='SpecLoc' id='SpecLoc' value='%s' class='form-control'>",
        "type" => 'text',
        "name" => 'SpecLoc',
        "id" => 'SpecLoc',
        "query" => null
    ],
    'Status' => [
        "label" => "<label for='Status'>Status</label>",
        "tagName" => "select",
        "element" => "<select name='SpecLoc' id='SpecLoc' class='form-control'>",
        "type" => null,
        "name" => 'Status',
        "id" => 'Status',
        "query" => "SELECT StatusID, Status FROM Status WHERE StatusID <> 3 ORDER BY StatusID"
    ],
    'SeverityName' => [
        "label" => "<label for='SeverityName'>Severity</label>",
        "tagName" => "select",
        "element" => "<select name='SeverityName' id='SeverityName' class='form-control'>",
        "type" => null,
        "name" => 'SeverityName',
        "id" => 'SeverityName',
        "query" => "SELECT SeverityID, SeverityName FROM Severity ORDER BY SeverityName"
    ],
    'DueDate' => [
        "label" => "<label for='DueDate'>To be resolved by</label>",
        "tagName" => "input",
        "element" => "<input type='date' name='DueDate' id='DueDate' value='%s' class='form-control'>",
        "type" => 'date',
        "name" => 'DueDate',
        "id" => 'DueDate',
        "query" => null
    ],
    'RequiredBy' => [
        "label" => "<label for='RequiredBy'>Required for</label>",
        "tagName" => "select",
        "element" => "<select name='RequiredBy' id='RequiredBy' class='form-control'>",
        "type" => null,
        "name" => 'RequiredBy',
        "id" => 'RequiredBy',
        "query" => "SELECT ReqByID, RequiredBy FROM RequiredBy ORDER BY RequiredBy"
    ],
    'GroupToResolve' =>[
        "label" => "<label for='GroupToResolve'>Group to Resolve</label>",
        "tagName" => "select",
        "element" => "<select name='GroupToResolve' id='GroupToResolve' class='form-control'>",
        "type" => null,
        "name" => 'GroupToResolve',
        "id" => 'GroupToResolve',
        "query" => "SELECT SystemID, System FROM System ORDER BY System"
    ],
    'IdentifiedBy' => [
        "label" => "<label for='IdentifiedBy'>Identified By</label>",
        "tagName" => "input",
        "element" => "<input type='text' name='IdentifiedBy' id='IdentifiedBy' class='form-control' value='%s'>",
        "type" => 'text',
        "name" => 'IdentifiedBy',
        "id" => 'IdentifiedBy',
        "query" => null
    ],
    'Description' => [
        "label" => "<label for='Description'>Deficiency Description</label>",
        "tagName" => "textarea",
        "element" => "<textarea name='Description' id='Description' class='form-control' maxlength='1000'>%s</textarea>",
        "type" => null,
        "name" => 'Description',
        "id" => 'Description',
        "query" => null
    ],
    'Spec' => [
        "label" => "<label for='Spec'>Spec or Code</label>",
        "tagName" => "input",
        "element" => "<input type='text' name='Spec' id='Spec' class='form-control'>",
        "type" => 'text',
        "name" => 'Spec',
        "id" => 'Spec',
        "query" => null
    ],
    'ActionOwner' => [
        "label" => "<label for='ActionOwner'>Action Owner</label>",
        "tagName" => "input",
        "element" => "<input type='text' name='ActionOwner' id='ActionOwner' class='form-control'>",
        "type" => 'text',
        "name" => 'ActionOwner',
        "id" => 'ActionOwner',
        "query" => null
    ],
    'OldID' => [
        "label" => "<label for='OldID'>Old Id</label>",
        "tagName" => "input",
        "element" => "<input type='text' name='OldID' id='OldID' class='form-control'>",
        "type" => 'text',
        "name" => 'OldID',
        "id" => 'OldID',
        "query" => null
    ],
    'comments' => [
        "label" => "<label for='comments'>More Information</label>",
        "tagName" => "textarea",
        "element" => "<textarea name='comments' id='comments' class='form-control' maxlength='1000'>%s</textarea>",
        "type" => null,
        "name" => 'comments',
        "id" => 'comments',
        "query" => null
    ],
    'EviType' => [
        "label" => "<label for='EviType'>Evidence Type</label>",
        "tagName" => 'select',
        'element' => "<select name='EviType' id='EviType' class='form-control'>%s</select>",
        "type" => '',
        "name" => 'EviType',
        "id" => 'EviType',
        "query" => "SELECT EviTypeID, EviType FROM EvidenceType ORDER BY EviType"
    ],
    'Repo' => [
        'label' => "<label for='Repo'>Evidence Repository</label>",
        'tagName' => 'select',
        'element' => "<select name='Repo' id='Repo' class='form-control'>%s</select>",
        'type' => '',
        'name' => 'Repo',
        'id' => 'Repo',
        'query' => "SELECT RepoID, Repo FROM Repo ORDER BY Repo"
    ],
    'EvidenceLink' => [
        'label' => "<label for='EvidenceLink'>Repository Number</label>",
        'tagName' => "input",
        'element' => "<input type='text' name='EvidenceLink' id='EvidenceLink' class='form-control'>",
        'type' => 'text',
        'name' => 'EvidenceLink',
        'id' => 'EvidenceLink',
        'query' => null
    ],
    'ClosureComments' => [
        "label" => "<label for='ClosureComments'>Closure Comments</label>",
        "tagName" => "textarea",
        "element" => "<textarea name='ClosureComments' id='ClosureComments' class='form-control' maxlength='1000'>%s</textarea>",
        "type" => null,
        "name" => 'ClosureComments',
        "id" => 'ClosureComments',
        "query" => null
    ]
];

    echo "
        <header class='container page-header'>
            <h1 class='page-title'>Update Deficiency ".$q."</h1>
        </header>
        <main class='container main-content'>";

    if($stmt = $link->prepare($Def)) {
        $stmt->execute();
        $stmt->bind_result(
            $formCtrls['OldID']['value'], 
            $formCtrls['LocationName']['value'], 
            $formCtrls['SpecLoc']['value'], 
            $formCtrls['SeverityName']['value'], 
            $formCtrls['Description']['value'], 
            $formCtrls['Spec']['value'],
            $DateCreated, 
            $formCtrls['Status']['value'],
            $formCtrls['IdentifiedBy']['value'], 
            $formCtrls['SystemAffected']['value'], 
            $formCtrls['GroupToResolve']['value'], 
            $formCtrls['ActionOwner']['value'], 
            $formCtrls['EviType']['value'], 
            $formCtrls['EvidenceLink']['value'], 
            $DateClosed, 
            $LastUpdated, 
            $Updated_by, 
            $formCtrls['comments']['value'], 
            $formCtrls['RequiredBy']['value'], 
            $formCtrls['Repo']['value'], 
            $Pics, 
            $formCtrls['ClosureComments']['value'],
            $formCtrls['DueDate']['value'],
            $formCtrls['SafetyCert']['value']);
                
        while ($stmt->fetch()) {
            echo "
                <form action='UpdateDefCommit.php' method='POST' onsubmit='' />
                    <input type='hidden' name='DefID' value='$q'>
                    <div class='row'>
                        <div class='col-12'>
                            <p>Deficiency No. $q</p>
                        </div>
                    </div>";
            echo returnRow($link2, array_slice($formCtrls, 0, 2));
                    // [
                        //     [
                        //         "label" => 'Safety Certifiable',
                        //         "tagName" => 'select',
                        //         "type" => '',
                        //         "name" => 'SafetyCert',
                        //         "id" => 'SafetyCert',
                        //         "value" => $SafetyCert,
                        //         "query" => "SELECT YesNoID, YesNo FROM YesNo ORDER BY YesNo"
                        //     ],
                        //     [
                        //         "label" => 'System Affected',
                        //         "tagName" => 'select',
                        //         "type" => '',
                        //         "name" => 'SystemAffected',
                        //         "id" => 'SystemAffected',
                        //         "value" => $SystemAffected,
                        //         "query" => "SELECT SystemID, System FROM System ORDER BY System"
                        //     ]
                        // ]);
            echo returnRow($link2, array_slice($formCtrls, 2, 2));
                    // [
                        //     [
                        //         "label" => 'General Location',
                        //         "tagName" => 'select',
                        //         "type" => '',
                        //         "name" => 'LocationName',
                        //         "id" => 'LocationName',
                        //         "value" => $Location,
                        //         "query" => "SELECT LocationID, LocationName FROM Location ORDER BY LocationName"
                        //     ],
                        //     [
                        //         "label" => "<label for='SpecLoc'>'Specific Location'</label>",
                        //         "tagName" => "input",
                        //         "element" => "<input type='text' name='SpecLoc' id='SpecLoc' value='%s' class='form-control'>",
                        //         "type" => 'text',
                        //         "name" => 'SpecLoc',
                        //         "id" => 'SpecLoc',
                        //         "query" => null
                        //     ]
                        // ]);
            echo returnRow($link2, array_slice($formCtrls, 4, 2));
            echo returnRow($link2, array_slice($formCtrls, 6, 2));
            echo returnRow($link2, array_slice($formCtrls, 8, 2));
            echo returnRow($link2, array_slice($formCtrls, 10, 1));
            
            echo "<h5>Optional Information</h5>";
            echo returnRow($link2, array_slice($formCtrls, 11, 3));
            echo returnRow($link2, array_slice($formCtrls, 14, 1));
            
            echo "<h5>Closure Information</h5>";
            echo returnRow($link2, array_slice($formCtrls, 15, 3));
            echo returnRow($link2, array_slice($formCtrls, 18, 1));
echo "                    
            <input type='submit' value='submit' class='btn btn-primary btn-lg'/>
            <input type='reset' value='reset' class='btn btn-primary btn-lg' />
        </form>";
        if ($role === 'S') {
            echo "
                <form action='DeleteDef.php' method='POST' onsubmit='' onclick='return confirm(`ARE YOU SURE? Deficiencies should not be deleted, your deletion will be logged.`)'/>
                    <button type='submit' name='q' value='$q' class='btn btn-primary btn-lg'>delete</button>
                </form>";
        }

        echo "</main>";
        }
    } else {  
        echo "<br>Unable to connect<br>";
        exit();  
    }
    $link->close();
    $link2->close();
    include('fileend.php');
?>
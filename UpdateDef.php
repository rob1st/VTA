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
$sql5 = "SELECT EviTypeID, EviType FROM EvidenceType ORDER BY EviType";
$list5 = mysqli_query($link,$sql5);
// $sql6 = "SELECT ReqByID, RequiredBy FROM RequiredBy ORDER BY RequiredBy";
// $list6 = mysqli_query($link,$sql6);
$sql7 = "SELECT RepoID, Repo FROM Repo ORDER BY Repo";
$list7 = mysqli_query($link,$sql7);
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
            $OldID, 
            $formCtrls['LocationName']['value'], 
            $formCtrls['SpecLoc']['value'], 
            $formCtrls['SeverityName']['value'], 
            $Description, 
            $Spec,
            $DateCreated, 
            $formCtrls['Status']['value'],
            $IdentifiedBy, 
            $formCtrls['SystemAffected']['value'], 
            $GroupToResolve, 
            $ActionOwner, 
            $EvidenceType, 
            $EvidenceLink, 
            $DateClosed, 
            $LastUpdated, 
            $Updated_by, 
            $Comments, 
            $formCtrls['RequiredBy']['value'], 
            $Repo, 
            $Pics, 
            $ClosureComments, 
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
    
    // echo "      
    //                     <p>To be resolved by:</p>
                    
    //                     <input type='date' name='DueDate' id='defdd' value='$DueDate' required/>
                    
    //                     <p>Required for:</p>
    //                 <select name='RequiredBy' value='".$RequiredBy."' id='defdd'>
    //                     <option value=''></option>";
    //                     if(is_array($list6) || is_object($list6)) {
    //                     foreach($list6 as $row) {
    //                         echo "<option value='$row[ReqByID]'";
    //                             if($row['ReqByID'] == $RequiredBy) {
    //                                 echo " selected>$row[RequiredBy]</option>";
    //                                 } else { echo ">$row[RequiredBy]</option>";
    //                             }
    //                     }
    //                     }
    echo "
                    <p>Group to Resolve:</p>
                    <select name='GroupToResolve' value='".$GroupToResolve."' id='defdd'>
                        <option value=''></option>";
                        if(is_array($sysResult) || is_object($sysResult)) {
                        foreach($sysResult as $row) {
                            echo "<option value='$row[SystemID]'";
                                if($row['SystemID'] == $GroupToResolve) {
                                    echo " selected>$row[System]</option>";
                                    } else { echo ">$row[System]</option>";
                                }
                        }
                        }
    echo "          <p>Identified By:</p>
                    <input type='text' name='IdentifiedBy' value='".$IdentifiedBy."' max='24' id='defdd'/>
                
                <p>Deficiency Description</p>
                <textarea type='message' rows='5' cols='99%' name='Description' max='1000'>$Description</textarea>
                
                <p>Optional Information</p>
                <p>Spec or Code:</p>
                <input type='text' name='Spec' value='".$Spec."' max='24'/>
                
                <p>Action Owner:</p>
                <input type='text' name='ActionOwner' value='".$ActionOwner."' max='24'/>
                <p>Old Id:</p>
                <input type='text' name='OldID' value='".$OldID."' max='24'/>

                        <p>More Information</p>
                        <textarea type='message'  rows='5' cols='99%' name='comments' max='1000'>$Comments</textarea>
                    
                    <p>Closure Information</p>
                    <p>Evidence Type:</p>
                    <select name='EviType' value='".$EvidenceType."'>
                        <option value=''></option>";
                        if(is_array($list5) || is_object($list5)) {
                        foreach($list5 as $row) {
                            echo "<option value='$row[EviTypeID]'";
                                if($row['EviTypeID'] == $EvidenceType) {
                                    echo " selected>$row[EviType]</option>";
                                    } else { echo ">$row[EviType]</option>";
                                }
                        }
                        }
    echo "          
                        <p>Evidence Repository:</p>
                    
                    <select name='Repo' value='".$Repo."'>
                        <option value=''></option>";
                        if(is_array($list7) || is_object($list7)) {
                        foreach($list7 as $row) {
                            echo "<option value='$row[RepoID]'";
                                if($row['RepoID'] == $Repo) {
                                    echo " selected>$row[Repo]</option>";
                                    } else { echo ">$row[Repo]</option>";
                                }
                        }
                        }
    echo "                
                        <p>Repository Number</p>
                    
                        <input type='text' name='EvidenceLink' max='255' value='$EvidenceLink' id='defdd'/>
                    
                        <p>Closure Comments</p>
                    
                        <textarea type='message'  rows='5' cols='99%' name='ClosureComments' max='1000'>$ClosureComments</textarea>
                    
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
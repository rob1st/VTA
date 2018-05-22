<?php
include('session.php');
include('html_functions/bootstrapGrid.php');
$defID = $_GET['defID'];
$Role = $_SESSION['Role'];
$title = "SVBX - Deficiency No".$defID;
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
include('filestart.php'); 
$link = f_sqlConnect();
$Def = file_get_contents("ViewDef.sql").$defID;

$keyStr = "<span>%s</span>";
$valStr = "<span class='d-block full-width pad-less thin-grey-border border-radius'>%s</span>";

    if($stmt = $link->prepare($Def)) {
        $stmt->execute();  
        $stmt->bind_result(
                $OldID, 
                $Location, 
                $SpecLoc, 
                $Severity, 
                $Description, 
                $Spec, 
                $DateCreated, 
                $Status, 
                $IdentifiedBy, 
                $SystemAffected, 
                $GroupToResolve, 
                $ActionOwner, 
                $EvidenceType, 
                $EvidenceLink, 
                $DateClosed, 
                $LastUpdated, 
                $Updated_by, 
                $Created_by, 
                $Comments,
                $RequiredBy,
                $contract,
                $Repo,
                $ClosureComments,
                $DueDate,
                $SafetyCert,
                $defType);  
        while ($stmt->fetch()) {
            $requiredRows = [
                [
                    sprintf($keyStr, 'Safety Certifiable'),
                    sprintf($valStr, $SafetyCert),
                    sprintf($keyStr, 'System Affected'),
                    sprintf($valStr, $SystemAffected)
                ],
                [
                    sprintf($keyStr, 'General Location'),
                    sprintf($valStr, $Location),
                    sprintf($keyStr, 'Specific Location'),
                    sprintf($valStr, $SpecLoc)
                ],
                [
                    sprintf($keyStr, 'Status'),
                    sprintf($valStr, $Status),
                    sprintf($keyStr, 'Severity'),
                    sprintf($valStr, $Severity)
                ],
                [
                    sprintf($keyStr, 'Due Date'),
                    sprintf($valStr, $DueDate),
                    sprintf($keyStr, 'Group to resolve'),
                    sprintf($valStr, $GroupToResolve)
                ],
                [
                    sprintf($keyStr, 'Resolution required by'),
                    sprintf($valStr, $RequiredBy),
                    sprintf($keyStr, 'Contract'),
                    sprintf($valStr, $contract)
                ],
                [
                    sprintf($keyStr, 'Identified By'),
                    sprintf($valStr, $IdentifiedBy),
                    sprintf($keyStr, 'Deficiency type'),
                    sprintf($valStr, $defType)
                ],
                [
                    sprintf($keyStr, 'Deficiency description').sprintf($valStr, $Description)
                ]
            ];
            
            $optionalRows = [
                [
                    sprintf($keyStr, 'Spec or Code'),
                    sprintf($valStr, $Spec),
                    sprintf($keyStr, 'Action Owner'),
                    sprintf($valStr, $ActionOwner),
                    sprintf($keyStr, 'Old Id'),
                    sprintf($valStr, $OldID)
                ],
                [
                    sprintf($keyStr, 'Additional information').sprintf($valStr, $Comments)
                ]
            ];
            
            $closureRows = [
                [
                    sprintf($keyStr, 'Evidence Type'),
                    sprintf($valStr, $EvidenceType),
                    sprintf($keyStr, 'Evidence Repository'),
                    sprintf($valStr, $Repo),
                    sprintf($keyStr, 'Repository No.'),
                    sprintf($valStr, $EvidenceLink)
                ],
                [
                    sprintf($keyStr, 'Closure Comments').sprintf($valStr, $ClosureComments)
                ]
            ];
            
            $modHistory = [
                [
                    sprintf($keyStr, 'Date Created'),
                    sprintf($valStr, $DateCreated),
                    sprintf($keyStr, 'Created by'),
                    sprintf($valStr, $Created_by)
                ],
                [
                    sprintf($keyStr, 'Last Updated'),
                    sprintf($valStr, $LastUpdated),
                    sprintf($keyStr, 'Updated by'),
                    sprintf($valStr, $Updated_by)
                ]
            ];

            if($Status == "Open") {
                $color = "bg-red text-white";
            } else {
                $color = "bg-success text-white"; 
            }
            echo "
                <header class='container page-header'>
                    <h1 class='page-title $color pad'>Deficiency No. $defID</h1>
                </header>
                <main class='container main-content'>
                    <div class='row'>
                        <div class='col-12'>
                            <h5 class='grey-bg pad'>Required Information</h5>
                        </div>
                    </div>";
                    foreach ($requiredRows as $gridRow) {
                        $options = count($gridRow) === 1 ? ['colWd' => 6] : [];
                        print returnRow($gridRow, $options);
                    }
                    print "<h5 class='grey-bg pad'>Optional Information</h5>";
                    foreach ($optionalRows as $gridRow) {
                        $options = count($gridRow) === 1 ? ['colWd' => 6] : [];
                        print returnRow($gridRow, $options);
                    }
                    print "<h5 class='grey-bg pad'>Closure Information</h5>";
                    foreach ($closureRows as $gridRow) {
                        $options = count($gridRow) === 1 ? ['colWd' => 6] : [];
                        print returnRow($gridRow, $options);
                    }
                    print "<h5 class='grey-bg pad'>Modification Details</h5>";
                    foreach ($modHistory as $gridRow) {
                        $options = count($gridRow) === 1 ? ['colWd' => 6] : [];
                        print returnRow($gridRow, $options);
                    }
                            
        }
        $stmt->close();
        
        // show photos linked to this Def
        if ($stmt = $link->prepare("SELECT pathToFile FROM CDL_pics WHERE defID=?")) {
            $stmt->bind_param('i', $defID);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($pathToFile);
            
            if ($count = $stmt->num_rows) {
                $collapseCtrl = "<h5 class='grey-bg pad'><a data-toggle='collapse' href='#defPics' role='button' aria-expanded='false' aria-controls='defPics' class='collapsed'>Photos<i class='typcn typcn-arrow-sorted-down'></i></a></h5>";
                $photoSection = sprintf("%s<section id='defPics' class='collapse item-margin-bottom'>", $collapseCtrl)."%s</section>";
                $curRow = "<div class='row item-margin-bottom'>%s</div>";
            
                $i = 0;
                $j = 1;
                while ($stmt->fetch()) {
                    $img = sprintf("<img src='%s' alt='photo related to deficiency number %s'>", $pathToFile, $defID);
                    $col = sprintf("<div class='col-md-4 text-center item-margin-bottom'>%s</div>", $img);
                    $marker = $j < $count ? '%s' : '';
                    
                    if ($i < 2) {
                        // if this is not 3rd col in row, append an extra format marker '%s' after col
                        $curRow = sprintf($curRow, $col.$marker);
                        // if this is the last photo in resultset, append row to section
                        if ($j >= $count) {
                            $photoSection = sprintf($photoSection, $curRow);
                        }
                        $i++;
                    }
                    // if this is 3rd col in row append row to section
                    else {
                        // if this is not the last photo is resultset append a str format marker, '%s', to row before appending row to section
                        $curRow = sprintf($curRow, $col).$marker;
                        $photoSection = sprintf($photoSection, $curRow);
                        // reset row string
                        $curRow = "<div class='row item-margin-bottom'>%s</div>";
                        $i = 0;
                    }
                    $j++;
                }
                echo $photoSection;
            }
            $stmt->close();
        }
        
        // if Role has permission level show Update and Clone buttons
        if($Role == 'S' OR $Role == 'A' OR $Role == 'U') {
            echo "
                <div style='display: flex; align-items: center; justify-content: center; hspace:20; margin-bottom:3rem'>
                    <a href='UpdateDef.php?defID=$defID' class='btn btn-primary btn-lg'>Update</a>
                    <form action='CloneDef.php' method='POST' onsubmit='' style='text-align:center'>
                        <div style='width:5px; height:auto; display:inline-block'></div>
                        <input type='hidden' name='q' value='$defID'/>
                        <input type='submit' value='Clone' class='btn btn-primary btn-lg'  />
                    </form>
                </div>";
        }
        echo "</main>";
    } else {  
        echo "
        <div class='container page-header'>
        <h5>Unable to connect</h5>";
        echo "<pre>";
        echo $link->error;
        echo "</pre>";
        echo "<p>$Def</p>";
        echo "</div>";
      exit();  
    } 
    include('fileend.php');
    $link->close(); 
?>
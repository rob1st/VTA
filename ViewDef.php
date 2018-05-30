<?php
include('session.php');
include('html_functions/bootstrapGrid.php');
include('html_functions/htmlFuncs.php');
include('error_handling/sqlErrors.php');
$defID = $_GET['defID'];
$bartID = $_GET['bartDefID'];
$Role = $_SESSION['Role'];
$title = "SVBX - Deficiency No".$defID;
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
include('filestart.php'); 
$link = f_sqlConnect();

// $spanStr = "<span>%s</span>";
$labelStr = "<p>%s</p>";
$checkbox = "<input type='checkbox' value='%s' class='form-control'>";
$fakeInputStr = "<p class='full-width pad-less thin-grey-border border-radius fake-input'>%s</p>";
$emptyFakeInputStr = "<p class='full-width pad-less thin-grey-border border-radius grey-bg fake-input'>%s</p>";

function returnFakeInputStr($val) {
    $str = "<span class='d-block full-width pad-less thin-grey-border border-radius'>%s</span>";
    $altStr = "<span class='d-block full-width pad-less thin-grey-border border-radius grey-bg fake-input'>%s</span>";
    return returnHtmlForVal($val, $str, $altStr);
}

if ($defID) {
    $sql = file_get_contents("ViewDef.sql").$defID;
    
    if($stmt = $link->prepare($sql)) {
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
                    sprintf($labelStr, 'Safety Certifiable'),
                    sprintf($fakeInputStr, $SafetyCert),
                    sprintf($labelStr, 'System Affected'),
                    sprintf($fakeInputStr, $SystemAffected)
                ],
                [
                    sprintf($labelStr, 'General Location'),
                    sprintf($fakeInputStr, $Location),
                    sprintf($labelStr, 'Specific Location'),
                    sprintf($fakeInputStr, stripcslashes($SpecLoc))
                ],
                [
                    sprintf($labelStr, 'Status'),
                    sprintf($fakeInputStr, $Status),
                    sprintf($labelStr, 'Severity'),
                    sprintf($fakeInputStr, $Severity)
                ],
                [
                    sprintf($labelStr, 'Due Date'),
                    sprintf($fakeInputStr, $DueDate),
                    sprintf($labelStr, 'Group to resolve'),
                    sprintf($fakeInputStr, $GroupToResolve)
                ],
                [
                    sprintf($labelStr, 'Resolution required by'),
                    sprintf($fakeInputStr, $RequiredBy),
                    sprintf($labelStr, 'Contract'),
                    sprintf($fakeInputStr, $contract)
                ],
                [
                    sprintf($labelStr, 'Identified By'),
                    sprintf($fakeInputStr, stripcslashes($IdentifiedBy)),
                    sprintf($labelStr, 'Deficiency type'),
                    sprintf($fakeInputStr, $defType)
                ],
                [
                    sprintf($labelStr, 'Deficiency description').sprintf($fakeInputStr, stripcslashes($Description))
                ]
            ];
            
            $optionalRows = [
                [
                    sprintf($labelStr, 'Spec or Code'),
                    returnFakeInputStr(stripcslashes($Spec)),
                    sprintf($labelStr, 'Action Owner'),
                    returnFakeInputStr(stripcslashes($ActionOwner)),
                    sprintf($labelStr, 'Old Id'),
                    returnFakeInputStr(stripcslashes($OldID))
                ],
                [
                    sprintf($labelStr, 'More information').returnFakeInputStr(stripcslashes($Comments))
                ]
            ];
            
            $closureRows = [
                [
                    sprintf($labelStr, 'Evidence Type'),
                    returnFakeInputStr($EvidenceType),
                    sprintf($labelStr, 'Evidence Repository'),
                    returnFakeInputStr($Repo),
                    sprintf($labelStr, 'Repository No.'),
                    returnFakeInputStr(stripcslashes($EvidenceLink))
                ],
                [
                    sprintf($labelStr, 'Closure comments').returnFakeInputStr(stripcslashes($ClosureComments))
                ]
            ];
            
            $modHistory = [
                [
                    sprintf($labelStr, 'Date Created'),
                    sprintf($labelStr, $DateCreated),
                    sprintf($labelStr, 'Created by'),
                    sprintf($labelStr, $Created_by)
                ],
                [
                    sprintf($labelStr, 'Last Updated'),
                    sprintf($labelStr, $LastUpdated),
                    sprintf($labelStr, 'Updated by'),
                    sprintf($labelStr, $Updated_by)
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
        } else {
            printSqlErrorAndExit($link, $sql);
        }
        // if Role has permission level show Update and Clone buttons
        if($Role == 'S' OR $Role == 'A' OR $Role == 'U') {
            echo "
                <div style='display: flex; align-items: center; justify-content: center; hspace:20; margin-bottom:3rem'>
                    <a href='UpdateDef.php?defID=$defID' class='btn btn-primary btn-lg'>Update</a>
                    <form action='CloneDef.php' method='POST' onsubmit='' style='text-align:center'>
                        <div style='width:5px; height:auto; display:inline-block'></div>
                        <input type='hidden' name='defID' value='$defID'/>
                        <input type='submit' value='Clone' class='btn btn-primary btn-lg'  />
                    </form>
                </div>";
        }
        echo "</main>";
    } else {  
        printSqlErrorAndExit($link, $sql);
    } 
} elseif ($bartID) {
    if ($result = $link->query('SELECT bdPermit from users_enc where userID='.$_SESSION['UserID'])) {
        if ($row = $result->fetch_row()) {
            $bdPermit = $row[0];
        }
        $result->close();
    }
    if ($bdPermit) {
        // render View for bartDef
        // $fakeInputStr = "<span class='full-width pad-less thin-grey-border border-radius fake-input'>%s</span>";
        $result = [];
        $sql = 'SELECT '.file_get_contents('bartdl.sql')." WHERE id=?";
        
        if ($stmt = $link->prepare($sql)) {
            if (!$stmt->bind_param('i', $bartID)) printSqlErrorAndExit($stmt, $sql);
            
            if (!$stmt->execute()) printSqlErrorAndExit($stmt, $sql);
            
            $meta = $stmt->result_metadata(); 
            while ($field = $meta->fetch_field()) { 
                $params[] = &$row[$field->name]; 
            }

            call_user_func_array(array($stmt, 'bind_result'), $params); 

            while ($stmt->fetch()) { 
                foreach($row as $key => $val) 
                { 
                    $c[$key] = $val; 
                } 
                $result = $c; 
            } 
    
            $topFields = [
                [
                    returnRow([ sprintf($labelStr, 'ID'), sprintf($fakeInputStr, $result['ID']) ]).
                    returnRow([ sprintf($labelStr, 'Creator'), sprintf($fakeInputStr, $result['Creator']) ]).
                    returnRow([ sprintf($labelStr, 'Joint status'), sprintf($fakeInputStr, $result['Status_VTA']) ]).
                    returnRow([ sprintf($labelStr, 'Next_Step'), sprintf($fakeInputStr, $result['Next_Step']) ]).
                    returnRow([ sprintf($labelStr, 'BIC'), sprintf($fakeInputStr, $result['BIC']) ]),
                    sprintf($labelStr, 'Descriptive_title_VTA').sprintf($fakeInputStr, $result['Descriptive_title_VTA'])
                ]
            ];
        
            $vtaFields = [
                'Root_Prob_VTA' => [ sprintf($labelStr, 'Root_Prob_VTA').sprintf($labelStr, sprintf($fakeInputStr, $result['Root_Prob_VTA'])) ],
                'Resolution_VTA' => [ sprintf($labelStr, 'Resolution_VTA').sprintf($labelStr, sprintf($fakeInputStr, $result['Resolution_VTA'])) ],
                [
                    returnRow([ sprintf($labelStr, 'Status_VTA'), sprintf($fakeInputStr, $result['Status_VTA']) ]).
                    returnRow([ sprintf($labelStr, 'Priority_VTA'), sprintf($fakeInputStr, $result['Priority_VTA']) ]).
                    returnRow([ sprintf($labelStr, 'Agree_VTA'), sprintf($fakeInputStr, $result['Agree_VTA']) ]).
                    returnRow([ sprintf($labelStr, 'Safety_Cert_VTA'), sprintf($fakeInputStr, $result['Safety_Cert_VTA']) ]).
                    returnRow([ sprintf($labelStr, 'Attachments'), sprintf($fakeInputStr, $result['Attachments']) ]), // will need sep table
                    sprintf($labelStr, 'Comments_VTA').sprintf($fakeInputStr, $result['Comments_VTA']). // new comments
                    // comments will need sep table
                    returnRow([
                        sprintf($labelStr, 'Resolution_disputed'), sprintf($checkbox, $result['Resolution_disputed']),
                        sprintf($labelStr, 'Structural'), sprintf($checkbox, $result['Structural'])
                    ])
                ]
            ];
        
            $bartFields = [
                [
                    returnRow([ sprintf($labelStr, 'ID_BART'), sprintf($fakeInputStr, $result['ID_BART']) ]),
                ],
                [
                    returnRow([ sprintf($labelStr, 'Description_BART'), sprintf($fakeInputStr, $result['Description_BART']) ])
                ],
                [
                    returnRow([ sprintf($labelStr, 'Cat1_BART'), sprintf($fakeInputStr, $result['Cat1_BART']) ]).
                    returnRow([ sprintf($labelStr, 'Cat2_BART'), sprintf($fakeInputStr, $result['Cat2_BART']) ]).
                    returnRow([ sprintf($labelStr, 'Cat3_BART'), sprintf($fakeInputStr, $result['Cat3_BART']) ]),
                    returnRow([ sprintf($labelStr, 'Level_BART'), sprintf($fakeInputStr, $result['Level_BART']) ]).
                    returnRow([ sprintf($labelStr, 'DateOpen_BART'), sprintf($fakeInputStr, $result['DateOpen_BART']) ]).
                    returnRow([ sprintf($labelStr, 'DateClose_BART'), sprintf($fakeInputStr, $result['DateClose_BART']) ]).
                    returnRow([ sprintf($labelStr, 'Status_BART'), sprintf($fakeInputStr, $result['Status_BART']) ])
                ]
            ];
        
            $stmt->close();
            // print "<header class='container page-header'>
            //     <a class='btn' role='button' data-toggle='collapse' href='#varDumpResult' aria-expanded='false' aria-controls='varDumpResult'>Show result</a>
            //     <div id='varDumpResult' class='collapse' class='page-header'><pre class='text-success'>";
            // var_dump($result);
            // print "</pre></div></header>";
            
            if($result['Status_VTA'] === "Closed") {
                $color = "bg-success text-white";
            } else {
                $color = "bg-red text-white";
            }
            
            print "
                <header class='container page-header'>
                    <h1 class='page-title $color pad'>Deficiency No. $bartID</h1>
                </header>
                <main class='container main-content'>";
            foreach ($topFields as $gridRow) {
                print returnRow($gridRow);
            }
            print "<h5 class='grey-bg pad'>VTA Information</h5>";
            foreach ($vtaFields as $gridRow) {
                print returnRow($gridRow);
            }
            print "<h5 class='grey-bg pad'>BART Information</h5>";
            foreach ($bartFields as $gridRow) {
                print returnRow($gridRow);
            }
            print "</main>";
            // print "<header class='page-header'><h4 class='text-success'>&darr; BART def view will go here &darr;</h4></header>";
        } else printSqlErrorAndExit($link, $sql);
    }
}
include('fileend.php');
$link->close(); 
?>
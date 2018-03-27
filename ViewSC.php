<?php
//include('session.php');
//$DefID = $_GET['DefID'];
$Role = $_SESSION['Role'];
$title = "SVBX - Deficiency No".$DefID;
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
include('filestart.php'); 
//$link = f_sqlConnect();
//$Def = file_get_contents("ViewDef.sql").$DefID;

    /*if($stmt = $link->prepare($Def)) {  
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
                $Repo,
                $filename,
                $ClosureComments,
                $DueDate,
                $SafetyCert);  
    while ($stmt->fetch()) { 
    
    echo "
            <table class='vdtable'>
                <tr class='vdtr'>";
                    if($Status == "Open") {
                        $color = "Red";
                    } elseif($Status == "Closed") {
                        $color = "Green"; 
                        } else {
                            $color = "Black";
                        }*/
            echo "        <br /><br />
            <br /><br />
            <table border='1' style='width:96%;margin-left:auto;margin-right:auto'>
                <tr>
                    <th class='vdth' colspan='12' height='50' style='background-color:$color'><p>Certifiable Element Checklist</p></th>
                </tr>
                <tr>
                    <th  class='vdth' style='width:10%;text-align:left'>Contract Number:</td>
                    <td style='width:6%'>C700</td>
                    <th class='vdth' style='width:10%;text-align:left'>Control Number:</td>
                    <td style='width:6%'>290</td>
                    <th class='vdth' style='width:10%;text-align:left'>Number of Items:</td>
                    <td style='width:6%'>30</td>
                    <th class='vdth' style='width:10%;text-align:left'>Initial Date:</td>
                    <td style='width:6%'>2/15/2012</td>
                    <th class='vdth' style='width:10%;text-align:left'>Disposition Date:</td>
                    <td style='width:6%'>2/15/2015</td>
                    <th class='vdth' style='width:10%;text-align:left'>Reviewed by:</td>
                    <td style='width:6%'>J. Bloggs</td>
                </tr>
                <tr>
                    <th class='vdth' style='text-align:left'>Element Group:</td>
                    <td>Station - Berryessa</td>
                    <th class='vdth' style='text-align:left'>Certifiable Element:</td>
                    <td>Mechanical</td>
                    <th class='vdth' colspan='4'></td>
                    <th class='vdth' style='text-align:left'>Sub-Element:</td>
                    <td>Blank</td>
                    <th class='vdth' style='text-align:left'>Sub-Component:</td>
                    <td>Blank</td>
                </tr>
            </table>
            <br />
            <table border='1' style='width:96%;margin-left:auto;margin-right:auto'>
                <tr>
                    <th class='vdth' colspan='12' height='50' style='background-color:$color'><p>Certification Stage</p></th>
                </tr>
                <tr>
                    <th class='vdth' style='width:4%'>Item</td>
                    <th class='vdth' style='width:28%'>Safety/Security<br />Requirement</td>
                    <th class='vdth' colspan='2' style='width:15%'>Design<br />Codes/Standards</td>
                    <th class='vdth' colspan='2' style='width:15%'>Design Specifications/Criteria</td>
                    <th class='vdth' colspan='2' style='width:12%'>Design Compliance</td>
                    <th class='vdth' colspan='2' style='width:12%'>Construction/Installation<br />Verification/Testing</td>
                    <th class='vdth' colspan='2' style='width:12%'>Pre-Revenue<br />Testing/Operations</td>
                </tr>
                <tr>
                    <td rowspan='11' style='vertical-align:Top;text-align:left'>SB.290.02</td>
                    <td rowspan='11' style='vertical-align:Top;text-align:left'>Toilet rooms exhaust shall be seperate from and other exhausts</td>
                    <td rowspan='6' colspan='2' style='vertical-align:Top;text-align:left'>Facility Design Criteria,<br />Mechanical 3.5.7 -<br />Stations & Station Sites</td>
                    <td rowspan='6' colspan='2' style='vertical-align:Top;text-align:left'>1.4.2.A, Page 5</td>
                    <th class='vdth' style='text-align:left'>Prepared by:</td>
                    <td>G.H.W.Bush</td>
                    <th class='vdth' style='text-align:left'>Prepared by:</td>
                    <td>G.W.Bush</td>
                    <th class='vdth' style='text-align:left'>Prepared by:</td>
                    <td>B.Clinton</td>
                </tr>
                <tr>
                    <th class='vdth' style='text-align:left'>Compliance Status:</td>
                    <td>Compliant</td>
                    <th class='vdth' style='text-align:left'>Compliance Status:</td>
                    <td>Not Compliant</td>
                    <th class='vdth' style='text-align:left'>Compliance Status:</td>
                    <td>Not Started</td>
                </tr>
                <tr>
                    <th class='vdth' style='text-align:left'>Date Disposed:</td>
                    <td>03/05/2018</td>
                    <th class='vdth' style='text-align:left'>Date Disposed:</td>
                    <td>03/05/2018</td>
                    <th class='vdth' style='text-align:left'>Date Disposed:</td>
                    <td>03/05/2018</td>
                </tr>
                <tr>
                    <th class='vdth' style='text-align:left'>Validated by:</td>
                    <td>B.Obama</td>
                    <th class='vdth' style='text-align:left'>Validated by:</td>
                    <td>E.J.Hoover</td>
                    <th class='vdth' style='text-align:left'>Validated by:</td>
                    <td>J.Bond</td>
                </tr>
                <tr>
                    <th class='vdth' style='text-align:left'>Date validated:</td>
                    <td>03/05/2018</td>
                    <th class='vdth' style='text-align:left'>Date validated:</td>
                    <td>03/05/2018</td>
                    <th class='vdth' style='text-align:left'>Date validated:</td>
                    <td>03/05/2018</td>
                </tr>
                <tr>
                    <th class='vdth' colspan='2'>Reference Drawings/Documents/Notes/Tests<br />(Include Page/Drawing/Test Numbers)</td>
                    <th class='vdth' colspan='2'>Reference Drawings/Documents/Notes/Tests<br />(Include Page/Drawing/Test Numbers)</td>
                    <th class='vdth' colspan='2'>Reference Drawings/Documents/Notes/Tests<br />(Include Page/Drawing/Test Numbers)</td>
                </tr>
                <tr>
                    <th class='vdth' colspan='4'>Compliance method<br />Check all methods used</td>
                    <td colspan='2' rowspan='5' style='vertical-align:Top;text-align:left'>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi.</td>
                    <td colspan='2' rowspan='5' style='vertical-align:Top;text-align:left'>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi.</td>
                    <td colspan='2' rowspan='5' style='vertical-align:Top;text-align:left'>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi.</td>
                </tr>
                <tr>
                    <th class='vdth' style='text-align:left' style='width:10%'>Design:</td>
                    <td style='width:5%;text-align:center'><input type='checkbox' id='SCDesign' name='SCDesign' value='1'></td>
                    <th class='vdth' style='text-align:left' style='width:10%'>Procedures & Training:</td>
                    <td style='width:5%;text-align:center'><input type='checkbox' id='SCPT' name='SCPT' value='1'></td>
                </tr>
                <tr>
                    <th class='vdth' style='text-align:left'>Warning Devices:</td>
                    <td style='text-align:center'><input type='checkbox' id='SCWD' name='SCWD' value='1'></td>
                    <th class='vdth' style='text-align:left'>Security Technology:</td>
                    <td style='text-align:center'><input type='checkbox' id='SCST' name='SCST' value='1'></td>
                </tr>
                <tr>
                    <th class='vdth' style='text-align:left'>Safety Devices:</td>
                    <td style='text-align:center'><input type='checkbox' id='SCSD' name='SCSD' value='1'></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th class='vdth' style='text-align:left'>Testing:</td>
                    <td style='text-align:center'><input type='checkbox' id='SCTesting' name='SCTesting' value='1'></td>
                    <th class='vdth' style='text-align:left'>Visual Inspection:</td>
                    <td style='text-align:center'><input type='checkbox' id='SCVI' name='SCVI' value='1'></td>
                </tr>
            </table><br>";
            /*if($Role == 'S' OR $Role == 'A' OR $Role == 'U') 
            {
                echo "
                    <form action='UpdateDef.php' method='POST' onsubmit='' style='text-align:center' />
                    <input type='hidden' name='q' value='".$DefID."'/>
                    <input type='submit' name='submit' value='Update' class='btn btn-primary btn-lg'/>
                    </form>
                    <br />
                    <br />";
            //} else {
                
            //}
            //        }  
            /*    } else {  
                    echo "
                    <div='container'>
                    <br />
                    <br />
                    <br />
                    <br>Unable to connect<br>
                    </div>";
                    echo $Def.'<br /><br />';
                    //echo mysqli_error();
                    //echo "<BR>Def ID: ".$DefID;
                  exit();  
                } */
    include('fileend.php');
    //MySqli_Close($link); 
?>
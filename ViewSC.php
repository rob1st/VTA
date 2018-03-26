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
            <table border='1' style='width:60% leftmargin:10%'>
                <th class='vdth' colspan='12' height='50' style='background-color:$color'><p>
                        Safety Certifiable Element. $SCItem</p></th>
                </tr>
                <tr>
                    <th class='vdth'>Item</td>
                    <th class='vdth'>Safety/Security<br />Requirement</td>
                    <th class='vdth' colspan='2'>Design<br />Codes/Standards</td>
                    <th class='vdth' colspan='2'>Design Specifications/Criteria</td>
                    <th class='vdth' colspan='2'>Design Compliance</td>
                    <th class='vdth' colspan='2'>Construction/Installation<br />Verification/Testing</td>
                    <th class='vdth' colspan='2'>Pre-Revenue<br />Testing/Operations</td>
                </tr>
                <tr>
                    <td rowspan='11'>SB.290.02</td>
                    <td rowspan='11'>Toilet rooms exhaust shall be seperate from and other exhausts</td>
                    <td rowspan='6' colspan='2'>Facility Design Criteria,<br />Mechanical 3.5.7 -<br />Stations & Station Sites</td>
                    <td rowspan='6' colspan='2'>1.4.2.A, Page 5</td>
                    <th class='vdth'>Prepared by:</td>
                    <td></td>
                    <th class='vdth'>Prepared by:</td>
                    <td></td>
                    <th class='vdth'>Prepared by:</td>
                    <td></td>
                </tr>
                <tr>
                    <th class='vdth'>Compliance Status:</td>
                    <td></td>
                    <th class='vdth'>Compliance Status:</td>
                    <td></td>
                    <th class='vdth'>Compliance Status:</td>
                    <td></td>
                </tr>
                <tr>
                    <th class='vdth'>Date Disposed:</td>
                    <td></td>
                    <th class='vdth'>Date Disposed:</td>
                    <td></td>
                    <th class='vdth'>Date Disposed:</td>
                    <td></td>
                </tr>
                <tr>
                    <th class='vdth'>Validated by:</td>
                    <td></td>
                    <th class='vdth'>Validated by:</td>
                    <td></td>
                    <th class='vdth'>Validated by:</td>
                    <td></td>
                </tr>
                <tr>
                    <th class='vdth'>Date validated:</td>
                    <td></td>
                    <th class='vdth'>Date validated:</td>
                    <td></td>
                    <th class='vdth'>Date validated:</td>
                    <td></td>
                </tr>
                <tr>
                    <th class='vdth' colspan='2'>Reference Drawings/Documents/Notes/Tests<br />(Include Page/Drawing/Test Numbers)</td>
                    <th class='vdth' colspan='2'>Reference Drawings/Documents/Notes/Tests<br />(Include Page/Drawing/Test Numbers)</td>
                    <th class='vdth' colspan='2'>Reference Drawings/Documents/Notes/Tests<br />(Include Page/Drawing/Test Numbers)</td>
                </tr>
                <tr>
                    <th class='vdth' colspan='4'>Compliance method<br />Check all methods used</td>
                    <td colspan='2' rowspan='5'></td>
                    <td colspan='2' rowspan='5'></td>
                    <td colspan='2' rowspan='5'></td>
                </tr>
                <tr>
                    <th class='vdth'>Design:</td>
                    <td></td>
                    <th class='vdth'>Procedures & Training:</td>
                    <td></td>
                </tr>
                <tr>
                    <th class='vdth'>Warning Devices:</td>
                    <td></td>
                    <th class='vdth'>Security Technology:</td>
                    <td></td>
                </tr>
                <tr>
                    <th class='vdth'>Safety Devices:</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th class='vdth'>Testing:</td>
                    <td></td>
                    <th class='vdth'>Visual Inspection:</td>
                    <td></td>
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
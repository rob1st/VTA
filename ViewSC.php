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
            echo "        <th class='vdth' colspan='7' height='50' style='background-color:$color'><p>
                        Satey Certifiable Element. $SCItem</p></th>
                </tr>
                <tr>
                    <td>Item</td>
                    <td>Safety/Security<br />Requirement</td>
                    <td>Design<br />Codes/Standards</td>
                    <td>Design Specifications/Criteria</td>
                    <td>Design Compliance</td>
                    <td>Construction/Installation<br />Verification/Testing</td>
                    <td>Pre-Revenue<br />Testing/Operations</td>
                </tr>
            </table><br>";
            if($Role == 'S' OR $Role == 'A' OR $Role == 'U') 
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
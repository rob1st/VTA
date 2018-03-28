<?php
include('session.php');
$Role = $_SESSION['Role'];
$title = "SVBX - Deficiency No".$DefID;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('filestart.php'); 
$link = f_sqlConnect();
$CertID = '1';
$SafetyCert = file_get_contents("SafetyCert.sql").$CertID;

    if($stmt = $link->prepare($SafetyCert)) {  
        $stmt->execute();  
        $stmt->bind_result(
                $CertID, 
                $Item, 
               $Requirement,
               $DesignCode,
               $DesignSpec,
               $ContractNo,
               $ControlNo,
               $ElementGroup,
               $CertElement);  
    while ($stmt->fetch()) { 
    
    echo "

            <table border='1' style='width:96%;margin-left:auto;margin-right:auto;margin-top:100px'>
                <tr>
                    <th class='vdth' colspan='8' height='50'><p>Certification Stage</p></th>
                </tr>
                <tr>
                    <th class='vdth' style='width:5%'>Item</td>
                    <th class='vdth' style='width:26%'>Safety/Security<br />Requirement</td>
                    <th class='vdth' style='width:15%'>Design<br />Codes/Standards</td>
                    <th class='vdth' style='width:15%'>Design Specifications/Criteria</td>
                    <th class='vdth' style='width:10%'>Contract No.</td>
                    <th class='vdth' style='width:10%'>Control No.</td>
                    <th class='vdth' style='width:10%'>Element Group</td>
                    <th class='vdth' style='width:10%'>Certifiable Element</td>
                    
                </tr>
                <tr>
                    <td style='vertical-align:Top;text-align:left'>$Item</td>
                    <td style='vertical-align:Top;text-align:left'>$Requirement</td>
                    <td style='vertical-align:Top;text-align:left'>$DesignCode</td>
                    <td style='vertical-align:Top;text-align:left'>$DesignSpec</td>
                    <td style='vertical-align:Top;text-align:left'>$ContractNo</td>
                    <td style='vertical-align:Top;text-align:left'>$ControlNo</td>
                    <td style='vertical-align:Top;text-align:left'>$ElementGroup</td>
                    <td style='vertical-align:Top;text-align:left'>$CertElement</td>
                </tr>
            </table><br>";
                    }  
                } else {  
                    echo "
                    <div='container'>
                    <br />
                    <br />
                    <br />
                    <br>Unable to connect<br>
                    </div>";
                    echo $SafetyCert.'<br /><br />';
                    //echo mysqli_error();
                    //echo "<BR>Def ID: ".$DefID;
                  exit();  
                } 
    include('fileend.php');
    MySqli_Close($link); 
?>
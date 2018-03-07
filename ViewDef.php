<?php
//include('session.php');
?>

<HTML>
    <HEAD>
        <TITLE>Deficiency Details</TITLE>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
    </HEAD>
    <BODY>
    <?php include('filestart.php'); 
    $link = f_sqlConnect();
    $DefID = $_GET['DefID'];
    $Def = file_get_contents("ViewDef.sql").$DefID;
    error_reporting(E_ALL);  
    ini_set("display_errors", 1);
    
    
    
    
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
                $Comments);  
    while ($stmt->fetch()) { 
    echo "
        <H1>View deficiency</H1>
            <table>
                <tr>";
                    if($Status == "Open") {
                        $color = "Red";
                    } else {
                        $color = "Green"; 
                        }
            echo "        <th colspan='4' height='50' style=background-color:$color><p>
                        Deficiency No. $DefID</p></th>
                </tr>
                <tr>
                    <th colspan='4'><p>Required Information</p></th>
                </tr>
                <tr>
                    <td><p>Date Created:</p></td>
                    <td><p>$DateCreated</p></td>
                    <td><p>System Affected:</p></td>
                    <td><p>$SystemAffected</p></td>
                </tr>
                <tr>
                    <td><p>General Location:</p></td>
                    <td><p>$Location</p></td>
                    <td><p>Specific Location:</p></td>
                    <td><p>$SpecLoc</p></td>
                </tr>
                <tr>
                    <td><p>Status:</p></td>
                    <td><p>$Status</p></td>
                    <td><p>Severity:</p></td>
                    <td><p>$Severity</p></td>
                </tr>
                <tr>
                    <td><p>Group to Resolve:</p></td>
                    <td><p>$GroupToResolve</p></td>
                    <td><p>Identified By:</p></td>
                    <td><p>$IdentifiedBy</p></td>
                </tr>
                <tr>
                    <th colspan='4' style='text-align:center'><p>Deficiency Description</p></th>
                </tr>
                <tr>
                    <td Colspan=4><p>"; echo nl2br($Description);
                    echo "</p></td>
                </tr>
                <tr>
                    <th colspan='4'><p>Optional Information</p></th>
                </tr>
                <tr>
                    <td><p>Spec or Code:</p></td>
                    <td colspan='3'><p>$Spec</p></td>
                </tr>
                <tr>
                    <td><p>Action Owner:</p></td>
                    <td><p>$ActionOwner</p></td>
                    <td><p>Old Id:</p></td>
                    <td><p>$OldID</p></td>
                <tr>
                    <th colspan='4'><p>Closure Information</p></th>
                </tr>
                <tr>
                    <td><p>Evidence Type:</p></td>
                    <td><p>$EvidenceType</p></td>
                    <td><p>Evidence Link:<br>(SharePoint)</p></td>
                    <td><p>$EvidenceLink</p></td>
                </tr>
                 <tr>
                    <th colspan='4' style='text-align:center'><p>Deficiency Comments</p></th>
                </tr>
                <tr>
                    <td Colspan=4><p>"; echo nl2br($Comments);
                    echo "</p></td>
                </tr>
                <tr>
                    <th colspan='4' style='text-align:center'><p>Modification Details</p></th>
                </tr>
                <tr>
                    <td rowspan='2'><p>Last Updated:</p></td>
                    <td rowspan='2'><p>$LastUpdated</p></td>
                    <td><p>Created by:</p></td>
                    <td><p>$Created_by</p></td>
                </tr>
                <tr>
                    <td><p>Updated by:</p></td>
                    <td><p>$Updated_by</p></td>
                </tr>
            </table><br>";
            if(!isset($_SESSION['UserID'])) 
            {
                
            } else {
            echo "
            <form action='UpdateDef.php' method='POST' onsubmit=''/>
                        <input type='hidden' name='q' value='".$DefID."'/><input type='submit' value='Update'></form>";
            }
                    }  
                } else {  
                    echo "<br>Unable to connect<br>";
                    echo $Def;
                    //echo "<BR>Def ID: ".$DefID;
                  exit();  
                } 
    include('fileend.php');
    MySqli_Close($link); 
?>
    </BODY>
</HTML>
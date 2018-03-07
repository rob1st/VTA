<?php
//include('session.php');
?>

<HTML>
    <HEAD>
        <TITLE>Update Deficiency</TITLE>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
    </HEAD>
    <BODY>
    <?php include('filestart.php'); 
    $link = f_sqlConnect();
    $q = $_POST['q'];
    $Def = file_get_contents("UpdateDef.sql").$q;
    error_reporting(E_ALL);  
    ini_set("display_errors", 1);
    $sql1 = "SELECT LocationID, LocationName FROM Location ORDER BY LocationName";
    $list1 = mysqli_query($link,$sql1);
    $sql2 = "SELECT SystemID, System FROM System ORDER BY System";
    $list2 = mysqli_query($link,$sql2);
    $sql3 = "SELECT StatusID, Status FROM Status ORDER BY Status";
    $list3 = mysqli_query($link,$sql3);
    $sql4 = "SELECT SeverityID, SeverityName FROM Severity ORDER BY SeverityName";
    $list4 = mysqli_query($link,$sql4);
    $sql5 = "SELECT EviTypeID, EviType FROM EvidenceType ORDER BY EviType";
    $list5 = mysqli_query($link,$sql5);
    
    
    
    
    if($stmt = $link->prepare($Def)) {  
        $stmt->execute();  
        $stmt->bind_result($OldID, $Location, $SpecLoc, $Severity, $Description, $Spec, $DateCreated, $Status, $IdentifiedBy, $SystemAffected, $GroupToResolve, $ActionOwner, $EvidenceType, $EvidenceLink, $DateClosed, $LastUpdated, $Updated_by, $Comments);  
    while ($stmt->fetch()) { 
    echo "
        <H1>Update deficiency</H1>
        <form action='UpdateDefCommit.php' method='POST' onsubmit='' />
        <input type='hidden' name='DefID' value='".$q."'>
            <table>
                <tr>
                <th colspan='4' height='50'><p>
                        Deficiency No. $q</p></th>
                </tr>
                <tr>
                    <th colspan='4'><p>Required Information</p></th>
                </tr>
                <tr>
                    <td><p>Date Created:</p></td>
                    <td><p>$DateCreated</td>
                    <td><p>System Affected:</p></td>
                    <td><select name='SystemAffected' value='".$SystemAffected."' id='defdd'></option>
                        <option value=''></option>";
                        if(is_array($list2) || is_object($list2)) {
                        foreach($list2 as $row) {
                            echo "<option value='$row[SystemID]'";
                                if($row[SystemID] == $SystemAffected) {
                                    echo " selected>$row[System]</option>";
                                    } else { echo ">$row[System]</option>";
                                }
                        }
                        }
    echo "      </td>
                </tr>
                <tr>
                    <td><p>General Location:</p></td>
                    <td><select name='LocationName' value='".$Location."' id='defdd'></option>
                        <option value=''></option>";
                        if(is_array($list1) || is_object($list1)) {
                        foreach($list1 as $row) {
                            echo "<option value='$row[LocationID]'";
                                if($row[LocationID] == $Location) {
                                    echo " selected>$row[LocationName]</option>";
                                    } else { echo ">$row[LocationName]</option>";
                                }
                        }
                        }
    echo "          </td>
                    <td><p>Specific Location:</p></td>
                    <td><p><input type='text' name='SpecLoc' value='".$SpecLoc."' max='55' id='defdd'/></p></td>
                </tr>
                <tr>
                    <td><p>Status:</p></td>
                    <td><select name='Status' value='".$Status."' id='defdd'></option>
                        <option value=''></option>";
                        if(is_array($list3) || is_object($list3)) {
                        foreach($list3 as $row) {
                            echo "<option value='$row[StatusID]'";
                                if($row[StatusID] == $Status) {
                                    echo " selected>$row[Status]</option>";
                                    } else { echo ">$row[Status]</option>";
                                }
                        }
                        }
    echo "          </td>
                    <td><p>Severity:</p></td>
                    <td><select name='SeverityName' value='".$Severity."' id='defdd'></option>
                        <option value=''></option>";
                        if(is_array($list4) || is_object($list4)) {
                        foreach($list4 as $row) {
                            echo "<option value='$row[SeverityID]'";
                                if($row[SeverityID] == $Severity) {
                                    echo " selected>$row[SeverityName]</option>";
                                    } else { echo ">$row[SeverityName]</option>";
                                }
                        }
                        }
    echo "      </td>
                </tr>
                <tr>
                    <td><p>Group to Resolve:</p></td>
                    <td><select name='GroupToResolve' value='".$GroupToResolve."' id='defdd'></option>
                        <option value=''></option>";
                        if(is_array($list2) || is_object($list2)) {
                        foreach($list2 as $row) {
                            echo "<option value='$row[SystemID]'";
                                if($row[SystemID] == $GroupToResolve) {
                                    echo " selected>$row[System]</option>";
                                    } else { echo ">$row[System]</option>";
                                }
                        }
                        }
    echo "          </td>
                    <td><p>Identified By:</p></td>
                    <td><input type='text' name='IdentifiedBy' value='".$IdentifiedBy."' max='24' id='defdd'/></td>
                </tr>
                <tr>
                    <th colspan='4' style='text-align:center'><p>Deficiency Description</p></th>
                </tr>
                <tr>
                    <td Colspan=4><textarea type='message' rows='5' cols='99%' name='Description' max='1000'>$Description</textarea></td>
                </tr>
                <tr>
                    <th colspan='4'><p>Optional Information</p></th>
                </tr>
                <tr>
                    <td><p>Spec or Code:</p></td>
                    <td><input type='text' name='Spec' value='".$Spec."' max='24'/></td>
                </tr>
                <tr>
                    <td><p>Action Owner:</p></td>
                    <td><input type='text' name='ActionOwner' value='".$ActionOwner."' max='24'/></td>
                    <td><p>Old Id:</p></td>
                    <td><input type='text' name='OldID' value='".$OldID."' max='24'/></td>
                <tr>
                    <th colspan='4'><p>Closure Information</p></th>
                </tr>
                <tr>
                    <td><p>Evidence Type:</p></td>
                    <td><select name='EviType' value='".$EviType."'></option>
                        <option value=''></option>";
                        if(is_array($list5) || is_object($list5)) {
                        foreach($list5 as $row) {
                            echo "<option value='$row[EviTypeID]'";
                                if($row[EviTypeID] == $EvidenceType) {
                                    echo " selected>$row[EviType]</option>";
                                    } else { echo ">$row[EviType]</option>";
                                }
                        }
                        }
    echo "          </td>
                    <td><p>Evidence Link:<br>(SharePoint)</p></td>
                    <td><input type='text' name='EvidenceLink' value='".$EvidenceLink."' max='255'/></td>
                </tr>
                 <tr>
                    <th colspan='4' style='text-align:center'><p>Deficiency Comments</p></th>
                </tr>
                <tr>
                    <td Colspan=4><textarea type='message' rows='5' cols='99%' name='Comments' max='1000'>$Comments</textarea></td>
                </tr>
            </table><br>
            <input type='submit'>
            <a href='DisplayDef.php'><button>Cancel</button></a>
            </form>
            ";
            //echo "Description: " .$Description;
                    }  
                } else {  
                    echo "<br>Unable to connect<br>";
                    exit();  
                } 
    include('fileend.php');
    MySqli_Close($link); 
?>
    </BODY>
</HTML>
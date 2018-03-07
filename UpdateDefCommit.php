<?php
include('SQLFunctions.php');
include('session.php');
session_start();

if(!empty($_POST)) {
    $DefID = $_POST['DefID'];
    $SystemAffected = $_POST['SystemAffected'];
    $LocationName = $_POST['LocationName'];
    $SpecLoc = $_POST['SpecLoc'];
    $Status = $_POST['Status'];
    $SeverityName = $_POST['SeverityName'];
    $GroupToResolve = $_POST['GroupToResolve'];
    $IdentifiedBy = $_POST['IdentifiedBy'];
    $Description = $_POST['Description'];
    $EvidenceType = $_POST['EviType'];
    $EvidenceLink = $_POST['EvidenceLink'];
    $ActionOwner = $_POST['ActionOwner'];
    $OldID = $_POST['OldID'];
    $Comments = $_POST['Comments'];
    $Spec = $_POST['Spec'];
    $UserID = $_SESSION['UserID'];
    $link = f_sqlConnect();
    
    $user = "SELECT Username FROM Users WHERE UserID = ".$UserID;
    if($result=mysqli_query($link,$user)) 
        {
          /*from the sql results, assign the username that returned to the $username variable*/    
          while($row = mysqli_fetch_assoc($result)) {
            $Username = $row['Username'];
          }
        }
    
    $sql = "UPDATE CDL
            SET  
                 OldID = '".$OldID."'
                ,Location = '".$LocationName."'
                ,SpecLoc = '".$SpecLoc."'
                ,Severity = '".$SeverityName."'
                ,Description = '".$Description."'
                ,Spec = '".$Spec."'
                ,Status = '".$Status."'
                ,IdentifiedBy = '".$IdentifiedBy."'
                ,SystemAffected = '".$SystemAffected."'
                ,GroupToResolve = '".$GroupToResolve."'
                ,ActionOwner = '".$ActionOwner."'
                ,EvidenceType = '".$EvidenceType."'
                ,EvidenceLink = '".$EvidenceLink."'
                ,Comments = '".$Comments."'
                ,Updated_by = '".$Username."'
                ,LastUpdated = NOW()
            WHERE DefID = ".$DefID.";";

            if(mysqli_query($link,$sql)) {
                echo "<br>Update Completed successfully";
        } else {
            echo "<br>Error: " .$sql. "<br>" .mysqli_error($link);
        }
        mysqli_close($link);
        header("Location: DisplayDefs.php?msg=1");
        //echo "<br>SQL: ".$sql;
}
?>
<?php
include('session.php');
session_start();
$title = "SVBX - Update Deficiency";
$role = $_SESSION['Role'];
include('filestart.php'); 

    $link = f_sqlConnect();
    $q = $_POST['q'];
    $Def = file_get_contents("UpdateDef.sql").$q;
    error_reporting(E_ALL);  
    ini_set("display_errors", 1);
    $sql1 = "SELECT LocationID, LocationName FROM Location ORDER BY LocationName";
    $list1 = mysqli_query($link,$sql1);
    $sql2 = "SELECT SystemID, System FROM System ORDER BY System";
    $list2 = mysqli_query($link,$sql2);
    $sql3 = "SELECT StatusID, Status FROM Status WHERE StatusID <> 3 ORDER BY StatusID";
    $list3 = mysqli_query($link,$sql3);
    $sql4 = "SELECT SeverityID, SeverityName FROM Severity ORDER BY SeverityName";
    $list4 = mysqli_query($link,$sql4);
    $sql5 = "SELECT EviTypeID, EviType FROM EvidenceType ORDER BY EviType";
    $list5 = mysqli_query($link,$sql5);
    $sql6 = "SELECT ReqByID, RequiredBy FROM RequiredBy ORDER BY RequiredBy";
    $list6 = mysqli_query($link,$sql6);
    $sql7 = "SELECT RepoID, Repo FROM Repo ORDER BY Repo";
    $list7 = mysqli_query($link,$sql7);
    $sql8 = "SELECT YesNoID, YesNo FROM YesNo ORDER BY YesNo";
    $list8 = mysqli_query($link,$sql8);
    
    
    
    
    if($stmt = $link->prepare($Def)) {  
        $stmt->execute();  
        $stmt->bind_result(
            $OldID, 
            $Location, 
            $SpecLoc, 
            $Severity, 
            $Description, 
            $Spec, $DateCreated, 
            $Status, $IdentifiedBy, 
            $SystemAffected, 
            $GroupToResolve, 
            $ActionOwner, 
            $EvidenceType, 
            $EvidenceLink, 
            $DateClosed, 
            $LastUpdated, 
            $Updated_by, 
            $Comments, 
            $RequiredBy, 
            $Repo, 
            $Pics, 
            $ClosureComments, 
            $DueDate, 
            $SafetyCert);  
    while ($stmt->fetch()) {
    echo "
        <header class='container page-header'>
            <h1 class='page-title'>Update Deficiency ".$q."</h1>
        </header>
        <main class='container main-content'> 
            <form action='UpdateDefCommit.php' method='POST' onsubmit='' />
                <input type='hidden' name='DefID' value='".$q."'>
<p>
                        Deficiency No. $q</p>
                        <p>Safety Certifiable:</p>
                <select name='SafetyCert' value='".$SafetyCert."' id='defdd'>
                        <option value=''></option>";
                        if(is_array($list8) || is_object($list8)) {
                        foreach($list8 as $row) {
                            echo "<option value='$row[YesNoID]'";
                                if($row['YesNoID'] == $SafetyCert) {
                                    echo " selected>$row[YesNo]</option>";
                                    } else { echo ">$row[YesNo]</option>";
                                }
                        }
                        }
                    echo "
                    <p>System Affected:</p>
                    <select name='SystemAffected' value='".$SystemAffected."' id='defdd'>
                        <option value=''></option>";
                        if(is_array($list2) || is_object($list2)) {
                        foreach($list2 as $row) {
                            echo "<option value='$row[SystemID]'";
                                if($row['SystemID'] == $SystemAffected) {
                                    echo " selected>$row[System]</option>";
                                    } else { echo ">$row[System]</option>";
                                }
                        }
                        }
    echo "      <p>General Location:</p>
                    <select name='LocationName' value='".$Location."' id='defdd'>
                        <option value=''></option>";
                        if(is_array($list1) || is_object($list1)) {
                        foreach($list1 as $row) {
                            echo "<option value='$row[LocationID]'";
                                if($row['LocationID'] == $Location) {
                                    echo " selected>$row[LocationName]</option>";
                                    } else { echo ">$row[LocationName]</option>";
                                }
                        }
                        }
    echo "          <p>Specific Location:</p>
    <input type='text' name='SpecLoc' value='".$SpecLoc."' max='55' id='defdd'/>
                
                <p>Status:</p>
                <select name='Status' value='".$Status."' id='defdd'>
                        <option value=''></option>";
                        if(is_array($list3) || is_object($list3)) {
                        foreach($list3 as $row) {
                            echo "<option value='$row[StatusID]'";
                                if($row['StatusID'] == $Status) {
                                    echo " selected>$row[Status]</option>";
                                    } else { echo ">$row[Status]</option>";
                                }
                        }
                        }
    echo "          <p>Severity:</p>
                    <select name='SeverityName' value='".$Severity."' id='defdd'>
                        <option value=''></option>";
                        if(is_array($list4) || is_object($list4)) {
                        foreach($list4 as $row) {
                            echo "<option value='$row[SeverityID]'";
                                if($row['SeverityID'] == $Severity) {
                                    echo " selected>$row[SeverityName]</option>";
                                    } else { echo ">$row[SeverityName]</option>";
                                }
                        }
                        }
    echo "      
                        <p>To be resolved by:</p>
                    
                        <input type='date' name='DueDate' id='defdd' value='$DueDate' required/>
                    
                        <p>Required for:</p>
                    <select name='RequiredBy' value='".$RequiredBy."' id='defdd'>
                        <option value=''></option>";
                        if(is_array($list6) || is_object($list6)) {
                        foreach($list6 as $row) {
                            echo "<option value='$row[ReqByID]'";
                                if($row['ReqByID'] == $RequiredBy) {
                                    echo " selected>$row[RequiredBy]</option>";
                                    } else { echo ">$row[RequiredBy]</option>";
                                }
                        }
                        }
    echo "
                    <p>Group to Resolve:</p>
                    <select name='GroupToResolve' value='".$GroupToResolve."' id='defdd'>
                        <option value=''></option>";
                        if(is_array($list2) || is_object($list2)) {
                        foreach($list2 as $row) {
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
            //echo "Description: " .$Description;
                    }  
                } else {  
                    echo "<br>Unable to connect<br>";
                    exit();  
                } 
    MySqli_Close($link);             
    include('fileend.php');
?>
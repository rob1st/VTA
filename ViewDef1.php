<?php
//include('session.php');
?>

<HTML>
    <HEAD>
        <TITLE>New Deficiency</TITLE>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
    </HEAD>
    <?php 
            include('SQLFunctions.php');
            
            $link = f_sqlConnect();
            $table = CDL;
    ?>
    <BODY>
        <?php 
        include('filestart.php');
        if($stmt = $link->prepare($Def)) {  
        $stmt->execute();  
        $stmt->bind_result($DefID, $OldID, $Location, $SpecLoc, $Severity, $Description, $Spec, $DateCreated, $Status, $IdentifiedBy, $SystemAffected, $GroupToResolve, $ActionOwner, $EvidenceType, $EvidenceLink, $DateClosed, $LastUpdated, $Comments);  
        while ($stmt->fetch()) { 
        echo "    
        <H1>Create a new deficiency</H1>
            <table>
                <tr>
                    <th colspan='4'>
                        <p>New Deficiency</p>
                    </th>
                </tr>
                <tr>
                    <th colspan='4'>
                        <p>Required Information</p>
                    </th>
                </tr>
                <tr>
                    <td>
                        <p>Date Created:</p>
                    </td>
                    <td>
                        
                    </td>
                    <td>
                        <p>System Affected</p>
                    </td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td>
                        <p>General Location:</p>
                    </td>
                    <td>
                        $Location
                    </td>
                    <td>
                        <p>Specific Location:</p>
                    </td>
                    <td>
                        $SpecLoc
                    </td>
                </tr>
                <tr>    
                    <td>
                        <p>Status:</p>
                    </td>
                    <td>

                    </td>
                    <td>
                        <p>Severity</p>
                    </td>
                    <td>

                    </td>
                </tr>
                <tr>    
                    <td>
                        <p>Group to resolve:</p>
                    </td>
                    <td>

                    </td>
                    <td>
                        <p>Identified by:</p>
                    </td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td colspan='4' style='text-align:center'>
                        <p>Deficiency Description</p>
                    </td>
                </tr>
                <tr>
                    <td Colspan=4>

                    </td>
                </tr>
                <tr>
                    <th colspan='4'>
                        <p>Optional Information</p>
                    </th>
                </tr>
                <tr>
                    <td>
                        <p>Spec or Code:</p>
                    </td>
                    <td colspan='3'>

                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Action Owner:</p>
                    </td>
                    <td>
                        ActionOwner
                    </td>
                    <td>
                        <p>Old Id:</p>
                    </td>
                    <td>
                        OldID
                    </td>
                 
                <tr>
                    <th colspan='4'>
                        <p>Closure Information</p>
                    </th>
                </tr>
                <tr>
                    <td>
                        <p>Evidence Type:</p>
                    </td>
                    <td>
                        EvidenceType
                    </td>
                    <td>
                        <p>Evidence Link:<br>(SharePoint)</p>
                    </td>
                    <td>
                        EvidenceLink
                    </td>
                </tr>
                 <tr>
                    <td colspan='4' style='text-align:center'>
                        <p>Deficiency Comments</p>
                    </td>
                </tr>
                <tr>
                    <td Colspan=4>
                        Comments
                    </td>
                </tr>
            </table><br>";

        include('fileend.php');
        MySqli_Close($link); ?>
    </BODY>
</HTML>
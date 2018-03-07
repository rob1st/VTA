<?php
include('session.php');
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
<?php include('filestart.php') ?>
        <H1>Create a new deficiency</H1>
        <FORM action="RecDef.php" method="POST">
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
                        <p>Auto fill</p>
                    </td>
                    <td>
                        <p>System Affected</p>
                    </td>
                    <td>
                        <?php
                            $sqlY = "SELECT SystemID, System FROM System ORDER BY System";
                             //if($result = mysqli_query($link,$sqlY)) {
                                    echo "<select name='SystemAffected' value='' id='defdd' required></option>";
                                    echo "<option value=''></option>";
                                    foreach(mysqli_query($link,$sqlY) as $row) {
                                        echo "<option value=$row[SystemID]>$row[System]</option>";
                             }
                            echo "</select>";
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>General Location:</p>
                    </td>
                    <td>
                        <?php
                            $sqlL = "SELECT LocationID, LocationName FROM Location ORDER BY LocationName";
                             //if($result = mysqli_query($link,$sqlL)) {
                                    echo "<select name='Location' value='' id='defdd' required></option>";
                                    echo "<option value=''></option>";
                                    foreach(mysqli_query($link,$sqlL) as $row) {
                                        echo "<option value=$row[LocationID]>$row[LocationName]</option>";
                             }
                            echo "</select>";
                        ?>
                    </td>
                    <td>
                        <p>Specific Location:</p>
                    </td>
                    <td>
                        <input type="text" name="SpecLoc" max="55" id="defdd" required/>
                    </td>
                </tr>
                <tr>    
                    <td>
                        <p>Status:</p>
                    </td>
                    <td>
                        <?php
                            $sqlT = "SELECT StatusID, Status FROM Status ORDER BY Status";
                             //if($result = mysqli_query($link,$sqlT)) {
                                    echo "<select name='Status' value='' id='defdd' required></option>";
                                    echo "<option value=''></option>";
                                    foreach(mysqli_query($link,$sqlT) as $row) {
                                        echo "<option value=$row[StatusID]>$row[Status]</option>";
                             }
                            echo "</select>";
                        ?>
                    </td>
                    <td>
                        <p>Severity</p>
                    </td>
                    <td>
                        <?php
                            $sqlS = "SELECT SeverityID, SeverityName FROM Severity ORDER BY SeverityName";
                             //if($result = mysqli_query($link,$sqlS)) {
                                    echo "<select name='Severity' value='' id='defdd' required></option>";
                                    echo "<option value=''></option>";
                                    foreach(mysqli_query($link,$sqlS) as $row) {
                                        echo "<option value=$row[SeverityID]>$row[SeverityName]</option>";
                             }
                            echo "</select>";
                        ?>
                    </td>
                </tr>
                <tr>    
                    <td>
                        <p>Group to resolve:</p>
                    </td>
                    <td>
                        <?php
                            $sqlY = "SELECT SystemID, System FROM System ORDER BY System";
                             //if($result = mysqli_query($link,$sqlY)) {
                                    echo "<select name='GroupToResolve' value='' id='defdd' required></option>";
                                    echo "<option value=''></option>";
                                    foreach(mysqli_query($link,$sqlY) as $row) {
                                        echo "<option value=$row[SystemID]>$row[System]</option>";
                             }
                            echo "</select>";
                        ?>
                    </td>
                    <td>
                        <p>Identified by:</p>
                    </td>
                    <td>
                        <input type="text" name="IdentifiedBy" max="24" id="defdd" required/>
                    </td>
                </tr>
                <tr>
                    <td colspan='4' style="text-align:center">
                        <p>Deficiency Description</p>
                    </td>
                </tr>
                <tr>
                    <td Colspan=4>
                        <textarea type="message"  rows="5" cols="99%" name="description" max="1000" required></textarea>
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
                    <td colspan="3">
                        <input type="text" name="Spec" max="55"  id="defdd"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Action Owner:</p>
                    </td>
                    <td>
                        <input type="text" name="ActionOwner" max="24"  id="defdd"/>
                    </td>
                    <td>
                        <p>Old Id:</p>
                    </td>
                    <td>
                        <input type="text" name="OldID" max="24" id="defdd"/>
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
                        <?php
                            $sqlE = "SELECT EviTypeID, EviType FROM EvidenceType ORDER BY EviType";
                             //if($result = mysqli_query($link,$sqlE)) {
                                    echo "<select name='EvidenceType' value='' id='defdd'></option>";
                                    echo "<option value=''></option>";
                                    foreach(mysqli_query($link,$sqlE) as $row) {
                                        echo "<option value=$row[EviTypeID]>$row[EviType]</option>";
                             }
                            echo "</select>";
                        ?>
                    </td>
                    <td>
                        <p>Evidence Link:<br>(SharePoint)</p>
                    </td>
                    <td>
                        <input type="text" name="EvidenceLink" max="255"  id='defdd'/>
                    </td>
                </tr>
                 <tr>
                    <td colspan='4' style="text-align:center">
                        <p>Deficiency Comments</p>
                    </td>
                </tr>
                <tr>
                    <td Colspan=4>
                        <textarea type="message"  rows="5" cols="99%" name="comments" max="1000"></textarea>
                    </td>
                </tr>
            </table><br>
            <input type="submit" class="button">
            <input type="reset" class="button">
        </FORM>
        <?php include('fileend.php');
        MySqli_Close($link); ?>
        <SCRIPT>
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1; //January is 0!
            var yyyy = today.getFullYear()+1;
             if(dd<10){
                    dd='0'+dd
                } 
                if(mm<10){
                    mm='0'+mm
                } 
            
            today = yyyy+'-'+mm+'-'+dd;
            document.getElementById("matchdate").setAttribute("max", today);
        </SCRIPT>
    </BODY>
</HTML>
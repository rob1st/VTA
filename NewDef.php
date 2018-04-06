<?php
include('session.php');
include('SQLFunctions.php');
$link = f_sqlConnect();
$Role = $_SESSION['Role'];
$title = "SVBX - New Deficiency";
    if($Role == 'V') {
        header('location: unauthorised.php');
    }
include('filestart.php') ?>
    <header class='container page-header'>
        <h1 class='page-title'>Add New Deficiency</h1>
    </header>
    <main class='container main-content'>
        <form action="RecDef.php" method="POST">
            <h5 class="bg-secondary text-white form-section-heading">Required Information</h5>
            <section class="form-section">
                <div class="form-row">
                    <label>Affect Safety Cert:
                        <select name'SafetyCert' value='' id='defdd' required>
                            <option value=''></option>
                            <option value='Y'>Yes</option>
                            <option value='N'>No</option>
                        </select>
                    </label>
                    <label>System Affected
                        <?php
                            $sqlY = "SELECT SystemID, System FROM System ORDER BY System";
                             //if($result = mysqli_query($link,$sqlY)) {
                                    echo "<select name='SystemAffected' value='' id='defdd' required>";
                                    echo "<option value=''></option>";
                                    foreach(mysqli_query($link,$sqlY) as $row) {
                                        echo "<option value=$row[SystemID]>$row[System]</option>";
                             }
                            echo "</select>";
                        ?>
                    </label>
                </div>
                <div class="form-row">
                    <label>General Location:
                        <?php
                            $sqlL = "SELECT LocationID, LocationName FROM Location ORDER BY LocationName";
                             //if($result = mysqli_query($link,$sqlL)) {
                                    echo "<select name='Location' value='' id='defdd' required>";
                                    echo "<option value=''></option>";
                                    foreach(mysqli_query($link,$sqlL) as $row) {
                                        echo "<option value=$row[LocationID]>$row[LocationName]</option>";
                             }
                            echo "</select>";
                        ?>
                    </label>
                    <label>Specific Location:
                        <input type="text" name="SpecLoc" max="55" id="defdd" required/>
                    </label>
                </div>
                <div class="form-row">
                    <label>Status:
                        <?php
                            $sqlT = "SELECT StatusID, Status FROM Status WHERE StatusID <> '3' ORDER BY StatusID";
                             //if($result = mysqli_query($link,$sqlT)) {
                                    echo "<select name='Status' value='' id='defdd' required>";
                                    echo "<option value=''></option>";
                                    foreach(mysqli_query($link,$sqlT) as $row) {
                                        echo "<option value=$row[StatusID]>$row[Status]</option>";
                             }
                            echo "</select>";
                        ?>
                    </label>
                    <label>Severity
                        <?php
                            $sqlS = "SELECT SeverityID, SeverityName FROM Severity ORDER BY SeverityName";
                             //if($result = mysqli_query($link,$sqlS)) {
                                    echo "<select name='Severity' value='' id='defdd' required>";
                                    echo "<option value=''></option>";
                                    foreach(mysqli_query($link,$sqlS) as $row) {
                                        echo "<option value=$row[SeverityID]>$row[SeverityName]</option>";
                             }
                            echo "</select>";
                        ?>
                    </label>
                </div>
                <div class="form-row">
                    <label>To be resolved by:
                        <input type='date' name='DueDate' id='defdd'/>
                    </label>
                    <label>Required for:
                        <?php
                            $sqlRB = "SELECT ReqByID, RequiredBy FROM RequiredBy ORDER BY ReqByID";
                             //if($result = mysqli_query($link,$sqlS)) {
                                    echo "<select name='RequiredBy' value='' id='defdd' required>";
                                    echo "<option value=''></option>";
                                    foreach(mysqli_query($link,$sqlRB) as $row) {
                                        echo "<option value=$row[ReqByID]>$row[RequiredBy]</option>";
                             }
                            echo "</select>";
                        ?>
                    </label>
                </div>
                <div class="form-row">
                    <label>Group to resolve:
                        <?php
                            $sqlY = "SELECT SystemID, System FROM System ORDER BY System";
                             //if($result = mysqli_query($link,$sqlY)) {
                                    echo "<select name='GroupToResolve' value='' id='defdd' required>";
                                    echo "<option value=''></option>";
                                    foreach(mysqli_query($link,$sqlY) as $row) {
                                        echo "<option value=$row[SystemID]>$row[System]</option>";
                             }
                            echo "</select>";
                        ?>
                    </label>
                    <label>Identified by:
                        <input type="text" name="IdentifiedBy" max="24" id="defdd" required/>
                    </label>
                </div>
                <label>Deficiency Description
                    <textarea type="message"  rows="5" cols="99%" name="description" max="1000" required></textarea>
                </label>
            </section>
            <h5 class="bg-secondary text-white form-section-heading">Optional Information</h5>
            <section class="form-section">
                <label>Spec or Code:
                    <input type="text" name="Spec" max="55"  id="defdd"/>
                </label>
                <label>Action Owner:
                    <input type="text" name="ActionOwner" max="24"  id="defdd"/>
                </label>
                <label>Old Id:
                    <input type="text" name="OldID" max="24" id="defdd"/>
                </label>
                <label>More Information
                    <textarea type="message"  rows="5" cols="99%" name="comments" max="1000"></textarea>
                </label>
            </section>
            <h5 class="bg-secondary text-white form-section-heading">Closure Information</h5>
            <section class="form-section">
                <label>Evidence Type:
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
                </label>
                <label>Evidence Repository:
                    <select name'Repo' value='' id='defdd'>
                        <option value=''></option>
                        <option value='A'>Aconex</option>
                        <option value='S'>SharePoint</option>
                    </select>
                </label>
                <label>Repository Number
                    <input type="text" name="EvidenceLink" max="255"  id='defdd'/>
                </label>
                <label>Closure Comments
                    <textarea type="message"  rows="5" cols="99%" name="ClosureComments" max="1000"></textarea>
                </label>
                <input type='submit' value='submit' class='btn btn-primary btn-lg' />
                <input type='reset' value='reset' class='btn btn-primary btn-lg' />
            </section>
        </form>
    </main>
<?php 
MySqli_Close($link);
include('fileend.php');
?>

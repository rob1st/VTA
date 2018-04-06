<?php
include('session.php');
include('SQLFunctions.php');
$link = f_sqlConnect();
$Role = $_SESSION['Role'];
$title = "SVBX - New Deficiency";
    if($Role == 'V') {
        header('location: unauthorised.php');
    }
include('filestart.php')
?>
<header class="container page-header">
    <h1 class="page-title">Add New Deficiency</h1>
</header>
<main role="main" class="container main-content">
    <form action="RecDef.php" method="POST">
        <fieldset class="form-section">
            <legend class="bg-secondary text-white form-section-heading">Required Information</legend>
            <div class="form-subsection form-group">
                <div class="form-element">
                    <label class="input-label">Affect Safety Cert</label>
                    <select name'SafetyCert' id='defdd' required>
                        <option value='Y'>Yes</option>
                        <option value='N'>No</option>
                    </select>
                </div>
                <div class="form-element">
                    <label class="input-label">System Affected</label>
                    <?php
                        $sqlY = "SELECT SystemID, System FROM System ORDER BY System";
                         //if($result = mysqli_query($link,$sqlY)) {
                                echo "<select name='SystemAffected' id='defdd' required>";
                                foreach(mysqli_query($link,$sqlY) as $row) {
                                    echo "<option value={$row[SystemID]}>{$row[System]}</option>";
                         }
                        echo "</select>";
                    ?>
                </div>
            </div>
            <div class="form-subsection form-group">
                <div class="form-element">
                    <label class="input-label">General Location</label>
                    <?php
                        $sqlL = "SELECT LocationID, LocationName FROM Location ORDER BY LocationName";
                         //if($result = mysqli_query($link,$sqlL)) {
                                echo "<select name='Location' id='defdd' required>";
                                foreach(mysqli_query($link,$sqlL) as $row) {
                                    echo "<option value={$row[LocationID]}>{$row[LocationName]}</option>";
                         }
                        echo "</select>";
                    ?>
                </div>
                <div class="form-element">
                    <label class="input-label">Specific Location</label>
                    <input type="text" name="SpecLoc" maxlength="55" id="defdd" required/>
                </div>
            </div>
            <div class="form-subsection form-group">
                <div class="form-element">
                    <label class="input-label">Status</label>
                    <?php
                        $sqlT = "SELECT StatusID, Status FROM Status WHERE StatusID <> '3' ORDER BY StatusID";
                         //if($result = mysqli_query($link,$sqlT)) {
                                echo "<select name='Status' id='defdd' required>";
                                foreach(mysqli_query($link,$sqlT) as $row) {
                                    echo "<option value={$row[StatusID]}>{$row[Status]}</option>";
                         }
                        echo "</select>";
                    ?>
                </div>
                <div class="form-element">
                    <label class="input-label">Severity</label>
                    <?php
                        $sqlS = "SELECT SeverityID, SeverityName FROM Severity ORDER BY SeverityName";
                         //if($result = mysqli_query($link,$sqlS)) {
                                echo "<select name='Severity' id='defdd' required>";
                                foreach(mysqli_query($link,$sqlS) as $row) {
                                    echo "<option value={$row[SeverityID]}>{$row[SeverityName]}</option>";
                         }
                        echo "</select>";
                    ?>
                </div>
            </div>
            <div class="form-subsection form-group">
                <div class="form-element">
                    <label class="input-label">To be resolved by</label>
                    <input type='date' name='DueDate' id='defdd'/>
                </div>
                <div class="form-element">
                    <label class="input-label">Required for</label>
                    <?php
                        $sqlRB = "SELECT ReqByID, RequiredBy FROM RequiredBy ORDER BY ReqByID";
                         //if($result = mysqli_query($link,$sqlS)) {
                                echo "<select name='RequiredBy' id='defdd' required>";
                                foreach(mysqli_query($link,$sqlRB) as $row) {
                                    echo "<option value=$row[ReqByID]>$row[RequiredBy]</option>";
                         }
                        echo "</select>";
                    ?>
                </div>
            </div>
            <div class="form-subsection form-group">
                <div class="form-element">
                    <label class="input-label">Group to resolve</label>
                    <?php
                        $sqlY = "SELECT SystemID, System FROM System ORDER BY System";
                         //if($result = mysqli_query($link,$sqlY)) {
                                echo "<select name='GroupToResolve' id='defdd' required>";
                                foreach(mysqli_query($link,$sqlY) as $row) {
                                    echo "<option value=$row[SystemID]>$row[System]</option>";
                         }
                        echo "</select>";
                    ?>
                </div>
                <div class="form-element">
                    <label class="input-label">Identified by</label>
                    <input type="text" name="IdentifiedBy" maxlength="24" id="defdd" required/>
                </div>
            </div>
            <div class="constrainer form-subsection center-element">
                <div class="form-element">
                    <label class="input-label label-breakline">Deficiency Description</label>
                    <textarea name="description" rows="5" maxlength="1000" required class="textarea-full-width"></textarea>
                </div>
            </div>
        </fieldset>
        <fieldset class="form-section">
            <legend class="bg-secondary text-white form-section-heading">Optional Information</legend>
            <div class="form-subsection form-group">
                <div class="form-element">
                    <label class="input-label">Spec or Code</label>
                    <input type="text" name="Spec" maxlength="55"  id="defdd"/>
                </div>
            </div>
            <div class="form-subsection form-group">
                <div class="form-element">
                    <label class="input-label">Action Owner</label>
                    <input type="text" name="ActionOwner" maxlength="24"  id="defdd"/>
                </div>
                <div class="form-element">
                    <label class="input-label">Old Id</label>
                    <input type="text" name="OldID" maxlength="24" id="defdd"/>
                </div>
            </div>
            <div class="constrainer form-subsection center-element">
                <div class="form-element">
                    <label class="input-label label-breakline">More Information</label>
                    <textarea name="comments" rows="5" maxlength="1000" class="textarea-full-width"></textarea>
                </div>
            </div>
        </fieldset>
        <fieldset class="form-section">
            <legend class="bg-secondary text-white form-section-heading">Closure Information</legend>
            <div class="form-subsection form-group">
                <div class="form-element">
                    <label class="input-label">Evidence Type</label>
                    <?php
                        $sqlE = "SELECT EviTypeID, EviType FROM EvidenceType ORDER BY EviType";
                         //if($result = mysqli_query($link,$sqlE)) {
                                echo "<select name='EvidenceType' id='defdd'></option>";
                                foreach(mysqli_query($link,$sqlE) as $row) {
                                    echo "<option value=$row[EviTypeID]>$row[EviType]</option>";
                         }
                        echo "</select>";
                    ?>
                </div>
            </div>
            <div class="form-subsection form-group">
                <div class="form-element">
                    <label class="input-label">Evidence Repository</label>
                    <select name'Repo' id='defdd'>
                        <option value=''></option>
                        <option value='A'>Aconex</option>
                        <option value='S'>SharePoint</option>
                    </select>
                </div>
                <div class="form-element">
                    <label class="input-label">Repository Number</label>
                    <input type="text" name="EvidenceLink" maxlength="255"  id='defdd'/>
                </div>
            </div>
            <div class="constrainer form-subsection center-element">
                <div class="form-element">
                    <label class="input-label label-breakline">Closure Comments</label>
                    <textarea name="ClosureComments" rows="5" maxlength="1000" class="textarea-full-width"></textarea>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <input type='submit' value='submit' class='btn btn-primary btn-lg' />
            <input type='reset' value='reset' class='btn btn-primary btn-lg' />
        </fieldset>
    </form>
</main>
<?php 
MySqli_Close($link);
include('fileend.php');
?>

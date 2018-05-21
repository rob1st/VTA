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
        <?php
            $formCtrls = [
                'Description' => [
                    "label" => "<label for='Description'>Deficiency Description</label>",
                    "tagName" => "textarea",
                    "element" => "<textarea name='Description' id='Description' class='form-control' maxlength='1000'>%s</textarea>",
                    "type" => null,
                    "name" => 'Description',
                    "id" => 'Description',
                    "query" => null,
                ],
                'comments' => [
                    "label" => "<label for='comments'>More Information</label>",
                    "tagName" => "textarea",
                    "element" => "<textarea name='comments' id='comments' class='form-control' maxlength='1000'>%s</textarea>",
                    "type" => null,
                    "name" => 'comments',
                    "id" => 'comments',
                    "query" => null
                ],
                'ClosureComments' => [
                    "label" => "<label for='ClosureComments'>Closure Comments</label>",
                    "tagName" => "textarea",
                    "element" => "<textarea name='ClosureComments' id='ClosureComments' class='form-control' maxlength='1000'>%s</textarea>",
                    "type" => null,
                    "name" => 'ClosureComments',
                    "id" => 'ClosureComments',
                    "query" => null
                ]
            ];
            
            $requiredRows = [
                [
                    'SafetyCert' => [
                        "label" => "<label for='SafetyCert'>Safety Certifiable</label>",
                        "tagName" => 'select',
                        "type" => '',
                        "name" => 'SafetyCert',
                        "id" => 'SafetyCert',
                        "query" => "SELECT YesNoID, YesNo FROM YesNo ORDER BY YesNo",
                        'value' => $safetyCert
                    ],
                    'SystemAffected' => [
                        "label" => "<label for='SystemAffected'>System Affected</label>",
                        "tagName" => 'select',
                        "type" => '',
                        "name" => 'SystemAffected',
                        "id" => 'SystemAffected',
                        "query" => "SELECT SystemID, System FROM System ORDER BY System",
                        'value' => $systemAffected
                    ]
                ],
                [
                    'LocationName' => [
                        "label" => "<label for='LocationName'>General Location</label>",
                        "tagName" => 'select',
                        "type" => '',
                        "name" => 'LocationName',
                        "id" => 'LocationName',
                        "query" => "SELECT LocationID, LocationName FROM Location ORDER BY LocationName",
                        'value' => $locationName
                    ],
                    'SpecLoc' => [
                        "label" => "<label for='SpecLoc'>Specific Location</label>",
                        "tagName" => "input",
                        "element" => "<input type='text' name='SpecLoc' id='SpecLoc' value='%s' class='form-control'>",
                        "type" => 'text',
                        "name" => 'SpecLoc',
                        "id" => 'SpecLoc',
                        "query" => null,
                        'value' => $specLoc
                    ]
                ],
                [
                    'Status' => [
                        "label" => "<label for='Status'>Status</label>",
                        "tagName" => "select",
                        "element" => "<select name='SpecLoc' id='SpecLoc' class='form-control'>",
                        "type" => null,
                        "name" => 'Status',
                        "id" => 'Status',
                        "query" => "SELECT StatusID, Status FROM Status WHERE StatusID <> 3 ORDER BY StatusID",
                        'value' => $status
                    ],
                    'SeverityName' => [
                        "label" => "<label for='SeverityName'>Severity</label>",
                        "tagName" => "select",
                        "element" => "<select name='SeverityName' id='SeverityName' class='form-control'>",
                        "type" => null,
                        "name" => 'SeverityName',
                        "id" => 'SeverityName',
                        "query" => "SELECT SeverityID, SeverityName FROM Severity ORDER BY SeverityName",
                        'value' => $severityName
                    ]
                ],
                [
                    'DueDate' => [
                        "label" => "<label for='DueDate'>To be resolved by</label>",
                        "tagName" => "input",
                        "element" => "<input type='date' name='DueDate' id='DueDate' value='%s' class='form-control'>",
                        "type" => 'date',
                        "name" => 'DueDate',
                        "id" => 'DueDate',
                        "query" => null,
                        'value' => $dueDate
                    ],
                    'GroupToResolve' =>[
                        "label" => "<label for='GroupToResolve'>Group to Resolve</label>",
                        "tagName" => "select",
                        "element" => "<select name='GroupToResolve' id='GroupToResolve' class='form-control'>",
                        "type" => null,
                        "name" => 'GroupToResolve',
                        "id" => 'GroupToResolve',
                        "query" => "SELECT SystemID, System FROM System ORDER BY System",
                        'value' => $groupToResolve
                    ]
                ],
                [
                    'RequiredBy' => [
                        "label" => "<label for='RequiredBy'>Required for</label>",
                        "tagName" => "select",
                        "element" => "<select name='RequiredBy' id='RequiredBy' class='form-control'>",
                        "type" => null,
                        "name" => 'RequiredBy',
                        "id" => 'RequiredBy',
                        "query" => "SELECT ReqByID, RequiredBy FROM RequiredBy ORDER BY RequiredBy",
                        'value' => $requiredBy
                    ],
                    'contract' => [
                        'label' => "<label for='contract'>Contact</label>",
                        'tagName' => 'select',
                        'element' => "
                            <label for='contract'>Contact</label>
                            <select name='contractID' id='contractID' class='form-control'>%s</select>",
                        'type' => null,
                        'name' => 'contractID',
                        'id' => 'contractID',
                        'query' => "SELECT contractID, contract FROM Contract ORDER BY contractID",
                        'value' => $contract
                    ]
                ],
                [
                    'IdentifiedBy' => [
                        "label" => "<label for='IdentifiedBy'>Identified By</label>",
                        "tagName" => "input",
                        "element" => "<input type='text' name='IdentifiedBy' id='IdentifiedBy' class='form-control' value='%s'>",
                        "type" => 'text',
                        "name" => 'IdentifiedBy',
                        "id" => 'IdentifiedBy',
                        "query" => null,
                        'value' => $identifiedBy
                    ],
                    'defType' => [
                        'label' => "<label for='defType'>Deficiency type</label>",
                        'tagName' => "select",
                        'element' => "<select name='defType' id='defType' class='form-control'>",
                        'type' => null,
                        'name' => 'defType',
                        'id' => 'defType',
                        'query' => 'SELECT defTypeID, defTypeName FROM defType',
                        'value' => $defType
                    ]
                ]
            ];
            
            $optionalRows = [
                [
                    'Spec' => [
                        "label" => "<label for='Spec'>Spec or Code</label>",
                        "tagName" => "input",
                        "element" => "<input type='text' name='Spec' id='Spec' value='%s' class='form-control'>",
                        "type" => 'text',
                        "name" => 'Spec',
                        "id" => 'Spec',
                        "query" => null,
                        'value' => $spec
                    ],
                    'ActionOwner' => [
                        "label" => "<label for='ActionOwner'>Action Owner</label>",
                        "tagName" => "input",
                        "element" => "<input type='text' name='ActionOwner' id='ActionOwner' value='%s' class='form-control'>",
                        "type" => 'text',
                        "name" => 'ActionOwner',
                        "id" => 'ActionOwner',
                        "query" => null,
                        'value' => $actionOwner
                    ]
                ],
                [
                    'OldID' => [
                        "label" => "<label for='OldID'>Old Id</label>",
                        "tagName" => "input",
                        "element" => "<input type='text' name='OldID' id='OldID' value='%s' class='form-control'>",
                        "type" => 'text',
                        "name" => 'OldID',
                        "id" => 'OldID',
                        "query" => null,
                        'value' => $oldID
                    ],
                    'CDL_pics' => [
                        'label' => "<label for='CDL_pics'>Upload Photo</label>",
                        'tagName' => 'input',
                        'element' => "<input type='file' accept='image/*' name='CDL_pics' id='CDL_pics' class='form-control form-control-file'>",
                        'type' => 'file',
                        'name' => 'CDL_pics',
                        'id' => 'CDL_pics',
                        'query' => null // this will need a query for photo evidence
                    ]
                ]
            ];
            
            $closureRows = [
                [
                    'EviType' => [
                        "label" => "<label for='EviType'>Evidence Type</label>",
                        "tagName" => 'select',
                        'element' => "<select name='EviType' id='EviType' class='form-control'>%s</select>",
                        "type" => '',
                        "name" => 'EviType',
                        "id" => 'EviType',
                        "query" => "SELECT EviTypeID, EviType FROM EvidenceType ORDER BY EviType",
                        'value' => $eviType
                    ],
                    'Repo' => [
                        'label' => "<label for='Repo'>Evidence Repository</label>",
                        'tagName' => 'select',
                        'element' => "<select name='Repo' id='Repo' class='form-control'>%s</select>",
                        'type' => '',
                        'name' => 'Repo',
                        'id' => 'Repo',
                        'query' => "SELECT RepoID, Repo FROM Repo ORDER BY Repo",
                        'value' => $repo
                    ],
                    'EvidenceLink' => [
                        'label' => "<label for='EvidenceLink'>Repository Number</label>",
                        'tagName' => "input",
                        'element' => "<input type='text' name='EvidenceLink' id='EvidenceLink' class='form-control' value='%s'>",
                        'type' => 'text',
                        'name' => 'EvidenceLink',
                        'id' => 'EvidenceLink',
                        'query' => null,
                        'value' => $evidenceLink
                    ]
                ]
            ];
            
            foreach ($requiredRows as $gridRow) {
                print returnRow($gridRow, ['inline' => true]);
            }
            echo returnRow([$formCtrls['Description']], ['colWd' => 6]);
            
            echo "
                <h5 class='grey-bg pad'>
                    <a data-toggle='collapse' href='#optionalInfo' role='button' aria-expanded='false' aria-controls='optionalInfo' class='collapsed'>Optional Information<i class='typcn typcn-arrow-sorted-down'></i></a>
                </h5>
                <div id='optionalInfo' class='collapse item-margin-bottom'>";
            foreach ($optionalRows as $gridRow) {
                print returnRow($gridRow, ['inline' => true]);
            }
                echo returnRow([$formCtrls['comments']], ['colWd' => 6]);
            echo "</div>";
            
            echo "
                <h5 class='grey-bg pad'>
                    <a data-toggle='collapse' href='#closureInfo' role='button' aria-expanded='false' aria-controls='closureInfo' class='collapsed'>Closure Information<i class='typcn typcn-arrow-sorted-down'></i></a>
                </h5>
                <div id='closureInfo' class='collapse item-margin-bottom'>";
            foreach ($closureRows as $gridRow) {
                print returnRow($gridRow, ['inline' => true]);
            }
                echo returnRow([$formCtrls['ClosureComments']], ['colWd' => 6]);
            echo "</div>";

        ?>
        <fieldset class="item-margin-bottom">
            <legend class="bg-secondary text-white form-section-heading">Required Information</legend>
            <div class="form-subsection item-border-bottom item-margin-bottom form-group">
                <div class="half-container">
                    <label class="input-label item-margin-right">Affect Safety Cert</label>
                    <select name='SafetyCert' class="form-control def-input" required>
                        <option value='' disabled selected>Y/N</option>
                        <option value='1'>Yes</option>
                        <option value='2'>No</option>
                    </select>
                </div>
                <div class="half-container">
                    <label class="input-label item-margin-right">System Affected</label>
                    <?php
                        $sqlY = "SELECT SystemID, System FROM System ORDER BY System";
                         //if($result = mysqli_query($link,$sqlY)) {
                                echo "<select name='SystemAffected' class='form-control def-input' required>
                                        <option value='' disabled selected>Choose System</option>";
                                foreach(mysqli_query($link,$sqlY) as $row) {
                                    echo "<option value={$row[SystemID]}>{$row[System]}</option>";
                         }
                        echo "</select>";
                    ?>
                </div>
            </div>
            <div class="form-subsection item-border-bottom item-margin-bottom form-group">
                <div class="half-container">
                    <label class="input-label item-margin-right">General Location</label>
                    <?php
                        $sqlL = "SELECT LocationID, LocationName FROM Location ORDER BY LocationName";
                         //if($result = mysqli_query($link,$sqlL)) {
                                echo "<select name='Location' class='form-control def-input' required>
                                        <option value='' disabled selected>Choose Location</option>";
                                foreach(mysqli_query($link,$sqlL) as $row) {
                                    echo "<option value={$row[LocationID]}>{$row[LocationName]}</option>";
                         }
                        echo "</select>";
                    ?>
                </div>
                <div class="half-container">
                    <label class="input-label item-margin-right">Specific Location</label>
                    <input type="text" name="SpecLoc" maxlength="55" class="form-control def-input" required/>
                </div>
            </div>
            <div class="form-subsection item-border-bottom item-margin-bottom form-group">
                <div class="half-container">
                    <label class="input-label item-margin-right">Status</label>
                    <?php
                        $sqlT = "SELECT StatusID, Status FROM Status WHERE StatusID <> '3' ORDER BY StatusID";
                         //if($result = mysqli_query($link,$sqlT)) {
                                echo "<select name='Status' class='form-control def-input' required>
                                        <option value='' disabled selected>Choose Status</option>";
                                foreach(mysqli_query($link,$sqlT) as $row) {
                                    echo "<option value={$row[StatusID]}>{$row[Status]}</option>";
                         }
                        echo "</select>";
                    ?>
                </div>
                <div class="half-container">
                    <label class="input-label item-margin-right">Severity</label>
                    <?php
                        $sqlS = "SELECT SeverityID, SeverityName FROM Severity ORDER BY SeverityName";
                         //if($result = mysqli_query($link,$sqlS)) {
                                echo "<select name='Severity' class='form-control def-input' required>
                                        <option value='' disabled selected>Choose Severity</option>";
                                foreach(mysqli_query($link,$sqlS) as $row) {
                                    echo "<option value={$row[SeverityID]}>{$row[SeverityName]}</option>";
                         }
                        echo "</select>";
                    ?>
                </div>
            </div>
            <div class="form-subsection item-border-bottom item-margin-bottom form-group">
                <div class="half-container">
                    <label class="input-label item-margin-right">To be resolved by</label>
                    <input type='date' name='DueDate' class="form-control def-input" required/>
                </div>
                <div class="half-container">
                    <label class="input-label item-margin-right">Required for</label>
                    <?php
                        $sqlRB = "SELECT ReqByID, RequiredBy FROM RequiredBy ORDER BY ReqByID";
                         //if($result = mysqli_query($link,$sqlS)) {
                                echo "<select name='RequiredBy' class='form-control def-input' required>
                                        <option value='' disabled selected>Requirement</option>";
                                foreach(mysqli_query($link,$sqlRB) as $row) {
                                    echo "<option value=$row[ReqByID]>$row[RequiredBy]</option>";
                         }
                        echo "</select>";
                    ?>
                </div>
            </div>
            <div class="form-subsection item-border-bottom item-margin-bottom form-group">
                <div class="half-container">
                    <label class="input-label item-margin-right">Group to resolve</label>
                    <?php
                        $sqlY = "SELECT SystemID, System FROM System ORDER BY System";
                         //if($result = mysqli_query($link,$sqlY)) {
                                echo "<select name='GroupToResolve' class='form-control def-input' required>
                                        <option value='' disabled selected>Group</option>";
                                foreach(mysqli_query($link,$sqlY) as $row) {
                                    echo "<option value=$row[SystemID]>$row[System]</option>";
                         }
                        echo "</select>";
                    ?>
                </div>
                <div class="half-container">
                    <label class="input-label item-margin-right">Identified by</label>
                    <input type="text" name="IdentifiedBy" maxlength="24" class="form-control def-input" required/>
                </div>
            </div>
            <div class="constrainer form-subsection item-border-bottom item-margin-bottom center-element">
                <div class="flex-column no-wrap">
                    <label class="input-label label-breakline">Deficiency Description</label>
                    <textarea name="description" rows="5" maxlength="1000" class="form-control textarea-full-width" required></textarea>
                </div>
            </div>
        </fieldset>
        <fieldset class="item-margin-bottom">
            <legend class="bg-secondary text-white form-section-heading">Optional Information</legend>
            <div class="form-subsection item-border-bottom item-margin-bottom form-group">
                <div class="half-container">
                    <label class="input-label item-margin-right">Spec or Code</label>
                    <input type="text" name="Spec" maxlength="55"  id="" class="form-control def-input"/>
                </div>
            </div>
            <div class="form-subsection item-border-bottom item-margin-bottom form-group">
                <div class="half-container">
                    <label class="input-label item-margin-right">Action Owner</label>
                    <input type="text" name="ActionOwner" maxlength="24"  id="" class="form-control def-input"/>
                </div>
                <div class="half-container">
                    <label class="input-label item-margin-right">Old Id</label>
                    <input type="text" name="OldID" maxlength="24" id="" class="form-control def-input"/>
                </div>
            </div>
            <div class="constrainer form-subsection item-border-bottom item-margin-bottom item-margin-bottom center-element">
                <div class="flex-column no-wrap">
                    <label class="input-label label-breakline">More Information</label>
                    <textarea name="comments" rows="5" maxlength="1000" class="form-control textarea-full-width"></textarea>
                </div>
            </div>
        </fieldset>
        <fieldset class="item-margin-bottom">
            <legend class="bg-secondary text-white form-section-heading">Closure Information</legend>
            <div class="form-subsection item-border-bottom item-margin-bottom form-group">
                <div class="half-container">
                    <label class="input-label item-margin-right">Evidence Type</label>
                    <?php
                        $sqlE = "SELECT EviTypeID, EviType FROM EvidenceType ORDER BY EviType";
                         //if($result = mysqli_query($link,$sqlE)) {
                                echo "<select name='EvidenceType' id='' class='form-control def-input'></option>
                                        <option value='' disabled selected>Choose Type</option>";
                                foreach(mysqli_query($link,$sqlE) as $row) {
                                    echo "<option value=$row[EviTypeID]>$row[EviType]</option>";
                         }
                        echo "</select>";
                    ?>
                </div>
            </div>
            <div class="form-subsection item-border-bottom item-margin-bottom form-group">
                <div class="half-container">
                    <label class="input-label item-margin-right">Evidence Repository</label>
                    <select name='Repo' id='' class="form-control def-input">
                        <option value='' disabled selected>Choose Repo</option>
                        <option value='2'>Aconex</option>
                        <option value='1'>SharePoint</option>
                    </select>
                </div>
                <div class="half-container">
                    <label class="input-label item-margin-right">Repository Number</label>
                    <input type="text" name="EvidenceLink" maxlength="255"  id='' class="form-control def-input"/>
                </div>
            </div>
            <div class="constrainer form-subsection item-border-bottom item-margin-bottom center-element">
                <div class="flex-column no-wrap">
                    <label class="input-label label-breakline">Closure Comments</label>
                    <textarea name="ClosureComments" rows="5" maxlength="1000" class="form-control textarea-full-width"></textarea>
                </div>
            </div>
        </fieldset>
        <fieldset class="center-content">
            <button type='submit' value='submit' class='btn btn-primary btn-lg'>Submit</button>
            <button type='reset' value='reset' class='btn btn-primary btn-lg'>Reset</button>
        </fieldset>
    </form>
</main>
<?php 
MySqli_Close($link);
include('fileend.php');
?>

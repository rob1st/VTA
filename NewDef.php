<?php
include('session.php');
include('SQLFunctions.php');
include('html_functions/bootstrapGrid.php');
include('html_functions/htmlForms.php');
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
    <h4 class='text-indigo'><?php print $_SESSION['UserID'] ?></h4>
</header>
<main role="main" class="container main-content">
    <form action="RecDef.php" method="POST">
        <input type='hidden' name='username' value='<?php print $_SESSION['Username'] ?>' />
        <?php
            $requiredRows = [
                [
                    'SafetyCert' => [
                        "label" => "<label for='SafetyCert'>Safety Certifiable</label>",
                        "tagName" => 'select',
                        "type" => '',
                        "name" => 'SafetyCert',
                        "id" => 'SafetyCert',
                        "query" => "SELECT YesNoID, YesNo FROM YesNo ORDER BY YesNo",
                    ],
                    'SystemAffected' => [
                        "label" => "<label for='SystemAffected'>System Affected</label>",
                        "tagName" => 'select',
                        "type" => '',
                        "name" => 'SystemAffected',
                        "id" => 'SystemAffected',
                        "query" => "SELECT SystemID, System FROM System ORDER BY System",
                    ]
                ],
                [
                    'Location' => [
                        "label" => "<label for='Location'>General Location</label>",
                        "tagName" => 'select',
                        "type" => '',
                        "name" => 'Location',
                        "id" => 'Location',
                        "query" => "SELECT LocationID, LocationName FROM Location ORDER BY LocationName",
                    ],
                    'SpecLoc' => [
                        "label" => "<label for='SpecLoc'>Specific Location</label>",
                        "tagName" => "input",
                        "element" => "<input type='text' name='SpecLoc' id='SpecLoc' value='%s' class='form-control'>",
                        "type" => 'text',
                        "name" => 'SpecLoc',
                        "id" => 'SpecLoc',
                        "query" => null,
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
                    ],
                    'Severity' => [
                        "label" => "<label for='Severity'>Severity</label>",
                        "tagName" => "select",
                        "element" => "<select name='Severity' id='Severity' class='form-control'>",
                        "type" => null,
                        "name" => 'Severity',
                        "id" => 'Severity',
                        "query" => "SELECT SeverityID, SeverityName FROM Severity ORDER BY SeverityName",
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
                    ],
                    'GroupToResolve' =>[
                        "label" => "<label for='GroupToResolve'>Group to Resolve</label>",
                        "tagName" => "select",
                        "element" => "<select name='GroupToResolve' id='GroupToResolve' class='form-control'>",
                        "type" => null,
                        "name" => 'GroupToResolve',
                        "id" => 'GroupToResolve',
                        "query" => "SELECT SystemID, System FROM System ORDER BY System",
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
                    ],
                    'contractID' => [
                        'label' => "<label for='contractID'>Contract</label>",
                        'tagName' => 'select',
                        'element' => "
                            <label for='contractID'>Contact</label>
                            <select name='contractID' id='contractID' class='form-control'>%s</select>",
                        'type' => null,
                        'name' => 'contractID',
                        'id' => 'contractID',
                        'query' => "SELECT contractID, contract FROM Contract ORDER BY contractID",
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
                    ],
                    'defType' => [
                        'label' => "<label for='defType'>Deficiency type</label>",
                        'tagName' => "select",
                        'element' => "<select name='defType' id='defType' class='form-control'>",
                        'type' => null,
                        'name' => 'defType',
                        'id' => 'defType',
                        'query' => 'SELECT defTypeID, defTypeName FROM defType',
                    ]
                ],
                [
                    'Description' => [
                        "label" => "<label for='Description'>Deficiency Description</label>",
                        "tagName" => "textarea",
                        "element" => "<textarea name='Description' id='Description' class='form-control' maxlength='1000'>%s</textarea>",
                        "type" => null,
                        "name" => 'Description',
                        "id" => 'Description',
                        "query" => null
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
                    ],
                    'ActionOwner' => [
                        "label" => "<label for='ActionOwner'>Action Owner</label>",
                        "tagName" => "input",
                        "element" => "<input type='text' name='ActionOwner' id='ActionOwner' value='%s' class='form-control'>",
                        "type" => 'text',
                        "name" => 'ActionOwner',
                        "id" => 'ActionOwner',
                        "query" => null,
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
                ],
                [
                    'comments' => [
                        "label" => "<label for='comments'>More Information</label>",
                        "tagName" => "textarea",
                        "element" => "<textarea name='comments' id='comments' class='form-control' maxlength='1000'>%s</textarea>",
                        "type" => null,
                        "name" => 'comments',
                        "id" => 'comments',
                        "query" => null
                    ]
                ]
            ];
            
            $closureRows = [
                [
                    'EvidenceType' => [
                        "label" => "<label for='EvidenceType'>Evidence Type</label>",
                        "tagName" => 'select',
                        'element' => "<select name='EvidenceType' id='EvidenceType' class='form-control'>%s</select>",
                        "type" => '',
                        "name" => 'EvidenceType',
                        "id" => 'EvidenceType',
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
                ],
                [
                    'ClosureComments' => [
                        "label" => "<label for='ClosureComments'>Closure Comments</label>",
                        "tagName" => "textarea",
                        "element" => "<textarea name='ClosureComments' id='ClosureComments' class='form-control' maxlength='1000'>%s</textarea>",
                        "type" => null,
                        "name" => 'ClosureComments',
                        "id" => 'ClosureComments',
                        "query" => null
                    ]
                ]
            ];
            
            echo "<h5 class='grey-bg pad'>Required Information</h5>";
            foreach ($requiredRows as $gridRow) {
                $options = count($gridRow) > 1 ? ['inline' => true] : ['colWd' => 6];
                print returnRow($gridRow, $options);
            }
            
            echo "
                <h5 class='grey-bg pad'>
                    <a data-toggle='collapse' href='#optionalInfo' role='button' aria-expanded='false' aria-controls='optionalInfo' class='collapsed'>Optional Information<i class='typcn typcn-arrow-sorted-down'></i></a>
                </h5>
                <div id='optionalInfo' class='collapse item-margin-bottom'>";
            foreach ($optionalRows as $gridRow) {
                $options = count($gridRow) > 1 ? ['inline' => true] : ['colWd' => 6];
                print returnRow($gridRow, $options);
            }
            echo "</div>";
            
            echo "
                <h5 class='grey-bg pad'>
                    <a data-toggle='collapse' href='#closureInfo' role='button' aria-expanded='false' aria-controls='closureInfo' class='collapsed'>Closure Information<i class='typcn typcn-arrow-sorted-down'></i></a>
                </h5>
                <div id='closureInfo' class='collapse item-margin-bottom'>";
            foreach ($closureRows as $gridRow) {
                $options = count($gridRow) > 1 ? ['inline' => true] : ['colWd' => 6];
                print returnRow($gridRow, $options);
            }
            echo "</div>";

        ?>
        <div class="center-content">
            <button type='submit' value='submit' class='btn btn-primary btn-lg'>Submit</button>
            <button type='reset' value='reset' class='btn btn-primary btn-lg'>Reset</button>
        </div>
    </form>
</main>
<?php 
MySqli_Close($link);
include('fileend.php');
?>

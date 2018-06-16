<?php
$attachmentFormats = preg_replace('/\s+/', ' ', file_get_contents('allowedFormats.csv'));

function returnLabel($for, $text, $required = '', $str = "<label for='%s'%s>%s</label>") {
    $required && $requiredAttr = " class='required'";
    return sprintf($str, $for, $requiredAttr, $text);
}

function checkboxLabel($for, $text, $required = '') {
    $str =  "<label for='%s' class='form-check-label check-label-left'%s>%s</label>";
    return returnLabel($for, $text, $required, $str);
}

function getAttachments($cnxn, $id) {
    $sql = "SELECT bdaFilepath, filename from bartdlAttachments WHERE bartdlID = ?";
    if (!$stmt = $cnxn->prepare($sql)) printSqlErrorAndExit($cnxn, $sql);
    if (!$stmt->bind_param('i', intval($id))) printSqlErrorAndExit($stmt, $sql);
    if (!$stmt->execute()) printSqlErrorAndExit($stmt, $sql);
    $attachments = stmtBindResultArray($stmt) ?: [];
    $stmt->close();
    return $attachments;
}

function renderAttachmentsAsAnchors(array $attachments = []) {
    $list = '';
    if (count($attachments)) {
        foreach ($attachments as $attachment) {
            $list .= "<li><a href='"
                .htmlentities(stripcslashes($attachment['bdaFilepath']))."'>"
                .htmlentities(stripcslashes($attachment['filename']))."</a></li>";
        }
    }
    return sprintf("<ul class='pl-0 mb-0'>%s</ul>", $list);
}

$commentFormat = "
    <div class='thin-grey-border pad mb-3'>
        <h6 class='d-flex flex-row justify-content-between text-secondary'><span>%s</span><span>%s</span></h6>
        <p>%s</p>
    </div>";

$requiredElements = [
    'safetyCert' => [
        "label" => returnLabel('safetyCert', 'Safety Certifiable', 1),
        "tagName" => 'select',
        'element' => "<select name='safetyCert' id='safetyCert' class='form-control' required>%s</select>",
        "type" => '',
        "query" => "SELECT YesNoID, YesNo FROM YesNo ORDER BY YesNo",
        'value' => ''
    ],
    'systemAffected' => [
        "label" => returnLabel('systemAffected', 'System Affected', 1),
        "tagName" => 'select',
        'element' => "<select name='systemAffected' id='systemAffected' class='form-control' required>%s</select>",
        "type" => '',
        "query" => "SELECT SystemID, System FROM System ORDER BY System",
        'value' => ''
    ],
    'locationName' => [
        "label" => returnLabel('locationName', 'General Location', 1),
        "tagName" => 'select',
        'element' => "<select name='locationName' id='locationName' class='form-control' required>%s</select>",
        "type" => '',
        "query" => "SELECT LocationID, LocationName FROM Location ORDER BY LocationName",
        'value' => ''
    ],
    'specLoc' => [
        "label" => returnLabel('specLoc', 'Specific Location', 1),
        "tagName" => "input",
        "element" => "<input type='text' name='specLoc' id='specLoc' value='%s' class='form-control' required>",
        "type" => 'text',
        "name" => 'SpecLoc',
        "id" => 'SpecLoc',
        "query" => null,
        'value' => ''
    ],
    'status' => [
        "label" => returnLabel('status', 'Status', 1),
        "tagName" => "select",
        "element" => "<select name='status' id='status' class='form-control' required>%s</select>",
        "type" => null,
        "query" => "SELECT StatusID, Status FROM Status WHERE StatusID <> 3 ORDER BY StatusID",
        'value' => ''
    ],
    'severityName' => [
        "label" => returnLabel('severityName', 'Severity', 1),
        "tagName" => "select",
        "element" => "<select name='severityName' id='severityName' class='form-control' required>%s</select>",
        "type" => null,
        "query" => "SELECT SeverityID, SeverityName FROM Severity ORDER BY SeverityName",
        'value' => ''
    ],
    'dueDate' => [
        "label" => returnLabel('dueDate', 'To be resolved by', 1),
        "tagName" => "input",
        "element" => "<input type='date' name='dueDate' id='dueDate' value='%s' class='form-control' required>",
        "type" => 'date',
        "query" => null,
        'value' => ''
    ],
    'groupToResolve' =>[
        "label" => returnLabel('groupToResolve', 'Group to Resolve', 1),
        "tagName" => "select",
        "element" => "<select name='groupToResolve' id='groupToResolve' class='form-control' required>%s</select>",
        "type" => null,
        "query" => "SELECT SystemID, System FROM System ORDER BY System",
        'value' => ''
    ],
    'requiredBy' => [
        "label" => returnLabel('requiredBy', 'Required for', 1),
        "tagName" => "select",
        "element" => "<select name='requiredBy' id='requiredBy' class='form-control' required>%s</select>",
        "type" => null,
        "query" => "SELECT ReqByID, RequiredBy FROM RequiredBy ORDER BY RequiredBy",
        'value' => ''
    ],
    'contract' => [
        'label' => returnLabel('contract', 'Contract', 1),
        'tagName' => 'select',
        'element' => "<select name='contractID' id='contractID' class='form-control' required>%s</select>",
        'type' => null,
        'query' => "SELECT contractID, contract FROM Contract ORDER BY contractID",
        'value' => ''
    ],
    'identifiedBy' => [
        "label" => returnLabel('identifiedBy', 'Identified By', 1),
        "tagName" => "input",
        "element" => "<input type='text' name='identifiedBy' id='identifiedBy' class='form-control' value='%s' required>",
        "type" => 'text',
        "query" => null,
        'value' => ''
    ],
    'defType' => [
        'label' => returnLabel('defType', 'Deficiency type', 1),
        'tagName' => "select",
        'element' => "<select name='defType' id='defType' class='form-control' required>%s</select>",
        'type' => null,
        'query' => 'SELECT defTypeID, defTypeName FROM defType',
        'value' => ''
    ],
    'description' => [
        "label" => returnLabel('Description', 'Deficiency description', 1),
        "tagName" => "textarea",
        "element" => "<textarea name='Description' id='Description' class='form-control' maxlength='1000' required>%s</textarea>",
        "type" => null,
        "query" => null,
        'value' => ''
    ]
];

$optionalElements = [
    'spec' => [
        "label" => returnLabel('spec', 'Spec or Code'),
        "tagName" => "input",
        "element" => "<input type='text' name='spec' id='spec' value='%s' class='form-control'>",
        "type" => 'text',
        "query" => null,
        'value' => ''
    ],
    'actionOwner' => [
        "label" => returnLabel('actionOwner', 'Action Owner'),
        "tagName" => "input",
        "element" => "<input type='text' name='actionOwner' id='actionOwner' value='%s' class='form-control'>",
        "type" => 'text',
        "query" => null,
        'value' => ''
    ],
    'oldID' => [
        "label" => returnLabel('oldID', 'Old Id'),
        "tagName" => "input",
        "element" => "<input type='text' name='oldID' id='oldID' value='%s' class='form-control'>",
        "type" => 'text',
        "query" => null,
        'value' => ''
    ],
    'CDL_pics' => [
        'label' => returnLabel('CDL_pics', 'Upload Photo'),
        'tagName' => 'input',
        'element' => "<input type='file' accept='image/*' name='CDL_pics' id='CDL_pics' class='form-control form-control-file'>",
        'type' => 'file',
        'query' => null
    ],
    'comments' => [
        "label" => returnLabel('comments', 'More Information'),
        "tagName" => "textarea",
        "element" => "<textarea name='comments' id='comments' class='form-control' maxlength='1000'>%s</textarea>",
        "type" => null,
        "query" => null,
        'value' => ''
    ]
];

$closureElements = [
    'eviType' => [
        "label" => returnLabel('eviType', 'Evidence Type'),
        "tagName" => 'select',
        'element' => "<select name='eviType' id='eviType' class='form-control'>%s</select>",
        "type" => '',
        "query" => "SELECT EviTypeID, EviType FROM EvidenceType ORDER BY EviType",
        'value' => ''
    ],
    'repo' => [
        'label' => returnLabel('repo', 'Evidence Repository'),
        'tagName' => 'select',
        'element' => "<select name='repo' id='repo' class='form-control'>%s</select>",
        'type' => '',
        'query' => "SELECT RepoID, Repo FROM Repo ORDER BY Repo",
        'value' => ''
    ],
    'evidenceLink' => [
        'label' => returnLabel('evidenceLink', 'Repository Number'),
        'tagName' => "input",
        'element' => "<input type='text' name='evidenceLink' id='evidenceLink' class='form-control' value='%s'>",
        'type' => 'text',
        'query' => null,
        'value' => ''
    ],
    'ClosureComments' => [
        "label" => returnLabel('closureComments', 'Closure Comments'),
        "tagName" => "textarea",
        "element" => "<textarea name='closureComments' id='closureComments' class='form-control' maxlength='1000'>%s</textarea>",
        "type" => null,
        "query" => null,
        'value' => ''
    ]
];

$bartDefEls = [ 'topElement' => &$topElements, 'vtaELements' => &$vtaElements, 'bartElements' => &$bartElements ];

$generalElements = [
    'creator' => [
        'label' => returnLabel('creator', 'Creator', 'required'),
        'tagName' => 'select',
        'element' => "<select name='creator' id='creator' class='form-control' required>%s</select>",
        'value' => '',
        'query' => "SELECT partyID, partyName from bdParties WHERE partyName <> '' ORDER BY partyID"
    ],
    'next_step' => [
        'label' => returnLabel('next_step', 'Next step'),
        'tagName' => 'select',
        'element' => "<select name='next_step' id='next_step' class='form-control'>%s</select>",
        'query' => "SELECT bdNextStepID, nextStepName FROM bdNextStep WHERE nextStepName <> '' ORDER BY bdNextStepID",
        'value' => ''
    ],
    'bic' => [
        'label' => returnLabel('bic', 'Ball in court'),
        'tagName' => 'select',
        'element' => "<select name='bic' id='bic' class='form-control'>%s</select>",
        'query' => "SELECT partyID, partyName from bdParties WHERE partyName <> '' ORDER BY partyID",
        'value' => ''
    ],
    'status' => [
        'label' => returnLabel('status', 'Status', 1),
        'tagName' => 'select',
        'element' => "<select name='status' id='status' class='form-control' required>%s</select>",
        'query' => "SELECT statusID, status from Status WHERE status <> 'Deleted'",
        'value' => ''
    ],
    'descriptive_title_vta' => [
        'label' => returnLabel('descriptive_title_vta', 'Description', 'required'),
        'tagName' => 'textarea',
        'element' => "<textarea name='descriptive_title_vta' id='descriptive_title_vta' class='form-control' maxlength='1000' required>%s</textarea>",
        'query' => null
    ]
];

$vtaElements = [
    'root_prob_vta' => [
        'label' => returnLabel('root_prob_vta', 'Root problem', true),
        'tagName' => 'textarea',
        'element' => "<textarea name='root_prob_vta' id='root_prob_vta' class='form-control' required>%s</textarea>",
        'query' => null
    ],
    'resolution_vta' => [
        'label' => returnLabel('resolution_vta', 'Resolution', 1),
        'tagName' => 'textarea',
        'element' => "<textarea name='resolution_vta' id='resolution_vta' class='form-control' required>%s</textarea>",
        'query' => null
    ],
    'priority_vta' => [
        'label' => returnLabel('priority_vta', 'Priority', 1),
        'tagName' => 'select',
        'element' => "<select name='priority_vta' id='priority_vta' class='form-control' required>%s</select>",
        'value' => '',
        'query' => [ 1, 2, 3 ]
    ],
    'agree_vta' => [
        'label' => returnLabel('agree_vta', 'Agree', 1),
        'tagName' => 'select',
        'element' => "<select name='agree_vta' id='agree_vta' class='form-control' required>%s</select>",
        'value' => '',
        'query' => "SELECT agreeDisagreeID, agreeDisagreeName FROM agreeDisagree WHERE agreeDisagreeName <> ''"
    ],
    'safety_cert_vta' => [
        'label' => returnLabel('safety_cert_vta', 'Safety Certiable', 1),
        'tagName' => 'select',
        'element' => "<select name='safety_cert_vta' id='safety_cert_vta' class='form-control' required>%s</select>",
        'value' => '',
        'query' => 'SELECT yesNoID, yesNo from YesNo'
    ],
    'bartdlAttachments' => [
        'label' => returnLabel('bartdlAttachments', 'Attachments'),
        'element' => "<div class='border-radius thin-grey-border pad scroll-y' style='height: 6.8rem'>%s</div>",
        'query' => "SELECT filepath, filename from bartdlAttachments WHERE bartdlID = ?"
    ],
    'attachment' => [
        'label' => returnLabel('attachment', 'Upload attachment'),
        'tagName' => 'input',
        'type' => 'file',
        'element' => "
            <input name='attachment' id='attachment' type='file' accept='$attachmentFormats' class='form-control'>
            <label class='text-red'>max. allowed file size 5Mb</label>"
    ],
    'bdCommText' => [
        'label' => returnLabel('bdCommText', 'Add comment'),
        'tagName' => 'textarea',
        'element' => "<textarea name='bdCommText' id='bdCommText' class='form-control'>%s</textarea>",
        'value' => ''
    ],
    'resolution_disputed' => [
        'label' => checkboxLabel('resolution_disputed', 'Resolution disputed'),
        'tagName' => 'input',
        'type' => 'checkbox',
        'element' => "<input name='resolution_disputed' id='resolution_disputed' type='checkbox' value='1' class='form-check-input' %s>",
        'value' => '',
        'query' => null
    ],
    'structural' => [
        'label' => checkboxLabel('structural', 'Stuctural'),
        'tagName' => 'input',
        'type' => 'checkbox',
        'element' => "<input name='structural' id='structural' type='checkbox' value='1' class='form-check-input' %s>",
        'value' => '',
        'query' => null
    ]
];

$bartElements = [
    'id_bart' => [
        'label' => returnLabel('id_bart', 'BART ID', 'required'),
        'tagName' => 'input',
        'type' => 'text',
        'element' => "<input name='id_bart' id='id_bart' type='text' value='%s' class='form-control' required>",
        'value' => ''
    ],
    'description_bart' => [
        'label' => returnLabel('description_bart', 'Description', 1),
        'tagName' => 'textarea',
        'element' => "<textarea name='description_bart' id='description_bart' maxlength='1000' class='form-control' required>%s</textarea>",
        'value' => ''
    ],
    'cat1_bart' => [
        'label' => returnLabel('cat1_bart', 'Category 1'),
        'tagName' => 'input',
        'type' => 'text',
        'element' => "<input name='cat1_bart' id='cat1_bart' type='text' maxlength='3' value='%s' class='form-control'>",
        'value' => ''
    ],
    'cat2_bart' => [
        'label' => returnLabel('cat2_bart', 'Category 2'),
        'tagName' => 'input',
        'type' => 'text',
        'element' => "<input name='cat2_bart' id='cat2_bart' type='text' maxlength='3' value='%s' class='form-control'>",
        'value' => ''
    ],
    'cat3_bart' => [
        'label' => returnLabel('cat3_bart', 'Category 3'),
        'tagName' => 'input',
        'type' => 'text',
        'element' => "<input name='cat3_bart' id='cat3_bart' type='text' maxlength='3' value='%s' class='form-control'>",
        'value' => ''
    ],
    'level_bart' => [
        'label' => returnLabel('level_bart', 'Level', true),
        'tagName' => 'select',
        'element' => "<select name='level_bart' id='level_bart' class='form-control' required>%s</select>",
        'value' => '',
        'query' => [ 'PROGRAM', 'PROJECT' ]
    ],
    'dateOpen_bart' => [
        'label' => returnLabel('dateOpen_bart', 'Date open', 1),
        'tagName' => 'input',
        'type' => 'date',
        'element' => "<input name='dateOpen_bart' id='dateOpen_bart' type='date' value='%s' class='form-control' required>",
        'value' => ''
    ],
    'dateClose_bart' => [
        'label' => returnLabel('dateClose_bart', 'Date closed'),
        'tagName' => 'input',
        'type' => 'date',
        'value' => '',
        'element' => "<input name='dateClose_bart' id='dateClose_bart' type='date' value='%s' class='form-control'>"
    ]
];
<?php
$defaultFormCtrls = array(
    'name' => '<label for="%1$s" class="required">%2$s name</label>
        <input name="%1$s" type="text" maxlength="10" value="%3$s" class="form-control item-margin-bottom" required>',
    'description' => '<label for="%1$s">%2$s description</label>
        <textarea name="%1$s" maxlength="255" class="form-control item-margin-bottom">%3$s</textarea>'
);
        
$sqlStrings = [
    'listAll' => "SELECT 'location' AS name, COUNT(locationID) AS count FROM location WHERE locationName <> ''
            UNION
            SELECT 'system', COUNT(systemID) FROM system WHERE systemName <> ''
            UNION
            SELECT 'component', COUNT(compID) FROM component WHERE compName <> ''
            UNION
            SELECT 'defType', COUNT(deftypeID) FROM defType WHERE deftypeName <> ''
            UNION
            SELECT 'contract', COUNT(contractID) FROM contract WHERE contractName <> ''
            UNION
            SELECT 'evidenceType', COUNT(eviTypeID) FROM evidenceType WHERE eviTypeName <> ''
            UNION
            SELECT 'status', COUNT(statusID) FROM status WHERE statusName <> ''
            UNION
            SELECT 'severity', COUNT(severityID) FROM severity WHERE severityName <> ''
            UNION
            SELECT 'testStatus', COUNT(testStatID) FROM testStatus WHERE testStatName <> ''",
    'component' => [
        'add' => ['compName', 'compDescrip', 'updatedBy', 'dateCreated'],
        'list' => ['compID', 'compName', 'compDescrip'],
        'update' => ['compName', 'compDescrip', 'updatedBy'],
        'formCtrls' => $defaultFormCtrls
    ],
    'contract' => [
        'add' => ['contractName', 'contractDescrip', 'updatedBy', 'dateCreated'],
        'list' => ['contractID', 'contractName', 'contractDescrip'],
        'update' => ['contractName', 'contractDescrip', 'updatedBy'],
        'formCtrls' => $defaultFormCtrls
    ],
    'defType' => [
        'add' => ['defTypeName', 'defTypeDescrip', 'updatedBy', 'dateCreated'],
        'list' => ['defTypeID', 'defTypeName', 'defTypeDescrip'],
        'update' => ['defTypeName', 'defTypeDescrip', 'updatedBy'],
        'formCtrls' => $defaultFormCtrls
    ],
    'evidenceType' => [
        'add' => ['eviTypeName', 'eviTypeDescrip', 'updatedBy', 'dateCreated'],
        'list' => ['eviTypeID', 'eviTypeName', 'eviTypeDescrip'],
        'update' => ['eviTypeName', 'eviTypeDescrip', 'updatedBy'],
        'formCtrls' => $defaultFormCtrls
    ],
    'location' => [
        'add' => ['locationName', 'locationDescrip', 'updatedBy', 'dateCreated'],
        'list' => ['locationID', 'locationName', 'locationDescrip'],
        'update' => ['locationName', 'locationDescrip', 'updatedBy'],
        'formCtrls' => $defaultFormCtrls
    ],
    'severity' => [
        'add' => ['severityName', 'severityDescrip', 'updatedBy', 'dateCreated'],
        'list' => ['severityID', 'severityName', 'severityDescrip'],
        'update' => ['severityName', 'severityDescrip', 'updatedBy'],
        'formCtrls' => $defaultFormCtrls
    ],
    'status' => [
        'add' => ['statusName', 'statusDescrip', 'updatedBy', 'dateCreated'],
        'list' => ['statusID', 'statusName', 'statusDescrip'],
        'update' => ['statusName', 'statusDescrip', 'updatedBy'],
        'formCtrls' => $defaultFormCtrls
    ],
    'system' => [
        'add' => ['systemName', 'systemDescription', 'updatedBy', 'dateCreated'],
        'list' => ['systemID', 'systemName', 'systemDescription'],
        'update' => ['systemName', 'systemDescription', 'updatedBy'],
        'formCtrls' => $defaultFormCtrls
    ],
    'testStatus' => [
        'add' => ['testStatName', 'testStatDescrip', 'updatedBy', 'dateCreated'],
        'list' => ['testStatID', 'testStatName', 'testStatDescrip'],
        'update' => ['testStatName', 'testStatDescrip', 'updatedBy'],
        'formCtrls' => $defaultFormCtrls
    ]
];

$displayNames = [
    'defType' => 'deficiency type',
    'evidenceType' => 'evidence type',
    'testStatus' => 'test status'
];

/* mutates array $row passed to it, mapping values to keys as they appear in template
**  @param array $row = a row of data returned from sql query
*/
function mapDisplayKeys(array &$row) {
    $displayKeys = [ 'id', 'name', 'description' ];
    
    if (count($row) !== count($displayKeys))
        throw new InvalidArgumentException('Wrong number of values in query. Expected 3 keys but found ' . count($row));
    
    $indexed = array_values($row);
    $row = array_combine($displayKeys, $indexed);
}

/* queries database for list of records for table name passed as arg
**  @param string $table = name of table to get records from
**  @param MysqliDb $link = db link object from joshcam's MySqliDB library
**  @return array $data = array of rows--as arrays--returned from query
*/
function queryLookupTable($table, $action, &$link, $id = null) {
    global $sqlStrings;

    $fields = $sqlStrings[$table]['list'];
    $idField = $sqlStrings[$table]['list'][0];
    
    if ($action === 'update') {
        $link->where($idField, $id);
        $data = $link->getOne($table, $fields);
    } else {
        $link->where("$idField <> ''");
        $data = $link->get($table, null, $fields);
        foreach ($data as &$row) {
            // re-map row's keys to keys as named in template file
            mapDisplayKeys($row);
            $row['href'] = "/public_html/manage.php/update/component?id={$row['id']}";
        }
    }
    
    return $data;
}

function getLookupData($action, $tableName, &$link) {
    global $sqlStrings, $displayNames;
    
    $displayName = isset($displayNames[$tableName])
        ? $displayNames[$tableName]
        : $tableName;
    $names = array(
        $sqlStrings[$tableName]['update'][0],
        $sqlStrings[$tableName]['update'][1]
    );
            
    if ($action === 'add') {
        $formCtrls = array(
            "<label for='{$names[0]}' class='required'>" . ucfirst($displayName) . " name</label>
            <input name='{$names[0]}' type='text' maxlength='10' class='form-control item-margin-bottom' required>",
            "<label for='{$names[1]}'>" . ucfirst($displayName) . " description</label>
            <textarea name='{$names[1]}' maxlength='255' class='form-control item-margin-bottom'></textarea>"
        );
    
        return array(
            'cardHeading' => 'Enter ' . $displayName . ' information',
            'formCtrls' => $formCtrls
        );
    } elseif ($action ==='update') {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_ENCODED);
        $data = queryLookupTable($tableName, 'update', $link, $id);
        $formCtrls = array_map(function ($n) use ($names, $displayName, $data) {
            static $i = 0;
            $ctrl = sprintf($n, $names[$i], ucfirst($displayName), $data[$names[$i]]);
            $i++;
            return $ctrl;
        }, $sqlStrings[$tableName]['formCtrls']);
        return array(
            'cardHeading' => 'Enter ' . $displayName . ' information',
            'formCtrls' => $formCtrls,
            'id' => $id
        );
    } else {
        return array(
            'data' => queryLookupTable($tableName, 'list', $link),
            'count' => $link->count
        );
    }
}

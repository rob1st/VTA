<?php
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
        'types' => 'ssi',
        'insert' => "INSERT component (compName, compDescrip) VALUES (?, ?)",
        'update' => "UPDATE component SET compName=?, compDescrip=?",
        'list' => "SELECT compID, compName, compDescrip FROM component WHERE compName <> ''",
        'insertFields' => ['compName', 'compDescrip', 'updatedBy']
    ],
    'contract' => [
        'list' => "SELECT contractID, contractName, contractDescrip FROM contract WHERE contractName <> ''",
        'selectFields' => ['contractID', 'contractName', 'contractDescrip'],
        'insertFields' => ['contractName', 'contractDescrip', 'updatedBy']
    ],
    'defType' => [
        'list' => "SELECT defTypeID, defTypeName, defTypeDescrip FROM defType WHERE defTypeName <> ''",
        'selectFields' => ['defTypeID', 'defTypeName', 'defTypeDescrip'],
        'insertFields' => ['defTypeName', 'defTypeDescrip', 'updatedBy']
    ],
    'evidenceType' => [
        'list' => "SELECT eviTypeID, eviTypeName, eviTypeDescrip FROM evidenceType WHERE eviTypeName <> ''",
        'selectFields' => ['eviTypeID', 'eviTypeName', 'eviTypeDescrip'],
        'insertFields' => ['eviTypeName', 'eviTypeDescrip', 'updatedBy']
    ],
    'location' => [
        'types' => 'ss',
        'insert' => 'INSERT location (locationName, locationDescrip, updatedBy) VALUES (?, ?, ?)',
        'update' => 'UPDATE location SET locationName=?, update_by=?',
        'list' => "SELECT locationID, locationName, locationDescrip FROM location WHERE locationName <> ''",
        'insertFields' => ['locationName', 'locationDescrip', 'updatedBy']
    ],
    'severity' => [
        'list' => "SELECT severityID, severityName, severityDescrip FROM severity WHERE severityName <> ''",
        'insertFields' => ['severityName', 'severityDescrip', 'updatedBy'],
        'updateFields' => ['severityName', 'severityDescrip', 'updatedBy'],
        'selectFields' => ['severityID', 'severityName', 'severityDescrip']
    ],
    'status' => [
        'list' => "SELECT statusID, statusName, statusDescrip FROM status WHERE statusName <> ''",
        'insertFields' => ['statusName', 'statusDescrip', 'updatedBy'],
        'updateFields' => ['statusName', 'statusDescrip', 'updatedBy'],
        'selectFields' => ['statusID', 'statusName', 'statusDescrip']
    ],
    'system' => [
        'types' => 'ssi',
        'list' => "SELECT systemID, systemName, systemDescrip FROM system WHERE systemName <> ''",
        'insertFields' => ['systemName', 'systemDescription', 'updatedBy']
    ],
    'testStatus' => [
        'list' => "SELECT testStatID, testStatName, testStatDescrip FROM testStatus WHERE testStatName <> ''",
        'insertFields' => ['testStatName', 'testStatDescrip', 'updatedBy'],
        'updateFields' => ['testStatName', 'testStatDescrip', 'updatedBy'],
        'selectFields' => ['testStatID', 'testStatName', 'testStatDescrip']
    ]
];

/* mutates array $row passed to it, mapping values to keys as they appear in template
**  @param array $row = a row of data returned from sql query
*/
function mapDisplayKeys(array &$row) {
    $displayKeys = [ 'id', 'name', 'description' ];
    
    if (count($row) !== count($displayKeys))
        throw new InvalidArgumentException('Too few keys in row. Expected 3 keys but found ' . count($row));
    
    $indexed = array_values($row);
    $row = array_combine($displayKeys, $indexed);
}

/* queries database for list of records for table name passed as arg
**  @param string $table = name of table to get records from
**  @param MysqliDb $link = db link object from joshcam's MySqliDB library
**  @return array $data = array of rows--as arrays--returned from query
*/
function queryLookupTable($table, &$link) {
    global $sqlStrings;

    $sql = $sqlStrings[$table]['list'];
    
    $data = $link->query($sql);
    
    foreach ($data as &$row) {
        // re-map row's keys to keys as named in template file
        mapDisplayKeys($row);
        $row['href'] = "/public_html/manage.php/update/component?id={$row['id']}";
    }
    
    return $data;
}

function getLookupData($action, $tableName, &$link) {
    global $sqlStrings;
    
    if ($action === 'add') {
        $displayName = ucfirst(isset($displayNames[$tableName])
            ? $displayNames[$tableName]
            : $tableName);
        $nameLabel = $sqlStrings[$tableName]['insertFields'][0];
        $descripLabel = $sqlStrings[$tableName]['insertFields'][1];
            
        $formCtrls = array(
            "<label for='$nameLabel' class='required'>$displayName name</label>
            <input name='$nameLabel' type='text' maxlength='10' class='form-control item-margin-bottom' required>",
            "<label for='$descripLabel'>$displayName description</label>
            <textarea name='$descripLabel' maxlength='255' class='form-control item-margin-bottom'></textarea>"
        );
    
        return array(
            'cardHeading' => 'Enter ' . $tableName . ' information',
            'target' => 'commitNewData.php',
            'formCtrls' => $formCtrls
        );
    } elseif ($action ==='update') {
        
    } else {
        return array(
            'data' => queryLookupTable($tableName, $link),
            'count' => $link->count,
            'cardHeading' => ucfirst($tableName) . 's'
        );
    }
}

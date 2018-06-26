<?php
$sqlStrings = [
    'listAll' => "SELECT 'location' AS name, COUNT(locationID) AS count FROM location WHERE locationName <> ''
            UNION
            SELECT 'system', COUNT(systemID) FROM system WHERE system <> ''
            UNION
            SELECT 'component', COUNT(compID) FROM component WHERE compName <> ''
            UNION
            SELECT 'deficiency type', COUNT(deftypeID) FROM defType WHERE deftypeName <> ''
            UNION
            SELECT 'contract', COUNT(contractID) FROM Contract WHERE contract <> ''
            UNION
            SELECT 'evidence', COUNT(eviTypeID) FROM EvidenceType WHERE eviType <> ''
            UNION
            SELECT 'status', COUNT(statusID) FROM Status WHERE Status <> ''
            UNION
            SELECT 'severity', COUNT(severityID) FROM Severity WHERE severityName <> ''
            UNION
            SELECT 'test status', COUNT(testStatID) FROM testStatus WHERE testStatName <> ''",
    'component' => [
        'types' => 'ss',
        'insert' => "INSERT component (compName, compDescrip) VALUES (?, ?)",
        'update' => "UPDATE component SET compName=?, compDescrip=?",
        'list' => "SELECT compID as id, compName as name, compDescrip as description FROM component WHERE compName <> ''",
        'insertFields' => ['compName', 'compDescrip']
    ],
    'location' => [
        'types' => '',
        'insert' => 'INSERT location (locationName, locationDescrip, updatedBy) VALUES (?, ?, ?)',
        'update' => 'UPDATE location SET locationName=?, update_by=?',
        'list' => "SELECT locationID as id, locationName as name, locationDescrip as description from location WHERE locationName <> ''",
        'insertFields' => ['locationName', 'locationDescrip']
    ]
];
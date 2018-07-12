<?php
$sqlMap = [
    'asset' => [
        'list' => ['assetID', 'assetTag', 'compName', 'locationName', 'installed', 'testStatName'],
        'add' => ['assetTag', 'component', 'location', 'room', 'installStatus', 'testStatus'],
        'update' => []
    ],
    'component' => [
        'table' => 'component',
        'fields' => ['compID', 'compName']
    ],
    'location' => [
        'table' => 'location',
        'fields' => ['locationID', 'locationName']
    ],
    'installStat' => [
        'table' => 'yesNo',
        'fields' => ['yesNoID', 'yesNoName']
    ],
    'testStat' => [
        'table' => 'testStatus',
        'fields' => ['testStatId', 'testStatName']
    ]
];
<?php
$sqlMap = [
    'asset' => [
        'list' => ['assetID', 'assetTag', 'component', 'location', installStat, testStat],
        'add' => ['assetTag', 'component', 'location', 'room', 'installStat', 'testStat'],
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
        'table' => 'YesNo',
        'fields' => ['yesNoID', 'yesNo']
    ],
    'testStat' => [
        'table' => 'testStatus',
        'fields' => ['testStatId', 'testStatName']
    ]
];
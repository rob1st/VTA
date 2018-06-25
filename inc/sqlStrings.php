<?php
$sqlStrings = [
    'component' => [
        'types' => 'ss',
        'insert' => "INSERT component (compName, compDescrip) VALUES (?, ?)",
        'update' => "UPDATE component SET compName=?, compDescrip=?",
        'list' => "SELECT * FROM component WHERE compName <> ''"
    ]
];
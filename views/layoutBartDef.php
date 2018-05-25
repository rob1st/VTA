<?php
require_once 'html_functions/bootstrapGrid.php';

function layoutTopFields($labelEl, $inputEl, $data) {
    return $topFields = [
        [
            returnRow([ sprintf($spanStr, 'ID'), sprintf($fakeInputStr, $data['ID']) ]).
            returnRow([ sprintf($spanStr, 'Creator'), sprintf($fakeInputStr, $data['Creator']) ]).
            returnRow([ sprintf($spanStr, 'Status_VTA'), sprintf($fakeInputStr, $data['Status_VTA']) ]).
            returnRow([ sprintf($spanStr, 'Next_Step'), sprintf($fakeInputStr, $data['Next_Step']) ]).
            returnRow([ sprintf($spanStr, 'BIC'), sprintf($fakeInputStr, $data['BIC']) ]),
            sprintf($pStr, 'Descriptive_title_VTA').sprintf($pStr, sprintf($fakeInputStr, $data['Descriptive_title_VTA']))
        ]
    ];
}

$vtaFields = [
    'Root_Prob_VTA' => [ sprintf($pStr, 'Root_Prob_VTA').sprintf($pStr, sprintf($fakeInputStr, $result['Root_Prob_VTA'])) ],
    'Resolution_VTA' => [ sprintf($pStr, 'Resolution_VTA').sprintf($pStr, sprintf($fakeInputStr, $result['Resolution_VTA'])) ],
    [
        returnRow([ sprintf($spanStr, 'Status_VTA'), sprintf($fakeInputStr, $result['Status_VTA']) ]).
        returnRow([ sprintf($spanStr, 'Priority_VTA'), sprintf($fakeInputStr, $result['Priority_VTA']) ]).
        returnRow([ sprintf($spanStr, 'Agree_VTA'), sprintf($fakeInputStr, $result['Agree_VTA']) ]).
        returnRow([ sprintf($spanStr, 'Safety_Cert_VTA'), sprintf($fakeInputStr, $result['Safety_Cert_VTA']) ]).
        returnRow([ sprintf($spanStr, 'Attachments'), sprintf($fakeInputStr, $result['Attachments']) ]), // will need sep table
        sprintf($pStr, 'Comments_VTA').sprintf($pStr, sprintf($fakeInputStr, $result['Comments_VTA'])). // new comments
        // comments will need sep table
        returnRow([
            sprintf($spanStr, 'Resolution_disputed'), sprintf($fakeInputStr, $result['Resolution_disputed']),
            sprintf($spanStr, 'Structural'), sprintf($fakeInputStr, $result['Structural'])
        ])
    ]
];

$bartFields = [
    [
        returnRow([ sprintf($spanStr, 'ID_BART'), sprintf($fakeInputStr, $result['ID_BART']) ]),
        sprintf($pStr, 'Description_BART').sprintf($pStr, sprintf($fakeInputStr, $result['Description_BART']))
    ],
    [
        returnRow([ sprintf($spanStr, 'Cat1_BART'), sprintf($fakeInputStr, $result['Cat1_BART']) ]).
        returnRow([ sprintf($spanStr, 'Cat2_BART'), sprintf($fakeInputStr, $result['Cat2_BART']) ]).
        returnRow([ sprintf($spanStr, 'Cat3_BART'), sprintf($fakeInputStr, $result['Cat3_BART']) ]),
        returnRow([ sprintf($spanStr, 'Level_BART'), sprintf($fakeInputStr, $result['Level_BART']) ]).
        returnRow([ sprintf($spanStr, 'DateOpen_BART'), sprintf($fakeInputStr, $result['DateOpen_BART']) ]).
        returnRow([ sprintf($spanStr, 'DateClose_BART'), sprintf($fakeInputStr, $result['DateClose_BART']) ]).
        returnRow([ sprintf($spanStr, 'Status_BART'), sprintf($fakeInputStr, $result['Status_BART']) ])
    ]
];
?>
<?php
include('session.php');
include('html_components/defComponents.php');
include('html_functions/bootstrapGrid.php');
include('sql_functions/stmtBindResultArray.php');
include('error_handling/sqlErrors.php');

$title = "SVBX - Update BART Deficiency";

$defID = $_GET['bartDefID'];
$fieldList = file_get_contents('bartdl.sql');
$fieldsArr = array_fill_keys(explode(',', $fieldList), '?');
$fieldList = implode(',', array_keys($fieldsArr));

$link = f_sqlConnect();

if ($result = $link->query('SELECT bdPermit from users_enc where userID='.$_SESSION['UserID'])) {
    if ($row = $result->fetch_row()) {
        $bdPermit = $row[0];
    }
    $result->close();
}

include('filestart.php');

$sql = "SELECT $fieldList FROM BARTDL WHERE id=$defID";

if ($stmt = $link->prepare($sql)) {
    // echo "
    //     <div style='margin-top: 3.5rem; color: darkCyan'>
    //         <p>$sql</p>";
    if ($stmt->execute()) {
        $elements = $topElements + $vtaElements + $bartElements;
        stmtBindResultArrayRef($stmt, $elements);
            $labelStr = "<label for='%s'%s>%s</label>";
            $required = " class='required'";
            $commentFormat = "
                <div class='thin-grey-border pad mb-3'>
                    <h6 class='d-flex flex-row justify-content-between text-secondary'><span>%s</span><span>%s</span></h6>
                    <p>%s</p>
                </div>";

            $stmt->close();
            
            // query for comments associated with this Def
            $sql = "SELECT firstname, lastname, date_created, bdCommText
                FROM bartdlComments bdc
                JOIN users_enc u
                ON bdc.userID=u.userID
                WHERE bartdlID=?
                ORDER BY date_created DESC";
            
            if (!$stmt = $link->prepare($sql)) printSqlErrorAndExit($link, $sql);
            
            if (!$stmt->bind_param('i', $defID)) printSqlErrorAndExit($stmt, $sql);
            
            if (!$stmt->execute()) printSqlErrorAndExit($stmt, $sql);
            
            $comments = stmtBindResultArray($stmt) ?: [];
            
            $stmt->close();
            
            $topRows = [
                'row1' => [
                    'col1' => [
                        'options' => [ 'inline' => true ],
                        [
                            $elements['creator']
                        ],
                        [
                            $elements['next_step']
                        ],
                        [
                            $elements['bic']
                        ]
                    ],
                    $elements['descriptive_title_vta']
                ]
            ];
        
            $vtaFields = [
                'row1' => [
                    $elements['root_prob_vta']
                ],
                'row2' => [
                    $elements['resolution_vta']
                ],
                'row3' => [
                    'col1' => [
                        'options' => [ 'inline' => true ],
                        'row1' => [
                            $elements['status_vta']
                        ],
                        'row2' => [
                            $elements['priority_vta']
                        ],
                        'row3' => [
                            $elements['agree_vta']
                        ],
                        'row4' => [
                            $elements['safety_cert_vta']
                        ],
                        'row5' => [ // will need sep table
                            $elements['bartdlAttachments']
                        ]
                    ],
                    'col2' => [
                        'row1' => [
                            $elements['bdCommText']
                        ],
                        'row2' => [
                            'options' => [ 'inline' => true ],
                            $elements['resolution_disputed'],
                            $elements['structural']
                        ]
                    ]
                ]
            ];
        
            $bartFields = [
                'row1' => [
                    $elements['id_bart']
                ],
                'row2' => [
                    $elements['description_bart']
                ],
                'row3' => [
                    'options' => [ 'inline' => true ],
                    'col1' => [
                        [
                            $elements['cat1_bart']
                        ],
                        [
                            $elements['cat2_bart']
                        ],
                        [
                            $elements['cat3_bart']
                        ]
                    ],
                    'col2' => [
                        [
                            $elements['level_bart']
                        ],
                        [
                            $elements['dateOpen_bart']
                        ],
                        [
                            $elements['dateClose_bart']
                        ],
                        [
                            $elements['status_bart']
                        ]
                    ]
                ]
            ];
            echo "
                <header class='container page-header'>
                    <h1 class='page-title'>Update Deficiency $defID</h1>
                </header>
                <main role='main' class='container main-content'>
                    <form action='updateBartDefCommit.php' method='POST' enctype='multipart/form-data'>
                        <input type='hidden' name='id' value='{$defID}' >
                        <h5 class='grey-bg pad'>General Information</h5>";
                        foreach ($topRows as $element) {
                            // $element['value'] = $result[$name];
                            print returnRow($element);
                        }
            echo "
                        <h5 class='grey-bg pad'>VTA Information</h5>";
                        foreach ($vtaFields as $gridRow) {
                            print returnRow($gridRow);
                        }
            echo "
                        <h5 class='grey-bg pad'>BART Information</h5>";
                        foreach ($bartFields as $gridRow) {
                            print returnRow($gridRow);
                        }
                        
            echo "
                        <h5 class='grey-bg pad'>Comments</h5>";
                        foreach ($comments as $comment) {
                            $timestamp = strtotime($comment['date_created']) - (60 * 60 * 7);
                            
                            printf(
                                $commentFormat,
                                $comment['firstname'].' '.$comment['lastname'],
                                date('j/n/Y • g:i a', $timestamp),
                                stripcslashes($comment['bdCommText'])
                            );
                        }

            echo "
                        <div class='center-content'>
                            <button type='submit' class='btn btn-primary btn-lg'>Submit</button>
                        </div>
                    </form>
                </main>";
                
            // echo "<pre>";
            // var_dump($result);
            // echo "</pre>";
        // } else {
        //     printSqlErrorAndExit($stmt, $sql);
        // }
    } else {
        printSqlErrorAndExit($stmt, $sql);
    }
    // echo "</div>";
} else {
    printSqlErrorAndExit($link, $sql);
}

$link->close();
include('fileend.php');

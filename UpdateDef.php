<?php
include('session.php');
include('html_components/defComponents.php');
include('html_functions/bootstrapGrid.php');
include('sql_functions/stmtBindResultArray.php');
include('filestart.php');

$title = "SVBX - Update Deficiency";
$role = $_SESSION['Role'];
$defID = $_GET['defID'];
$defSql = file_get_contents("UpdateDef.sql").$defID;

$link = f_sqlConnect();

try {
    $elements = $requiredElements + $optionalElements + $closureElements;
    
    if (!$stmt = $link->prepare($defSql)) throw mysqli_sql_exception($link->error);

    if (!$stmt->execute()) throw new mysqli_sql_exception($stmt->error);
    
    if (!stmtBindResultArrayRef($stmt, $elements))
        throw new mysqli_sql_exception($stmt-error);
        
    $stmt->close();
            
    $requiredRows = [
        [
            $elements['safetyCert'],
            $elements['systemAffected']
        ],
        [
            $elements['locationName'],
            $elements['specLoc']
        ],
        [
            $elements['status'],
            $elements['severityName']
        ],
        [
            $elements['dueDate'],
            $elements['groupToResolve']
        ],
        [
            $elements['requiredBy'],
            $elements['contract']
        ],
        [
            $elements['identifiedBy'],
            $elements['defType']
        ],
        [
            $elements['description']
        ]
    ];
            
    $optionalRows = [
        [
            $elements['spec'],
            $elements['actionOwner']
        ],
        [
            $elements['oldID'],
            $elements['CDL_pics']
        ],
        [
            $elements['comments']
        ]
    ];
            
    $closureRows = [
        [
            $elements['eviType'],
            $elements['repo'],
            $elements['evidenceLink']
        ],
        [
            $elements['closureComments']
        ]
    ];
    
    echo "
        <header class='container page-header'>
            <h1 class='page-title'>Update Deficiency ".$defID."</h1>
        </header>
        <main class='container main-content'>
        <form action='UpdateDefCommit.php' method='POST' enctype='multipart/form-data' onsubmit='' class='item-margin-bottom'>
            <input type='hidden' name='DefID' value='$defID'>
            <div class='row'>
                <div class='col-12'>
                    <h4 class='pad grey-bg'>Deficiency No. $defID</h4>
                </div>
            </div>";
                        
            foreach ($requiredRows as $gridRow) {
                $options = [ 'required' => true ];
                if (count($gridRow) > 1) $options['inline'] = true;
                else $options['colWd'] = 6;
                print returnRow($gridRow, $options);
            }
                
        echo "
            <h5 class='grey-bg pad'>
                <a data-toggle='collapse' href='#optionalInfo' role='button' aria-expanded='false' aria-controls='optionalInfo' class='collapsed'>Optional Information<i class='typcn typcn-arrow-sorted-down'></i></a>
            </h5>
            <div id='optionalInfo' class='collapse item-margin-bottom'>";
            foreach ($optionalRows as $gridRow) {
                $options = [ 'required' => true ];
                if (count($gridRow) > 1) $options['inline'] = true;
                else $options['colWd'] = 6;
                print returnRow($gridRow, $options);
            }
        echo "
                <p class='text-center pad-less bg-yellow'>Photos uploaded from your phone may not preserve rotation information. We are working on a fix for this.</p>
            </div>
            <h5 class='grey-bg pad'>
                <a data-toggle='collapse' href='#closureInfo' role='button' aria-expanded='false' aria-controls='closureInfo' class='collapsed'>Closure Information<i class='typcn typcn-arrow-sorted-down'></i></a>
            </h5>
            <div id='closureInfo' class='collapse item-margin-bottom'>";
           foreach ($closureRows as $gridRow) {
                $options = [ 'required' => true ];
                if (count($gridRow) > 1) $options['inline'] = true;
                else $options['colWd'] = 6;
                print returnRow($gridRow, $options);
            }
        echo "
            </div>
            <div class='row item-margin-bottom'>
                <div class='col-12 center-content'>
                    <input type='submit' value='submit' class='btn btn-primary btn-lg'/>
                    <input type='reset' value='reset' class='btn btn-primary btn-lg' />
                </div>
            </div>
        </form>";
    if ($role === 'S') {
        echo "
            <form action='DeleteDef.php' method='POST' onsubmit=''>
                <div class='row'>
                    <div class='col-12 center-content'>
                        <button class='btn btn-danger btn-lg' type='submit' name='q' value='$defID'
                            onclick='return confirm(`ARE YOU SURE? Deficiencies should not be deleted, your deletion will be logged.`)'>delete</button>
                    </div>
                </div>
            </form>";
    }
} catch (mysqli_sql_exception $e) {

} catch (Exception $e) {
    
}
$link->close();
include('fileend.php');

<?php
include('session.php');
include('SQLFunctions.php');
include('html_components/defComponents.php');
include('html_functions/bootstrapGrid.php');
$link = f_sqlConnect();
$Role = $_SESSION['Role'];
$title = "SVBX - New Deficiency";
if($Role == 'V') {
    header('Location: unauthorised.php');
}
include('filestart.php');

echo "
<header class='container page-header'>
    <h1 class='page-title'>Add New Deficiency</h1>
</header>
<main role='main' class='container main-content'>
    <form action='RecDef.php' method='POST' enctype='multipart/form-data'>
        <input type='hidden' name='username' value='{$_SESSION['Username']}' />";
        $elements = $requiredElements + $optionalElements + $closureElements;

        $requiredRows = [
            [
                $elements['safetyCert'],
                $elements['systemAffected']
            ],
            [
                $elements['location'],
                $elements['specLoc']
            ],
            [
                $elements['status'],
                $elements['severity']
            ],
            [
                $elements['dueDate'],
                $elements['groupToResolve']
            ],
            [
                $elements['requiredBy'],
                $elements['contractID']
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
            ]
        ];
                
        $closureRows = [
            [
                $elements['evidenceType'],
                $elements['repo'],
                $elements['evidenceLink']
            ],
            [
                $elements['closureComments']
            ]
        ];
    
            echo "<h5 class='grey-bg pad'>Required Information</h5>";
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
                $options = count($gridRow) > 1 ? ['inline' => true] : ['colWd' => 6];
                print returnRow($gridRow, $options);
            }
            echo "</div>";
            
            echo "
                <h5 class='grey-bg pad'>
                    <a data-toggle='collapse' href='#closureInfo' role='button' aria-expanded='false' aria-controls='closureInfo' class='collapsed'>Closure Information<i class='typcn typcn-arrow-sorted-down'></i></a>
                </h5>
                <div id='closureInfo' class='collapse item-margin-bottom'>";
            foreach ($closureRows as $gridRow) {
                $options = count($gridRow) > 1 ? ['inline' => true] : ['colWd' => 6];
                print returnRow($gridRow, $options);
            }
            echo "</div>";
            
    echo "
        <div class='center-content'>
            <button type='submit' value='submit' class='btn btn-primary btn-lg'>Submit</button>
            <button type='reset' value='reset' class='btn btn-primary btn-lg'>Reset</button>
        </div>
    </form>
</main>";

$link->close();
include('fileend.php');
?>

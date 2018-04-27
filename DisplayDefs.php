<?php 
include('session.php');
include('SQLFunctions.php');
$title = 'View Deficiencies';
$link = f_sqlConnect();
$Role = $_SESSION['Role'];
include('filestart.php');

$AND = 0;

if($_POST['Search'] == NULL) {
    $sql = file_get_contents("CDList.sql");
    $count = "SELECT COUNT(*) FROM CDL";
} else {
    
    //Search needs to have a field for each of the items below:
    $DefIDS = $_POST['DefID']; //text
    $SafetyCertS = $_POST['SafetyCert']; //Yes or No dropdown
    $SystemAffectedS = $_POST['SystemAffected']; //Dropdown from system
    $LocationS = $_POST['Location']; //Dropdown from Location table
    $SpecLocS = $_POST['SpecLoc']; //Text
    $StatusS = $_POST['Status']; //Dropdown from Status table
    $SeverityS = $_POST['Severity']; //Dropdown from severity table
    $GroupToResolveS = $_POST['GroupToResolve']; //Dropdown from systems table
    $IdentifiedByS = $_POST['IdentifiedBy']; //text
    $DescriptionS = $_POST['Description']; //text
    
    $sql = "SELECT 
                A.DefID,
                L.Location, 
                S.Severity, 
                A.DateCreated, 
                Y.SystemAffected, 
                A.Description 
            FROM 
                CDL A 
            LEFT JOIN 
                Location L 
            ON 
                L.LocationID = A.Location
            LEFT JOIN 
                Severity S 
            ON 
                C.SeverityID = A.Severity
            LEFT JOIN 
                System Y
            ON 
                Y.SystemID = A.System";
    $count = "SELECT COUNT(*) FROM CDL A";
    
    if($DefIDS <> NULL) {
       $DefIDSQL = " WHERE A.DefID = '".$DefIDS."'";
       $AND = 1;
    } else {
        $DefIDSQL = "";
    }
    if($SafetyCertS <> NULL) {
        if($AND == 1) {
           $SafetyCertSQL = " AND A.SafetyCert = '".$SafetyCertS."'"; 
       } else {
       $SafetyCertSQL = " WHERE A.SafetyCert = '".$RequirementS."'";
       $AND = 1;
       }
    } else {
        $SafetyCertSQL = "";
    }
    if($SystemAffectedS <> NULL) {
        if($AND == 1) {
           $SystemAffectedSQL = " AND A.SystemAffected = '".$DesignCodeS."'";
        } else {
           $SystemAffectedSQL = " WHERE A.SystemAffected = '".$DesignCodeS."'";
           $AND = 1;
       }
    } else {
        $SystemAffectedSQL = "";
    }
    if($GroupToResolveS <> NULL) {
        if($AND == 1) {
           $GroupToResolveSQL = " AND A.GroupToResolve = '".$DesignCodeS."'";
        } else {
           $GroupToResolveSQL = " WHERE A.GroupToResolve = '".$DesignCodeS."'";
           $AND = 1;
       }
    } else {
        $GroupToResolveSQL = "";
    }
    if($LocationS <> NULL) {
        if($AND == 1) {
            $LocationSQL = " AND A.Location = '".$LocationS."'";
        } else {
            $LocationSQL = " WHERE A.Location = '".$LocationS."'";
            $AND = 1;
        }
    } else {
        $LocationSQL = "";
    }
    if($SpecLocS <> NULL) {
       if($AND == 1) {
            $SpecLocSQL = " AND A.SpecLoc LIKE '%".$SpecLocS."%'";
        } else {
            $SpecLocSQL = " WHERE A.SpecLoc LIKE '%".$SpecLocS."%'";
            $AND = 1;
        }
    } else {
        $SpecLocSQL = "";
    }
    if($StatusS <> 0) {
       if($AND == 1) {
            $StatusSQL = " AND A.Status = '".$StatusS."'";
        } else {
            $StatusSQL = " WHERE A.Status = '".$StatusS."'";
            $AND = 1;
        }
    } else {
        $StatusSQL = "";
    }
    if($SeverityS <> 0) {
       if($AND == 1) {
            $SeveryitySQL = " AND A.Severity = '".$SeverityS."'";
        } else {
            $SeveritySQL = " WHERE A.Severity = '".$SeverityS."'";
            $AND = 1;
        }
    } else {
        $SeveritySQL = "";
    }
    if($IdentifiedByS <> NULL) {
       if($AND == 1) {
            $IdentifiedBySQL = " AND A.IdentifiedBy LIKE '%".$IdentifiedByS."%'";
        } else {
            $IdentifiedBySQL = " WHERE A.IdentifiedBy LIKE '%".$IdentifiedByS."%'";
            $AND = 1;
        }
    } else {
        $IdentifiedBySQL = "";
    }
    if($DescriptionS <> NULL) {
       if($AND == 1) {
            $DescriptionSQL = " AND A.Description LIKE '%".$DescriptionS."%'";
        } else {
            $DescriptionSQL = " WHERE A.Description LIKE '%".$DescriptionS."%'";
            $AND = 1;
        }
    } else {
        $DescriptionSQL = "";
    }
    $sql = $sql.$DefIDSQL.$SafetyCertSQL.$SystemAffectedSQL.$GroupToResolveSQL.$LocationSQL.$SpecLocSQL.$StatusSQL.$SeveritySQL.$IdentifiedBySQL.$DescriptionSQL;
    $count = $count.$DefIDSQL.$SafetyCertSQL.$SystemAffectedSQL.$GroupToResolveSQL.$LocationSQL.$SpecLocSQL.$StatusSQL.$SeveritySQL.$IdentifiedBySQL.$DescriptionSQL;
    
}
?>
    
<header class='container page-header'>
    <h1 class='page-title'>Deficiencies</h1>
</header>
<?php     
    if($result = mysqli_query($link,$CDL)) {
        echo "
            <main class='container main-content'>
            
                <div class='card heading-card'>
                    <div class='card-body grey-bg item-margin-right page-heading-panel'>
                        <p>Click Deficiency ID Number to see full details</p>";
                        if ($Role == 'U' OR $Role == 'A' OR $Role == 'S') {
                            echo "<a href='NewDef.php' class='btn btn-primary'>Add New Deficiency</a>";
                        }
        echo "
                </div></div>
                <ul class='def-nav'>";
                $self = $_SERVER['PHP_SELF'];
                $defNavLinks = array(
                      All => 'DisplayDefs.php',
                      Open => 'DisplayOpenDefs.php',
                      Closed => 'DisplayClosedDefs.php'
                    );

                foreach ($defNavLinks as $linkText => $fileName) {
                    $extraClass = '';
                    if (strpos($self, $fileName)) {
                        $extraClass = ' nav-cur-page';
                    }
                    echo "
                        <li class='item-margin-right flex'>
                            <a href=''.$fileName.'' class='btn btn-sm btn-outline def-nav-btn'.$extraClass.''>'.$linkText.'</a>
                        </li>";
                }
        echo "
                </ul>
                
                <table class='table table-striped table-responsive svbx-table def-table'>
                    <thead>
                        <tr class='svbx-tr table-heading'>
                            <th class='svbx-th id-th'>ID</th>
                            <th class='svbx-th loc-th collapse-sm collapse-xs'>Location</th>
                            <th class='svbx-th sev-th collapse-xs'>Severity</th>
                            <th class='svbx-th created-th collapse-md  collapse-sm collapse-xs'>Date Created</th>
                            <th class='svbx-th status-th'>Status</th>
                            <th class='svbx-th system-th collapse-sm collapse-xs'>System Affected</th>
                            <th class='svbx-th desrip-th'>Brief Description</th>";
                    if($Role == 'S' OR $Role == 'A' OR $Role == 'U') {
                        echo "
                            <th class='svbx-th updated-th collapse-md collapse-sm collapse-xs'>Last Updated</th>
                            <th class='svbx-th edit-th collapse-sm collapse-xs'>Edit</th>";
                    } else echo "</tr></thead><tbody>
                                <tr>
                                    <td colspan='7' class='svbx-th'>Search Bar</td>
                                </tr>";
                    if($Role == 'S') {
                        echo "
                            <th class='svbx-th del-th collapse-sm collapse-xs'>Delete</th>
                        </tr></thead>
                            <tr>
                                <td colspan='10' class='svbx-th'>Search Bar</td>
                            </tr>"; 
                    } else echo "
                                </tr></thead><tbody>
                                <tr>
                                    <td colspan='9' class='svbx-th'>Search Bar</td>
                                </tr>";
                    echo "
                        <tr class='svbx-tr'>
                            <td class='svbx-td id-td'><input type='text' name='DefID'></td>
                            <td class='svbx-td loc-td collapse-sm collapse-xs'><input type='text' name='Location'></td>
                            <td class='svbx-td sev-td collapse-xs'><input type='text' name='Severity'></td>
                            <td class='svbx-td created-td collapse-md  collapse-sm collapse-xs'><input type='date' name='DateCreated'></td>
                            <td class='svbx-td status-td'><input type='text' name='Status'></td>
                            <td class='svbx-td system-td collapse-sm collapse-xs'><input type='text' name='SystemAffected'></td>
                            <td class='svbx-td descrip-td'><input type='text' name='Description'></td>";
                            
                    if($Role == 'S' OR $Role == 'A' OR $Role == 'U') {
                        echo "
                            <th class='svbx-th updated-th collapse-md collapse-sm collapse-xs'></th>
                            <th class='svbx-th edit-th collapse-sm collapse-xs'></th>";
                    } else echo "</tr>";
                    if($Role == 'S') {
                        echo "
                            <th class='svbx-th del-th collapse-sm collapse-xs'></th>
                            </tr>"; 
                    } else echo "
                            </tr></thead><tbody>";
                    
                while($row = mysqli_fetch_array($result)) {
                    echo "
                        <tr class='svbx-tr'>
                            <td class='svbx-td id-td'><a href='ViewDef.php?DefID={$row[0]}' class='class1'>{$row[0]}</a></td>
                            <td class='svbx-td loc-td collapse-sm collapse-xs'>{$row[1]}</td>
                            <td class='svbx-td sev-td collapse-xs'>{$row[2]}</td>
                            <td class='svbx-td created-td collapse-md  collapse-sm collapse-xs'>{$row[3]}</td>
                            <td class='svbx-td status-td'>{$row[4]}</td>
                            <td class='svbx-td system-td collapse-sm collapse-xs'>{$row[5]}</td>
                            <td class='svbx-td descrip-td'>'.nl2br($row[6]).'</td>";
                    if ($Role == 'S' OR $Role == 'A' OR $Role == 'U') {
                       echo "
                            <td class='svbx-td updated-td collapse-md  collapse-sm collapse-xs'>{$row[7]}</td>
                            <td class='svbx-td edit-td collapse-sm collapse-xs'>
                                <form action='UpdateDef.php' method='POST' onsubmit=''/>
                                    <button type='submit' name='q' value=''.$row[0].''><i class='typcn typcn-edit'></i></button>
                                </form>
                            </td>";
                    } else echo "</tr>";
                    if ($Role == 'S') {
                        echo "
                            <td class='svbx-td del-td collapse-sm collapse-xs'>
                                <form action='DeleteDef.php' method='POST' onsubmit='' onclick='return confirm(`ARE YOU SURE? Deficiencies should not be deleted, your deletion will be logged.`)'/>
                                    <button type='submit' name='q' value=''.$row[0].''><i class='typcn typcn-times'></i></button>
                                </form>
                            </td></tr>";
                    }
            }
        echo "</tbody></table></main>";
//mysqli_free_result($result);

    // Error display:
    if(mysqli_error( $link )) {
        echo "<main class='container main-content error-display'>Error: " .mysqli_error($link);
    }
                    
mysqli_close($link);
    }
include 'fileend.php';
?>
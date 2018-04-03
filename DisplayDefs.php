<?php 
include('session.php');
include('SQLFunctions.php');
$title = "View Deficiencies";
$link = f_sqlConnect();
$CDL = file_get_contents("CDList.sql");
$Role = $_SESSION['Role'];
include('filestart.php');
?>
    
<header class="container page-header">
    <h1 class="page-title">Deficiencies</h1>
</header>
<?php     
    if($result = mysqli_query($link,$CDL)) {
        echo "
            <main class='container main-content'>
            
                <div class='card heading-card'>
                    <div class='card-body grey-bg page-heading-panel'>
                        <p>Click Deficiency ID Number to see full details</p>";
                        if ($Role == 'U' OR $Role == 'A' OR $Role == 'S') {
                            echo "<a href='NewDef.php' class='btn btn-primary'>Add New Deficiency</a>";
                        }
          echo "
                </div></div>
                <ul class='def-nav'>
                    <li class='def-nav-item'><a href='DisplayDefs.php' class='btn btn-sm btn-outline def-nav-btn'>All</a></li>
                    <li class='def-nav-item'><a href='DisplayOpenDefs.php' class='btn btn-sm btn-outline def-nav-btn'>Open</a></li>
                    <li class='def-nav-item'><a href='DisplayClosedDefs.php' class='btn btn-sm btn-outline def-nav-btn'>Closed</a></li>
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
                            <th class='svbx-th updated-th collapse-md  collapse-sm collapse-xs'>Last Updated</th>
                            <th class='svbx-th edit-th'>Edit</th>";
                    } else echo "</tr></thead><tbody>";
                    if($Role == 'S') {
                        echo "
                            <th class='svbx-th'>Delete</th>
                        </tr></thead>"; 
                    } else echo '</tr></thead><tbody>';
                    
                while($row = mysqli_fetch_array($result)) {
                    echo "
                        <tr class='svbx-tr'>
                            <td class='svbx-td'><a href='ViewDef.php?DefID={$row[0]}' class='class1'>{$row[0]}</a></td>
                            <td class='svbx-td collapse-sm collapse-xs'>{$row[1]}</td>
                            <td class='svbx-td collapse-xs'>{$row[2]}</td>
                            <td class='svbx-td collapse-md  collapse-sm collapse-xs'>{$row[3]}</td>
                            <td class='svbx-td'>{$row[4]}</td>
                            <td class='svbx-td collapse-sm collapse-xs'>{$row[5]}</td>
                            <td class='svbx-td'>".nl2br($row[6])."</td>";
                    if ($Role == 'S' OR $Role == 'A' OR $Role == 'U') {
                       echo "
                            <td class='svbx-td collapse-md  collapse-sm collapse-xs'>{$row[7]}</td>
                            <td class='svbx-td'><form action='UpdateDef.php' method='POST' onsubmit=''/>
                                <button type='submit' name='q' value='".$row[0]."'><i class='typcn typcn-edit'></i></button></form>
                            </td>";
                    } else echo "</tr>";
                    if ($Role == 'S') {
                        echo "
                            <td class='svbx-td'><form action='DeleteDef.php' method='POST' onsubmit='' onclick='return confirm(`ARE YOU SURE? Deficiencies should not be deleted, your deletion will be logged.`)'/>
                                <button type='submit' name='q' value='".$row[0]."'><i class='typcn typcn-times'></i></form></td>
                            </tr>";
                    }
            }
        echo "</tbody></table></main>";
    }
//mysqli_free_result($result);

    // Error display:
    if(mysqli_error( $link )) {
        echo "<main class='container main-content error-display'>Error: " .mysqli_error($link);
    }
                    
mysqli_close($link);
    
include 'fileend.php';
?>
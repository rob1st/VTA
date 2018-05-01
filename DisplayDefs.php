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
    if($result = $link->query($CDL)) {
        $cdlResult = $result;
        echo "
        <main class='container main-content'>
            <div class='card heading-card'>
                <div class='card-body grey-bg item-margin-right page-heading-panel'>
                    <p>Click Deficiency ID Number to see full details</p>";
                    if ($Role == 'U' OR $Role == 'A' OR $Role == 'S') {
                        echo "<a href='NewDef.php' class='btn btn-primary'>Add New Deficiency</a>";
                    }
        echo "
                </div>
            </div>";

            // search form
        echo "
            <form class='item-margin-bottom'>
                <div class='row item-margin-bottom'>
                    <div class='col-6 col-sm-1 pl-1 pr-1'>
                        <label class='input-label'>Def #</label>
                        <select name='DefID' class='form-control'>";
                        if ($result = $link->query('SELECT DefID from CDL')) {
                            while ($row = $result->fetch_array()) {
                                echo "<option value='{$row[0]}'>{$row[0]}</option>";
                            }
                        }
        echo "
                        </select>
                    </div>
                    <div class='col-6 col-sm-2 pl-1 pr-1'>
                        <label class='input-label'>Status</label>
                        <select name='Status' class='form-control'>";
                        if ($result = $link->query('SELECT StatusID, Status from Status')) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['StatusID']}'>{$row['Status']}</option>";
                            }
                        }
        echo "
                        </select>
                    </div>
                    <div class='col-xs-6 col-sm-1 pl-1 pr-1'>
                        <label class='input-label'>Safety cert</label>
                        <select name='SafetyCert' class='form-control'>
                            <option value='1'>Yes</option>
                            <option value='2'>No</option>
                        </select>
                    </div>
                    <div class='col-xs-6 col-sm-2 pl-1 pr-1'>
                        <label class='input-label'>Severity</label>
                        <select name='Severity' class='form-control'>";
                        if ($result = $link->query('SELECT SeverityID, SeverityName from Severity')) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['SeverityID']}'>{$row['SeverityName']}</option>";
                            }
                        }
        echo "
                        </select>
                    </div>
                    <div class='col-xs-12 col-sm-3 pl-1 pr-1'>
                        <label class='input-label'>System</label>
                        <select name='SystemAffected' class='form-control'>";
                        if ($result = $link->query('SELECT s.SystemID, s.System from CDL c JOIN System s ON s.SystemID=c.SystemAffected GROUP BY System ORDER BY SystemID')) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['SystemID']}'>{$row['System']}</option>";
                            }
                        }
        echo "
                        </select>
                    </div>
                    <div class='col-sm-3 pl-1 pr-1'>
                        <label class='input-label'>Group to resolve</label>
                        <select name='GroupToResolve' class='form-control'>";
                        if ($result = $link->query('SELECT s.SystemID, s.System FROM CDL c JOIN System s ON s.SystemID=c.GroupToResolve GROUP BY System ORDER BY SystemID')) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['SystemID']}'>{$row['System']}</option>";
                            }
                            $result->close();
                        }
                        
        echo "
                        </select>
                    </div>
                </div>
                <div class='row item-margin-bottom'>
                    <div class='col-sm-5 pl-1 pr-1'>
                        <label class='input-label'>Description</label>
                        <input type='text' name='Description' class='form-control'>
                    </div>
                    <div class='col-sm-2 pl-1 pr-1'>
                        <label class='input-label'>Location</label>
                        <select name='Location' class='form-control'>";
                        if ($result = $link->query('SELECT l.LocationID, l.LocationName FROM CDL c JOIN Location l ON l.LocationID=c.Location GROUP BY LocationName ORDER BY LocationID')) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['LocationID']}'>{$row['LocationName']}</option>";
                            }
                        }
        echo "
                        </select>
                    </div>
                    <div class='col-sm-2 pl-1 pr-1'>
                        <label class='input-label'>Specific location</label>
                        <select name='SpecLoc' class='form-control'>";
                        if ($result = $link->query('SELECT SpecLoc FROM CDL GROUP BY SpecLoc')) {
                            while ($row = $result->fetch_row()) {
                                if ($row[0]) {
                                    echo "<option value='$row[0]'>$row[0]</option>";
                                } else echo "<option value=''>none</option>";
                            }
                            $result->close();
                        }
        echo "
                        </select>
                    </div>
                    <div class='col-sm-2 pl-1 pr-1'>
                        <label class='input-label'>Identified By</label>
                        <select name='IdentifiedBy' class='form-control'>";
                        if ($result = $link->query('SELECT IdentifiedBy FROM CDL GROUP BY IdentifiedBy')) {
                            while ($row = $result->fetch_array()) {
                                echo "<option value='{$row[0]}'>{$row[0]}</option>";
                            }
                            $result->close();
                        }
        echo "
                        </select>
                    </div>
                    <div class='col-sm-1 pl-1 pr-1'>
                        <button type='submit' class='btn btn-primary' style='position: relative; top: 1.5rem'>Search</button>
                    </div>
                </div>
            </form>";
            
        /*    <ul class='def-nav'>
            $self = $_SERVER['PHP_SELF'];
            $defNavLinks = array(
                  'All' => 'DisplayDefs.php',
                  'Open' => 'DisplayOpenDefs.php',
                  'Closed' => 'DisplayClosedDefs.php'
                );

            // loop over nav links, hilite the currently displayed one
            foreach ($defNavLinks as $linkText => $fileName) {
                $extraClass = '';
                if (strpos($self, $fileName)) {
                    $extraClass = ' nav-cur-page';
                }
                echo "
                    <li class='item-margin-right flex'>
                        <a href='$fileName' class='btn btn-sm btn-outline def-nav-btn{$extraClass}'>$linkText</a>
                    </li>";
            }*/
        echo "
            </ul>
            <table class='table table-striped table-responsive svbx-table'>
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
                    if($Role == 'S') {
                        echo "<th class='svbx-th del-th collapse-sm collapse-xs'>Delete</th>";
                    }
                } echo "</tr></thead><tbody>";
                
            while($row = $cdlResult->fetch_array()) {
                echo "
                    <tr class='svbx-tr'>
                        <td class='svbx-td id-td'><a href='ViewDef.php?DefID={$row[0]}' class='class1'>{$row[0]}</a></td>
                        <td class='svbx-td loc-td collapse-sm collapse-xs'>{$row[1]}</td>
                        <td class='svbx-td sev-td collapse-xs'>{$row[2]}</td>
                        <td class='svbx-td created-td collapse-md  collapse-sm collapse-xs'>{$row[3]}</td>
                        <td class='svbx-td status-td'>{$row[4]}</td>
                        <td class='svbx-td system-td collapse-sm collapse-xs'>{$row[5]}</td>
                        <td class='svbx-td descrip-td'>".nl2br($row[6])."</td>";
                if ($Role == 'S' OR $Role == 'A' OR $Role == 'U') {
                   echo "
                        <td class='svbx-td updated-td collapse-md  collapse-sm collapse-xs'>{$row[7]}</td>
                        <td class='svbx-td edit-td collapse-sm collapse-xs'>
                            <form action='UpdateDef.php' method='POST' onsubmit=''/>
                                <button type='submit' name='q' value='".$row[0]."'><i class='typcn typcn-edit'></i></button>
                            </form>
                        </td>";
                } else echo "</tr>";
                if ($Role == 'S') {
                    echo "
                        <td class='svbx-td del-td collapse-sm collapse-xs'>
                            <form action='DeleteDef.php' method='POST' onsubmit='' onclick='return confirm(`ARE YOU SURE? Deficiencies should not be deleted, your deletion will be logged.`)'/>
                                <button type='submit' name='q' value='".$row[0]."'><i class='typcn typcn-times'></i></button>
                            </form>
                        </td></tr>";
                }
            }
        echo "</tbody></table></main>";
    } elseif($link->error) {
        echo "<main class='container main-content error-display'>Error: $link->error";
    }
                    
mysqli_close($link);
    
include 'fileend.php';
?>
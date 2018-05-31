<?php 
include('session.php');
// session_start();
include('SQLFunctions.php');
include('utils/utils.php');
include('html_functions/htmlTables.php');
$title = "View Deficiencies";
$link = f_sqlConnect();
$role = $_SESSION['Role'];
$view = $_GET['view'];
include('filestart.php');

$roleLvlMap = [
    'V' => 1,
    'U' => 2,
    'A' => 3,
    'S' => 4
];
$roleLvl = $roleLvlMap[$role];

if ($result = $link->query('SELECT bdPermit from users_enc where userID='.$_SESSION['UserID'])) {
    if ($row = $result->fetch_row()) {
        $bdPermit = $row[0];
    }
}

function concatSqlStr($arr, $tableName, $initStr = '') {
    $joiner = 'WHERE';
    $equality = '=';
    $qStr = $initStr;
    foreach ($arr as $key => $val) {
        if (strpos(strtolower($key), 'description') !== false) {
            $equality = ' LIKE ';
            $val = "%{$val}%";
        }
        $qStr .= " $joiner $tableName.{$key}{$equality}'{$val}'";
        $joiner = 'AND';
        $equality = '=';
    }
    return $qStr;
}

function printInfoBox($lvl, $href) {
    $box ="
        <div class='card item-margin-bottom'>
            <div class='card-body flex-row justify-content-between align-items-center grey-bg'>
                <p class='mb-1'>Click Deficiency ID number to see full details</p>
                %s
            </div>
        </div>";
    $btn = $lvl > 1 ? "<a href='%s' class='btn btn-primary'>Add New Deficiency</a>" : '';
    
    $box = sprintf($box, $btn);
            
    return printf($box, $href);
}

function printSearchBar($cnxn, $post, $formAction) {
    list($collapsed, $show) = $_POST['Search'] ? ['', ' show'] : ['collapsed', ''];
    $formStrF = "
        <div class='row item-margin-bottom'>
            <form action='%s' method='%s' class='col-12'>
                <div class='row'>
                    <h5 class='col-12'>
                        <a data-toggle='collapse' href='#filterDefs' role='button' aria-expanded='false' aria-controls='filterDefs' class=$collapsed>Filter deficiencies<i class='typcn typcn-arrow-sorted-down'></i></a>
                    </h5>
                </div>
                <div class='collapse$show' id='filterDefs'>";
    // concat content to $form string until it's all written
    // then return the completed string
    $form = sprintf($formStrF, $formAction['action'], $formAction['method']);
    $form .= "<div class='row item-margin-bottom'>
                    <div class='col-6 col-sm-1 pl-1 pr-1'>
                        <label class='input-label'>Def #</label>
                        <select name='defID' class='form-control'>
                            <option value=''></option>";
    if ($result = $cnxn->query('SELECT defID from CDL')) {
        while ($row = $result->fetch_array()) {
            $select = ($post['defID'] === $row[0]) ? 'selected' : '';
            $form .= "<option value='{$row[0]}' $select>{$row[0]}</option>";
        }
        $result->close();
    }
    $form .= "</select>
                </div>
                <div class='col-6 col-sm-2 pl-1 pr-1'>
                    <label class='input-label'>Status</label>
                    <select name='Status' class='form-control'>
                        <option value=''></option>";
    if ($result = $cnxn->query('SELECT StatusID, Status from Status')) {
        while ($row = $result->fetch_array()) {
            $select = ($post['Status'] === $row[0]) ? 'selected' : '';
            $form .= "<option value='{$row[0]}' $select>{$row[1]}</option>";
        }
        $result->close();
    }
    $form .= "</select>
                </div>
                <div class='col-6 col-sm-1 pl-1 pr-1'>
                    <label class='input-label'>Safety cert</label>
                    <select name='SafetyCert' class='form-control'>
                        <option value=''></option>
                        <option value='1'>Yes</option>
                        <option value='2'>No</option>
                    </select>
                </div>
                <div class='col-6 col-sm-2 pl-1 pr-1'>
                    <label class='input-label'>Severity</label>
                    <select name='Severity' class='form-control'>
                        <option value=''></option>";
    if ($result = $cnxn->query('SELECT SeverityID, SeverityName from Severity')) {
        while($row = $result->fetch_array()) {
            $select = ($post['Severity'] === $row[0]) ? 'selected' : '';
            $form .= "<option value='{$row[0]}' $select>{$row[1]}</option>";
        }
        $result->close();
    }
    $form .= "</select>
                </div>
                <div class='col-6 col-sm-3 pl-1 pr-1'>
                    <label class='input-label'>System</label>
                    <select name='SystemAffected' class='form-control'>
                        <option value=''></option>";
    if ($result = $cnxn->query('SELECT s.SystemID, s.System from CDL c JOIN System s ON s.SystemID=c.SystemAffected GROUP BY System ORDER BY SystemID')) {
        while ($row = $result->fetch_array()) {
            $select = ($post['SystemAffected'] === $row[0]) ? 'selected' : '';
            $form .= "<option value='{$row[0]}' $select>{$row[1]}</option>";
        }
        $result->close();
    }
    $form .= "</select>
                </div>
                <div class='col-6 col-sm-3 pl-1 pr-1'>
                    <label class='input-label'>Group to resolve</label>
                    <select name='GroupToResolve' class='form-control'>
                        <option value=''></option>";
    if ($result = $cnxn->query('SELECT s.SystemID, s.System FROM CDL c JOIN System s ON s.SystemID=c.GroupToResolve GROUP BY System ORDER BY SystemID')) {
        while ($row = $result->fetch_array()) {
            $select = ($post['GroupToResolve'] === $row[0]) ? 'selected' : '';
            $form .= "<option value='{$row[0]}' $select>{$row[1]}</option>";
        }
        $result->close();
    }
    $form .= "</select>
                </div>
            </div>
            <div class='row item-margin-bottom'>
                <div class='col-sm-4 pl-1 pr-1'>
                    <label class='input-label'>Description</label>
                    <input type='text' name='Description' class='form-control' value='{$post['Description']}'>
                </div>
                <div class='col-sm-2 pl-1 pr-1'>
                    <label class='input-label'>Location</label>
                    <select name='Location' class='form-control'>
                        <option value=''></option>";
    if ($result = $cnxn->query('SELECT l.LocationID, l.LocationName FROM CDL c JOIN Location l ON l.LocationID=c.Location GROUP BY LocationName ORDER BY LocationID')) {
        while ($row = $result->fetch_array()) {
            $select = ($post['Location'] === $row[0]) ? 'selected' : '';
            $form .= "<option value='{$row[0]}' $select>{$row[1]}</option>";
        }
        $result->close();
    }
    $form .= "</select>
                </div>
                <div class='col-sm-2 pl-1 pr-1'>
                    <label class='input-label'>Specific location</label>
                    <select name='SpecLoc' class='form-control'>";
    if ($result = $cnxn->query('SELECT SpecLoc FROM CDL GROUP BY SpecLoc')) {
        while ($row = $result->fetch_row()) {
            $select = ($post['SpecLoc'] === $row[0]) ? 'selected' : '';
            $form .= "<option value='$row[0]' $select>$row[0]</option>";
        }
        $result->close();
    }
    $form .= "</select>
                </div>
                <div class='col-sm-2 pl-1 pr-1'>
                    <label class='input-label'>Identified By</label>
                    <select name='IdentifiedBy' class='form-control'>
                        <option value=''></option>";
    if ($result = $cnxn->query('SELECT IdentifiedBy FROM CDL GROUP BY IdentifiedBy')) {
        while ($row = $result->fetch_row()) {
            $select = ($post['IdentifiedBy'] === $row[0]) ? 'selected' : '';
            $form .= "<option value='{$row[0]}' $select>{$row[0]}</option>";
        }
        $result->close();
    }
    $form .= "</select>
                </div>
                <div class='col-sm-2 pl-1 pr-1 pt-2 flex-row justify-center align-end'>
                    <button name='Search' value='search' type='submit' class='btn btn-primary item-margin-right'>Search</button>
                    <button name='Reset' value='reset' type='button' class='btn btn-primary item-margin-right' onclick='return resetSearch(event)'>Reset</button>
                </div>
            </div>
            </div>
        </form>
    </div>";
    return $form;
}

function printDefsTable($cnxn, $qry, $elements, $lvl) {
    if ($result = $cnxn->query($qry)) {
        if ($result->num_rows) {
            /*
            // $lvl = is_bool(lvl) ? boolToStr($lvl) : $lvl;
            // $table = "
            //     <table class='table table-striped table-responsive svbx-table'>
            //         <thead>
            //             <tr class='svbx-tr table-heading'>
            //                 <th class='svbx-th id-th'>ID</th>
            //                 <th class='svbx-th loc-th collapse-sm collapse-xs'>Location</th>
            //                 <th class='svbx-th sev-th collapse-xs'>Severity</th>
            //                 <th class='svbx-th created-th collapse-md  collapse-sm collapse-xs'>Date Created</th>
            //                 <th class='svbx-th status-th'>Status</th>
            //                 <th class='svbx-th system-th collapse-sm collapse-xs'>System Affected</th>
            //                 <th class='svbx-th descrip-th'>Brief Description</th>
            //                 <th class='svbx-th collapse-md collapse-sm collapse-xs'>Spec Loc</th>";
            // if ($lvl > 1) {
            //     $table .= "
            //         <th class='svbx-th updated-th collapse-md collapse-sm collapse-xs'>Last Updated</th>
            //         <th class='svbx-th edit-th collapse-sm collapse-xs'>Edit</th>";
            // } $table .= "</tr></thead><tbody>";
            */
            print "<table class='table table-striped table-responsive svbx-table'>";
            printTableHeadings(array_column($elements, 'header'), $lvl);
            populateTable($result, array_column($elements, 'cell'), $lvl);
            print "</table>";
            /*
            // while($row = $result->fetch_array()) {
            //     $table .= "
            //         <tr class='svbx-tr'>
            //             <td class='svbx-td id-td'><a href='ViewDef.php?DefID={$row[0]}' class='class1'>{$row[0]}</a></td>
            //             <td class='svbx-td loc-td collapse-sm collapse-xs'>{$row[1]}</td>
            //             <td class='svbx-td sev-td collapse-xs'>{$row[2]}</td>
            //             <td class='svbx-td created-td collapse-md  collapse-sm collapse-xs'>{$row[3]}</td>
            //             <td class='svbx-td status-td'>{$row[4]}</td>
            //             <td class='svbx-td system-td collapse-sm collapse-xs'>{$row[5]}</td>
            //             <td class='svbx-td descrip-td'>".nl2br($row[6])."</td>
            //             <td class='svbx-td collapse-md collapse-sm collapse-xs'>{$row[7]}</td>";
            //     if ($lvl > 1) {
            //       $table .= "
            //             <td class='svbx-td updated-td collapse-md  collapse-sm collapse-xs'>{$row[8]}</td>
            //             <td class='svbx-td edit-td collapse-sm collapse-xs'>
            //                 <form action='UpdateDef.php' method='POST' onsubmit=''/>
            //                     <button type='submit' name='q' value='".$row[0]."'><i class='typcn typcn-edit'></i></button>
            //                 </form>
            //             </td>";
            //     } else $table .= "</tr>";
            // }
            // $table .= "</tbody></table>";
            */
        } else {
            // $table .=
            print "<h4 class='text-secondary text-center'>No results found for your search</h4>";
        }
    } elseif ($cnxn->error) {
        // $table .=
        print "<h4 class='text-danger center-content'>Error: $cnxn->error</h4><p>$qry</p>";
    }
}

function printProjectDefsTable($cnxn, $qry, $lvl) {
    $tdClassList = 'svbx-td';
    $thClassList = 'svbx-th';
    $collapseXs = 'collapse-xs';
    $collapseSm = 'collapse-sm collapse-xs';
    $collapseMd = 'collapse-md  collapse-sm collapse-xs';
    $tableFields = [
        [
            'header' => [ 'text' => 'ID', 'classList' => $thClassList ],
            'cell' => [ 'classList' => $tdClassList, 'innerHtml' => "<a href='ViewDef.php?defID=%s'>%s</a>" ]
        ],
        [
            'header' => [ 'text' => 'Location', 'classList' => "$thClassList $collapseSm" ],
            'cell' => [ 'classList' => "$tdClassList $collapseSm" ]
        ],
        [
            'header' => [ 'text' => 'Severity', 'classList' => "$thClassList $collapseXs" ],
            'cell' => [ 'classList' => "$tdClassList $collapseXs" ]
        ],
        [
            'header' => [ 'text' => 'Date Created', 'classList' => "$thClassList $collapseMd" ],
            'cell' => [ 'classList' => "$tdClassList $collapseMd" ]
        ],
        [
            'header' => [ 'text' => 'Status', 'classList' => $thClassList ],
            'cell' => [ 'classList' => $tdClassList ]
        ],
        [
            'header' => [ 'text' => 'System Affected', 'classList' => "$thClassList $collapseSm" ],
            'cell' => [ 'classList' => "$tdClassList $collapseSm" ]
        ],
        [
            'header' => [ 'text' => 'Brief Description', 'classList' => $thClassList ],
            'cell' => [ 'classList' => $tdClassList ]
        ],
        [
            'header' =>  [ 'text' => 'Specific Location', 'classList' => "$thClassList $collapseMd" ],
            'cell' => [ 'classList' => "$tdClassList $collapseMd" ]
        ],
        [
            'header' => [ 'auth' => 2, 'text' => 'Last Updated', 'classList' => "$thClassList $collapseMd" ],
            'cell' => [ 'auth' => 2, 'classList' => "$tdClassList $collapseMd" ]
        ],
        [
            'header' => ['auth' => 2, 'text' => 'Edit', 'classList' => "$thClassList $collapseSm" ],
            'cell' => [
                'auth' => 2,
                'classList' => "$tdClassList $collapseSm",
                'innerHtml' => "<a id='updateDef%s' href='UpdateDef.php?defID=%s' type='submit' class='btn'><i class='typcn typcn-edit'></i></button>"
            ]
        ]
    ];
    print "<h4 class='text-yellow'>".count($tableFields)."</h4>";
    printDefsTable($cnxn, $qry, $tableFields, $lvl);
}

function printBartDefsTable($cnxn, $qry, $lvl) {
    $tdClassList = 'svbx-td';
    $thClassList = 'svbx-th';
    $collapseXs = 'collapse-xs';
    $collapseSm = 'collapse-sm collapse-xs';
    $collapseMd = 'collapse-md  collapse-sm collapse-xs';
    $thStrF = "<th class='%s'>%s</th>";
    $tdStrF = "<td class='%s'>%s</td>";
    $tableFields = [
        [
            'header' => [ 'text' => 'ID' ],
            'cell' => [ 'innerHtml' => "<a href='ViewDef.php?bartDefID=%s'>%s</a>" ]
        ],
        [
            'header' => [ 'text' => 'BART Status' ],
            'cell' => []
        ],
        [
            'header' => [ 'text' => 'VTA Status' ],
            'cell' => []
        ],
        [
            'header' => [ 'text' => 'Date created' ],
            'cell' => []
        ],
        [
            'header' => [ 'text' => 'Description' ],
            'cell' => []
        ],
        [
            'header' => [ 'text' => 'Resolution' ],
            'cell' => []
        ],
        [
            'header' => [ 'text' => 'Next step' ],
            'cell' => []
        ],
        [
            'header' => [ 'text' => 'Edit', 'element' => sprintf($th, "$thClassList $collapseSm", 'Edit')],
            'cell' => [
                'element' => sprintf("<td class='%s'><form action='UpdateDef.php' method='POST' onsubmit=''/><button type='submit' name='q' value='%s' id='updateDef%s'><i class='typcn typcn-edit'></i></button></form></td>", "$thClassList $collapseSm", '%s', '%s'),
                'innerHtml' => "<form action='UpdateDef.php' method='POST' onsubmit=''/><button type='submit' name='bartDef' value='%s' id='updateDef%s'><i class='typcn typcn-edit'></i></button></form>"
            ]
        ]
    ];
    print "<h4 class='text-success'>".count($tableFields)."</h4>";
    printDefsTable($cnxn, $qry, $tableFields, $lvl);
}

if($_POST['Search'] == NULL) {
    $whereCls = ' WHERE D.Status <> 3 ORDER BY DefID';
    $count = "SELECT COUNT(*) FROM CDL";
} else {
    $postData = array_filter($_POST);
    unset($postData['Search']);
    
    $count = "SELECT COUNT(*) FROM CDL D";
    
    $whereCls = concatSqlStr($postData, 'D');
    $count .= concatSqlStr($postData, 'D');
}
?>
<header class="container page-header">
    <h1 class="page-title">Deficiencies</h1>
    <h4 id='printUserInfo' class='text-purple'>
        <ul>
            <li>roleLvl <?php print $roleLvl ?></li>
            <li>role <?php print $role ?></li>
            <li>bdPermit <?php print $bdPermit ?></li>
            <li>view <?php print $view ?></li>
            <li>phpversion <?php print phpversion() ?></li>
        </ul>
    </h4>
    <?php
        $btnSelected = 'btn-light border-dark-blue box-shadow'; 
        $btnNotSelected = 'btn-secondary text-white';
        list($bartBtn, $projBtn) = $view === 'BART' ? [$btnSelected, $btnNotSelected] : [$btnNotSelected, $btnSelected];
        if ($bdPermit) {
            print "
                <div class='row'>
                    <div class='col-12 d-flex'>
                        <a href='DisplayDefs.php' class='btn $projBtn flex-grow item-margin-right'>Project deficiencies</a>
                        <a href='DisplayDefs.php?view=BART' class='btn $bartBtn flex-grow item-margin-right'>BART deficiencies</a>
                    </div>
                </div>
            ";
        }
    ?>
</header>
<?php
    echo "<main class='container main-content'>";
    echo printSearchBar($link, $postData, [ method => 'POST', action => 'DisplayDefs.php' ]);
    if ($view !== 'BART' || !$bdPermit) {
        $sql = file_get_contents("CDList.sql").$whereCls;
        printInfoBox($roleLvl, 'NewDef.php');
        printProjectDefsTable($link, $sql, $roleLvl);
    } elseif ($bdPermit) {
        $sql = 'SELECT '.file_get_contents('bartdl.sql').' FROM BARTDL';
        printInfoBox($roleLvl, 'newBartDef.php');
        printBartDefsTable($link, $sql, $bdPermit);
    }
    $result->close();
    echo "</main>";
    echo "
        <script>
            function resetSearch(ev) {
                ev.target.form.reset();
                ev.target.form.submit();
            }
        </script>";
                    
mysqli_close($link);
    
include 'fileend.php';
?>
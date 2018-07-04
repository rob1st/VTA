<?php
include('session.php');
// session_start();
include('SQLFunctions.php');
include('utils/utils.php');
include('html_functions/htmlTables.php');
$title = "View Deficiencies";
$role = $_SESSION['role'];
$view = $_GET['view'];

// $roleLvlMap = [
//     'V' => 1,
//     'U' => 2,
//     'A' => 3,
//     'S' => 4
// ];
// $roleLvl = $roleLvlMap[$role];

include('filestart.php');
try {
    $link = connect();
    $link->where('userid', $_SESSION['userID']);
    $result = $link->getOne('users_enc', [ 'bdPermit' ]);
    $bartPermit = $result['bdPermit'];
} catch (Exception $e) {
    echo "<h1 style='font-size: 4rem; font-family: monospace; color: red;'>$e</h1>";
    exit;
}

if (isset($bartPermit)) echo "<h1 style='font-size: 4rem; color: #1ca;'>$bartPermit</h1>";
else echo "<h1 style='font-size: 4rem; color: #9d1;'>Houston, we have a problem<h1>";

// function concatSqlStr($arr, $tableName, $initStr = '') {
//     $joiner = 'WHERE';
//     $equality = '=';
//     $qStr = $initStr;
//     foreach ($arr as $key => $val) {
//         if (strpos(strtolower($key), 'description') !== false) {
//             $equality = ' LIKE ';
//             $val = "%{$val}%";
//         }
//         $qStr .= " $joiner $tableName.{$key}{$equality}'{$val}'";
//         $joiner = 'AND';
//         $equality = '=';
//     }
//     return $qStr;
// }

function printInfoBox($userLvl, $href, $dataGraphic = false) {
    $dataContainer = $dataGraphic
        ? "<div class='row mb-3'><div id='dataContainer' class='col-md-4 offset-md-4 d-flex flex-row flex-wrap justify-content-start'></div></div>"
        : '';
    $box ="
        <div class='card item-margin-bottom'>
            <div class='card-body grey-bg'>
                $dataContainer
                <div class='row'>
                    <div class='col-12 d-flex flex-row flex-wrap justify-content-between align-items-center'>
                        <span class='mb-2'>Click Deficiency ID number to see full details</span>
                        %s
                    </div>
                </div>
            </div>
        </div>";
    $btn = $userLvl > 1 ? "<a href='%s' class='btn btn-primary'>Add New Deficiency</a>" : '';

    $box = sprintf($box, $btn);

    return printf($box, $href);
}

function printSearchBar($link, $post, $formAction) {
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
    if ($result = $link->query('SELECT defID from CDL')) {
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
    if ($result = $link->query('SELECT StatusID, Status from Status')) {
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
    if ($result = $link->query('SELECT SeverityID, SeverityName from Severity')) {
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
    if ($result = $link->query('SELECT s.SystemID, s.System from CDL c JOIN System s ON s.SystemID=c.SystemAffected GROUP BY System ORDER BY SystemID')) {
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
    if ($result = $link->query('SELECT s.SystemID, s.System FROM CDL c JOIN System s ON s.SystemID=c.GroupToResolve GROUP BY System ORDER BY SystemID')) {
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
                    <select name='location' class='form-control'>
                        <option value=''></option>";
    if ($result = $link->query('SELECT l.locationID, l.locationName FROM CDL c JOIN location l ON l.locationID=c.location GROUP BY locationName ORDER BY locationID')) {
        while ($row = $result->fetch_array()) {
            $select = ($post['location'] === $row[0]) ? 'selected' : '';
            $form .= "<option value='{$row[0]}' $select>{$row[1]}</option>";
        }
        $result->close();
    }
    $form .= "</select>
                </div>
                <div class='col-sm-2 pl-1 pr-1'>
                    <label class='input-label'>Specific location</label>
                    <select name='SpecLoc' class='form-control'>";
    if ($result = $link->query('SELECT SpecLoc FROM CDL GROUP BY SpecLoc')) {
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
    if ($result = $link->query('SELECT IdentifiedBy FROM CDL GROUP BY IdentifiedBy')) {
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
                    <button type='button' class='btn btn-primary item-margin-right' onclick='return resetSearch(event)'>Reset</button>
                </div>
            </div>
            </div>
        </form>
    </div>";
    print $form;
}

function printDefsTable($result, $tableElements, $userLvl) {
    if (count($result)) {
        $keys = array_keys($tableElements);
        $headers = array_combine(
            $keys, array_column($tableElements, 'header')
        );
        $cells = array_combine(
            $keys, array_column($tableElements, 'cell')
        );
        echo "<table class='table table-striped table-responsive svbx-table'>";
        printTableHeadings($headers, $userLvl);
        populateTable($result, $cells, $userLvl);
        echo "</table>";
    } else
        print "<h4 class='text-secondary text-center'>No results found for your search</h4>";
}

function printProjectDefsTable($result, $userLvl) {
    $tdClassList = 'svbx-td';
    $thClassList = 'svbx-th';
    $collapseXs = 'collapse-xs';
    $collapseSm = 'collapse-sm collapse-xs';
    $collapseMd = 'collapse-md  collapse-sm collapse-xs';
    $tableElements = [
        'ID' => [
            'header' => [ 'text' => 'ID', 'classList' => $thClassList ],
            'cell' => [ 'classList' => $tdClassList, 'innerHtml' => "<a href='ViewDef.php?defID=%s'>%s</a>" ]
        ],
        'location' => [
            'header' => [ 'text' => 'Location', 'classList' => "$thClassList $collapseSm" ],
            'cell' => [ 'classList' => "$tdClassList $collapseSm" ]
        ],
        'severity' => [
            'header' => [ 'text' => 'Severity', 'classList' => "$thClassList $collapseXs" ],
            'cell' => [ 'classList' => "$tdClassList $collapseXs" ]
        ],
        'dateCreated' => [
            'header' => [ 'text' => 'Date Created', 'classList' => "$thClassList $collapseMd" ],
            'cell' => [ 'classList' => "$tdClassList $collapseMd" ]
        ],
        'status' => [
            'header' => [ 'text' => 'Status', 'classList' => $thClassList ],
            'cell' => [ 'classList' => $tdClassList ]
        ],
        'systemAffected' => [
            'header' => [ 'text' => 'System Affected', 'classList' => "$thClassList $collapseSm" ],
            'cell' => [ 'classList' => "$tdClassList $collapseSm" ]
        ],
        'description' => [
            'header' => [ 'text' => 'Brief Description', 'classList' => $thClassList ],
            'cell' => [ 'classList' => $tdClassList ]
        ],
        'specLoc' => [
            'header' =>  [ 'text' => 'Specific Location', 'classList' => "$thClassList $collapseMd" ],
            'cell' => [ 'classList' => "$tdClassList $collapseMd" ]
        ],
        'lastUpdated' => [
            'header' => [ 'auth' => 20, 'text' => 'Last Updated', 'classList' => "$thClassList $collapseMd" ],
            'cell' => [ 'auth' => 20, 'classList' => "$tdClassList $collapseMd" ]
        ],
        'edit' => [
            'header' => ['auth' => 20, 'text' => 'Edit', 'classList' => "$thClassList $collapseSm" ],
            'cell' => [
                'auth' => 20,
                'classList' => "$tdClassList $collapseSm",
                'innerHtml' => "<a id='updateDef%s' href='updateDef.php?defID=%s' class='btn btn-outline'><i class='typcn typcn-edit'></i></button>"
            ]
        ]
    ];
    printDefsTable($result, $tableElements, $userLvl);
}

function printBartDefsTable($link, $userLvl) {
    // build SELECT query string from sql file
    $fieldList = preg_replace('/\s+/', '', file_get_contents('bartdl.sql'))
        .',form_modified';
    // replace ambiguous or JOINED keys
    $fieldList = str_replace('updated_by', 'BARTDL.updated_by AS updated_by', $fieldList);
    $fieldList = str_replace('status', 's.status AS status', $fieldList);
    $fieldList = str_replace('agree_vta', 'ag.agreeDisagreeName AS agree_vta', $fieldList);
    $fieldList = str_replace('creator', 'c.partyName AS creator', $fieldList);
    $fieldList = str_replace('next_step', 'n.nextStepName AS next_step', $fieldList);

    $qry = 'SELECT '
            ." BARTDL.id, s.status s, date_created, descriptive_title_vta, resolution_vta, n.nextStepName"
            ." FROM BARTDL"
            ." JOIN bdNextStep n ON BARTDL.next_step=n.bdNextStepID"
            ." JOIN Status s ON BARTDL.status=s.statusID"
            ." ORDER BY BARTDL.id";
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
            'header' => [ 'text' => 'Status' ],
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
                'element' => sprintf("<td class='%s'><a id='updateDef%s'  href='updateBartDef.php?bartDefID=%s'><i class='typcn typcn-edit'></i></a></td>", "$thClassList $collapseSm", '%s', '%s'),
                'innerHtml' => "<a id='updateDef%s' href='updateBartDef.php?bartDefID=%s' class='btn btn-outline'><i class='typcn typcn-edit'></i></button></form>"
            ]
        ]
    ];

    printDefsTable($link, $qry, $tableFields, $userLvl);
}

// if($_POST['Search'] == NULL) {
//     $whereCls = ' WHERE D.Status <> 3 ORDER BY DefID';
//     $count = "SELECT COUNT(*) FROM CDL";
// } else {
//     $postData = array_filter($_POST);
//     unset($postData['Search']);
//
//     $count = "SELECT COUNT(*) FROM CDL D";
//
//     $whereCls = concatSqlStr($postData, 'D');
//     $count .= concatSqlStr($postData, 'D');
// }

?>
<header class="container page-header">
    <h1 class="page-title">Deficiencies</h1>
    <?php
        $btnSelected = 'btn-light border-dark-blue box-shadow-blue';
        $btnNotSelected = 'btn-secondary text-white';
        list($bartBtn, $projBtn) = $view === 'BART' ? [$btnSelected, $btnNotSelected] : [$btnNotSelected, $btnSelected];
        if ($bartPermit) {
            print "
                <div class='row'>
                    <div class='col-12 d-flex'>
                        <a href='defs.php' class='btn $projBtn flex-grow item-margin-right text-wrap'>Project deficiencies</a>
                        <a href='defs.php?view=BART' class='btn $bartBtn flex-grow item-margin-right text-wrap'>BART deficiencies</a>
                    </div>
                </div>
            ";
        }
    ?>
</header>
<?php
    echo "<main class='container main-content'>";
    if ($view !== 'BART' || !$bartPermit) {
        $sql = file_get_contents('CDList.sql');
        try {
            $link->orderBy('ID', 'ASC');
            $link->where('c.status', 3, '<>');
            $result = $link->query($sql);
        } catch (Exception $e) {
            echo "<h1 style='color: #da0;'>$e</h1>";
        }
        // printSearchBar($link, $postData, [ method => 'POST', action => 'defs.php' ]);
        // printInfoBox($roleLvl, 'NewDef.php');
        printProjectDefsTable($result, $_SESSION['role']);
        // echo "<pre style='color: #129;'>";
        // var_dump($result);
        // echo "</pre>";
    } elseif ($bartPermit) {
        $statusSql = 'SELECT s.status, count(id) from BARTDL b JOIN Status s ON b.status=s.statusID GROUP BY s.status';
        $altStatusSql = "SELECT COUNT(CASE WHEN s.status='open' THEN 1
            ELSE NULL END) AS statusOpen,
            COUNT(CASE WHEN s.status='closed' THEN 1
            ELSE NULL END) AS statusClosed
            FROM BARTDL b JOIN Status s
            ON b.status=s.statusID";

        $errFormat = "<p class='text-red'>%s</p>";

        if (!$res = $link->query($altStatusSql)) printf($errFormat, $link->error);
        elseif (!$statusData = $res->fetch_assoc()) printf($errFormat, $res->error);

        printInfoBox($_SESSION['role'], 'newBartDef.php', 1);
        printBartDefsTable($link, $bartPermit);
    }
    echo "</main>";
    echo "
        <script src='https://d3js.org/d3.v5.js'></script>
        <script src='js/pie_chart.js'></script>
        <script>
            function resetSearch(ev) {
                ev.target.form.reset();
                ev.target.form.submit();
            }";
        if ($view === 'BART' && $bartPermit) {
            echo "
                const openCloseChart = new PieChart(
                    window.d3,
                    'dataContainer',
                    { open: '{$statusData['statusOpen']}', closed: '{$statusData['statusClosed']}' },
                    { red: 'var(--red)', green: 'var(--green)' });
                openCloseChart.draw();";
        }
    echo "</script>";

$link->disconnect();

include 'fileend.php';
?>

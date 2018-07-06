<?php
include('session.php');
// session_start();
include('SQLFunctions.php');
include('utils/utils.php');
include('html_functions/htmlTables.php');
$title = "View Deficiencies";
$role = $_SESSION['role'];
$view = isset($_GET['view']) ? $_GET['view'] : '';

include('filestart.php');

// query to see if user has permission to view BART defs
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
else echo "<h1 style='font-size: 4rem; color: #9d1;'>$role</h1>";

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

function printSearchBar($link, $get, $formAction) {
    list($collapsed, $show) = isset($get['search']) ? ['', ' show'] : ['collapsed', ''];
    $marker = '%s';
    $formF = "
        <div class='row item-margin-bottom'>
            <form method='{$formAction['method']}' action='{$formAction['action']}' class='col-12'>
                <div class='row'>
                    <h5 class='col-12'>
                        <a
                            data-toggle='collapse'
                            href='#filterDefs'
                            role='button'
                            aria-expanded='false'
                            aria-controls='filterDefs'
                            class=''
                        >Filter deficiencies<i class='typcn typcn-arrow-sorted-down'></i>
                        </a>
                    </h5>
                </div>
                <div class='collapse show' id='filterDefs'>%s</div>
            </form>
        </div>";
    $rowF = "<div class='row'>%s</div>";
    $colF = "<div class='col-%s col-sm-%s pl-1 pr-1'>%s</div>";
    $labelF = "<label>%s</label>";
    $selectF = "
        <select name='%s' class='form-control'>
            <option value=''></option>
            %s
        </select>";
    $optionF = "<option value='%s'%s>%s</option>";

    $makeSelectEl = function ($labelText, $param, array $fields, array $colWds, $data) use ($get, $labelF, $selectF, $optionF, $colF)
    {
        list($inputVal, $inputText) = isset($fields[1])
            ? [ $fields[0], $fields[1] ] : [ $fields[0], $fields[0]];
        $opts = '';
        foreach ($data as $row) {
            $selected = isset($get[$param]) && $get[$param] === $row[$fields[0]]
                ? ' selected' : '';
            $opts .= sprintf($optionF, $row[$inputVal], $selected, $row[$inputText]);
        }
        $curLab = sprintf($labelF, $labelText);
        $curEl = sprintf($selectF, $param, $opts);
        // return sprintf('%s', 'CONTENT!' . '6');
        return sprintf($colF, 6, 2, $curLab . $curEl);

    };
    // collect elements w/i cols in 2 two rows
    if ($result = $link->get('CDL', null, 'defID')) {
        // collect <option> els in a str before sprintf <select>
        // $opts = '';
        // foreach ($result as $row) {
        //     $selected = isset($get['defID']) &&$get['defID'] === $row['defID']
        //         ? ' selected' : '';
        //     $opts .= sprintf($optionF, $row['defID'], $selected, $row['defID']);
        // }
        // $curEl = sprintf($selectF, 'ID', $opts);
        // $cols = sprintf($colF, 6, 1, $curEl);

        // this is the first column so we start a new $cols collector
        $cols = $makeSelectEl('Def #', 'defID', ['defID'], [6, 1], $result);
    } else throw new mysqli_sql_exception("select defID no good");

    if ($result = $link->get('status', null, 'statusID, statusName')) {
        // $opts = '';
        // foreach ($result as $row) {
        //     $selected = isset($get['status']) && $get['status'] === $row['statusID']
        //         ? ' selected' : '';
        //     $opts .= sprintf($optionF, $row['statusID'], $selected, $row['statusName']);
        // }
        // $curLab = sprintf($labelF, 'Status');
        // $curEl = sprintf($selectF, 'status', $opts);
        // sprintf($colF, 6, 2, $curLab . $curEl);
        $cols .= $makeSelectEl('Status', 'status', ['statusID', 'statusName'], [6, 2], $result);
    } else throw new mysqli_sql_exception("select status no good");

    if ($result = $link->get('yesNo', null, 'yesNoID, yesNoName')) {
        // $opts = '';
        // foreach ($result as $row) {
        //     $selected = isset($get['safetyCert']) && $get['safetyCert'] === 'yesNoID'
        //         ? ' selected' : '';
        //     $opts .= sprintf($optionF, $row['yesNoID'], $selected, $row['yesNoName']);
        // }
        // $curEl = sprintf($selectF, 'safetyCert', $opts);
        // $cols .= sprintf($colF, 6, 1, $curEl);
        $cols .= $makeSelectEl('Safety cert', 'safetyCert', ['yesNoID', 'yesNoName'], [6, 1], $result);
    } else throw new mysqli_sql_exception("select safetyCert no good");

    if ($result = $link->get('severity', null, 'severityID, severityName')) {
        // $opts = '';
        // foreach ($result as $row) {
        //     $selected = isset($get['severity']) && $get['severity'] === $row['severityID']
        //         ? ' selected' : '';
        //     $opts .= sprintf($optionF, $row['severityID'], $selected, $row['severityName']);
        // }
        // $curEl = sprintf($selectF, 'severity', $opts);
        // $cols .= sprintf($colF, 6, 2, $curEl);
        $cols .= $makeSelectEl('Severity', 'severity', ['severityID', 'severityName'], [6, 2], $result);
    } else throw new mysqli_sql_exception("select severity no good");

    $link->join('system s', 'c.systemAffected = s.systemID', 'INNER');
    $link->groupBy('systemName');
    $link->orderBy('systemID');
    if ($result = $link->get('CDL c', null, 'systemID, systemName')) {
        // $opts = '';
        // foreach ($result as $row) {
        //     $selected = isset($get['systemAffected']) && $get['systemAffected'] === $row['systemID']
        //         ? ' selected' : '';
        //     $opts .= sprintf($optionF, $row['systemID'], $selected, $row['systemName']);
        // }
        // $curEl = sprintf($selectF, 'systemAffected', $opts);
        // $cols .= sprintf($colF, 6, 2, $curEl);
        $cols .= $makeSelectEl('System affected', 'systemAffected', ['systemID', 'systemName'], [6, 3], $result);
    } else throw new mysqli_sql_exception("select system no good");

    $link->join('system s', 'c.groupToResolve = s.systemID', 'INNER');
    $link->groupBy('systemName');
    $link->orderBy('systemID');
    if ($result = $link->get('CDL c', null, 'systemID, systemName')) {
        // $opts = '';
        // foreach ($result as $row) {
        //     $selected = isset($get['groupToResolve']) && $get['groupToResolve'] === $row['systemID']
        //         ? ' selected' : '';
        //     $opts .= sprintf($optionF, $row['systemID'], $selected, $row['systemName']);
        // }
        // $curEl = sprintf($selectF, 'groupToResolve', $opts);
        // $cols .= sprintf($colF, 6, 2, $curEl);
        $cols .= $makeSelectEl('Group to resolve', 'groupToResolve', ['systemID', 'systemName'], [6, 3], $result);
    } else throw new mysqli_sql_exception("select groupToResolve no good");

    // finish first row
    $row1 = sprintf($rowF, $cols);

    // begin new row with a fresh $cols collector
    $curLab = sprintf($labelF, 'Description');
    $curVal = isset($get['description']) ? $get['description'] : '';
    $curEl = "<input type='text' name='description' class='form-control' value='$curVal'>";
    $cols = sprintf($colF, 4, 4, $curLab . $curEl);
                // <div class='col-sm-2 pl-1 pr-1'>
                //     <label class='input-label'>Location</label>
                //     <select name='location' class='form-control'>
                //         <option value=''></option>";
    $link->join('location l', 'c.location = l.locationID', 'INNER');
    $link->groupBy('locationName');
    $link->orderBy('locationID');
    if ($result = $link->get('CDL c', null, 'l.locationID, l.locationName')) {
        // $opts = '';
        // foreach ($result as $row) {
        //     $selected = isset($get['location']) && $get['location'] === $row['locationID']
        //         ? ' selected' : '';
        //     $opts .= sprintf($optionF, $row['locationID'], $selected, $row['locationName']);
        // }
        // $curEl = sprintf($selectF, 'location', $opts);
        // $cols .= sprintf($colF, 6, 2, $curEl);
        $cols .= $makeSelectEl('Location', 'location', ['locationID', 'locationName'], [6, 2], $result);
    } else throw new mysqli_sql_exception("select groupToResolve no good");

    $link->groupBy('specLoc');
    if ($result = $link->get('CDL', null, 'specLoc')) {
        // $opts = '';
        // foreach ($result as $row) {
        //     $selected = isset($get['specLoc']) && $get['location'] === $row['specLoc']
        //         ? ' selected' : '';
        //     $opts .= sprintf($optionF, $row['specLoc'], $selected, $row['specLoc']);
        // }
        // $curEl = sprintf($selectF, 'specLoc', $opts);
        // $cols .= sprintf($colF, 6, 2, $curEl);
        $cols .= $makeSelectEl('Specific location', 'specLoc', ['specLoc'], [6, 2], $result);
    } else throw new mysqli_sql_exception("select groupToResolve no good");

    $link->groupBy('identifiedBy');
    if ($result = $link->get('CDL', null, 'identifiedBy')) {
        // $opts = '';
        // foreach ($result as $row) {
        //     $selected = isset($get['identifiedBy']) && $get['identifiedBy'] === $row['identifiedBy']
        //         ? ' selected' : '';
        //     $opts .= sprintf($optionF, $row['identifiedBy'], $selected, $row['identifiedBy']);
        // }
        // $curEl = sprintf($selectF, 'identifiedBy', $opts);
        // $cols .= sprintf($colF, 6, 2, $curEl);
        $cols .= $makeSelectEl('Identified by', 'identifiedBy', ['identifiedBy'], [6, 2], $result);
    } else throw new mysqli_sql_exception("select groupToResolve no good");

    // submit and reset buttons
    $buttons = "
            <button name='search' value='search' type='submit' class='btn btn-primary item-margin-right'>Search</button>
            <button type='button' class='btn btn-primary item-margin-right' onclick='return resetSearch(event)'>Reset</button>";
    // buttons column needs flex classes so I tack them on after bootstrap col width class
    $cols .= sprintf($colF, 12, '2 flex-row justify-center align-end', $buttons);

    // finish second row;
    $row2 = sprintf($rowF, $cols);

    $form = sprintf($formF, $row1 . $row2);

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
            'header' => ['auth' => 15, 'text' => 'Edit', 'classList' => "$thClassList $collapseSm" ],
            'cell' => [
                'auth' => 15,
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

// check for search params
// if no search params show all defs that are not 'deleted'
if(isset($_GET['search'])) {
    $get = filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS);
    $get = array_filter($get); // filter to remove falsey values -- is this necessary?
    unset($get['search']);

    foreach ($get as $param => $val) {
        if ($param === 'description') $link->where($param, "%{$val}%", 'LIKE');
        else $link->where($param, $val);
    }
} else {
    $get = null;
}

?>
<header class="container page-header">
    <h1 class="page-title">Deficiencies</h1>
    <?php
        $btnSelected = 'btn-light border-dark-blue box-shadow-blue';
        $btnNotSelected = 'btn-secondary text-white';
        list($bartBtn, $projBtn) = $view === 'BART'
            ? [$btnSelected, $btnNotSelected]
            : [$btnNotSelected, $btnSelected];
        if ($bartPermit) {
            echo "
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
<main class='container main-content'>
<?php
    // render Project Defs table and Search Fields
    if ($view !== 'BART' || !$bartPermit) {
        try {
            printSearchBar($link, $get, ['method' => 'GET', 'action' => 'defs.php']);
        } catch (Exception $e) {
            echo "print search bar got issues: $e";
        }
        try {
            $fields = [
                "c.defID AS ID",
                "l.locationName AS location",
                "s.severityName AS severity",
                "DATE_FORMAT(c.dateCreated, '%d %b %Y') AS dateCreated",
                "t.statusName AS status",
                "y.systemName AS systemAffected",
                "SUBSTR(c.description, 1, 50) AS description",
                "c.specLoc AS specLoc",
                "c.lastUpdated AS lastUpdated"
            ];
            $joins = [
                "location l" => "c.location = l.locationID",
                "severity s" => "c.severity = s.severityID",
                "status t" => "c.status = t.statusID",
                "system y" => "c.systemAffected = y.systemID"
            ];
            foreach ($joins as $tableName => $on) {
                $link->join($tableName, $on, 'LEFT');
            }
            $link->orderBy('ID', 'ASC');
            $link->where('c.status', 3, '<>');
            $result = $link->get('CDL c', 20, $fields);
            printProjectDefsTable($result, $_SESSION['role']);
            // $result = $link->query($sql);
        } catch (Exception $e) {
            echo "<h1 style='color: #da0;'>$e</h1>";
        }
        // printInfoBox($roleLvl, 'NewDef.php');
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

<?php
include('SQLFunctions.php');
session_start();
include('filestart.php');
$title = "SVBX - Inspector's Daily Report";
$curDateText = date('M j, Y');
$curDateNum = date('Y-m-d');
$reptNum = 0; // generate this based on db

$link = f_sqlConnect();
$userID = $_SESSION['UserID'];
// why do this check if $_SESSION already has $Username(?)
$user = "SELECT Username FROM users_enc WHERE UserID = ".$UserID;
if($result=mysqli_query($link,$user)) {
  /*from the sql results, assign the username that returned to the $username variable*/    
  while($row = mysqli_fetch_assoc($result)) {
    $username = $row['Username'];
  }
}

$sqlLoc = "SELECT L.LocationName, C.Location FROM CDL C inner join Location L on L.LocationID=C.Location group by Location order by L.LocationName";

$authorizedUsers = [
    'svandevanter',
    'avallejo',
    'Superadmin'
];

if (!in_array($username, $authorizedUsers)) {
    include 'unauthorized.php';
} else echo "
<header class='container page-header'>
    <h1 class='page-title'>Inspector's Daily Report</h1>
</header>
<main class='container main-content'>
    <form id='dailyReportForm'>
        <div class='flex-row space-between align-stretch item-margin-bottom'>
            <fieldset id='dayData' class='card half-container'>
                <div class='card-header grey-bg'>";
                    if (!$reptNum) {
                        echo "<h6 class='text-danger'>Unable to retrieve Report Number</h6>
                                <p>Try refreshing the page</p>";
                    } else echo "<h6>Report #${reptNum}</h6>";
echo "
                </div>
                <div class='card-body'>
                    <div class='flex-row no-wrap space-between align-center item-margin-bottom'>
                        <label class='input-label item-margin-right'>Contract day</label>
                            <input type='date' value='{$curDateNum}' id='contractDay' name='contractDay' class='form-control' />
                    </div>
                    <div class='flex-row no-wrap space-between align-center item-margin-bottom'>
                        <label class='input-label item-margin-right'>Date</label>
                        <input type='text' value='{$curDateText}' id='curDate' name='curDate' class='form-control' readonly />
                    </div>
                    <div class='flex-row no-wrap space-between align-center item-margin-bottom'>
                        <label class='input-label item-margin-right'>Project</label>
                        <input type='text' id='projectId' name='projectId' class='form-control' />
                    </div>
                    <div class='flex-row no-wrap space-between align-center item-margin-bottom'>
                        <label class='input-label item-margin-right'>Weather</label>
                        <input type='text' id='weatherDescrip' name='weatherDescrip' class='form-control' />
                    </div>
                    <div class='flex-row no-wrap space-between align-center item-margin-bottom'>
                        <label class='input-label item-margin-right'>Shift Hrs</label>
                        <input type='text' id='shiftHrs' name='shiftHrs' class='form-control' />
                    </div>
                </div>
            </fieldset>
            <fieldset id='safetyData' class='card half-container'>
                <div class='card-header grey-bg'>
                    <h6>Safety</h6>
                </div>
                <div class='card-body'>
                    <div class='flex-row no-wrap space-between item-margin-bottom'>
                        <label class='input-label'>EIC</label>
                        <input type='text' id='eic' name='eic' class='form-control' />
                    </div>
                    <div class='flex-row no-wrap space-between item-margin-bottom'>
                        <label class='input-label'>Watchman</label>
                        <input type='text' id='watchman' name='watchman' class='form-control' />
                    </div>
                    <div class='flex-row no-wrap space-between item-margin-bottom'>
                        <label class='input-label'>RAP #</label>
                        <input type='text' id='rapNum' name='rapNum' class='form-control' />
                    </div>
                    <div class='flex-row no-wrap space-between item-margin-bottom'>
                        <label class='input-label'>SSWP #</label>
                        <input type='text' id='sswpNum' name='sswpNum' class='form-control' />
                    </div>
                    <div class='flex-row no-wrap space-between item-margin-bottom'>
                        <label class='input-label'>TCP #</label>
                        <input type='text' id='tcpNum' name='tcpNum' class='form-control' />
                    </div>
                </div>
            </fieldset>
        </div>
        
        <div id='locAndDescrip' class='flex-row grey-bg form-section-heading'>
            <div class='item-margin-right'>
                <label class='input-label'>Location</label>
                <select name='location' class='form-control'>";
                    if ($result = mysqli_query($link, $sqlLoc)) {
                        while ($row = mysqli_fetch_array($result)) {
                            if (strpos($row[0], 'Berryessa') !== false OR strpos($row[0], 'Milpitas') !== false) {
                                echo "<option value='{$row[1]}'>{$row[0]}</option>";
                            }
                        }
                    }
echo "
                </select>
            </div>
            <div class='flex item-margin-right'>
                <label class='input-label'>Description of Operation</label>
                <input type='text' name='opDesc' class='form-control full-width' />
            </div>
        </div>
        <div id='workInputList'>
            <div id='workInputGroup_0' class='form-subsection item-border-bottom item-margin-bottom'>
                <div class='flex-row item-margin-bottom'>
                    <div class='item-margin-right'>
                        <label class='input-label'>Equip/Labor</label>
                        <select id='selectEquipPersons_0' name='equipOrPersons_0' class='form-control'>
                            <option value='equipment' selected>Equipment</option>
                            <option value='labor'>Labor</option>
                        </select>
                    </div>
                    <div class='item-margin-right'>
                        <label class='input-label' id='labelNumEquipLabor_0'>Equipment No.</label>
                        <input type='number' name='numEquip_0' class='form-control' style='max-width:110px' />
                    </div>
                    <div class='item-margin-right flex-grow'>
                        <label class='input-label' id='labelDescEquipLabor_0'>Description of Equipment</label>
                        <input type='text' name='equipDesc_0' class='form-control full-width' />
                    </div>
                    <div class='item-margin-right' style='position:relative'>
                        <label class='input-label'>Notes</label>
                        <button type='button' id='showNotes_0' class='form-control'><i class='typcn typcn-document-text'></i></button>
                        <aside
                            id='notesField_0'
                            style='
                                display: none;
                                position: absolute;
                                right: 46px;
                                bottom: -2px;
                                border: 1px solid #3333;
                                padding: .25rem;
                                background-color: white;
                            '
                        >
                            <textarea name='remarks_0' rows='5' cols='30' maxlength='125' class='form-control'></textarea>
                        </aside>
                    </div>
                </div>
                <div class='pad border-radius grey-bg'>
                    <div class='flex-row item-margin-bottom'>
                        <div class='flex-grow item-margin-right'>
                            <label class='input-label'>Description of task/activity</label>
                            <input id='taskInput_0' type='text' class='form-control full-width' />
                        </div>
                        <div class='item-margin-right'>
                            <label class='input-label'>Add Task</label>
                            <button type='button' id='addTask_0' class='btn btn-success block'>Add<i class='typcn typcn-chevron-right-outline'></i></button>
                        </div>
                        <div class='item-margin-right' style='min-width:150px'>
                            <label class='input-label'>Task/activity</label>
                            <select id='taskList_0' name='taskList_0' class='form-control full-width'>
                            </select>
                        </div>
                        <div class='item-margin-right' style='max-width: 100px;'>
                            <label class='input-label'>Hours</label>
                            <input type='number' id='hours_0' name='hours_0' class='form-control full-width' />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style='text-align: right'>
            <button type='button' id='addLineBtn' class='btn btn-success'>Add Line</button>
        </div>
        <div class='center-content'>
            <button type='submit' class='btn btn-primary btn-lg'>Submit</button>
        </div>
    </form>
</main>
<script src='js/dailyReport.js'></script>";
MySqli_Close($link);
include('fileend.php');
?>
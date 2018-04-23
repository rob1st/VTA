<?php
include('SQLFunctions.php');
session_start();
include('filestart.php');
$title = "SVBX - Inspector's Daily Report";
$curDateNum = date('Y-m-d');

$link = f_sqlConnect();
$userID = $_SESSION['UserID'];
$username = $_SESSION['Username'];
// why do this check if $_SESSION already has $Username(?)
$userQry = 'SELECT firstname, lastname, viewIDR FROM users_enc WHERE UserID = '.$UserID;
$contractQry = 'SELECT ContractID, Contract FROM Contract';
if($result=mysqli_query($link,$userQry)) {
  /*from the sql results, assign the username that returned to the $username variable*/    
  while($row = mysqli_fetch_assoc($result)) {
    $userFullName = "{$row['firstname']} {$row['lastname']}";
    $userAuth = intval($row['viewIDR']);
  }
}

/*
how do I use boolean conditions with JOINs?

SELECT * FROM (
(((IDR i
    JOIN labor l ON
    i.idrID=l.idrID
    AND i.idrID=1)
    JOIN laborAct_link link ON
    l.laborID=link.laborID)
    JOIN activity a ON link.activityID=a.activityID)
OR (((IDR i
        JOIN equipment e ON
        i.idrID=e.idrID
        AND i.idrID=1)
        JOIN equipAct_link link ON
        e.equipID=link.equipID)
        JOIN activity a ON link.activityID=a.activityID)
);
*/

// determine view
if ($idrID = $_GET['idrID']) {
    $view = $_GET['view'];
    $idrQry = "SELECT idrID, firstname, lastname, idrDate, Contract, weather, shift, EIC, watchman, rapNum, sswpNum, tcpNum, locationName, opDesc, approvedBy
        FROM (((IDR i
        JOIN users_enc u ON
        i.userID=u.UserID)
        JOIN Location l ON
        i.locationID=l.locationID)
        JOIN Contract c ON
        i.project=c.ContractID)
        WHERE i.idrID=$idrID";
    $laborQry = "SELECT * FROM labor WHERE idrID=$idrID";
    $equipQry = "SELECT * FROM equipment WHERE idrID=$idrID";

    if ($result = $link->query($idrQry)) {
        $laborResult = $link->query($laborQry);
        $equipResult = $link->query($equipQry);
    }
}

$sqlLoc = "SELECT L.LocationName, C.Location FROM CDL C inner join Location L on L.LocationID=C.Location group by Location order by L.LocationName";

if ($userAuth < 1) {
    include 'unauthorised.php';
} else {
echo "
    <header class='container page-header'>
        <h1 class='page-title'>Inspector's Daily Report</h1>
    </header>
    <main class='container main-content'>
";
    if ($view === 'comment') {
        echo "<h1 style='color: chartreuse; text-align: center;'>Comment View</h1>";
    } elseif ($view === 'review') {
        if ($result = $link->query($idrQry)) {
            while ($row = $result->fetch_assoc()) {
                // reviewer's view
                echo "
                <h3 style='text-align: center; font-style: italic;'>Review</h3>
                <div class='row'>
                    <div class='col-md-6'>
                        <div class='card'>
                            <div class='card-header'>
                                <h6 class='flex-row space-between'>
                                    <span>Inspector Name</span>
                                    <span>{$row['firstname']} {$row['lastname']}</span>
                                </h6>
                            </div>
                            <div class='card-body'>
                                <ul>
                                    <li class='flex-row space-between'>
                                        <span>date</span>
                                        <span>{$row['idrDate']}</span>
                                    </li>
                                    <li class='flex-row space-between'>
                                        <span>project</span>
                                        <span>{$row['Contract']}</span>
                                    </li>
                                    <li class='flex-row space-between'>
                                        <span>weather</span>
                                        <span>{$row['weather']}</span>
                                    </li>
                                    <li class='flex-row space-between'>
                                        <span>shift hours</span>
                                        <span>{$row['shift']}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class='card'>
                            <div class='card-header'><h6>Track safety</h6></div>
                            <div class='card-body'>
                                <ul>
                                    <li class='flex-row space-between'>
                                        <span>EIC</span>
                                        <span>{$row['EIC']}</span>
                                    </li>
                                    <li class='flex-row space-between'>
                                        <span>Watchman</span>
                                        <span>{$row['watchman']}</span>
                                    </li>
                                    <li class='flex-row space-between'>
                                        <span>Rap #</span>
                                        <span>{$row['rapNum']}</span>
                                    </li>
                                    <li class='flex-row space-between'>
                                        <span>SSWP #</span>
                                        <span>{$row['sswpNum']}</span>
                                    </li>
                                    <li class='flex-row space-between'>
                                        <span>TCP #</span>
                                        <span>{$row['tcpNum']}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-6'>
                        <h4 id='location'></h4>
                    </div>
                    <div class='col-6'>
                        <h4 id='discipline'></h4>
                    </div>
                </div>";
                // iterate over results and display as nested <ul>s
                if ($laborResult) {
                    $linkingT = 'laborAct_link';
                    $resourceT = 'labor';
                    $resourceID = 'laborID';
                    echo "<div id='laborResults' class='row'>";
                    while ($row = $laborResult->fetch_assoc()) {
                        $actQry = "SELECT * FROM (($linkingT link
                            JOIN $resourceT rsrc ON
                            link.{$resourceID}=rsrc.{$resourceID}
                            AND rsrc.{$resourceID}={$row[$resourceID]})
                            join activity a on
                            link.activityID=a.activityID)";
        
                        echo "
                        <ul>
                            <li>
                                <span style='color: grey; font-style: bold'>description of labor:</span>
                                <span> {$row['laborDesc']}, </span>
                                <span style='color: grey; font-style: bold'>number of personnel:</span>
                                <span> {$row['laborNum']}</span>
                            </li>";
                        if ($actResult = $link->query($actQry)) {
                            echo "<ul>";
                            while ($row = $actResult->fetch_assoc()) {
                                echo "
                                <li>
                                    <span style='color: grey; font-style: bold'>task:</span>
                                    <span> {$row['actDesc']}, </span>
                                    <span style='color: grey; font-style: bold'>hours:</span>
                                    <span> {$row['actHrs']}</span>
                                </li>";
                            }
                            echo "</ul>";
                        }
                        echo "</ul>";
                    }
                    echo "</div>";
                }
                if ($equipResult) {
                    $linkingT = 'equipAct_link';
                    $resourceT = 'equipment';
                    $resourceID = 'equipID';
                    echo "<div id='equipResults' class='row'>";
                    while ($row = $equipResult->fetch_assoc()) {
                        $actQry = "SELECT * FROM (($linkingT link
                            JOIN $resourceT rsrc ON
                            link.{$resourceID}=rsrc.{$resourceID}
                            AND rsrc.{$resourceID}={$row[$resourceID]})
                            join activity a on
                            link.activityID=a.activityID)";
                        echo "
                        <ul>
                            <li>descriptoin of equipment: {$row['equipDesc']}, number of equipment: {$row['equipNum']}</li>";
                        if ($actResult = $link->query($actQry)) {
                            echo "<ul>";
                            while ($row = $actResult->fetch_assoc()) {
                                echo "<li>task: {$row['actDesc']}, hours: {$row['actHrs']}</li>";
                            }
                            echo "</ul>";
                        }
                        echo "</ul>";
                    }
                    echo "</div>";
                }
                echo "
                <div class='row'>
                    <div class='col-12'>
                        <textarea id='commentBox' name='comment' class='form-control'></textarea>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-12'>
                        <button class='btn btn-lg btn-primary'>Approve</button>
                        <button class='btn btn-lg btn-primary'>Request revisions</button>
                    </div>
                </div>
                ";
            }
        }
    } elseif ($view === 'lookback') {
        echo "<h1 style='color: darkOrange; text-align: center;'>Lookback View</h1>";
    } else {
    // initial input view
    echo "
        <h6><span class='text-danger'>*</span><span> = required</span></h6>
        <form id='dailyReportForm'>
            <div class='flex-row space-between align-stretch item-margin-bottom'>
                <fieldset id='dayData' class='card half-container'>
                    <div class='card-header grey-bg'>
                        <h6 class='flex-row space-between'>
                            <span class='item-margin-right'>Inspector Name</span>
                            <span>{$userFullName}</span>
                        </h6>
                        <input type='hidden' name='userID' value='{$userID}' />
                    </div>
                    <div class='card-body'>
                        <div class='flex-row no-wrap space-between align-center item-margin-bottom'>
                            <label class='input-label item-margin-right'>Date</label>
                            <input type='date' value='{$curDateNum}' id='curDate' name='idrDate' class='form-control' readonly />
                        </div>
                        <div class='flex-row no-wrap space-between align-center item-margin-bottom'>
                            <label class='input-label item-margin-right'>Project<span class='text-danger'>*</span></label>
                            <select value='C700' name='project' class='form-control' required>";
                                if ($result = $link->query($contractQry)) {
                                    while ($row = $result->fetch_array()) {
                                        echo "<option value ='{$row[0]}'>{$row[1]}</option>";
                                    }
                                }
                                
    echo "
                            </select>
                        </div>
                        <div class='flex-row no-wrap space-between align-center item-margin-bottom'>
                            <label class='input-label item-margin-right'>Weather<span class='text-danger'>*</span></label>
                            <input type='text' id='weatherDescrip' name='weather' class='form-control' required />
                        </div>
                        <div class='flex-row no-wrap space-between align-center item-margin-bottom'>
                            <label class='input-label item-margin-right'>Shift Hrs<span class='text-danger'>*</span></label>
                            <input type='text' id='shiftHrs' name='shift' class='form-control' required />
                        </div>
                    </div>
                </fieldset>
                <fieldset id='safetyData' class='card half-container'>
                    <div class='card-header grey-bg'>
                        <h6>Track safety</h6>
                    </div>
                    <div class='card-body'>
                        <div class='flex-row no-wrap space-between item-margin-bottom'>
                            <label class='input-label'>EIC</label>
                            <input type='text' id='eic' name='EIC' class='form-control' />
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
                        </div>";
    echo "
                    </div>
                </fieldset>
            </div>
            
            <div id='locAndDescrip' class='flex-row grey-bg form-section-heading'>
                <div class='item-margin-right'>
                    <label class='input-label'>Location<span class='text-danger'>*</span></label>
                    <select name='locationID' class='form-control' required>";
                        if ($result = mysqli_query($link, $sqlLoc)) {
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<option value='{$row[1]}'>{$row[0]}</option>";
                            }
                        }
    echo "
                    </select>
                </div>
                <div class='flex item-margin-right'>
                    <label class='input-label'>Name of operation or discipline<span class='text-danger'>*</span></label>
                    <input type='text' name='opDesc' class='form-control full-width' required />
                </div>
            </div>
            <div id='workInputList'>
                <div id='workInputGroup_0' class='form-subsection item-border-bottom item-margin-bottom'>
                    <div class='flex-row item-margin-bottom'>
                        <div class='item-margin-right'>
                            <label class='input-label'>Equip/Labor<span class='text-danger'>*</span></label>";
                    // the value of this ctrl will determine whether data goes to equip or labor table
    echo "
                            <select id='selectEquipLabor_0' class='form-control' required>
                                <option value='labor' selected>Labor</option>
                                <option value='equipment'>Equipment</option>
                            </select>
                        </div>
                        <div class='item-margin-right'>
                            <label class='input-label' id='labelNumEquipLabor_0'># of Personnel<span class='text-danger'>*</span></label>
                            <input type='number' id='equipOrLaborNum_0' name='laborNum_0' class='form-control' style='max-width:110px' required />
                        </div>
                        <div class='item-margin-right flex-grow'>
                            <label class='input-label' id='labelDescEquipLabor_0'>Description of labor<span class='text-danger'>*</span></label>
                            <input type='text' id='equipOrLaborDesc_0' name='laborDesc_0' class='form-control full-width' required />
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
                                <input id='actInput_0' type='text' class='form-control full-width' />
                            </div>
                            <div class='item-margin-right'>
                                <label class='input-label'>Add Task</label>
                                <button type='button' id='addAct_0' class='btn btn-success block'>Add<i class='typcn typcn-chevron-right-outline'></i></button>
                            </div>
                            <div class='item-margin-right' style='min-width:150px'>
                                <label class='input-label'>Task/activity</label>
                                <select id='actList_0' class='form-control full-width'>
                                </select>
                            </div>
                            <div class='item-margin-right' style='max-width: 100px;'>
                                <label class='input-label'>Hours</label>
                                <input type='number' id='hours_0' class='form-control full-width' />
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
    <script src='js/dailyReport.js'></script>";
    }
echo "</main>";
}
MySqli_Close($link);
include('fileend.php');
?>
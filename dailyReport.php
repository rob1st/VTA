<?php
include('SQLFunctions.php');
session_start();
include('filestart.php');
$title = "SVBX - Inspector's Daily Report";
$curDate = date('M j, Y');
$reptNum = int; // generate this based on db

$link = f_sqlConnect();
$sqlLoc = "SELECT L.LocationName, C.Location FROM CDL C inner join Location L on L.LocationID=C.Location group by Location order by L.LocationName";
?>
<header class='container page-header'>
    <h1 class='page-title'>Inspector's Daily Report</h1>
</header>
<?php
echo "
<main class='container main-content'>
    <form action='submitDaily.php' method='POST'>
        <div class='flex-row space-between align-stretch'>
            <fieldset id='dayData' class='card half-container'>
                <div class='card-header grey-bg'>
                    <div class='flex-row no-wrap space-between align-center item-margin-bottom'>
                        <label class='input-label item-margin-right'>Report #</label>
                        <input type='text' value='${reptNum}' id='reportNum' class='form-control' readonly/>
                    </div>
                </div>
                <div class='card-body'>
                    <div class='flex-row no-wrap space-between align-center item-margin-bottom'>
                        <label class='input-label item-margin-right'>Contract day</label>
                        <input type='text' id='contractDay' class='form-control' />
                    </div>
                    <div class='flex-row no-wrap space-between align-center item-margin-bottom'>
                        <label class='input-label item-margin-right'>Date</label>
                        <input type='text' value='${curDate}' id='curDate' class='form-control' readonly />
                    </div>
                    <div class='flex-row no-wrap space-between align-center item-margin-bottom'>
                        <label class='input-label item-margin-right'>Project</label>
                        <input type='text' id='projectId' class='form-control' />
                    </div>
                    <div class='flex-row no-wrap space-between align-center item-margin-bottom'>
                        <label class='input-label item-margin-right'>Weather</label>
                        <input type='text' id='weatherDescrip' class='form-control' />
                    </div>
                    <div class='flex-row no-wrap space-between align-center item-margin-bottom'>
                        <label class='input-label item-margin-right'>Shift Hrs</label>
                        <input type='text' id='shiftHrs' class='form-control' />
                    </div>
                </div>
            </fieldset>
            <fieldset id='safetyData' class='card half-container'>
                <div class='card-header grey-bg'>
                    <h5 class='form-section-heading grey-bg'>Safety</h5>
                </div>
                <div class='card-body'>
                    <div class='flex-row no-wrap space-between'>
                        <label class='input-label'>EIC</label>
                        <input type='text' id='eic' class='form-control' />
                    </div>
                    <div class='flex-row no-wrap space-between'>
                        <label class='input-label'>Watchman</label>
                        <input type='text' id='watchman' class='form-control' />
                    </div>
                    <div class='flex-row no-wrap space-between'>
                        <label class='input-label'>RAP #</label>
                        <input type='text' id='rapNum' class='form-control' />
                    </div>
                </div>
            </fieldset>
        </div>
        
        <fieldset id='locAndDescrip' class='flex-row'>
                <select class='form-control'>";
                    if ($result = mysqli_query($link, $sqlLoc)) {
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<option value='{$row[1]}'>{$row[0]}</option>";
                        }
                    }
echo "
                </select>
            </fieldset>
        </div>
    </form>
</main>";
?>
<?php
include('fileend.php');
?>
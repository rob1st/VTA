<?php
include('SQLFunctions.php');
session_start();
include('filestart.php');
$title = "SVBX - Inspector's Daily Report";
$curDate = date('M j, Y');
$reptNum = 0; // generate this based on db

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
                    <h6>Safety</h6>
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
        
        <div class='card item-margin-bottom'>
            <div id='locAndDescrip' class='card-header grey-bg flex-row'>
                <div class='item-margin-right'>
                    <label>Location</label>
                    <select class='form-control'>";
                        if ($result = mysqli_query($link, $sqlLoc)) {
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<option value='{$row[1]}'>{$row[0]}</option>";
                            }
                        }
echo "
                    </select>
                </div>
                <div class='flex item-margin-right'>
                    <label>Description of Operation</label>
                    <input type='text' class='form-control full-width' />
                </div>
            </div>
            <div class='card-body'>
                <div class='flex-row'>
                    <div class='item-margin-right'>
                        <label>Equip/Labor</label>
                        <select id='selectEquipOrPersons' class='form-control'>
                            <option value='equipment' selected>Equipment</option>
                            <option value='labor'>Labor</option>
                        </select>
                    </div>
                    <div class='item-margin-right'>
                        <label id='labelForNumOfEquipOrPersons'>Equipment Number</label>
                        <input type='number' class='form-control' />
                    </div>
                    <div class='item-margin-right'>
                        <label>Description</label>
                        <input type='text' class='form-control' />
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>
<script>
    (function() {
        const select = document.getElementById('selectEquipOrPersons');
        const label = document.getElementById('labelForNumOfEquipOrPersons');
        select.addEventListener('change', event => {
            if (select.value === 'labor') label.innerText = 'Number of Personnel';
            else label.innerText = 'Equipment Number';
        })
    })()
</script>";
?>
<?php
include('fileend.php');
?>
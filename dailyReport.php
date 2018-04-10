<?php
include('SQLFunctions.php');
session_start();
include('filestart.php');
$curDate = date('M j, Y');
$reptNum = int; // generate this based on db
?>
<header class='container page-header'>
    <h1 class='page-title'>Inspector's Daily Report</h1>
</header>
<main class='container main-content'>
    <form action='submitDaily.php' method='POST'>
        <div class='flex-row space-between'>
            <fieldset id='dayData' class='card half-container'>
                <div class='flex-row no-wrap space-between grey-bg'>
                    <label class='form-section-heading'>Report #</label>
                    <input type='text' id='reportNum' class='form-control' />
                </div>
                <div class='flex-row no-wrap space-between'>
                    <label>Contract day</label>
                    <input type='text' id='contractDay' class='form-control' />
                </div>
                <div class='flex-row no-wrap space-between'>
                    <label>Date</label>
                    <input type='text' value='${curDate}' id='curDate' class='form-control' readonly />
                </div>
                <div class='flex-row no-wrap space-between'>
                    <label>Project</label>
                    <input type='text' id='projectId' class='form-control' />
                </div>
                <div class='flex-row no-wrap space-between'>
                    <label>Weather</label>
                    <input type='text' id='weatherDescrip' class='form-control' />
                </div>
                <div class='flex-row no-wrap space-between'>
                    <label>Shift Hrs</label>
                    <input type='text' id='shiftHrs' class='form-control' />
                </div>
            </fieldset>
            <fieldset id='safetyData' class='card half-container'>
                <h5 class='form-section-heading grey-bg'>Safety</h5>
                <div class='flex-row no-wrap space-between'>
                    <label>EIC</label>
                    <input type='text' id='eic' class='form-control' />
                </div>
                <div class='flex-row no-wrap space-between'>
                    <label>Watchman</label>
                    <input type='text' id='watchman' class='form-control' />
                </div>
                <div class='flex-row no-wrap space-between'>
                    <label>RAP #</label>
                    <input type='text' id='rapNum' class='form-control' />
                </div>
            </fieldset>
        </div>
    </form>
</main>
<?php
include('fileend.php');
?>
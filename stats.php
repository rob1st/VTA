<?php
include('SQLFunctions.php');
session_start();

    $title = "SVBX - Home";
    $link = f_sqlConnect();
    //$table = pages;
    
    $System = "SELECT S.System, COUNT(C.GroupToResolve) FROM CDL C LEFT JOIN System S ON C.GroupToResolve=S.SystemID GROUP BY System ORDER BY S.System"; //Count Actions by System
    $Sev = "SELECT S.SeverityName, COUNT(C.Severity) FROM CDL C LEFT JOIN Severity S ON C.Severity=S.SeverityID WHERE C.Status=1 GROUP BY Severity ORDER BY S.SeverityName"; 
    $Status = "SELECT S.Status, COUNT(C.Status) FROM CDL C LEFT JOIN Status S ON C.Status=S.StatusID GROUP BY Status ORDER BY StatusID";
    $location = "SELECT L.LocationName, COUNT(C.Location) FROM CDL C LEFT JOIN Location L ON L.LocationID=C.Location GROUP BY Location  ORDER BY L.LocationName";
    $Comp = "SELECT CompName FROM Comp ORDER BY CompName";
    $sqlSys = "SELECT COUNT(*) FROM System"; //Systems Count
    $sqlStat = "SELECT COUNT(*) FROM Status"; //Status Counts
    $sqlSev = "SELECT COUNT(*) FROM Severity"; //Severity Counts
    $sqlLoc = "SELECT COUNT(*) FROM Location"; //Location Counts
    $sqlET = "SELECT COUNT(*) FROM CDL WHERE Status=2"; //Status Closed Counts
        
    // vars to pass to JS scripts
    $statusData = [
      open => '',
      closed => ''
    ];
    $sevData = [
      blocker => '',
      critical => '',
      major => '',
      minor => ''
    ];
    // $statusOpen = 0;
    // $statusClosed = 0;
    // $blockSev = 0;
    // $critSev = 0;
    // $majSev = 0;
    // $minSev = 0;

    //if(!f_tableExists($link, $table, DB_Name)) {
    //    die('<br>Destination table does not exist: '.$table);
    //}
    
    // array of tables to render as cards
    // each item MUST include TableName, PluralString, ItemString, SqlString
    // if any extra content is needed, add it to index after required items
    $cards = [
      ['Status', 'Statuses', 'Items', 
        "<div class='data-display'><div id='status-graph' class='chart-container'></div><p id='status-legend' class='flex-column'></p></div>"],
      ['Severity', 'Severities', 'Open Items', '<div class="data-display"><div id="severity-graph" class="chart-container"></div><p id="severity-legend" class="legend"></p></div>'],
      ['System', 'Systems', 'Actions'],
      ['Location', 'Locations', 'Open Items']
    ];
    $queries = [
      'Status' => 'SELECT S.Status, COUNT(C.Status) FROM CDL C LEFT JOIN Status S ON C.Status=S.StatusID GROUP BY Status ORDER BY StatusID',
      'Severity' => 'SELECT S.SeverityName, COUNT(C.Severity) FROM CDL C LEFT JOIN Severity S ON C.Severity=S.SeverityID WHERE C.Status=1 GROUP BY Severity ORDER BY S.SeverityName',
      'System' => 'SELECT S.System, COUNT(C.Status) FROM CDL C LEFT JOIN System S ON C.GroupToResolve=S.SystemID WHERE Status = 1 GROUP BY System ORDER BY S.System',
      'Location' => 'SELECT L.LocationName, COUNT(C.Status) FROM CDL C LEFT JOIN Location L ON L.LocationID=C.Location WHERE Status = 1 GROUP BY Location  ORDER BY L.LocationName'
    ];
    
    function writeDashCard($count, $result, $card) {
      global $statusData, $sevData;
      // global $statusOpen, $statusClosed, $blockSev, $critSev, $majSev, $minSev;
      // if ($db) $dbConnect = 'connection successful';
      echo "
        <div class='card dash-card'>
          <header class='card-header'>
            <h4>{$card[1]}</h4>
          </header>
          <div class='card-body grey-bg'>
            <ul class='dash-list'>
              <li class='bg-secondary text-white dash-list-heading'>
                <span class='dash-list-left dash-list-name'>{$card[0]}</span>
                <span class='dash-list-right dash-list-count'>{$card[2]}</span>
              </li>";
      if ($count && $result) {
        while ($row = $result->fetch_array()) {
          if ($row[0] === 'Open' || $row[0] === 'Closed') $statusData[lcfirst($row[0])] = $row[1];
          elseif ($row[0] === 'Blocker' || $row[0] === 'Critical' || $row[0] === 'Major' || $row[0] === 'Minor') $sevData[lcfirst($row[0])] = $row[1];
          echo "
              <li class='dash-list-item'>
                <span class='dash-list-left'>{$row[0]}</span>
                <span class='dash-list-right'>{$row[1]}</span>
              </li>
          ";
        }
        echo "</ul>";
        if (count($card) > 3) {
          echo "{$card[3]}</div>";
        } else echo "</div>";
        echo "
            <footer class='card-footer'>
              <a href='Display{$cardSpecs[1]}.php' class='btn btn-lg btn-outline btn-a'>Number of {$cardSpecs[1]} {$tot}</a>
            </footer>
        ";
      } else echo "</ul><p class='empty-qry-msg'>0 items returned from database</p>";
      echo "</div>";
    }

    include('filestart.php'); //Provides all HTML starting code
?> 
<header class="container page-header">
  <h1 class="page-title">Database Information</h1>
</header>
<main role="main" class="container main-content dashboard">
  <?php
  foreach($cards as $card) {
    $tableStr = 'SELECT COUNT(*) FROM '.$card[0];
    $count = $link->query($tableStr)->fetch_array()[0];
    $result = $link->query($queries[$card[0]]);
    writeDashCard($count, $result, $card);
  }
?> 
</main>
<?php
  // replace flat $___Data props with d3-structured objs
  foreach ($statusData as $name => $num) {
    array_push($statusData, [ label => $name, value => $num ]);
    unset($statusData[$name]);
  }
  $jsonStatus = json_encode($statusData);
  $statusColors = json_encode([ red => '#d73027', green => '#58BF73' ]);
  
  foreach ($sevData as $name => $num) {
    array_push($sevData, [ label => $name, value => $num ]);
    unset($sevData[$name]);
  }
  $jsonSev = json_encode($sevData);
  $sevColors = json_encode([ red => '#bd0026', redOrange => '#fc4e2a', orange => '#feb24c', yellow => '#ffeda0']);
  echo "
  <!--THIS IS A TERRIBLE WAY TO DO THIS
      THIS IS ONLY FOR TESTING PURPOSES-->
  <script src='https://d3js.org/d3.v5.js'></script>
  <script src='js/pie_chart.js'></script>
  <script>
    window.drawPieChart(document.getElementById('status-graph'), $jsonStatus, $statusColors)
    window.drawPieChart(document.getElementById('severity-graph'), $jsonSev, $sevColors)
  </script>
  <!--REMOVE ABOVE SCRIPT TAGS ONCE TESTING IS DONE-->
  <!--DO NOT TYPE BELOW THIS LINE-->";
  include('fileend.php');
?>

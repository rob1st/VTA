<?php
include('SQLFunctions.php');
session_start();

    $title = "SVBX - Home";
    $link = f_sqlConnect();
    //$table = pages;
    
    // vars to pass to JS scripts
    $dataCollection = [];

    //if(!f_tableExists($link, $table, DB_Name)) {
    //    die('<br>Destination table does not exist: '.$table);
    //}
    
    // array of tables to render as cards
    // each item MUST include TableName, PluralString, ItemString, SqlString
    // if any extra content is needed, add it to index after required items
    $cards = [
      [
        name => 'status',
        plural => 'statuses',
        itemName => 'items'
      ],
      [
        name => 'severity',
        plural => 'severities',
        itemName => 'open Items'
      ],
      [
        name => 'system',
        plural => 'systems',
        itemName => 'actions'
      ],
      [
        name => 'location',
        plural => 'locations',
        itemName => 'open Items'
      ]
    ];
    $queries = [
      'Status' => 'SELECT S.Status, COUNT(C.Status) FROM CDL C LEFT JOIN Status S ON C.Status=S.StatusID GROUP BY Status ORDER BY StatusID',
      'Severity' => 'SELECT S.SeverityName, COUNT(C.Severity) FROM CDL C LEFT JOIN Severity S ON C.Severity=S.SeverityID WHERE C.Status=1 GROUP BY Severity ORDER BY S.SeverityName',
      'System' => 'SELECT S.System, COUNT(C.Status) FROM CDL C LEFT JOIN System S ON C.GroupToResolve=S.SystemID WHERE Status = 1 GROUP BY System ORDER BY S.System',
      'Location' => 'SELECT L.LocationName, COUNT(C.Status) FROM CDL C LEFT JOIN Location L ON L.LocationID=C.Location WHERE Status = 1 GROUP BY Location  ORDER BY L.LocationName'
    ];
    
    function writeDashCard($count, $result, $card) {
      global $dataCollection;
      echo "
        <div class='card dash-card'>
          <header class='card-header'>
            <h4>".ucfirst($card['plural'])."</h4>
          </header>
          <div class='card-body grey-bg'>
            <ul class='dash-list'>
              <li class='bg-secondary text-white dash-list-heading'>
                <span class='dash-list-left dash-list-name'>".ucfirst($card['name'])."</span>
                <span class='dash-list-right dash-list-count'>".ucfirst($card['itemName'])."</span>
              </li>";
      if ($count && $result) {
        while ($row = $result->fetch_array()) {
          // append row data to obj that will be json encoded
          $dataCollection[$card['name']][] = [ label => lcfirst($row[0]), value => $row[1]];
          echo "
              <li class='dash-list-item'>
                <span class='dash-list-left'>{$row[0]}</span>
                <span class='dash-list-right'>{$row[1]}</span>
              </li>
          ";
        }
        echo "
            </ul>
            <div class='data-display'>
              <div id='{$card['name']}-graph' class='chart-container'></div>
              <p id='status-legend' class='flex-column'></p>
            </div>
          </div>
          <footer class='card-footer'>
            <a href='Display{$card['plural']}.php' class='btn btn-lg btn-outline btn-a'>Number of {$card['plural']} {$count}</a>
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
    $tableName = ucfirst($card['name']);
    $tableStr = 'SELECT COUNT(*) FROM '.ucfirst($tableName);
    $count = $link->query($tableStr)->fetch_array()[0];
    $result = $link->query($queries[$tableName]);
    writeDashCard($count, $result, $card);
  }
?> 
</main>
<?php
  // encode dataCollection arrays as json
  $jsonData = [];
  foreach ($cards as $card) {
    $jsonData[$card['name']] = json_encode($dataCollection[$card['name']]);
  }

  $statusColors = json_encode([ red => '#d73027', green => '#58BF73' ]);
  $sevColors = json_encode([ red => '#bd0026', redOrange => '#fc4e2a', orange => '#feb24c', yellow => '#ffeda0']);
  
  echo "
  <!--THIS IS A TERRIBLE WAY TO DO THIS
      THIS IS ONLY FOR TESTING PURPOSES-->
  <script src='https://d3js.org/d3.v5.js'></script>
  <script src='js/pie_chart.js'></script>
  <script>
    window.drawPieChart(document.getElementById('status-graph'), {$jsonData['status']}, $statusColors)
    window.drawPieChart(document.getElementById('severity-graph'), {$jsonData['severity']}, $sevColors)
  </script>
  <!--REMOVE ABOVE SCRIPT TAGS ONCE TESTING IS DONE-->
  <!--DO NOT TYPE BELOW THIS LINE-->";
  include('fileend.php');
?>

<?PHP
    include('SQLFunctions.php');
    session_start();
    $link = f_sqlConnect();
    $date = date('Y-m-d');
    $nullVal = null;
    $sql = file_get_contents("recDef.sql");
    $post = $_POST;
    unset($post['CDL_pics']);

    // checks for redundant POSTs
    // $check = "SELECT * FROM $table WHERE Description='{$_POST['Description']}' AND Location={$_POST['Location']}";

    $userID = $_SESSION['UserID'];
    
    echo "<p style='font-family: monospace'>SQL string: $sql</p>";
    echo "<pre style='color: blue'>";
    var_dump($post);
    echo "</pre>";
    
    if ($stmt = $link->prepare($sql)) {
      $types = 'iiisiisiiisisssisiisssss';
      if ($stmt->bind_param($types,
        $post['SafetyCert'],
        $post['SystemAffected'],
        $post['Location'],
        $link->escape_string($post['SpecLoc']),
        $post['Status'],
        $post['Severity'],
        $link->escape_string($post['DueDate']),
        $post['GroupToResolve'],
        $post['RequiredBy'],
        $post['contractID'],
        $link->escape_string($post['IdentifiedBy']),
        $post['defType'],
        $link->escape_string($post['Description']),
        $link->escape_string($post['Spec']),
        $link->escape_string($post['ActionOwner']),
        $post['OldID'],
        $link->escape_string($post['comments']),
        $post['EvidenceType'],
        $post['Repo'],
        $link->escape_string($post['EvidenceLink']),
        $link->escape_string($post['ClosureComments']),
        $link->escape_string($post['username']),
        $date,
        $nullVal
      )) {
        echo "<p style='color: lightSeaGreen; font-family: monospace'>{$stmt->param_count}</p>";
        if ($stmt->execute()) {
          echo "<p style='color: tomato; font-family: cursive'>INSERT ID: ";
          echo $stmt->insert_id;
          echo ", AFFECTED ROWS: ";
          echo $stmt->affected_rows;
          echo "</p>";
        } elseif ($stmt->error) {
          echo "<pre style='color: mediumSlateBlue; font-size: 1.2rem'>STATEMENT ERROR: ";
          echo $stmt->error;
          echo "</pre>";
        } else {
          echo "<pre style='color: goldenRod; font-size: 1.2rem'>LINK ERROR from execute: ";
          echo $link->error;
          echo "</pre>";
        }
        // echo "<h4>did it execute? what was the result? who knows?</h4>";
      } else {
        echo "<pre style='color: limeGreen'>";
        echo $link->error;
        echo "</pre>";
        $link->close();
        exit;
      }
    } else {
      echo "<pre style='color: fuchsia'>{$link->error}</pre>";
      $link->close();
      exit;
    }
    
    // echo "<h3 style='color: darkRed; font-family: sans-serif'>What happened? who can tell?</h3>";
    
    /*
    
    // this function is currently broken
    // I am avoiding fixing it because I do not at this moment have time to deal with the repurcussions of suddenly turning on a previously broken but widely used fcn
    if(!f_tableExists($link, $table, DB_Name)) {
        die('<br>Destination table does not exist:'.$table);
    }
    
    $result = mysqli_query($link,$check);
    $num_rows = mysqli_num_rows($result);

    if ($num_rows > 0) {
      header("location: $duplicate?msg=1");
    }
    else {
    // this here is the part that does the work of storing new vals in db
    $sql = "INSERT INTO $table($keys, DateCreated, Created_by) VALUES ('$values', CURDATE(), '$Username')";
    echo '<br>sql: ' .$sql;
    //echo '<br>Num_rows: ' .$num_rows;
    
    if(!mysqli_query($link,$sql)) {
		echo '<br>Error: ' .mysqli_error($link);
		// what does this do(?) isn't this var hard-coded in SQLFcns(?)
		if(!empty($rejectredirecturl)) {
	    	//header("location: $rejectredirecturl?msg=1");
	    	echo $sql;
    }    
	  // isn't this redundant with above code(?)
    }else if(!empty ($rejectredirecturl)) {
            header("location: DisplayDefs.php");
            //echo "Success";
    }
}*/
  $stmt->close();
	$link->close();
?>
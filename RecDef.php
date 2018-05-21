<?PHP
    include('SQLFunctions.php');
    session_start();
    $link = f_sqlConnect();
    $table = CDL;
    $sql = file_get_contents("recDef.sql");
    $post = $_POST;
    unset($post['CDL_pics']);

    // checks for redundant POSTs
    // $check = "SELECT * FROM $table WHERE Description='{$_POST['Description']}' AND Location={$_POST['Location']}";

    $userID = $_SESSION['UserID'];
    
    echo '<p>Parsed keys: '.$sql.'</p>';
    
    if ($stmt = $link->prepare($sql)) {
      $types = 'iiisiisiiisisssisiississ';
      echo "<pre>";
      var_dump($post);
      echo "</pre>";
      $values = "'".implode("', '", (array_values($post)))."'";
      $values .= ", '$userID', CURDATE()";
      $dateClosed = $post['DateClosed'] ? ", {$post['DateClosed']}" : ", null";
      $values .= $dateClosed;
        $valsArr = explode(', ', $values);
      echo "<pre>type string: ".strlen($types).", $types</pre>";
      echo "<pre>Parsed Values:".count($valsArr).": $values</pre>";
      echo "<pre>";
      var_dump($valsArr);
      echo "</pre>";
      if ($stmt->bind_param($types, $values)) {
        echo "<span>{$stmt->field_count} {$stmt->param_count}</span>";
      } else {
        echo $link->error;
        $link->close();
        exit;
      }
    } else {
      echo $link->error;
      $link->close();
      exit;
    }
    
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
    
	$link->close();
?>
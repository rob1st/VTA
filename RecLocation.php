<?PHP
    include('SQLFunctions.php');
    session_Start();
    $table = 'location';

    echo '<br>display full contents of the _POST: <br>';
    var_dump($_POST);
    
    $link = f_sqlConnect();
    $check = "SELECT * FROM $table WHERE LocationName = '".$_POST['LocationName']."'";
    $UserID = $_SESSION['userID'];

    $keys = implode(", ", (array_keys($_POST)));
    echo '<br>Parsed Key: ' .$keys;
    $values = implode("', '", (array_values($_POST)));
    echo '<br>Parsed Values: ' .$values;
    
    if(!f_tableExists($link, $table, DB_NAME)) {
        die('<br>Destination table does not exist:'.$table);
    }
    
    $result = mysqli_query($link,$check);
    $num_rows = mysqli_num_rows($result);

    if ($num_rows > 0) {
      header("location: $duplicate?msg=1");
    }
    else {
    $sql = "INSERT INTO $table($keys, lastUpdated, updatedBy) VALUES ('$values', NOW(), '$UserID')";
    
    if (!mysqli_query($link,$sql)) {
		echo '<br>Error: ' .mysqli_error($link);
		if(!empty($rejectredirecturl)) {
	    	echo $sql;
    }    
	
    }else if(!empty ($rejectredirecturl)) {
            header("location: DisplayLocations.php");
    }
}
    
	mysqli_close($link);
?>
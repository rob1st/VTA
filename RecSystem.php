<?PHP
    include('SQLFunctions.php');
    session_start();
    $table = 'system';

    echo '<br>display full contents of the _POST: <br>';
    var_dump($_POST);
    
    $link = f_sqlConnect();
    $UserID = $_SESSION['userID'];
    $Username = $_SESSION['username'];
    
    $keys = implode(", ", (array_keys($_POST)));
    echo '<br>Parsed Key: ' .$keys;
    $values = implode("', '", (array_values($_POST)));
    echo '<br>Parsed Values: ' .$values;
    
    $sql = "INSERT INTO $table($keys, lastUpdated, updatedBy) VALUES ('$values', NOW(), '$UserID')";
    
    if (!mysqli_query($link,$sql)) {
		echo '<br>Error: ' .mysqli_error($link);
		if(!empty($rejectredirecturl)) {
	    	echo $sql;
        }
    } else {
        header("Location: DisplaySystems.php");
    }
    
	mysqli_close($link);
?>
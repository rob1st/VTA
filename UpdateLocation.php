<?php 
    include('session.php');
    include('SQLFunctions.php');
    $link = f_sqlConnect();
    $table = Location;
    $q = $_POST["q"];
    $title = "SVBX - Update Location";
    $Loc = "SELECT LocationName FROM $table WHERE LocationID = ".$q;
    include('filestart.php');
    
    if($Role == 'U' OR $Role == 'V') {
        header('location: unauthorised.php');
    }
?>
    <div class="jumbotron">
        <div class="container">
            <h1 class="display-3">Update Location</h1>
        </div>
    </div>
        <?php       
            if($stmt = $link->prepare($Loc)) {
                $stmt->execute();
                $stmt->bind_result($Location);
                while ($stmt->fetch()) {
                    echo "
                        <div class='container'> 
                            <FORM action='UpdateLocationCommit.php' method='POST'>
                                <input type='hidden' name='LocationID' value='".$q."'>
                                <table class='table'>
                                    <tr class='usertr'>
                                        <th class='userth'>Evidence Type Name:</th>
                                        <td class='usertd'>
                                            <input type='text' name='LocationName' maxlength='50' required value='".$Location."'/>
                                        </td>
                                    </tr>
                                </table>
                                <br />
                                <input type='submit' value='submit' class='btn btn-primary btn-lg' style='margin-left:40%' />
                                <input type='reset' value='reset' class='btn btn-primary btn-lg' />
                            </FORM>
                        </div>";
                }
            } else {
                echo '<br>Unable to connect';
                exit();
            }
        include('fileend.php') ?>

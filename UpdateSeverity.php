<?php 
    include('session.php');
    include('SQLFunctions.php');
    $link = f_sqlConnect();
    $table = Severity;
    $q = $_POST["q"];
    $title = "SVBX - Update Severity";
    $Loc = "SELECT SeverityName, Description FROM $table WHERE SeverityID = ".$q;
    include('filestart.php');
    
    if($Role == 'U' OR $Role == 'V' OR $Role == 'A') {
        header('location: unauthorised.php');
    }
?>
    <div class="jumbotron">
        <div class="container">
            <h1 class="display-3">Update Severity</h1>
        </div>
    </div>
<?php       if($stmt = $link->prepare($Loc)) {
            $stmt->execute();
            $stmt->bind_result($SeverityName, $Description);
            while ($stmt->fetch()) {
                echo "
                    <div class='container'> 
                        <FORM action='UpdateSeverityCommit.php' method='POST'>
                            <input type='hidden' name='SeverityID' value='".$q."'>
                            <table class='table'>
                                <tr class='usertr'>
                                    <th class='userth'>Severity Name:</th>
                                    <td class='usertd'>
                                        <input type='text' name='SeverityName' maxlength='50' required value='".$SeverityName."'/>
                                    </td>
                                </tr>
                                <tr class='usertr'>
                                    <th coldpan='2' class='userth'>Severity Description:</th>
                                    <td class='usertd'>
                                        <textarea type='message'  rows='5' cols='99%' name='Description' max='255' required>$Description</textarea>
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
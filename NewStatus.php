<?php
include('session.php');
$title = "SVBX - Add New Status Type";
include('filestart.php');
    if($Role == 'U' OR $Role == 'V' OR $Role == 'A') {
        header('location: unauthorised.php');
    }
    ?>
        <div class="jumbotron">
        <div class="container">
          <h1 class="display-3">Add New Evidence type</h1>
        </div>
    </div>
        <div class="container"> 
        <FORM action="RecStatus.php" method="POST">
            <table class='table'>
                <tr class='usertr'>
                    <th class='userth'>Evidence Type Name:</th>
                    <td class='usertd'>
                        <input type="text" name="Status" maxlength="50" required/>
                    </td>
                </tr>
            </table>
            <br />
            <input type='submit' value='submit' class='btn btn-primary btn-lg' style='margin-left:40%' />
            <input type='reset' value='reset' class='btn btn-primary btn-lg' />
        </FORM>
        </div>
<?php include('fileend.php') ?>
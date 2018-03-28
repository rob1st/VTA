<?php
include('session.php');
$Role = $_SESSION['Role'];
$title = "SVBX - Deficiency No".$DefID;
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('filestart.php'); 
$link = f_sqlConnect();
$SafetyCert = file_get_contents("SafetyCert.sql");
?>
    <table border='1' style='width:96%;margin-left:auto;margin-right:auto;margin-top:100px'>
        <tr>
            <th class='vdth' colspan='8'>Search</td>
        </tr>
        <tr>
            <th class='vdth' style='width:5%'>Item</td>
            <th class='vdth' style='width:26%'>Safety/Security<br />Requirement</td>
            <th class='vdth' style='width:15%'>Design<br />Codes/Standards</td>
            <th class='vdth' style='width:15%'>Design Specifications/Criteria</td>
            <th class='vdth' style='width:10%'>Contract No.</td>
            <th class='vdth' style='width:10%'>Control No.</td>
            <th class='vdth' style='width:10%'>Element Group</td>
            <th class='vdth' style='width:10%'>Certifiable Element</td>
                    
        </tr>
        <tr>
            <form>
                    <td><?php
                            $sqlI = "SELECT Item FROM SafetyCert ORDER BY Item";
                             //if($result = mysqli_query($link,$sqlL)) {
                                    echo "<select name='Item'></option>";
                                    echo "<option value=''></option>";
                                    foreach(mysqli_query($link,$sqlI) as $row) {
                                        echo "<option value=$row[Item]>$row[Item]</option>";
                             }
                            echo "</select>";
                        ?></td>
                    <td><input type="text" name="Requirement" max="55" style="width:100%" /></td>
                    <td><input type="text" name="DesignCode" max="55" style="width:100%" /></td>
                    <td><input type="text" name="DesignSpec" max="55" style="width:100%" /></td>
                    <td><?php
                            $sqlC = "SELECT Contract FROM Contract ORDER BY Contract";
                             //if($result = mysqli_query($link,$sqlL)) {
                                    echo "<select name='Item' style='width:100%'></option>";
                                    echo "<option value=''></option>";
                                    foreach(mysqli_query($link,$sqlC) as $row) {
                                        echo "<option value=$row[Contract]>$row[Contract]</option>";
                             }
                            echo "</select>";
                        ?></td>
                    <td><input type="text" name="ControlNo" max="3" style="width:100%" /></td>
                    <td><?php
                            $sqlE = "SELECT ElementGroup FROM ElementGroup ORDER BY ElementGroup";
                             //if($result = mysqli_query($link,$sqlL)) {
                                    echo "<select name='Item' style='width:100%'></option>";
                                    echo "<option value=''></option>";
                                    foreach(mysqli_query($link,$sqlE) as $row) {
                                        echo "<option value=$row[ElementGroup]>$row[ElementGroup]</option>";
                             }
                            echo "</select>";
                        ?></td>
                    <td><?php
                            $sqlCE = "SELECT CertifiableElement FROM CertifiableElement ORDER BY CertifiableElement";
                             //if($result = mysqli_query($link,$sqlL)) {
                                    echo "<select name='Item' style='width:100%'></option>";
                                    echo "<option value=''></option>";
                                    foreach(mysqli_query($link,$sqlCE) as $row) {
                                        echo "<option value=$row[CertifiableElement]>$row[CertifiableElement]</option>";
                             }
                            echo "</select>";
                        ?></td>
            </form>
        </tr>
    </table>

<?php
    if($result = mysqli_query($link,$SafetyCert)) {
    echo "<table border='1' style='width:96%;margin-left:auto;margin-right:auto;margin-top:100px'>
                <tr>
                    <th class='vdth' colspan='8' height='50'><p>Certification Stage</p></th>
                </tr>
                <tr>
                    <th class='vdth' style='width:5%'>Item</td>
                    <th class='vdth' style='width:26%'>Safety/Security<br />Requirement</td>
                    <th class='vdth' style='width:15%'>Design<br />Codes/Standards</td>
                    <th class='vdth' style='width:15%'>Design Specifications/Criteria</td>
                    <th class='vdth' style='width:10%'>Contract No.</td>
                    <th class='vdth' style='width:10%'>Control No.</td>
                    <th class='vdth' style='width:10%'>Element Group</td>
                    <th class='vdth' style='width:10%'>Certifiable Element</td>
                    
                </tr>";
                while($row = mysqli_fetch_array($result)) { 
                    echo "
                <tr>
                    <td style='vertical-align:Top;text-align:left'>{$row[1]}</td>
                    <td style='vertical-align:Top;text-align:left'>{$row[2]}</td>
                    <td style='vertical-align:Top;text-align:left'>{$row[3]}</td>
                    <td style='vertical-align:Top;text-align:left'>{$row[4]}</td>
                    <td style='vertical-align:Top;text-align:left'>{$row[5]}</td>
                    <td style='vertical-align:Top;text-align:left'>{$row[6]}</td>
                    <td style='vertical-align:Top;text-align:left'>{$row[7]}</td>
                    <td style='vertical-align:Top;text-align:left'>{$row[8]}</td>
                </tr>";
                    }  
                echo "</table><br>";
                } else {  
                    echo "
                    <div='container'>
                    <br />
                    <br />
                    <br />
                    <br>Unable to connect<br>
                    </div>";
                    echo $SafetyCert.'<br /><br />';
                    //echo mysqli_error();
                    //echo "<BR>Def ID: ".$DefID;
                  exit();  
                } 
    include('fileend.php');
    MySqli_Close($link); 
?>
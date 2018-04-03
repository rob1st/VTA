<?php 
include('session.php');
include('SQLFunctions.php');
$title = "View Deleted Deficiencies";
$link = f_sqlConnect();
$CDL = file_get_contents("CDListClosed.sql");
$Role = $_SESSION['Role'];
include('filestart.php');
?>
    
    <header class="container page-header">
          <h1 class="page-title">Deficiencies</h1>
    </header>
    <div class="container">
<?php     
    if($result = mysqli_query($link,$CDL)) {
        echo "
            <main class='container main-content'>
                <div class='card card-body grey-bg def-table-heading'>
                    <p>Click Deficiency ID Number to see full details</p>";
                    if ($Role == 'U' OR $Role == 'A' OR $Role == 'S') {
                        echo "<a href='NewDef.php' class='btn btn-primary'>Add New Deficiency</a>";
                    }
        echo "
                </div>
                <ul class='def-nav'>
                    <li class='def-nav-item'><a href='DisplayDefs.php' class='btn btn-sm btn-outline def-nav-btn'>All</a></li>
                    <li class='def-nav-item'><a href='DisplayOpenDefs.php' class='btn btn-sm btn-outline def-nav-btn'>Open</a></li>
                    <li class='def-nav-item'><a href='DisplayClosedDefs.php' class='btn btn-sm btn-outline def-nav-btn'>Closed</a></li>
                </ul>

            <table class='table svbx-table' border='1'>
                <tr class='svbx-tr'>
                    <th class='svbx-th'>Def ID</th>
                    <th class='collapse-sm  collapse-xs svbx-th'>Location</th>
                    <th class='collapse-xs svbx-th'>Severity</th>
                    <th class='collapse-md  collapse-sm collapse-xs svbx-th'>Date Created</th>
                    <th class='svbx-th'>Status</th>
                    <th class='collapse-sm collapse-xs svbx-th'>System Affected</th>
                    <th class='svbx-th'>Brief Description</th>";
                    if($Role == 'S' OR $Role == 'A' OR $Role == 'U') 
                    {
                        echo "
                            <th class='collapse-md  collapse-sm collapse-xs svbx-th'>Last Updated</th>
                            <th class='svbx-th'>Edit</th>";
                    } else {
                        echo "
                            </tr>";
                            }
                            if($Role == 'S')
                            {
                            echo "
                                <th class='svbx-th'>Delete</th>
                            </tr>"; 
                    }
            while($row = mysqli_fetch_array($result)) {
                echo "  <tr class='svbx-tr'>
                        <td class='svbx-td' style='text-align:center'><a href='ViewDef.php?DefID={$row[0]}'>{$row[0]}</a></td>
                        <td class='collapse-sm  collapse-xs svbx-td'>{$row[1]}</td>
                        <td class='collapse-xs svbx-td' style='text-align:center'>{$row[2]}</td>
                        <td class='collapse-md  collapse-sm collapse-xs svbx-td' style='text-align:center'>{$row[3]}</td>
                        <td class='svbx-td' style='text-align:center'>{$row[4]}</td>
                        <td class='collapse-sm collapse-xs svbx-td'>{$row[5]}</td>
                        <td class='svbx-td'>"; echo nl2br($row[6]);
                        echo "</td>";
                        if($Role == 'S' OR $Role == 'A' OR $Role == 'U') 
                        {
                            echo "
                            <td class='collapse-md  collapse-sm collapse-xs svbx-td'>{$row[7]}</td>
                            <td class='svbx-td' style='text-align:center'><form action='UpdateDef.php' method='POST' onsubmit=''/>
                            <input type='hidden' name='q' value='".$row[0]."'/><input type='submit' value='Update'></form></td>";
                        } else {
                        echo "</tr>";
                            }
                            if($Role == 'S')
                            {
                            echo "
                            <td class='svbx-td' style='text-align:center'><form action='DeleteDef.php' method='POST' onsubmit='' onclick='return confirm(`ARE YOU SURE? Deficiencies should not be deleted, your deletion will be logged.`)'/>
                            <button type='Submit' name='q' value='".$row[0]."'><i class='typcn typcn-times'></i></button></form></td>
                            </tr>";
                        }
            }
                echo "
                </table>
                </div>";
    }
//mysqli_free_result($result);

    if(mysqli_error( $link )) {
        echo '<br>Error: ' .mysqli_error($link);
    }
                    
mysqli_close($link);
    
include 'fileend.php';
?>
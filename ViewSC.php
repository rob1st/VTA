<?php
include('session.php');
$Role = $_SESSION['Role'];
$title = "SVBX - Safety Certifications".$DefID;
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
include('filestart.php'); 
$link = f_sqlConnect();
$AND = 0;

if($_POST['Search'] == NULL) {
    $sql = file_get_contents("SafetyCert.sql");
    $count = "SELECT COUNT(*) FROM SafetyCert A";
} else {
    
    $ItemS = $_POST['Item'];
    $RequirementS = $_POST['Requirement'];
    $DesignCodeS = $_POST['DesignCode'];
    $DesignSpecS = $_POST['DesignSpec'];
    $ContractNoS = $_POST['ContractNo'];
    $ControlNoS = $_POST['ControlNo'];
    $ElementGroupS = $_POST['ElementGroup'];
    $CertElementS = $_POST['CertElement'];
    
    $sql = "SELECT 
                A.CertID,
                A.Item, 
                A.Requirement, 
                A.DesignCode, 
                A.DesignSpec, 
                B.Contract, 
                A.ControlNo, 
                C.ElementGroup, 
                D.CertifiableElement 
            FROM 
                SafetyCert A 
            LEFT JOIN 
                Contract B 
            ON 
                B.ContractID = A.ContractNo
            LEFT JOIN 
                ElementGroup C 
            ON 
                C.EG_ID = A.ElementGroup
            LEFT JOIN 
                CertifiableElement D 
            ON 
                D.CE_ID = A.CertElement";
    $count = "SELECT COUNT(*) FROM SafetyCert A";
    
    if($ItemS <> NULL) {
       $ItemSQL = " WHERE A.Item = '".$ItemS."'";
       $AND = 1;
    } else {
        $ItemSQL = "";
    }
    if($RequirementS <> NULL) {
        if($AND == 1) {
           $ReqSQL = " AND A.Requirement LIKE '%".$RequirementS."%'"; //LIKE '%123456%'
       } else {
       $ReqSQL = " WHERE A.Requirement LIKE '%".$RequirementS."%'";
       $AND = 1;
       }
    } else {
        $ReqSQL = "";
    }
    if($DesignCodeS <> NULL) {
        if($AND == 1) {
           $DesignCodeSQL = " AND A.DesignCode LIKE '%".$DesignCodeS."%'";
        } else {
           $DesignCodeSQL = " WHERE A.DesignCode LIKE '%".$DesignCodeS."%'";
           $AND = 1;
       }
    } else {
        $DesignCodeSQL = "";
    }
    if($DesignSpecS <> NULL) {
        if($AND == 1) {
            $DesignSpecSQL = " AND A.DesignSpec LIKE '%".$DesignSpecS."%'";
        } else {
            $DesignSpecSQL = " WHERE A.DesignSpec LIKE '%".$DesignSpecS."%'";
            $AND = 1;
        }
    } else {
        $DesignSpecSQL = "";
    }
    if($ContractNoS <> NULL) {
       if($AND == 1) {
            $ContractNoSQL = " AND A.ContractNo = '".$ContractNoS."'";
        } else {
            $ContractNoSQL = " WHERE A.ContractNo = '".$ContractNoS."'";
            $AND = 1;
        }
    } else {
        $ContractNoSQL = "";
    }
    if($ControlNoS <> 0) {
       if($AND == 1) {
            $ControlNoSQL = " AND A.ControlNo = '".$ControlNoS."'";
        } else {
            $ControlNoSQL = " WHERE A.ControlNo = '".$ControlNoS."'";
            $AND = 1;
        }
    } else {
        $ControlNoSQL = "";
    }
    if($ElementGroupS <> 0) {
       if($AND == 1) {
            $ElementGroupSQL = " AND A.ElementGroup = '".$ElementGroupS."'";
        } else {
            $ElementGroupSQL = " WHERE A.ElementGroup = '".$ElementGroupS."'";
            $AND = 1;
        }
    } else {
        $ElementGroupSQL = "";
    }
    if($CertElementS <> 0) {
       if($AND == 1) {
            $CertElementSQL = " AND A.CertElement = '".$CertElementS."'";
        } else {
            $CertElementSQL = " WHERE A.CertElement = '".$CertElementS."'";
            $AND = 1;
        }
    } else {
        $CertElementSQL = "";
    }
    $sql = $sql.$ItemSQL.$ReqSQL.$DesignCodeSQL.$DesignSpecSQL.$ContractNoSQL.$ControlNoSQL.$ElementGroupSQL.$CertElementSQL;
    $count = $count.$ItemSQL.$ReqSQL.$DesignCodeSQL.$DesignSpecSQL.$ContractNoSQL.$ControlNoSQL.$ElementGroupSQL.$CertElementSQL;
    
}



?>
<main class="container main-content">
    <header class="container page-header">
        <form action="ViewSC.php" method="POST" class="form-inline">
            <legend class=''>Search</legend>
            <input type='hidden' name='Search' value=1>
            <?php
                $sqlI = "SELECT Item FROM SafetyCert ORDER BY Item";
                 //if($result = mysqli_query($link,$sqlL)) {
                echo "
                    <label>Item</label>
                    <select name='Item' value='".$ItemS."' class='form-control'>";
                echo "<option value=''></option>";
                foreach(mysqli_query($link,$sqlI) as $row) {
                    echo "<option value='$row[Item]'";
                    if($row['Item'] == $ItemS) echo " selected>$row[Item]</option>";
                    else echo ">$row[Item]</option>";
                }
                echo "</select>";
            ?>
            <label>Safety/Security Requirement</label>
            <input type="text" name="Requirement" maxlength="55" value="<?php echo $RequirementS ?>" class='form-control'/>
            <label>Design Code/Standard</label>
            <input type="text" name="DesignCode" maxlength="55" value="<?php echo $DesignCodeS ?>" class='form-control'/>
            <label>Design Spec/Criteria</label>
            <input type="text" name="DesignSpec" maxlength="55" value="<?php echo $DesignSpecS ?>" class='form-control'/>
            <lable>Contract No.</lable>
            <?php
                $sqlC = "SELECT ContractID, Contract FROM Contract ORDER BY Contract";
                 //if($result = mysqli_query($link,$sqlL)) {
                echo "<select name='ContractNo' value='".$ContractNoS."'>";
                echo "<option value=''></option>";
                foreach(mysqli_query($link,$sqlC) as $row) {
                    echo "<option value='$row[ContractID]'";
                    if($row['ContractID'] == $ContractNoS) echo " selected>$row[Contract]</option>";
                    else echo ">$row[Contract]</option>";
                }
                echo "</select>";
            ?>
            <label>Control No.</label>
            <?php
                $sqlCN = "SELECT DISTINCT ControlNo FROM SafetyCert ORDER BY ControlNo";
                 //if($result = mysqli_query($link,$sqlL)) {
                echo "<select name='ControlNo'' value='".$ControlNoS."' class='form-control'>";
                echo "<option value=''></option>";
                foreach(mysqli_query($link,$sqlCN) as $row) {
                    echo "<option value='$row[ControlNo]'";
                    if($row['ControlNo'] == $ControlNoS) echo " selected>$row[ControlNo]</option>";
                    else echo ">$row[ControlNo]</option>";
                }
                echo "</select>";
            ?>
            <label>Element Group</label>
            <?php
                $sqlE = "SELECT EG_ID, ElementGroup FROM ElementGroup ORDER BY ElementGroup";
                 //if($result = mysqli_query($link,$sqlL)) {
                echo "<select name='ElementGroup' style='width:100%' value='".$ElementGroupS."' class='form-control'>";
                echo "<option value=''></option>";
                foreach(mysqli_query($link,$sqlE) as $row) {
                    echo "<option value='$row[EG_ID]'";
                    if($row['EG_ID'] == $ElementGroupS) echo " selected>$row[ElementGroup]</option>";
                    else echo ">$row[ElementGroup]</option>";
                }
                echo "</select>";
            ?>
            <label>Certifiable Element</label>
            <?php
                $sqlCE = "SELECT CE_ID, CertifiableElement FROM CertifiableElement ORDER BY CertifiableElement";
                 //if($result = mysqli_query($link,$sqlL)) {
                echo "<select name='CertElement' style='width:100%' value='".$CertElementS."' class='form-control'>";
                echo "<option value=''></option>";
                foreach(mysqli_query($link,$sqlCE) as $row) {
                    echo "<option value='$row[CE_ID]'";
                    if($row['CE_ID'] == $CertElementS) echo " selected>$row[CertifiableElement]</option>";
                    else echo ">$row[CertifiableElement]</option>";
                }
                echo "</select>";
            ?>
            <button type='submit' value='Submit' class='btn btn-primary btn-lg'>Submit</button>
        </form>
        <form action="ViewSC.php">
            <input type='submit' value='Reset' class='btn btn-primary btn-lg'/>
        </form>
    </header>
    
<?php 
    if($result = mysqli_query($link,$count)) {
        echo "
            <div class='col-4 offset-4'>
                <div class='card sum-card'>
                    <div class='card-body center-content'>
                        <span>Safety Requirements Found: </span>";
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<span>{$row[0]}<span>";
                        }
        echo "</div></div></div>";
    }
?>
<?php
    if($result = mysqli_query($link,$sql)) {
    echo "
        <table class='table table-striped table-responsive svbx-table sc-table'>
            <thead>
                <tr>
                    <th class='svbx-th svbx-table-title' colspan='8'>Certification Stage</th>
                </tr>
                <tr>
                    <th class='svbx-th'>Item</td>
                    <th class='svbx-th collapse-xs'>Safety/Security Requirement</td>
                    <th class='svbx-th'>Design Codes/Standards</td>
                    <th class='svbx-th'>Design Specifications/Criteria</td>
                    <th class='svbx-th collapse-xs'>Contract No.</td>
                    <th class='svbx-th collapse-xs'>Control No.</td>
                    <th class='svbx-th collapse-xs'>Element Group</td>
                    <th class='svbx-th collapse-xs'>Certifiable Element</td>
                    
                </tr>
            </head>
            <tbody>";
                while($row = mysqli_fetch_array($result)) { 
                    echo "
                <tr>
                    <td class='svbx-td'>{$row[1]}</td>
                    <td class='svbx-td collapse-xs'>{$row[2]}</td>
                    <td class='svbx-td'>{$row[3]}</td>
                    <td class='svbx-td'>{$row[4]}</td>
                    <td class='svbx-td collapse-xs'>{$row[5]}</td>
                    <td class='svbx-td collapse-xs'>{$row[6]}</td>
                    <td class='svbx-td collapse-xs'>{$row[7]}</td>
                    <td class='svbx-td collapse-xs'>{$row[8]}</td>
                </tr>";
                    }  
    echo "</tbody></table></main>";
    } else {  
        echo "
        <div='container'>
        <p>Unable to connect</p>
        </div>";
        echo $sql."</main>";
        //echo mysqli_error();
        //echo "<BR>Def ID: ".$DefID;
      exit();  
    } 
    include('fileend.php');
    MySqli_Close($link); 
?>
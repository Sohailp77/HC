<?php
session_start(); // Ensure session is started
$userCode = $_SESSION['userCode'];
include("../../cisfunctions.php");
// include("../../session_handle.php");
include_once('../../../../swecourtishc/includes/sessions.php');

include "./connect.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from_x = $_POST['from_x'];
    $to_x=$_POST['to_x'];
}

// Report only fatal errors
error_reporting(E_ERROR);

// Enable error display
ini_set('display_errors', 1);
?>

<script type="text/javascript" src="../../js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="../../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../../js/jquery.dump.js"></script>
<script type="text/javascript" src="../../cisjsfunctions.js"></script>
<script type="text/javascript" src="../../validation.js"></script>
<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
<script language="javascript" type="text/javascript">

function clearForm() {
    document.getElementById('to_x').value = "";
    document.getElementById('from_x').value = "";
    document.getElementById('adv_id').value = '0';

}


</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Advocate Report</title>
    <?php $userCode;?>
    <style>
        .error-message {
            color: red;
            font-size: 18px;
            
        }
    </style>
</head>
<body>
<div class="container-fluid" style="width:60%">
    <form name="infofrm" id="myForm" method="post" action="Adv_report2.php">
        <div class="panel panel-primary">
            <div class="panel-heading" style="text-align:center"><b>Advocate Report </b></div>
            <div class="panel-body">
                <div id="viewerror" style="display:none;color:red;size:+2" align='center'></div><br>

                <!-- table started -->
                <table border="0" class="table table-bordered">
                    <tr>
                       <td>
                        <select name="adv_id" id="adv_id" class="form-control">
                            <option value="0">Select</option>
                             <?php
                            $sql= "SELECT * FROM periphery.adv_govt_det WHERE display='Y'";
                            $sql = $pgconn->prepare($sql); //connecting to database
                            $pgconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //pdo of connection
                            $sql->execute(); //to execute the query
                            $rows = $sql->fetchAll();
                            foreach($rows as $row){  
                            ?>
                            <option value=<?php echo $row['advid']?>><?php echo $row['advname'] ." "."(".$row['advtype'].")"?></option>
                            <?php }?>
                        </select>
                       </td>
                        <td>
                            <label>From</label>
                            <input type="date" name="from_x" id="from_x" pattern="\d{4}-\d{2}-\d{2}" value="<?php echo $from_x; ?>" class="form-control" <?php if($from_x != '') echo 'disabled'; ?> Required >
                        </td>
                        <td>
                            <label>To</label>
                            <input type="date" name="to_x" id="to_x" pattern="\d{4}-\d{2}-\d{2}" value="<?php echo $to_x; ?>" class="form-control" <?php if($to_x != '') echo 'disabled'; ?> Required>
                        </td>
                        <td>
                            <button class="btn btn-primary" id="submitButton" type="submit" role="button">Go</button>
                            <button class="btn btn-primary" type="button" role="button" onclick="clearForm()">Clear</button>
                         
                        </td>
                    </tr>
                </table>
                </form>
    </body>
    </html>
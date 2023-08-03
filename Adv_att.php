<?php
include "./connect.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$cino=$_POST['Cino'];
$Petn=$_POST['Petn'];
$Resp=$_POST['Resp'];
$Petn_Adv=$_POST['Petn_Adv'];
$Resp_Adv=$_POST['Resp_Adv'];
$Case_Stage=$_POST['Case_stage'];         
$conn= $_POST['arr_cino'];
$Adv_govt=$_POST['Adv_govt'];
$datex=$_POST['date'];
$t_day=$_POST['t_day'];
$case_type=$_POST['case_type'];


//CONN AS STRING
$connAsString = implode("  ,  ", $conn);

//preventing duplicate entries
$sql = "SELECT * FROM periphery.adv_attend WHERE ci_no='$cino' AND adv_code='$Adv_govt' AND appdate='$datex' AND display='Y'";
$queryStmt = $pgconn->prepare($sql);
$queryStmt->execute();
$rows2 = $queryStmt->fetchAll();

if($rows2){
    $Mg_error="Duplicate Entry ";
}
else{

//getting adv name based on adv_id
$sql = "SELECT * FROM periphery.adv_govt_det WHERE advid = :Adv_govt";
$queryStmt = $pgconn->prepare($sql);
$pgconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$queryStmt->bindParam(':Adv_govt', $Adv_govt);
$queryStmt->execute();
$row = $queryStmt->fetch();
$Adv_name = $row['advname'];
$Adv_type=$row['advtype'];


//inserting data into table 
$query = "INSERT INTO periphery.adv_attend(ci_no,adv_code,adv,stage,appdate,conn,lupdt,petn,resp,adv_type,case_type) VALUES(:cino,:Adv_govt,:Adv_name,:Case_Stage,:datex,:connAsString,:t_day,:Petn,:Resp,:adv_type,:case_type)";
$queryStmt = $pgconn->prepare($query);
$pgconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //pdo of connection
$executeSuccess = $queryStmt->execute(array(':cino'=>$cino,':Adv_govt'=>$Adv_govt, ':Adv_name'=>$Adv_name,':Case_Stage'=>$Case_Stage,':datex'=>$datex,':connAsString'=>$connAsString,':t_day'=>$t_day,':Petn'=>$Petn,':Resp'=>$Resp,':adv_type'=>$Adv_type,':case_type'=>$case_type));

 if($executeSuccess){
    header('Location:Adv_att2.php');
}
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Page</title>
    <style>
        .error-message {
            color: red;
            text-align: center;
            font-size: 38px;
        }
    </style>
</head>
<body>
    <label class="error-message"><?php echo $Mg_error; ?></label>
    <a class='btn btn-primary' href='Adv_att2.php' role='button'>Go Back</a>
</body>
</html>

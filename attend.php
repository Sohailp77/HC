<?php
include "./connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Adv_name=$_POST['Adv_name'];
    $Adv_title=$_POST['Adv_title'];
    $Adv_type=$_POST['Adv_type'];
    $priority=0;
}
if($Adv_title=="Advocate Genral"){
    $priority=1;
}

$query = "INSERT INTO periphery.adv_govt_det(advtype,title,advname,print_priority) VALUES(:advtype,:title,:advname,:print_priority)";
$queryStmt = $pgconn->prepare($query);
$pgconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //pdo of connection
$executeSuccess = $queryStmt->execute(array(':advtype'=>$Adv_type,':title'=>$Adv_title, ':advname'=>$Adv_name,':print_priority'=>$print_priority));

 if($executeSuccess){
    header('Location:Adv_entry.php');
}

?>
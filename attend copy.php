<?php
include "./connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Adv_name=$_POST['Adv_name'];
    $Adv_title=$_POST['Adv_title'];
    $Adv_type=$_POST['Adv_type'];
}

$query = "INSERT INTO periphery.adv_govt_det(advtype,title,advname) VALUES(:advtype,:title,:advname)";
$queryStmt = $pgconn->prepare($query);
$pgconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //pdo of connection
$executeSuccess = $queryStmt->execute(array(':advtype'=>$Adv_type,':title'=>$Adv_title, ':advname'=>$Adv_name));

 if($executeSuccess){
    header('Location:Adv_entry.php');
}

?>
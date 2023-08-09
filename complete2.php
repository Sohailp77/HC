<?php
include "./connect.php";
$fullDate = date("F j, Y");

if(isset($_GET["id"])) {
     $id = $_GET['id'];
     
    try {
        // Update the row in the database
        $query = "UPDATE periphery.task_scheduler SET completed ='$fullDate' WHERE sr_no = :id";
        $queryStmt = $pgconn->prepare($query);
        $queryStmt->bindParam(':id', $id);
        $queryStmt->execute();

        header('Location: Index2.php');
    } catch (PDOException $e) {
        echo "Delete failed: " . $e->getMessage();
    }
}
?>
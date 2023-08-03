<?php
include "./connect.php";

if(isset($_GET["id"])) {
    $id = $_GET['id'];

    try {
        // Update the row in the database
        $query = "UPDATE periphery.adv_govt_det SET display ='N' WHERE advid = :id";
        $queryStmt = $pgconn->prepare($query);
        $queryStmt->bindParam(':id', $id);
        $queryStmt->execute();

        header('Location: Adv_entry.php');
    } catch (PDOException $e) {
        echo "Delete failed: " . $e->getMessage();
    }
}
?>
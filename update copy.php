<?php
include "./connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $Adv_name = $_POST['Adv_name'];
    $Adv_title = $_POST['Adv_title'];
    $Adv_type = $_POST['Adv_type'];

    try {
        // Update the row in the database
        $query = "UPDATE periphery.adv_govt_det SET advtype = :advtype, title = :title, advname = :advname WHERE advid = :id";
        $queryStmt = $pgconn->prepare($query);
        $queryStmt->bindParam(':advtype', $Adv_type);
        $queryStmt->bindParam(':title', $Adv_title);
        $queryStmt->bindParam(':advname', $Adv_name);
        $queryStmt->bindParam(':id', $id);
        $queryStmt->execute();

        header('Location: Adv_entry.php');
    } catch (PDOException $e) {
        echo "Update failed: " . $e->getMessage();
    }
}
?>

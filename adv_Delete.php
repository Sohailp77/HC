<?php
include "./connect.php";



// if (isset($_GET['ci_no'])) {
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $ci_no = $_GET['ci_no'];
  $Adv=$_GET['adv'];
  $date=$_GET['date'];

    $sql = "UPDATE periphery.adv_attend SET display = 'N' WHERE ci_no = :ci_no AND adv=:adv AND appdate=:date";
    $updateStmt = $pgconn->prepare($sql);
    $updateStmt->bindParam(':ci_no', $ci_no);
    $updateStmt->bindParam(':adv', $Adv);
    $updateStmt->bindParam(':date', $date);
    //$updateStmt->execute();

  try {
    $updateStmt->execute();
    echo "Row deleted successfully!";
    header('Location:Adv_att2.php');

  } catch (PDOException $e) {
    echo "Error deleting row: " . $e->getMessage();
  }
}
?>

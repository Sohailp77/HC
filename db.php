<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "./connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $Task = $_POST['Task'];
    $Task_Dsc = $_POST['Task_Desc'];
    $Date = $_POST['dateInput'];
    $DayOfWeek = $_POST['dayOfWeek'];
    $Priority = $_POST['Priority'];
    $Frequency = $_POST['frequency'];
    $Task_Type = $_POST['Task_type'];
    $Print_priority = "";
    $DOW="";
    $day="";

    if ($Priority == "Low") {
        $Print_priority = "1";
    } elseif ($Priority == "Medium") {
        $Print_priority = "2";
    } elseif ($Priority == "High") {
        $Print_priority = "3";
    }

    if($DayOfWeek=="Monday"){
        $DOW="1";
    }elseif($DayOfWeek=="Tuesday"){
        $DOW="2";
    }elseif($DayOfWeek=="Wednesday"){
        $DOW="3";
    }elseif($DayOfWeek=="Thursday"){
        $DOW="4";
    }elseif($DayOfWeek=="Friday"){
        $DOW="5";
    }elseif($DayOfWeek=="Saturday"){
        $DOW="6";
    }elseif($DayOfWeek=="Sunday"){
        $DOW="7";
    }

    $dateComponents = explode('-', $Date);
     $days = $dateComponents[2];
   
    if($days=="01"){
        $day="1";
    }elseif($days=="02"){
        $day="2";
    }elseif($days=="03"){
        $day="3";
    }elseif($days=="04"){
        $day="3";
    }elseif($days=="05"){
        $day="5";
    }elseif($days=="06"){
        $day="6";
    }elseif($days=="07"){
        $day="7";
    }elseif($days=="08"){
        $day="8";
    }elseif($days=="09"){
        $day="9";
    }
    // echo $DOW;

    $query = "INSERT INTO periphery.Task_scheduler(task_name, task_desc, date_x, dow, priority, frequency, print_priority, task_type,dom) VALUES(:task_name, :task_desc, :date_x, :dow, :priority, :frequency, :print_priority, :task_type,:day_x)";
    $queryStmt = $pgconn->prepare($query);

    // Use bindValue to bind the parameters
    $queryStmt->bindValue(':task_name', $Task, PDO::PARAM_STR);
    $queryStmt->bindValue(':task_desc', $Task_Dsc, PDO::PARAM_STR);
    $queryStmt->bindValue(':date_x', $Date, PDO::PARAM_STR);
    $queryStmt->bindValue(':dow', $DOW, PDO::PARAM_STR);
    $queryStmt->bindValue(':priority', $Priority, PDO::PARAM_STR);
    $queryStmt->bindValue(':frequency', $Frequency, PDO::PARAM_STR);
    $queryStmt->bindValue(':print_priority', $Print_priority, PDO::PARAM_INT);
    $queryStmt->bindValue(':task_type', $Task_Type, PDO::PARAM_STR);
    $queryStmt->bindValue(':day_x', $day, PDO::PARAM_STR);

    try {
        $queryStmt->execute();
        header('Location: Reminder.php');
    } catch (PDOException $e) {
        // Handle the exception or error here
        echo "Error: " . $e->getMessage();
    }
}
?>

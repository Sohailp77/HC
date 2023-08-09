<?php
session_start(); // Ensure session is started
$userCode = $_SESSION['userCode'];
include("../../cisfunctions.php");
// include("../../session_handle.php");
include_once('../../../../swecourtishc/includes/sessions.php');

include "./connect.php";
$fullDate = date("F j, Y");
$currentDate = date("Y-m-d");
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

function loadPage(url) {
            window.location.href =url;
        }

        setTimeout(function() {
            location.reload();
            }, 5000);
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Task Scheduler</title>
    <?php $userCode;?>
    <style>
       .error-message {
            color: red;
            font-size: 18px;
            
        }
        body {
    display: flex;
    height: 100vh;
    margin: 0;
    }
        .container-box {
    border: 1px solid #ccc;
    background-color: #fff;
    padding: 20px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0.3, 0.40);
    border-radius: 5px;
    max-width: 1000px;
    width: 100%;
    margin: auto; /* Center the box using auto margins */
    border-radius: 15px;
    
    }
    .container-box:hover {
    box-shadow: 0px 0px 20px rgba(0, 0, 0.6, 0.70);
    border-radius: 25px;
}

    .container-box label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .container-box input,
    .container-box textarea,
    .container-box select  {
        width: 100%;
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 3px;
        
    }
    .task-box {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            position: relative;
            border-radius:15px;
            transition: transform 0.3s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .task-box:hover{
        box-shadow: 0px 0px 20px rgba(0, 0, 0.6, 0.50);
        transform: scale(1.2);
    }

    .task-buttons {
    position: absolute;
    top: 0;
    right: 0;
    border-radius: 20px;
        }

        /* Adjust the styles for the delete and edit buttons as needed */
        .delete-button{
            border: none;
            margin-left: 5px;
            padding: 3px;
        }
        
        .complete-button{
            position: absolute;
            bottom: 0;
            right: 0;
            border-radius: 40px;
        }

        .delete-button {
            background-color: #f44336; 
        }

        .delete-button:hover {
            opacity: 0.8;
        }
        .priority-low { background-color: #b2e0b2; }
        .priority-medium { background-color: #f5e87d; }
        .priority-high { background-color: #ffad99; }
    
    </style>
</head>
<body>
    
<div class="container-fluid" style="width:60%">
<p></p>
    <form name="infofrm" id="myForm" method="post" action="db.php">
        <div class="panel panel-primary">
            <div class="panel-heading" style="text-align:center"><b>Task Scheduler</b></div>
            <div class="panel-body">
                <div id="viewerror" style="display:none;color:red;size:+2" align='center'></div><br>

                <!-- table started -->
                <table border="0" class="table table-bordered">
                    <tr>
                    <td align='center' colspan='6'>
                        <input type="radio" name="Task_type" value="T"  checked onclick="loadPage('Index2.php')">
                        <span id="label_fsearch_case">To Do</span>
                        &nbsp;&nbsp;&nbsp;
                        <input type="radio" name="Task_type" value="R"  onclick="loadPage('Reminder2.php')">
                        <span id="label_fsearch_filling">Reminder</span>
                    </td>
                    </tr>
                </table>
                <div class="container-box">
                    <h1>To-Do List</h1>
                <div>
                <?php
                 $sql= "SELECT * FROM periphery.task_scheduler WHERE display='Y' AND task_type='T' AND completed !='$fullDate' AND date_x='$currentDate' ORDER BY print_priority DESC";
                 $sql = $pgconn->prepare($sql); //connecting to database
                 $pgconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //pdo of connection
                 $sql->execute(); //to execute the query
                 $rows = $sql->fetchAll();
                 foreach($rows as $row){ 
                    $priorityClass = 'priority-low';
                    if ($row['priority'] == 'Medium') {
                        $priorityClass = 'priority-medium';
                    } elseif ($row['priority'] == 'High') {
                        $priorityClass = 'priority-high';
                    }
                    if($rows){

                    echo "<div class='task-box $priorityClass'>";
                    echo "<h3>{$row['task_name']}</h3>";
                    echo "<p>{$row['task_desc']}</p>";
                    echo"      <div class='task-buttons'>";
                    // echo      "<button class='delete-button'>Delete</button>";
                    echo "<a class='delete-button' href='./delete.php?id={$row['sr_no']}'>X</a>";
                    echo      "</div>";
                    echo "<a class='btn btn-primary' href='./complete2.php?id={$row['sr_no']}'>Completed</a>";
                    echo      "</div>";
                ?>
                
                <?php } ?>
                <?php } ?>
                </div>
                
                <a class="btn btn-primary"  href="Index.php">Task Scheduler</a>
                </div>
                
                <p></p>
    </div>
                </form>
               
    </body>
    </html>
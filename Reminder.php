<?php
session_start(); // Ensure session is started
$userCode = $_SESSION['userCode'];
include("../../cisfunctions.php");
// include("../../session_handle.php");
include_once('../../../../swecourtishc/includes/sessions.php');

include "./connect.php";


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

function updateDayOfWeek() {
    const dateInput = document.getElementById('dateInput').value;
    const selectedDate = new Date(dateInput);
    const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const dayOfWeek = daysOfWeek[selectedDate.getDay()];
    document.getElementById('dayOfWeek').textContent = dayOfWeek;
    document.getElementById('dayOfWeekInput').value = dayOfWeek;
        }

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
    max-width: 400px;
    width: 100%;
    margin: auto; /* Center the box using auto margins */
    }

    .container-box label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .container-box input,
    .container-box textarea,
    .container-box button,
    .container-box a,
    .container-box select  {
        width: 100%;
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    .radio-group {
    display: flex;
    align-items: center;
    }

    .radio-group input[type="radio"] {
        margin-right: 5px;
    }

    .radio-group label {
        margin-right: 15px;
        cursor: pointer;
    }



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
                        <input type="radio" name="Task_type" value="T"  onclick="loadPage('Index.php')">
                        <span id="label_fsearch_case">To Do</span>
                        &nbsp;&nbsp;&nbsp;
                        <input type="radio" name="Task_type" value="R" checked onclick="loadPage('Reminder.php')">
                        <span id="label_fsearch_filling">Reminder</span>
                    </td>
                    </tr>
                </table>
                <div class="container-box">
                <label for="dateInput">Select a Date:</label>
                <input type="date" class="form-control" id="dateInput" name="dateInput" oninput="updateDayOfWeek()">
   
                    <label>Task: </label>
                    <input type="text" class="form-control" name="Task"> 
                    <label>Task Description:</label>
                    <textarea class="form-control" name="Task_Desc"></textarea>
                    <label>Priority :</label>
                    <select class="form-control" name="Priority">
                        <option>Select</option>
                        <option>Low</option>
                        <option>Medium</option>
                        <option>High</option>
                    </select>
                    
                    <div class="radio-group">
                    <input type="radio" id="daily" name="frequency" value="D">
                    <label for="daily">Daily</label>

                    <input type="radio" id="weekly" name="frequency" value="W">
                    <label for="weekly">Weekly</label>

                    <input type="radio" id="monthly" name="frequency" value="M">
                    <label for="monthly">Monthly</label>
                    </div>

                    <button class="btn btn-primary"  type="submit">Submit</button>
                    <a class="btn btn-primary"  href="Reminder2.php">Task Scheduled</a>
                    <p>Day of Week: <span id="dayOfWeek"></span></p>
                    <input type="hidden" id="dayOfWeekInput" name="dayOfWeek"></input>

                </div>
                <p></p>
    </div>
                </form>
               
    </body>
    </html>
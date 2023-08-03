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


function updateSecondSelect(selectedValue) {
  const secondSelect = document.getElementById("second-select");
  
  // Automatically select the corresponding option in the second select based on the first select's value
  switch (selectedValue) {
    case "Govt Adv(State)":
      secondSelect.value = "Advocate Genral";
      break;
    default:
      // If no match, you can choose a default value or do nothing
      secondSelect.value = "";
  }
}

</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Advocate Entry</title>
    <?php $userCode;?>
    <style>
        .error-message {
            color: red;
            font-size: 18px;
        }
        .container2 {
        display: flex;
        justify-content: center;
        align-items: center;
    }

        
    </style>
</head>
<body>
<div class="container-fluid" style="width:60%">
    <form name="infofrm" id="myForm" method="post" action="attend.php">
        <div class="panel panel-primary">
            <div class="panel-heading" style="text-align:center"><b>Advocate Entry </b></div>
            <div class="panel-body">
                <div id="viewerror" style="display:none;color:red;size:+2" align='center'></div><br>

                <!-- table started -->
                <table border="0" class="table table-bordered">
                  
                    <tr >
                        <form>
                        <td>
                         <select class="form-control" name="Adv_type" id="first-select" onchange="updateSecondSelect(this.value)">
                            <option>Select</option>
                            <option value="Govt Adv(State)" >Govt Adv (State)</option>
                            <option value="Govt Adv(Central)">Govt Adv (Central)</option>
                         </select>
                        </td>
                        <td>
                            <label for="second-select">Tittle:</label>
                            <select id="second-select" class="form-control" name="Adv_title">
                            <option value="">Select</option>
                            <option value="Advocate Genral">Advocate Genral</option>
                            <option value="Public prosecutor">Public prosecutor</option>
                            <option value="Additional Public prosecutor">Additional Public prosecutor</option>
                            <option value="Govt (Adv)">Govt (Adv)</option>
                            <option value="Additional Govt(Adv)">Additional Govt(Adv)</option>
                        </select>
                        </td>
                        <td>
                        <label>Name:</label>
                            <input class="form-control" name="Adv_name"></input> 
                        </td>
                        <td>
                        <button class="btn btn-primary" id="submitButton" type="submit" role="button">Go</button>
                        </td>
                    </tr>
                </table>
                </form>
                <br></br>
                <h2>List of Advocates</h2>
    </div>
    <div class="container2 my-5 mx-5">
        <table class="table table-hover" style="align-items: center;">
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Sr No.</th>
                    <th scope="col">Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Title</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <?php 
                  $sql_s = "SELECT * FROM periphery.adv_govt_det WHERE display='Y'"; //Query
                  
                  $sql_s = $pgconn->prepare($sql_s); //connecting to database
                  $pgconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //pdo of connection
                  $sql_s->execute(); //to execute the query
                  $rows = $sql_s->fetchAll(); //if multiple variables use fetchall and foreach loop or while loop || if its fetch() no loop
                    
                  //print_r($rows);
            ?>
                    <?php for($i=0;$i<count($rows);$i++){ ?>

                        <tr>
                        <td></td>
                        <td><?php echo $i+1;?></td>
                        <td><?php echo $rows[$i]['advname'];?></td>
                        <td><?php echo $rows[$i]['advtype'];?></td>
                        <td><?php echo $rows[$i]['title'];?></td>
                        <td>
                            <form action="delete.php" method="post">
                            <a class="btn btn-primary" href="./Adv_edit.php?id=<?php echo $rows[$i]['advid']; ?>" type="submit">Edit</a>
                            <a class="btn btn-primary" href="./Delete.php?id=<?php echo $rows[$i]['advid']; ?>" type="submit">Delete</a>
                             <!-- <a  class="btn btn-primary" href="./edit.php?id=<?php //echo $row[$i]['id']; ?>">Edit</a> -->
                             <?php
                             echo $row[$i]['id'];
                             ?>
                            </form>
                        </td>
                        </tr>



                    <?php } ?>
            </tbody>
        </table>
               
    </body>
    </html>
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
</script>
<?php
if(isset($_GET["id"])) {
    $id = $_GET["id"];

    // Retrieve the task details from the database
    $query = "SELECT * FROM periphery.adv_govt_det WHERE advid = :id";
    $queryStmt = $pgconn->prepare($query);
    $queryStmt->bindParam(':id', $id, PDO::PARAM_INT);
    $queryStmt->execute();
    $task = $queryStmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Redirect to the home page if the ID is not provided
    header('Location: Adv_entry.php');
    exit();
}
?>

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
    <form name="infofrm" id="myForm" method="post" action="update.php">
        <div class="panel panel-primary">
            <div class="panel-heading" style="text-align:center"><b>Advocate Entry </b></div>
            <div class="panel-body">
                <div id="viewerror" style="display:none;color:red;size:+2" align='center'></div><br>

                <!-- table started -->
                <table border="0" class="table table-bordered">
                  
                    <tr >
                        <form>
                        <td>
                         <select class="form-control" name="Adv_type" id="first-select">
                            <option value="<?php echo $task['advtype'];?>"><?php echo $task['advtype'];?></option>
                         </select>
                        </td>
                        <td>
                            <label for="second-select">Tittle:</label>
                            <select id="second-select" class="form-control" name="Adv_title">
                            <option value="<?php echo $task['title']; ?>"><?php echo $task['title']; ?></option>
                            <option value="Advocate Genral">Advocate Genral</option>
                            <option value="Public prosecutor">Public prosecutor</option>
                            <option value="Additional Public prosecutor">Additional Public prosecutor</option>
                            <option value="Govt (Adv)">Govt (Adv)</option>
                            <option value="Additional Govt(Adv)">Additional Govt(Adv)</option>
                        </select>
                        </td>
                        <td>
                        <label>Name:</label>
                            <input class="form-control" name="Adv_name" value="<?php echo $task['advname'];?>"></input> 
                            <input type="hidden" name="id" value="<?php echo $id; ?>"></input>
                        </td>
                        <td>
                        <button class="btn btn-primary" id="submitButton" type="submit" role="button">Go</button>
                        </td>
                    </tr>
                </table>
                </form>
</body>
</html>
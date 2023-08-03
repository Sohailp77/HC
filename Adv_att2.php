<?php
session_start(); // Ensure session is started
$userCode = $_SESSION['userCode'];
include("../../cisfunctions.php");
// include("../../session_handle.php");
include_once('../../../../swecourtishc/includes/sessions.php');

include "./connect.php";

$today = date('Y-m-d');

// $p_casekey = '';
// $p_caseno = '';
// $p_caseyr = '';
// $p_sr = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p_casekey = $_POST['mskey'];
    $p_caseno = $_POST['m_no'];
    $p_caseyr = $_POST['m_yr'];
    $date_x = $_POST['Date_x'];
    $p_sr = $_POST['fcasenotype'];
}

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

function clearForm() {
    document.getElementById('mskey').value = "0";
    document.getElementById('m_no').value = "";
    document.getElementById('m_yr').value = "";
    document.getElementById('Date_x').value = "";

}
function copy_obj_xx() {
      var copy_objection_xx = document.getElementById("arr_obj_xx");
      copy_objection_xx.select();
      copy_objection_xx.setSelectionRange(0, 99999); // For mobile devices

      document.execCommand("copy");
      alert("Copied the text: " + copy_objection_xx.value);
    }

</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Advocate Attendance</title>
    <?php $userCode;?>
    <style>
        .error-message {
            color: red;
            font-size: 18px;
            
        }
    </style>
</head>
<body>
<div class="container-fluid" style="width:60%">
    <form name="infofrm" id="myForm" method="post" action="">
        <div class="panel panel-primary">
            <div class="panel-heading" style="text-align:center"><b>Advocate Attendance</b></div>
            <div class="panel-body">
                <div id="viewerror" style="display:none;color:red;size:+2" align='center'></div><br>

                <!-- table started -->
                <table border="0" class="table table-bordered">
                    <tr>
                        <td align='center' colspan='6'>
                            <input type="radio"  name="fcasenotype" value="R" checked <?php if ($p_sr==='R') echo 'Checked';?>>
                            <span id="label_fsearch_case">Case No.</span>
                            &nbsp;&nbsp;&nbsp;
                            <input type="radio" name="fcasenotype" value="F"  <?php if ($p_sr==='F') echo 'Checked';?>>
                            <span id="label_fsearch_filling">Filing No.</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="mskey_hidden" value="<?php echo $p_casekey; ?>">
                            <select id='mskey' name='mskey' class="form-control"  <?php if($p_casekey != '') echo 'disabled'; ?>>
                                <option value='0'>Select</option>
                                <?php
                                $query = "select case_type,concat(type_name,'-',case_type) as jname from case_type_t where display='Y' order by type_name";
                                $var_casetype = bhc_filldropdown($query,'case_type','jname');
                                foreach($var_casetype as $key => $value) {
                                    if($p_casekey == $key) {
                                        echo "<option value='$key' selected>$value</option>";
                                    } else {
                                        echo "<option value='$key'>$value</option>";
                                    }
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <label>NO:</label>
                            <input name="m_no" id="m_no" value="<?php echo $p_caseno; ?>" class="form-control" <?php if($p_caseno != '') echo 'disabled'; ?> >
                        </td>
                        <td>
                            <label>Year:</label>
                            <input name="m_yr" id="m_yr" value="<?php echo $p_caseyr; ?>" class="form-control" <?php if($p_caseyr != '') echo 'disabled'; ?> >
                        </td>
                        <td>
                            <label>Date</label>
                            <input type="date" name="Date_x" id="Date_x" pattern="\d{4}-\d{2}-\d{2}" value="<?php echo $date_x; ?>" class="form-control" <?php if($date_x != '') echo 'disabled'; ?> >
                        </td>
                        <td>
                            <button class="btn btn-primary" id="submitButton" type="submit" role="button">Go</button>
                            <button class="btn btn-primary" type="submit" role="button" onclick="clearForm()">Clear</button>
                        </td>
                    </tr>
                </table>
                </form>
                <?php
            
            $cino=bhc_validatecaseno($p_casekey,$p_caseno,$p_caseyr,$p_sr,"");
            
            
            // echo $cino;
            ?>
           
            <?php if (!empty($cino)): ?>
              <div class="container">

                <form action="Adv_att.php" method="post">
                <input type="hidden" name="Cino" value="<?php print $cino; ?>">
            <?php
                $sql="select hide_pet_name,hide_res_name,hide_partyname,pet_name,res_name,pet_adv,res_adv,ci_cri from Civil_t where cino='$cino' union select hide_pet_name,hide_res_name,hide_partyname,pet_name,res_name,pet_adv,res_adv,ci_cri from Civil_t_a where cino='$cino'";
                if(fun_sel($sql))	
                {
                    $row_p=fun_fetar($sql);
                    $hide_party_flag = $row_p['hide_partyname'];
                    $hide_pet_flag = $row_p['hide_pet_name'];
                    $hide_res_flag = $row_p['hide_res_name'];
                    $pet_adv=$row_p['pet_adv'];
                    $res_adv=$row_p['res_adv'];
                    $case_type=$row_p['ci_cri'];
                    if($hide_pet_flag == 'Y')
                        $disp_pet_name = 'XXXXX';
                    else
                        $disp_pet_name = $row_p['pet_name'];
                        
                    if($hide_res_flag == 'Y')
                        $disp_res_name = 'XXXXX';
                    else
                        $disp_res_name = $row_p['res_name'];
                }
                else{
                    $error="Invalid CINO";
                }
        
    ?>

        
        <label>Petn</label>
        <select style="width: 200px;" class="form-control" name="Petn">
            <option><?php echo $disp_pet_name;?></option>
        </select>
        

                
            <label>V/s Resp</label>
            <select class="form-control" style="width: 200px;" name="Resp">
            <option><?php echo $disp_res_name;?></option>
            </select>
                <br></br>
                    
                <label>Petn. Adv</label>
                <select id="myDropdown" style="width: 200px;" class="form-control" name="Petn_Adv">
                        <option value="<?php echo $pet_adv?>"><?php echo $pet_adv; ?></option>
                    </select>
               
                <label>Res. Adv</label>
                <select id="myDropdown" style="width: 200px;" class="form-control" name="Resp_Adv">
                        <option value="<?php echo $res_adv?>"><?php echo $res_adv;?></option>
                    </select>
                
                <br></br>
                
    <label>Enter Govt. Adv. who appeared</label>
    <select class="form-control" Name="Adv_govt" Required>
        <option value="">Select</option>
        <?php
        $sql= "SELECT * FROM periphery.adv_govt_det WHERE display='Y'";
        $sql = $pgconn->prepare($sql); //connecting to database
        $pgconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //pdo of connection
        $sql->execute(); //to execute the query
        $rows = $sql->fetchAll();
        foreach($rows as $row){  
        ?>
        <option value=<?php echo $row['advid']?>><?php echo $row['advname'] ." "."(".$row['advtype'].")"?></option>
        <?php }?>
    </select>
    
    <br></br>

    <label>Date of Appearance</label>
    <input type="Date" name="date" class="form-control" value="<?php echo $date_x?>"></input>

    <br></br>

<?php
    //purpose code based on cino
    $sql = "SELECT purpose_cd, causelist_date FROM cause_list WHERE cino = :cino ORDER BY causelist_date DESC LIMIT 1";
    $sql = $pgconn->prepare($sql);
    $sql->bindParam(':cino', $cino);
    $sql->execute();
    $rows_s = $sql->fetchAll();
    if(empty($rows_s)){
        $purpose_cd = 0;
    }else{
        foreach ($rows_s as $row_t) {
            $purpose_cd= $row_t['purpose_cd'];
        }
    }
    ?>
    
<?php

    //to Get purpose name based on purpose code
    $sql = "SELECT purpose_code,purpose_name from purpose_t where purpose_code=$purpose_cd and display='Y' order by purpose_priority";
    $sql = $pgconn->prepare($sql);
    $sql->execute();
    $tasks2 = $sql->fetchAll();
    $purpose_nm="";
    foreach($tasks2 as $task2){
        $purpose_nm=$task2['purpose_name'];
    }
    ?>

    <label>Case Stage </label>
    <select class="form-control" name="Case_stage" style="width: 200px;">
        <option value="0" selected><?php echo 'Select' ?></option>
        <option value="503">503-FOR ADMISSION</option>
        <option value="522">522-FOR ORDERS</option>
        <option value="595">595-FOR FINAL HEARING</option>
    </select>
    <br></br>

    <table align="auto" class="table table-bordered" border="0" style="width: auto;">
            
            <tr>
                <td> </td>
                <td width="" align="center"><b>Case Number</b></td>	
                <td width="" align="center"><b>Matter </b></td>
            </tr>
            
            <tr>
                <td width="5%" align="center">
                    <input type="checkbox" name="arr_cino[]"  value="<?php print "|M|".$cino; ?>" <?php print 'onclick="return false;" onkeydown="e = e || window.event; if(e.keyCode !== 9) return false;"'; ?> checked readonly> 
                </td>
                 <td width="20%" align="center">
                   <?php print($cino_print = getCaseNo($cino)); ?>

                    </td>
                <td width="60%" align="center">
                    <?php $arr_txt = 'txt_'.$cino; 
                    $matter_type = "MN";
                    ?>

                    <input type="hidden" name="hidden_mnmt" value="<?php print $matter_type; ?>">
                    <label for="arr_txt">Main Matter</label>
                    <!-- <textarea class="form-control" cols="50" rows="2" id="arr_txt" name="<?php //print $arr_txt; ?>" disabled></textarea> -->
                </td>
            </tr>
            
            <?php 
            
            //   copy remark from     misc_causelist_casedetails?:
    
                $history_query_xx = "select case_remark from periphery.misc_causelist_casedetails where cino= '$cino' order by update_date desc";
    //							print $history_query_xx;
                if(fun_sel($history_query_xx)>0) 
                {	
                    $data_func1_xx=fun_fetarAll($history_query_xx);
                    foreach($data_func1_xx as $func1_xx) 
                    {
                        $history_obj_xx=trim($func1_xx['case_remark']);
                    }
                    
            ?>
            <tr>
            <td></td>
            <td></td>
            <td width="60%" align="center">
            <textarea class="form-control" cols="50" rows="2" id="arr_obj_xx" name="arr_obj_xx" style="background-color:#DFDFDF;" disabled ><?php print $history_obj_xx;?></textarea>
            </td>
               <td width="15%" align="center" bgcolor="">
             <input type="button" name="copy_objection_xx" id="copy_objection_xx"  value="Copy Objection" onClick="copy_obj_xx()" class="btn btn-info">
            
            </tr>
           
            <?php 
                }
                ?>
    

            <?php 
            //copy remark from objection_history table
                $history_query = "select objection from objection_history where cino= '$cino'";
                                
                if(fun_sel($history_query)>0) 
                {	
                    $data_func1=fun_fetarAll($history_query);
                    foreach($data_func1 as $func1) 
                    {
                        $history_obj=trim($func1['objection']);
                    }
                
                    $history_obj = str_replace("%","",$history_obj);

            ?>
            <tr>
            <td></td>
            <td></td>
            <td width="60%" align="center">
            <textarea class="form-control" cols="50" rows="2" id="arr_obj" name="arr_obj" style="background-color:#DFDFDF;"><?php print $history_obj; ?></textarea>
            </td>
               <td width="15%" align="center">
             <input type="button" name="copy_objection" id="copy_objection"  value="Copy Objection" onClick="copy_obj()" class="btn btn-info">
            
            </tr>
           
            <?php 
                }
                ?>
                
          <?php
          
            $link_query = "select cino from civil_t where link_cino = '$cino' and cino!='$cino' union select cino from civil_t_a where link_cino = '$cino' and cino!='$cino'";
            if(fun_sel($link_query)>0) 
            {	
                $link_q_res=fun_fetarAll($link_query);
                foreach($link_q_res as $row_px2) 
                {
                    $link_cino = $row_px2['cino'];
                    
                    $matter_type = "CM";

                    
                    $check111="select * from public.civil_t where cino='$cino'";
                    if(fun_sel($check111)>0)
                        $disable_checkbox = 'N';
                    else
                        $disable_checkbox = 'Y';
                    ?>
                    <tr>
                            <td width="5%" align="center">
                                <input type="checkbox" class="form-control" name="arr_cino[]"  value="<?php print $link_cino; ?>" <?php if($disable_checkbox == 'Y') { print 'onclick="return false;" onkeydown="e = e || window.event; if(e.keyCode !== 9) return false;"'; }  ?> > 
                                
                            </td>
                            <td width="20%" align="center">
                                <?php print($cino_print = getCaseNo($link_cino)); ?>
                                <input type="hidden" name="hidden_mnmt" value="<?php print $matter_type; ?>">
                                
                            </td>
                           
                            <td width="15%" align="center"> Connected Matter</td>
                        </tr>
                        <?php } 
                  }
                  ?>
</table>

<?php
   $sql = "SELECT * FROM periphery.adv_attend WHERE ci_no = :cino AND appdate = :date_x AND display = 'Y'";
   $queryStmt = $pgconn->prepare($sql);
   $queryStmt->bindParam(':cino', $cino);
   $queryStmt->bindParam(':date_x', $date_x);
   $queryStmt->execute();
   $tasks3 = $queryStmt->fetchAll();
   
   if (!empty($tasks3)) {
       foreach ($tasks3 as $row) { 
           $adv = $row['adv'];
           ?>
           <label class="error-message"><?php echo $adv ;?> Already occupied for <?php echo $date_x ;?></label>
           <a class='btn btn-primary'href="adv_Delete.php?ci_no=<?php echo $cino; ?>&adv=<?php echo $adv; ?>&date=<?php echo $date_x; ?>" role='button'>Delete</a>
           <br></br>
       <?php } } ?>




<input type="hidden"  name="t_day" value="<?php echo $today;?>"></input>
<input name="case_type" type="hidden" value="<?php echo $case_type;?>"></input>
<br></br>
<button class="btn btn-primary" align="center" type="submit" role="button">Submit</button>
<?php 
?>                                                   
</form>

<?php endif; ?>
</div>
</div>
</div>
</div>
</div>
</body>
</html>
   

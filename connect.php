<?php
$dbname_p='hcbgoa';
$db_host_p='localhost';
$db_user_p = "postgres";
$db_password_p = "postgres"; 
try 
{  		//connect to database
	$pgconn = new PDO("pgsql:host=$db_host_p;dbname=$dbname_p", $db_user_p, $db_password_p, array(PDO::ATTR_PERSISTENT => true));
	//echo "connect";	
}
//check connection successfull or not
catch(PDOException $e) 
{  		
	$e->getMessage();  
	echo  $e='Connection Failed dd';

} 
date_default_timezone_set('Asia/Kolkata'); 

?>
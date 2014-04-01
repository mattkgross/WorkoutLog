<?php
//$con = mysql_connect("mattkgrosscom.ipagemysql.com", "thedonald", "footlong");
// make sure to set the default_host in php.ini to mattkgrosscom.ipagemysql.com
$con = mysqli_init(); 

if (!$con) 
{
	die("mysqli_init failed"); 
}

$connection = mysqli_real_connect($con, ini_get("mysql.default_host"), "root", "");

if(!$connection)
	die("Database connection failure! Please alert webmaster.");
mysql_select_db("workout");
?>
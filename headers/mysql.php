<?php
//$con = mysql_connect("mattkgrosscom.ipagemysql.com", "thedonald", "footlong");
// make sure to set the default_host in php.ini to mattkgrosscom.ipagemysql.com
$con = mysql_connect(ini_get("mysql.default_host"), "cockfight", "SouthDakota3Way");
if(!$con)
	die("Database connection failure! Please alert webmaster.");
mysql_select_db("workout_beta");
?>
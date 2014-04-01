<?php
// Hide deprecation errors - switch to mysqli for future apps, though
error_reporting(E_ALL ^ E_DEPRECATED);

//$con = mysql_connect("mattkgrosscom.ipagemysql.com", "thedonald", "footlong");
// make sure to set the default_host in php.ini to mattkgrosscom.ipagemysql.com
$con = mysql_connect(ini_get("mysql.default_host"), "root", "");
if(!$con)
	die("Database connection failure! Please alert webmaster.");
mysql_select_db("workout");
?>
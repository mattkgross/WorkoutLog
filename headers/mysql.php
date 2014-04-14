<?php
// Hide deprecation errors - switch to mysqli for future apps, though
error_reporting(E_ALL ^ E_DEPRECATED);

function connStrToArray($conn_str){
 
    // Initialize array.
    $conn_array = array();
 
    // Split conn string on semicolons. Results in array of "parts".
    $parts = explode(";", $conn_str);
 
 
    // Loop through array of parts. (Each part is a string.)
    foreach($parts as $part){
 
        // Separate each string on equals sign. Results in array of 2 items.
        $temp = explode("=", $part);
 
        // Make items key=>value pairs in returned array.
        $conn_array[$temp[0]] = $temp[1];
    }
 
    return $conn_array;
}

$conn_str = getenv("MYSQLCONNSTR_DefaultConnection");

if(!empty($conn_str)) {
	$conn_array = connStrToArray($conn_str);
	$con = mysql_connect($conn_array['Data Source'], $conn_array['User Id'], $conn_array['Password']);
}
else {
	$con = mysql_connect(ini_get("mysql.default_host"), "root", "");
}
if(!$con)
	die("Database connection failure! Please alert webmaster.");
mysql_select_db("workout");
?>
<?php
session_start();

require_once("headers/mysql.php");

$ID = empty($_SESSION['ID'])?"":intval($_SESSION['ID']);
$sql = mysql_query("SELECT * FROM users WHERE id='" . $ID . "'");
$user = mysql_fetch_array($sql);

$sql = mysql_query("SELECT * FROM user_groups WHERE u_id='" . $ID . "' AND g_id='" . intval(addslashes($_GET['g'])) . "'");
$count = mysql_num_rows($sql);

if($count == 1) {
	$_SESSION['GROUP'] = intval(addslashes($_GET['g'])); }

header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
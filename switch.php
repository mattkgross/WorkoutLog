<?php
session_start();

require_once("headers/mysql.php");

$user = empty($_SESSION['USER'])?"":$_SESSION['USER'];

$sql = mysql_query("SELECT * FROM user_groups WHERE u_id='" . $user['id'] . "' AND g_id='" . intval(addslashes($_GET['g'])) . "'");
$count = mysql_num_rows($sql);

if($count == 1) {
	$sql = mysql_query("SELECT * FROM groups WHERE id='". intval(addslashes($_GET['g'])) . "'");
    $_SESSION['GROUP'] = mysql_fetch_array($sql); }

header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
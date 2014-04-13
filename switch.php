<?php
session_start();

require_once("headers/mysql.php");

$user = empty($_SESSION['USER'])?"":$_SESSION['USER'];

$sql = mysql_query("SELECT g_id,admin FROM user_groups WHERE u_id='" . $user['id'] . "' AND g_id='" . intval(addslashes($_GET['g'])) . "'");
$group = mysql_fetch_array($sql);

if(!empty($group)) {
	$sql = mysql_query("SELECT * FROM groups WHERE id='". $group['g_id'] . "'");
    $_SESSION['GROUP'] = mysql_fetch_array($sql);
    $_SESSION['G_ADMIN'] = (intval($group['admin']) == 1)?true:false;
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
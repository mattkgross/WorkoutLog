<?php
session_start();

require_once("headers/mysql.php");

$user = empty($_SESSION['USER'])?"":$_SESSION['USER'];
$group = empty($_SESSION['GROUP'])?"":$_SESSION['GROUP'];
$admin = empty($_SESSION['G_ADMIN'])?false:$_SESSION['G_ADMIN'];

$req = $_POST['req'];
$body = $_POST['body'];

if(!empty($user) && $admin)
{
	if($req == "a-ad") {
		mysql_query("UPDATE user_groups SET admin='1' WHERE u_id='" . intval($body) . "' AND g_id='" . $group['id'] . "'");
	}
	else if($req == "r-ad") {
		mysql_query("UPDATE user_groups SET admin='0' WHERE u_id='" . intval($body) . "' AND g_id='" . $group['id'] . "'");
	}
	else if($req == "del") {
		mysql_query("DELETE FROM user_groups WHERE u_id='" . intval($body) . "' AND g_id='" . $group['id'] . "'");
	}
	else {
		echo "No operation request was matched.";
	}
}

mysql_close();
?>
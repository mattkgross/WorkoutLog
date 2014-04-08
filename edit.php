<?php
session_start();

require_once("headers/mysql.php");

$ID = empty($_SESSION['ID'])?"":intval($_SESSION['ID']);
$G_ID = empty($_SESSION['GROUP'])?0:intval($_SESSION['GROUP']);
$sql = mysql_query("SELECT * FROM users WHERE id='" . $ID . "'");
$user = mysql_fetch_array($sql);
$sql = mysql_query("SELECT * FROM groups WHERE id='" . $G_ID . "'");
$group = mysql_fetch_array($sql);

$post_id = empty(($_POST['p_id'])?0:intval(addslashes($_POST['p_id']));
$desc = empty($_POST['desc'])?"":addslashes($_POST['desc']);
$op = empty($_POST['op'])?"":$_POST['op'];


$sql = mysql_query("SELECT * FROM posts WHERE id='". $post_id . "' AND u_id='" . $ID . "'");
$post = mysql_fetch_array($sql);

// Kick out anyone who's not logged in.
if(empty($ID) || empty($post) || ($op != "del" && $op != "save")) {
	header('Location: index.php'); }

if($op == "save") {
	mysql_query("UPDATE posts SET text='". $desc . "' WHERE id='" . $post_id . "'");
}
if ($op == "del" || empty($desc)) {
	mysql_query("DELETE FROM posts WHERE id='" . $post_id . "'");
}

header('Location: index.php?alert=workout_update#' . $user['u_name']);
?>
<?php
session_start();

require_once("headers/mysql.php");

$user = empty($_SESSION['USER'])?"":$_SESSION['USER'];
$group = empty($_SESSION['GROUP'])?"":$_SESSION['GROUP'];

$post_id = empty(($_POST['p_id'])?0:intval(addslashes($_POST['p_id']));
$desc = empty($_POST['desc'])?"":addslashes($_POST['desc']);
$op = empty($_POST['op'])?"":$_POST['op'];

$ID = empty($user)?0:$user['id'];
$sql = mysql_query("SELECT * FROM posts WHERE id='". $post_id . "' AND u_id='" . $ID . "'");
$post = mysql_fetch_array($sql);

// Kick out anyone who's not logged in.
if(empty($user) || empty($post) || ($op != "del" && $op != "save")) {
	header('Location: index.php'); }

if($op == "save") {
	mysql_query("UPDATE posts SET text='". $desc . "' WHERE id='" . $post_id . "'");
}
if ($op == "del" || empty($desc)) {
	mysql_query("DELETE FROM posts WHERE id='" . $post_id . "'");
}

header('Location: index.php?alert=workout_update#' . $user['u_name']);
?>
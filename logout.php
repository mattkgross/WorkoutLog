<?php
session_start();
if(isset($_SESSION['ID'])) {
	unset($_SESSION['ID']); }
if(isset($_SESSION['GROUP'])) {
	unset($_SESSION['GROUP']); }
session_destroy();
header('Location: index.php');
exit();
?>
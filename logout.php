<?php
session_start();
if(isset($_SESSION['USER'])) {
	unset($_SESSION['USER']); }
if(isset($_SESSION['GROUP'])) {
	unset($_SESSION['GROUP']); }
session_destroy();
header('Location: index.php');
exit();
?>
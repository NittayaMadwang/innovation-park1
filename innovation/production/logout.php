<?php 
	session_start();
	unset($_SESSION['AUTHEN']['UID']);
	unset($_SESSION['AUTHEN']['UNAME']);
	unset($_SESSION['AUTHEN']['UEMAIL']);
	unset($_SESSION['AUTHEN']['ULEVEL']);
	header('location: index.php');
	die(); 
?>
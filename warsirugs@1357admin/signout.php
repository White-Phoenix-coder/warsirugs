<?php

session_start();

include 'connection.php';

unset($_SESSION['ADMIN_USERID']);
session_destroy();
header("location:index.php");
		
?>
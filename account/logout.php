<?php
session_start();
$_SESSION['logged'] = FALSE;
unset($_SESSION['logged']);
header("Location: ../index.php");
?>
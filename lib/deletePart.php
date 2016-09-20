<?php
include("dbinfo.php");
session_start();

if (isset($_SESSION['logged']) and $_SESSION['logged'] and isset($_GET['id']) and isset($_SESSION['teamID'])) {
	try {
		$conn = new PDO( "mysql:host=$dbHost;dbname=$dbInstance", $dbRW, $dbRWPw);
		$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
	catch (Exception $e) {
		die( print_r( $e->getMessage(), true));
	}
	$dbQuery = "DELETE FROM requests WHERE request_teamID=".$_SESSION['teamID']." AND idrequests=".$conn->quote($_GET['id']);
	$conn->query($dbQuery);
}
header("Location: /parts/");
?>


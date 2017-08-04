<?php
require("secrets.php");

function db_connect_rw() {
  global $dbHost, $dbInstance, $dbRW, $dbRWPw;
  try {
    $conn = new PDO( "mysql:host=$dbHost;dbname=$dbInstance", $dbRW, $dbRWPw);
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    return $conn;
  }
  catch (Exception $e) {
    die( print_r( $e->getMessage(), true));
  }
}

function db_connect_access() {
  global $dbHost, $dbInstance, $dbAccess, $dbAccessPw;
  try {
		$conn = new PDO( "mysql:host=$dbHost;dbname=$dbInstance", $dbAccess, $dbAccessPw);
		$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    return $conn;
	}
	catch (Exception $e) {
		die( print_r( $e->getMessage(), true));
	}
}
?>

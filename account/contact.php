<?php
include "../lib/dbinfo.php";
if (isset($_GET['team'])) {
	$teamId = $_GET['team'];
	try {
		$conn = new PDO( "mysql:host=$dbHost;dbname=$dbInstance", $dbAccess, $dbAccessPw);
		$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
	catch (Exception $e) {
		die( print_r( $e->getMessage(), true));
	}
    $sql = $conn->query("SELECT email, twitter, website, zipcode FROM teams
    					 WHERE teamId=".$conn->quote($teamId)."");
    if($conn->query("SELECT COUNT(*) FROM teams
    WHERE teamId=".$conn->quote($teamId)."")->fetchColumn() == 1){
        $row = $sql->fetch();
        $email = $row['email'];
        $teamTwitter = $row['twitter'];
        $teamWebsite = $row['website'];
        $teamZipcode = $row['zipcode'];

        echo "Email: <a href=\"mailto:".$email."\">" . $email . "</a><br />";
		if ($teamTwitter !== NULL) {
			echo "Twitter: <a href=\"http://twitter.com/" . $teamTwitter . "\">@".$teamTwitter."</a><br />";
		}
		if ($teamWebsite !== NULL) {
			echo "Website: <a href=\"" . $teamWebsite . "\">".$teamWebsite."</a><br />";
		}
    if ($teamZipcode !== NULL) {
			echo "Zipcode: ".$teamZipcode."<br />";
		}
    }
    else {
        // Because this is an AJAX script, this script shouldn't be called with invalid data
    }
}
?>

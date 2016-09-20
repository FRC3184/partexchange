<?php
include('dbinfo.php');
include('mail.php');

if (isset($_GET['team'])) {
	var token = uniqid();
	var team = $_GET['team'];
	try {
		$conn = new PDO( "mysql:host=$dbHost;dbname=$dbInstance", $dbRW, $dbRWPw);
		$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
	catch (Exception $e) {
		die( print_r( $e->getMessage(), true));
	}
	$teams = $conn->query("SELECT email FROM teams WHERE teamId=".$conn->quote($_GET['team']));
	if (count($teams) !== 1) {
		die("Couldn't find that team number");
	}

	//TODO put token in db	

	$email = setupMail();
	$email->setFrom("noreply@parts.blazerobotics.org");
	$email->addAddress($teams[0]['email']);
	$email->isHTML(true);
	$email->Subject = "Password reset request for team " . $team;
	$email->Body = "A password reset request was generated for team ".$team." on the Blaze Robotics parts exchange. If you did not generate this request, you can safely ignore this email.<br /><br /><a href='https://parts.blazerobotics.org/account/reset_password.php?token=".$token."'>Reset your password</a>";
	$res = send($email);
	if ($res) {
		die($res);
	}
	echo("Check your inbox for a link to reset your password.");
} else {
	echo("Please enter a team number");
}
?>

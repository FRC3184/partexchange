<?php 
if(!empty($_POST)){ 
    require_once('recaptchalib.php');
    $siteKey = "6LcXGfwSAAAAACkoABhkFZWun5IgorYz0qgysE0K";
    $secret = "6LcXGfwSAAAAABnW3eS2I_xUIPCHTJp-3L52E4bX";
    // reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language
    $lang = "en";

    // The response from reCAPTCHA
    $resp = null;
    // The error code from reCAPTCHA, if any
    $error = null;

    $reCaptcha = new ReCaptcha($secret);

    // Was there a reCAPTCHA response?
    if ($_POST["g-recaptcha-response"]) {
        $resp = $reCaptcha->verifyResponse(
            $_SERVER["REMOTE_ADDR"],
            $_POST["g-recaptcha-response"]
        );
    }
    if ($resp != null && $resp->success) {
        
    
    
    include "dbinfo.php";
    
    //Verify passwords
    if (strcmp($_POST['password1'], $_POST['password2']) !== 0) {
        header("Location: /account/create.php?err=0");
        exit;
    }
    //Verify email
    if (preg_match("/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,9}|[0-9]{1,3})(\]?)$/", $_POST["email"]) !== 1) {
        header("Location: /account/create.php?err=1");
        exit;
    }
    
    $name = "".$dbHost . "\\" . $dbInstance . ",1433";
	try {
	$conn = new PDO( "sqlsrv:server=$name;", $dbRW, $dbRWPw);
	$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
	catch (Exception $e) {
		die( print_r( $e->getMessage(), true));
	} 
    
    //Verify team number
    if (preg_match("/\d+/", $_POST["teamNumber"]) !== 1) {
        header("Location: /account/create.php?err=2");
        exit;
    }
    $usr = $conn->quote($_POST['teamNumber']);
    if($conn->query("SELECT COUNT(*) FROM teams 
        WHERE teamId=".$usr.";")->fetchColumn() == 1){ 
        header("Location: /account/create.php?err=3");
        exit;
    }
    $query = "INSERT INTO teams (teamId, teamName, email, password) VALUES ('" . $_POST["teamNumber"] . "','" . $_POST["teamName"] . "','" . $_POST["email"] . "','" . hash('sha256', $_POST['password1']) . "');";
    $sql = $conn->query($query); 
    header("Location: /account/login.php");
    }
    else {
        header("Location: /account/create.php?err=4&txt=" . $resp->$errorCodes[0]);
        exit;
    }
    
} else {
    header("Location: /account/create.php");
}
    
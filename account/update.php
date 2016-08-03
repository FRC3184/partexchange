<?php 
include "../lib/vars.php";
if (!$logged) {
    //header("Location: login.php");
    echo "not logged in";
}

if(!empty($_POST)){ 
    print_r($_POST);
    include "../lib/dbinfo.php";
    $name = "".$dbHost . "\\" . $dbInstance . ",1433";
	try {
	$conn = new PDO( "mysql:host=$dbHost;dbname=$dbInstance", $dbRW, $dbRWPw);
	$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
	catch (Exception $e) {
		die( print_r( $e->getMessage(), true));
	} 
    $dbQuery = "UPDATE teams SET ";
    $comma = FALSE;
    echo sizeof($_FILES);
    
    if (strlen($_POST['email']) > 0) {
        //Verify email
        if (preg_match("/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,9}|[0-9]{1,3})(\]?)$/", $_POST["email"]) !== 1) {
            header("Location: index.php?err=0");
            exit;
        }   
        $values .= "email=" . $conn->quote($_POST['email']);
        $comma = TRUE;
    }
    if (strlen($_POST['twitter']) > 0) {
        if ($comma) {
            $dbQuery .= ",";
        }
        $dbQuery .= "twitter=".$conn->quote($_POST['twitter']);
        
        $comma = TRUE;
    }
    if (strlen($_POST['website']) > 0) {
        if ($comma) {
            $dbQuery .= ",";
        }
        $dbQuery .= "website=".$conn->quote($_POST['website']);
        
        $comma = TRUE;
        
    }
	
    if (file_exists($_FILES['picture']['tmp_name']) || is_uploaded_file($_FILES['picture']['tmp_name'])) {
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $temp = explode(".", $_FILES["picture"]["name"]);
        $extension = end($temp);

        if ((($_FILES["picture"]["type"] == "image/gif")
        || ($_FILES["picture"]["type"] == "image/jpeg")
        || ($_FILES["picture"]["type"] == "image/jpg")
        || ($_FILES["picture"]["type"] == "image/pjpeg")
        || ($_FILES["picture"]["type"] == "image/x-png")
        || ($_FILES["picture"]["type"] == "image/png"))
        && (true)
        && in_array($extension, $allowedExts)) {
          if ($_FILES["picture"]["error"] > 0) {
            header("Location: index.php?err=" . $_FILES["picture"]["error"]+2);
            echo "error";
            exit;
          } else {
            echo "moving";
            move_uploaded_file($_FILES["picture"]["tmp_name"], "../profile/".$_SESSION["teamID"].".png");
            if ($comma) {
                $dbQuery .= ",";
            }
            $dbQuery .= "has_profile_pic=1";
            
            $comma = TRUE;
          }
        } else {
          echo "bad file";
          header("Location: index.php?err=1");
          exit;
        }
    }
	$err = FALSE;
	if (strlen($_POST['newpass']) > 0) {
		if ($_POST['newpass'] != $_POST['confirmpass']) {
			$err = TRUE;
			if (!$comma) {
				header("Location: index.php?err=2");
				exit;
			}
		}
		$pas = hash("sha256", $_POST['oldpass']);
		if($conn->query("SELECT COUNT(*) FROM teams 
        WHERE teamId=".$conn->quote($_SESSION['teamID'])." AND 
        password='".$pas."';")->fetchColumn() == 1) {
			if ($comma) {
				$dbQuery .= ",";
			}
			
			
			$dbQuery .= "password='".hash("sha256", $_POST['newpass'])."'";
			
			$comma = TRUE;
		}
		else {
			$err = TRUE;
			if (!$comma) {
				header("Location: index.php?err=2");
				exit;
			}
		}
		
        
        
    }
    $dbQuery .= " WHERE teamId='" . $_SESSION['teamID'] . "';";
    
    echo "<br>".$dbQuery."<br>";
    $result = $conn->query($dbQuery); 
	if ($err) {
		header("Location: index.php?err=1");
		exit;
	}
	else {
		header("Location: index.php");
		exit;
	}
    
}else{    //If the form button wasn't submitted go to the index page, or login page 
    header("Location: index.php");
    exit; 
} 
?>
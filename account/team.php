<!DOCTYPE html>
<html>
<head>
    <?php
        $teamName = $email = $teamId = $teamTwitter = $website = NULL;
        $foundTeam = TRUE;
        $pic = "../profile/default.png";
        if (!isset($_GET['id'])) {
            header("Location: ../index.php");
            exit;
        }
        include("../lib/vars.php");
        include("../lib/dbinfo.php");
        $name = "".$dbHost . "\\" . $dbInstance . ",1433";
		try {
		$conn = new PDO( "sqlsrv:server=$name;", $dbAccess, $dbAccessPw);
		$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		}
		catch (Exception $e) {
			die( print_r( $e->getMessage(), true));
		} 
        $sql = $conn->query("SELECT * FROM teams 
        WHERE teamId=".$conn->quote($_GET['id']).""); 
        if($conn->query("SELECT COUNT(*) FROM teams 
        WHERE teamId=".$conn->quote($_GET['id'])."")->fetchColumn() == 1){ 
            $row = $sql->fetch(); 
            global $teamName, $email, $teamId, $teamTwitter, $pic, $website;
            $teamName = $row['teamName'];
            $email = $row['email'];
            $teamId = $row['teamId'];
            $teamTwitter = $row['twitter'];
            $teamWebsite = $row['website'];
            if ($row['has_profile_pic'] == 1) {
                $pic = "../profile/".$teamId.".png";
            }
        }
        else {
            global $teamName, $email, $teamId, $teamTwitter, $pic, $website, $foundTeam;
            $foundTeam = FALSE;
            $teamName = "?";
            $teamId = $_GET['id'];
        }
    ?>
    <title>Team <?php echo $teamId . ": " . $teamName; ?></title>

    <?php include("../lib/head.html"); ?>
</head>
<body>
    <?php include '../lib/navbar.php'; ?>
        
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo $teamName; ?></h3>
        </div>
        <div class="panel-body">
              <img class="img-thumbnail" src="<?php echo $pic; ?>" alt="Profile Picture" title="Profile Picture" style="width:256px;height:256px;float:left;"/>
              <div style="float:right;">
              <?php 
              if ($foundTeam) {
                  echo "Email: <a href=\"mailto:".$email."\">" . $email . "</a><br />";
                  if ($teamTwitter !== NULL) {
                    echo "Twitter: <a href=\"http://twitter.com/" . $teamTwitter . "\">@".$teamTwitter."</a><br />";
                  }
                  if ($teamWebsite !== NULL) {
                    echo "Website: <a href=\"" . $teamWebsite . "\">".$teamWebsite."</a><br />";
                  }
                  
              }
              else {
                echo "Couldn't find this team. If this is your team, you can <a href=\"create.php\">create an account</a>.";
              }
              ?>
              </div>
        </div>
    </div>
    
    <?php include '../lib/foot.html'; ?>
</body>
</html>
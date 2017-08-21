<?php
require("../lib/region.php");
require("../lib/database.php");
if (isset($_GET['team'])) {
	$teamId = $_GET['team'];
	$conn = db_connect_access();
  $sql_fetch = $conn->prepare("SELECT email, twitter, website, region FROM teams
    					         WHERE teamId=:team");
  $sql_count = $conn->prepare("SELECT COUNT(*) FROM teams WHERE teamId=:team");
  $sql_count->execute(array(":team" => $teamId));
  if ($sql_count->fetchColumn() == 1) {
    $sql_fetch->execute(array(":team" => $teamId));
    $row = $sql_fetch->fetch();
    $email = $row['email'];
    $teamTwitter = $row['twitter'];
    $teamWebsite = $row['website'];
    $teamRegion = $row['region'];

    echo "<span class='contact-row'>Email: <a href=\"mailto:".$email."\">" . $email . "</a></span>";
    echo "<span class='contact-row'>Region: " . getRegionName($teamRegion) . "</span>";
		if ($teamTwitter !== NULL) {
			echo "<span class='contact-row'>Twitter: <a href=\"http://twitter.com/" . $teamTwitter . "\">@".$teamTwitter."</a></span>";
		}
		if ($teamWebsite !== NULL) {
			echo "<span class='contact-row'>Website: <a href=\"" . $teamWebsite . "\">".$teamWebsite."</a></span>";
		}
  }
  else {
    echo "Can't find this team.";
  }
}
?>

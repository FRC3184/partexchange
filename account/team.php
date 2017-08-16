<?php include("vars.php"); ?>
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
  require("../lib/database.php");
  $conn = db_connect_access();

  $sql_fetch = $conn->prepare("SELECT teamName, email, teamId, has_profile_pic FROM teams WHERE teamId=:team");
  $sql_count = $conn->prepare("SELECT COUNT(*) FROM teams WHERE teamId=:team");
  $sql_count->execute(array(':team' => $_GET['id']));
  if ($sql_count->fetchColumn() == 1) {
    $sql_fetch->execute(array(":team" => $_GET['id']));
    $row = $sql_fetch->fetch();

    global $teamName, $email, $teamId, $teamTwitter, $pic, $website;
    $teamName = $row['teamName'];
    $email = $row['email'];
    $teamId = $row['teamId'];
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
    <div class="panel-body act-view">
      <img class="img-thumbnail" src="<?php echo $pic; ?>" alt="Profile Picture" title="Profile Picture"
           style="width:256px;height:256px;float:left;"/>
      <div id="team-contact-info">
        <?php
        if ($foundTeam) {
          echo '<button class="btn btn-primary" id="show-team-contact-info">Show contact information</button>';
        }
        else {
          echo "Couldn't find this team. If this is your team, you can <a href=\"create.php\">create an account</a>.";
        }
        ?>
      </div>
    </div>
  </div>


  <?php include '../lib/foot.html'; ?>
  <script type="text/javascript">
    $("#show-team-contact-info").click(function() {
      $.get("/account/contact.php?team=<?php echo $teamId; ?>").success(function(data) {
        $("#team-contact-info").html(data);
      });
    });
  </script>
</body>
</html>

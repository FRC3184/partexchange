<?php include("vars.php"); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Your Exchange History</title>

  <?php include("../lib/head.html"); ?>
</head>
<body>
  <?php
    $PRINT = isset($_GET['print']);
    $REQUEST = $_GET['opt'] === "req";

    if (!$PRINT) {include '../lib/navbar.php';}
    else {session_start();}
  ?>

  <?php
  if (!isset($_SESSION['logged']) or !$_SESSION['logged']) {
    echo "You must be logged in to see this.";
  }
  else {

    require("../lib/database.php");
    $conn = db_connect_access();
    if ($REQUEST) {
      $result = $conn->prepare("SELECT request_date, request_teamID, description, supply_team_id, fulfilled_date
                                FROM requests WHERE request_teamID=:team AND verified=1 AND supply_team_id IS NOT NULL
                                ORDER BY request_date DESC");
      $count = $conn->prepare("SELECT COUNT(*) FROM requests WHERE request_teamID=:team AND verified=1");
    }
    else {
      $result = $conn->prepare("SELECT request_date, request_teamID, description, supply_team_id, fulfilled_date
                                FROM requests WHERE supply_team_id=:team AND verified=1
                                ORDER BY request_date DESC");
      $count = $conn->prepare("SELECT COUNT(*) FROM requests WHERE supply_team_id=:team AND verified=1");
    }
    $result->execute(array(":team" => $_SESSION['teamID']));
    $count->execute(array(":team" => $_SESSION['teamID']));

    $usePic_sql = $conn->prepare("SELECT has_profile_pic FROM teams WHERE teamId=:team");
    $usePic_sql->execute(array(":team" => $_SESSION['teamID']));
    $usePicture = $usePic_sql->fetchColumn() == 1;

    if ($usePicture && $PRINT) {
      echo '<img style="float:left;width:128px;height:128px;" src="/profile/' . $_SESSION['teamID'] . '.png" />';
    }
    if ($PRINT) {
      echo '<img style="position:absolute;right:0;top:0px;width:32px;height:32px;" src="/profile/default.png" />';
      echo '<img style="position:absolute;right:0;top:32px;width:32px;height:32px;" src="/3184.png" />';
    }
    echo '
          <h2>Parts '.($REQUEST ? "requested" : "filled").' by '.$_SESSION['teamName'].' ' . (!$PRINT ? '&nbsp;
          <a href="history.php?opt='.($REQUEST ? "req" : "filled").'&print=true">Print</a>' : '') . '</h2>
          <h3>Total: '.$count->fetchColumn().'</h3>
          <table class="table table-striped table-hover ">
            <thead>
              <tr>
                <th>Date</th>
                <th>Requester</th>
                <th>Description</th>
                <th>Fulfilled Team</th>
                <th>Fulfilled Date</th>
              </tr>
            </thead>
            <tbody>';

              while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                  echo "<tr>";
                  echo "<td>" . $row["request_date"] . "</td>";
                  echo "<td>Team " . $row["request_teamID"] . "</td>";
                  echo "<td>" . $row["description"] . "</td>";
                  echo '<td> ' . $row['supply_team_id'] . '</td>';
                  echo '<td> ' . $row['fulfilled_date'] . '</td>';
                  echo "</tr>";
              }
              echo '
            </tbody>
          </table>';

        }
    ?>

    <?php if (!$PRINT) {include '../lib/foot.html'; } ?>
</body>
</html>

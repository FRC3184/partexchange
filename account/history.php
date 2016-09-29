<!DOCTYPE html>
<html>
<head>
  <title>Your Exchange History</title>

  <?php include("../lib/head.html"); ?>
</head>
<body>
  <?php
    if (!isset($_GET['print'])) {include '../lib/navbar.php';}
    else {session_start();}
  ?>

  <?php
  if (!isset($_SESSION['logged']) or !$_SESSION['logged']) {
    echo "You must be logged in to see this.";
  }
  else {
    if ($_GET['opt'] == "req") {
      include("../lib/dbinfo.php");
      $name = "".$dbHost . "\\" . $dbInstance . ",1433";
      try {
        $conn = new PDO( "mysql:host=$dbHost;dbname=$dbInstance", $dbAccess, $dbAccessPw);
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
      }
      catch (Exception $e) {
        die( print_r( $e->getMessage(), true));
      }

      $result = $conn->query("SELECT * FROM requests WHERE request_teamID='".$_SESSION['teamID']."' AND verified=1
                              ORDER BY request_date DESC");

      $team = $conn->query("SELECT * FROM teams WHERE teamId='".$_SESSION["teamID"] . "'")->fetch(PDO::FETCH_ASSOC);
      $usePicture = $team["has_profile_pic"];

      if ($usePicture == 1 && isset($_GET['print'])) {
        echo '<img style="float:left;width:128px;height:128px;" src="/profile/' . $_SESSION['teamID'] . '.png" />';
      }
      if (isset($_GET['print'])) {
        echo '<img style="position:absolute;right:0;top:0px;width:32px;height:32px;" src="/profile/default.png" />';
        echo '<img style="position:absolute;right:0;top:32px;width:32px;height:32px;" src="/3184.png" />';
      }
      echo '
            <h2>Parts requested by '.$_SESSION['teamName'].' ' . (!isset($_GET['print']) ? '&nbsp;
            <a href="history.php?opt=req&print=true">Print</a>' : '') . '</h2>
            <h3>Total: '.$conn->query("SELECT COUNT(*) FROM requests WHERE request_teamID='" . $_SESSION['teamID'] ."'
                                       AND verified=1")->fetchColumn().'</h3>
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
    else {
      include("../lib/dbinfo.php");
      $name = "".$dbHost . "\\" . $dbInstance . ",1433";
      try {
        $conn = new PDO( "mysql:host=$dbHost;dbname=$dbInstance", $dbAccess, $dbAccessPw);
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
      }
      catch (Exception $e) {
        die( print_r( $e->getMessage(), true));
      }

      $result = $conn->query("SELECT * FROM requests WHERE supply_team_id=".$_SESSION['teamID']."
                              ORDER BY request_date DESC");

      $team = $conn->query("SELECT * FROM teams WHERE teamId=".$_SESSION["teamID"])->fetch(PDO::FETCH_ASSOC);
      $usePicture = $team["has_profile_pic"];

      if ($usePicture == 1 && isset($_GET['print'])) {
        echo '<img style="float:left;width:128px;height:128px;" src="/profile/' . $_SESSION['teamID'] . '.png" />';
      }
      if (isset($_GET['print'])) {
        echo '<img style="position:absolute;right:0;top:0;width:32px;height:32px;" src="/profile/default.png" />';
        echo '<img style="position:absolute;right:0;top:32px;width:32px;height:32px;" src="/3184.png" />';

      }

      echo '
            <h2>Requests filled by ' . $_SESSION["teamName"] . ' ' . (!isset($_GET['print']) ? '&nbsp;
            <a href="history.php?opt=filled&print=true">Print</a>' : '') . '</h2>
            <h3>Total: '.$conn->query("SELECT COUNT(*) FROM requests WHERE
                                       supply_team_id='" . $_SESSION['teamID'] . "'")->fetchColumn().'</h3>
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
      if (isset($_GET['print'])) {
        echo '<script type="text/javascript">window.print()</script>';
      }
    }
    ?>

    <?php if (!isset($_GET['print'])) {include '../lib/foot.html'; } ?>
</body>
</html>

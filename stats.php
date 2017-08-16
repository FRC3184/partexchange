<?php include("vars.php"); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Site Statistics</title>

  <?php include("lib/head.html"); ?>
</head>
<body>
  <?php include 'lib/navbar.php'; ?>

  <?php
  include "lib/database.php";
  $conn = db_connect_access();
  $allReq = $conn->query("SELECT COUNT(*) FROM requests WHERE verified=1")->fetchColumn();
  $filled = $conn->query("SELECT COUNT(*) FROM requests WHERE verified=1 AND supply_team_id IS NOT NULL")->fetchColumn();
  $teams = $conn->query("SELECT COUNT(*) FROM teams")->fetchColumn();

  echo $allReq . " parts have been requested. " . $filled .
  " (" . ($allReq != 0 ? (10 * $filled/$allReq) : 0). "%) have been filled. "
  . $teams . " teams have registered for the site.";

  if ($logged and $_SESSION['level'] >= 2) { ?>
  <table class="table table-striped table-hover ">
    <thead>
      <tr>
        <th>Team</th>
        <th>Name</th>
        <th>Email</th>
        <th>Twitter</th>
        <th>Website</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $result = $conn->query("SELECT * FROM teams");
      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";

        echo "<td>" . $row["teamId"] . "</td>";
        echo "<td>Team " . $row["teamName"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $row["twitter"] . "</td>";
        echo "<td>" . $row["website"] . "</td>";

        echo "</tr>";

      }
      ?>

    </tbody>
  </table>
  <?php
  }
  ?>


  <?php include 'lib/foot.html'; ?>
</body>
</html>

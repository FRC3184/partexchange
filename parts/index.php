<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/vars.php"); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Part Requests</title>

  <?php include("../lib/head.html"); ?>
</head>
<body>
  <?php include '../lib/navbar.php'; ?>
  <?php
  require("../lib/database.php");
  $conn = db_connect_access();

  $perPage = 30;
  if (isset($_GET['pp'])) {
    $perPage = $_GET['pp'];
  }
  $maxPages = ceil($conn->query("SELECT COUNT(*) FROM requests WHERE supply_team_id IS NULL AND verified=1")->fetchColumn()/$perPage);

  $start = 0;
  $curPage = 0;
  if (isset($_GET['p'])) {
    $start = $perPage * $_GET['p'];
    $curPage = $_GET['p'];
  }

  if ($curPage != 0 and ($curPage < 0 or $curPage >= $maxPages)) {
    header("Location: index.php");
  }

  $newest = "";
  if ($curPage==0) {
    $newest="disabled";
  }
  $oldest = "";
  if ($curPage==$maxPages-1) {
    $oldest="disabled";
  }

  $optionsPrev="?p=" . ($curPage+1);
  $optionsNext="?p=" . ($curPage-1);
  $options="";
  if (isset($_GET['pp'])) {
    $options = "&pp=" . $perPage;
  }
  if (isset($_GET['like'])) {
    $options .= "&like=" . $_GET['like'];
  }
  if (isset($_GET['team'])) {
    $options .= "&team=" . $_GET['team'];
  }
  if (isset($_GET['region'])) {
    $options .= "&region=" . $_GET['region'];
  }

  echo '<h2>Open Part Requests</h2>
        <ul class="pager">
          <li class="previous ' . $newest .'"><a href="index.php'.$optionsNext.$options.'">← Newer</a></li>
          <li class="next ' . $oldest . '"><a href="index.php'.$optionsPrev.$options.'">Older →</a></li>
        </ul>';
  ?>
  <form method="GET" action="index.php">
    <div class="input-group" style="display: inline-flex; width:100%; justify-content:center;">
      <input value="<?php echo isset($_GET['like']) ? $_GET['like'] : ''; ?>" type="text" name="like"
             class="form-control" style="width: 45%; max-width: 20em;" placeholder="Title" />
      <input value="<?php echo isset($_GET['team']) ? $_GET['team'] : ''; ?>" type="text" name="team"
             class="form-control" style="width: 20%; max-width: 6em;" placeholder="Team #" />
      <select name="region" class="form-control" style="width: 30%; max-width: 9em;">
        <?php
        include "../lib/region.php";
        printRegionSelect(isset($_GET['region']) ? $_GET['region'] : '', True);
        ?>
      </select>
      <?php
      if (isset($_GET['pp'])) {
        echo "<input type='hidden' value='".$perPage."' name='pp'>";
      }
      ?>
      <input type="submit" value="Search" class="btn btn-primary" style="float:left">
    </div>
  </form>
  <table class="table table-striped table-hover ">
    <thead>
      <tr>
        <th>Date</th>
        <th>Requester</th>
        <th>Description</th>
      </tr>
    </thead>
    <tbody>

      <?php
      $search = "%%";
      if (isset($_GET['like']) and $_GET['like']) {
        $search = "%" . $_GET['like'] . "%";
      }
      $level = 0;
      $current_team = NULL;
      if ($logged) {
        $level = $_SESSION['level'];
        $current_team = $_SESSION['teamID'];
      }
      $query_args = array(":search" => $search, ":level" => $level, ":current_team" => $current_team);

      $end = $start + $perPage;

      $team = "";
      $teamvar = "";
      if (isset($_GET['team']) and $_GET['team']) {
        $team = " AND request_teamID=:team";
        $query_args = array_merge($query_args, array(":team" => $_GET['team']));
      }

      $region = "";
      $regionvar = "";
      if (isset($_GET['region']) and $_GET['region']) {
        $region = " AND :region = (SELECT region FROM teams WHERE teamId = request_teamID)";
        $query_args = array_merge($query_args, array(":region" => $_GET['region']));
      }

      $requests_sql = $conn->prepare("SELECT idrequests, verified, request_date, request_teamID, description
                                      FROM requests WHERE supply_team_id IS NULL AND description LIKE :search
                                      AND (verified=1 OR :level >= 1 OR request_teamID=:current_team) $team $region
                                      ORDER BY request_date DESC, idrequests DESC LIMIT $start, $perPage");
      $requests_sql->execute($query_args);
      while($row = $requests_sql->fetch(PDO::FETCH_ASSOC)) {

        printf("<tr class='clickable-row%s' data-href='part.php?id=%d'>",
               ($row['verified'] == 1 ? "" : " unverified"), $row['idrequests']);
        echo "<td>" . $row["request_date"] . "</td>";
        echo "<td>Team " . $row["request_teamID"] . "</td>";
        echo "<td>" . $row["description"] . "</td>";
        echo '<td class="click-indicator">Show More</td>';
        echo "</tr>";
      }
      ?>

    </tbody>
  </table>
  <?php
  echo '
        <ul class="pager">
          <li class="previous ' . $newest .'"><a href="index.php'.$optionsNext.$options.'">← Newer</a></li>
          <li class="next ' . $oldest . '"><a href="index.php'.$optionsPrev.$options.'">Older →</a></li>
          </ul>';
  ?>

  <?php include '../lib/foot.html'; ?>
  <script>
  jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
      window.open($(this).data("href"), "_blank");
    });
  });
  </script>
</body>
</html>

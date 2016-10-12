<!DOCTYPE html>
<html>
<head>
  <title>Part Requests</title>

  <?php include("../lib/head.html"); ?>
</head>
<body>
  <?php include '../lib/navbar.php'; ?>
  <?php
  include("../lib/dbinfo.php");
  $name = "".$dbHost . "\\" . $dbInstance . ",1433";
  try {
    $conn = new PDO( "mysql:host=$dbHost;dbname=$dbInstance", $dbAccess, $dbAccessPw);
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  }
  catch (Exception $e) {
    die( print_r( $e->getMessage(), true));
  }

  $perPage = 100;
  if (isset($_GET['pp'])) {
    $perPage = $_GET['pp'];
  }
  $maxPages = ceil($conn->query("SELECT COUNT(*) FROM requests WHERE supply_team_id IS NULL")->fetchColumn()/$perPage);

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

  echo '
        <ul class="pager">
          <li class="previous ' . $newest .'"><a href="index.php'.$optionsNext.$options.'">← Newer</a></li>
          <li class="next ' . $oldest . '"><a href="index.php'.$optionsPrev.$options.'">Older →</a></li>
        </ul>';
  ?>
  <form method="GET" action="index.php">
    <div class="input-group" style="display: inline-block">
      <input value="<?php echo isset($_GET['like']) ? $_GET['like'] : ''; ?>" type="text" name="like"
             class="form-control" style="width: 55%; max-width: 20em;" placeholder="Title" />
      <input value="<?php echo isset($_GET['team']) ? $_GET['team'] : ''; ?>" type="text" name="team"
             class="form-control" style="width: 20%; max-width: 6em;" placeholder="Team #" />
      <?php
      if (isset($_GET['pp'])) {
        echo "<input type='hidden' value='".$perPage."' name='pp'>";
      }
      ?>
      <input type="submit" value="Search" class="btn btn-primary">
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
      $like = "";
      if (isset($_GET['like']) and $_GET['like']) {
        $like = " AND description LIKE " . $conn->quote("%".$_GET['like']."%");
      }

      $team = "";
      if (isset($_GET['team']) and $_GET['team']) {
        $like = " AND request_teamID=" . $conn->quote($_GET['team']);
      }

      $ver = " AND verified=1";
      if ($logged) {
        $ver = " AND (verified=1 OR ".$_SESSION['level'].">=1 OR request_teamID='".$_SESSION['teamID']."')";
      }

      $result = $conn->query("SELECT * FROM requests WHERE supply_team_id IS NULL".$like.$ver.$team." ORDER BY
      request_date DESC, idrequests DESC LIMIT ".$start.", ".($start+$perPage));
      while($row = $result->fetch(PDO::FETCH_ASSOC)) {

        if ($row["verified"] != 1) {
          echo "<tr class='unverified'>";
        } else {
          echo "<tr>";
        }
          echo "<td>" . $row["request_date"] . "</td>";
          echo "<td>Team " . $row["request_teamID"] . "</td>";
          echo "<td>" . $row["description"] . "</td>";
          echo '<td><a href="part.php?id='.$row['idrequests'].'" target="_blank">See More</a></td>';
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
</body>
</html>

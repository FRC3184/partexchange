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
		$conn = new PDO( "sqlsrv:server=$name;", $dbAccess, $dbAccessPw);
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
            $options="&pp=" . $perPage;
        }
        
        echo '
        <ul class="pager">
          <li class="previous ' . $newest .'"><a href="index.php'.$optionsNext.$options.'">← Newer</a></li>
          <li class="next ' . $oldest . '"><a href="index.php'.$optionsPrev.$options.'">Older →</a></li>
        </ul>
        ';
    ?>
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
        
        
      
        $ver = "WHERE verified=1";
		if ($logged and $_SESSION['level'] >= 1) {
			$ver = "";
		}
        
        $result = $conn->query("
		SELECT *
		FROM (
			SELECT *, ROW_NUMBER() OVER (ORDER BY request_date DESC) AS RowNum
			FROM requests ".$ver."
		) AS MyDerivedTable
		WHERE MyDerivedTable.RowNum BETWEEN ".$start." AND ".($start+$perPage)." AND supply_team_id IS NULL
		");
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            
            if ($logged and $_SESSION['level'] >= 1 && $row["verified"] != 1) {
				echo "<tr class='unverified'>";
			}
			else {
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
        </ul>
        ';
    ?>
    
    <?php include '../lib/foot.html'; ?>
    
    
</body>
</html>
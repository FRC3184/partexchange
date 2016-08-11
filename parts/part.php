<!DOCTYPE html>
<html>
<head>
    <title>View Part Request</title>

    <?php include("../lib/head.html"); ?>
</head>
<body>
    <?php include '../lib/navbar.php'; ?>
        
    <?php 
    if (!isset($_GET['id'])) {
        header("Location: index.php");
    }
    include "../lib/dbinfo.php";
    $name = "".$dbHost . "\\" . $dbInstance . ",1433";
	try {
	$conn = new PDO( "mysql:host=$dbHost;dbname=$dbInstance", $dbAccess, $dbAccessPw);
	$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
	catch (Exception $e) {
		die( print_r( $e->getMessage(), true));
	} 
	
    
	$query = "SELECT * FROM requests WHERE idrequests=" . $conn->quote($_GET['id']) . " AND verified=1;";
	$queryCount = "SELECT COUNT(*) FROM requests WHERE idrequests=" . $conn->quote($_GET['id']) . " AND verified=1;";
	if ($logged and $_SESSION['level'] >= 1) {
		$query = "SELECT * FROM requests WHERE idrequests=" . $conn->quote($_GET['id']);
		$queryCount = "SELECT COUNT(*) FROM requests WHERE idrequests=" . $conn->quote($_GET['id']) . ";";
	}
    $result = $conn->query($query);
    if($conn->query($queryCount)->fetchColumn() == 1){ 
        ?>
        <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">View Part Request</h3>
        </div>
        <div class="panel-body">
        <?php
        $row = $result->fetch(PDO::FETCH_ASSOC);
        
        echo "<h2>".$row["description"]."</h2>";
        echo "<h3>Requested by <a href=\"/account/team.php?id=".$row["request_teamID"]."\">Team ".$row["request_teamID"]."</a> on ".$row['request_date']."</h3>";
        if ($row['supply_team_id'] != '') {
            echo "<h3>Filled by <a href=\"/account/team.php?id=".$row["supply_team_id"]."\">Team ".$row["supply_team_id"]."</a></h3>";
        }
        echo "<br /><p>";
        echo $row['long_description'];
        echo "</p>";
        if ($row["site_url"] != "") {
            echo "<div><a class=\"btn btn-primary\"href=\"" . $row["site_url"] . "\">Part Webpage</a></div>";
        }
        if ($row["image_ext"] != "") {
            echo "<div><img src=\"/images/" . $_GET['id'] . "." . $row['image_ext'] . "\" alt=\"Picture of Part\" title=\"Picture of Part\" class=\"img-thumbnail\" /></div>";
        }
		if ($logged and $_SESSION['level'] >= 1) {
			echo '
			<div>
				<a href="verifyPart.php?id='.$_GET['id'].'&verified=1"
				<button class="btn btn-primary">
				  Verify
				</button>
				</a>
			</div><br />';
		}
        
        if ($logged and $row['supply_team_id'] == '' and $row["request_teamID"] == $_SESSION['teamID']) {?>
            <!-- Button trigger modal -->
		    <div>
		    <a href="/lib/deletePart.php?id=<?php echo $_GET['id']; ?>" class="btn btn-primary" onclick="">
			Delete Listing
                    </a> 
		    </div>
		    <br />
                    <div>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                      Mark Request As Filled
                    </button>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="myModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="modal-form" action="../lib/markForm.php" data-remote="true" method="post">
                                    <div class="modal-header">
                                        <a class="close" data-dismiss="modal">×</a>
                                        <h3>Mark Request As Filled</h3>
                                    </div>
                                    <div class="modal-body">
                                          <!--<label for="inputEmail" class="col-lg-2 control-label">Team #</label>
                                          <div class="col-lg-10">-->
                                            <input type="text" name="username" class="form-control" id="inputEmail" placeholder="Team #" autocomplete="off" style="cursor: auto; background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3QsPDhss3LcOZQAAAU5JREFUOMvdkzFLA0EQhd/bO7iIYmklaCUopLAQA6KNaawt9BeIgnUwLHPJRchfEBR7CyGWgiDY2SlIQBT/gDaCoGDudiy8SLwkBiwz1c7y+GZ25i0wnFEqlSZFZKGdi8iiiOR7aU32QkR2c7ncPcljAARAkgckb8IwrGf1fg/oJ8lRAHkR2VDVmOQ8AKjqY1bMHgCGYXhFchnAg6omJGcBXEZRtNoXYK2dMsaMt1qtD9/3p40x5yS9tHICYF1Vn0mOxXH8Uq/Xb389wff9PQDbQRB0t/QNOiPZ1h4B2MoO0fxnYz8dOOcOVbWhqq8kJzzPa3RAXZIkawCenHMjJN/+GiIqlcoFgKKq3pEMAMwAuCa5VK1W3SAfbAIopum+cy5KzwXn3M5AI6XVYlVt1mq1U8/zTlS1CeC9j2+6o1wuz1lrVzpWXLDWTg3pz/0CQnd2Jos49xUAAAAASUVORK5CYII=); background-attachment: scroll; background-position: 100% 50%; background-repeat: no-repeat;">
                                          <!--</div>-->
                                          <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                        <input type="submit" value="Mark As Filled" class="btn btn-primary" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    </div>
                    <?php  
        }
    }
    else {
        echo "Listing not found.";
    }
    ?>
    
    <?php include '../lib/foot.html'; ?>
</body>
</html>

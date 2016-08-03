<!DOCTYPE html>
<html>
<head>
    <?php
        
        $teamName = $email = $teamId = $teamTwitter = $pic = $website = NULL;
        include("../lib/vars.php");
        include("../lib/dbinfo.php");
        if (!$logged) {
            header("Location: login.php");
        }
        $name = "".$dbHost . "\\" . $dbInstance . ",1433";
		try {
		$conn = new PDO( "mysql:host=$dbHost;dbname=$dbInstance", $dbAccess, $dbAccessPw);
		$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		}
		catch (Exception $e) {
			die( print_r( $e->getMessage(), true));
		} 
        $sql = $conn->query("SELECT * FROM teams 
        WHERE teamId=".$conn->quote($_SESSION['teamID'])); 
        if($conn->query("SELECT COUNT(*) FROM teams 
        WHERE teamId=".$conn->quote($_SESSION['teamID']))->fetchColumn() == 1){ 
            $row = $sql->fetch();
            global $teamName, $email, $teamId, $teamTwitter, $pic, $website;
            $teamName = $row['teamName'];
            $email = $row['email'];
            $teamId = $row['teamId'];
            $teamTwitter = $row['twitter'];
            $teamWebsite = $row['website'];
            if ($row['has_profile_pic'] == 0) {
                $pic = "../profile/default.png";
            } else {
                $pic = "../profile/".$teamId.".png";
            }
        }
    ?>
    <title>Your Account</title>

    <?php include("../lib/head.html"); ?>
</head>
<body>
    <?php include '../lib/navbar.php'; ?>
        
	<?php
        if (isset($_GET['err'])) {
            if ($_GET['err'] == 0) {
            echo '
                <div class="alert alert-dismissable alert-danger">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Error:</strong> Email is invalid
                </div>';
            }
			else if ($_GET['err'] == 1) {
            echo '
                <div class="alert alert-dismissable alert-danger">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Error:</strong> File upload error
                </div>';
            }
			else if ($_GET['err'] == 2) {
            echo '
                <div class="alert alert-dismissable alert-danger">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Error:</strong> Incorrect password/Passwords do not match
                </div>';
            }
			else  {
            echo '
                <div class="alert alert-dismissable alert-danger">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Error:</strong> File upload error
                </div>';
            }
        }
        ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo $teamName; ?></h3>
        </div>
        <div class="panel-body">
              <img class="img-thumbnail" src="<?php echo $pic; ?>" alt="Profile Picture" title="Profile Picture" style="width:256px;height:256px;float:left;"/>
              <div style="float:right;display:block;">
              <form role="form" id="changeActInfoForm" action="update.php" method="post" enctype="multipart/form-data">
              <div class="panel-group" id="accordion">			      
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                          Email: <?php echo $email; ?>
                        </a>
                      </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse">
                      <div class="panel-body">
                        <div class="form-group">
                            <input name="email" name="emailAddress" type="email" class="form-control" id="email" placeholder="Email Address">
                          </div>
                      </div>
                    </div>
                  </div>
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                          <?php
                          if ($teamTwitter !== NULL) {
                            echo "Twitter: @". $teamTwitter;
                          }
                          else {
                            echo "Twitter: <i>Not Set.</i>";
                          }
                          ?>
                        </a>
                      </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse">
                      <div class="panel-body">
                        
                            <div class="form-group">
                                <input type="text" name="twitter" class="form-control" id="twitter" title="Omit @" placeholder="Enter Twitter Handle">
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                          <?php
                          if ($teamWebsite !== NULL) {
                            echo "Website: ". $teamWebsite;
                          }
                          else {
                            echo "Website: <i>Not Set.</i>";
                          }
                          ?>
                        </a>
                      </h4>
                    </div>
                    <div id="collapseThree" class="panel-collapse collapse">
                      <div class="panel-body">
                        <div class="form-group">
                                <input type="text" name="website" class="form-control" id="website" title="Please include http://" placeholder="Enter Website">
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#changePicture">
                            Picture
                        </a>
                      </h4>
                    </div>
                    <div id="changePicture" class="panel-collapse collapse">
                      <div class="panel-body">
                        <div class="form-group">
                                <input type="file" accept="image/*" name="picture" class="form-control" id="picture" title="Please keep your profile picture small">
                            </div>
                        </div>
                    </div>
                  </div>
				  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#changePassword">
                            Password
                        </a>
                      </h4>
                    </div>
                    <div id="changePassword" class="panel-collapse collapse">
                      <div class="panel-body">
                        <div class="form-group">
                                <label for="oldpass">Old Password</label><input type="password" name="oldpass" class="form-control" id="oldpass">
								<label for="newpass">New Password</label><input type="password" name="newpass" class="form-control" id="newpass">
								<label for="confirmpass">Confirm New Password</label><input type="password" name="confirmpass" class="form-control" id="confirmpass">
                            </div>
                        </div>
                        </div>
                    </div>
                  </div>
                
                <input name="submt" type="submit" value="Update" class="btn btn-primary" />
                </form>
                <a href="team.php?id=<?php echo $_SESSION['teamID']; ?>">Public Profile</a>
				</div>
        </div>
    </div>
    
    
    <?php include '../lib/foot.html'; ?>
    
    <script type="text/javascript">
        $("#twitter").tooltip({"trigger":"focus hover"});
        $("#website").tooltip({"trigger":"focus hover"});
        $(document).ready(function() {
    $('#changeActInfoForm').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            email: {
                validators: {
                    emailAddress: {
                        message: 'The email address is not valid'
                    }
                }
            },
            website: {
                validators: {
                    uri: {
                        message: 'Not a valid URL'
                    }
                }
            }
        }
    });
});
    </script>
</body>
</html>
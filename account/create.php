<!DOCTYPE html>
<html>
<head>
  <title>Create an Account</title>

  <?php include("../lib/head.html"); ?>
  <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
  <?php include '../lib/navbar.php'; ?>

  <?php
  function print_error($message) {
    echo '
      <div class="alert alert-dismissable alert-danger">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Error:</strong> '.$message.'
      </div>';
  }

  if (isset($_GET['err'])) {
    if ($_GET['err'] == "0") {
      print_error("Passwords do not match.");
    }
    if ($_GET['err'] == "1") {
      print_error("Email is not valid.");
    }
    if ($_GET['err'] == "2") {
      print_error("Team number is invalid.");
    }
    if ($_GET['err'] == "3") {
      print_error("An account with this team number already exists.");
    }
    if ($_GET['err'] == "4") {
      print_error("Please retry the captcha.");
    }
  }
  ?>

  <form id="createActForm" role="form" action="../lib/do_create.php" method="post" style="width:50%;">
    <div class="form-group">
      <label for="inputTeam">Team Number</label>
      <input required pattern="\d+" name="teamNumber" type="text" class="form-control" id="inputTeam" placeholder="Team Number">
      <div class="help-block with-errors"></div>
    </div>
    <div class="form-group">
      <label for="inputTeamName">Team Name</label>
      <input required name="teamName" type="text" class="form-control" id="inputTeamName" placeholder="Team Name">
      <div class="help-block with-errors"></div>
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">Email Address</label>
      <input required name="email" type="email" class="form-control" id="exampleInputEmail1" placeholder="Email Address">
      <div class="help-block with-errors"></div>
    </div>
    <div class="form-group">
      <label for="password1">Password</label>
      <input required data-minlength="5" name="password1" type="password" class="form-control" id="password1" placeholder="Password">
      <div class="help-block with-errors"></div>
      <label for="password2">Repeat Password</label>
      <input required data-match="#password1" name="password2" type="password" class="form-control" id="password2" placeholder="Repeat Password">
      <div class="help-block with-errors"></div>
    </div>
    <div class="form-group">
      <div class="g-recaptcha" data-sitekey="6LcXGfwSAAAAACkoABhkFZWun5IgorYz0qgysE0K"></div>
    </div>
    <div class="form-group">
      <span id="tos-warning">By clicking "Create Account" you agree to the <a href="/tos.php">Terms of Service</a> for the site</span>
    </div>
    <div class="col-lg-10 col-lg-offset-2">
          <input name="submt" type="submit" value="Create Account" class="btn btn-primary" />
      </div>
  </form>

  <?php include '../lib/foot.html'; ?>
  <script type="text/javascript" src="/js/validator.min.js"></script>

  <script type="text/javascript">
    $("#createActForm").validator();
  </script>
</body>
</html>

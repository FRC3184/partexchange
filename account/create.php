<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/vars.php"); ?>
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
    if ($_GET['err'] == "5") {
      print_error("Invalid district/region selected.");
    }
  }
  ?>

  <form id="createActForm" role="form" action="../lib/do_create.php" method="post">
    <div class="form-group">
      <label for="inputTeam">Team Number</label>
      <input required pattern="\d+" name="teamNumber" type="text" class="form-control" id="inputTeam" placeholder="Team Number">
      <div class="help-block with-errors"></div>
    </div>
    <div class="form-group">
      <label for="inputRegion">Region</label>
      Choose district, state/province, or country.
      <select required name="region" class="form-control" id="inputRegion">
        <?php
        include "../lib/region.php";
        printRegionSelect("", False);
        ?>
      </select>
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
      <span id="tos-warning">By clicking "Create Account" you agree to the <a href="/tos.php">Terms of Service</a> for the site</span>
    </div>
    <div class="form-group">
      <button class="btn btn-primary g-recaptcha" data-sitekey="6LdmZSsUAAAAAKASLeW7JE5w7M1F-5eYxZMbVe8G"
              data-callback="submit_form">Create Account</button>
    </div>
    <script>
    function submit_form() {
      $("#createActForm").submit();
    }
    </script>
  </form>

  <?php include '../lib/foot.html'; ?>
  <script type="text/javascript" src="/js/validator.min.js"></script>

  <script type="text/javascript">
    $("#createActForm").validator();
  </script>
</body>
</html>

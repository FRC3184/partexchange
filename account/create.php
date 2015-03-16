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
        if (isset($_GET['err'])) {
            if ($_GET['err'] == "0") {
            echo '
                <div class="alert alert-dismissable alert-danger">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Error:</strong> Passwords do not match.
                </div>';
            }
            if ($_GET['err'] == "1") {
            echo '
                <div class="alert alert-dismissable alert-danger">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Error:</strong> Email is not valid.
                </div>';
            }
            if ($_GET['err'] == "2") {
            echo '
                <div class="alert alert-dismissable alert-danger">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Error:</strong> Team number is not acceptable.
                </div>';
            }
            if ($_GET['err'] == "3") {
            echo '
                <div class="alert alert-dismissable alert-danger">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Error:</strong> Team number has been taken.
                </div>';
            }
            if ($_GET['err'] == "4") {
            echo '
                <div class="alert alert-dismissable alert-danger">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Captcha Error:</strong> '.($_GET['txt'] === "invalid-input-response" ? "Please retry the captcha" : $_GET['txt']).'
                </div>';
            }
        }
        ?>
        
    <form id="createActForm" role="form" action="../lib/do_create.php" method="post" style="width:50%;">
      <div class="form-group">
        <label for="inputTeam">Team Number</label>
        <input name="teamNumber" type="text" class="form-control" id="inputTeam" placeholder="Team Number">
      </div>
      <div class="form-group">
        <label for="inputTeamName">Team Name</label>
        <input name="teamName" type="text" class="form-control" id="inputTeamName" placeholder="Team Name">
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1">Email Address</label>
        <input name="email" type="email" class="form-control" id="exampleInputEmail1" placeholder="Email Address">
      </div>
      <div class="form-group">
        <label for="password1">Password</label>
        <input name="password1" type="password" class="form-control" id="password1" placeholder="Password">
        <label for="password2">Repeat Password</label>
        <input name="password2" type="password" class="form-control" id="password2" placeholder="Repeat Password">
      </div>
      <div class="form-group">
        <div class="g-recaptcha" data-sitekey="6LcXGfwSAAAAACkoABhkFZWun5IgorYz0qgysE0K"></div>
      </div>
      <div class="col-lg-10 col-lg-offset-2">
            <input name="submt" type="submit" value="Create Account" class="btn btn-primary" />
        </div>
    </form>
    
    <?php include '../lib/foot.html'; ?>
    
    <script type="text/javascript">
    $(document).ready(function() {
    $('#createActForm').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            teamNumber: {
                message: 'The team number is not valid',
                validators: {
                    notEmpty: {
                        message: 'The team number is required and cannot be empty'
                    },
                    regexp: {
                        regexp: /\d+/,
                        message: 'The team number can only consist of numbers'
                    },
                    different: {
                        field: 'password1',
                        message: 'The username and password cannot be the same as each other'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'The email address is required and cannot be empty'
                    },
                    emailAddress: {
                        message: 'The email address is not valid'
                    }
                }
            },
            password1: {
                validators: {
                    notEmpty: {
                        message: 'The password is required and cannot be empty'
                    },
                    different: {
                        field: 'username',
                        message: 'The password cannot be the same as username'
                    },
                    stringLength: {
                        min: 3,
                        message: 'The password must have at least 3 characters'
                    },
                    identical: {
                        field: 'password2',
                        message: 'Passwords must match'
                    }
                }
            },
            password2: {
                validators: {
                    notEmpty: {
                        message: 'The password is required and cannot be empty'
                    },
                    different: {
                        field: 'username',
                        message: 'The password cannot be the same as username'
                    },
                    stringLength: {
                        min: 3,
                        message: 'The password must have at least 3 characters'
                    },
                    identical: {
                        field: 'password1',
                        message: 'Passwords must match'
                    }
                }
            },
            teamName: {
                validators: {
                    notEmpty: {
                        message: 'The team name is required and cannot be empty'
                    },
                    stringLength: {
                        min: 3,
                        message: 'The team name must have at least 3 characters'
                    }
                }
            }
        }
    });
});
    </script>
</body>
</html>
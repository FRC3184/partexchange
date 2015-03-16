<!DOCTYPE html>
<html>
<head>
    <title>Request a Part</title>

    <?php include("../lib/head.html"); ?>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
    <?php include '../lib/navbar.php'; ?>
    <?php 
    if (!$logged) {
        echo '<div class="panel panel-warning">
                  <div class="panel-heading">
                    <h3 class="panel-title">Warning</h3>
                  </div>
                  <div class="panel-body">
                    You must be logged in to request a part.
                  </div>
                </div>';
    }
    else { ?>
        <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Request a Part</h3>
        </div>
        <div class="panel-body">
        <?php
        if (isset($_GET['err'])) {
            if ($_GET['err'] == "0") {
            echo '
                <div class="alert alert-dismissable alert-danger">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Error:</strong> Please enter a title
                </div>';
            }
            if ($_GET['err'] == "1") {
            echo '
                <div class="alert alert-dismissable alert-danger">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Captcha Error:</strong> '.($_GET['txt'] === "invalid-input-response" ? "Please retry the captcha" : $_GET['txt']).'
                </div>';
            }
        }
        ?>
        <form action="../lib/postPart.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <input placeholder="Title" name="shortDesc" type="text" class="form-control" id="inputSDesc" autocomplete="off">
              
            </div>
        
            <div class="form-group">
                <textarea placeholder="Description" name="longDesc" class="form-control" rows="3" id="textArea"></textarea>
              
            </div>
            <div class="form-group">
                <input placeholder="Link to website (not required)" name="partURL" type="text" class="form-control" id="partURL" autocomplete="off">
              
            </div>
            <div class="form-group">
                <input type="file" name="image" class="form-control" id="image-upload" accept="image/*">
              
            </div>
            <div class="form-group">
              <div class="g-recaptcha" data-sitekey="6LcXGfwSAAAAACkoABhkFZWun5IgorYz0qgysE0K"></div>
            </div>
            
            <div class="col-lg-10 col-lg-offset-2">
                <input name="submit" type="submit" class="btn btn-primary" />
            </div>
        </form>
    </div>
    <?php
    }
    ?>
    
    
    
    <?php include '../lib/foot.html'; ?>
</body>
</html>
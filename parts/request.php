<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/vars.php"); ?>
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
                    <strong>Error:</strong> Please enter a title.
                  </div>';
          }
          if ($_GET['err'] == "1") {
            echo '
                  <div class="alert alert-dismissable alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Error:</strong> File not recognized as an image.
                  </div>';
          }
          if ($_GET['err'] == "2") {
            echo '
                  <div class="alert alert-dismissable alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Error:</strong> Captcha failed.
                  </div>';
          }
          if ($_GET['err'] == "3") {
            echo '
                  <div class="alert alert-dismissable alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Error:</strong> File too large
                  </div>';
          }
        }
        ?>
        <form action="../lib/postPart.php" method="post" enctype="multipart/form-data" id="part_form">
          <div class="form-group">
            <input required placeholder="Title" name="shortDesc" type="text" class="form-control" id="inputSDesc" autocomplete="off">
            <div class="help-block with-errors"></div>
          </div>

          <div class="form-group">
            <textarea placeholder="Description" name="longDesc" class="form-control" rows="3" id="textArea"></textarea>

          </div>
          <div class="form-group">
            <input placeholder="Link to website (not required)" name="partURL" type="url" class="form-control" id="partURL" autocomplete="off">
            <div class="help-block with-errors"></div>
          </div>
          <div class="form-group">
            <?php
            function return_bytes($val) {
              $val = trim($val);
              $last = strtolower($val[strlen($val)-1]);
              switch($last) {
                  // The 'G' modifier is available since PHP 5.1.0
                  case 'g':
                      $val *= (1024 * 1024 * 1024); //1073741824
                      break;
                  case 'm':
                      $val *= (1024 * 1024); //1048576
                      break;
                  case 'k':
                      $val *= 1024;
                      break;
              }

              return $val;
            }
            $pretty_size = ini_get('post_max_size');
            $bytes = return_bytes($pretty_size);
            ?>
            <a class="btn btn-primary" id="btn-attach">Attach Image (<?php echo $pretty_size ?> max)</a>
            <input data-maxsize="<?php echo $bytes; ?>" type="file" name="image" class="form-control" id="image-upload" accept="image/*">
            <div class="help-block with-errors"></div>
            <div class="img-preview-block">
              <img id="preview" />
            </div>
          </div>

          <div class="col-lg-10 col-lg-offset-2">
            <button class="btn btn-primary g-recaptcha" data-sitekey="6LdmZSsUAAAAAKASLeW7JE5w7M1F-5eYxZMbVe8G"
                    data-callback="submit_form">Submit</button>
          </div>
          <script>
          function submit_form() {
            $("#part_form").submit();
          }
          </script>
        </form>
      </div>
  <?php
  }
  ?>



  <?php include '../lib/foot.html'; ?>

  <script type="text/javascript" src="/js/validator.min.js"></script>

  <script type="text/javascript">
    $("#part_form").validator({custom: {
      maxsize: function($el) {
        var maxSize = parseInt($el.data("maxsize"));
        if ($el[0].files[0].size > maxSize) {
          return "File too large";
        }
      }
    }});

    $("#btn-attach").click(function() {
      var $fileobj = $("#image-upload");
      if ($fileobj[0].files.length == 0) {
        $fileobj.trigger("click");
      }
      else {
        $fileobj.val("");
        $fileobj.trigger("change");
      }
    });

    $("#image-upload").change(function() {
      var $fileobj = $("#image-upload");
      var $btn = $("#btn-attach");
      var $preview = $("#preview");
      var $preview_block = $preview.parent();
      if ($fileobj[0].files.length > 0) {
        $btn.html("Remove Image");
        $preview.attr("src", URL.createObjectURL($fileobj[0].files[0]));
        $preview_block.css("display", "block");
      }
      else {
        $btn.html("Attach Image (4MB max)");
        $preview.attr("src", "");
        $preview_block.css("display", "none");
      }
    });
  </script>
</body>
</html>

<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/vars.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Internal Server Error</title>

    <?php include("../lib/head.html"); ?>
</head>
<body>
    <?php include '../lib/navbar.php'; ?>

      <div class="jumbotron">
        <h1>500 Internal Server Error</h1>
        <p class="lead">All errors are reported to the webmaster. You can also create a ticket on
          <a href="https://github.com/FRC3184/partexchange/issues">GitHub.</a></p>
      </div>



    <?php include '../lib/foot.html'; ?>
</body>
</html>

<?php
//Do all this after the page is generated so that any errors in the email code still show a 500 page
include '../lib/mail.php';
$email = setupMail();
$email->setFrom("frc.parts.exchange@gmail.com");
foreach ($mailRecipients as $addr) {
  $email->addAddress($addr);
}
$email->isHTML(true);

$email->Subject = "Error on parts.blazerobotics.org";
$email->Body = sprintf("URL: %s<br/>Query String: %s", $_SERVER['REDIRECT_URL'], $_SERVER['REDIRECT_QUERY_STRING']);

$res = send($email);
?>

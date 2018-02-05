<?php
require 'PHPMailer/PHPMailerAutoload.php';
include 'mailVars.php';

function setupMail() {
  global $mailHost, $mailUser, $mailPass, $mailPort;

  $mail = new PHPMailer;

  $mail->SMTPDebug = 0;                               // Disable debug

  $mail->isSMTP();                                      // Set mailer to use SMTP
  $mail->Host = $mailHost;  // Specify main and backup SMTP servers
  $mail->SMTPAuth = true;                               // Enable SMTP authentication
  $mail->Username = $mailUser;                 // SMTP username
  $mail->Password = $mailPass;                           // SMTP password
  $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
  $mail->Port = $mailPort;  // TCP port to connect to
  $mail->setFrom($mailUser);
  return $mail;
}

$unsubscribe_info = "<br />You can disable these emails on your <a href='https://parts.blazerobotics.org/account'>account settings page</a>.";

function send($mail) {
  if(!$mail->send()) {
      return $mail->ErrorInfo;
  } else {
        return null;
  }
}

<?php
if (PHP_SAPI !== 'cli') {
  header("Location: /");  // Only run on the command line
  exit;
}

require "lib/database.php";
require "lib/mail.php";
$conn = db_connect_access();

// For each user, find what parts were posted in their region
// We could do this by each region, but this doesn't need to be very efficient and this is simpler

$teams = $conn->query("SELECT teamId, email, region FROM teams WHERE gets_emails=1 AND region IS NOT NULL");

$count_requests_sql = $conn->prepare("SELECT COUNT(*), COUNT(supply_team_id)
        FROM requests WHERE :region = (SELECT region FROM teams WHERE teamId = request_teamID)
        AND request_date > DATE_SUB(CURDATE(), INTERVAL 1 WEEK) AND verified=1");

while($team = $teams->fetch(PDO::FETCH_ASSOC)) {
  //Find total # of parts posted in this region and number unfilled

  $region = $team['region'];
  $count_requests_sql->execute(array(':region' => $region));
  $count_requests = $count_requests_sql->fetch(PDO::FETCH_ASSOC);
  $total = $count_requests['COUNT(*)'];
  $filled = $count_requests['COUNT(supply_team_id)'];
  $unfilled = $total - $filled;

  $email = setupMail();
  $email->addAddress($team['email']);
  $email->isHTML(true);

  $email->Subject = "FRC Part Exchange Weekly Digest";
  $email->Body = sprintf("
  Last week, %d parts were requested in your region. %d of them have not been filled.<br />
  <a href='https://parts.blazerobotics.org/parts/?region=%s'>View open part requests in your region.</a><br />.
  " . $unsubscribe_info, $total, $unfilled, $region);

  $res = send($email);
  if ($res) {
    die($res);
  }
}

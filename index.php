<!DOCTYPE html>
<html>
<head>
    <title>FRC Parts Exchange</title>

    <?php include("lib/head.html"); ?>
</head>
<body>
    <?php include 'lib/navbar.php'; ?>

    <!-- Jumbotron -->
    <?php if (!$logged) { ?>
      <div class="jumbotron">
        <h1>FRC Parts Exchange</h1>
        <p class="lead">This is a site designed to encourage cooperation between FRC teams by lending parts. Built by Team 3184 Blaze Robotics, the site is currently aimed at Minnesota teams.</p>
        <p><a class="btn btn-lg btn-success" href="/getting_started.php" role="button">Get Started &raquo;</a></p>
      </div>


      <div class="row">
        <div class="col-lg-4">
          <h2>Why?</h2>
          <b>To encourage "coopertition" between FRC teams.</b>
          <p>Many FRC teams exchange parts both during and outside of competitions. We wanted to help be giving everyone a place to easily do that and keep track of it.</p>
          <p><a class="btn btn-primary" href="http://www.firstinspires.org/about/vision-and-mission" role="button">Read More &raquo;</a></p>
        </div>
        <div class="col-lg-4">
            <a href="http://www.firstinspires.org/robotics/frc"><img src="/profile/default.png" alt="FIRST Logo" /></a>
        </div>
        <div class="col-lg-4">
            <a href="http://blazerobotics.org"><img height="256" width="256" src="/3184.png" alt="Blaze Robotics" /></a>
        </div>
      </div>
      <?php } else { ?>
        <h1>Welcome Back, <?php echo $_SESSION["teamName"]; ?></h1><br />
        <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Dashboard</h3>
        </div>
        <div class="panel-body">
        <div class="row">
        <div class="col-sm-4">
          <p><a class="btn btn-primary" href="/parts/index.php" role="button">View Open Requests &raquo;</a></p>
          <p><a class="btn btn-primary" href="/parts/request.php" role="button">Request a Part &raquo;</a></p>
        </div>
        <div class="col-sm-4">
          <p><a class="btn btn-primary" href="/account/index.php" role="button">Account Settings &raquo;</a></p>
          <p><a class="btn btn-primary" href="/account/history.php?opt=req" role="button">View Requested Parts &raquo;</a></p>
          <p><a class="btn btn-primary" href="/account/history.php?opt=filled" role="button">View Filled Requests &raquo;</a></p>
          <p><a class="btn btn-danger" href="/account/logout.php" role="button">Log Out &raquo;</a></p>
        </div>
      </div>
      </div>
      <?php } ?>

    <?php include 'lib/foot.html'; ?>
</body>
</html>

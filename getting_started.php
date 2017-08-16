<?php include("vars.php"); ?>
<!DOCTYPE html>
<html>
<head>
  <title>FRC Parts Exchange</title>

  <?php include("lib/head.html"); ?>
</head>
<body>
  <?php include 'lib/navbar.php'; ?>

  <div role="main">
    <div class="page-header">
      <h1>Getting Started</h1>
    </div>
    <h3>Introduction</h3>
    <p id="intro">You can also download a PowerPoint presentation about the site <a href="/FRC_Parts_Exchange.pptx">here</a>.</p>
    <h3>Create an Account</h3>
    <p id="create-act">First, you will need to create an account for your team. Only one account per team is required.<br /><a href="/account/create.php">Create an account</a></p>
    <h3>View Open Requests</h3>
    <p id="view-open">Now that you are logged in, you can request parts and view other requests. You can view open requests by clicking <a href="/parts/index.php">here</a> or the link at the top of the page.</p>
    <h3>Request a Part</h3>
    <p id="request-prt">You can request parts by clicking <a href="/parts/request.php">here</a> or the link at the top of the page.
      Note that you can add the title of your request, a description of the required part, a link to the manufacturer's website, and a picture of the part.
      <br /><br />When another team gets you the part, make sure to mark it as filled so they get credit.</p>
  </div>

  <?php include 'lib/foot.html'; ?>
</body>
</html>

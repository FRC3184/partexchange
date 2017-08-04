<!-- Static navbar -->
<div class="navbar navbar-default navbar-static-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">FRC Parts Exchange</a>
    </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li><a href="/">Home</a></li>
        <li><a href="/parts/">Part Requests</a></li>
        <li><a href="/parts/request.php">Request a Part</a></li>
        <?php
        include("vars.php");
        if($logged){
          echo '<li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$_SESSION['teamName'].' <b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <li><a href="/account/">Account</a></li>
                    <li><a href="/parts/?team='.$_SESSION['teamID'].'">Open Requests</a></li>
                    <li><a href="/account/history.php?opt=req">Filled Requests</a></li>
                    <li><a href="/account/history.php?opt=filled">Requests You Filled</a></li>
                    <li class="divider"></li>
                    <li><a href="/account/logout.php">Log Out</a></li>
                  </ul>
                </li>';
        }
        else {
          echo '<li><a href="/account/login.php">Login</a></li>';
        }
        ?>

      </ul>
    </div><!--/.nav-collapse -->
  </div>
</div>
<div id="wrap">
<section id="main" class="container clear-top">

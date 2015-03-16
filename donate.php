<!DOCTYPE html>
<html>
<head>
    <title>Donate to the FRC Parts Exchange</title>

    <?php include("lib/head.html"); ?>
</head>
<body>
    <?php include 'lib/navbar.php'; ?>
        
    <div class="jumbotron">
        <h1>Donate</h1>
        <p>Thanks for your interest in donating. We can accept either parts to send out to teams, or money to keep the site running and improving.</p>
        <p>To donate parts, please contact <a href="mailto://s_lenhardt@yahoo.com">Sean Lenhardt</a>. If you would like to donate money, the button below will allow you to donate through PayPal.</p>
        <p>
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="43X7EVHNMFN9A">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
        </p>
      </div>
    
    <?php include 'lib/foot.html'; ?>
</body>
</html>
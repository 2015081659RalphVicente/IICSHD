<?php 
    require './include/controller.php';
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Log In - IICS Help Desk </title>
        <link rel="shortcut icon" href="img/favicon.ico">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <!-- form start -->
        <div class="container">
            <div class="card card-container">
                <center><h2>IICS HD</h2></center>
                <center><p><h5>Log-In</h5></p></center>
                <form class="form-signin" action="" method="POST">
                    <span id="reauth-email" class="reauth-email"></span>
                    <p><input type="text" id="inputEmail" class="form-control" placeholder="Username" name="userid" autofocus></p>
                    <p><input type="password" id="inputPassword" class="form-control" placeholder="Password" name = "password"></p>
                    <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="login">Log-In</button>
                </form><!-- /form -->
                <a href="#" class="forgot-password">
                    Forgot Password?
                </a>
                <a href="#" class="forgot-password">
                    New User?
                </a> 
                
            </div><!-- /card-container -->
        </div><!-- /container -->
        <!-- form end -->
        
    </body>
</html>
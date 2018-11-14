<?php
require './include/controller.php';
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Log In - IICS Help Desk </title>
        <link rel="shortcut icon" href="img/favicon.png">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body>

        <div class="container-fluid header">
            &nbsp;
        </div>
        <div class="container-fluid headerline">
            &nbsp;
        </div>


        <br>
        <!-- form start -->
        <div align="center" class="container">

            <center><img src="img/logo2.png"></center>
            <div align="center" class="container-fluid card card-container">
                <form class="form-signin" action="" method="POST">
                    <span id="reauth-email" class="reauth-email"></span>
                    <p><input type="text" id="inputEmail" class="form-control" placeholder="Username" name="userid" autofocus></p>
                    <p><input type="password" id="inputPassword" class="form-control" placeholder="Password" name = "password"></p>
                    <button class="btn btn-lg btn-success btn-block btn-signin" type="submit" name="login">Log-In</button>
                </form><!-- /form -->
                <a href="#" class="forgot-password">
                    Forgot Password?
                </a>
                <a href="register.php" class="forgot-password">
                    New User?
                </a>
            </div>

        </div><!-- /container -->
        <br>
    <center><a href="user/admin/home.php">Admin Preview</a> |
        <a href="user/faculty/home.php">Faculty Preview</a> |
        <a href="user/student/home.php">Student Preview</a></center>
    <!-- form end -->
    <br>

    <div class="container-fluid headerline">
        &nbsp;
    </div>
    <div class="container-fluid footer">
        &nbsp;
    </div>

</body>
</html>
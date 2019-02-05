<?php
require './include/controller.php';

if (isset($_SESSION['resetpass']) && $_SESSION['resetpass'] == 0) {
    if (isset($_SESSION['user_name']) && ($_SESSION['role'] == "admin")) {
        header("location:/iicshd/user/admin/home.php");
    } elseif (isset($_SESSION['user_name']) && $_SESSION['role'] == "student") {
        header("location:/iicshd/user/student/home.php");
    } elseif (isset($_SESSION['user_name']) && $_SESSION['role'] == "faculty") {
        header("location:/iicshd/user/faculty/home.php");
    }
} elseif (isset($_SESSION['resetpass']) && $_SESSION['resetpass'] == 1) {
    session_unset();
    session_destroy();
}

unset($_SESSION['seq']);
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>IICS Help Desk - Log In</title>
        <link rel="shortcut icon" href="img/favicon.png">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">

        <style>
            .container{
                height: 100%;
            }
        </style>
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
                <?php echo $registerSuccess; ?>
                <form class="form-signin" action="" method="POST">
                    <span id="reauth-email" class="reauth-email"></span>
                    <p><input type="text" id="inputEmail" class="form-control" placeholder="Student Number / Faculty Number" name="userid" autofocus></p>
                    <p><input type="password" id="inputPassword" class="form-control" placeholder="Password" name = "password"><?php echo $passwordErr; ?></p>
                    <button class="btn btn-lg btn-success btn-block btn-signin" type="submit" name="login">Log-In</button>
                </form><!-- /form -->
                <a href="forgot.php" class="forgot-password">
                    Forgot Password?
                </a>
                <a href="register.php" class="forgot-password">
                    New User?
                </a>
            </div>

        </div><!-- /container -->
        <br>
        <!-- form end -->

        <div class="container-fluid headerline">
            &nbsp;
        </div>
        <div class="container-fluid footer">
            &nbsp;
        </div>

    </body>
</html>
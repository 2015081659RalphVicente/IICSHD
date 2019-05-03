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
}

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

            <center><img src="img/logo3_3.png"></center>
            <div align="center" class="container-fluid card card-container">
                <h6 style="text-align: center;"><p>Your password was reset. <br> Please input a new password.</p></h6>  
                <form class="form-signin" action="" method="POST">
                    <span id="reauth-email" class="reauth-email"></span>
                    <input class="form-control" type="password" placeholder="New Password" name="newPassword" required><?php echo $resetpasswordErr; ?><br>
                    <input class="form-control" type="password" placeholder="Confirm New Password" name="confirm" required><?php echo $confirmErr; ?><br>
                    <button class="btn btn-lg btn-success btn-block btn-signin" type="submit" name="resetlogin">Submit</button>
                </form><!-- /form -->
            </div>

        </div><!-- /container -->
        <br>
        <br>
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
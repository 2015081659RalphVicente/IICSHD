<?php
require './include/controller.php';

if (isset($_SESSION['user_name']) && $_SESSION['role'] == "admin") {
    header("location:/iicshd/user/admin/home.php");
}
if (isset($_SESSION['user_name']) && $_SESSION['role'] == "faculty") {
    header("location:/iicshd/user/faculty/home.php");
}
if (isset($_SESSION['user_name']) && $_SESSION['role'] == "student") {
    header("location:/iicshd/user/student/home.php");
}
if (isset($_SESSION['user_name'])) {

    if ((time() - $_SESSION['last_time']) > 1200) {
        header("Location:../../logout.php");
    } else {
        $_SESSION['last_time'] = time();
    }
}

if (!isset($_SESSION['user_name'])) {
    if ($_SESSION['param'] == "registerSuccess") {
        
    } else {
        header("location:/iicshd/index.php");
    }
}


?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>IICS Help Desk - Register</title>
        <link rel="shortcut icon" href="img/favicon.png">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="fa-5.5.0/css/fontawesome.css">


        <!-- Font Awesome JS -->
        <script defer src="fa-5.5.0/js/solid.js"></script>
        <script defer src="fa-5.5.0/js/fontawesome.js"></script>

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
        <div class="container">
            <br>
            <div class="row">
                <div class="col-md-5 left">
                    <div align="center"><img src="img/logo2.png" alt=""/><br/><br/></div>
                </div>

                <div class="col-md-7 right">
                    <div class="card">
                        <div class="card-body">
                            <span class="fas fa-2x fa-check-circle"></span>
                            <center><h4>Registration Successful!</h4></center>
                            <?php $_SESSION['param'] = ''; ?>
                            <a href="index.php"><button class="btn btn-lg btn-success btn-block btn-signin" type="submit" name="login">Log-In</button></a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
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


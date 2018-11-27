<?php
include './include/controller.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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

    if ((time() - $_SESSION['last_time']) > 2000) {
        header("Location:../../logout.php");
    } else {
        $_SESSION['last_time'] = time();
    }
}


unset($_SESSION['seq']);
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>IICS Help Desk - Forgot Password</title>
        <link rel="shortcut icon" href="img/favicon.png">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">

        <link href="fa-5.5.0/css/fontawesome.css" rel="stylesheet">

        <!-- Font Awesome JS -->
        <script defer src="fa-5.5.0/js/solid.js"></script>
        <script defer src="fa-5.5.0/js/fontawesome.js"></script>
        
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
        <div class="container">
            <br>
            <div class="row">

                <div class="col-md-5 left">
                    <div align="center"><img src="img/logo2.png" alt=""/><br/><br/></div>
                </div>

                <div class="col-md-7 right">

                    <div class="card">
                        <div class="card-title">
                            <p><h4 style="text-align: center; ">Forgot Password</p></h4>
                            <hr>
                        </div>
                        <div class="alert alert-info">
                            <span class="fas fa-info-circle"></span> Enter your <em>ust.edu.ph</em> email account.
                        </div>
                        <form class="form-container" method="POST" action="">
                            <div class="card-body">
                                <div class="form-group">
                                    <p><input class="form-control" type="text" placeholder="ust.edu.ph Email Account" value="<?php echo $email; ?>" autofocus autocomplete="off" name="email"/></p>
                                    <?php echo $usernameErr; ?>
                                    <?php echo $usernameErr2; ?>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-lg btn-success btn-block btn-signin" name="forgotPass">Search</button>
                                <br>
                                <a href = "index.php"><button type="button" class="btn btn-lg btn-success btn-block btn-signin" name="back">Cancel</button></a>                   
                                <!-- form end -->
                            </div>
                        </form>
                        <div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="container-fluid headerline">
            &nbsp;
        </div>
        <div class="container-fluid footer">
            &nbsp;
        </div>
    </body>

</html>


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

if (!isset($_SESSION['seq'])) {
    header("location:/iicshd/forgot.php");
    exit;
} else {
    $seq = $_SESSION['seq'];
}
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
                            <p><h4 style="text-align: center; ">Security Question</p></h4>
                            <hr>
                        </div>
                        <div class="alert alert-info">
                            <span class="fas fa-info-circle"></span> Please answer the <em>security question</em> tied to your account.
                        </div>
                        <form class="form-container" method="POST" action="">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><b>Question:</b></label>
                                    <p><?php echo $_SESSION['seq']; ?></p>
                                    <label for="exampleInputEmail1"><b>Answer:</b></label>

                                    <input class="form-control" type="password" placeholder="Answer" id="securityans" onfocusout="setAttribute('type', 'password');" onfocus="setAttribute('type', 'text');" name="securityans" autofocus autocomplete="off">
                                    <?php echo $answerErr; ?>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-lg btn-success btn-block btn-signin" name="submitAnswer">Submit</button>
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

        <br><br>

        <div class="container-fluid headerline">
            &nbsp;
        </div>
        <div class="container-fluid footer">
            &nbsp;
        </div>
    </body>

</html>


<?php
require './include/controller.php';
require './include/PHPMailer/src/PHPMailer.php';
require './include/PHPMailer/src/SMTP.php';
require './include/PHPMailer/src/Exception.php';


//if (isset($_SESSION['user_name']) && $_SESSION['role'] == "admin") {
//    header("location:/iicshd/user/admin/home.php");
//}
//if (isset($_SESSION['user_name']) && $_SESSION['role'] == "faculty") {
//    header("location:/iicshd/user/faculty/home.php");
//}
//if (isset($_SESSION['user_name']) && $_SESSION['role'] == "student") {
//    header("location:/iicshd/user/student/home.php");
//}
//if (isset($_SESSION['user_name'])) {
//
//    if ((time() - $_SESSION['last_time']) > 2000) {
//        header("Location:../../logout.php");
//    } else {
//        $_SESSION['last_time'] = time();
//    }
//}
//
if (!isset($_SESSION['user_name'])) {

    if ($_SESSION['param'] == "registerSuccess") {

        $role = $_SESSION['studrole'];

        $studnum = $_SESSION['studnum'];
        $studfname = $_SESSION['studfname'];
        $studlname = $_SESSION['studlname'];
        $vcode = $_SESSION['vcode'];
        $studemail = $_SESSION['studemail'];
    } elseif ($_SESSION['param'] == "registerSuccess2") {

        $role = $_SESSION['emprole'];

        $empnum = $_SESSION['empnum'];
        $empfname = $_SESSION['empfname'];
        $emplname = $_SESSION['emplname'];
        $vcode = $_SESSION['vcode'];
        $empemail = $_SESSION['empemail'];
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
                            <span class="fas fa-2x fa-user-check"></span>
                            <center><h4>Almost Done!</h4></center>
                            <?php $_SESSION['param'] = ''; ?>
                            <div class="alert alert-success">
                                We have sent an email to
                                <b><i>
                                        <?php
                                        if ($role == "student") {

                                            $mail = new PHPMailer\PHPMailer\PHPMailer();

                                            try {
                                                $mail->isSMTP();                                      // Set mailer to use SMTP
                                                $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
                                                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                                                $mail->Username = 'noreply.iicshd@gmail.com';                 // SMTP username
                                                $mail->Password = '1ng0dw3trust';                           // SMTP password
                                                $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                                                $mail->Port = 587;                                    // TCP port to connect to
                                                //Recipients
                                                $mail->setFrom('noreply.iicshd@gmail.com', 'IICS Help Desk');
                                                $mail->addAddress($_SESSION['studemail']);
                                                //                                                $mail->addAddress('rlphvicente@gmail.com');
                                                $mail->addReplyTo('noreply.iicshd@gmail.com', 'IICS Help Desk'); // Add a recipient

                                                $mail->isHTML(true);                                  // Set email format to HTML
                                                $mail->Subject = 'IICS Help Desk | Verify Your Account';
                                                $mail->Body = '<html><head></head><body><div align="center"><img src="https://i.imgur.com/TpIc9n9.png" alt="IICS Help Desk"/></center>'
                                                        . '<p>Thank you for signing up STUDENT!</p>'
                                                        . '<p>Please input the <b>verification code</b> to complete registration.</p>'
                                                        . '<hr>'
                                                        . '<p align="left"><b>Name: </b>' . $_SESSION['studfname'] . ' ' . $_SESSION['studlname'] . '</p>
                                                           <p align="left"><b>User ID: </b>' . $_SESSION['studnum'] . '</p>
                                                           <p align="left"><b>Verification Code: </b>' . $_SESSION['vcode'] . '</p>'
                                                        . '<hr></body></html>';

                                                $mail->send();
                                            } catch (Exception $ex) {
                                                echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                                            }

                                            echo $_SESSION['studemail'] . '.';
                                        } else {

                                            $mail = new PHPMailer\PHPMailer\PHPMailer();

                                            try {
                                                $mail->isSMTP();                                      // Set mailer to use SMTP
                                                $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
                                                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                                                $mail->Username = 'noreply.iicshd@gmail.com';                 // SMTP username
                                                $mail->Password = '1ng0dw3trust';                           // SMTP password
                                                $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                                                $mail->Port = 587;                                    // TCP port to connect to
                                                //Recipients
                                                $mail->setFrom('noreply.iicshd@gmail.com', 'IICS Help Desk');
//                                                $mail->addAddress('rlphvicente@gmail.com');
                                                $mail->addAddress($_SESSION['empemail']);
                                                $mail->addReplyTo('noreply.iicshd@gmail.com', 'IICS Help Desk'); // Add a recipient

                                                $mail->isHTML(true);                                  // Set email format to HTML
                                                $mail->Subject = 'IICS Help Desk | Verify Your Account';
                                                $mail->Body = '<div align="center"><img src="https://i.imgur.com/TpIc9n9.png" alt="IICS Help Desk"/></center>'
                                                        . '<p>Thank you for signing up FACULTY!</p>'
                                                        . '<p>Please input the <b>verification code</b> to complete registration.</p>'
                                                        . '<hr>'
                                                        . '<p align="left"><b>Name: </b>' . $_SESSION['empfname'] . ' ' . $_SESSION['emplname'] . '</p>
                                                           <p align="left"><b>User ID: </b>' . $_SESSION['empnum'] . '</p>
                                                           <p align="left"><b>Verification Code: </b>' . $_SESSION['vcode'] . '</p>'
                                                        . '<hr>';

                                                $mail->send();
                                            } catch (Exception $ex) {
                                                echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                                            }

                                            echo $_SESSION['empemail'] . '.';
                                        }
                                        ?>
                                    </i></b> 
                                Please check your <b>Spam</b> folder if you can't locate the email.
                            </div>
                            <div class="alert alert-secondary">
                                <p>Input the <b>Verification Code</b> below to confirm your credentials.</p>

                                <form id ="verify" action="" method="POST">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Verification Code" name="vcode" required/>
                                    </div>
                                    <center><input type="submit" class="btnVerify" name="verify" value="Submit"/></center>
                                </form>

                            </div>
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


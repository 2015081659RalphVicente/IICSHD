<?php
include './include/controller.php';

require './include/PHPMailer/src/PHPMailer.php';
require './include/PHPMailer/src/SMTP.php';
require './include/PHPMailer/src/Exception.php';
require './include/PHPMailer/src/Config.php';
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

if (!isset($_SESSION['user_name'])) {
    if ($_SESSION['param'] == "successChange") {
        
    } else {
        header("location:/iicshd/login.php");
    }
}

function RandomString($length) {
    $keys = array_merge(range(0, 9), range('A', 'Z'));

    $key = "";
    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[mt_rand(0, count($keys) - 1)];
    }
    return $key;
}

$temp_pass = RandomString(8);

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
                            <center><h4>Success!</h4></center>
                            <br>
                            <div class="alert alert-success">
                                We have sent an email to <em><b><?php echo $_SESSION['requser']; ?></b></em> containing your temporary password.
                                Please check your <b>Spam</b> folder if you can't locate the email.
                                <?php $_SESSION['param'] = ''; ?>
                            </div>

                            <?php
                            $mail = new PHPMailer\PHPMailer\PHPMailer();

                            try {
                                //Recipients
                                $mail->setFrom('noreply.iicshd@gmail.com', 'IICS Help Desk');
                                $mail->addAddress($_SESSION['requser']);
//                                $mail->addAddress('rlphvicente@gmail.com');
                                $mail->addReplyTo('noreply.iicshd@gmail.com', 'IICS Help Desk'); // Add a recipient

                                $mail->isHTML(true);                                  // Set email format to HTML
                                $mail->Subject = 'IICS Help Desk | Forgot Password';
                                $mail->Body = '<html><head></head><body><div align="center"><img src="https://i.imgur.com/TpIc9n9.png" alt="IICS Help Desk"/></center>'
                                        . '<p>You have requested for a password reset.</p>'
                                        . '<p>Please use the given <b>temporary password</b> for logging-in.</p>'
                                        . '<hr>'
                                        . '<p align="left"><b>Temporary Password: </b>' . $temp_pass . '</p>'
                                        . '<hr></body></html>';

                            } catch (Exception $ex) {
                                echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                            }

                            if ($mail->send() == TRUE) {

                                $hashedTemp = password_hash($temp_pass, PASSWORD_DEFAULT);
                                $email = $_SESSION['requser'];
                                $forgotpass = '1';

                                $sqladd = $conn->prepare("UPDATE users SET users.password = ?, users.forgotpass = ? WHERE users.email = ?");
                                $sqladd->bind_param("sis", $hashedTemp, $forgotpass, $email);
                                $sqladd->execute();
                                $sqladd->close();
                            }
                            ?>
                            <br>
                            <a href="index.php"><button class="btn btn-lg btn-success btn-block btn-signin" type="submit" name="login">Log-In</button></a>
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


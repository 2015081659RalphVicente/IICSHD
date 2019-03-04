<?php
require './include/controller.php';

use PHPMailer\PHPMailer\PHPMailer;

require './include/PHPMailer/src/SMTP.php';
require './include/PHPMailer/src/Exception.php';
require './include/PHPMailer/src/PHPMailer.php';
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

$mail = new PHPMailer;

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'noreply.iicshd@gmail.com';                 // SMTP username
$mail->Password = '1ng0dw3trust';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$vcodeErr = "";

if (isset($_GET['codefail'])) {
    $codefail = $_GET['codefail'];
} else {
    $codefail = '';
}

if (isset($_POST['verify'])) {
    $inputv = $_POST['inputv'];

    $checker = $conn->prepare("SELECT vcode FROM users WHERE email = '" . $_SESSION['requser'] . "'");
    $checker->bind_param("s", $inputnum);
    $checker->execute();
    $result = $checker->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $checkv = $row['vcode'];

            if ($inputv == $checkv) {

                $_SESSION['param'] = "successChange";
                header("Location: securitysuccess.php");
                exit();
            } else {

                $_SESSION['counter'] = 0;

                if ($_SESSION['counter'] <= 2) {
                    $_SESSION['counter'] ++;
                    $_GET['codefail'] = 'success';
                    header("Location: forgotsecurity.php?codefail=success");
                }
            }
        }
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
                            <p><h4 style="text-align: center; ">Forgot Security Question</p></h4>
                            <hr>
                        </div>

                        <?php
                        if ($codefail == TRUE) {
                            echo "<div class='alert alert-danger'> Wrong verification code. Please check your <b>Spam</b> folder if you can't locate the email.</div>";
                        } else {
                            echo '';
                        }
                        ?>

                        <div class="alert alert-info">
                            <span class="fas fa-info-circle"></span> We have sent an email to <b><i><?php echo $_SESSION['requser']; ?></b></i> containing a Verification Code in order to confirm your identity. Please check your <b>Spam</b> folder if you can't locate the email.
                        </div>

                        <?php
                        $selectQuery = "SELECT vcode FROM users WHERE email = '" . $_SESSION['requser'] . "'";

                        $selectResult = $conn->query($selectQuery);

                        if ($selectResult->num_rows > 0) {
                            while ($row = $selectResult->fetch_assoc()) {
                                $vcode = $row['vcode'];

                                try {
                                    //Recipients
                                    $mail->setFrom('noreply.iicshd@gmail.com', 'IICS Help Desk');
                                    $mail->addAddress($_SESSION['requser']);
//                                    $mail->addAddress('rlphvicente@gmail.com');
                                    $mail->addReplyTo('noreply.iicshd@gmail.com', 'IICS Help Desk'); // Add a recipient

                                    $mail->isHTML(true);                                  // Set email format to HTML
                                    $mail->Subject = 'IICS Help Desk | Forgot Security Question';
                                    $mail->Body = '<html><head></head><body><div align="center"><img src="https://i.imgur.com/TpIc9n9.png" alt="IICS Help Desk"/></center>'
                                            . '<p>Please input the <b>verification code</b> to proceed with resetting your password.</p>'
                                            . '<hr>'
                                            . '<p align="left"><b>Verification Code: </b>' . $vcode . '</p>'
                                            . '<hr></body></html>';

                                    $mail->send();
                                } catch (Exception $ex) {
                                    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                                }
                            }
                        } else {
                            echo 'Message could not be sent.';
                        }
                        ?>

                        <div class="alert alert-secondary">
                            <p>Input the <b>Verification Code</b> below to confirm your credentials.</p>

                            <form id ="verify" action="" method="POST">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Verification Code" id="inputv" name="inputv" required/>
                                    <?php echo $vcodeErr; ?>
                                </div>
                                <center><input type="submit" class="btnVerify" id = "verify" name="verify" value="Submit"/></center>
                            </form>

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


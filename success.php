<?php
require './include/controller.php';

require './include/PHPMailer/src/PHPMailer.php';
require './include/PHPMailer/src/SMTP.php';
require './include/PHPMailer/src/Exception.php';
require './include/PHPMailer/src/Config.php';

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

$studSuccess = $_SESSION['studSuccess'];

$vcode = $vcodeErr = $studrole = $emprole = "";


$studnum = $studfname = $studmname = $studlname = $studsection = $studemail = $studpass = $studconfpass = $studsecq = $studsecans = $studrole = $forgot = $hidden = "";
$empnum = $empfname = $empmname = $emplname = $empsection = $empemail = $emppass = $empconfpass = $empsecq = $empsecans = $emprole = $forgot = $hidden = "";


if ($studSuccess == TRUE) {

    $role = $_SESSION['studrole'];
    $vcode = $_SESSION['vcode'];

    $studnum = $_SESSION['studnum'];
    $studfname = $_SESSION['studfname'];
    $studmname = $_SESSION['studmname'];
    $studlname = $_SESSION['studlname'];
    $studemail = $_SESSION['studemail'];
    $hashedPwd = $_SESSION['studpass'];
    $forgot = $_SESSION['studforgot'];
    $studsection = $_SESSION['studsection'];
    $studsecq = $_SESSION['studsecq'];
    $hashedSecAns = $_SESSION['studseca'];
    $hidden = $_SESSION['studhidden'];

    if (isset($_POST['verify'])) {

        if ($role == "student") {

            $inputv = $_POST['inputv'];
            $inputnum = $_SESSION['studnum'];

            $checker = $conn->prepare("SELECT * FROM users_temp WHERE userid = ? AND  HIDDEN = 0");
            $checker->bind_param("s", $inputnum);
            $checker->execute();
            $result = $checker->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $checkv = $row['vcode'];

                    if ($inputv == $checkv) {

//                        $studnum = $_SESSION['studnum'];
//                        $studfname = $_SESSION['studfname'];
//                        $studmname = $_SESSION['studmname'];
//                        $studlname = $_SESSION['studlname'];
//                        $studemail = $_SESSION['studemail'];
//                        $hashedPwd = $_SESSION['studpass'];
//                        $forgot = $_SESSION['studforgot'];
                        $studrole = $role;
//                        $studsection = $_SESSION['studsection'];
//                        $studsecq = $_SESSION['studsecq'];
//                        $hashedSecAns = $_SESSION['studseca'];
//                        $hidden = $_SESSION['studhidden'];


                        $verified = "1";
                        $hashedv = password_hash($inputv, PASSWORD_DEFAULT);
                        //insert the user into the database

                        $sqladd = $conn->prepare("INSERT INTO users VALUES ('',?,?,?,?,?,?,?,?,?,?,?,?,'',?,?)");
                        $sqladd->bind_param("ssssssissisisi", $studnum, $studfname, $studmname, $studlname, $studemail, $hashedPwd, $forgot, $studrole, $studsection, $studsecq, $hashedSecAns, $hidden, $hashedv, $verified);
                        $sqladd->execute();
                        $sqladd->close();

                        if ($sqladd == TRUE) {

                            $sqldelete = $conn->prepare("DELETE FROM users_temp WHERE userid = ?");
                            $sqldelete->bind_param("s", $inputnum);
                            $sqldelete->execute();
                            $sqldelete->close();


                            $_SESSION['studSuccess'] = 1;
                            header("Location: verified.php");
                            exit();
                        } else {
                            $vcodeErr = '<div class="alert alert-danger">
                        Register error!
                        </div>';
                        }
                    } else {
                        $_SESSION['studSuccess'] = 0;
                        header("Location: verifyfail.php");
                    }
                }
            }
        }
    }
} elseif ($studSuccess == FALSE) {

    $role = $_SESSION['emprole'];
    $vcode = $_SESSION['vcode'];

    $empnum = $_SESSION['empnum'];
    $empfname = $_SESSION['empfname'];
    $empmname = $_SESSION['empmname'];
    $emplname = $_SESSION['emplname'];
    $empemail = $_SESSION['empemail'];
    $hashedPwd = $_SESSION['emppass'];
    $forgot = $_SESSION['empforgot'];
    $empsection = $_SESSION['empsection'];
    $empsecq = $_SESSION['empsecq'];
    $hashedSecAns = $_SESSION['empseca'];
    $hidden = $_SESSION['emphidden'];

    if (isset($_POST['verify'])) {

        if ($role == "faculty") {

            $inputv = $_POST['inputv'];
            $inputnum = $_SESSION['empnum'];

            $checker = $conn->prepare("SELECT * FROM users_temp WHERE userid = ? AND  HIDDEN = 0");
            $checker->bind_param("s", $inputnum);
            $checker->execute();
            $result = $checker->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $checkv = $row['vcode'];

                    if ($inputv == $checkv) {

//                        $empnum = $_SESSION['empnum'];
//                        $empfname = $_SESSION['empfname'];
//                        $empmname = $_SESSION['empmname'];
//                        $emplname = $_SESSION['emplname'];
//                        $empemail = $_SESSION['empemail'];
//                        $hashedPwd = $_SESSION['emppass'];
//                        $forgot = $_SESSION['empforgot'];
                        $emprole = $role;
//                        $empsection = $_SESSION['empsection'];
//                        $empsecq = $_SESSION['empsecq'];
//                        $hashedSecAns = $_SESSION['empseca'];
//                        $hidden = $_SESSION['emphidden'];


                        $verified = "1";
                        $hashedv = password_hash($inputv, PASSWORD_DEFAULT);
                        //insert the user into the database

                        $sqladd = $conn->prepare("INSERT INTO users VALUES ('',?,?,?,?,?,?,?,?,?,?,?,?,'',?,?)");
                        $sqladd->bind_param("ssssssissisisi", $empnum, $empfname, $empmname, $emplname, $empemail, $hashedPwd, $forgot, $emprole, $empsection, $empsecq, $hashedSecAns, $hidden, $hashedv, $verified);
                        $sqladd->execute();
                        $sqladd->close();

                        if ($sqladd == TRUE) {

                            $sqldelete = $conn->prepare("DELETE FROM users_temp WHERE userid = ?");
                            $sqldelete->bind_param("s", $inputnum);
                            $sqldelete->execute();
                            $sqldelete->close();

                            $_SESSION['studSuccess'] = 1;
                            header("Location: verified.php");
                            exit();
                        } else {
                            $vcodeErr = '<div class="alert alert-danger">
                        Register error!
                        </div>';
                        }
                    } else {
                        $_SESSION['studSuccess'] = 0;
                        header("Location: verifyfail.php");
                    }
                }
            }
        }
    }
} else {
    header("Location: index.php");
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
                                                //Recipients
                                                $mail->setFrom('noreply.iicshd@gmail.com', 'IICS Help Desk');
//                                                $mail->addAddress($_SESSION['studemail']);
                                                $mail->addAddress('rlphvicente@gmail.com');
                                                $mail->addReplyTo('noreply.iicshd@gmail.com', 'IICS Help Desk'); // Add a recipient

                                                $mail->isHTML(true);                                  // Set email format to HTML
                                                $mail->Subject = 'IICS Help Desk | Verify Your Account';
                                                $mail->Body = '<html><head></head><body><div align="center"><img src="https://i.imgur.com/TpIc9n9.png" alt="IICS Help Desk"/></center>'
                                                        . '<p>Thank you for signing up STUDENT!</p>'
                                                        . '<p>Please input the <b>verification code</b> to complete registration.</p>'
                                                        . '<hr>'
                                                        . '<p align="left"><b>Name: </b>' . $studfname . ' ' . $studlname . '</p>
                                                           <p align="left"><b>User ID: </b>' . $studnum . '</p>
                                                           <p align="left"><b>Verification Code: </b>' . $vcode . '</p>'
                                                        . '<hr></body></html>';

                                                $mail->send();
                                            } catch (Exception $ex) {
                                                echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                                            }

                                            echo $studemail . '.';
                                        } elseif ($role == "faculty") {

                                            $mail = new PHPMailer\PHPMailer\PHPMailer();

                                            try {
                                                //Recipients
                                                $mail->setFrom('noreply.iicshd@gmail.com', 'IICS Help Desk');
                                                $mail->addAddress('rlphvicente@gmail.com');
//                                                $mail->addAddress($_SESSION['empemail']);
                                                $mail->addReplyTo('noreply.iicshd@gmail.com', 'IICS Help Desk'); // Add a recipient

                                                $mail->isHTML(true);                                  // Set email format to HTML
                                                $mail->Subject = 'IICS Help Desk | Verify Your Account';
                                                $mail->Body = '<div align="center"><img src="https://i.imgur.com/TpIc9n9.png" alt="IICS Help Desk"/></center>'
                                                        . '<p>Thank you for signing up FACULTY!</p>'
                                                        . '<p>Please input the <b>verification code</b> to complete registration.</p>'
                                                        . '<hr>'
                                                        . '<p align="left"><b>Name: </b>' . $empfname . ' ' . $emplname . '</p>
                                                           <p align="left"><b>User ID: </b>' . $empnum . '</p>
                                                           <p align="left"><b>Verification Code: </b>' . $vcode . '</p>'
                                                        . '<hr>';

                                                $mail->send();
                                            } catch (Exception $ex) {
                                                echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                                            }

                                            echo $empemail . '.';
                                        }
                                        ?>
                                    </i></b> 
                                Please check your <b>Spam</b> folder if you can't locate the email.
                            </div>
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


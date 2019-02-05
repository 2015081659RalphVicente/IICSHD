<?php
require './include/controller.php';

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
//if (!isset($_SESSION['user_name'])) {
//    if ($_SESSION['param'] == "registerSuccess") {
//        
//    } else {
//        header("location:/iicshd/index.php");
//    }
//}
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
                                We have sent a verification code to
                                <b><i>
                                        <?php
                                        $query = "SELECT * FROM users_temp ORDER BY userno DESC LIMIT 1";
                                        $result = $conn->query($query);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $temp_email = "rlphvicente@gmail.com";
                                                $temp_name = ($row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']);
                                                $temp_userid = $row['userid'];
                                                $temp_verify = $row['vcode'];

                                                $to = $temp_email; // Send email to our user
                                                $subject = 'IICS Help Desk | Verification Code'; // Give the email a subject 
                                                $message = '
 
                                                Thank you for signing up!
                                                Your account has been created, please input the verification code to complete registration.
                                                ------------------------
                                                Name: ' . $temp_name . '
                                                User ID: ' . $temp_userid . '
                                                Verification Code: ' . $temp_verify . '
                                                ------------------------

                                                '; // Our message above including the link

                                                $headers = 'From: rlphvicente@gmail.com' . "\r\n"; // Set from headers
                                                mail($to, $subject, $message, $headers); // Send our email

                                                echo $temp_email . '.';
                                            }
                                        } else {
                                            echo "Error";
                                        }
                                        ?>
                                    </i></b>
                                Input the verification code below to confirm your credentials.
                            </div>
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


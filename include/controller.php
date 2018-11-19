<?php

//connect to db
session_start();
require 'dbconn.php';
require 'clean.php';
date_default_timezone_set("Asia/Manila");
$date = date("Y-m-d");
$thisDate = date("Y-m-d");
$date_time = date("Y-m-d h:i:sa");

//initial variables
$userid = $password = $email = $success = $fail = $passwordErr = $registerSuccess = "";

//login
if (isset($_POST['login'])) {

    //login credentials
    $userid = $_POST["userid"];
    $password = $_POST["password"];

    $checker = $conn->prepare("SELECT * FROM users WHERE userid = ? AND  HIDDEN = 0");
    $checker->bind_param("s", $userid);
    $checker->execute();
    $result = $checker->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            $hashedPwdCheck = password_verify($password, $row['password']);
            if ($hashedPwdCheck == FALSE) {
                $passwordErr = '<div class="alert alert-danger">
                        Login Failed!
                        </div>';
                $userno = $row['userno'];
                $userid = $row['userid'];
                $name = ($row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']);
                $role = $row['role'];
            } elseif ($hashedPwdCheck == TRUE) {
                $name = ($row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']);
                $_SESSION['user_name'] = $name;
                $_SESSION['userid'] = $row['userid'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['fname'] = $row['fname'];
                $_SESSION['mname'] = $row['mname'];
                $_SESSION['name'] = $row['lname'];
                $_SESSION['userno'] = $row['userno'];
                $_SESSION['section'] = $row['section'];
                $_SESSION['last_time'] = time();
                $_SESSION['resetpass'] = $row['forgotpass'];


                if ($_SESSION['role'] == "admin") {
                    header("location:/iicshd/user/admin/home.php");
                    exit();
                }
                if ($_SESSION['role'] == "student") {
                    header("location:/iicshd/user/student/home.php");
                    exit();
                }
                if ($_SESSION['role'] == "faculty") {
                    header("location:/iicshd/user/faculty/home.php");
                    exit();
                }
            }
        }
    } else {

        $passwordErr = '<div class="alert alert-danger">
                        Login Failed!
                        </div>';
    }
}

?>
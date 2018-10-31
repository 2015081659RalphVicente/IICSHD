<?php

//connect to db
session_start();
require 'dbconn.php';
date_default_timezone_set("Asia/Manila");

//initial variables
$userid = $password = $email = $success = $fail = "";

//login
if (isset($_POST['login'])) {

    //login credentials
    $userid = $_POST["userid"];
    $password = $_POST["password"];

    $checker = $conn->prepare("SELECT * FROM users WHERE userid =? AND  HIDDEN = 0");
    $checker->bind_param("s", $userid);
    $checker->execute();
    $result = $checker->get_result();
    
    
}
?>
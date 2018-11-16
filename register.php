<?php
require './include/controller.php';

$studnum = $studfname = $studmname = $studlname = $studsection = $studemail = $studpass = $studconfpass = $studsecq = $studsecans = $studrole = $forgot = $hidden = "";
$empnum = $empfname = $empmname = $emplname = $empsection = $empemail = $emppass = $empconfpass = $empsecq = $empsecans = $emprole = $forgot = $hidden = "";
$tab1 = $tab2 = $studnumErr = $empnumErr = $numErr = $numErr2 = $firstErr = $firstErr2 = $midErr = $midErr2 = $lastErr = $lastErr2 = $emailErr = $emailErr2 = $confirmErr = $confirmErr2 = $passwordErr = $passwordErr2 = "";


//register student
if (isset($_POST['studRegister'])) {
    $studnum = clean($_POST["studnum"]);
    $studfname = clean($_POST["studfname"]);
    $studmname = clean($_POST["studmname"]);
    $studlname = clean($_POST["studlname"]);
    $studsection = clean($_POST["studsection"]);
    $studemail = $_POST["studemail"];
    $studpass = $_POST["studpass"];
    $studconfpass = $_POST["studconfpass"];
    $studsecq = clean($_POST["studsecq"]);
    $studsecans = $_POST["studsecans"];
    $studrole = "student";
    $forgot = $hidden = "0";

    //checker
    $pcheck = $conn->prepare("SELECT userid from users where userid=?");
    $pcheck->bind_param("s", $studnum);
    $pcheck->execute();
    $resultpcheck = $pcheck->get_result();
    $pcheck->close();

    $updateBool = TRUE;

    //validators
    if (!preg_match("/^[0-9]{10,10}$/", $studnum)) {
        $numErr = '<div class="alert alert-warning">
                        Input must only be numbers and should have 10 digits.
    </div>';
        $updateBool = FALSE;
    }
    if (!preg_match("/^[a-zA-Z\ ]*$/", $studfname)) {
        $firstErr = '<div class="alert alert-warning">
                        <strong>Wrong</strong> input! Input must only be characters.
    </div>';
        $updateBool = FALSE;
    }
    if (!preg_match("/^[a-zA-Z\. ]*$/", $studmname)) {
        $midErr = '<div class="alert alert-warning">
                       <strong>Wrong</strong> input! Input must only be characters.
    </div>';
        $updateBool = FALSE;
    }
    if (!preg_match("/^[a-zA-Z\ ]*$/", $studlname)) {
        $lastErr = '<div class="alert alert-warning">
                        <strong>Wrong</strong> input! Input must only be characters.
                        </div>';
        $updateBool = FALSE;
    }
    if (!preg_match("/^[a-zA-Z0-9_.+-]+@(?:(?:[a-zA-Z0-9-]+\.)?[a-zA-Z]+\.)?(ust)\.edu\.ph*$/", $studemail)) {
        $emailErr = '<div class="alert alert-warning">
                        <strong>Wrong</strong> input! Use ust.edu.ph email instead.
                        </div>';
        $updateBool = FALSE;
    }
    if ($resultpcheck->num_rows > 0) {
        $studnumErr = '<div class="alert alert-warning">
                        <strong>Student Number</strong> already has an account.
                        </div>';
        $updateBool = FALSE;
    }
    if (!preg_match("/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $studpass)) {
        $passwordErr = '<div class="alert alert-warning">
                        <strong>Wrong</strong> input! Password must be atleast 8 characters long and must be a combination of uppercase letters, lowercase letters and numbers.
                        </div>';
        $updateBool = FALSE;
    }
    if ($studpass != $studconfpass) {
        $confirmErr = '<div class="alert alert-warning">
                        <strong>Password</strong> mismatch.
                        </div>';
        $updateBool = FALSE;
    }

    if ($updateBool == TRUE) {

        //protect password
        $hashedPwd = password_hash($studpass, PASSWORD_DEFAULT);
        $hashedSecAns = password_hash($studsecans, PASSWORD_DEFAULT);
        //insert the user into the database

        $sqladd = $conn->prepare("INSERT INTO users VALUES ('',?,?,?,?,?,?,?,?,?,?,?,?)");
        $sqladd->bind_param("ssssssissisi", $studnum, $studfname, $studmname, $studlname, $studemail, $hashedPwd, $forgot, $studrole, $studsection, $studsecq, $hashedSecAns, $hidden);
        $sqladd->execute();
        $sqladd->close();

//        //PANG LOGS
//        $perval = 'Personnel ID: ' . $newpid . ', ' . $newpfname . ' ' . $newpmname . ' ' . $newplname . ' (' . $newrole . '), ' . $newperteam . ' Team';
//
//        $peraction = "Add Personnel (For Activation)";
//
//        $logper = $conn->prepare("INSERT INTO addlogs VALUES ('',?,?,NOW(),?)");
//        $logper->bind_param("sss", $peraction, $_SESSION['user_name'], $perval);
//        $logper->execute();
//        $logper->close();

        header("Location: register.php");
        exit;
    } else {
        $_SESSION['tab'] = '1';
    }
}

//register faculty
if (isset($_POST['empRegister'])) {
    $empnum = clean($_POST["empnum"]);
    $empfname = clean($_POST["empfname"]);
    $empmname = clean($_POST["empmname"]);
    $emplname = clean($_POST["emplname"]);
    $empemail = $_POST["empemail"];
    $emppass = $_POST["emppass"];
    $empconfpass = $_POST["empconfpass"];
    $empsecq = clean($_POST["empsecq"]);
    $empsecans = $_POST["empsecans"];
    $empsection = "Faculty";
    $emprole = "faculty";
    $forgot = $hidden = "0";

    //checker
    $pcheck = $conn->prepare("SELECT userid from users where userid=?");
    $pcheck->bind_param("s", $empnum);
    $pcheck->execute();
    $resultpcheck = $pcheck->get_result();
    $pcheck->close();

    $updateBool = TRUE;

    //validators
    if (!preg_match("/^[0-9]{10,10}$/", $empnum)) {
        $numErr2 = '<div class="alert alert-warning">
                        Input must only be numbers and should have 10 digits.
    </div>';
        $updateBool = FALSE;
    }
    if (!preg_match("/^[a-zA-Z\ ]*$/", $empfname)) {
        $firstErr2 = '<div class="alert alert-warning">
                        <strong>Wrong</strong> input! Input must only be characters.
    </div>';
        $updateBool = FALSE;
    }
    if (!preg_match("/^[a-zA-Z\. ]*$/", $empmname)) {
        $midErr2 = '<div class="alert alert-warning">
                       <strong>Wrong</strong> input! Input must only be characters.
    </div>';
        $updateBool = FALSE;
    }
    if (!preg_match("/^[a-zA-Z\ ]*$/", $emplname)) {
        $lastErr2 = '<div class="alert alert-warning">
                        <strong>Wrong</strong> input! Input must only be characters.
                        </div>';
        $updateBool = FALSE;
    }
    if (!preg_match("/^[a-zA-Z0-9_.+-]+@(?:(?:[a-zA-Z0-9-]+\.)?[a-zA-Z]+\.)?(ust)\.edu\.ph*$/", $empemail)) {
        $emailErr2 = '<div class="alert alert-warning">
                        <strong>Wrong</strong> input! Use ust.edu.ph email instead.
                        </div>';
        $updateBool = FALSE;
    }
    if ($resultpcheck->num_rows > 0) {
        $empnumErr2 = '<div class="alert alert-warning">
                        <strong>Student Number</strong> already has an account.
                        </div>';
        $updateBool = FALSE;
    }
    if (!preg_match("/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $emppass)) {
        $passwordErr2 = '<div class="alert alert-warning">
                        <strong>Wrong</strong> input! Password must be atleast 8 characters long and must be a combination of uppercase letters, lowercase letters and numbers.
                        </div>';
        $updateBool = FALSE;
    }
    if ($emppass != $empconfpass) {
        $confirmErr2 = '<div class="alert alert-warning">
                        <strong>Password</strong> mismatch.
                        </div>';
        $updateBool = FALSE;
    }

    if ($updateBool == TRUE) {

        //protect password
        $hashedPwd = password_hash($emppass, PASSWORD_DEFAULT);
        $hashedSecAns = password_hash($empsecans, PASSWORD_DEFAULT);
        //insert the user into the database

        $sqladd = $conn->prepare("INSERT INTO users VALUES ('',?,?,?,?,?,?,?,?,?,?,?,?)");
        $sqladd->bind_param("ssssssissisi", $empnum, $empfname, $empmname, $emplname, $empemail, $hashedPwd, $forgot, $emprole, $empsection, $empsecq, $hashedSecAns, $hidden);
        $sqladd->execute();
        $sqladd->close();

//        //PANG LOGS
//        $perval = 'Personnel ID: ' . $newpid . ', ' . $newpfname . ' ' . $newpmname . ' ' . $newplname . ' (' . $newrole . '), ' . $newperteam . ' Team';
//
//        $peraction = "Add Personnel (For Activation)";
//
//        $logper = $conn->prepare("INSERT INTO addlogs VALUES ('',?,?,NOW(),?)");
//        $logper->bind_param("sss", $peraction, $_SESSION['user_name'], $perval);
//        $logper->execute();
//        $logper->close();

        header("Location: register.php");
        exit;
    } else {
        $_SESSION['tab'] = '2';
    }
}

if (isset($_SESSION['tab'])) {
    $tabparam = $_SESSION['tab'];
    if ($tabparam == '1') {
        $tab1 = "id='defaultOpen'";
    }
    if ($tabparam == '2') {
        $tab2 = "id='defaultOpen'";
    }
} else {
    $tab1 = "id='defaultOpen'";
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>IICS Help Desk - Register</title>
        <link rel="shortcut icon" href="img/favicon.png">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">

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
                    <div class="tab">
                        <button class="tablinks active" onclick="openTab(event, 'Student')" <?php echo $tab1; ?>>Student</button>
                        <button class="tablinks active" onclick="openTab(event, 'Faculty')" <?php echo $tab2; ?>>Faculty</button>
                    </div>

                    <?php $_SESSION['tab'] = '1'; ?>

                    <div class="tabcontent" id="Student">
                        <p style="padding-top: 1px;"></p>
                        <h3>Register as Student</h3><hr>
                        <form action="" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Student Number *" value="<?php echo $studnum; ?>" name="studnum" required/>
                                        <?php
                                        echo $studnumErr;
                                        echo $numErr;
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="First Name *" value="<?php echo $studfname; ?>" name="studfname" required/>
                                        <?php echo $firstErr; ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Middle Initial *" value="<?php echo $studmname; ?>" name="studmname" required/>
                                        <?php echo $midErr; ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Last Name *" value="<?php echo $studlname; ?>" name="studlname" required/>
                                        <?php echo $lastErr; ?>
                                    </div>
                                    <div class="form-group">
                                        <select required class="form-control" name="studsection">
                                            <option class="hidden" value="" selected disabled>Year and Section: *</option>
                                            <option disabled>──────── 1st Year ────────</option>
                                            <option value="1ITA">1ITA</option>
                                            <option value="1ITB">1ITB</option>
                                            <option value="1ITC">1ITC</option>
                                            <option value="1ITD">1ITD</option>
                                            <option value="1ITE">1ITE</option>
                                            <option value="1ITF">1ITF</option>
                                            <option value="1ITG">1ITG</option>
                                            <option disabled>──────── 2nd Year ────────</option>
                                            <option value="2ITA">2ITA</option>
                                            <option value="2ITB">2ITB</option>
                                            <option disabled>──────── 3rd Year ────────</option>
                                            <option value="3ITA">3ITA</option>
                                            <option value="3ITB">3ITB</option>
                                            <option disabled>──────── 4th Year ────────</option>
                                            <option value="4ITA">4ITA</option>
                                            <option value="4ITB">4ITB</option>
                                            <option value="4ITC">4ITC</option>
                                            <option value="4ITD">4ITD</option>
                                            <option value="4ITE">4ITE</option>
                                            <option value="4ITF">4ITF</option>
                                            <option value="4ITG">4ITG</option>
                                            <option value="4ITH">4ITH</option>
                                            <option value="4ITI">4ITI</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control" placeholder="E-mail (ust.edu.ph) *" value="<?php echo $studemail; ?>" name="studemail" required/>
                                        <?php echo $emailErr; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Password *" value="" name="studpass" required/>
                                        <?php echo $passwordErr; ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control"  placeholder="Confirm Password *" value="" name="studconfpass" required/>
                                        <?php echo $confirmErr; ?>
                                    </div>
                                    <div class="form-group">
                                        <select required class="form-control" name="studsecq">
                                            <option class="hidden" value="" selected disabled>Security Question: *</option>
                                            <option value="1">What is your mother's maiden name?</option>
                                            <option value="2">What was your favorite childhood movie?</option>
                                            <option value="3">What is the name of your first pet?</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" onfocusout="setAttribute('type', 'password');" onfocus="setAttribute('type', 'text');" class="form-control" placeholder="Answer *" value="" name="studsecans" required/>
                                    </div>
                                    <div class="custom-control custom-checkbox form-group">
                                        <input type="checkbox" class="custom-control-input" name="customCheck1" id="customCheck1" required>
                                        <label class="custom-control-label" for="customCheck1">
                                            I agree to the <a href="https://www.privacy.gov.ph/data-privacy-act/">R.A. 10173 (Data Privacy Act of 2012)</a> and I hereby confirm that the information given in this form is true, complete and accurate.
                                        </label>
                                    </div>
                                    <br>
                                    <input type="submit" class="btnRegister" name="studRegister" value="Register"/><br><br>
                                    <div align="right" style="font-size: 14px;"><a href="index.php">Already have an account? Log-In</a></div>
                                </div>
                            </div>
                        </form>
                        <br>
                    </div>

                    <div class="tabcontent" id="Faculty">
                        <p style="padding-top: 1px;"></p>
                        <h3>Register as Faculty</h3><hr>
                        <form action="" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Employee Number *" value="<?php echo $empnum; ?>" name="empnum" required/>
                                        <?php
                                        echo $empnumErr;
                                        echo $numErr2;
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="First Name *" value="<?php echo $empfname; ?>" name="empfname" required/>
                                        <?php
                                        echo $firstErr2;
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Middle Initial *" value="<?php echo $empmname; ?>" name="empmname" required/>
                                        <?php
                                        echo $midErr2;
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Last Name *" value="<?php echo $emplname; ?>" name="emplname" required/>
                                        <?php
                                        echo $lastErr2;
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control" placeholder="E-mail (ust.edu.ph) *" value="<?php echo $empemail; ?>" name="empemail" required/>
                                        <?php
                                        echo $emailErr2;
                                        ?>
                                    </div>  
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Password *" value="" name="emppass" required/>
                                        <?php
                                        echo $passwordErr2;
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control"  placeholder="Confirm Password *" value="" name="empconfpass" required/>
                                        <?php
                                        echo $confirmErr2;
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <select class="form-control" name="empsecq" required>
                                            <option class="hidden" value="" selected disabled>Security Question: *</option>
                                            <option value="1">What is your mother's maiden name?</option>
                                            <option value="2">What was your favorite childhood movie?</option>
                                            <option value="3">What is the name of your first pet?</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Answer *" value="" name="empsecans" required/>
                                    </div>
                                    <div class="custom-control custom-checkbox form-group">
                                        <input type="checkbox" class="custom-control-input" name="customCheck2" id="customCheck2" required>
                                        <label class="custom-control-label" for="customCheck2">
                                            I agree to the <a href="https://www.privacy.gov.ph/data-privacy-act/">R.A. 10173 (Data Privacy Act of 2012)</a> and I hereby confirm that the information given in this form is true, complete and accurate.
                                        </label>
                                    </div>
                                    <br>
                                    <input type="submit" class="btnRegister" name="empRegister" value="Register"/><br><br>
                                    <div align="right" style="font-size: 14px;"><a href="index.php">Already have an account? Log-In</a></div>
                                </div>
                            </div>
                        </form>
                        <br>
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

<script>
    function openTab(evt, cityName) {
        // Declare all variables
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }
</script>
<script>
    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();
</script>


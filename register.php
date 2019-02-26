<?php
require './include/controller.php';

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

unset($_SESSION['fromMain']);

$studnum = $studfname = $studmname = $studlname = $studsection = $studemail = $studpass = $studconfpass = $studsecq = $studsecans = $studrole = $forgot = $hidden = "";
$empnum = $empfname = $empmname = $emplname = $empsection = $empemail = $emppass = $empconfpass = $empsecq = $empsecans = $emprole = $forgot = $hidden = "";
$tab1 = $tab2 = $studnumErr = $empnumErr2 = $emailErr3 = $empnumErr3 = $numErr = $numErr2 = $firstErr = $firstErr2 = $midErr = $midErr2 = $lastErr = $lastErr2 = $emailErr = $emailErr2 = $confirmErr = $confirmErr2 = $passwordErr = $passwordErr2 = "";

function RandomString($length) {
    $keys = array_merge(range(0, 9), range('A', 'Z'));

    $key = "";
    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[mt_rand(0, count($keys) - 1)];
    }
    return $key;
}

$vcode = RandomString(5);

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
    $forgot = $hidden = $verified = "0";
    $vcode = RandomString(5);

    //checker
    $pcheck = $conn->prepare("SELECT userid from users where userid=?");
    $pcheck->bind_param("s", $studnum);
    $pcheck->execute();
    $resultpcheck = $pcheck->get_result();
    $pcheck->close();

    $emailcheck = $conn->prepare("SELECT email from users where email=?");
    $emailcheck->bind_param("s", $studemail);
    $emailcheck->execute();
    $resultemailcheck = $emailcheck->get_result();
    $emailcheck->close();

    $updateBool = TRUE;

    //validators
    if (!preg_match("/^[0-9]{10,10}$/", $studnum)) {
        $numErr = '<div class="alert alert-warning">
                        Input must contain numbers only and should have 10 digits.
    </div>';
        $updateBool = FALSE;
    }
    if (!preg_match("/^[a-zA-Z\ ]*$/", $studfname)) {
        $firstErr = '<div class="alert alert-warning">
                        Input must contain letters only.
    </div>';
        $updateBool = FALSE;
    }
    if (!preg_match("/^[a-zA-Z\. ]*$/", $studmname)) {
        $midErr = '<div class="alert alert-warning">
                       Input must contain a letter and a period (.)
    </div>';
        $updateBool = FALSE;
    }
    if (!preg_match("/^[a-zA-Z\ ]*$/", $studlname)) {
        $lastErr = '<div class="alert alert-warning">
                        Input must contain letters only.
                        </div>';
        $updateBool = FALSE;
    }
    if (!preg_match("/^[a-zA-Z0-9_.+-]+@(?:(?:[a-zA-Z0-9-]+\.)?[a-zA-Z]+\.)?(ust)\.edu\.ph*$/", $studemail)) {
        $emailErr = '<div class="alert alert-warning">
                        Please use your <em>ust.edu.ph</em> email address.
                        </div>';
        $updateBool = FALSE;
    }
    if ($resultpcheck->num_rows > 0) {
        $studnumErr = '<div class="alert alert-danger">
                        This user already has an account.
                        </div>';
        $updateBool = FALSE;
    }
    if ($resultemailcheck->num_rows > 0) {
        $emailErr3 = '<div class="alert alert-danger">
                        This email is already in use.
                        </div>';
        $updateBool = FALSE;
    }
    if (!preg_match("/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $studpass)) {
        $passwordErr = '<div class="alert alert-warning">
                        Your password must be atleast 8 characters long and must be a combination of uppercase letters, lowercase letters and numbers.
                        </div>';
        $updateBool = FALSE;
    }
    if ($studpass != $studconfpass) {
        $confirmErr = '<div class="alert alert-warning">
                        Password does not match the confirm password.
                        </div>';
        $updateBool = FALSE;
    }

    if ($updateBool == TRUE) {

        //protect password
        $hashedPwd = password_hash($studpass, PASSWORD_DEFAULT);
        $hashedSecAns = password_hash($studsecans, PASSWORD_DEFAULT);
        //insert the user into the database

        $sqladd = $conn->prepare("INSERT INTO users_temp VALUES ('',?,?,?,?,?,?,?,?,?,?,?,?,'',?,?)");
        $sqladd->bind_param("ssssssissisisi", $studnum, $studfname, $studmname, $studlname, $studemail, $hashedPwd, $forgot, $studrole, $studsection, $studsecq, $hashedSecAns, $hidden, $vcode, $verified);
        $sqladd->execute();
        $sqladd->close();

//        LOGS
//        $perval = 'Personnel ID: ' . $newpid . ', ' . $newpfname . ' ' . $newpmname . ' ' . $newplname . ' (' . $newrole . '), ' . $newperteam . ' Team';
//
//        $peraction = "Add Personnel (For Activation)";
//
//        $logper = $conn->prepare("INSERT INTO addlogs VALUES ('',?,?,NOW(),?)");
//        $logper->bind_param("sss", $peraction, $_SESSION['user_name'], $perval);
//        $logper->execute();
//        $logper->close();
        $_SESSION['studnum'] = $studnum;
        $_SESSION['studfname'] = $studfname;
        $_SESSION['studmname'] = $studmname;
        $_SESSION['studlname'] = $studlname;
        $_SESSION['studrole'] = $studrole;
        $_SESSION['vcode'] = $vcode;
        $_SESSION['studemail'] = $studemail;
        $_SESSION['studpass'] = $hashedPwd;
        $_SESSION['studforgot'] = $forgot;
        $_SESSION['studsection'] = $studsection;
        $_SESSION['studsecq'] = $studsecq;
        $_SESSION['studseca'] = $hashedSecAns;
        $_SESSION['studhidden'] = $hidden;
        $_SESSION['studverified'] = $verified;

        $studSuccess = TRUE;

        $_SESSION['studSuccess'] = $studSuccess;
        header("Location: success.php");
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
    $forgot = $hidden = $verified = "0";
    $vcode = RandomString(5);

    //checker
    $echeck = $conn->prepare("SELECT userid from users where userid=?");
    $echeck->bind_param("s", $empnum);
    $echeck->execute();
    $resultecheck = $echeck->get_result();
    $echeck->close();

    $emailcheck = $conn->prepare("SELECT email from users where email=?");
    $emailcheck->bind_param("s", $empemail);
    $emailcheck->execute();
    $resultemailcheck = $emailcheck->get_result();
    $emailcheck->close();

    $updateBool = TRUE;

    //validators
    if (!preg_match("/^[0-9]{10,10}$/", $empnum)) {
        $numErr2 = '<div class="alert alert-warning">
                        Input must contain numbers only and should have 10 digits.
    </div>';
        $updateBool = FALSE;
    }
    if (!preg_match("/^[a-zA-Z\ ]*$/", $empfname)) {
        $firstErr2 = '<div class="alert alert-warning">
                        Input must contain letters only.
    </div>';
        $updateBool = FALSE;
    }
    if (!preg_match("/^[a-zA-Z\. ]*$/", $empmname)) {
        $midErr2 = '<div class="alert alert-warning">
                       Input must contain a letter and a period (.)
    </div>';
        $updateBool = FALSE;
    }
    if (!preg_match("/^[a-zA-Z\ ]*$/", $emplname)) {
        $lastErr2 = '<div class="alert alert-warning">
                        Input must contain letters only.
                        </div>';
        $updateBool = FALSE;
    }
    if (!preg_match("/^[a-zA-Z0-9_.+-]+@(?:(?:[a-zA-Z0-9-]+\.)?[a-zA-Z]+\.)?(ust)\.edu\.ph*$/", $empemail)) {
        $emailErr2 = '<div class="alert alert-warning">
                        Please use your <em>ust.edu.ph</em> email address.
                        </div>';
        $updateBool = FALSE;
    }
    if ($resultecheck->num_rows > 0) {
        $empnumErr2 = '<div class="alert alert-danger">
                        This user already has an account.
                        </div>';
        $updateBool = FALSE;
    }
    if ($resultemailcheck->num_rows > 0) {
        $empnumErr3 = '<div class="alert alert-danger">
                        This email is already in use.
                        </div>';
        $updateBool = FALSE;
    }
    if (!preg_match("/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $emppass)) {
        $passwordErr2 = '<div class="alert alert-warning">
                        Password must be atleast 8 characters long and must be a combination of uppercase letters, lowercase letters and numbers.
                        </div>';
        $updateBool = FALSE;
    }
    if ($emppass != $empconfpass) {
        $confirmErr2 = '<div class="alert alert-warning">
                        Password does not match the confirm password.
                        </div>';
        $updateBool = FALSE;
    }

    if ($updateBool == TRUE) {

        //protect password
        $hashedPwd = password_hash($emppass, PASSWORD_DEFAULT);
        $hashedSecAns = password_hash($empsecans, PASSWORD_DEFAULT);
        //insert the user into the database

        $sqladd = $conn->prepare("INSERT INTO users_temp VALUES ('',?,?,?,?,?,?,?,?,?,?,?,?,'',?,?)");
        $sqladd->bind_param("ssssssissisisi", $empnum, $empfname, $empmname, $emplname, $empemail, $hashedPwd, $forgot, $emprole, $empsection, $empsecq, $hashedSecAns, $hidden, $vcode, $verified);
        $sqladd->execute();
        $sqladd->close();

//        LOGS
//        $perval = 'Personnel ID: ' . $newpid . ', ' . $newpfname . ' ' . $newpmname . ' ' . $newplname . ' (' . $newrole . '), ' . $newperteam . ' Team';
//
//        $peraction = "Add Personnel (For Activation)";
//
//        $logper = $conn->prepare("INSERT INTO addlogs VALUES ('',?,?,NOW(),?)");
//        $logper->bind_param("sss", $peraction, $_SESSION['user_name'], $perval);
//        $logper->execute();
//        $logper->close();

        $_SESSION['empnum'] = $empnum;
        $_SESSION['empfname'] = $empfname;
        $_SESSION['empmname'] = $empmname;
        $_SESSION['emplname'] = $emplname;
        $_SESSION['emprole'] = $emprole;
        $_SESSION['vcode'] = $vcode;
        $_SESSION['empemail'] = $empemail;
        $_SESSION['emppass'] = $hashedPwd;
        $_SESSION['empforgot'] = $forgot;
        $_SESSION['empsection'] = $empsection;
        $_SESSION['empsecq'] = $empsecq;
        $_SESSION['empseca'] = $hashedSecAns;
        $_SESSION['emphidden'] = $hidden;
        $_SESSION['empverified'] = $verified;

        $studSuccess = FALSE;

        $_SESSION['studSuccess'] = $studSuccess;
        header("Location: success.php");
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

        <style>
            .form-text {
                color: red;
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
                    <div class="tab">
                        <button class="tablinks active" onclick="openTab(event, 'Student')" <?php echo $tab1; ?>>Student</button>
                        <button class="tablinks active" onclick="openTab(event, 'Faculty')" <?php echo $tab2; ?>>Faculty</button>
                    </div>

                    <?php $_SESSION['tab'] = '1'; ?>

                    <div class="tabcontent" id="Student">
                        <p style="padding-top: 1px;"></p>
                        <h3>Register as Student</h3><hr>

                        <div class="alert alert-danger">Fields with <em>asterisk (*)</em> are <b>required.</b></div>

                        <form id="student-register" action="" method="POST">
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
                                        <input type="text" class="form-control" placeholder="Middle Name" value="<?php echo $studmname; ?>" name="studmname"/>
                                        <?php echo $midErr; ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Last Name *" value="<?php echo $studlname; ?>" name="studlname" required/>
                                        <?php echo $lastErr; ?>
                                    </div>
                                    <div class="form-group">
                                        <select required class="form-control" name="studsection">
                                            <option class="hidden" value="" selected disabled>Year and Section: *</option>
                                            <?php
                                            $prof = mysqli_query($conn, "SELECT * from sections WHERE hidden = '0' ORDER BY sectionname DESC");
                                            if ($prof->num_rows > 0) {
                                                while ($row = $prof->fetch_assoc()) {
                                                    $sectionname = $row['sectionname'];
                                                    $sectionno = $row['sectionno'];
                                                    echo "<option value='" . $sectionname . "'>" . $sectionname . "</option>";
                                                }
                                            } else {
                                                echo"<option value=''></option>";
                                            }
                                            ?> 
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control" placeholder="E-mail (ust.edu.ph) *" value="<?php echo $studemail; ?>" name="studemail" required/>
                                        <?php echo $emailErr; ?>
                                        <?php echo $emailErr3; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="password" class="form-control" id = "studpass" placeholder="Password *" value="" name="studpass" required/>
                                        <?php echo $passwordErr; ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control"  placeholder="Confirm Password *" value="" name="studconfpass" required/>
                                        <?php echo $confirmErr; ?>
                                    </div>
                                    <div class="form-group">
                                        <select required class="form-control" name="studsecq">
                                            <option class="hidden" value="" selected disabled>Security Question: *</option>
                                            <?php
                                            $sql = "SELECT * FROM secq";
                                            $result = mysqli_query($conn, $sql);
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $secqno = $row['secqno'];
                                                    $secq = $row['secq'];

                                                    echo "<option value='" . $secqno . "'> " . $secq . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" onfocusout="setAttribute('type', 'password');" onfocus="setAttribute('type', 'text');" class="form-control" placeholder="Answer *" value="" name="studsecans" required/>
                                    </div>
                                    <div class="custom-control custom-checkbox form-group">
                                        <input type="checkbox" class="custom-control-input" name="customCheck1" id="customCheck1" required>
                                        <label class="custom-control-label" for="customCheck1">
                                            I agree to the <a href="https://www.privacy.gov.ph/data-privacy-act/" target="_blank">R.A. 10173 (Data Privacy Act of 2012)</a> and I hereby confirm that the information given in this form is true, complete and accurate.
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

                        <div class="alert alert-danger">Fields with <em>asterisk (*)</em> are <b>required.</b></div>

                        <form id="faculty-register" action="" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Faculty Number *" value="<?php echo $empnum; ?>" name="empnum" required/>
                                        <?php
                                        echo $empnumErr2;
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
                                        <input type="text" class="form-control" placeholder="Middle Name" value="<?php echo $empmname; ?>" name="empmname"/>
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
                                        echo $empnumErr3;
                                        ?>
                                    </div>  
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="password" class="form-control" id="emppass" placeholder="Password *" value="" name="emppass" required/>
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
                                            <?php
                                            $sql2 = "SELECT * FROM secq";
                                            $result2 = mysqli_query($conn, $sql2);
                                            if ($result2->num_rows > 0) {
                                                while ($row = $result2->fetch_assoc()) {
                                                    $secqno = $row['secqno'];
                                                    $secq = $row['secq'];

                                                    echo "<option value='" . $secqno . "'> " . $secq . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" onfocusout="setAttribute('type', 'password');" onfocus="setAttribute('type', 'text');" class="form-control" placeholder="Answer *" value="" name="empsecans" required />
                                    </div>
                                    <div class="custom-control custom-checkbox form-group">
                                        <input type="checkbox" class="custom-control-input" name="customCheck2" id="customCheck2" required>
                                        <label class="custom-control-label" for="customCheck2">
                                            I agree to the <a href="https://www.privacy.gov.ph/data-privacy-act/" target="_blank">R.A. 10173 (Data Privacy Act of 2012)</a> and I hereby confirm that the information given in this form is true, complete and accurate.
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

<!--jQuery Validation-->
<script src="./js/jquery-3.3.1.js"></script>
<script src="./js/jquery-validation-1.17.0/dist/jquery.validate.min.js"></script>
<script src="./js/registervalidate.js"></script>



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



<?php
include '../../include/controller.php';
include '../../include/upload.php';

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
    header("location:/iicshd/index.php");
}


$empnum = $empfname = $empmname = $emplname = $empsection = $empemail = $emppass = $empconfpass = $empsecq = $empsecans = $emprole = $forgot = $hidden = "";
$studnumErr = $empnumErr2 = $emailErr3 = $empnumErr3 = $numErr = $numErr2 = $firstErr = $firstErr2 = $midErr = $midErr2 = $lastErr = $lastErr2 = $emailErr = $emailErr2 = $confirmErr = $confirmErr2 = $passwordErr = $passwordErr2 = "";

if (isset($_GET['createAdmin'])) {
    $createAdmin = $_GET['createAdmin'];
} else {
    $createAdmin = '';
}

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
    $empsection = "admin";
    $emprole = "admin";
    $forgot = $hidden = "0";
    $verified = "1";
    $vcode = "admin";

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
//    if (!preg_match("/^[0-9]{10,10}$/", $empnum)) {
//        $numErr2 = '<div class="alert alert-warning">
//                        Input must contain numbers only and should have 10 digits.
//    </div>';
//        $updateBool = FALSE;
//    }
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

        $sqladd = $conn->prepare("INSERT INTO users VALUES ('',?,?,?,?,?,?,?,?,?,?,?,?,'',?,?)");
        $sqladd->bind_param("ssssssissisisi", $empnum, $empfname, $empmname, $emplname, $empemail, $hashedPwd, $forgot, $emprole, $empsection, $empsecq, $hashedSecAns, $hidden, $vcode, $verified);
        $sqladd->execute();
        $sqladd->close();

        $_GET['createAdmin'] = 'success';
        header("Location: cpanel5.php?createAdmin=success");
        exit;
    } else {
        
    }
}
?>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="../../img/favicon.png">

        <title>IICS Help Desk - Admin</title>

        <!-- Bootstrap core CSS -->
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link href="../../css/dashboard.css" rel="stylesheet">
        <link href="../../fa-5.5.0/css/fontawesome.css" rel="stylesheet">

        <!-- Font Awesome JS -->
        <script defer src="../../fa-5.5.0/js/solid.js"></script>
        <script defer src="../../fa-5.5.0/js/fontawesome.js"></script>

        <style>
            .header {
                padding: 10px;
                text-align: center;
                background: #2e2e2e;
                color: white;
                font-size: 30px;
                position:fixed;
                bottom:0;                
                border-top: 5px solid #b00f24;
            }

            th { font-size: 14px; }
            td { font-size: 14px; }
        </style>


        <!-- DataTable-->
        <link rel="stylesheet" href="../../DataTables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="../../DataTables/Responsive-2.2.1/css/responsive.bootstrap4.min.css">
        <link rel="stylesheet" href="../../DataTables/Buttons-1.5.1/css/buttons.dataTables.min.css">

    </head>

    <body>

        <!--NEW NAVBAR-->

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand">
                <img src = "../../img/logosolo.png"></img>       
                <span class="mb-0 h6" style="color:white;">IICS Help Desk</span> 
            </a>


            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav mr-auto">

                    <li class="nav-item">
                        <a class="nav-link"  style="color:white;" href="home.php">
                            <span data-feather="home"></span>
                            Home <span class="sr-only">(current)</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" style="color:white;" href="documents.php">
                            <span data-feather="file-text"></span>
                            Documents
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" style="color:white;" href="queue.php">
                            <span data-feather="users"></span>
                            Queue
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" style="color:white;" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <span data-feather="calendar"></span>
                            Schedule
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="fschedule.php">
                                <span data-feather="book-open"></span>
                                Faculty Schedule
                            </a>
                            <a class="dropdown-item" href="cschedule.php">
                                <span data-feather="book-open"></span>
                                Class Schedule
                            </a>
                            <a class="dropdown-item" href="rschedule.php">
                                <span data-feather="book-open"></span>
                                Room Schedule
                            </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="color:white;" href="stats.php">
                            <span data-feather="bar-chart-2"></span>
                            Statistics
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="color:white;" href="reports.php">
                            <span data-feather="layers"></span>
                            Reports
                        </a>
                    </li>


                </ul>

                <ul class="navbar-nav px-1">
                    <li class="nav-item text-nowrap">
                    <li class="nav-item dropdown">
                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="fas fa-envelope"></span>
                            Notifications
                        </button>
                        <div class="dropdown-menu" style="white-space: normal;">
                            <?php
                            $notifquery = "(SELECT notif.notifno, notif.notiftitle, notif.notifdesc, notif.notifaudience, notif.notifdate, users.userno FROM notif INNER JOIN users ON users.userno = notif.notifaudience WHERE notif.notifaudience = '".$_SESSION['userno']."' ORDER by notif.notifdate DESC)"
                                    . " UNION "
                                    . "(SELECT notif.notifno, notif.notiftitle, notif.notifdesc, notif.notifaudience, notif.notifdate, notif.notifno as userno FROM notif WHERE notif.notifaudience = 'all' ORDER by notif.notifdate DESC)"
                                    . " UNION "
                                    . "(SELECT notif.notifno, notif.notiftitle, notif.notifdesc, notif.notifaudience, notif.notifdate, notif.notifno as userno FROM notif WHERE notif.notifaudience = 'admin' ORDER by notif.notifdate DESC)";
                            $notifresult = $conn->query($notifquery);

                            if ($notifresult->num_rows > 0) {
                                while ($row = $notifresult->fetch_assoc()) {
                                    $notiftitle = $row['notiftitle'];
                                    $notifdesc = $row['notifdesc'];
                                    $notifdate = $row['notifdate'];

                                    echo '
                                            <a class="dropdown-item" href="#" style="width: 300px; white-space: normal;">
                                                <span style="font-size: 13px;"><strong> ' . $notiftitle . ' </strong></span><br>
                                                ' . $notifdesc . ' <br>
                                                <span style="font-size: 10px;"> ' . $notifdate . ' </span><br>
                                            </a>
                                            <div class="dropdown-divider"></div>';
                                }
                            } else {
                                echo '
                                            <a class="dropdown-item" href="#" style="width: 300px; white-space: normal;">
                                                No new notifications.
                                            </a>';
                            }
                            ?>

                        </div>
                    </li>
                    </li>
                </ul>
                
                <ul class="navbar-nav px-3">
                    <li class="nav-item text-nowrap">
                    <li class="nav-item dropdown">
                        <button type="button" class="btn btn-dark btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span data-feather="user"></span>
                            <?php
                            echo $_SESSION['user_name'];
                            ?>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item active" href="cpanel.php">
                                <i class="fas fa-sliders-h"></i>
                                Control Panel
                            </a>
                            <a class="dropdown-item" href="account.php">
                                <i class="fas fa-user-cog"></i>
                                Account
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../../logout.php">
                                <span data-feather="log-out"></span>  Log Out
                            </a>
                        </div>
                    </li>
                    </li>
                </ul>
            </div>
        </nav>


        <div class="container-fluid">


            <main role="main" class="col-md-12 ml-sm-auto">

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Control Panel</h1>
                </div>

                <?php
                if ($createAdmin == TRUE) {
                    echo '<div class="alert alert-success"><span class="fas fa-check"></span> Admin created successfully!</div>';
                } else {
                    echo '';
                }
                ?>

                <div class="row">

                    <div class="col-sm-2">
                        <div class="card">
                            <ul class="list-group list-group-flush">
                                <a href="cpanel.php"><li class="list-group-item">General <span style="float:right;" class="fas fa-caret-right"></span></li></a>
                                <a href="cpanel2.php"><li class="list-group-item">User Accounts <span style="float:right;" class="fas fa-caret-right"></span></li></a>
                                <a href="cpanel3.php"><li class="list-group-item">Queue Settings <span style="float:right;" class="fas fa-caret-right"></span></li></a>
                                <a href="cpanel4.php"><li class="list-group-item">Manage Uploads <span style="float:right;" class="fas fa-caret-right"></span></li></a>
                                <a href="cpanel5.php"><li class="list-group-item active">Create Admin <span style="float:right;" class="fas fa-caret-right"></span></li></a>
                            </ul>
                        </div>
                    </div>

                    <div class="col-sm-10">
                        <div class="card">
                            <h4 class="card-header">Create Admin</h4>
                            <div class="card-body">

                                <form id="faculty-register" action="" method="POST">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Username *" value="<?php echo $empnum; ?>" name="empnum" required/>
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
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                </div>
                <br><br><br>

            </main>

        </div>

        <div class="container-fluid header">
            <div align="center" style="font-size: 11px; color:white;">
                IICS Help Desk © 2019
            </div>
        </div>



        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="../../js/jquery-3.3.1.js" ></script>
        <script>window.jQuery || document.write('<script src="../../js/jquery-3.3.1.js"><\/script>')</script>
        <script src="../../js/popper.js"></script>
        <script src="../../js/bootstrap.min.js"></script>

        <!-- DataTable js -->
        <script src="../../DataTables/DataTables-1.10.16/js/jquery.dataTables.min.js"></script>
        <script src="../../DataTables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
        <script src="../../DataTables/Responsive-2.2.1/js/responsive.bootstrap4.min.js"></script>

        <!-- DatatableButtons -->
        <script src="../../DataTables/Buttons-1.5.1/js/dataTables.buttons.min.js"></script>
        <script src="../../DataTables/Buttons-1.5.1/js/buttons.bootstrap4.min.js"></script>
        <script src="../../DataTables/Buttons-1.5.1/js/buttons.flash.min.js"></script>
        <script src="../../DataTables/JSZip-2.5.0/jszip.min.js"></script>
        <script src="../../DataTables/pdfmake-0.1.32/pdfmake.min.js"></script>
        <script src="../../DataTables/pdfmake-0.1.32/vfs_fonts.js"></script>
        <script src="../../DataTables/Buttons-1.5.1/js/buttons.html5.min.js"></script>
        <script src="../../DataTables/Buttons-1.5.1/js/buttons.print.min.js"></script>
        <!-- Icons -->
        <script src="../../js/feather.min.js"></script>
        <script>
                                                    feather.replace()
        </script>

        <script>
            $(document).ready(function () {
<?php
$thisDate = date("m/d/Y");
?>

                $('#students').DataTable({
                    "bLengthChange": true,
                    initComplete: function () {
                        this.api().columns().every(function () {
                            var column = this;
                            var select = $('<select><option value="">Show all</option></select>')
                                    .appendTo($(column.footer()).empty())
                                    .on('change', function () {
                                        var val = $.fn.dataTable.util.escapeRegex(
                                                $(this).val()
                                                );

                                        column
                                                .search(val ? '^' + val + '$' : '', true, false)
                                                .draw();
                                    });

                            column.data().unique().sort().each(function (d, j) {
                                select.append('<option value="' + d + '">' + d + '</option>')
                            });
                        });
                    }



                });
            });

            $(document).ready(function () {
<?php
$thisDate = date("m/d/Y");
?>

                $('#facultymembers').DataTable({
                    "bLengthChange": true,
                    initComplete: function () {
                        this.api().columns().every(function () {
                            var column = this;
                            var select = $('<select><option value="">Show all</option></select>')
                                    .appendTo($(column.footer()).empty())
                                    .on('change', function () {
                                        var val = $.fn.dataTable.util.escapeRegex(
                                                $(this).val()
                                                );

                                        column
                                                .search(val ? '^' + val + '$' : '', true, false)
                                                .draw();
                                    });

                            column.data().unique().sort().each(function (d, j) {
                                select.append('<option value="' + d + '">' + d + '</option>')
                            });
                        });
                    }



                });
            });

        </script>

        <script>
            $('.collapse').on('shown.bs.collapse', function () {
                $(this).parent().find(".fa-plus-circle").removeClass("fa-plus-circle").addClass("fa-minus-circle");
            }).on('hidden.bs.collapse', function () {
                $(this).parent().find(".fa-minus-circle").removeClass("fa-minus-circle").addClass("fa-plus-circle");
            });
        </script>

    </body>
</html>

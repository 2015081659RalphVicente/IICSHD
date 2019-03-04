
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

$secqErr = $_FILES['fileToUpload'] = $sectionErr = '';

if (isset($_GET['addSecQ'])) {
    $addSecQ = $_GET['addSecQ'];
} else {
    $addSecQ = '';
}

if (isset($_GET['addSection'])) {
    $addSection = $_GET['addSection'];
} else {
    $addSection = '';
}

if (isset($_GET['activate'])) {
    $activate = $_GET['activate'];
} else {
    $activate = '';
}

if (isset($_GET['deactivate'])) {
    $deactivate = $_GET['deactivate'];
} else {
    $deactivate = '';
}

if (isset($_GET['upload'])) {
    $upload = $_GET['upload'];
} else {
    $upload = '';
}

if (isset($_GET['updatesched'])) {
    $updatesched = $_GET['updatesched'];
} else {
    $updatesched = '';
}

if (isset($_GET['uploadfail'])) {
    $uploadfail = $_GET['uploadfail'];
} else {
    $uploadfail = '';
}

if (isset($_GET['deletesec'])) {
    $deletesec = $_GET['deletesec'];
} else {
    $deletesec = '';
}

if (isset($_GET['editsec'])) {
    $editsec = $_GET['editsec'];
} else {
    $editsec = '';
}


if (isset($_POST['addnewsecq'])) {
    $newsecq = $_POST['newsecq'];

    $bool = TRUE;

    $secqcheck = $conn->prepare("SELECT secqno FROM secq WHERE secq=? ");
    $secqcheck->bind_param("s", $newsecq);
    $secqcheck->execute();

    $secqcheckresult = $secqcheck->get_result();

    if ($secqcheckresult->num_rows > 0) {
        $secqErr = '<div class="alert alert-warning">
                        <strong>Security Question</strong> already exists!
         </div>';
        $bool = FALSE;
    }

    if ($bool == TRUE) {

        $addnewsec = $conn->prepare("INSERT INTO secq VALUES ('', ?)");
        $addnewsec->bind_param("s", $newsecq);

        $addnewsec->execute();
        $addnewsec->close();

        $passval = 'Added security question successfully.';

        $passaction = "Add Security Question";
        $logpass = $conn->prepare("INSERT INTO updatelogs VALUES ('',?,?,NOW(),?)");
        $logpass->bind_param("sss", $passaction, $_SESSION['user_name'], $passval);
        $logpass->execute();
        $logpass->close();


        $_GET['addSecQ'] = 'success';
        echo '<script>window.location.href="cpanel.php?addSecQ=success"</script>';
    }
}

if (isset($_POST['addSection'])) {
    $newsection = $_POST['newSection'];

    $bool = TRUE;

    $secqcheck = $conn->prepare("SELECT sectionno FROM sections WHERE sectionname=? ");
    $secqcheck->bind_param("s", $newsection);
    $secqcheck->execute();

    $secqcheckresult = $secqcheck->get_result();

    if ($secqcheckresult->num_rows > 0) {
        $sectionErr = '<div class="alert alert-warning">
                        <strong>Section</strong> already exists!
         </div>';
        $bool = FALSE;
    }

    if ($bool == TRUE) {

        $addnewsec = $conn->prepare("INSERT INTO sections VALUES ('', ?, '0')");
        $addnewsec->bind_param("s", $newsection);

        $addnewsec->execute();
        $addnewsec->close();

        $passval = 'Added section successfully.';

        $passaction = "Add Section";
        $logpass = $conn->prepare("INSERT INTO updatelogs VALUES ('',?,?,NOW(),?)");
        $logpass->bind_param("sss", $passaction, $_SESSION['user_name'], $passval);
        $logpass->execute();
        $logpass->close();


        $_GET['addSection'] = 'success';
        echo '<script>window.location.href="cpanel.php?addSection=success"</script>';
    }
}

if (isset($_POST['updateSched'])) {
    // sql to deactivate a record
    $schedid = $_POST['schedID'];
    $schedlink = $_POST['schedLink'];
    $schedname = $_POST['schedName'];

    $bool = TRUE;

    if ($bool == TRUE) {
        $stmt = $conn->prepare("UPDATE schedule SET schedlink =? WHERE schedno= ? ");

        $stmt->bind_param("si", $schedlink, $schedid);
        $stmt->execute();
        $stmt->close();


        $passval = 'Updated schedule link of ' . $schedname . ' successfully.';

        $passaction = "Update Schedule Link";
        $logpass = $conn->prepare("INSERT INTO updatelogs VALUES ('',?,?,NOW(),?)");
        $logpass->bind_param("sss", $passaction, $_SESSION['user_name'], $passval);
        $logpass->execute();
        $logpass->close();

        $notiftitle = "Schedule Updated";
        $notifdesc = "Updated " . $schedname . ".";
        $notifaudience = "all";

        $notif = $conn->prepare("INSERT INTO notif VALUES ('',?,?,?,?,NOW())");
        $notif->bind_param("isss", $_SESSION['userno'], $notiftitle, $notifdesc, $notifaudience);
        $notif->execute();
        $notif->close();

        $_GET['updatesched'] = 'success';

        header("Location: cpanel.php?updatesched=success");
    } else {
        $_GET['updatesched'] = 'failed';
        header("location: cpanel.php?updatesched=failed");
    }
}

if (isset($_POST['deactivate'])) {
    // sql to deactivate a record
    $deactivate_id = $_POST['deactivate_id'];
    $deacname = $_POST['deacname'];

    $bool = TRUE;

    if ($bool == TRUE) {
        $sql = "UPDATE sections SET hidden = 1 where sectionno='$deactivate_id'";
        if ($conn->query($sql) === TRUE) {
            $sql = "UPDATE sections SET hidden = 1 where sectionno='$deactivate_id'";
            if ($conn->query($sql) === TRUE) {

                $_GET['deactivate'] = 'success';
                header("location: cpanel.php?deactivate=success");
            } else {
                echo "Error deleting record: " . $conn->error;
            }
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        $_GET['deactivate'] = 'failed';
        header("location: cpanel.php?deactivate=failed");
    }
}

if (isset($_POST['activate'])) {
    // sql to activate a record
    $activate_id = $_POST['activate_id'];
    $acname = $_POST['acname'];

    $sql = "UPDATE sections SET hidden = 0 where sectionno='$activate_id'";
    if ($conn->query($sql) === TRUE) {
        $sql = "UPDATE sections SET hidden = 0 where sectionno='$activate_id'";

        if ($conn->query($sql) === TRUE) {
            $_GET['activate'] = 'success';
            header("location: cpanel.php?activate=success");
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

if (isset($_POST['editsec'])) {
    // sql to deactivate a record
    $deactivate_id = $_POST['editsec_id'];
    $deacname = $_POST['editsecname'];

    $bool = TRUE;

    if ($bool == TRUE) {
        $sql = "UPDATE sections SET sectionname = '$deacname' where sectionno='$deactivate_id'";
        if ($conn->query($sql) === TRUE) {
            $sql = "UPDATE sections SET sectionname = '$deacname' where sectionno='$deactivate_id'";
            if ($conn->query($sql) === TRUE) {

                $_GET['editsec'] = 'success';
                header("location: cpanel.php?editsec=success");
            } else {
                echo "Error deleting record: " . $conn->error;
            }
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        $_GET['editsec'] = 'failed';
        header("location: cpanel.php?editsec=failed");
    }
}

if (isset($_POST['deletesec'])) {
    // sql to activate a record
    $activate_id = $_POST['deletesec_id'];
    $acname = $_POST['deletesecname'];

    $sql = "DELETE FROM sections WHERE sectionno='$activate_id'";
    if ($conn->query($sql) === TRUE) {
        $sql = "DELETE FROM sections WHERE sectionno='$activate_id'";

        if ($conn->query($sql) === TRUE) {
            $_GET['deletesec'] = 'success';
            header("location: cpanel.php?deletesec=success");
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "Error deleting record: " . $conn->error;
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
                            $notifquery = "SELECT notif.notifno, notif.notiftitle, notif.notifdesc, notif.notifaudience, notif.notifdate, users.userno 
                                                    FROM notif 
                                                INNER JOIN users 
                                                ON users.userno = notif.notifaudience 
                                                WHERE notif.notifaudience = '1' 
                                                UNION ALL 
                                            SELECT notif.notifno, notif.notiftitle, notif.notifdesc, notif.notifaudience, notif.notifdate, notif.notifno as userno 
                                                    FROM notif 
                                                WHERE notif.notifaudience = 'all' 
                                                UNION ALL
                                            SELECT notif.notifno, notif.notiftitle, notif.notifdesc, notif.notifaudience, notif.notifdate, notif.notifno as userno 
                                                    FROM notif 
                                                    WHERE notif.notifaudience = 'admin' 
                                            ORDER BY notifno DESC LIMIT 4";
                            $notifresult = $conn->query($notifquery);

                            if ($notifresult->num_rows > 0) {
                                while ($row = $notifresult->fetch_assoc()) {
                                    $notiftitle = $row['notiftitle'];
                                    $notifdesc = $row['notifdesc'];
                                    $notifdate = $row['notifdate'];

                                    echo '
                                            <a class="dropdown-item" ';

                                    if ($notiftitle == "New Announcement Posted") {
                                        echo 'href="home.php"';
                                    }
                                    if ($notiftitle == "New Queue Ticket") {
                                        echo 'href="queue.php"';
                                    }
                                    if ($notiftitle == "Schedule Updated") {
                                        echo 'href="fschedule.php"';
                                    }
                                    echo 'style="width: 300px; white-space: normal;">
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
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="notifications.php" style="color: blue; width: 300px; white-space: normal;">
                                <center>View All Notifications</center>
                            </a>
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
                if ($addSecQ == TRUE) {
                    echo '<div class="alert alert-success"><span class="fas fa-check"></span> Security question added successfully!</div>';
                } else {
                    echo '';
                }

                if ($editsec == TRUE) {
                    echo '<div class="alert alert-success"><span class="fas fa-check"></span> Section edited successfully!</div>';
                } else {
                    echo '';
                }

                if ($deletesec == TRUE) {
                    echo '<div class="alert alert-success"><span class="fas fa-check"></span> Section deleted successfully!</div>';
                } else {
                    echo '';
                }

                if ($addSection == TRUE) {
                    echo '<div class="alert alert-success"><span class="fas fa-check"></span> Section added successfully!</div>';
                } else {
                    echo '';
                }

                if ($upload == TRUE) {
                    echo '<div class="alert alert-success"><span class="fas fa-check"></span> Document uploaded successfully!</div>';
                } else {
                    echo '';
                }

                if ($activate == TRUE) {
                    echo '<div class="alert alert-success"><span class="fas fa-check"></span> Section activated successfully!</div>';
                } else {
                    echo '';
                }

                if ($updatesched == TRUE) {
                    echo '<div class="alert alert-success"><span class="fas fa-check"></span> Updated schedule link successfully!</div>';
                } else {
                    echo '';
                }

                if ($deactivate == TRUE) {
                    echo '<div class="alert alert-success"><span class="fas fa-check"></span> Section deactivated successfully!</div>';
                } else {
                    echo '';
                }


                if ($uploadfail == TRUE) {
                    echo '<div class="alert alert-danger"><span class="fas fa-times"></span> <b>Document upload failed! </b><br>'
                    . 'It may be caused by any of the following reasons: <br>'
                    . '<ul>'
                    . '<li>The file already exists.</li>'
                    . '<li>The file is too large. The maximum file size is 20MB.</li>'
                    . '<li>Only .pdf, .docx and .doc files are allowed.</li>'
                    . '</ul>'
                    . '</div>';
                } else {
                    echo '';
                }
                ?>

                <div class="row">

                    <div class="col-sm-2">
                        <div class="card">
                            <ul class="list-group list-group-flush">
                                <a href="cpanel.php"><li class="list-group-item active">General <span style="float:right;" class="fas fa-caret-right"></span></li></a>
                                <a href="cpanel2.php"><li class="list-group-item">User Accounts <span style="float:right;" class="fas fa-caret-right"></span></li></a>
                                <a href="cpanel3.php"><li class="list-group-item">Queue Settings <span style="float:right;" class="fas fa-caret-right"></span></li></a>
                                <a href="cpanel4.php"><li class="list-group-item">Manage Uploads <span style="float:right;" class="fas fa-caret-right"></span></li></a>
                                <a href="cpanel5.php"><li class="list-group-item">Create Admin <span style="float:right;" class="fas fa-caret-right"></span></li></a>
                            </ul>
                        </div>
                    </div>

                    <div class="col-sm-10">
                        <div class="card">
                            <h4 class="card-header">General Settings</h4>
                            <div class="card-body">

                                <h5 class="card-title">Registration - Add Security Question</h5>
                                <hr>
                                <form action="" method="POST">
                                    <div class="alert alert-secondary"><span class="fas fa-exclamation-circle"></span>
                                        Security questions are mandatory each time a user creates an account. 
                                        This provides users an extra security layer and serves as an authenticator should they forget their password.
                                    </div>
                                    <label for="oldPw" class="control-label">New Security Question:</label>
                                    <input type="text" class="form-control" id="newsecq" required name="newsecq">
                                    <?php echo $secqErr; ?>
                                    <br>
                                    <button type="submit" name = "addnewsecq" class="btn btn-success float-right">Add</button>
                                </form>

                                <br>

                                <h5 class="card-title">Manage Sections</h5>
                                <hr>
                                <div class="alert alert-secondary"><span class="fas fa-exclamation-circle"></span>
                                    The section list must be updated each time a new semester starts.
                                </div>
                                <hr>
                                <table id="sections" class="table table-striped table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Section</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $query = mysqli_query($conn, "SELECT * FROM sections");
                                        if ($query->num_rows > 0) {
                                            while ($row = $query->fetch_assoc()) {
                                                $getsecno = $row['sectionno'];
                                                $getsecname = $row['sectionname'];
                                                $getstatus = $row['hidden'];

                                                echo "<tr>"
                                                . "<td>" . $getsecname . "</td>";
                                                if ($getstatus == 0) {
                                                    echo "<td> Active </td>"
                                                    . "<td>" . "<a href='#deactivate" . $getsecno . "'data-toggle='modal'><button type='button' class='btn btn-danger btn-sm' title='Deactivate'><span class='fas fa-lock' aria-hidden='true'></span></button></a>" . "</td>";
                                                } else {
                                                    echo "<td> Deactivated </td>"
                                                    . "<td>" . "<a href='#activate" . $getsecno . "'data-toggle='modal'><button type='button' class='btn btn-success btn-sm' title='Activate'><span class='fas fa-lock' aria-hidden='true'></span></button></a>" . "</td>";
                                                }
                                                echo "<td>" . "<a href='#editsec" . $getsecno . "'data-toggle='modal'><button type='button' class='btn btn-dark btn-sm' title='Edit'><span class='fas fa-edit' aria-hidden='true'></span></button></a>" . "</td>";
                                                echo "<td>" . "<a href='#deletesec" . $getsecno . "'data-toggle='modal'><button type='button' class='btn btn-danger btn-sm' title='Delete'><span class='fas fa-trash' aria-hidden='true'></span></button></a>" . "</td>";

                                                echo '<div id="activate';
                                                echo $getsecno;
                                                echo'" class="modal fade" role="dialog">
                                                                <div class="modal-dialog modal-lg">
                                                                    <form method="post">
                                                                        <div class="modal-content">

                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title">Activate Section</h4>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="activate_id" value="';
                                                echo $getsecno;
                                                echo '">
                                                                                <input type="hidden" name="acname" value="';
                                                echo $getsecname;
                                                echo '">

                                                                                <div class="alert alert-warning"><p>Are you sure you want to ACTIVATE the section <strong>';
                                                echo $getsecname;
                                                echo'</strong>?</p>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="submit" name="activate" class="btn btn-success"><span class="fas fa-check"></span> Yes</button>
                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fas fa-times"></span> No</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>';


                                                echo '<div id="deletesec';
                                                echo $getsecno;
                                                echo'" class="modal fade" role="dialog">
                                                                <div class="modal-dialog modal-lg">
                                                                    <form method="post">
                                                                        <div class="modal-content">

                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title">Delete Section</h4>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="deletesec_id" value="';
                                                echo $getsecno;
                                                echo '">
                                                                                <input type="hidden" name="deletesecname" value="';
                                                echo $getsecname;
                                                echo '">

                                                                                <div class="alert alert-danger"><p>Are you sure you want to DELETE the section <strong>';
                                                echo $getsecname;
                                                echo'</strong>?</p>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="submit" name="deletesec" class="btn btn-success"><span class="fas fa-check"></span> Yes</button>
                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fas fa-times"></span> No</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>';


                                                echo '<div id="editsec';
                                                echo $getsecno;
                                                echo'" class="modal fade" role="dialog">
                                                                <div class="modal-dialog modal-lg">
                                                                    <form method="post">
                                                                        <div class="modal-content">

                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title">Edit Section</h4>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="editsec_id" value="';
                                                echo $getsecno;
                                                echo '">
                                                                                <input type="text" class="form-control" name="editsecname" value="';
                                                echo $getsecname;
                                                echo '">
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="submit" name="editsec" class="btn btn-success"><span class="fas fa-check"></span> Save Changes</button>
                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fas fa-times"></span> No</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>';



                                                echo '            <div id="deactivate';
                                                echo $getsecno;
                                                echo'" class="modal fade" role="dialog">
                                                                <div class="modal-dialog modal-lg">
                                                                    <form method="post">
                                                                        <div class="modal-content">

                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title">Deactivate Section</h4>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="deactivate_id" value="';
                                                echo $getsecno;
                                                echo'">
                                                                                <input type="hidden" name="deacname" value="';
                                                echo $getsecname;
                                                echo'">

                                                                                <div class="alert alert-warning"><p>Are you sure you want to DEACTIVATE the section <strong>';
                                                echo $getsecname;
                                                echo'</strong>?</p>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="submit" name="deactivate" class="btn btn-success"><span class="fas fa-check"></span> Yes</button>
                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fas fa-times"></span> No</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Section</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <hr>
                                <form action="" method="post" enctype="multipart/form-data">
                                    <label for="newSection">Add New Section: </label>

                                    <input type="text" id="newSection" name="newSection" class="form-control" required><br>

                                    <br>
                                    <button type="submit" name = "addSection" class="btn btn-success float-right">Add</button>
                                </form>

                                <br>

                                <h5 class="card-title">Update Schedule Link</h5>
                                <hr>
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="alert alert-secondary"><span class="fas fa-exclamation-circle"></span>
                                        Class, Faculty and Room Schedules must be updated in Google Sheets before updating the link here.
                                    </div>

                                    <table id="schedule" class="table table-striped table-responsive">
                                        <thead>
                                            <tr>
                                                <th>Schedule Name</th>
                                                <th>Schedule Link</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $query = mysqli_query($conn, "SELECT * FROM schedule");
                                            if ($query->num_rows > 0) {
                                                while ($row = $query->fetch_assoc()) {
                                                    $getschedno = $row['schedno'];
                                                    $getschedname = $row['schedname'];
                                                    $getschedlink = $row['schedlink'];

                                                    echo "<tr>"
                                                    . "<td>" . $getschedname . "</td>"
                                                    . "<td>" . $getschedlink . "</td>";
                                                    echo "<td>" . "<a href='#edit" . $getschedno . "'data-toggle='modal'><button type='button' class='btn btn-dark btn-sm' title='Edit'><span class='fas fa-edit' aria-hidden='true'></span></button></a>" . "</td>";

                                                    echo '<div id="edit';
                                                    echo $getschedno;
                                                    echo'" class="modal fade" role="dialog">
                                                                <div class="modal-dialog modal-lg">
                                                                    <form method="post">
                                                                        <div class="modal-content">

                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title">Update Schedule Link of ' . $getschedname . '</h4>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="schedID" value="';
                                                    echo $getschedno;
                                                    echo '">
                                                                                <input type="hidden" name="schedName" value="';
                                                    echo $getschedname;
                                                    echo '">
                                                        

                                                                                <label for="schedule">Schedule Link: </label>

                                                                                <input type="text" id="schedLink" name="schedLink" class="form-control" placeholder=' . $getschedlink . ' required>
                                                                                
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="submit" name="updateSched" class="btn btn-success"><span class="fas fa-check"></span> Update</button>
                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fas fa-times"></span> Cancel</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>';
                                                }
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Schedule Name</th>
                                                <th>Schedule Link</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </form>

                                <hr>
                                <br>

                                <h5 class="card-title">Document Templates</h5>
                                <hr>
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="alert alert-secondary"><span class="fas fa-exclamation-circle"></span>
                                        File Upload Rules:
                                        <ul>
                                            <li>The maximum file size is 20MB.</li>
                                            <li>Only .pdf, .docx and .doc files are allowed.</li>
                                        </ul>
                                    </div>

                                    <label for="filename">Filename: </label>

                                    <input type="text" id="fileName" name="fileName" class="form-control" required><br>

                                    <label for="newfile">Upload New Document Template: </label>

                                    <input type="file" id="fileToUpload" name="fileToUpload" class="form-control" accept=".pdf, .docx, .doc" required>
                                    <br>
                                    <button type="submit" name = "uploadFile" class="btn btn-success float-right">Upload</button>
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

                $('#schedule').DataTable({
                    "bLengthChange": false,
                    pageLength: 9,
                    autoWidth: false,
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

                $('#sections').DataTable({
                    "bLengthChange": false,
                    pageLength: 10,
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

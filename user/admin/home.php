
<?php
include '../../include/controller.php';

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

if (isset($_GET['post'])) {
    $post = $_GET['post'];
} else {
    $post = '';
}
if (isset($_GET['edit'])) {
    $edit = $_GET['edit'];
} else {
    $edit = '';
}
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
} else {
    $delete = '';
}

//create announcement
if (isset($_POST['postAnnouncement'])) {
    $pTitle = clean($_POST["pTitle"]);
    $pDesc = clean($_POST["pDesc"]);

    $announceSql = $conn->prepare("INSERT INTO announcements VALUES ('', ?, ?, NOW(), ?, '0', '0')");
    $announceSql->bind_param("ssi", $pTitle, $pDesc, $_SESSION['userno']);

    if ($announceSql == TRUE) {

        $announceSql->execute();
        $announceSql->close();

        $passval = 'Announcement posted successfully.';

        $passaction = "Post Announcement";
        $logpass = $conn->prepare("INSERT INTO updatelogs VALUES ('',?,?,NOW(),?)");
        $logpass->bind_param("sss", $passaction, $_SESSION['user_name'], $passval);
        $logpass->execute();
        $logpass->close();

        $notiftitle = "New Announcement Posted";
        $notifdesc = "" . $pTitle . " posted by " . $_SESSION['user_name'] . "";
        $notifaudience = "all";

        $notif = $conn->prepare("INSERT INTO notif VALUES ('',?,?,?,?,NOW())");
        $notif->bind_param("isss", $_SESSION['userno'], $notiftitle, $notifdesc, $notifaudience);
        $notif->execute();
        $notif->close();

        $_GET['post'] = 'success';
        header("Location: home.php?post=success");
        exit;
    } else {
        $postFailed = '<div class="alert alert-danger">
                        Post Failed!
                        </div>';
    }
}

//edit announcement
if (isset($_POST['editpost'])) {
    $edit_ann_title = clean($_POST['edit_ann_title']);
    $edit_ann_no = $_POST['edit_ann_no'];
    $edit_ann_desc = $_POST['edit_ann_desc'];


//    $oldcatval=$conn->prepare("SELECT * FROM propcategory WHERE PROPCATNO=?");
//    $oldcatval->bind_param("i",$edit_id);
//    $oldcatval->execute();
//    $oldcatvalresult=$oldcatval->get_result();
//
//    $row = $oldcatvalresult->fetch_assoc();
//
//    $oldpropcatno=$row['PROPCATNO'];
//    $oldpropcat=$row['PROPCAT'];
//
//    $oldcatvalfinal= implode("**",array($oldpropcatno,$oldpropcat));
    //   $query="UPDATE propcategory SET PROPCAT='$edit_category_name' WHERE PROPCATNO='$edit_id'";
    $editquery = $conn->prepare("UPDATE announcements SET anntitle=?, anndesc=?, anndate=NOW(), userno=? WHERE annno=?");
    $editquery->bind_param("ssii", $edit_ann_title, $edit_ann_desc, $_SESSION['userno'], $edit_ann_no);
    $editquery->execute();
    $editquery->close();
//
//
//    $newcatval=$conn->prepare("SELECT * FROM propcategory WHERE PROPCATNO=?");
//    $newcatval->bind_param("i",$edit_id);
//    $newcatval->execute();
//    $newcatvalresult=$newcatval->get_result();
//    $rownew = $newcatvalresult->fetch_array(MYSQLI_ASSOC);
//
//    $newcatvalfinal= implode("**",$row);
//
//    $actiondesc="Updated Category  ";
//
//    $elogadd=$conn->prepare("INSERT INTO editlogs VALUES('',NOW(),?,?,?,?)");
//    $elogadd->bind_param("ssss",$_SESSION['user_name'],$oldcatvalfinal,$newcatvalfinal,$actiondesc);
//    $elogadd->execute();
//    $elogadd->close();

    if ($editquery == TRUE) {

        $passval = 'Announcement edited successfully.';

        $passaction = "Edit Announcement";
        $logpass = $conn->prepare("INSERT INTO updatelogs VALUES ('',?,?,NOW(),?)");
        $logpass->bind_param("sss", $passaction, $_SESSION['user_name'], $passval);
        $logpass->execute();
        $logpass->close();

        $_GET['edit'] = 'success';
        header("location: home.php?edit=success");
        exit;
    } else {
        echo "Update failed.";
    }
}

//delete announcement
if (isset($_POST['deletepost'])) {
    $delete_id = $_POST['delete_ann_no'];

    $check = $conn->prepare("SELECT * FROM announcements WHERE annno=?");
    $check->bind_param("i", $delete_id);
    $check->execute();
    $checkprop = $check->get_result();
    $check->close();

    $deletequery = $conn->prepare("UPDATE announcements SET userno=?, hidden = '1' WHERE annno=?");
    $deletequery->bind_param("ii", $_SESSION['userno'], $delete_id);


    if ($deletequery == TRUE) {

//            $oldcatquery = $conn->prepare("SELECT * FROM propcategory WHERE PROPCATNO=?");
//            $oldcatquery->bind_param("i", $delete_id);
//            $oldcatquery->execute();
//            $oldcatqueryresult = $oldcatquery->get_result();
//
//
//            $row = $oldcatqueryresult->fetch_assoc(MYSQLI_ASSOC);
//
//
//            $oldcatvalfinal = implode("**", $row);
//
//
//
//
//            $actiondesc = "Deleted Category";
//
//            $elogadd = $conn->prepare("INSERT INTO editlogs VALUES('',NOW(),?,?,'-',?)");
//            $elogadd->bind_param("sss", $_SESSION['user_name'], $oldcatvalfinal, $actiondesc);
//            $elogadd->execute();
//            $elogadd->close();
//

        $deletequery->execute();
        $deletequery->close();

        $passval = 'Announcement deleted successfully.';

        $passaction = "Delete Announcement";
        $logpass = $conn->prepare("INSERT INTO updatelogs VALUES ('',?,?,NOW(),?)");
        $logpass->bind_param("sss", $passaction, $_SESSION['user_name'], $passval);
        $logpass->execute();
        $logpass->close();

        $_GET['delete'] = 'success';
        header("location: home.php?delete=success");
        exit;
    } else {
//            $_SESSION['tabedit'] = '5';
        echo "Delete failed.";
        //ERROR HANDLING
        //header("location: adminAddSuccess.php"); 
    }
}
?>

<!doctype html>

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

                    <li class="nav-item active">
                        <a class="nav-link active" href="home.php">
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
                            <a class="dropdown-item" href="cpanel.php">
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
                    <h1 class="h2">Home</h1>
                </div>

                <?php
                if ($post == TRUE) {
                    echo '<div class="alert alert-success"><span class="fas fa-check"></span> Announcement posted!</div>';
                } else {
                    echo '';
                }

                if ($edit == TRUE) {
                    echo '<div class="alert alert-success"><span class="fas fa-check"></span> Post successfully edited!</div>';
                } else {
                    echo '';
                }

                if ($delete == TRUE) {
                    echo '<div class="alert alert-success"><span class="fas fa-check"></span> Post successfully deleted!</div>';
                } else {
                    echo '';
                }
                ?>


                <div class="accordion" id="accordionExample">

                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn bg-dark text-white" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    <span class="fas fa-plus-circle"></span> Post Announcement
                                </button>
                            </h5>
                        </div>

                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                            <div class="card-body">
                                <form action="" method="POST">

                                    <div class="form-group">
                                        <label for="title">Title <span class="require">*</span></label>
                                        <input type="text" class="form-control" name="pTitle" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea rows="2" class="form-control" name="pDesc" required ></textarea>
                                    </div>

                                    <div class="form-group">
                                        <button style="float:right;" type="submit" name="postAnnouncement" class="btn btn-success">
                                            Post
                                        </button>
                                        <br>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn bg-dark text-white" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <span class="fas fa-plus-circle"></span> My Announcements
                                </button>
                            </h5>
                        </div>


                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">

                            <div class="alert alert-warning alert-dismissible" role="alert">
                                <p style="font-size: 15px;"><strong>Note: </strong>You can manage each post by clicking the <span class="fas fa-edit"></span> button. 
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <?php
                                    $announceSelect = "SELECT announcements.annno, announcements.anntitle, announcements.anndesc, announcements.anndate, announcements.userno, users.fname, users.mname, users.lname FROM announcements LEFT JOIN users ON users.userno = announcements.userno WHERE announcements.hidden = '0' AND announcements.userno = '" . $_SESSION['userno'] . "' ORDER BY announcements.annno DESC";
                                    $result = $conn->query($announceSelect);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $annno = $row['annno'];
                                            $anntitle = $row['anntitle'];
                                            $anndesc = $row['anndesc'];
                                            $anndate = $row['anndate'];
                                            $usercreated = ($row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']);

                                            echo '                           
                                                        <div class="col-md-4">
                                                            <div class="card">
                                                                <div class="card-header bg-dark text-white">
                                                                    <h7>
                                                                    <div style="float: right;" class="btn-group" role="group">
                                                                        <a href="#edit' . $annno . '" data-toggle="modal" title="Edit Post"><button type="button" class="btn btn-outline-light btn-sm"><span class="fas fa-edit" aria-hidden="true"></span></button></a>
                                                                    </div>
                                                                    </h7>
                                                                </div>
                                                                <div class="card-body">
                                                                    <h5 class="card-title">' . $anntitle . '</h5>
                                                                    <p class="card-text" style="font-size: 12px;">' . $anndate . ' by ' . $usercreated . '</p>
                                                                    <p class="card-text" style="font-size: 15px;">' . $anndesc . '</p>
                                                                </div>
                                                            </div>
                                                            <br>
                                                        </div>
                                                        <br>';

                                            echo '<div id="edit' . $annno . '" class="modal fade" role="dialog">
                                                        <form method="post">
                                                            <div class="modal-dialog modal-lg">
                                                                <!-- Modal content-->
                                                                <div class="modal-content">
                                                                    <div class="modal-header">

                                                                        <h4 class="modal-title">Edit Post</h4>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div class="alert alert-danger alert-dismissible" role="alert">
                                                                            <p style="font-size: 15px;"><strong>Note: </strong>Deleting a post will send it to your <a href="account4.php">archives</a> for tracking purposes. 
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-sm-12">
                                                                                <strong><h5>Title: </h5></strong>
                                                                                    <span><input type="text" class="form-control" name="edit_ann_title" value="' . $anntitle . '"></span>
                                                                                <br>
                                                                                <strong><h5>Description: </h5></strong>
                                                                                    <span><textarea rows="2" class="form-control" name="edit_ann_desc" required>' . $anndesc . '</textarea>
                                                                                <input type="hidden" name="edit_ann_no" value="' . $annno . '">
                                                                                <input type="hidden" name="delete_ann_no" value="' . $annno . '">
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <div class="modal-footer">
                                                                            <button style="float: right;" type="button" class="btn btn-secondary btn-m" data-dismiss="modal"><span class="fas fa-times"></span> Cancel</button> 
                                                                            <button style="float: left;" type="submit" name="deletepost" class="btn btn-danger btn-m"><span class="fas fa-trash"></span> Delete Post</button>
                                                                            <button style="float: right;" type="submit" name="editpost" class="btn btn-success btn-m"><span class="fas fa-save" ></span> Save Changes</button>
                                                                            
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>';
                                        }
                                    } else {
                                        echo "<h5>You haven't made any announcements yet.</h5>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>

                <h5>Announcements</h5>
                <hr>

                <?php
                $announceSelect = "SELECT announcements.annno, announcements.anntitle, announcements.anndesc, announcements.anndate, announcements.userno, users.fname, users.mname, users.lname FROM announcements LEFT JOIN users ON users.userno = announcements.userno WHERE announcements.hidden = '0' ORDER BY announcements.annno DESC";
                $result = $conn->query($announceSelect);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $annno = $row['annno'];
                        $anntitle = $row['anntitle'];
                        $anndesc = $row['anndesc'];
                        $anndate = $row['anndate'];
                        $usercreated = ($row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']);

                        echo '<div class="card">
                                        <div class="card-header bg-dark text-white">
                                            <h6></h6>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">' . $anntitle . '</h5>
                                            <p class="card-text" style="font-size: 12px;">' . $anndate . ' by ' . $usercreated . '</p>
                                            <p class="card-text" style="font-size: 15px;">' . $anndesc . '</p>
                                        </div>
                                  </div><br>';
                    }
                } else {
                    echo "<h5>There are no announcements yet.</h5>";
                }
                ?>


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

                $('#myannouncements').DataTable({
                    "bLengthChange": false,
                    pageLength: 5,
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

<?php
include '../../include/controller.php';

if (isset($_SESSION['user_name']) && $_SESSION['role'] == "admin") {
    header("location:/iicshd/user/admin/home.php");
}
if (isset($_SESSION['user_name']) && $_SESSION['role'] == "faculty") {
    header("location:/iicshd/user/faculty/home.php");
}
if (isset($_SESSION['user_name'])) {

    if ((time() - $_SESSION['last_time']) > 2000) {
        header("Location:../../logout.php");
    } else {
        $_SESSION['last_time'] = time();
    }
}

if (!isset($_SESSION['user_name'])) {
    header("location:/iicshd/login.php");
}

//if (isset($_POST['submitDoc'])) {
//    $dTitle = clean($_POST["dTitle"]);
//    $dDesc = clean($_POST["dDesc"]);
//    $dStatus = "Submitted";
//
//    $submitSql = $conn->prepare("INSERT INTO documents VALUES ('', NOW(), ?, ?, ?, ?, '0', '0')");
//    $submitSql->bind_param("isss", $_SESSION['userno'], $dTitle, $dDesc, $dStatus);
//    $submitSql2 = $conn->prepare("INSERT INTO doclogs VALUES ('', NOW(), ?, ?, ?, ?, '0', '0')");
//    $submitSql2->bind_param("isss", $_SESSION['userno'], $dTitle, $dDesc, $dStatus);
//
//
//    if ($submitSql == TRUE) {
//
//        $submitSql->execute();
//        $submitSql->close();
//
//        if ($submitSql2 == TRUE) {
//            $submitSql2->execute();
//            $submitSql2->close();
//        }
//
//
//        header("Location: documents.php");
//        exit;
//    } else {
//        $postFailed = '<div class="alert alert-danger">
//                        Submit Failed!
//                        </div>';
//    }
//}

if (isset($_POST['receiveRel'])) {
    $recDoc = $_POST['recDoc'];
    $docTitle = $_POST['docTitle'];
    $docstatus = "Received by Student";

    $editquery = $conn->prepare("UPDATE documents SET docstatus=?, docdatechange=NOW() WHERE docno=?");
    $editquery->bind_param("si", $docstatus, $recDoc);
    $editquery->execute();
    $editquery->close();

    $editquery2 = $conn->prepare("UPDATE doclogs SET docstatus=?, docdatechange=NOW() WHERE docno=?");
    $editquery2->bind_param("si", $docstatus, $recDoc);
    $editquery2->execute();
    $editquery2->close();

    if ($editquery == TRUE) {

        if ($editquery2 == TRUE) {

            $notiftitle = "Document Status Updated";
            $notifdesc = "Document Title: " . $docTitle . " / Status: " . $docstatus . "";
            $notifaudience = "admin";

            $notif = $conn->prepare("INSERT INTO notif VALUES ('',?,?,?,?,NOW(),0)");
            $notif->bind_param("isss", $_SESSION['userno'], $notiftitle, $notifdesc, $notifaudience);
            $notif->execute();
            $notif->close();

            header("location: documents.php");
            exit;
        }
    } else {
        echo "Update failed.";
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

        <title>IICS Help Desk</title>

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

                    <li class="nav-item">
                        <a class="nav-link" style="color:white;" href="home.php">
                            <span data-feather="home"></span>
                            Home <span class="sr-only">(current)</span>
                        </a>
                    </li>

                    <li class="nav-item active">
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

                    <li class="nav-item">
                        <a class="nav-link" style="color:white;" href="consultations.php">
                            <span data-feather="info"></span>
                            Consultation
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

                </ul>

                <ul class="navbar-nav px-1">
                    <li class="dropdown">
                        <a href="#" class="btn btn-primary btn-sm dropdown-toggle notif-toggle" data-toggle="dropdown"><span class="badge badge-danger count" style="border-radius:10px;"></span> <span class="fas fa-bell" style="font-size:18px;"></span> Notifications</a>
                        <ul class="shownotif dropdown-menu" style="white-space:normal;"></ul>
                    </li>
                </ul>
                <!--
                 <ul class="navbar-nav px-1">
                                    <li class="nav-item text-nowrap">
                                    <li class="nav-item dropdown">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="fas fa-envelope"></span>
                                            Notifications
                                        </button>
                                        <div class="dropdown-menu" style="white-space: normal;">
                <?php
//                            $notifquery = "SELECT notif.notifno, notif.notiftitle, notif.notifdesc, notif.notifaudience, notif.notifdate, users.userno 
//                                                    FROM notif 
//                                                INNER JOIN users 
//                                                ON users.userno = notif.notifaudience 
//                                                WHERE notif.notifaudience = '".$_SESSION['userno']."' 
//                                                UNION ALL 
//                                            SELECT notif.notifno, notif.notiftitle, notif.notifdesc, notif.notifaudience, notif.notifdate, notif.notifno as userno 
//                                                    FROM notif 
//                                                WHERE notif.notifaudience = 'all' 
//                                                UNION ALL
//                                            SELECT notif.notifno, notif.notiftitle, notif.notifdesc, notif.notifaudience, notif.notifdate, notif.notifno as userno 
//                                                    FROM notif 
//                                                    WHERE notif.notifaudience = 'student' 
//                                            ORDER BY notifno DESC LIMIT 4";
//                            $notifresult = $conn->query($notifquery);
//
//                            if ($notifresult->num_rows > 0) {
//                                while ($row = $notifresult->fetch_assoc()) {
//                                    $notiftitle = $row['notiftitle'];
//                                    $notifdesc = $row['notifdesc'];
//                                    $notifdate = $row['notifdate'];
//
//                                    echo '
//                                            <a class="dropdown-item" ';
//
//                                    if ($notiftitle == "New Announcement Posted") {
//                                        echo 'href="home.php"';
//                                    }
//                                    if ($notiftitle == "New File Upload") {
//                                        echo 'href="documents.php"';
//                                    }
//                                    if ($notiftitle == "Schedule Updated") {
//                                        echo 'href="fschedule.php"';
//                                    }
//                                    echo 'style="width: 300px; white-space: normal;">
//                                                <span style="font-size: 13px;"><strong> ' . $notiftitle . ' </strong></span><br>
//                                                ' . $notifdesc . ' <br>
//                                                <span style="font-size: 10px;"> ' . $notifdate . ' </span><br>
//                                            </a>
//                                            <div class="dropdown-divider"></div>';
//                                }
//                            } else {
//                                echo '
//                                            <a class="dropdown-item" href="#" style="width: 300px; white-space: normal;">
//                                                No new notifications.
//                                            </a>';
//                            }
                ?>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="notifications.php" style="color: blue; width: 300px; white-space: normal;">
                                                <center>View All Notifications</center>
                                            </a>
                                        </div>
                                    </li>
                                    </li>
                                </ul>-->

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
                    <h1 class="h2">Documents</h1>
                </div>

                <div class="accordion" id="accordionExample">

                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn bg-dark text-white" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <span class="fas fa-plus-circle"></span> Received Documents
                                </button>
                            </h5>
                        </div>

                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                            <div class="card-body">


                                <div class="table-responsive">

                                    <table id="received" class="table table-striped table-responsive-lg">

                                        <thead>
                                            <tr>
                                                <th>Document #</th>
                                                <th>Date Submitted</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            <?php
                                            $receivedQuery = mysqli_query($conn, "SELECT LPAD(documents.docno,4,0), documents.docdatesubmit, users.fname, users.mname, users.lname, documents.doctitle,"
                                                    . "documents.docdesc, documents.docstatus FROM documents INNER JOIN users WHERE documents.userno = users.userno "
                                                    . "AND documents.docstatus = 'Received by Student' AND documents.userno = " . $_SESSION['userno'] . "");

                                            if ($receivedQuery->num_rows > 0) {
                                                while ($row = $receivedQuery->fetch_assoc()) {
                                                    $docid = $row['LPAD(documents.docno,4,0)'];
                                                    $docdatesubmit = $row['docdatesubmit'];
                                                    $userid = ($row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']);
                                                    $doctitle = $row['doctitle'];
                                                    $docdesc = $row['docdesc'];
                                                    $docstatus = $row['docstatus'];

                                                    echo '<tr>'
                                                    . '<td>' . $docid . '</td>'
                                                    . '<td>' . date("m/d/Y h:iA", strtotime($docdatesubmit)) . '</td>'
                                                    . '<td>' . $doctitle . '</td>'
                                                    . '<td>' . $docdesc . '</td>'
                                                    . '<td>' . $docstatus . '</td>';
                                                }
                                            }
                                            ?>

                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <th>Document #</th>
                                                <th>Date Submitted</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Status</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>



                    </div>


                    <div class="card">
                        <div class="card-header" id="headingThree">
                            <h5 class="mb-0">
                                <button class="btn bg-dark text-white" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    <span class="fas fa-plus-circle"></span> Downloadable Templates
                                </button>
                            </h5>
                        </div>

                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                            <div class="card-body">

                                <div class="table-responsive">

                                    <table id="downloadable" class="table table-striped table-responsive-lg">

                                        <thead>
                                            <tr>
                                                <th>Filename</th>
                                                <th>Date Uploaded</th>
                                                <th>Download</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            <?php
                                            $getfiles = mysqli_query($conn, "SELECT * FROM files WHERE HIDDEN = '0'");

                                            if ($getfiles->num_rows > 0) {
                                                while ($row = $getfiles->fetch_assoc()) {
                                                    $fileno = $row['fileno'];
                                                    $filetitle = $row['filetitle'];
                                                    $filename = $row['filename'];
                                                    $filedate = $row['filedate'];

                                                    echo '<tr><td>' . $filetitle . '</td>'
                                                    . '<td>' . date("m/d/Y h:iA", strtotime($filedate)) . '</td>'
                                                    . '<td>'
                                                    . '<a href = "../../uploads/' . $filename . '" target=”_blank”>'
                                                    . '<button type = "button" class="btn btn-success btn-sm" name ="downloadFile" title="Download File"><span class="fas fa-arrow-circle-down"></span> Download</button>'
                                                    . '</a></td></tr>';
                                                }
                                            }
                                            ?>

                                        </tbody>


                                    </table>



                                </div>

                            </div>
                        </div>



                    </div>

                </div>

                <br>
                <br>
                <h5>Tracking</h5><hr>

                <div class="table-responsive">

                    <table id="submitted" class="table table-striped table-responsive-lg">

                        <thead>
                            <tr>
                                <th>Document #</th>
                                <th>Date Submitted</th>
                                <th>Submitted By</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            $newsubquery = mysqli_query($conn, "SELECT LPAD(documents.docno,4,0), documents.docdatesubmit, users.fname, users.mname, users.lname, documents.doctitle,"
                                    . "documents.docdesc, documents.docstatus FROM documents INNER JOIN users WHERE documents.userno = users.userno "
                                    . "AND documents.userno = " . $_SESSION['userno'] . " AND documents.docstatus != 'Received by Student'");


                            if ($newsubquery->num_rows > 0) {
                                while ($row = $newsubquery->fetch_assoc()) {
                                    $docid = $row['LPAD(documents.docno,4,0)'];
                                    $docdatesubmit = $row['docdatesubmit'];
                                    $userid = ($row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']);
                                    $doctitle = $row['doctitle'];
                                    $docdesc = $row['docdesc'];
                                    $docstatus = $row['docstatus'];

                                    echo '<tr>'
                                    . '<td>' . $docid . '</td>'
                                    . '<td>' . date("m/d/Y h:iA", strtotime($docdatesubmit)) . '</td>'
                                    . '<td>' . $userid . '</td>'
                                    . '<td>' . $doctitle . '</td>'
                                    . '<td>' . $docdesc . '</td>'
                                    . '<td>' . $docstatus . '</td>';
                                    if ($docstatus == 'For Release') {
                                        echo '<td>'
                                        . '<form method="post">'
                                        . '<input type = "hidden" name="recDoc" value="' . $docid . '">'
                                        . '<input type = "hidden" name="docTitle" value="' . $doctitle . '">'
                                        . '<button type = "submit" class="btn btn-success btn-sm" name ="receiveRel"><span class="fas fa-check"></span></button>'
                                        . '</td></tr>';
                                    } else {
                                        echo '<td> - </td></tr>';
                                    }
                                }
                            }
                            ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Document #</th>
                                <th>Date Submitted</th>
                                <th>Submitted By</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>

                    </table>
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

                $('#submitted').DataTable({
                    dom: 'lBfrtip',
                    buttons: [
                        {extend: 'copy', className: 'btn btn-secondary', text: '<i class="fas fa-copy"></i>', titleAttr: 'Copy', title: 'Report Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                        {extend: 'csv', className: 'btn bg-primary', text: '<i class="fas fa-file-alt"></i>', titleAttr: 'CSV', title: 'Report Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                        {extend: 'excel', className: 'btn btn-success', text: '<i class="fas fa-file-excel"></i>', titleAttr: 'Excel', title: 'Report Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                        {extend: 'pdf', className: 'btn btn-danger', orientation: 'landscape', pageSize: 'LEGAL', text: '<i class="fas fa-file-pdf"></i>', titleAttr: 'PDF', title: 'Report Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                        {extend: 'print', className: 'btn btn-dark', text: '<i class="fas fa-print"></i>', titleAttr: 'Print', title: 'Report printed by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'}
                    ],

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

                $('#received').DataTable({
                    dom: 'lBfrtip',
                    buttons: [
                        {extend: 'copy', className: 'btn btn-secondary', text: '<i class="fas fa-copy"></i>', titleAttr: 'Copy', title: 'Report Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                        {extend: 'csv', className: 'btn bg-primary', text: '<i class="fas fa-file-alt"></i>', titleAttr: 'CSV', title: 'Report Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                        {extend: 'excel', className: 'btn btn-success', text: '<i class="fas fa-file-excel"></i>', titleAttr: 'Excel', title: 'Report Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                        {extend: 'pdf', className: 'btn btn-danger', orientation: 'landscape', pageSize: 'LEGAL', text: '<i class="fas fa-file-pdf"></i>', titleAttr: 'PDF', title: 'Report Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                        {extend: 'print', className: 'btn btn-dark', text: '<i class="fas fa-print"></i>', titleAttr: 'Print', title: 'Report printed by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'}
                    ],

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

                $('#downloadable').DataTable({

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

        <script>
            $(document).ready(function () {

                function load_unseen_notification(view = '')
                {
                    $.ajax({
                        url: "../../include/fetch2.php",
                        method: "POST",
                        data: {view: view},
                        dataType: "json",
                        success: function (data)
                        {
                            $('.shownotif').html(data.notification);
                            if (data.unseen_notification > 0)
                            {
                                $('.count').html(data.unseen_notification);
                            }
                        }
                    });
                }

                load_unseen_notification();

                $(document).on('click', '.notif-toggle', function () {
                    $('.count').html('');
                    load_unseen_notification('yes');
                });

                setInterval(function () {
                    load_unseen_notification();
                    ;
                }, 1000);

            });
        </script>

    </body>
</html>

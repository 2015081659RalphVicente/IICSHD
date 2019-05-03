
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
    header("location:/iicshd/login.php");
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

        <!-- Font Awesome JS -->
        <script defer src="../../fa-5.5.0/js/solid.js"></script>
        <script defer src="../../fa-5.5.0/js/fontawesome.js"></script>

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
                    <li class="nav-item active">
                        <a class="nav-link" style="color:white;" href="reports.php">
                            <span data-feather="layers"></span>
                            Reports
                        </a>
                    </li>

                </ul>

                <ul class="navbar-nav px-1">
                    <li class="dropdown">
                        <a href="#" class="btn btn-primary btn-sm dropdown-toggle notif-toggle" data-toggle="dropdown"><span class="badge badge-danger count" style="border-radius:10px;"></span> <span class="fas fa-bell" style="font-size:18px;"></span> Notifications</a>
                        <ul class="shownotif dropdown-menu" style="white-space:normal;"></ul>
                    </li>
                </ul>

                <!--                <ul class="navbar-nav px-1">
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
//                                                    WHERE notif.notifaudience = 'admin' 
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
//                                    if ($notiftitle == "New Queue Ticket") {
//                                        echo 'href="queue.php"';
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
                    <h1 class="h2">Reports</h1>
                </div>

                <div class="accordion" id="accordionExample">

                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn bg-dark text-white" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    <span class="fas fa-plus-circle"></span> Document Logs
                                </button>
                            </h5>
                        </div>

                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                            <br>
                            <div class="table-responsive">

                                <table id="docLog" class="table table-striped table-responsive-lg">

                                    <thead>
                                        <tr>
                                            <th>Document #</th>
                                            <th>Date Submitted</th>
                                            <th>Submitted By</th>
                                            <th>Title</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php
                                        $newsubquery = mysqli_query($conn, "SELECT LPAD(documents.docno,4,0), documents.docdatesubmit, users.fname, users.mname, users.lname, documents.doctitle,"
                                                . "documents.docdesc, documents.docstatus FROM doclogs documents INNER JOIN users WHERE documents.userno = users.userno AND documents.hidden = '0' ORDER BY documents.docdatesubmit DESC");

                                        if ($newsubquery->num_rows > 0) {
                                            while ($row = $newsubquery->fetch_assoc()) {
                                                $docid = $row['LPAD(documents.docno,4,0)'];
                                                $docdatesubmit = $row['docdatesubmit'];
                                                $userid = ($row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']);
                                                $doctitle = $row['doctitle'];
                                                $docdesc = $row['docdesc'];
                                                $docstatus = $row['docstatus'];

                                                echo "<tr>"
                                                . "<td>" . $docid . "</td>"
                                                . "<td>" . date("m/d/Y h:iA", strtotime($docdatesubmit)) . "</td>"
                                                . "<td>" . $userid . "</td>"
                                                . "<td>" . $doctitle . "</td>"
                                                . "<td>" . $docstatus . "</td>";
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
                                            <th>Status</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <br>
                            </div>
                        </div>

                    </div>

                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn bg-dark text-white" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <span class="fas fa-plus-circle"></span> Queue Logs
                                </button>
                            </h5>
                        </div>


                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                            <div class="card-body">
                                <table id="queueLog" class="table table-striped table-responsive">

                                    <thead>
                                        <tr>
                                            <th>Queue #</th>
                                            <th>Date Queued</th>
                                            <th>Student Name</th>
                                            <th>Transaction Type</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php
                                        $qLogsquery = mysqli_query($conn, "SELECT LPAD(queue.qno,4,0), queue.qtitle, queue.qtype, queue.qdate, queue.qstatus, queue.qdesc, queue.qremarks, users.userid, users.fname, users.mname, users.lname FROM queuelogs queue INNER JOIN users ON queue.userno = users.userno ORDER BY queue.qno DESC");

                                        if ($qLogsquery->num_rows > 0) {
                                            while ($row = $qLogsquery->fetch_assoc()) {
                                                $qno = $row['LPAD(queue.qno,4,0)'];
                                                $qdate = $row['qdate'];
                                                $userid = ($row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']);
                                                $qtype = $row['qtype'];
                                                $qdesc = $row['qdesc'];
                                                $qstatus = $row['qstatus'];
                                                $qtitle = $row['qtitle'];
                                                $qremarks = $row['qremarks'];

                                                echo "<tr>"
                                                . "<td>" . $qno . "</td>"
                                                . "<td>" . date("m/d/Y h:iA", strtotime($qdate)) . "</td>"
                                                . "<td>" . $userid . "</td>"
                                                . "<td>" . $qtype . "</td>";
                                                if ($qtitle == "") {
                                                    echo "<td> - </td>"
                                                    . "<td>" . $qdesc . "</td>"
                                                    . "<td>" . $qstatus . "</td>"
                                                    . "<td>" . $qremarks . "</td>";
                                                } else {
                                                    echo "<td>" . $qtitle . "</td>"
                                                    . "<td>" . $qdesc . "</td>"
                                                    . "<td>" . $qstatus . "</td>"
                                                    . "<td>" . $qremarks . "</td>";
                                                }
                                            }
                                        }
                                        ?>

                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th>Queue #</th>
                                            <th>Date Queued</th>
                                            <th>Student Name</th>
                                            <th>Transaction Type</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
                <br><br><br>
            </main>
            <br>
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

        <!-- Icons -->
        <script src="../../js/feather.min.js"></script>
        <script>
            feather.replace()
        </script>


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

        <script>
            $(document).ready(function () {
<?php
$thisDate = date("m/d/Y");
?>

                $('#docLog').DataTable({

                    dom: 'lBfrtip',
                    buttons: [
                        {extend: 'copy', className: 'btn btn-secondary', text: '<i class="fas fa-copy"></i>', titleAttr: 'Copy', title: 'IICSHD - Document Logs Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                        {extend: 'csv', className: 'btn bg-primary', text: '<i class="fas fa-file-alt"></i>', titleAttr: 'CSV', title: 'IICSHD - Document Logs Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                        {extend: 'excel', className: 'btn btn-success', text: '<i class="fas fa-file-excel"></i>', titleAttr: 'Excel', title: 'IICSHD - Document Logs Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                        {extend: 'pdfHtml5',
                            customize: function (doc) {
                                doc.content[1].margin = [25, 0, 0, 0]
                                doc.content.splice(1, 0, {
                                    margin: [0, 0, 0, 12],
                                    alignment: 'center',
                                    image: 'data:image/png;base64,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'
                                });

                            },
                            alignment: 'center', className: 'btn btn-danger', orientation: 'portrait', pageSize: 'LEGAL', text: '<i class="fas fa-file-pdf"></i>', titleAttr: 'PDF', title: 'IICSHD - Document Logs Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                        {extend: 'print', className: 'btn btn-dark', text: '<i class="fas fa-print"></i>', titleAttr: 'Print', title: 'IICSHD - Document Logs Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'}
                    ],

                    initComplete: function () {
                        this.api().columns([1, 2, 3, 4, 5, 6, 7, 8]).every(function () {
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

                $('#queueLog').DataTable({

                    dom: 'lBfrtip',
                    buttons: [
                        {extend: 'copy', className: 'btn btn-secondary', text: '<i class="fas fa-copy"></i>', titleAttr: 'Copy', title: 'IICSHD - Queue Logs Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                        {extend: 'csv', className: 'btn bg-primary', text: '<i class="fas fa-file-alt"></i>', titleAttr: 'CSV', title: 'IICSHD - Queue Logs Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                        {extend: 'excel', className: 'btn btn-success', text: '<i class="fas fa-file-excel"></i>', titleAttr: 'Excel', title: 'IICSHD - Queue Logs Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                        {extend: 'pdfHtml5',
                            customize: function (doc) {
                                doc.content[1].margin = [100, 0, 100, 0]
                                doc.content.splice(1, 0, {
                                    margin: [0, 0, 0, 12],
                                    alignment: 'center',
                                    image: 'data:image/png;base64,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'
                                });
                            },
                            className: 'btn btn-danger', orientation: 'landscape', pageSize: 'LEGAL', text: '<i class="fas fa-file-pdf"></i>', titleAttr: 'PDF', title: 'IICSHD - Queue Logs Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                        {extend: 'print', className: 'btn btn-dark', text: '<i class="fas fa-print"></i>', titleAttr: 'Print', title: 'IICSHD - Queue Logs Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'}
                    ],

                    initComplete: function () {
                        this.api().columns([1, 2, 3, 4, 5, 6, 7, 8]).every(function () {
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
            $(document).ready(function () {

                function load_unseen_notification(view = '')
                {
                    $.ajax({
                        url: "../../include/fetch1.php",
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

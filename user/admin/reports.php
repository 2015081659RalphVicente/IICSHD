
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

        <!-- Font Awesome JS -->
        <script defer src="../../fa-5.5.0/js/solid.js"></script>
        <script defer src="../../fa-5.5.0/js/fontawesome.js"></script>

        <!-- DataTable-->
        <link rel="stylesheet" href="../../DataTables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="../../DataTables/Responsive-2.2.1/css/responsive.bootstrap4.min.css">
        <link rel="stylesheet" href="../../DataTables/Buttons-1.5.1/css/buttons.dataTables.min.css">
    </head>

    <body>

        <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#"><img src="../../img/logosolo.png"> IICS Help Desk</a>
            <ul class="navbar-nav px-3">
                <li class="nav-item text-nowrap">
                    <a style="font-size: 13px;" class="btn btn-danger" href="../../logout.php" onclick="if (!confirm('Are you sure you want to log out?')) {
                                return false;
                            }">
                        <span data-feather="log-out"></span>  Log Out
                    </a>
                </li>
            </ul>
        </nav>


        <div class="container-fluid">

            <div class="row">

                <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                    <div class="sidebar sidebar-sticky">
                        <ul class="nav flex-column">
                            <br>
                            <center><span class="fas fa-6x fa-user-circle"></span><br><br>
                                <h6 class="nav-item">Welcome, <?php echo $_SESSION['user_name']; ?></h6>
                            </center>
                            <li class="nav-item">
                                <a class="nav-link" href="home.php">
                                    <span data-feather="home"></span>
                                    Home <span class="sr-only">(current)</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="documents.php">
                                    <span data-feather="file-text"></span>
                                    Documents
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="queue.php">
                                    <span data-feather="users"></span>
                                    Queue
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
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
                                <a class="nav-link" href="stats.php">
                                    <span data-feather="bar-chart-2"></span>
                                    Statistics
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="reports.php">
                                    <span data-feather="layers"></span>
                                    Reports
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="account.php">
                                    <span data-feather="user"></span>
                                    Account
                                </a>
                            </li> 
                        </ul>
                    </div>
                </nav>

                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
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

                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
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
                                                    . "documents.docdesc, documents.docstatus FROM documents INNER JOIN users WHERE documents.userno = users.userno AND documents.hidden = '0'");

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
                                                    . "<td>" . $docdatesubmit . "</td>"
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
                                                <th>Description</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            <?php
                                            $qLogsquery = mysqli_query($conn, "SELECT LPAD(queue.qno,4,0), queue.qtype, queue.qdate, queue.qstatus, queue.qdesc, users.userid, users.fname, users.mname, users.lname FROM queuelogs queue INNER JOIN users ON queue.userno = users.userno");

                                            if ($qLogsquery->num_rows > 0) {
                                                while ($row = $qLogsquery->fetch_assoc()) {
                                                    $qno = $row['LPAD(queue.qno,4,0)'];
                                                    $qdate = $row['qdate'];
                                                    $userid = ($row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']);
                                                    $qtype = $row['qtype'];
                                                    $qdesc = $row['qdesc'];
                                                    $qstatus = $row['qstatus'];

                                                    echo "<tr>"
                                                    . "<td>" . $qno . "</td>"
                                                    . "<td>" . $qdate . "</td>"
                                                    . "<td>" . $userid . "</td>"
                                                    . "<td>" . $qtype . "</td>"
                                                    . "<td>" . $qdesc . "</td>"
                                                    . "<td>" . $qstatus . "</td>";
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
                                                <th>Description</th>
                                                <th>Status</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>


                    </div>
                </main>
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
                                    {extend: 'copy', className: 'btn btn-secondary', text: '<i class="fas fa-copy"></i>', titleAttr: 'Copy', title: 'Report Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                                    {extend: 'csv', className: 'btn bg-primary', text: '<i class="fas fa-file-alt"></i>', titleAttr: 'CSV', title: 'Report Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                                    {extend: 'excel', className: 'btn btn-success', text: '<i class="fas fa-file-excel"></i>', titleAttr: 'Excel', title: 'Report Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                                    {extend: 'pdf', className: 'btn btn-danger', orientation: 'landscape', pageSize: 'LEGAL', text: '<i class="fas fa-file-pdf"></i>', titleAttr: 'PDF', title: 'Report Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                                    {extend: 'print', className: 'btn btn-dark', text: '<i class="fas fa-print"></i>', titleAttr: 'Print', title: 'Report printed by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'}
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
                                    {extend: 'copy', className: 'btn btn-secondary', text: '<i class="fas fa-copy"></i>', titleAttr: 'Copy', title: 'Report Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                                    {extend: 'csv', className: 'btn bg-primary', text: '<i class="fas fa-file-alt"></i>', titleAttr: 'CSV', title: 'Report Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                                    {extend: 'excel', className: 'btn btn-success', text: '<i class="fas fa-file-excel"></i>', titleAttr: 'Excel', title: 'Report Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                                    {extend: 'pdf', className: 'btn btn-danger', orientation: 'landscape', pageSize: 'LEGAL', text: '<i class="fas fa-file-pdf"></i>', titleAttr: 'PDF', title: 'Report Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                                    {extend: 'print', className: 'btn btn-dark', text: '<i class="fas fa-print"></i>', titleAttr: 'Print', title: 'Report printed by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'}
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

    </body>
</html>

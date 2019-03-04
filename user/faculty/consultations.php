<?php
include '../../include/controller.php';

if (isset($_SESSION['user_name']) && $_SESSION['role'] == "admin") {
    header("location:/iicshd/user/admin/home.php");
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

if (isset($_POST['updatedoc2'])) {
    $edit_doc_no = $_POST['edit_doc_no2'];
    $docstatus = $_POST['edit_status2'];
    $conremarks = $_POST['conremarks'];
    $constart = $_POST['constart'];
    $conend = $_POST['conend'];

    $constart = date("Y-m-d H:i:s");
    $conend = date("Y-m-d H:i:s");


    if ($docstatus == "Declined") {
        $constart = "0";
        $conend = "0";

        $editquery = $conn->prepare("UPDATE consultations SET constatus=?, conremarks = ?, constart = ?, conend = ?, condatemodified=NOW() WHERE conno=?");
        $editquery->bind_param("ssssi", $docstatus, $conremarks, $constart, $conend, $edit_doc_no);
        $editquery->execute();
        $editquery->close();
    } elseif ($docstatus == "Accepted") {

        $editquery = $conn->prepare("UPDATE consultations SET constatus=?, conremarks = ?, constart = ?, conend = ?, condatemodified=NOW() WHERE conno=?");
        $editquery->bind_param("ssssi", $docstatus, $conremarks, $constart, $conend, $edit_doc_no);
        $editquery->execute();
        $editquery->close();
    }

    if ($editquery == TRUE) {

        $passval = 'Consultation Request No. ' . $edit_doc_no . ' changed status to ' . $docstatus . '.';

        $passaction = "Update Consultation Request Status";
        $logpass = $conn->prepare("INSERT INTO updatelogs VALUES ('',?,?,NOW(),?)");
        $logpass->bind_param("sss", $passaction, $_SESSION['user_name'], $passval);
        $logpass->execute();
        $logpass->close();

        header("location: consultations.php");
        exit;
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

                    <li class="nav-item active">
                        <a class="nav-link" style="color:white;" href="consultations.php">
                            <span data-feather="file-text"></span>
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
                                                    WHERE notif.notifaudience = 'faculty' 
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
                                    if ($notiftitle == "Consultation Request") {
                                        echo 'href="consultations.php"';
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
                    <h1 class="h2">Consultation Requests</h1>
                </div>

                <div class="table-responsive">

                    <table id="consultation" class="table table-striped table-responsive-lg">

                        <thead>
                            <tr>
                                <th>Edit</th>
                                <th>Consultation #</th>
                                <th>Date Requested</th>
                                <th>Requested By</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Start Date/Time</th>
                                <th>End Date/Time</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            $newsubquery = mysqli_query($conn, "SELECT LPAD(c.conno,4,0), c.condatecreated, u.fname, u.mname, u.lname, c.consub,"
                                    . "c.condesc, c.constatus, c.conprof, c.conremarks, c.constart, c.conend FROM consultations c INNER JOIN users u WHERE c.userno = u.userno "
                                    . "AND c.conprof = " . $_SESSION['userno'] . "");

                            if ($newsubquery->num_rows > 0) {
                                while ($row = $newsubquery->fetch_assoc()) {
                                    $docid = $row['LPAD(c.conno,4,0)'];
                                    $docdatesubmit = $row['condatecreated'];
                                    $userid = ($row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']);
                                    $doctitle = $row['consub'];
                                    $docdesc = $row['condesc'];
                                    $docstatus = $row['constatus'];
                                    $conremarks = $row['conremarks'];
                                    $constart = $row['constart'];
                                    $conend = $row['conend'];

                                    echo '<tr>';
                                    if ($docstatus == 'Accepted') {
                                        echo '<td> - </td>';
                                    } elseif ($docstatus == 'Declined') {
                                        echo '<td> - </td>';
                                    } else {
                                        echo
                                        "<td>" . "<a href='#edit2" . $docid . "'data-toggle='modal'><button type='button' class='btn btn-dark btn-sm' title='Edit'><span class='fas fa-edit' aria-hidden='true'></span></button></a>" . "</td>";
                                    } echo '<td>' . $docid . '</td>'
                                    . '<td>' . $docdatesubmit . '</td>'
                                    . '<td>' . $userid . '</td>'
                                    . '<td>' . $doctitle . '</td>'
                                    . '<td>' . $docdesc . '</td>'
                                    . '<td>' . $docstatus . '</td>';
                                    if ($conremarks != "") {
                                        echo '<td>' . $conremarks . '</td>';
                                    } else {
                                        echo '<td>Waiting for Approval</td>';
                                    }
                                    if ($constart != 0) {
                                        echo '<td>' . $constart . '</td>';
                                    } else {
                                        echo '<td>N/A</td>';
                                    }
                                    if ($conend != 0) {
                                        echo '<td>' . $conend . '</td>';
                                    } else {
                                        echo '<td>N/A</td>';
                                    }
                                    ?>
                                    <?php
                                    echo '<div id="edit2' . $docid . '" class="modal fade" role="dialog">
                                                            <form method="post">
                                                                <div class="modal-dialog modal-lg">
                                                                    <!-- Modal content-->
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">

                                                                            <h4 class="modal-title">Edit Consultation Request #' . $docid . '</h4>
                                                                        </div>

                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-sm-12">
                                                                                    <input type="hidden" name="edit_doc_no2" value="' . $docid . '">
                                                                                    <p><strong>Subject of Concern: </strong>' . $doctitle . '</p>
                                                                                    <p><strong>Description: </strong>' . $docdesc . '</p>
                                                                                    <p><strong>Date Requested: </strong>' . $docdatesubmit . '</p>
                                                                                    <p><strong>Requested By: </strong>' . $userid . '</p>  
                                                                                    <p><strong>Update Status: </strong><select name="edit_status2" id="edit_status2" class="form-control">
                                                                                    <option value="Accepted"';
                                    if ($docstatus == 'Accepted') {
                                        echo "selected";
                                    } echo' >Accept
                                                                                    </option>
                                                                                    <option value="Declined"';
                                    if ($docstatus == 'Declined') {
                                        echo "selected";
                                    } echo'>Decline
                                                                                    </option>
                                                                                    </select></p>
                                                                                    
                                                                                <div class="form-group" id="conremarks">
                                                                                    <strong>Remarks: *</strong>
                                                                                    <textarea rows="2" class="form-control" name="conremarks" id="conremarks"></textarea>
                                                                                </div>
                                                                                
                                                                                <div class="form-group" id="constart">
                                                                                    <strong>Start Date/Time: </strong>
                                                                                    <input type="datetime-local" name="constart" id="constart" class="form-control">
                                                                                </div>
                                                                                
                                                                                <div class="form-group" id="conend">
                                                                                    <strong>End Date/Time: </strong>
                                                                                    <input type="datetime-local" name="conend" id="conend" class="form-control">
                                                                                </div>
                                                                                
                                                                                </div>
                                                                            </div>
                                                                            <br>
                                                                            <div class="modal-footer">
                                                                                <button style="float: right;" type="button" class="btn btn-secondary btn-m" data-dismiss="modal"><span class="fas fa-times"></span> Cancel</button>
                                                                                <button style="float: right;" type="submit" name="updatedoc2" class="btn btn-success btn-m"><span class="fas fa-clipboard-check" ></span> Save Changes</button>
                                                                                 
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>';
                                }
                            }
                            ?>

                        </tbody>

                        <tfoot>
                            <tr>
                                <th>Edit</th>
                                <th>Consultation #</th>
                                <th>Date Requested</th>
                                <th>Requested By</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Start Date/Time</th>
                                <th>End Date/Time</th>
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

                $('#consultation').DataTable({
                    dom: 'lBfrtip',
                    buttons: [
                        {extend: 'copy', className: 'btn btn-secondary', text: '<i class="fas fa-copy"></i>', titleAttr: 'Copy', title: 'Report Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                        {extend: 'csv', className: 'btn bg-primary', text: '<i class="fas fa-file-alt"></i>', titleAttr: 'CSV', title: 'Report Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                        {extend: 'excel', className: 'btn btn-success', text: '<i class="fas fa-file-excel"></i>', titleAttr: 'Excel', title: 'Report Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                        {extend: 'pdf', className: 'btn btn-danger', orientation: 'landscape', pageSize: 'LEGAL', text: '<i class="fas fa-file-pdf"></i>', titleAttr: 'PDF', title: 'Report Generated by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'},
                        {extend: 'print', className: 'btn btn-dark', text: '<i class="fas fa-print"></i>', titleAttr: 'Print', title: 'Report printed by: <?php echo $_SESSION['user_name'] . " on " . $thisDate; ?>'}
                    ],
                    initComplete: function () {
                        this.api().columns([1, 2, 3, 4, 5, 6, 7, 8, 9]).every(function () {
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




                }
                );

            });

            $("#constart").show();
            $("#conend").show();
            $("#edit_status2").change(function () {
                var val = $("#edit_status2").val();
                if (val == "Declined") {
                    $("#constart").hide();
                    $("#conend").hide();
                } else {
                    $("#constart").show();
                    $("#conend").show();
                }
            });

        </script>

    </body>
</html>

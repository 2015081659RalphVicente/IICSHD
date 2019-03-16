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

if (isset($_GET['addsched'])) {
    $addsched = $_GET['addsched'];
} else {
    $addsched = '';
}

if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
} else {
    $delete = '';
}

if (isset($_GET['deactivate'])) {
    $deactivate = $_GET['deactivate'];
} else {
    $deactivate = '';
}
if (isset($_GET['activate'])) {
    $activate = $_GET['activate'];
} else {
    $activate = '';
}


if (isset($_POST['insertsched'])) {
    $consulday = $_POST['consulday'];
    $consulstart = $_POST['consulstart'];
    $consulend = $_POST['consulend'];

    $c = $consulday . " " . $consulstart . "-" . $consulend;

    $submitSql = $conn->prepare("INSERT INTO consulthours VALUES ('', ?, 1, ?)");
    $submitSql->bind_param("ss", $_SESSION['userno'], $c);
    $submitSql->execute();
    $submitSql->close();

    $_GET['addsched'] = 'success';

    header("Location: account4.php?addsched=success");
}

if (isset($_POST['activate'])) {
// sql to activate a record
    $activate_id = $_POST['activate_id'];

    $notif = $conn->prepare("UPDATE consulthours SET isactive = 1 where chourno=?");
    $notif->bind_param("i", $activate_id);
    $notif->execute();
    $notif->close();

    if ($notif == TRUE) {

        $_GET['activate'] = 'success';
        header("location: account4.php?activate=success");
    } else {
//        echo "Error deleting record: " . $conn->error;
    }
}

if (isset($_POST['deactivate'])) {
// sql to activate a record
    $activate_id = $_POST['activate_id'];

    $notif = $conn->prepare("UPDATE consulthours SET isactive = 0 where chourno=?");
    $notif->bind_param("i", $activate_id);
    $notif->execute();
    $notif->close();

    if ($notif == TRUE) {

        $_GET['deactivate'] = 'success';
        header("location: account4.php?deactivate=success");
    } else {
//        echo "Error deleting record: " . $conn->error;
    }
}

if (isset($_POST['delete'])) {
// sql to activate a record
    $activate_id = $_POST['activate_id'];

    $notif = $conn->prepare("DELETE FROM consulthours WHERE chourno=?");
    $notif->bind_param("i", $activate_id);
    $notif->execute();
    $notif->close();

    if ($notif == TRUE) {

        $_GET['delete'] = 'success';
        header("location: account4.php?delete=success");
    } else {
//        echo "Error deleting record: " . $conn->error;
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
        <link href="../../css/bootstrap-timepicker.min.css" rel="stylesheet"/>

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
//                                                WHERE notif.notifaudience = '" . $_SESSION['userno'] . "' 
//                                                UNION ALL 
//                                            SELECT notif.notifno, notif.notiftitle, notif.notifdesc, notif.notifaudience, notif.notifdate, notif.notifno as userno 
//                                                    FROM notif 
//                                                WHERE notif.notifaudience = 'all' 
//                                                UNION ALL
//                                            SELECT notif.notifno, notif.notiftitle, notif.notifdesc, notif.notifaudience, notif.notifdate, notif.notifno as userno 
//                                                    FROM notif 
//                                                    WHERE notif.notifaudience = 'faculty' 
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
//                                    if ($notiftitle == "Consultation Request") {
//                                        echo 'href="consultations.php"';
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
                            <a class="dropdown-item active" href="account.php">
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
                    <h1 class="h2">Account Details</h1>
                </div>
                <?php
                if ($addsched == TRUE) {
                    echo '<div class="alert alert-success"><span class="fas fa-check"></span> Consultation hour added successfully!</div>';
                } else {
                    echo '';
                }

                if ($delete == TRUE) {
                    echo '<div class="alert alert-success"><span class="fas fa-check"></span> Consultation hour deleted successfully!</div>';
                } else {
                    echo '';
                }

                if ($activate == TRUE) {
                    echo '<div class="alert alert-success"><span class="fas fa-check"></span> Consultation hour activated successfully!</div>';
                } else {
                    echo '';
                }

                if ($deactivate == TRUE) {
                    echo '<div class="alert alert-success"><span class="fas fa-check"></span> Consultation hour deactivated successfully!</div>';
                } else {
                    echo '';
                }
                ?>

                <div class="row">

                    <div class="col-sm-2">
                        <div class="card">
                            <ul class="list-group list-group-flush">
                                <a href="account.php"><li class="list-group-item">User Information <span style="float:right;" class="fas fa-caret-right"></span></li></a>
                                <a href="account2.php"><li class="list-group-item">Security <span style="float:right;" class="fas fa-caret-right"></span></li></a>
                                <a href="account4.php"><li class="list-group-item active">Consultation Hours <span style="float:right;" class="fas fa-caret-right"></span></li></a>
                                <a href="account3.php"><li class="list-group-item">Activity Logs <span style="float:right;" class="fas fa-caret-right"></span></li></a>
                            </ul>
                        </div>
                    </div>


                    <div class='col-sm-10'>

                        <div class="card">
                            <div class="card-header">
                                <h5>
                                    <span class="fas fa-lock"></span>
                                    Consultation Hours
                                </h5>
                            </div>

                            <div class="card-body">
                                <form action="" method="POST">
                                    <h5>Current Consultation Hours</h5><hr>

                                    <table id="consultation" class="table table-striped table-lg">

                                        <thead>
                                            <tr>
                                                <th>Consultation Hours</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            <?php
                                            $newsubquery = mysqli_query($conn, "SELECT * FROM consulthours WHERE userno ='" . $_SESSION['userno'] . "'");
                                            if ($newsubquery->num_rows > 0) {
                                                while ($row = $newsubquery->fetch_assoc()) {
                                                    $conhourno = $row['chourno'];
                                                    $consulday = $row['daytime'];
                                                    $consulstat = $row['isactive'];

                                                    echo "<tr>"
                                                    . "<td>" . $consulday . "</td>";
                                                    if ($consulstat == 1) {
                                                        echo "<td> Active </td>"
                                                        . "<td>" . "<a href='#deactivate" . $conhourno . "'data-toggle='modal'><button type='button' class='btn btn-dark btn-sm' title='Deactivate'><span class='fas fa-lock' aria-hidden='true'></span></button></a>" . "</td>";
                                                    } else {
                                                        echo "<td> Deactivated </td>"
                                                        . "<td>" . "<a href='#activate" . $conhourno . "'data-toggle='modal'><button type='button' class='btn btn-success btn-sm' title='Activate'><span class='fas fa-lock' aria-hidden='true'></span></button></a>" . "</td>";
                                                    }

                                                    echo "<td>" . "<a href='#delete" . $conhourno . "'data-toggle='modal'><button type='button' class='btn btn-danger btn-sm' title='Delete'><span class='fas fa-trash' aria-hidden='true'></span></button></a>" . "</td>";

                                                    echo '<div id="activate';
                                                    echo $conhourno;
                                                    echo'" class="modal fade" role="dialog">
                                                                <div class="modal-dialog modal-lg">
                                                                    <form method="post">
                                                                        <div class="modal-content">

                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title">Activate Consultation Hour</h4>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="activate_id" value="';
                                                    echo $conhourno;
                                                    echo '">

                                                                                <div class="alert alert-warning"><p>Are you sure you want to activate this Consultation Hour? This will <b>allow</b> students to set this as preferred time whenever they request for consultation. <strong>';

                                                    echo'</strong></p>
                                                                                </div>';
                                                    echo '<h5>Consultation Hour: ' . $consulday . '</h5><br>';
                                                    echo '<div class="modal-footer">
                                                                                    <button type="submit" name="activate" class="btn btn-success"><span class="fas fa-check"></span> Yes</button>
                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fas fa-times"></span> No</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>';

                                                    echo '<div id="delete';
                                                    echo $conhourno;
                                                    echo'" class="modal fade" role="dialog">
                                                                <div class="modal-dialog modal-lg">
                                                                    <form method="post">
                                                                        <div class="modal-content">

                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title">Delete Consultation Hour</h4>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="activate_id" value="';
                                                    echo $conhourno;
                                                    echo '">

                                                                                <div class="alert alert-warning"><p>Are you sure you want to delete this Consultation Hour? <strong>';

                                                    echo'</strong></p>
                                                                                </div>';
                                                    echo '<h5>Consultation Hour: ' . $consulday . '</h5><br>';
                                                    echo '<div class="modal-footer">
                                                                                    <button type="submit" name="delete" class="btn btn-danger"><span class="fas fa-trash"></span> Delete</button>
                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fas fa-times"></span> Cancel</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>';


                                                    echo '<div id="deactivate';
                                                    echo $conhourno;
                                                    echo'" class="modal fade" role="dialog">
                                                                <div class="modal-dialog modal-lg">
                                                                    <form method="post">
                                                                        <div class="modal-content">

                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title">Deactivate Consultation Hour</h4>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="activate_id" value="';
                                                    echo $conhourno;
                                                    echo '">

                                                                                <div class="alert alert-warning"><p>Are you sure you want to deactivate this Consultation Hour? This will <b>disable</b> the option to choose this consultation hour for consultation requests. <strong>';

                                                    echo'</strong></p>
                                                                                </div>';
                                                    echo '<h5>Consultation Hour: ' . $consulday . '</h5><br>';
                                                    echo '<div class="modal-footer">
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
                                                <th>Consultation Hours</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                                <th>Delete</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </form>

                                <hr>

                                <form action="" method="POST">
                                    <h5>Select Day: </h5><hr>
                                    <select name="consulday" class="form-control" required>
                                        <option value="" selected disabled>Choose one: </option>
                                        <option value="Monday">Monday</option>
                                        <option value="Tuesday">Tuesday</option>
                                        <option value="Wednesday">Wednesday</option>
                                        <option value="Thursday">Thursday</option>
                                        <option value="Friday">Friday</option>
                                        <option value="Saturday">Saturday</option>
                                    </select>
                                    <br>
                                    <h5>Select Time (AM/PM): </h5><hr>
                                    Start: 
                                    <div class="input-group bootstrap-timepicker timepicker">
                                        <input id="timepicker1" type="text" name="consulstart" class="form-control" required>
                                        <span class="input-group-addon"><i class="fas fa-clock"></i></span>
                                    </div><br>
                                    End: 
                                    <div class="input-group bootstrap-timepicker timepicker">
                                        <input id="timepicker2" type="text" name="consulend" class="form-control" required>
                                        <span class="input-group-addon"><i class="fas fa-clock"></i></span>
                                    </div>
                                    <br>
                                    <div class="btn-div">
                                        <button type="submit" name = "insertsched" class="btn btn-success float-right">Add Consultation Hour</button><br>
                                    </div>
                                    <br>
                                </form>
                            </div>

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
<script type="text/javascript" src="../../js/bootstrap-timepicker.min.js"></script>

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

<script type="text/javascript">
    $('#timepicker1').timepicker({
        explicitMode: true,
        defaultTime: false,
        showInputs: true
    });

    $('#timepicker2').timepicker({
        explicitMode: true,
        defaultTime: false,
        showInputs: true
    });

    $('#timepicker3').timepicker({
        explicitMode: true,
        defaultTime: false,
        showInputs: true
    });

    $('#timepicker4').timepicker({
        explicitMode: true,
        defaultTime: false,
        showInputs: true
    });
</script>

<!-- Graphs -->
<script src="../../js/Chart.min.js"></script>
<script>
    var ctx = document.getElementById("myChart");
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
            datasets: [{
                    data: [15339, 21345, 18483, 24003, 23489, 24092, 12034],
                    lineTension: 0,
                    backgroundColor: 'transparent',
                    borderColor: '#007bff',
                    borderWidth: 4,
                    pointBackgroundColor: '#007bff'
                }]
        },
        options: {
            scales: {
                yAxes: [{
                        ticks: {
                            beginAtZero: false
                        }
                    }]
            },
            legend: {
                display: false,
            }
        }
    });
</script>

<script>
    $(document).ready(function () {

        function load_unseen_notification(view = '')
        {
            $.ajax({
                url: "../../include/fetch3.php",
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

<script>
    $(document).ready(function () {
<?php
$thisDate = date("m/d/Y");
?>
        $('#consultation').DataTable({
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

</script>
</body>
</html>

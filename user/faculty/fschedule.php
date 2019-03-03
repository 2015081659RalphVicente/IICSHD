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
                    <li class="nav-item dropdown active">
                        <a class="nav-link dropdown-toggle" style="color:white;" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <span data-feather="calendar"></span>
                            Schedule
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item active" href="fschedule.php">
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
                            $notifquery = "(SELECT notif.notifno, notif.notiftitle, notif.notifdesc, notif.notifaudience, notif.notifdate, users.userno FROM notif INNER JOIN users ON users.userno = notif.notifaudience WHERE notif.notifaudience = '" . $_SESSION['userno'] . "' ORDER by notif.notifdate DESC)"
                                    . " UNION "
                                    . "(SELECT notif.notifno, notif.notiftitle, notif.notifdesc, notif.notifaudience, notif.notifdate, notif.notifno as userno FROM notif WHERE notif.notifaudience = 'all' ORDER by notif.notifdate DESC)"
                                    . " UNION "
                                    . "(SELECT notif.notifno, notif.notiftitle, notif.notifdesc, notif.notifaudience, notif.notifdate, notif.notifno as userno FROM notif WHERE notif.notifaudience = 'faculty' ORDER by notif.notifdate DESC)";
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
                    <h1 class="h2">Faculty Schedule</h1>
                </div>

                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">CS</a>
                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">IS</a>
                        <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">IT</a>
                    </div>
                </nav>

                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <?php
                        $query = mysqli_query($conn, "SELECT * FROM schedule WHERE schedname = 'CS Faculty Schedule'");
                        if ($query->num_rows > 0) {
                            while ($row = $query->fetch_assoc()) {
                                $schedlink = $row['schedlink'];

                                if ($schedlink == TRUE) {
                                    echo '<iframe style="border:none; position:relative; width:100%; height:100vh;" width="100%" src="' . $schedlink . '?widget=true&amp;headers=false"></iframe>';
                                } else {
                                    echo '<br><h4>No schedule available yet.</h4><br>';
                                }
                            }
                        }
                        ?>
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <?php
                        $query = mysqli_query($conn, "SELECT * FROM schedule WHERE schedname = 'IS Faculty Schedule'");
                        if ($query->num_rows > 0) {
                            while ($row = $query->fetch_assoc()) {
                                $schedlink = $row['schedlink'];

                                if ($schedlink == TRUE) {
                                    echo '<iframe style="border:none; position:relative; width:100%; height:100vh;" width="100%" src="' . $schedlink . '?widget=true&amp;headers=false"></iframe>';
                                } else {
                                    echo '<br><h4>No schedule available yet.</h4><br>';
                                }
                            }
                        }
                        ?>
                    </div>
                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                        <?php
                        $query = mysqli_query($conn, "SELECT * FROM schedule WHERE schedname = 'IT Faculty Schedule'");
                        if ($query->num_rows > 0) {
                            while ($row = $query->fetch_assoc()) {
                                $schedlink = $row['schedlink'];

                                if ($schedlink == TRUE) {
                                    echo '<iframe style="border:none; position:relative; width:100%; height:100vh;" width="100%" src="' . $schedlink . '?widget=true&amp;headers=false"></iframe>';
                                } else {
                                    echo '<br><h4>No schedule available yet.</h4><br>';
                                }
                            }
                        }
                        ?>
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

        <!-- Icons -->
        <script src="../../js/feather.min.js"></script>
        <script>
            feather.replace()
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
    </body>
</html>

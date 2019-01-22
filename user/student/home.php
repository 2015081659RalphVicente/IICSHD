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

                    <li class="nav-item">
                        <a class="nav-link" href="consultations.php">
                            <span data-feather="info"></span>
                            Consultation
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
                        <a class="nav-link" href="account.php">
                            <span data-feather="user"></span>
                            Account
                        </a>
                    </li>

                </ul>

                <ul class="navbar-nav px-2">
                    <li class="nav-item text-nowrap">
                        <a style="font-size: 13px;" class="btn btn-danger bg-danger" href="../../logout.php" onclick="if (!confirm('Are you sure you want to log out?')) {
                                    return false;
                                }">
                            <span data-feather="log-out"></span>  Log Out
                        </a>
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
                                            <h6>
                                            </h6>
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

                </main>
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

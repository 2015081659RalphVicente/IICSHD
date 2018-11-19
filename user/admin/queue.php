
<?php
include '../../include/controller.php';

if (isset($_SESSION['user_name']) && $_SESSION['role'] == "faculty") {
    header("location:/iicshd/user/faculty/home.php");
}
if (isset($_SESSION['user_name']) && $_SESSION['role'] == "student") {
    header("location:/iicshd/user/student/home.php");
}
if (isset($_SESSION['user_name'])) {

    if ((time() - $_SESSION['last_time']) > 1200) {
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
                                <a class="nav-link active" href="queue.php">
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
                                <a class="nav-link" href="reports.php">
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
                        <h1 class="h2">Queue</h1>
                    </div>

                    <div class="row">

                        <div class="col-6 col-lg-6">
                            <div class="card">
                                <div class="card-header  bg-info text-white">
                                    <center><h5>Now Serving</h5></center>
                                </div>
                                <div class="card-body">
                                    <div style="align: center;">
                                        <center><h1>0005</h1></center>
                                        <hr>
                                        <p><b>Student Number:</b> 2015081659</p>
                                        <p><b>Student Name:</b> Ralph Angelo C. Vicente</p>
                                        <p><b>Transaction Type:</b> Document Inquiry</p>
                                    </div>
                                    <hr>
                                    <div class="row">

                                        <div class="col-sm-4">
                                            <form action="" post="method">
                                                <div class="card">
                                                        <div class="card-header">
                                                            <center><span class="fas fa-3x fa-volume-up"></span></center>
                                                        </div>
                                                        <div class="card-title btn btn-block">
                                                            <input type="button" value="Call Again"  onclick="play()">
                                                        </div>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="col-sm-4">
                                            <form action="" post="method">
                                                <div class="card">
                                                    <button>
                                                        <div class="card-header">
                                                            <center><span class="fas fa-3x fa-times"></span></center>
                                                        </div>
                                                        <div class="card-title">
                                                            No-Show
                                                        </div>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="col-sm-4">
                                            <form action="" post="method">
                                                <div class="card">
                                                    <button>
                                                        <div class="card-header">
                                                            <center><span class="fas fa-3x fa-arrow-right"></span></center>
                                                        </div>
                                                        <div class="card-title">
                                                            Next
                                                        </div>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-2 col-sm-2">
                            <div class="card">
                                <div class="card-header  bg-dark text-white">
                                    <center><h5>Waiting</h5></center>
                                </div>
                                <div class="card-body">
                                    <center><h2>0006</h2></center>
                                    <hr>
                                    <center><h2>0007</h2></center>
                                </div>
                            </div>
                        </div>


                        <div class="col-2 col-sm-2">
                            <div class="card">
                                <div class="card-header  bg-danger text-white">
                                    <center><h5>No-Show</h5></center>
                                </div>
                                <div class="card-body">
                                    <center><h2>0004</h2></center>
                                </div>
                            </div>
                        </div>


                        <div class="col-2 col-sm-2">
                            <div class="card">
                                <div class="card-header  bg-success text-white">
                                    <center><h5>Done</h5></center>
                                </div>
                                <div class="card-body">
                                    <center><h2>0001</h2></center>
                                    <hr>
                                    <center><h2>0002</h2></center>
                                    <hr>
                                    <center><h2>0003</h2></center>
                                </div>
                            </div>
                        </div>

                    </div>

                </main>
            </div>
        </div>

        <audio id="audio" src="../../include/ring.mp3" ></audio>

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

        <script>
            function play() {
                var audio = document.getElementById("audio");
                audio.play();
            }
        </script>

    </body>
</html>

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
                    <a class="nav-link" href="../../logout.php">
                        <span data-feather="log-out"></span>  Log Out
                    </a>
                </li>
            </ul>
        </nav>


        <div class="container-fluid">

            <div class="row">

                <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                    <div class="sidebar-sticky">
                        <ul class="nav flex-column">
                            <li class="nav-item">
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
                        <h1 class="h2">Home</h1>
                    </div>

                    <div class="accordion" id="accordionExample">

                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        <span class="fas fa-plus-circle"></span> Post Announcement
                                    </button>
                                </h5>
                            </div>

                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    <form action="" method="POST">

                                        <div class="form-group">
                                            <label for="title">Title <span class="require">*</span></label>
                                            <input type="text" class="form-control" name="title" />
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea rows="2" class="form-control" name="description" ></textarea>
                                        </div>

                                        <div class="form-group">
                                            <button style="float:right;" type="submit" class="btn btn-primary">
                                                Post
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>

                    <br>

                    <div class="card">
                        <div class="card-header bg-dark text-white"><h6>Announcement</h6></div>
                        <div class="card-body">
                            <h5 class="card-title">Tiger Day</h5>
                            <p class="card-text" style="font-size: 11px;">Tuesday, November 13, 2018 8:00:24 PM PHT by Noel Estrella</p>
                            <p class="card-text">Yellow day on November 16-17, 2018.</p>
                        </div>
                    </div>

                    <br>

                    <div class="card">
                        <div class="card-header bg-dark text-white"><h6>Announcement</h6></div>
                        <div class="card-body">
                            <h5 class="card-title">IICS INSTITUTIONAL MASS</h5>
                            <p class="card-text" style="font-size: 11px;">Monday, November 12, 2018 8:07:20 PM PHT by Noel Estrella</p>
                            <p class="card-text"><a href="https://docs.google.com/document/d/14uFqLXI_9dbHElkApPe215VusAJjANCYOLMgf1wcE9E/edit">IICS INSTITUTIONAL MASS</a> for the month of November 2018</p>
                        </div>
                    </div>

                    <br>

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

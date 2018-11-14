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
                
                <!-- Side Nav Bar -->
                <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                    <div class="sidebar-sticky">
                        <ul class="nav flex-column">
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
                                </div>
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


                    <!-- cards -->
                    <h3 class="font-weight-light text-center my-3">Choose Transaction Type</h3>

                    <!-- full size Card container -->
                    <div class="container-fluid mx-auto d-md-block my-3 ">
                        <div class="row text-center">

                            <!-- Card # -->
                            <div class="col-6 col-lg-3">
                                <div class="card flex-fill">
                                    <div class="card-header bg-info text-light">Document Inquiry</div>
                                    <div class="card-footer">
                                        <button class="btn btn-outline-info d-block w-75 mx-auto">Select</button>
                                    </div>
                                </div>
                                <br>
                            </div><!-- Card # -->
                            
                            <br>

                            <!-- Card # -->
                            <div class="col-6 col-lg-3">
                                <div class="card flex-fill">
                                    <div class="card-header bg-info text-light rounded">Enrollment Concern</div>
                                    <div class="card-footer">
                                        <button class="btn btn-outline-info d-block w-75 mx-auto">Select</button>
                                    </div>
                                </div>
                                <br>
                            </div><!-- Card # -->

                            <!-- Card # -->
                            <div class="col-6 col-lg-3">
                                <div class="card flex-fill">
                                    <div class="card-header bg-info text-light rounded">Meeting</div>
                                    <div class="card-footer">
                                        <button class="btn btn-outline-info d-block w-75 mx-auto">Select</button>
                                    </div>
                                </div>
                                <br>
                            </div><!-- Card # -->

                            <!-- Card # -->
                            <div class="col-6 col-lg-3">
                                <div class="card flex-fill">
                                    <div class="card-header bg-info text-light rounded">Other Inquiry</div>
                                    <div class="card-footer">
                                        <button class="btn btn-outline-info d-block w-75 mx-auto">Select</button>
                                    </div>
                                </div>
                                <br>
                            </div><!-- Card # -->

                        </div>
                    </div><!-- /full size Card container -->




                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">In Line</h1>
                    </div>

                    <div class="row text-center">

                        <div class="col-6 col-lg-3">
                            <div class="card flex-fill">
                                <div class="card-header bg-primary text-light rounded">Currently Serving</div>
                                <div class="card-footer">
                                    <h2 class="timer count-title count-number" data-to="100" data-speed="1500">N001</h2>
                                </div>
                            </div>
                            <br>
                        </div>

                        <div class="col-6 col-lg-3">
                            <div class="card flex-fill">
                                <div class="card-header bg-danger text-light rounded">Up Next</div>
                                <div class="card-footer">
                                    <h2 class="timer count-title count-number" data-to="100" data-speed="1500">N002</h2>
                                </div>
                            </div>
                            <br>
                        </div>

                        <div class="col-6 col-lg-3">
                            <div class="card flex-fill">
                                <div class="card-header bg-dark text-light rounded">Waiting</div>
                                <div class="card-footer">
                                    <h2 class="timer count-title count-number" data-to="100" data-speed="1500">N003</h2>
                                    <h2 class="timer count-title count-number" data-to="100" data-speed="1500">N004</h2>
                                </div>
                            </div><br>
                        </div>

                        <div class="col-6 col-lg-3">
                            <div class="card flex-fill">
                                <div class="card-header bg-success text-light rounded">Done</div>
                                <div class="card-footer">
                                    <h2 class="timer count-title count-number" data-to="100" data-speed="1500">N000</h2>
                                </div>
                            </div>
                            <br>
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

    </body>
</html>

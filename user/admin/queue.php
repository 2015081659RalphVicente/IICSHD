
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

if (isset($_POST['toggleClose'])) {
    $closeQueue = $_POST['closeQueue'];
    $qtogno = "1";

    $closeQuery = $conn->prepare("UPDATE qtoggle SET qtoggle=? WHERE qtogno=?");
    $closeQuery->bind_param("ii", $closeQueue, $qtogno);
    $closeQuery->execute();
    $closeQuery->close();

    if ($closeQuery == TRUE) {

        header("location: queue.php");
        exit;
    } else {
        echo "Queue toggle failed.";
    }
}

if (isset($_POST['toggleOpen'])) {
    $openQueue = $_POST['openQueue'];
    $qtogno = "1";

    $openQuery = $conn->prepare("UPDATE qtoggle SET qtoggle=? WHERE qtogno=?");
    $openQuery->bind_param("ii", $openQueue, $qtogno);
    $openQuery->execute();
    $openQuery->close();

    if ($openQuery == TRUE) {

        header("location: queue.php");
        exit;
    } else {
        echo "Queue toggle failed.";
    }
}

if (isset($_POST['qStart'])) {
    $qqno = $_POST['startQno'];
    $qstatus = "Now";

    $startQuery = $conn->prepare("UPDATE queue SET qstatus=? WHERE qno=?");
    $startQuery->bind_param("si", $qstatus, $qqno);
    $startQuery->execute();
    $startQuery->close();

    if ($startQuery == TRUE) {

        header("location: queue.php");
        exit;
    } else {
        echo "Start queue failed.";
    }
}

if (isset($_POST['qNext'])) {
    $qqdone = $_POST['userQDone'];
    $qnumdone = $_POST['userNumDone'];
    $qtype = $_POST['userQType'];
    $qdesc = $_POST['userQDesc'];
    $qstatus = "Done";
    $inqueue = "0";



    $nextQuery = $conn->prepare("UPDATE queue SET qstatus=? WHERE qno=?");
    $nextQuery->bind_param("si", $qstatus, $qqdone);
    $nextQuery->execute();
    $nextQuery->close();

    $userQ = $conn->prepare("UPDATE users SET inqueue=? WHERE userno=?");
    $userQ->bind_param("ii", $inqueue, $qnumdone);

    $insertQ = $conn->prepare("INSERT INTO queuelogs VALUES ('', ?, ?, ?, ?, ?)");
    $insertQ->bind_param("issss", $qnumdone, $qtype, $qdesc, $thisDate, $qstatus);

    if ($nextQuery == TRUE) {
        $checkQuery = mysqli_query($conn, "SELECT * FROM queue WHERE qstatus = 'Waiting' ORDER BY qno ASC LIMIT 1");
        $checkQueryResults = $checkQuery->num_rows;

        if ($checkQueryResults == '1') {
            $statusNow = "Now";
            $nowQno = $row['qno'];

            $getNext = $conn->prepare("UPDATE queue SET qstatus = ? WHERE qno = ?");
            $getNext->bind_param("si", $statusNow, $nowQno);
            $getNext->execute();
            $getNext->close();
        } else {
            echo 'Waiting List Empty.';
        }

        $userQ->execute();
        $userQ->close();

        $insertQ->execute();
        $insertQ->close();

        header("location: queue.php");
        exit;
    } else {
        echo "Start queue failed.";
    }
}

if (isset($_POST['qStart'])) {
    $qqno = $_POST['startQno'];
    $qstatus = "Now";

    $startQuery = $conn->prepare("UPDATE queue SET qstatus=? WHERE qno=?");
    $startQuery->bind_param("si", $qstatus, $qqno);
    $startQuery->execute();
    $startQuery->close();

    if ($startQuery == TRUE) {

        header("location: queue.php");
        exit;
    } else {
        echo "Start queue failed.";
    }
}

if (isset($_POST['qNoShow'])) {
    $qqdone = $_POST['userQNS'];
    $qnumdone = $_POST['usernsNumDone'];
    $qtype = $_POST['usernsQType'];
    $qdesc = $_POST['usernsQDesc'];
    $qstatus = "No-Show";
    $inqueue = "0";



    $nextQuery = $conn->prepare("UPDATE queue SET qstatus=? WHERE qno=?");
    $nextQuery->bind_param("si", $qstatus, $qqdone);
    $nextQuery->execute();
    $nextQuery->close();

    $userQ = $conn->prepare("UPDATE users SET inqueue=? WHERE userno=?");
    $userQ->bind_param("ii", $inqueue, $qnumdone);

    $insertQ = $conn->prepare("INSERT INTO queuelogs VALUES ('', ?, ?, ?, ?, ?)");
    $insertQ->bind_param("issss", $qnumdone, $qtype, $qdesc, $thisDate, $qstatus);

    if ($nextQuery == TRUE) {
        $checkQuery = mysqli_query($conn, "SELECT * FROM queue WHERE qstatus = 'Waiting' ORDER BY qno ASC LIMIT 1");
        $checkQueryResults = $checkQuery->num_rows;

        if ($checkQueryResults == '1') {
            $statusNow = "No-Show";
            $nowQno = $row['qno'];

            $getNext = $conn->prepare("UPDATE queue SET qstatus = ? WHERE qno = ?");
            $getNext->bind_param("si", $statusNow, $nowQno);
            $getNext->execute();
            $getNext->close();
        } else {
            echo 'Waiting List Empty.';
        }

        $userQ->execute();
        $userQ->close();

        $insertQ->execute();
        $insertQ->close();

        header("location: queue.php");
        exit;
    } else {
        echo "Start queue failed.";
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


        <title>IICS Help Desk - Admin</title>

        <!-- Bootstrap core CSS -->
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link href="../../css/dashboard.css" rel="stylesheet">
        <link href="../../fa-5.5.0/css/fontawesome.css" rel="stylesheet">

        <!-- Font Awesome JS -->
        <script defer src="../../fa-5.5.0/js/solid.js"></script>
        <script defer src="../../fa-5.5.0/js/fontawesome.js"></script>

        <style>
            .bg-orange{
                background-color: #da5913;
            }
        </style>


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

                    <li class="nav-item active">
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
                        <h1 class="h2">Queue</h1>
                    </div>

                    <?php
                    $qCheck = mysqli_query($conn, "SELECT * FROM qtoggle WHERE qtogno = '1'");

                    if ($qCheck->num_rows > 0) {
                        while ($row = $qCheck->fetch_assoc()) {
                            $qtoggle = $row['qtoggle'];

                            if ($qtoggle == '1') {
                                echo

                                '<div class="card">'
                                . '<div class="card-header bg-success text-white">'
                                . '<h5>Status: Open '
                                . '<div style="float: right;" class="btn-group" role="group">'
                                . '<form method="post" action="">'
                                . '<input type="hidden" name="closeQueue" value="0">'
                                . '<button class="btn btn-danger" type = "submit" name="toggleClose"><span class="fas fa-lock"></span> Close Queue</button>'
                                . '</form>'
                                . '</div>'
                                . '</h5> '
                                . '</div> '
                                . '</div>'
                                . '<br>';

                                echo '<div class="row">';

                                $qQuery = mysqli_query($conn, "SELECT users.userno, queue.qdesc, LPAD(queue.qno,4,0), queue.qtype, queue.qdate, queue.qstatus, users.userid, users.fname, users.mname, users.lname FROM queue INNER JOIN users ON queue.userno = users.userno AND queue.qstatus = 'Now' LIMIT 1");



                                echo '<div class = "col-6 col-lg-6">
                                <div class = "card">
                                <div class = "card-header bg-info text-white">
                                <center><h5>Now Serving</h5></center>
                                </div>
                                <div class = "card-body">';
                                if ($qQuery->num_rows > 0) {
                                    while ($row = $qQuery->fetch_assoc()) {
                                        $qno = $row['LPAD(queue.qno,4,0)'];
                                        $userno = $row['userno'];
                                        $qdate = $row['qdate'];
                                        $userid = $row['userid'];
                                        $username = ($row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']);
                                        $qtype = $row['qtype'];
                                        $qstatus = $row['qstatus'];
                                        $qdesc = $row['qdesc'];
                                        echo' <div style = "align: center;">
                                            
                                <center><h1>' . $qno . '</h1></center>
                                <hr>
                                <p><b>Student Number:</b> ' . $userid . '</p>
                                <p><b>Student Name:</b> ' . $username . '</p>
                                <p><b>Transaction Type:</b> ' . $qtype . '</p>';
                                        if ($qtype == 'Other') {
                                            echo '<p><b>Description:</b> ' . $qdesc . '</p>'
                                            . '</div><hr>';
                                        } else {
                                            echo '</div>'
                                            . '<hr>';
                                        }

                                        echo'<div class="row">

                            <div class="col-sm-4">
                                <form action="" method="post">
                                    <div class="card">
                                        <div class="card-header">
                                            <center><span class="fas fa-3x fa-volume-up"></span></center>
                                        </div>
                                        <div class="card-title btn btn-block">
                                            <button type="button" class="btn btn-secondary" onclick="play()">Call Again</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-sm-4">
                                <form action="" method="post">
                                    <div class="card">
                                        <div class="card-header">
                                            <center><span class="fas fa-3x fa-times-circle"></span></center>
                                        </div>
                                        <div class="card-title btn btn-block">
                                            <input type ="hidden" name="userQNS" value="' . $qno . '">
                                            <input type ="hidden" name="usernsQType" value="' . $qtype . '">
                                            <input type ="hidden" name="usernsQDesc" value="' . $qdesc . '">
                                            <input type ="hidden" name="usernsNumDone" value="' . $userno . '">  
                                            <button type="submit" name="qNoShow" class="btn btn-danger">No-Show</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-sm-4">
                                <form action="" method="post">
                                    <div class="card">
                                        <div class="card-header">
                                            <center><span class="fas fa-3x fa-check-circle"></span></center>
                                        </div>
                                        <div class="card-title btn btn-block">
                                            <input type ="hidden" name="userQDone" value="' . $qno . '">
                                            <input type ="hidden" name="userQType" value="' . $qtype . '">
                                            <input type ="hidden" name="userQDesc" value="' . $qdesc . '">
                                            <input type ="hidden" name="userNumDone" value="' . $userno . '">       
                                            <button type="submit" name ="qNext" class="btn btn-success">Done</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
            </div>
            <br>
        </div>';
                                    }
                                } else {
                                    echo '<center><h3>Empty</h3></center>'
                                    . '<hr> ';

                                    $qNextWait = mysqli_query($conn, "SELECT qno, LPAD(qno,4,0) FROM queue WHERE qstatus = 'Waiting' ORDER BY qno ASC LIMIT 1");

                                    if ($qNextWait->num_rows > 0) {
                                        while ($row = $qNextWait->fetch_assoc()) {
                                            $qno = $row['LPAD(qno,4,0)'];
                                            $qqno = $row['qno'];

                                            echo
                                            '<div class="col-sm-12">
                                                    <form action="" method="post">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <center><span class="fas fa-3x fa-arrow-alt-circle-right"></span></center>
                                                            </div>
                                                            <input type="hidden" name="startQno" value ="' . $qqno . '">
                                                            <div class="card-title btn btn-block">
                                                                <button type="submit" name ="qStart" class="btn btn-success">Call Next</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                </div></div></div>';
                                        }
                                    } else {
                                        echo '</div></div></div>';
                                    }
                                }


                                echo '        
        <div class="col-2 col-sm-2">
            <div class="card">
                <div class="card-header  bg-orange text-white">
                    <center><h5>Waiting</h5></center>
                </div>
                <div class="card-body">';

                                $qWaiting = mysqli_query($conn, "SELECT LPAD(qno,4,0) FROM queue WHERE qstatus = 'Waiting' LIMIT 5");
                                if ($qWaiting->num_rows > 0) {
                                    while ($row = $qWaiting->fetch_assoc()) {
                                        $qno = $row['LPAD(qno,4,0)'];
                                        echo '<center><h2>' . $qno . '</h2></center><hr>';
                                    }
                                } else {
                                    echo '<center><h4>Empty</h4></center>';
                                }

                                echo'  </div>
            </div>
        </div>';


                                echo '<div class="col-2 col-sm-2">
            <div class="card">
                <div class="card-header  bg-dark text-white">
                    <center><h5>No-Show</h5></center>
                </div>
                <div class="card-body">';

                                $qNoShow = mysqli_query($conn, "SELECT LPAD(qno,4,0) FROM queue WHERE qstatus = 'No-Show' ORDER BY qno DESC LIMIT 5");
                                if ($qNoShow->num_rows > 0) {
                                    while ($row = $qNoShow->fetch_assoc()) {
                                        $qno = $row['LPAD(qno,4,0)'];
                                        echo '<center><h2>' . $qno . '</h2></center><hr>';
                                    }
                                } else {
                                    echo '<center><h4>Empty</h4></center>';
                                }

                                echo'  </div>
            </div>
        </div>';


                                echo '<div class="col-2 col-sm-2">
            <div class="card">
                <div class="card-header  bg-success text-white">
                    <center><h5>Done</h5></center>
                </div>
                <div class="card-body">';

                                $qDone = mysqli_query($conn, "SELECT LPAD(qno,4,0) FROM queue WHERE qstatus = 'Done' ORDER BY qno DESC LIMIT 5");
                                if ($qDone->num_rows > 0) {
                                    while ($row = $qDone->fetch_assoc()) {
                                        $qno = $row['LPAD(qno,4,0)'];
                                        echo '<center><h2>' . $qno . '</h2></center><hr>';
                                    }
                                } else {
                                    echo '<center><h4>Empty</h4></center>';
                                }

                                echo'  </div>
            </div>
            <br>
        </div>';
                            } else {
                                echo
                                '<div class="card">'
                                . '<div class="card-header bg-dark text-white">'
                                . '<h5>Status: Closed '
                                . '<div style="float: right;" class="btn-group" role="group">'
                                . '<form method="post" action="">'
                                . '<input type="hidden" name="openQueue" value="1">'
                                . '<button class="btn btn-success" type = "submit" name="toggleOpen"><span class="fas fa-unlock"></span> Open Queue</button>'
                                . '</form>'
                                . '</div>'
                                . '</h5> '
                                . '</div> '
                                . '</div>';
                            }
                        }
                    }
                    ?>

            <br>
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

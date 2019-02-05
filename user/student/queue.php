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

if (isset($_POST['getQueueNum'])) {

    $qType = $_POST["selectType"];
    $qDesc = $_POST['qDesc'];
    $userno = $_SESSION['userno'];
    $qStatus = "Waiting";
    $inqueue = "1";

    $submitSql = $conn->prepare("INSERT INTO queue VALUES ('', ?, ?, ?, NOW(), ?)");
    $submitSql->bind_param("isss", $_SESSION['userno'], $qType, $qDesc, $qStatus);

    $submitSql2 = $conn->prepare("INSERT INTO queuelogs VALUES ('', ?, ?, ?, NOW(), ?)");
    $submitSql2->bind_param("isss", $_SESSION['userno'], $qType, $qDesc, $qStatus);

    $userQ = $conn->prepare("UPDATE users SET inqueue=? WHERE userno=?");
    $userQ->bind_param("ii", $inqueue, $_SESSION['userno']);


    if ($submitSql == TRUE) {

        $submitSql->execute();
        $submitSql->close();

        if ($submitSql2 == TRUE) {
            $submitSql2->execute();
            $submitSql2->close();
        }

        if ($userQ == TRUE) {
            $userQ->execute();
            $userQ->close();
        }
        header("Location: queue.php");
        exit;
    } else {
        $postFailed = '<div class="alert alert-danger">
                        Queue Failed!
                        </div>';
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

                    <li class="nav-item active">
                        <a class="nav-link" style="color:white;" href="queue.php">
                            <span data-feather="users"></span>
                            Queue
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" style="color:white;" href="consultations.php">
                            <span data-feather="info"></span>
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
                    $qcToggle = mysqli_query($conn, "SELECT * FROM qtoggle WHERE qtogno = '1'");

                    if ($qcToggle->num_rows > 0) {
                        while ($row = $qcToggle->fetch_assoc()) {
                            $qtoggle = $row['qtoggle'];

                            if ($qtoggle == '0') {
                                echo

                                '<div class="card">'
                                . '<div class="card-header bg-dark text-white">'
                                . '<h5>Status: Closed '
                                . '</h5> '
                                . '</div> '
                                . '</div>'
                                . '<br>';
                            } else {

                                echo '<div class="card">'
                                . '<div class="card-header bg-success text-white">'
                                . '<h5>Status: Open '
                                . '</h5> '
                                . '</div> '
                                . '</div>'
                                . '<br>';
                                ;
                                $qCheck = mysqli_query($conn, "SELECT *, LPAD(queue.qno,4,0) FROM users LEFT JOIN queue ON users.userno = queue.userno WHERE users.userid = " . $_SESSION['userid'] . " ORDER BY queue.qno DESC LIMIT 1");

                                if ($qCheck->num_rows > 0) {
                                    while ($row = $qCheck->fetch_assoc()) {
                                        $inqueue = $row['inqueue'];
                                        $qno = $row['LPAD(queue.qno,4,0)'];
                                        $qdate = $row['qdate'];
                                        $qdesc = $row['qdesc'];
                                        $qtype = $row['qtype'];


                                        if ($inqueue == '0') {
                                            echo '
                    <a href="#getQueue" data-toggle="modal">
                        <button class="btn btn-primary btn-block">
                            <h4 class="text-center my-3">Get Queue Ticket</h4>
                        </button>
                    </a>

                    <div id="getQueue" class="modal fade" role="dialog">

                        <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">

                                    <h4 class="modal-title">Get Queue Ticket</h4>
                                </div>

                                <div class="modal-body">
                                    <form method="post" action="">
                                        <div class="row">
                                            <div class="col-sm-12">

                                                <div class="form-group">
                                                    <label for="title"><h5>Transaction Type: <span class="require">*</span></h5></label>
                                                    <select name="selectType" class="form-control" id="selectType" required>
                                                        <option selected disabled>Select one:</option>
                                                        <option value="Document Inquiry">Document Inquiry</option>
                                                        <option value="Enrollment Concern">Enrollment Concern</option>
                                                        <option value="Meeting with Admin">Meeting with Admin</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>

                                                <div class="form-group" id="qDesc">
                                                    <label for="description"><h5>Description: *</h5></label>
                                                    <textarea rows="2" class="form-control" name="qDesc"></textarea>
                                                </div>

                                            </div>
                                        </div>
                                        <br>
                                        <div class="modal-footer">
                                            <button style="float: right;" type="button" class="btn btn-secondary btn-m" data-dismiss="modal"><span class="fas fa-times"></span> Cancel</button>
                                            <button style="float: right;" type="submit" name="getQueueNum" class="btn btn-success btn-m"><span class="fas fa-clipboard-check" ></span> Queue</button>

                                        </div>
                                </div>
                                </form>
                            </div>
                        </div>

                    </div>';
                                        } else {
                                            echo '<div class="card">'
                                            . '<div class="card-header">'
                                            . '<h4 class="text-center my-3">Your Queue Number</h4>'
                                            . '</div>'
                                            . '<div class="card-body">'
                                            . '<h2 class="text-center">' . $qno . '</h2>'
                                            . '<h6 class="text-center">' . $qdate . '</h6>'
                                            . '<hr>'
                                            . '<h6 class="text-center">Transaction Type: ' . $qtype . '</h6>';
                                            if ($qtype == 'Other') {
                                                echo '<h6 class="text-center">Description: ' . $qdesc . '</h6>'
                                                . '</div></div>';
                                            } else {
                                                echo '</div>'
                                                . '</div>';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    ?>


                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">In Line</h1>
                    </div>



                    <?php
                    $qcToggle = mysqli_query($conn, "SELECT * FROM qtoggle WHERE qtogno = '1'");

                    if ($qcToggle->num_rows > 0) {
                        while ($row = $qcToggle->fetch_assoc()) {
                            $qtoggle = $row['qtoggle'];

                            if ($qtoggle == '0') {
                                echo

                                '<div class="card">'
                                . '<div class="card-header bg-dark text-white">'
                                . '<h5>Status: Closed '
                                . '</h5> '
                                . '</div> '
                                . '</div>'
                                . '<br>';
                            } else {

                                echo '                    <div class="row text-center">
                                        <div class="col-lg-6">
                            <div class="card flex-fill">
                                <div class="card-header bg-info text-light rounded"><h5>Now Serving</h5></div>
                                <div class="card-body">';

                                $qWaiting = mysqli_query($conn, "SELECT LPAD(qno,4,0) FROM queue WHERE qstatus = 'Now' LIMIT 1");
                                if ($qWaiting->num_rows > 0) {
                                    while ($row = $qWaiting->fetch_assoc()) {
                                        $qno = $row['LPAD(qno,4,0)'];
                                        echo '<center><h2>' . $qno . '</h2></center></div>';
                                    }
                                } else {
                                    echo '<center><h4>Empty</h4></center></div>';
                                }
                            }
                        }
                    }
                    ?>
            </div>
        <br>
        </div>

        <br>

        <div class="col-lg-2">
            <div class="card flex-fill">
                <div class="card-header bg-orange text-light rounded"><h5>Waiting</h5></div>
                <div class="card-body">
                    <?php
                    $qWaiting = mysqli_query($conn, "SELECT LPAD(qno,4,0) FROM queue WHERE qstatus = 'Waiting' LIMIT 5");
                    if ($qWaiting->num_rows > 0) {
                        while ($row = $qWaiting->fetch_assoc()) {
                            $qno = $row['LPAD(qno,4,0)'];
                            echo '<center><h4>' . $qno . '</h4></center><hr>';
                        }
                    } else {
                        echo '<center><h4>Empty</h4></center>';
                    }
                    ?>
                </div>
            </div>
            <br>
        </div>

        <div class="col-lg-2">
            <div class="card flex-fill">
                <div class="card-header bg-dark text-light rounded"><h5>No-Show</h5></div>
                <div class="card-body">
                    <?php
                    $qWaiting = mysqli_query($conn, "SELECT LPAD(qno,4,0) FROM queue WHERE qstatus = 'No-Show' ORDER BY qno DESC LIMIT 5");
                    if ($qWaiting->num_rows > 0) {
                        while ($row = $qWaiting->fetch_assoc()) {
                            $qno = $row['LPAD(qno,4,0)'];
                            echo '<center><h4>' . $qno . '</h4></center><hr>';
                        }
                    } else {
                        echo '<center><h4>Empty</h4></center>';
                    }
                    ?>
                </div>
            </div>
            <br>
        </div>

        <div class="col-lg-2">
            <div class="card flex-fill">
                <div class="card-header bg-success text-light rounded"><h5>Done</h5></div>
                <div class="card-body">
                    <?php
                    $qWaiting = mysqli_query($conn, "SELECT LPAD(qno,4,0) FROM queue WHERE qstatus = 'Done' ORDER BY qno DESC LIMIT 5");
                    if ($qWaiting->num_rows > 0) {
                        while ($row = $qWaiting->fetch_assoc()) {
                            $qno = $row['LPAD(qno,4,0)'];
                            echo '<center><h4>' . $qno . '</h4></center><hr>';
                        }
                    } else {
                        echo '<center><h4>Empty</h4></center>';
                    }
                    ?>
                </div>
            </div>
            <br>
            <br>
        </div>
    </div>

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

<script>
    $("#qDesc").hide();
    $("#selectType").change(function () {
        var val = $("#selectType").val();
        if (val == "Other") {
            $("#qDesc").show();
        } else {
            $("#qDesc").hide();
        }
    });
</script>

</body>
</html>

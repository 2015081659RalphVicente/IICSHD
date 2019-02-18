
<?php
include '../../include/controller.php';
include '../../include/upload.php';

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
    header("location:/iicshd/index.php");
}

$secqErr = $_FILES['fileToUpload'] = '';

if (isset($_GET['addSecQ'])) {
    $addSecQ = $_GET['addSecQ'];
} else {
    $addSecQ = '';
}

if (isset($_GET['upload'])) {
    $upload = $_GET['upload'];
} else {
    $upload = '';
}

if (isset($_GET['uploadfail'])) {
    $uploadfail = $_GET['uploadfail'];
} else {
    $uploadfail = '';
}

if (isset($_POST['addnewsecq'])) {
    $newsecq = $_POST['newsecq'];

    $bool = TRUE;

    $secqcheck = $conn->prepare("SELECT secqno FROM secq WHERE secq=? ");
    $secqcheck->bind_param("s", $newsecq);
    $secqcheck->execute();

    $secqcheckresult = $secqcheck->get_result();

    if ($secqcheckresult->num_rows > 0) {
        $secqErr = '<div class="alert alert-warning">
                        <strong>Security Question</strong> already exists!
         </div>';
        $bool = FALSE;
    }

    if ($bool == TRUE) {

        $addnewsec = $conn->prepare("INSERT INTO secq VALUES ('', ?)");
        $addnewsec->bind_param("s", $newsecq);

        $addnewsec->execute();
        $addnewsec->close();

        $passval = 'Added security question successfully.';

        $passaction = "Add Security Question";
        $logpass = $conn->prepare("INSERT INTO updatelogs VALUES ('',?,?,NOW(),?)");
        $logpass->bind_param("sss", $passaction, $_SESSION['user_name'], $passval);
        $logpass->execute();
        $logpass->close();

        $_GET['addSecQ'] = 'success';
        echo '<script>window.location.href="cpanel.php?addSecQ=success"</script>';
    }
}
?>

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
            .header {
                padding: 10px;
                text-align: center;
                background: #2e2e2e;
                color: white;
                font-size: 30px;
            }

            .headerline {
                padding: 1px;
                text-align: center;
                background: #b00f24;
                color: white;
                font-size: 2px;
            }
        </style>


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
                        <a class="nav-link"  style="color:white;" href="home.php">
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

                    <li class="nav-item">
                        <a class="nav-link" style="color:white;" href="queue.php">
                            <span data-feather="users"></span>
                            Queue
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
                    <li class="nav-item">
                        <a class="nav-link" style="color:white;" href="stats.php">
                            <span data-feather="bar-chart-2"></span>
                            Statistics
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="color:white;" href="reports.php">
                            <span data-feather="layers"></span>
                            Reports
                        </a>
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
                            <a class="dropdown-item active" href="cpanel.php">
                                <i class="fas fa-sliders-h"></i>
                                Control Panel
                            </a>
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
                    <h1 class="h2">Control Panel</h1>
                </div>

                <?php
                if ($addSecQ == TRUE) {
                    echo '<div class="alert alert-success"><span class="fas fa-check"></span> Security question added successfully!</div>';
                } else {
                    echo '';
                }

                if ($upload == TRUE) {
                    echo '<div class="alert alert-success"><span class="fas fa-check"></span> Document uploaded successfully!</div>';
                } else {
                    echo '';
                }

                if ($uploadfail == TRUE) {
                    echo '<div class="alert alert-danger"><span class="fas fa-times"></span> <b>Document upload failed! </b><br>'
                    . 'It may be caused by any of the following reasons: <br>'
                    . '<ul>'
                    . '<li>The file already exists.</li>'
                    . '<li>The file is too large. The maximum file size is 20MB.</li>'
                    . '<li>Only .pdf, .docx and .doc files are allowed.</li>'
                    . '</ul>'
                    . '</div>';
                } else {
                    echo '';
                }
                ?>

                <div class="row">

                    <div class="col-sm-2">
                        <div class="card">
                            <ul class="list-group list-group-flush">
                                <a href="cpanel.php"><li class="list-group-item active">General <span style="float:right;" class="fas fa-caret-right"></span></li></a>
                                <a href="cpanel2.php"><li class="list-group-item">User Accounts <span style="float:right;" class="fas fa-caret-right"></span></li></a>
                                <a href="cpanel3.php"><li class="list-group-item">Queue Settings <span style="float:right;" class="fas fa-caret-right"></span></li></a>
                                <a href="cpanel4.php"><li class="list-group-item">Manage Uploads <span style="float:right;" class="fas fa-caret-right"></span></li></a>
                            </ul>
                        </div>
                    </div>

                    <div class="col-sm-10">
                        <div class="card">
                            <h4 class="card-header">General Settings</h4>
                            <div class="card-body">

                                <h5 class="card-title">Registration - Add Security Question</h5>
                                <hr>
                                <form action="" method="POST">
                                    <div class="alert alert-secondary"><span class="fas fa-exclamation-circle"></span>
                                        Security questions are mandatory each time a user creates an account. 
                                        This provides users an extra security layer and serves as an authenticator should they forget their password.
                                    </div>
                                    <label for="oldPw" class="control-label">New Security Question:</label>
                                    <input type="text" class="form-control" id="newsecq" required name="newsecq">
                                    <?php echo $secqErr; ?>
                                    <br>
                                    <button type="submit" name = "addnewsecq" class="btn btn-success float-right">Add</button>
                                </form>

                                <br>

                                <h5 class="card-title">Document Templates</h5>
                                <hr>
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="alert alert-secondary"><span class="fas fa-exclamation-circle"></span>
                                        File Upload Rules:
                                        <ul>
                                            <li>The maximum file size is 20MB.</li>
                                            <li>Only .pdf, .docx and .doc files are allowed.</li>
                                        </ul>
                                    </div>

                                    <label for="filename">Filename: </label>

                                    <input type="text" id="fileName" name="fileName" class="form-control"><br>

                                    <label for="newfile">Upload New Document Template: </label>

                                    <input type="file" id="fileToUpload" name="fileToUpload" class="form-control" accept=".pdf, .docx, .doc">
                                    <br>
                                    <button type="submit" name = "uploadFile" class="btn btn-success float-right">Upload</button>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
                <br>

            </main>

        </div>

        <div class="container-fluid headerline">
            &nbsp;
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

                $('#myannouncements').DataTable({
                    "bLengthChange": false,
                    pageLength: 5,
                    initComplete: function () {
                        this.api().columns().every(function () {
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

        <script>
            $('.collapse').on('shown.bs.collapse', function () {
                $(this).parent().find(".fa-plus-circle").removeClass("fa-plus-circle").addClass("fa-minus-circle");
            }).on('hidden.bs.collapse', function () {
                $(this).parent().find(".fa-minus-circle").removeClass("fa-minus-circle").addClass("fa-plus-circle");
            });
        </script>
    </body>
</html>
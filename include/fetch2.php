<?php

require 'controller.php';

header('Content-Type: application/json');

if (isset($_POST['view'])) {

// $con = mysqli_connect("localhost", "root", "", "notif");
    if ($_POST["view"] != '') {
        $update_query = "UPDATE notif SET notifstatus = 1 WHERE notifstatus=0";
        mysqli_query($conn, $update_query);
    }

    // admin notif

    $query = "SELECT notif.notifno, notif.notiftitle, notif.notifdesc, notif.notifaudience, notif.notifdate, users.userno 
                                                    FROM notif 
                                                INNER JOIN users 
                                                ON users.userno = notif.notifaudience 
                                                WHERE notif.notifaudience = '" . $_SESSION['userno'] . "' 
                                                UNION ALL 
                                            SELECT notif.notifno, notif.notiftitle, notif.notifdesc, notif.notifaudience, notif.notifdate, notif.notifno as userno 
                                                    FROM notif 
                                                WHERE notif.notifaudience = 'all' 
                                                UNION ALL
                                            SELECT notif.notifno, notif.notiftitle, notif.notifdesc, notif.notifaudience, notif.notifdate, notif.notifno as userno 
                                                    FROM notif 
                                                    WHERE notif.notifaudience = 'student' 
                                            ORDER BY notifno DESC LIMIT 4";

    $result = mysqli_query($conn, $query);

    $output = '';

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {

            $notiftitle = $row['notiftitle'];
            $notifdesc = $row['notifdesc'];
            $notifdate = $row['notifdate'];

            if ($notiftitle == "New Announcement Posted") {
                $href = 'href="home.php"';
            }

            if ($notiftitle == "Document Status Updated") {
                $href = 'href="documents.php"';
            }

            if ($notiftitle == "New File Upload") {
                $href = 'href="documents.php"';
            }

            if ($notiftitle == "Schedule Updated") {
                $href = 'href="fschedule.php"';
            }

            if ($notiftitle == "Queue Status") {
                $href = 'href="queue.php"';
            }

            if ($notiftitle == "Consultation Request Update") {
                $href = 'href="consultations.php"';
            }


            $output .= '
   <li style="width: 300px; white-space: normal;">
   <a ' . $href . ' class="dropdown-item style="width: 300px; white-space: normal;">
                                                <span style="font-size: 13px;"><strong> ' . $notiftitle . ' </strong></span><br>
                                                <span style="width: 300px; white-space: normal;">' . $notifdesc . '</span><br>
                                                <span style="font-size: 10px;" > ' . date("m/d/Y h:iA", strtotime($notifdate)) . ' </span><br>
                                            </a></li>
   <div class="dropdown-divider"></div>
   ';
        }
        $output .= '                                               <a class="dropdown-item" href="notifications.php" style="color: blue; width: 300px; white-space: normal;">
                                                <center>View All Notifications</center>
                                            </a>';
    } else {
        $output .= '                        <li>
                                            <a class="dropdown-item" href="#" style="width: 300px; white-space: normal;">
                                                No new notifications.
                                            </a>
                                            </li>';
    }

    $status_query = "SELECT notif.notifno, notif.notifstatus, notif.notiftitle, notif.notifdesc, notif.notifaudience, notif.notifdate, users.userno 
                                                    FROM notif 
                                                INNER JOIN users 
                                                ON users.userno = notif.notifaudience 
                                                WHERE notif.notifaudience = '" . $_SESSION['userno'] . "'
                                                AND notif.notifstatus = '0'
                                                UNION ALL 
                                            SELECT notif.notifno, notif.notifstatus, notif.notiftitle, notif.notifdesc, notif.notifaudience, notif.notifdate, notif.notifno as userno 
                                                    FROM notif 
                                                WHERE notif.notifaudience = 'all'
                                                AND notif.notifstatus = '0'
                                                UNION ALL
                                            SELECT notif.notifno, notif.notifstatus, notif.notiftitle, notif.notifdesc, notif.notifaudience, notif.notifdate, notif.notifno as userno 
                                                    FROM notif 
                                                    WHERE notif.notifaudience = 'student'
                                                    AND notif.notifstatus = '0'
                                            ORDER BY notifno DESC LIMIT 4";

    $result_query = mysqli_query($conn, $status_query);
    $count = mysqli_num_rows($result_query);
    $data = array(
        'notification' => $output,
        'unseen_notification' => $count
    );

    echo json_encode($data);
}

if (isset($_POST['waitingList'])) {

    $qWaiting = mysqli_query($conn, "SELECT LPAD(qno,4,0) FROM queue WHERE qstatus = 'Waiting' LIMIT 5");
    $waitOutput = '';

    if ($qWaiting->num_rows > 0) {
        while ($row = $qWaiting->fetch_assoc()) {
            $qno = $row['LPAD(qno,4,0)'];

            $waitOutput .= '<center><h2>' . $qno . '</h2></center><hr>';
        }
    } else {
        $waitOutput .= '<center><h4>Empty</h4></center>';
    }

    $waitData = array(
        'waitingList' => $waitOutput
    );

    echo json_encode($waitData);
}

if (isset($_POST['nowList'])) {

    $qNow = mysqli_query($conn, "SELECT LPAD(qno,4,0) FROM queue WHERE qstatus = 'Now' LIMIT 1");

    $nowOutput = '';

    if ($qNow->num_rows > 0) {
        while ($row = $qNow->fetch_assoc()) {
            $qno = $row['LPAD(qno,4,0)'];

            $nowOutput .= '<center><h2>' . $qno . '</h2></center>';
        }
    } else {
        $nowOutput .= '<center><h4>Empty</h4></center>';
    }

    $nowData = array(
        'nowList' => $nowOutput
    );

    echo json_encode($nowData);
}

if (isset($_POST['nsList'])) {

    $qnos = mysqli_query($conn, "SELECT LPAD(qno,4,0) FROM queue WHERE qstatus = 'No-Show' ORDER BY qno DESC LIMIT 5");

    $nsOutput = '';

    if ($qnos->num_rows > 0) {
        while ($row = $qnos->fetch_assoc()) {
            $qno = $row['LPAD(qno,4,0)'];
            $nsOutput .= '<center><h2>' . $qno . '</h2></center><hr>';
        }
    } else {
        $nsOutput .= '<center><h4>Empty</h4></center>';
    }

    $nsData = array(
        'nsList' => $nsOutput
    );

    echo json_encode($nsData);
}

if (isset($_POST['doneList'])) {

    $qdone = mysqli_query($conn, "SELECT LPAD(qno,4,0) FROM queue WHERE qstatus = 'Done' ORDER BY qno DESC LIMIT 5");

    $doneOutput = '';

    if ($qdone->num_rows > 0) {
        while ($row = $qdone->fetch_assoc()) {
            $qno = $row['LPAD(qno,4,0)'];
            $doneOutput .= '<center><h2>' . $qno . '</h2></center><hr>';
        }
    } else {
        $doneOutput .= '<center><h4>Empty</h4></center>';
    }

    $doneData = array(
        'doneList' => $doneOutput
    );

    echo json_encode($doneData);
}
?>    



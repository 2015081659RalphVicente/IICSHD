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
                                                    WHERE notif.notifaudience = 'admin' 
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
            if ($notiftitle == "New Queue Ticket") {
                $href = 'href="queue.php"';
            }
            if ($notiftitle == "Schedule Updated") {
                $href = 'href="fschedule.php"';
            }

            $output .= '
   <li style="width: 300px; white-space: normal;">
   <a ' . $href . ' class="dropdown-item style="width: 300px; white-space: normal;">
                                                <span style="font-size: 13px;"><strong> ' . $notiftitle . ' </strong></span><br>
                                                <span style="width: 300px; white-space: normal;">' . $notifdesc . '</span><br>
                                                <span style="font-size: 10px;" > ' . $notifdate . ' </span><br>
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
                                                    WHERE notif.notifaudience = 'admin'
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
?>    



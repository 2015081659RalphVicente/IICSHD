<?php
require 'controller.php';

//setting header to json
header('Content-Type: application/json');

//query to get data from the table
$query = sprintf("(select COUNT(qno) as qno, qstatus from queuelogs where qstatus = 'Done') union (select COUNT(qno) as qno, qstatus from queuelogs where qstatus = 'No-Show')");

//execute query
$result = $conn->query($query);

//loop through the returned data
$data = array();
foreach ($result as $row) {
  $data[] = $row;
}


//free memory associated with result
$result->close();

//close connection
$conn->close();

//now print the data
print json_encode($data);
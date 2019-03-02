<?php
require 'controller.php';

//setting header to json
header('Content-Type: application/json');

//query to get data from the table
$query = sprintf("(select COUNT(qno) as qno, qtype from queuelogs where qtype = 'Document Inquiry') union (select COUNT(qno) as qno, qtype from queuelogs where qtype = 'Enrollment Concern') UNION (select COUNT(qno) as qno, qtype from queuelogs where qtype = 'Meeting with Admin') UNION (select COUNT(qno) as qno, qtype from queuelogs where qtype = 'Other') union (select COUNT(qno) as qno, qtype from queuelogs where qtype = 'Document Submission')");

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
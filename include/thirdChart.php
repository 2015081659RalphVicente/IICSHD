<?php
require 'controller.php';

//setting header to json
header('Content-Type: application/json');

//query to get data from the table
$query = sprintf("(select COUNT(docno) as docno, docstatus from documents where docstatus = 'Received by Office') 
union 
(select COUNT(docno) as docno, docstatus from documents where docstatus = 'On-Process') 
UNION 
(select COUNT(docno) as docno, docstatus from documents where docstatus = 'Processed') 
UNION 
(select COUNT(docno) as docno, docstatus from documents where docstatus = 'For Release') 
union
(select COUNT(docno) as docno, docstatus from documents where docstatus = 'Received by Student')
UNION
(select COUNT(docno) as docno, docstatus from documents where docstatus = 'Not Received')");

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
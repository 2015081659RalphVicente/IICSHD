<?php

require 'controller.php';

//setting header to json
header('Content-Type: application/json');

//query to get data from the table
$query = sprintf("(select COUNT(docno) as docno, docstatus from doclogs where docstatus = 'Received by Office') 
union 
(select COUNT(docno) as docno, docstatus from doclogs where docstatus = 'On-Process') 
UNION 
(select COUNT(docno) as docno, docstatus from doclogs where docstatus = 'Processed') 
UNION 
(select COUNT(docno) as docno, docstatus from doclogs where docstatus = 'For Release') 
union
(select COUNT(docno) as docno, docstatus from doclogs where docstatus = 'Received by Student')
UNION
(select COUNT(docno) as docno, docstatus from doclogs where docstatus = 'Not Received')
UNION
(select COUNT(docno) as docno, docstatus from doclogs where docstatus = 'Submitted')");

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

<?php
include('procedures.php');

$con = getConnection();

//$query_results = test($con);
//$query_results = procedureTest($con);

$query_results = specialty($con);


print_results($query_results);



mysql_close($con);
?>
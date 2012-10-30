<?php
include('procedures.php');

$con = getConnection();

//$query_results = test($con);
$query_results = procedureTest($con);

mysql_close($con);

print_r(mysql_fetch_assoc($query_results));
  
?>
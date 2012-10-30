<?php
include('procedures.php');

$id = $_POST['person_id'];

$con = getConnection();

$query_results = personDetail($id, $con);

mysql_close($con);

print_r(mysql_fetch_assoc($query_results));
  
?>
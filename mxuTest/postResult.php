<?php
include('procedures.php');

//$id = $_POST['specialty_id'];
$lat = $_POST['lat'];
$lon = $_POST['lon'];

$con = getConnection();

$query_results = locations_search($lat,$lon, $con);

mysql_close($con);

print_results($query_results);
  
?>
<?php
include('procedures.php');

$type = $_POST['type'];
$order = $_POST['order'];

echo $type;
echo $order;
echo "<br />";

$con = getConnection();

$query_results = get_remove_approved($type, $con, $order);

mysql_close($con);

print_results($query_results);
?>
<?php
include('procedures.php');
$con = getConnection();
$id = $_GET['id'];
$resource = person_detail($id, $con);
mysql_close($con);
while($row = mysql_fetch_assoc($resource))
{
	echo "<table>";
	foreach ($row as $key => $value)
	{
		echo "<tr><td>{$key}:</td><td>{$value}</td></tr>";
	}
	echo "</table>";
}
?>
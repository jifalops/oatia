<?php
include('procedures.php');
$con = getConnection();
$specialty = $_POST['specialty'];
$resource = specialty_person($specialty, $con);
mysql_close($con);
while($row = mysql_fetch_array($resource))
{
	echo "<a href='personDetail.php?id={$row[0]}'>".$row[1]." ".$row[2]."</a><br />";
}
?>
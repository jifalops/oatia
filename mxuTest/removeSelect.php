<?php
include('procedures.php');
$con = getConnection();
$resource = specialty($con);
mysql_close($con);
echo "<form method='POST' action='remove.php'><br />Select specialty to remove:<select name='specialty'>";
while($re = mysql_fetch_assoc($resource))
{
	$id = $re['specialty_id'];
	$name = $re['specialty'];
	echo "<option value='{$id}'>{$name}</option>";
}
echo "</select><br />";
echo "<input type='submit'/></form>";
?>
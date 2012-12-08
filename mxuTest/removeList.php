<?php
include('procedures.php');

$type = $_POST['type'];

switch($type)
	{
		case "AG": 
			echo "<h4>Organization</h4><br />";
			break;
		case "AL":
			echo "<h4>Location</h4><br />";
			break;
		case "LK": 
			echo "<h4>Link</h4><br />";
			break;
		case "MB": 
			echo "<h4>Member</h4><br />";
			break;
		case "PS": 
			echo "<h4>Person</h4><br />";
			break;
		case "SP": 
			echo "<h4>Specialty</h4><br />";
			break;
	}

$con = getConnection();

$resource = get_pending_remove($type, $con);

mysql_close($con);
echo "<form id='form' method='POST' action='removeApproved.php'><br />Select specialty:<select name='specialty'>";
while($re = mysql_fetch_assoc($resource)){
	$id = $re['specialty_id'];
	$name = $re['specialty'];
	echo "<option value='{$id}'>{$name}</option>";
}
echo "</select><br /><br />";
echo "<input type='submit' value='approve'/> <input type='button' value='cancel approve' onclick='gocancel()'/><script>function gocancel(){document.getElementById('form').action='removeCancel.php';document.getElementById('form').submit();}</script></form>";
?>
<?php
include('procedures.php');
$con = getConnection();
$new = $_GET['new'];
$edit = $_GET['edit'];
$remove = $_GET['remove'];
$cancel = $_GET['cancel'];
$resource = specialty($con);
mysql_close($con);
if($new != "")
	echo "<h3>ID of new specialty: ".$new.".</h3><br />";
if($edit != "")
	echo "<h3>ID of edited specialty: ".$edit.".</h3><br />";
if($remove != "")
	echo "<h3>ID of removed specialty: ".$remove.".</h3><br />";
if($cancel != "")
	echo "<h3>ID of canceled removing specialty: ".$cancel.".</h3><br />";

while($re = mysql_fetch_assoc($resource)){
	print_r($re);
	print "<br />";
}
?>
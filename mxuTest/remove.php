<?php
include('procedures.php');
$con = getConnection();
$specialty = $_POST['specialty'];
if(remove_specialty($con, $specialty))
	echo "Change successfully.<br />Click <a href='./removeApprove.html'>here</a> to approve the remove process.";
?>
<?php
include('procedures.php');
$specialty = $_POST['specialty'];
$con = getConnection();
if(cancel_remove_specialty($con, $specialty))
	echo "<p>Remove process canceled.</p><script>setTimeout(function(){window.location.replace('http://businesshours.net/oatia/mxuTest/specialties.php?cancel={$specialty}')},1000);</script>";
?>
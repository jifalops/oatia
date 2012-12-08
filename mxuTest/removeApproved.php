<?php
include('procedures.php');
$specialty = $_POST['specialty'];
$con = getConnection();
if(confirm_remove_specialty($con, $specialty))
	echo "<p>Remove process confirmed.</p><script>setTimeout(function(){window.location.replace('http://businesshours.net/oatia/mxuTest/specialties.php?remove={$specialty}')},1000);</script>";
?>
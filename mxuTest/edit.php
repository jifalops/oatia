<?php
include('procedures.php');
$specialty = $_POST['specialty'];
$new = $_POST['new'];
$con = getConnection();
if(edit_specialty($con, $specialty, $new))
	echo "<p>Edit successfully.</p><script>setTimeout(function(){window.location.replace('http://businesshours.net/oatia/mxuTest/specialties.php?edit={$specialty}')},1000);</script>";
else
	echo "<p>Fail to edit</p>";
?>
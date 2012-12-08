<?php
include('procedures.php');
$specialty = $_POST['specialty'];
$con = getConnection();
$new = create_specialty($con, $specialty);
mysql_close($con);
echo "<p>Create successfully.</p><script>setTimeout(function(){window.location.replace('http://businesshours.net/oatia/mxuTest/specialties.php?new={$new}')},1000);</script>";
?>

<?php
include('procedures.php');

$con = getConnection();

if($con)
{
	echo "<p>Successfully connected to MySQL version ".mysql_get_server_info($con)." on ".mysql_get_host_info($con).".</p>";
}
else
{
	echo "<p>Could not connect.</p>";
}

if(mysql_close($con))
{
	echo "<p>Connection closed.</p>";
}
else
{
	echo "<p>Failed to close the connection.</p>";
}
  
?>
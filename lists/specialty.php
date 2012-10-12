<?php
	require_once('../header.php');
	
	/*$group_id = (int) $_GET['group'];
	
	if ($group_id > 0) {
		$sql = 'SELECT `specialty_id`, `specialty`'
			.' FROM `specialty`'
			." WHERE `specialty_group_id`=$group_id;";
	}
	else*/ $sql = 'SELECT `specialty_id`, `specialty` FROM `specialty`;';

	$db->connect();

	

	$records = $db->get_records($sql);
	echo '<table border="1"><tr><th>Specialty</th></tr>'.NL;
	if (is_array($records)) {			
		foreach ($records as $record) {			
			echo "<tr><td><a 
				href='person.php?specialty={$record['specialty_id']}'>"
				."{$record['specialty']}</a></td></tr>".NL;			
		}
	}
	else $log->d('table "specialty" did not return an array');
	echo '</table>'.NL;

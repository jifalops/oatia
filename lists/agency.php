<?php
	require_once('../header.php');

	$db->connect();

	$sql = <<<QUERY
SELECT `agency_id`, `agency` FROM `agency`;
QUERY;

	$records = $db->get_records($sql);
	echo '<table border="1"><tr><th>Agency</th></tr>'.NL;
	if (is_array($records)) {			
		foreach ($records as $record) {			
			echo "<tr><td><a 
				href='person.php?agency={$record['agency_id']}'>"
				."{$record['agency']}</a></td></tr>".NL;			
		}
	}
	else $log->d('table "agency" is empty');
	echo '</table>'.NL;

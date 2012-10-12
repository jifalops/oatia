<?php
	require_once('../header.php');

	$member_id = (int) $_GET['member'];

	$db->connect();

	if ($member_id > 0) {
		$member_sql = 
			'SELECT `username`, `email`, `expire_date`, `disclaimer_accept`'
			.' FROM `member` WHERE `member_id`='.$member_id.';';

		$person_sql = 
			'SELECT `person_id`, `first_name`, `last_name`'
			.' FROM `person` WHERE `modified_by`='.$member_id.';';

		$member_records = $db->get_records($member_sql);
		$person_records = $db->get_records($person_sql);

		$member = $member_records[0];
	

		$people = '';
		foreach ($person_records as $pr) {
			$people .= "<a href='person.php?person={$pr['person_id']}'
				>{$pr['first_name']} {$pr['last_name']}</a><br />".NL;
		}

		echo "<table border='1'><tr><th>{$member['username']}</th></tr>".NL;		
		echo "<tr><td>
			{$member['email']}<br />
			Expires: {$member['expire_date']}<br />
			Disclaimer Accept: {$member['disclaimer_accept']}<br />
			<p>Modified:<br />$people</p>
			</td></tr></table>".NL;			
	}
	else {
		$sql = 'SELECT `member_id`, `username` FROM `member`;';

		$records = $db->get_records($sql);
		echo '<table border="1"><tr><th>Members</th></tr>'.NL;
		if (is_array($records)) {			
			foreach ($records as $record) {			
				echo "<tr><td><a 
					href='member.php?member={$record['member_id']}'>"
					."{$record['username']}</a></td></tr>".NL;			
			}
		}
		else $log->d('table "member" did not return an array');
		echo '</table>'.NL;
	}

<?php
	require_once('../header.php');

	echo '<p><a href="https://docs.google.com/document/d/1GdOlmwTEfJUk8Zs8sEzA7VZH7F30Z_g28JxpTba2nYw/edit"
		>Functional Spec Google Doc</a> (Deprecated in favor of Word Document)</p>';

	$files = gather_files('.');

	echo '<table border="1" cellpadding="5">'.NL
		.'<tr><th>Name</th><th>Size (KB)</th><th>Modified (Eastern)</th></tr>';
	foreach ($files as $f) {
		echo '<tr><td><a href="'.$f['name'].'">'.$f['name'].'</td><td>'
			.round($f['size']/1000, 1).'</td><td>'
			.date('Y-m-d H:i:s', $f['modified']+60*60*2)
			.' | '. time_ago($f['modified']).' ago</td></tr>'.NL;
	}
	echo '</table>'.NL;


	function gather_files($dir) {
        // File system objects
        $fsos = scandir($dir);
        foreach ($fsos as $fso) {
            $fso_full = $dir . DS . $fso;                
            if (is_file($fso_full) && $fso != basename(__FILE__)) {
		$modified = filemtime($fso_full);
                $files[] = array(
			'name' => $fso,
			'size' => filesize($fso_full),				
			'modified' => $modified
		);
            }
        }

		usort($files, 'compare_files');	
		return $files;
    }

	function compare_files($f1, $f2) {
		$m1 = $f1['modified'];
		$m2 = $f2['modified'];
		if ($m1 == $m2) return 0;
		return ($m1 < $m2) ? 1 : -1;
	}

    function time_ago($timestamp) {
        $since = time() - $timestamp;
	
		$week = 60*60*24*7;
		$day = 60*60*24;
		$hour = 60*60;
		$min = 60;
	
		$weeks = (int) ($since / $week);
		$since -= $weeks * $week;

		$days = (int) ($since / $day);
		$since -= $days * $day; 
	
		$hours = (int) ($since / $hour);
		$since -= $hours * $hour;
	
		$mins = (int) ($since / $min);
		$since -= $mins * $min;
	
		$secs = $since;
	
		if ($weeks > 0) $result .= $weeks . 'w ';
		if ($days > 0) $result .= $days . 'd ';
		if ($hours > 0) $result .= $hours . 'h ';
		if ($mins > 0) $result .= $mins . 'm ';
		if ($secs > 0) $result .= $secs . 's ';
	
		return $result;
    }
?>

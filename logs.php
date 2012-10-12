<?php
	$dont_show_navigation = true;
	require_once('header.php');

	define('ERROR_LOG', 'error_log');

	$level = $_GET['show'];
	if (empty($level)) $level = 'all';	

	$clear = $_GET['clear'];
	if ($clear == 'debug') {
		$file = $log->get_filename(); 
		if (file_exists($file)) {
			unset($log);
			unlink($file);
			header('Location: logs.php?show='.$level);
			die();
		}
	}
	elseif (!empty($clear) && substr($clear, 0 - strlen(ERROR_LOG)) == ERROR_LOG && file_exists($clear)) {		
		unlink($clear);
		header('Location: logs.php?show='.$level);
		die();
	}


	show_navigation();
	
	echo '<h3>Developer Log / Debug Log</h3>'.NL;
	echo '<a href="logs.php?clear=debug">clear</a><br />'.NL;	
	echo '<p><a href="logs.php?show=all">all</a>
		 | <a href="logs.php?show=error">error</a>
		 | <a href="logs.php?show=debug">debug</a>
		 | <a href="logs.php?show=info">info</a></p>'.NL;
	
	$records = $log->to_array();
	echo '<table border="1">'.NL;	
	$header = true;
	foreach ($records as $record) {
		if ($header) {
			echo '<tr>'.NL;
			foreach ($record as $field) {
				echo '<th>'.$field.'</th>'.NL;
			}
			echo '</tr>'.NL;
			$header = false;
		}
		else {
			if ($level != 'all' && $level != $record['level']) continue;
			echo '<tr>'.NL;
			foreach ($record as $field) {
				echo '<td>'.$field.'</td>'.NL;
			}
			echo '</tr>'.NL;
		}
	}
	echo '</table><br />'.NL;

	echo '<hr />';

	echo '<h3>PHP Error Logs:</h3>'.NL;
	$logs = get_error_logs_recursive();
	foreach ($logs as $l) {
		echo '<h4>'.$l.':</h4>'.NL;
		echo '<a href="logs.php?clear='.urlencode($l).'">clear</a><br />'.NL;	
		echo nl2br(file_get_contents($l));
	}

	function get_error_logs_recursive($dir=BASE_DIR) {
		// File system objects
        $fsos = scandir($dir);
        foreach ($fsos as $fso) {
			if ($fso == '.' || $fso == '..') continue;
            $fso_full = $dir.DS.$fso;    
            if (is_file($fso_full) && $fso == ERROR_LOG) {
                $logs[] = $fso_full;
            }
            // Recurse, skip hidden directories (ones that start with a dot)
			// and skip the private dir
            else if (is_dir($fso_full) 
					&& substr($fso, 0, 1) != '.'
					&& $fso_full != PRIVATE_DIR) {
                $subdir_errors = get_error_logs_recursive($fso_full);
				if (!empty($subdir_errors)) {
					foreach ($subdir_errors as $e) {
						if (!empty($e)) $logs[] = $e;
					}
				}
            }	
        }
		return $logs;
	}

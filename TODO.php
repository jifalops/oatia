<?php
	require_once('header.php');

	echo '<h4>Things that need to be done:</h4>'.NL;

	exec('grep -nr --binary-files=without-match TODO .', $lines);
	
	echo '<table border="1">'.NL;
	echo '<tr><th>File</th><th>Line #</th><th>Comment (first line)</th>'.NL;
	foreach ($lines as $line) {
		$parts = explode(':', $line, 3);
		$file = substr($parts[0], 2);
		$line_num = $parts[1];
		$comment = $parts[2];
		
		// skip this file and skip index.php (has link to this file)
		if ($file == basename(__FILE__)	|| $file == 'index.php') continue;

		echo '<tr><td>'.$file.'</td><td>'.$line_num.'</td><td>'.$comment.'</td></tr>'.NL;
	}
	echo '</table>'.NL;

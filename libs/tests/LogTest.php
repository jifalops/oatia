<?php
class LogTest {
	public static function run() {
		echo '<hr />Running LogTest<br />'.NL;
		$file = pathinfo(__FILE__, PATHINFO_DIRNAME).DS.'logtest.txt';
		echo 'Log file:'.$file.'<br />'.NL;

		if (file_exists($file)) {
			echo 'Removing old test file.<br />'.NL;			
			unlink($file);
		}
		$logtest = new Log($file);
		
		echo $logtest->d('debug msg') . ' bytes written.<br />'.NL;		
		echo $logtest->e('error msg') . ' bytes written.<br />'.NL;
		echo $logtest->i('info msg') . ' bytes written.<br />'.NL;

		$logtest->close();

		echo $logtest->d('forced write error') 
			. ' bytes written. Forces warning to be written to error_log.<br />'.NL;

		$logtest = new Log($file);

		echo $logtest->d('debug msg 2') . ' bytes written.<br />'.NL;		
		echo $logtest->e('error msg 2') . ' bytes written.<br />'.NL;
		echo $logtest->i('info msg 2') . ' bytes written.<br />'.NL;

		echo 'Log contents (view source to see better formatting):<br />'.NL;
		print_r($logtest->to_array());

		echo  '<br />'.NL.'Done running LogTest<br /><hr />'.NL;
	}
}

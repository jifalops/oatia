<?php
class LogTest {
    public static function run() {
        echo '<hr />Running LogTest<br /><br />'.NL;
        $file = dirname(__FILE__).DS.'log.txt';
        echo 'Log file: "'.$file.'"<br />'.NL;

        if (file_exists($file)) {
            echo 'Removing old test file...<br />'.NL;          
            unlink($file);
        }

        echo '<br />Creating instance...<br />'.NL;
        $logtest = new Log($file);
        
        echo '$logtest->d(\'debug msg\') => ' . $logtest->d('debug msg') . ' bytes written.<br />'.NL;      
        echo '$logtest->e(\'error msg\') => ' . $logtest->e('error msg') . ' bytes written.<br />'.NL;
        echo '$logtest->i(\'info msg\') => ' . $logtest->i('info msg') . ' bytes written.<br />'.NL;

        echo '<br />Creating new instance...<br />'.NL;
        $logtest = new Log($file);

        echo '$logtest->d(\'debug msg 2\') => ' . $logtest->d('debug msg 2') . ' bytes written.<br />'.NL;      
        echo '$logtest->e(\'error msg 2\') => ' . $logtest->e('error msg 2') . ' bytes written.<br />'.NL;
        echo '$logtest->i(\'info msg 2\') => ' . $logtest->i('info msg 2') . ' bytes written.<br />'.NL;

        echo '<br />Log contents:<br />'.NL;
        
        $records = $logtest->to_array();
        echo '<table border="1">'.NL;   
        $header = true;
        foreach ($records as $record) {
            if ($header) {
                echo '<tr>';
                foreach ($record as $field) {
                    echo '<th>'.$field.'</th>';
                }
                echo '</tr>'.NL;
                $header = false;
            }
            else {
                echo '<tr>';
                foreach ($record as $field) {
                    echo '<td>'.$field.'</td>';
                }
                echo '</tr>'.NL;
            }
        }

        echo '</table><br />'.NL.'Done running LogTest<br /><hr />'.NL;
    }
}

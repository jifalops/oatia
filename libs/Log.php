<?php
// TODO comment on how to use this
class Log {
    private $file;
    private $handle;
    private $verbosity;

    function __construct($file) {
        $this->verbosity = 3;
        if(!empty($file)) $this->open($file);
    }

    private function open($file) {
        $this->file = $file;
        $exists = file_exists($file);
        $this->handle = fopen($file, 'a');
        if (!$exists) {
            $this->log('LEVEL', 'MESSAGE', 'TAG', 'TIME');      
        }
        return $this->handle;
    }

    private function log($level, $msg, $tag='', $time='') {
        if (empty($tag)) $tag = $_SERVER['PHP_SELF'];
        if (empty($time)) $time = date('Y-m-d H:i:s O');
        $tag = $this->escape($tag);
        $msg = $this->escape($msg);
        return fwrite($this->handle, "$time<>$level<>$tag<>$msg".NL);
    }

    private function escape($text) {
        $text = str_replace('>', '|>', $text);
        return str_replace(NL, ' ', $text);
    }

    private function unescape($text) {
        return str_replace('|>', '>', $text);
        // newlines (\n) are not returned to their original form
    }
    
    public function e($msg) { 
        if ($this->verbosity >= 1)
            return $this->log('error', $msg); 
    }

    public function d($msg) { 
        if ($this->verbosity >= 2)
            return $this->log('debug', $msg); 
    }
    
    public function i($msg) { 
        if ($this->verbosity >= 3)
            return $this->log('info', $msg); 
    }

    public function get_verbosity() {
        return $this->verbosity;
    }

    public function set_verbosity($v) {
        if ($v == 3) $this->verbosity = 3;
        elseif ($v == 2) $this->verbosity = 2;
        elseif ($v == 1) $this->verbosity = 1;
        else $this->verbosity = 0;
    }

    public function get_filename() {
        return $this->file;
    }

    public function to_array() {
        // Read the log file into an array with each line as an element and
        // trim the newlines so they're not part of the array elements.
        $lines = file($this->file, FILE_IGNORE_NEWLINES | FILE_SKIP_EMPTY_LINES);

        $records = array();
        foreach ($lines as $line) {
            $fields = explode('<>', $line);
            $records[] = array(
                'time'  => $fields[0],
                'level' => $fields[1],
                'tag'   => $fields[2],
                'msg'   => $fields[3]
            );
        }
        return $records;
    }   

    private function close() {
        return fclose($this->handle);
    }   

    function __destruct() {
        $this->close();
    }
}

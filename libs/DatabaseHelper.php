<?php

require_once(dirname(__FILE__).'/Log.php');

class DatabaseHelper {
    private $mysqli;    
    private $log;
        
    function __construct($host, $username, $password, $database=null) {
        $this->mysqli = new mysqli($host, $username, $password, $database);           
        $this->log = new Log('log.txt'); 
        
        $err = $this->mysqli->connect_errorno;
        if ($err) {
            $this->log->e('mysqli connect error '.$err.': '. $this->mysqli->connect_error);
        }
    } 
    
    function get_mysqli() {
        return $this->mysqli;
    }
    
    /** 
     * Errors from last command
     * Uses the format given by $mysqli->error_list
     */
    function get_errors() { 
        $errno = $this->mysqli->errno; 
        if ($errno == 0) return; 
        return array(
            array (
                'errno' => $errno,
                'sqlstate' => $this->mysqli->sqlstate,
                'error' => $this->mysqli->error
            )
        );
    }
    
    function log_errors() {
        $errors = $this->get_errors();
        if (empty($errors)) return;
        foreach ($errors as $e) {
            $this->log->e('mysqli error '.$e['errno'].': '.$e['error']);
        }
    }
    
    function select_db($db) {        
        $x = $this->mysqli->select_db($db);   
        $this->log_errors();     
        return $x;
    }
    
    /** Helps protect against injection (when charset is defined) */
    function escape($user_string) {
        $x = $this->mysqli->real_escape_string($user_string);
        $this->log_errors();
        return $x;
    }


    function raw_query($sql) {
        if (empty($sql)) return;
        $resource = $this->mysqli->query($sql);
        $this->log_errors();
        return $resource;
    }
    
    /**
     * Fetch return a numerical array with
     * each entry being an associative array.     
     */
    function query($sql, $as_assoc=true) {
        $results = array();
        $resource = $this->raw_query($sql);
        if (empty($resource)) return $results;
        if ($as_assoc) {
            for ($i = 0; $i < $resource->num_rows; ++$i) {
                $results[] = $resource->fetch_assoc();
    		}
		}
		else {
	        for ($i = 0; $i < $resource->num_rows; ++$i) {
                $results[] = $resource->fetch_array();
    		}
		}
		return $results;         
    }        
    
    /** 
     * Returns a single value: 
     * The value in the first column of the first row.
     */
    function result($sql) {
		$results = $this->query($sql, false);		
		if (!empty($results) && !empty($results[0])) {
		    return $results[0][0];
		}
    }
    
    /** 
     * Returns a 1 dimensional array: 
     * The first row of the results.
     */
    function result_array($sql, $as_assoc=true) {
		$results = $this->query($sql, $as_assoc);		
		if (count($results) > 0) return $results[0];
		else return array();
    }
    
    
    function get_tables() {        
        $tables = array();
		$results = $this->query('SHOW TABLES;', false);
		foreach ($results as $r) {
		    $tables[] = $r[0];
		}		
		return $tables;
	}
    
	// Includes meta data on columns
    function get_columns($table) {
        return $this->query("SHOW COLUMNS FROM `$table`");
    }
    
	function get_column_names($table) {   
	    $columns = array();     
        $results = $this->get_columns($table);             
		foreach ($results as $r) {
		    $columns[] = $r['Field'];
		}		
		return $columns;       
    }
    
    /** Return numerical array of associative arrays */
    function get_warnings() {
        $warnings = array();
        $w = $this->mysqli->get_warnings();
	    if (empty($w)) return $warnings;	
	    $i = 0;    
	    do { 
            $warnings[$i]['errno'] = $w->errno;
            $warnings[$i]['message'] = $w->message;
            ++$i;            
        } 
        while ($w->next());
        
        return $warnings;
    }
}
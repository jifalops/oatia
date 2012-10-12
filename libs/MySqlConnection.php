<?php
class MySqlConnection {
    private $host, $username, $password, $database, $link;
    
    public function __construct($host, $username, $password, $database) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;        
    }

	public function get_host() {
		return $this->host;
	}

	public function get_username() {
		return $this->username;
	}

	public function get_database() {
		return $this->database;
	}

	public function get_link() {
        return $this->link;
    }
	
    
    public function connect() {    
        $this->link = mysql_connect(
			$this->host, $this->username, $this->password
		);  
		mysql_select_db($this->database, $this->link);      
    }

	public function set_database($db) {
		$this->database = $db;
		mysql_select_db($db, $this->link);
	}
    

    public function query($sql) {
        return mysql_query($this->escape($sql), $this->link);
    }

	public function get_tables() {
		$result = $this->query('SHOW TABLES;');
		if ($result) $rows = mysql_num_rows($result);
        for ($i = 0; $i < $rows; ++$i) {
			$table = mysql_fetch_row($result);
			$tables[] = $table[0];
		}
		return $tables;
	}
    
	// Includes meta data on columns
    public function get_columns($table) {
        return $this->query("SHOW COLUMNS FROM `$table`");
    }
    
	public function get_column_names($table) {
        $result = $this->get_columns($table);     
        if ($result) $rows = mysql_num_rows($result);
        for ($i = 0; $i < $rows; ++$i) {
            $column_info = mysql_fetch_assoc($result);
            if (is_array($column_info)) {
                $fields[] = $column_info['Field'];               
            }
        }
        return $fields;
    }
    
	// Returns the contents of the first cell
    public function result($sql, $unique=false) {
        $result = $this->query($sql);
        if (!empty($result)) {
            $rows = mysql_num_rows($result);          
            if ($rows > 0) {
                if ($unique && $rows > 1) {
                    throw new Exception(UNIQUE_ERROR);
                }
                return mysql_result($result, 0);
            }            
        }
    }
    
   	// Returns an array of arrays.
    public function get_records($sql, $unique=false) {
        $result = $this->query($sql);
        if (!empty($result)) {
            $rows = mysql_num_rows($result);
            if ($rows > 0) {
                if ($unique && $rows > 1) {
                    throw new Exception(UNIQUE_ERROR);
                }
				for ($i = 0; $i < $rows; $i++) {
                	$records[] = mysql_fetch_assoc($result);
				}
				return $records;
            }            
        }       
    }
    
    /**
     * Needs escaped
	 * Note: This probably causes more overhead than it saves
	 * 	plus the added possibility of bugs.
     *
    public function needs_escaped($var) {
        if ($var === true) return true;
        if (is_array($var)) {
            array_map(__METHOD__, $var);
        }
        else {
            $chars = '\\x00\\x0D\\x0A\\x1A\'\\\\"';
            $has_chars = "/[{$chars}]/";
            $chars_escaped = "/^[^{$chars}]*((\\\\[{$chars}])+[^{$chars}]*)+$/";
            if (preg_match($has_chars, $var) > 0 &&
                    preg_match($chars_escaped, $var) <= 0) {                
                $var = true;            
            }
            else {
                $var = false;
            }
        }
        return $var; 
    }*/
    

    public function escape($var) {
        if (is_array($var)) {
            array_map(__METHOD__, $var);
        }
        else {
            $var = mysql_real_escape_string($var, $this->link);
        }
        return $var; 
    }    
    
    public function unescape($var) {
        if (is_array($var)) {
            array_map(__METHOD__, $var);
        }
        else {
            $pattern = '/\\\([\\x00\\x0D\\x0A\\x1A\'\\\"])/';
            $var = preg_replace($pattern, '$1', $var);        
        }
        return $var; 
    }
    
    public function __sleep()
    {
        mysql_close($this->link);
        unset($this->link);
        return array('host', 'username', 'password', 'database');
    }
    
    public function __wakeup()
    {
        $this->connect();
    }
}

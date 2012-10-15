<?php
/* author: Shazad Bakhsh
 * get user and other stuff from the database
 * The grease monkey of database :)
 */
// TODO Add All Server Interactions --SSB

class DBMonkey {
 
    private $db;
 
    //put your code here
    // constructor
    function __construct() {
        require_once 'DBConnet.php';
        // connecting to database
        $this->db = new DBConnect();
        $this->db->connect();
    }
 
    // destructor
    function __destruct() {
      //todo
    }
    
    /**
     * Get user by name and password
     */
    public function getUserByNameAndPassword($name, $password) {
        $result = mysql_query("SELECT * FROM member WHERE username = '$name'") or die(mysql_error());
        // check for result
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysql_fetch_array($result);
            //$salt = $result['salt'];
            //$encrypted_password = $result['encrypted_password'];
            //$hash = $this->checkhashSHA($salt, $password);
            // check for password equality
            if (sha1($password) == $result['password']) {
                // user authentication details are correct
                return $result;
            }
        } else {
            // user not found
            return false;
        }
    }
	
	
	 /**
     * Get all Specialties in the DB
     */
	 public function getSpecialties() {
	 
		$temp = '';
		$result = mysql_query("SELECT * FROM specialty");
		        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            while($specialties = mysql_fetch_assoc($result)){
				$temp[] = $specialties;
			}
			return $temp;
        } else {
            return false;
        }
	}
		
	 /**
     * Get all Agency in the DB
     */
	 public function getAgency() {
	 
		$temp = '';
		$result = mysql_query("SELECT * FROM agency");
		        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            while($agency = mysql_fetch_assoc($result)){
				$temp[] = $agency;
			}
			return $temp;
        } else {
            return false;
        }
	}
	
	/**
     * Get all links in the DB
     */
	 public function getLinks() {
	 
		$temp = '';
		$result = mysql_query("SELECT * FROM link");
		        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            while($link = mysql_fetch_assoc($result)){
				$temp[] = $link;
			}
			return $temp;
        } else {
            return false;
        }
	}
 
	/**
     * Get People by Specialty and password
     */
	 public function getPeoplebySpecialty($specialty_id) {
	 
		$temp = '';
		$result = mysql_query("SELECT * FROM person_specialty WHERE specialty_id= '$specialty_id'");
		        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            while($row = mysql_fetch_array($result))
			{
				$user = mysql_query("SELECT * FROM person WHERE person_id='$row[person_id]'");
				//$user  = mysql_fetch_array($user);
				//$temp = $temp.",".$user['person_id'].":".$user['first_name']."-".$user['last_name'];
				$temp[] = mysql_fetch_assoc($user);
				
			}
			//$temp = substr($temp, 1); 
			return $temp;
        } else {
            return false;
        }
	} 
	
    /**
     * Encrypting password
     */
    public function hashSSHA($password) {
 
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }
 
    /**
     * Decrypting password
     */
    public function checkhashSHA($salt, $password) {
 
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
 
        return $hash;
    }
}

?>
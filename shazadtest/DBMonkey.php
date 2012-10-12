<?php
/* author: Shazad Bakhsh
 * get user and other stuff from the database
 * The grease monkey of database :)
 */
// todo

class DBMonkey {
 
    private $db;
 
    //put your code here
    // constructor
    function __construct() {
        require_once 'DBConnect.php';
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
        $result = mysql_query("SELECT * FROM users WHERE name = '$name'") or die(mysql_error());
        // check for result
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysql_fetch_array($result);
            $salt = $result['salt'];
            $encrypted_password = $result['encrypted_password'];
            $hash = $this->checkhashSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $result;
            }
        } else {
            // user not found
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
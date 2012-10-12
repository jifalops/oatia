<?php

/* author: Shazad Bakhsh
 *  connect to the database
 */

class DBConnect {

    // constructor
    function __construct() {
		// todo
    }

    // destructor
    function __destruct() {
        // todo
    }

    // Connecting to database
    public function connect() {
        require_once 'DBConfig.php';
        // connecting to mysql
        $connect = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
        // selecting database
        mysql_select_db(DB_DATABASE);

        // return database handler
        return $connect;
    }

    // Closing database connection
    public function close() {
        mysql_close();
    }

}

?>
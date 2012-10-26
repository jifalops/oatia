<?php
    /* 
     * header.php
     * The purpose of this file is to be included at the beginning
     * of every other PHP file (except classes, which go in the library
     * directory). That will ensure that pages within the site work
     * consistantly. To include it, the preferred method would be:
     *
     *  require_once('header.php');
     * 
     *  -or-
     *
     *  require_once('../header.php');
     *
     * The second example above is how to include this file from a file
     * in a sub-directory. ".." represents the parent directory and can
     * be used repeatedly (i.e. '../../../header.php').
     */ 

    // Directory separator
    define('DS',            '/');

    // Shorthand for php's built-in cross-platform newline handling
    define('NL',            PHP_EOL);

    // No trailing slash on directories!
    // These are the complete file paths (i.e. "/home/username/public_html/oatia/filename.ext")
    define('BASE_DIR',      dirname(__FILE__)); 
    define('LIBRARY_DIR',   BASE_DIR.DS.'libs');
    define('TEST_DIR',      LIBRARY_DIR.DS.'tests');
    define('PRIVATE_DIR',   BASE_DIR.DS.'.private');
    // Shortened file path usable with HTTP anchors (i.e. "/oatia/filename.ext")
    define('BASE_DIR_HTTP', DS.basename(BASE_DIR)); // "/oatia"

    // Include our private data (not part of the public code)
    require_once(PRIVATE_DIR.DS.'private.php');
    
    /** 
    * This is called when you try to create a new instance of an object
    * and PHP doesn't know what it is. For this to work as intended,
    * all classes should be in the library directory. Also, if the class
    * is a test, it should be in the tests directory and should have
    * "Test" in its name.
    */
    function __autoload($class_name) {
        
        // Test classes should have "Test" in the name.
        if (strpos($class_name, 'Test') === false) {            
            include_once(LIBRARY_DIR.DS. $class_name.'.php');
        }
        else include_once(TEST_DIR.DS. $class_name.'.php');
    }
   
    // Pages that use the DB have to call $db->connect() before using other methods. 
    // TODO this should be a mysqli object.     
    $db = new MySqlConnection(Secure::DB_HOST,      Secure::DB_USERNAME, 
                              Secure::DB_PASSWORD,  Secure::DB_DATABASE);
    
    // Logging mechanism for developers. Similar to Android's logging mechanism.
    $log = new Log(BASE_DIR.DS.'log.txt');

    // If you want to wait before showing navigation, set
    // $dont_show_navigation = true; before including this
    // file. This would be required if you send HTTP headers
    // in your file (via the header() function).
    if (!$dont_show_navigation) show_navigation();

    /**
     * Shows the top navigation links on the page.
     */
    function show_navigation() {
        echo '<a href="/oatia/index.php">Home</a>
            | <a href="/oatia/documents/">Documents</a><br />
            
            Example App Lists (old)
            | <a href="/oatia/lists/agency.php">Agencies</a>
            | <a href="/oatia/lists/member.php">Members</a>
            | <a href="/oatia/lists/person.php">People</a>
            | <a href="/oatia/lists/specialty.php">Specialties</a><br />
            
            <hr />'.NL;
    }


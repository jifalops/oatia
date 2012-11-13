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
    require_once(PRIVATE_DIR.DS.'Secure.php');
    
    /** 
    * This is called when you try to create a new instance of an object
    * and PHP doesn't know what it is. For this to work as intended,
    * all classes should be in the library directory. Also, if the class
    * is a test, it should be in the tests directory and should have
    * "Test" in its name.
    */
    function __autoload($class_name) {
        $matches = array();
        find_files($matches, LIBRARY_DIR, $class_name, 'php', 1);        
        foreach ($matches as $m) {
            require_once($m);
        }        
    }
    
    /** 
     * $matches - an array containing the full file paths of all matches.
     * $directory - The full path of the directory to search.
     * Neither $filename or $extension include the dot between them. 
     * Omitted $filename or $extension matches everything.
     * Omitted $limit (or a limit of 0) means unlimited.
     */
    function find_files(&$matches, $directory, $filename=null, $extension=null, $limit=null,
            $case_sensitive=true, $subdirs=true) {
        // File system objects
        $fsos = scandir($directory);
        foreach ($fsos as $fso) {
            $fso_full = $directory . DS . $fso;    
            $parts = pathinfo($fso_full);            
            if (is_file($fso_full)
                    && (empty($filename)
                        || ($case_sensitive && $filename == $parts['filename'])
                        || (!$case_sensitive && strcasecmp($filename, $parts['filename'])))
                    && (empty($extension)
                        || ($case_sensitive && $extension == $parts['extension'])
                        || (!$case_sensitive && strcasecmp($extension, $parts['extension'])))) {                  
                $matches[] = $fso_full;
                if ($limit > 0 && count($matches) >= $limit) return;
            }            
            else if (is_dir($fso_full) && $subdirs && $fso != '.' && $fso != '..') {
                find_files($matches, $fso_full, $filename, $extension, $limit, $case_sensitive, $subdirs);
            }
        }
    }
                              
    $db = new DatabaseHelper(   Secure::DB_HOST,      Secure::DB_USERNAME, 
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
            <hr />'.NL;
    }


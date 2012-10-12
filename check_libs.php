<?php
    /**
     * check_libs.php
     *
     * This file's purpose is only to load all of the library code so that 
     * it can be checked for syntax errors.
     */

    require_once('header.php');
    
    load_php_files_recursive(LIBRARY_DIR);
    
    function load_php_files_recursive($dir) {
        // File system objects
        $fsos = scandir($dir);
        foreach ($fsos as $fso) {
            $fso_full = $dir . DS . $fso;    
            // Require including the file, if it has a .php extension
            if (is_file($fso_full) && substr($fso, -4, 4) == '.php') {
                require_once($fso_full);
            }
            // Recurse, skip hidden directories (ones that start with a dot)
            else if (is_dir($fso_full) && substr($fso, 0, 1) != '.') {
                load_php_files_recursive($fso_full);
            }
        }
    }
?>

If this page loads without errors, there aren't any syntax errors in
the library files (including tests). That doesn't mean there aren't
other errors.

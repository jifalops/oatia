<?php
    require_once('header.php');
    $matches = array();
    find_files($matches, LIBRARY_DIR, null, 'php');
    foreach ($matches as $m) require_once($m);
    echo 'Loaded '. count($matches) .' class files.<br />'.NL;
?>
<p />
If you see this (and no errors), there aren't any syntax errors in
the library files. That doesn't mean there aren't other errors.

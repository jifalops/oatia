<?php
/* author: Shazad Bakhsh
 *  constant variables to connect to database
 */

/*
 * Note from Jacob:
 * updating so that passwords are hidden.
 */
$dont_show_navigation=true;
require_once('../header.php');

define("DB_HOST", Secure::DB_HOST);
define("DB_USER", Secure::DB_USERNAME);
define("DB_PASSWORD", Secure::DB_PASSWORD);
define("DB_DATABASE", Secure::DB_DATABASE);
?>

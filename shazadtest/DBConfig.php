<?php
/* author: Shazad Bakhsh
 *  constant variables to connect to database
 */

/*
 * Note from Jacob:
 * updating so that passwords are hidden.
 */
require_once('../header.php');

define("DB_HOST", $PRIVATE['DB_SERVER']);
define("DB_USER", $PRIVATE['DB_USERNAME']);
define("DB_PASSWORD", $PRIVATE['DB_PASSWORD']);
define("DB_DATABASE", $PRIVATE['DB_DATABASE']);
?>

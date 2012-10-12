<?php
/* author: Jizhou Tong
 *  This is the fuzzy search for the person table.
 */

/*
 * Note from Jacob:
 * I'm going to use source control for the site. As a result,
 * having passwords as plain text is bad. The source control
 * ignores everything in the 'oatia/.private/' directory. In
 * there there is a PHP file containing an array that should
 * be able to handle any private data. As of now, it contains
 * the server credentials. I changed the first line of your
 * code so that it uses that information.
 */
require_once('../header.php');

$mysqli = new mysqli($PRIVATE['DB_SERVER'],		$PRIVATE['DB_USERNAME'], 
					 $PRIVATE['DB_PASSWORD'],   $PRIVATE['DB_DATABASE']);
$last_name="";
$res = $mysqli->multi_query( "CALL fuzzy($last_name,@x);SELECT @x" );
if( $res ) {
  $results = "";
  do {
    if ($result = $mysqli->store_result()) {
      printf( "<b>Result #%u</b>:<br/>", ++$results );
      while( $row = $result->fetch_row() ) {
        foreach( $row as $cell ) echo $cell, "&nbsp;";
      }
      $result->close();
      if( $mysqli->more_results() ) echo "<br/>";
    }
  } while( $mysqli->next_result() );
}
$mysqli->close();
?>

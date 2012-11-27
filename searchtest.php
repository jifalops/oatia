<?php
require_once('header.php');

$queries = new DatabaseInterface($db);
$results = $queries->search_all($_GET['q']);

echo count($results) . " results<br />";

echo nl2br(print_r($results, true));
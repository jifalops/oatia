<?php
  require_once('header.php');
  
  echo '<h4>This page shows a raw dump of all of the tables in the database</h4>';

  /*echo '<a href="tables.php?table=all">all</a>
    | <a href="tables.php?table=agency">agency</a>
    | <a href="tables.php?table=member">member</a>
    | <a href="tables.php?table=person">person</a>
    | <a href="tables.php?table=person_agency">person_agency</a>
    | <a href="tables.php?table=person_specialty">person_specialty</a>
    | <a href="tables.php?table=specialty">specialty</a>
    | <a href="tables.php?table=specialty_group">specialty_group</a>';*/
  
  

  $table = $_GET['table'];

  if (empty($table)) $table = 'all';


  $db->connect();

  if ($table == 'all') {
    $tables = $db->get_tables();
    foreach ($tables as $table) {
      make_table($table);
    }
  }
  else {
    $log->i("showing '$table' table");
    make_table($table);
  }

  function make_table($table) {    

    $fields = $GLOBALS['db']->get_column_names($table);
    if (empty($fields)) {
      $GLOBALS['log']->e("table '$table' has no fields or doesn't exist");
    }

    $sql = <<<QUERY
SELECT * FROM `{$table}`;
QUERY;

    $records = $GLOBALS['db']->get_records($sql);

    echo '<h3>'.$table.'</h3>'.NL;
    echo '<table border="1"><tr>'.NL;
    foreach ($fields as $field) {
      echo '<th>'.$field.'</th>'.NL;
    }
    echo '</tr>'.NL;
    if (is_array($records)) {      
      foreach ($records as $record) {
        echo '<tr>'.NL;
        foreach ($record as $item) {
          echo '<td>'.$item.'</td>'.NL;
        }
        echo '</tr>'.NL;
      }
    }
    else $GLOBALS['log']->d("table '$table' is empty");
    echo '</table>'.NL;
  }

  

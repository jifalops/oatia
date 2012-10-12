<?php
  require_once('../header.php');
  
  echo '<h4><u>This table will be deleted and we will use a flat specialty structure</u></h4>';

  $db->connect();

  $sql = 'SELECT `specialty_group_id`, `specialty_group` FROM `specialty_group`;';

  $records = $db->get_records($sql);
  echo '<table border="1"><tr><th>Specialty Groups</th></tr>'.NL;
  if (is_array($records)) {      
    foreach ($records as $record) {      
      echo "<tr><td><a 
        href='specialty.php?group={$record['specialty_group_id']}'>"
        ."{$record['specialty_group']}</a></td></tr>".NL;      
    }
  }
  else $log->d('table "specialty_group" did not return an array');
  echo '</table>'.NL;

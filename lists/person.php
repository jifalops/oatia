<?php
  require_once('../header.php');
  
  $db->connect();

  // Does an int cast protect from injection attempts?
  $person_id = (int) $_GET['person'];  
  $specialty_id = (int) $_GET['specialty'];  
  $agency_id = (int) $_GET['agency'];
  

  if ($person_id > 0) {
    $person_sql =
      'SELECT `p`.`first_name`, `p`.`last_name`, `p`.`address`,'
      .' `p`.`city`, `p`.`state`, `p`.`zip`, `p`.`email`, `p`.`phone_1`,' 
      .' `p`.`phone_2`, `p`.`notes`'
      .' FROM `person` `p`'
      ." WHERE `p`.`person_id`=$person_id;";

    $specialty_sql = 
      'SELECT `s`.`specialty_id`, `s`.`specialty`'
      .' FROM `specialty` `s`, `person_specialty` `ps`'
      .' WHERE `ps`.`specialty_id`=`s`.`specialty_id`'
      ." AND `ps`.`person_id`=$person_id;";

    $agency_sql = 
      'SELECT `a`.`agency_id`, `a`.`agency`'
      .' FROM `agency` `a`, `person_agency` `pa`'
      .' WHERE `pa`.`agency_id`=`a`.`agency_id`'
      ." AND `pa`.`person_id`=$person_id;";

    $person_records = $db->get_records($person_sql);
    $specialty_records = $db->get_records($specialty_sql);
    $agency_records = $db->get_records($agency_sql);

    $person = $person_records[0];

    $specialties = '';
    foreach ($specialty_records as $sr) {
      $specialties .= "<a href='person.php?specialty={$sr['specialty_id']}'
        >{$sr['specialty']}</a><br />".NL;
    }

    $agencies = '';
    foreach ($agency_records as $ar) {
      $agencies .= "<a href='person.php?agency={$ar['agency_id']}'
        >{$ar['agency']}</a><br />".NL;
    }

    echo "<table border='1'><tr><th>{$person['first_name']} 
      {$person['last_name']}</th></tr>".NL;
    echo "<tr><td>
      <p>Specialties:<br />$specialties</p>
      <p>Agencies:<br />$agencies</p>
      <p>Contact:<br />
      {$person['address']}<br />
      {$person['city']}, {$person['state']} {$person['zip']}<br />
      {$person['phone_1']}<br />
      {$person['phone_2']}<br />
      {$person['email']}<br /></p>
      <p>Notes<br />{$person['notes']}</p>
      </td></tr></table>".NL;
  }
  else {
    if ($specialty_id > 0) {
      $sql = 
        'SELECT `p`.`person_id`, `p`.`first_name`, `p`.`last_name`'
        .' FROM `person` `p`, `person_specialty` `ps`'
        ." WHERE `ps`.`specialty_id`=$specialty_id"
        .' AND `ps`.`person_id`=`p`.`person_id`;';
    }
    elseif ($agency_id > 0) {
      $sql = 
        'SELECT `p`.`person_id`, `p`.`first_name`, `p`.`last_name`'
        .' FROM `person` `p`, `person_agency` `pa`'
        ." WHERE `pa`.`agency_id`=$agency_id"
        .' AND `pa`.`person_id`=`p`.`person_id`;';
    }
    else {
      $sql = 'SELECT `person_id`, `first_name`, `last_name` FROM `person`;';
    }

    $records = $db->get_records($sql);

    echo '<table border="1"><tr><th>Person</th></tr>'.NL;
    if (is_array($records)) {      
      foreach ($records as $record) {      
        echo "<tr><td><a 
          href='person.php?person={$record['person_id']}'>"
          ."{$record['first_name']}
          {$record['last_name']}</a></td></tr>".NL;      
      }
    }
    else $log->d('table "person" did not return an array');
    echo '</table>'.NL;
  }

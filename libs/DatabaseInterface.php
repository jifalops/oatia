<?php
require_once('DatabaseHelper.php');
require_once('DatabaseContract/TableAuthKeys.php');
require_once('DatabaseContract/TableFlag.php');
require_once('DatabaseContract/TableLink.php');
require_once('DatabaseContract/TableLocation.php');
require_once('DatabaseContract/TableMember.php');
require_once('DatabaseContract/TableOrganization.php');
require_once('DatabaseContract/TablePerson.php');
require_once('DatabaseContract/TablePersonOrganization.php');
require_once('DatabaseContract/TablePersonSpecialty.php');
require_once('DatabaseContract/TableRole.php');
require_once('DatabaseContract/TableSpecialty.php');

class DatabaseInterface {
    const LIMIT = 1000; // Using a real limit won't be ready by monday
    
    private $db;
    
    function __construct(DatabaseHelper $db) {
        $this->db = $db;
    }
    
    // TODO make sure all user strings are passed through this.
    private function clean_user_input($string) {
        // all whitespace is reduced to a single space.
        $string = preg_replace('/\s+/', ' ', $string);
        return $this->db->escape($string);
    }
    
    function check_in($id, $lat, $lon) {
        $sql = 'update member set latitude='.$lat.', longitude='.$lon.', location_time=now() where member_id='.$id.';';
        return $this->db->query($sql);
    }

    //2Get Specialties
    function specialty($start = 0)
    {
    	$sql = "select specialty.specialty_id , specialty.specialty from specialty";
    	$sql = $sql." limit {$start},".self::LIMIT;    	 	
    	return $this->db->query($sql);
    }
    
    //4Get Organizations 
    function organization($start = 0)
    {    
    	$sql = "select organization.organization_id , organization.organization from organization";
    	$sql = $sql." limit {$start},".self::LIMIT;
    	return $this->db->query($sql);
    }
    
    //5Get Links
    function links($start = 0)
    {    	
    	$sql = "select link.link_id , link.link , link.title from link";
    	$sql = $sql." limit {$start},".self::LIMIT;
    	return $this->db->query($sql);
    }
    
    //6Get Pending Removals
    // TODO this should use the "flag" table
    function get_pending_remove($table, $start = 0)
    {
    	$sql = "select ";
    	switch($table)
    	{
    		case "AG": 
    			$sql = $sql."organization.organization_id , organization.organization from organization where remove = 1 order by organization";
    			break;
    		case "AL":
    			$sql = $sql."organization_location.location_id , organization_location.address , organization_location.modified_time from organization_location where remove = 1 order by modified_time";
    			break;
    		case "LK": 
    			$sql = $sql."link.link , link.title from link where remove = 1 order by title";
    			break;
    		case "MB": 
    			$sql = $sql."member.username from member where remove = 1 order by expire_date";
    			break;
    		case "PS": 
    			$sql = $sql."person.first_name , person.last_name from person where remove = 1 order by modified_time";
    			break;
    		case "SP": 
    			$sql = $sql."specialty.specialty from specialty where remove = 1 order by specialty";
    			break;
    	}
    	$sql = $sql." limit {$start},".self::LIMIT;
    	return $this->db->query($sql);
    }
    
    //7Get Roles
    function role($start = 0)
    {    	
    	$sql = "select role.role_id , role.role from role";
    	$sql = $sql." limit {$start},".self::LIMIT;
    	return $this->db->query($sql);
    }
    
    //8Get People by Specialty
    function specialty_person($specialty_id, $start = 0)
    {    	
    	$sql = "select person.person_id, person.city, person.state, person.zip, person.first_name , person.last_name from person where person_id in (select person_specialty.person_id from person_specialty where specialty_id ={$specialty_id})";
    	$sql = $sql." limit {$start},".self::LIMIT;
    	return $this->db->query($sql);
    }

    //9Get Organization Locations
    function organization_locations($organization_id, $start = 0)
    {    	
    	$sql = "select organization_location.location_id , organization_location.is_primary, organization_location.city, organization_location.state, organization_location.zip,  organization_location.address from organization_location where organization_id = {$organization_id}";
    	$sql = $sql." limit {$start},".self::LIMIT;
    	return $this->db->query($sql);
    }
    
    //10Get Person Detail
    function personDetail($id)
    {    	 
    	$sql = "select p.person_id, m.location_time, m.latitude, m.longitude, p.first_name, p.last_name, p.address, p.city, p.state, p.zip, p.email, p.phone1, p.phone2, p.notes from person as p, member as m where p.person_id={$id} and m.person_id=p.person_id";
    	$person = $this->db->query($sql);
    	$sql = 'select s.specialty_id, s.specialty from person_specialty as ps, specialty as s where ps.person_id='.$id.' and s.specialty_id=ps.specialty_id;';
    	$specialties = $this->db->query($sql);
    	$sql = 'select po.organization_id, o.organization from person_organization as po, organization as o where po.person_id='.$id.' and o.organization_id=po.organization_id;';
    	$organizations = $this->db->query($sql);
    	$s = '';
    	$len = count($specialties);
    	for ($i = 0; $i < $len; ++$i) {
    	    if ($i == 0) $s = $specialties[$i]['specialty'];
    	    else $s .= '<>]&' . $specialties[$i]['specialty'];  // Don't change the delimiter!
    	}
    	$person[0]['specialties'] = $s;

    	$o = '';
    	$len = count($organizations);
    	for ($i = 0; $i < $len; ++$i) {
    	    if ($i == 0) $o = $organizations[$i]['organization'];
    	    else $o .= '<>]&' . $organizations[$i]['organization'];  // Don't change the delimiter!
    	}
    	$person[0]['organizations'] = $o;
    	
    	return $person;
    }
    
    //11Get (Organization) Location Detail
    function location($location_id)
    {    	
    	$sql = "select l.location_id, l.is_primary, l.address, l.city, l.state, l.zip, l.latitude, l.longitude, l.email1, l.email2, l.phone1, l.phone2, o.organization, o.organization_id from organization_location as l, organization as o where location_id = {$location_id} and o.organization_id=l.organization_id";
    	return $this->db->query($sql);
    }
    
    //12Get Approved Removals
    // TODO this should use the "flag" table
    function get_remove_approved($table, $order = "FF", $start = 0)
    {
    	$sql = "select ";
    	switch($table)
    	{
    		case "AG": 
    			$sql = $sql."organization.organization_id , organization.organization from organization where remove_approved = 1 order by organization";
    			break;
    		case "AL":
    			$sql = $sql."organization_location.location_id , organization_location.address , organization_location.modified_time from organization_location where remove_approved = 1 order by modified_time";
    			break;
    		case "LK": 
    			$sql = $sql."link.link , link.title from link where remove_approved = 1 order by title";
    			break;
    		case "MB": 
    			$sql = $sql."member.username from member where remove_approved = 1 order by expire_date";
    			break;
    		case "PS": 
    			$sql = $sql."person.first_name , person.last_name from person where remove_approved = 1 order by modified_time";
    			break;
    		case "SP": 
    			$sql = $sql."specialty.specialty from specialty where remove_approved = 1 order by specialty";
    			break;
    	}
    	
    	if($order == "LF")
    		$sql = $sql." DESC";
    		
    	
    	$sql = $sql." limit {$start},".self::LIMIT;
    	return $this->db->query($sql);
    }
    
    //15Create Person
    function create_person($first_name , $last_name , $address , $city , $state , $zip , $phone1 , $latitude = "", $longitude = "", $email = "", $phone2 = "", $note = "")
    {
    	$sql = "insert into person set first_name ='{$first_name}', last_name ='{$last_name}', address ='{$address}', city ='{$city}', state ='{$state}', zip ='{$zip}', phone1='{$phone1}', modified_time = now()";
    	if($latitude != "" && $longitude != "")
    		$sql = $sql.", latitude = {$latitude}, longitude = {$longitude}";
    	if($email != "")
    		$sql = $sql.", email = '{$email}'";
    	if($phone2 != "")
    		$sql = $sql.", phone2 = '{$phone2}'";
    	if($note != "")
    		$sql = $sql.", notes = '{$note}'";
    	
    	// insert
    	$this->db->query($sql);    	
    	
    	// return new id
    	// TODO this could return a different id than expected (two created almost simultaneously)
    	$sql = "select person_id from person order by person_id DESC limit 1";
    	return $this->db->query($sql);
    }
    
    //16 - Create Specialty
    function create_specialty($specialty)
    {
    	$sql = "insert into specialty set specialty ='{$specialty}'";
    	$this->db->query($sql);
    	
    	// TODO this could return a different id than expected (two created almost simultaneously)
    	$sql = "select specialty_id from specialty order by specialty_id DESC limit 1";
        return $this->db->query($sql);    	
    }
    
    //17 - Create Organization
    function create_organization($organization)
    {
    	$sql = "insert into organization set organization ='{$organization}'";
    	$this->db->query($sql);
    	
    	// TODO this could return a different id than expected (two created almost simultaneously)
    	$sql = "select organization_id from organization order by organization_id DESC limit 1";
        return $this->db->query($sql);
    }
    
    //18 - Create Link
    function create_link($role_id , $link , $title , $top_level = 0, $organization_id = "", $location_id = "", $member_id = "", $person_id = "", $specialty_id = "")
    {
    
    	$sql = "insert into link set role_id ={$role_id}, link ='{$link}', title ='{$title}', top_level ={$top_level}";
    	if($organization_id != "")
    		$sql = $sql.", organization_id = {$organization_id}";
    	if($location_id != "")
    		$sql = $sql.", location_id = {$location_id}";
    	if($member_id != "")
    		$sql = $sql.", member_id = {$member_id}";
    	if($person_id != "")
    		$sql = $sql.", person_id = {$person_id}";
    	if($specialty_id != "")
    		$sql = $sql.", specialty_id = {$specialty_id}";
    	
        $this->db->query($sql);
    	
    	// TODO this could return a different id than expected (two created almost simultaneously)
    	$sql = "select link_id from link order by link_id DESC limit 1";
        return $this->db->query($sql);       
    }
    
    //19 - Create Organization Location
    function create_location($organization_id , $primary , $address , $city , $state , $zip , $latitude , $longitude , $email1 = "", $email2 = "", $phone1 = "", $phone2 = "")
    {
    	$sql = "insert into organization_location set organization_id ={$organization_id}, is_primary ={$primary}, address ='{$address}', city ='{$city}', state ='{$state}', zip ='{$zip}', latitude = {$latitude}, longitude = {$longitude}, modified_time = now()";
    	if($email1 != "")
    		$sql = $sql.", email1 = '{$email1}'";
    	if($email2 != "")
    		$sql = $sql.", email2 = '{$email2}'";
    	if($phone1 != "")
    		$sql = $sql.", phone1 = '{$phone1}'";
    	if($phone2 != "")
    		$sql = $sql.", phone2 = '{$phone2}'";
    	
    	$this->db->query($sql);
    	
    	// TODO this could return a different id than expected (two created almost simultaneously)
    	$sql = "select location_id from organization_location order by location_id DESC limit 1";
        return $this->db->query($sql);
    }
    
    //20 - Edit Specialty
    function edit_specialty($specialty_id ,$specialty)
    {
    	$sql = "update specialty set specialty = '{$specialty}' where specialty_id = {$specialty_id}";    	
    	return $this->db->query($sql);
    }
    //21 - Edit Organization
    function edit_organization($organization_id, $organization)
    {
    	$sql = "update organization set organization = '{$organization}' where organization_id = {$organization_id}";    	
    	return $this->db->query($sql);
    }
    //22 - Edit Link
    function edit_link($link_id, $role_id ="", $link ="", $title ="", $top_level = "", $organization_id = "", $location_id = "", $member_id = "", $person_id = "", $specialty_id = "")
    {
    	$sql = "update link set link_id = {$link_id}";
    	if($role_id != "")
    		$sql .= ", role_id = {$role_id}";
    	if($link != "")
    		$sql .= ", link = '{$link}'";
    	if($title != "")
    		$sql .= ", title = '{$title}'";
    	if($top_level != "")
    		$sql .= ", top_level = {$top_level}";
    	if($organization_id != "")
    		$sql .= ", organization_id = {$organization_id}";
    	if($location_id != "")
    		$sql .= ", location_id = {$location_id}";
    	if($member_id != "")
    		$sql .= ", member_id = {$member_id}";
    	if($person_id != "")
    		$sql .= ", person_id = {$person_id}";
    	if($specialty_id != "")
    		$sql .= ", specialty_id = {$specialty_id}";
    	$sql .= " where link_id = {$link_id}";
    	
    	return $this->db->query($sql);
    }
    
    //23 - Edit Person/Member
    function edit_person($person_id ,$first_name ="", $last_name ="", $address ="", $city ="", $state ="", $zip ="", $phone1 ="", $latitude = "", $longitude = "", $email = "", $phone2 = "", $note = "", $modified_by = "")
    {
    	$sql = "update person set modified_time = now()";
    	if($first_name != "")
    		$sql .= ", first_name ='{$first_name}'";
    	if($last_name != "")
    		$sql .= ", last_name ='{$last_name}'";
    	if($address != "")
    		$sql .= ", address ='{$address}'";
    	if($city != "")
    		$sql .= ", city ='{$city}'";
    	if($state != "")
    		$sql .= ", state ='{$state}'";
    	if($zip != "")
    		$sql .= ", zip ='{$zip}'";
    	if($phone1 != "")
    		$sql .= ", phone1 =='{$phone1}'";
    	if($latitude != "" && $longitude != "")
    		$sql = $sql.", latitude = {$latitude}, longitude = {$longitude}";
    	if($email != "")
    		$sql = $sql.", email = '{$email}'";
    	if($phone2 != "")
    		$sql = $sql.", phone2 = '{$phone2}'";
    	if($note != "")
    		$sql = $sql.", notes = '{$note}'";
    	if($modified_by != "")
    		$sql = $sql.", modified_by = {$modified_by}";
    	$sql .= " where person_id = {$person_id}";
    	    
    	return $this->db->query($sql);
    }
    
    //24 - Edit Organization Location
    function edit_organization_location($location_id ,$organization_id ="", $primary ="", $address ="", $city ="", $state ="", $zip ="", $latitude = "", $longitude = "", $email1 = "", $email2 = "", $phone1 ="", $phone2 = "", $modified_by = "")
    {
    	$sql = "update organization_location set modified_time = now()";
    	if($organization_id != "")
    		$sql .= ", organization_id ={$organization_id}";
    	if($primary != "")
    		$sql .= ", is_primary ={$primary}";
    	if($address != "")
    		$sql .= ", address ='{$address}'";
    	if($city != "")
    		$sql .= ", city ='{$city}'";
    	if($state != "")
    		$sql .= ", state ='{$state}'";
    	if($zip != "")
    		$sql .= ", zip ='{$zip}'";
    	if($phone1 != "")
    		$sql .= ", phone1 =='{$phone1}'";
    	if($latitude != "" && $longitude != "")
    		$sql = $sql.", latitude = {$latitude}, longitude = {$longitude}";
    	if($email1 != "")
    		$sql = $sql.", email1 = '{$email1}'";
    	if($email2 != "")
    		$sql = $sql.", email2 = '{$email2}'";
    	if($phone2 != "")
    		$sql = $sql.", phone2 = '{$phone2}'";
    	if($modified_by != "")
    		$sql = $sql.", modified_by = {$modified_by}";
    	$sql .= " where location_id = {$location_id}";
    	    
    	return $this->db->query($sql);
    }
    
    //25 - Edit Flagged Item
    function edit_flag($flag_id, $verdict = "", $verdict_by = "", $verdict_comment = "", $organization_id = "", $location_id = "", $link_id = "", $member_id = "", $person_id = "", $specialty_id = "")
    {
    	$sql = "update flag set verdict_time = now()";
    	if($verdict != "")
    		$sql .= ", verdict = {$verdict}";
    	if($verdict_by != "")
    		$sql .= ", verdict_by = {$verdict_by}";
    	if($verdict_comment != "")
    		$sql .= ", verdict_comment = '{$verdict_comment}'";
    	if($organization_id != "")
    		$sql .= ", organization_id = {$organization_id}";
    	if($location_id != "")
    		$sql .= ", location_id = {$location_id}";
    	if($link_id != "")
    		$sql .= ", link_id = {$link_id}";
    	if($member_id != "")
    		$sql .= ", member_id = {$member_id}";
    	if($person_id != "")
    		$sql .= ", person_id = {$person_id}";
    	if($specialty_id != "")
    		$sql .= ", specialty_id = {$specialty_id}";
    	$sql .= " where flag_id = {$flag_id}";
    	
    	return $this->db->query($sql);
    }
    
    //26 - Edit Role
    function edit_role($role_id, $role = "" , $edits_per_day = "" ,  $edit_top_links = "" ,  $flag_for_removal = "" ,  $approve_removal = "" ,  $unremove = "" ,  $change_member_roles = "" ,  $manage_roles = "" )
    {
    	$sql = "update role set role_id = {$role_id}";
    	if($role != "")
    		$sql .= ", role = '{$role}'";
    	if($edits_per_day != "")
    		$sql .= ", edits_per_day = {$edits_per_day}";
    	if($edit_top_links != "")
    		$sql .= ", edit_top_links = {$edit_top_links}";
    	if($flag_for_removal != "")
    		$sql .= ", flag_for_removal = {$flag_for_removal}";
    	if($approve_removal != "")
    		$sql .= ", approve_removal = {$approve_removal}";
    	if($unremove != "")
    		$sql .= ", unremove = {$unremove}";
    	if($change_member_roles != "")
    		$sql .= ", change_member_roles = {$change_member_roles}";
    	if($manage_roles != "")
    		$sql .= ", manage_roles = {$manage_roles}";
    	$sql .= " where role_id = {$role_id}";
    	
    	return $this->db->query($sql);
    }
    
    //27 - Flag For Removal
    function create_flag($flag_by, $flag_comment = "", $organization_id="", $location_id="", $link_id="", $member_id="", $person_id="", $specialty_id="")
    {
    	$sql = "insert into flag set flag_by = {$flag_by}, flag_time = now()";
    	if($flag_comment != "")
    		$sql .= ", flag_comment = '{$flag_comment}'";
    	if($organization_id != "")
    		$sql .= ", organization_id = {$organization_id}";
    	if($location_id != "")
    		$sql .= ", location_id = {$location_id}";
    	if($link_id != "")
    		$sql .= ", link_id = {$link_id}";
    	if($member_id != "")
    		$sql .= ", member_id = {$member_id}";
    	if($person_id != "")
    		$sql .= ", person_id = {$person_id}";
    	if($specialty_id != "")
    		$sql .= ", specialty_id = {$specialty_id}";
    	
    	$this->db->query($sql);
    	
    	// TODO this could return a different id than expected (two created almost simultaneously)
    	$sql = "select flag_id from flag order by flag_id DESC limit 1";
        return $this->db->query($sql);    	
    }
    
    private function get_terms($search) {
        $terms = array();
        // treat the string as an array of chars
        $i = 0;
        $len = strlen($search);
        while ($i < $len) {      
            $end = 0;
            // If we come across a double-quote, add everything up to the next quote as a term,
            // unless the quotes are next to each other or there is only whitespace inside them.
            if ($search[$i] == '"') {
                $end = strpos($search, '"', $i + 1);
                if ($end >= $i + 2) {
                    $t = substr($search, $i + 1, $end - $i - 1);
                    if (preg_match('/\S/', $t)) {
                        $count = count($terms);
                        $terms[$count]['term'] = $t;
                        $terms[$count]['was_quoted'] = true;
                        $terms[$count]['index'] = $i + 1;
                        $terms[$count]['is_trivial'] = $this->is_trivial($t);
                        $terms[$count]['synonyms'] = array();
                        $terms[$count]['result_count'] = 0;
                    }                
                }                                            
            }
            // If we come across an alphanumeric character, consider it the start of another term.
            // The term ends when a non-alphanumeric character except an apostrophe is encountered.
            elseif (preg_match('/[a-zA-Z0-9]/', $search[$i])) {
                $t = array();
                $t[] = $search[$i];
                // Allows multiple apostrophes 
                for ($j = $i + 1; $j < $len; ++$j) {
                    if (preg_match('/[a-zA-Z0-9\']/', $search[$j])) {
                        $t[] = $search[$j];
                    }
                    else {            
                        $end = $j;
                        break; // inner loop
                    }
                }
                if ($end == 0) $end = $len;
                $t = implode($t);
                $count = count($terms);

                $terms[$count]['term'] = $t;
                $terms[$count]['was_quoted'] = false;
                $terms[$count]['index'] = $i;
                $terms[$count]['is_trivial'] = $this->is_trivial($t);
                $terms[$count]['synonyms'] = array();
                $terms[$count]['result_count'] = 0;
                             
                if (strpos($t, "'") !== false) {
                   $terms[$count]['synonyms'][] = str_replace("'", '', $t);
                }
            }
            if ($end > $i) $i = $end + 1;
            else ++$i;
        }
        return $terms;
    }
    
    private function is_trivial($term) {
        return strcasecmp($term, "a") == 0
            || strcasecmp($term, "and") == 0
            || strcasecmp($term, "it") == 0
            || strcasecmp($term, "the") == 0
            || strcasecmp($term, "but") == 0
            || strcasecmp($term, "by") == 0
            || strcasecmp($term, "i") == 0
            || strcasecmp($term, "for") == 0;
    }
    
    
    // quotes
    // first word
    // last word
    // other non-trivial words
    private function get_prioritized_terms($search) {
        $terms = $this->get_terms($search);          
        $out = array();
        $len = count($terms);
        if ($len < 1) return $out;
        
        $first = '';
        $last = '';

        // quoted non-trivial terms
        foreach ($terms as $t) {
            if (!$t['is_trivial'] && $t['was_quoted']) $out[] = $t['term'];
        }
        
        // first non-trivial term not quoted
        foreach ($terms as $t) {
            if (!$t['is_trivial'] && !$t['was_quoted']) {
                $first = $t['term'];
                $out[] = $first;
                foreach ($t['synonyms'] as $s) {
                    $out[] = $s;
                }
                break;
            }
        }
        
        // last non-trivial term not quoted
        for ($i = $len - 1; $i >= 0; --$i) {
            if (!$terms[$i]['is_trivial'] && !$terms[$i]['was_quoted'] && $terms[$i]['term'] != $first) {
                $last = $terms[$i]['term'];
                $out[] = $last;
                foreach ($terms[$i]['synonyms'] as $s) {
                    $out[] = $s;
                }
                break;
            }
        }
        
        // other non-trivial terms not quoted
        foreach ($terms as $t) {
            if (!$t['is_trivial'] && !$t['was_quoted'] && $t['term'] != $first && $t['term'] != $last) {
                $out[] = $t['term'];
            }
        }
        
        // trivial terms
        foreach ($terms as $t) {
            if ($t['is_trivial']) {
                $out[] = $t['term'];
            }
        }
        
        return array_unique($out);
    }  
    
    // Search all
    function search_all($search, $start = 0) {
        $search = $this->clean_user_input($search);
        if (strlen($search) == 0) return;
        $terms = $this->get_prioritized_terms($search);       
        
        $results = array();
        
        $person         = $this->simple_person_search($search);
        $location       = $this->simple_location_search($search);
        $specialty      = $this->simple_specialty_search($search);
        $organization   = $this->simple_organization_search($search);
        $link           = $this->simple_link_search($search);   
        
        $count['person']        = count($person);
        $count['location']      = count($location);
        $count['specialty']     = count($specialty);
        $count['organization']  = count($organization);
        $count['link']          = count($link);
                
        $max = max($count);
        for ($i = 0; $i < $max; ++$i) {
            if ($i < $count['person'])       $results[] = $person[$i];
            if ($i < $count['location'])     $results[] = $location[$i];
            if ($i < $count['specialty'])    $results[] = $specialty[$i];
            if ($i < $count['organization']) $results[] = $organization[$i];
            if ($i < $count['link'])         $results[] = $link[$i];
        }
        
        $term_results = array();
        $term_count = array();
        $len = count($terms);
        if ($len < 2) return $results;
        // terms
      
        for ($i = 0; $i < $len; ++$i) {
       
            $person         = $this->simple_person_search($terms[$i]);
            $location       = $this->simple_location_search($terms[$i]);
            $specialty      = $this->simple_specialty_search($terms[$i]);
            $organization   = $this->simple_organization_search($terms[$i]);
            $link           = $this->simple_link_search($terms[$i]);   
            
            $count['person']        = count($person);
            $count['location']      = count($location);
            $count['specialty']     = count($specialty);
            $count['organization']  = count($organization);
            $count['link']          = count($link);
 
            $max = max($count);                             
            for ($j = 0; $j < $max; ++$j) {
                if ($j < $count['person'])       $term_results[$i][] = $person[$j];
                if ($j < $count['location'])     $term_results[$i][] = $location[$j];
                if ($j < $count['specialty'])    $term_results[$i][] = $specialty[$j];
                if ($j < $count['organization']) $term_results[$i][] = $organization[$j];
                if ($j < $count['link'])         $term_results[$i][] = $link[$j];
            }
     
            $term_count[$i] = count($term_results[$i]);
        }
//echo nl2br(print_r($term_count, true));        
        uasort($term_count, array("DatabaseInterface", "order_terms"));
//echo nl2br(print_r($term_count, true));
        foreach (array_keys($term_count) as $i) {
            $results = array_merge($results, $term_results[$i]);
        }   
        
        $results = $this->remove_duplicates($results);
        return array_slice($results, $start, self::LIMIT);
    }
    
    private static function order_terms($count1, $count2) {  
        if ($count1 > $count2 * 2) return 1;        
        elseif ($count1 * 2 < $count2) return -1;
        else return 0; // undefined order :\
    }
    
    private function remove_duplicates($results) {
        $keys = array();
        $len = count($results);
        for ($i = 0; $i < $len; ++$i) {
            foreach (array_keys($results[$i]) as $field) {
                if ($keys[$field][$results[$i][$field]]) {                
                    unset($results[$i]);
                }
                else {
                    $keys[$field][$results[$i][$field]] = true;
                }
                break;
            }
        }       
        return array_merge($results);
    }
    
    private function simple_link_search($string) {
        $sql = "select * from link"
            . " where link.link like '%$string%'"
            . " or title like '%$string%'"
            . ";";
        return $this->db->query($sql);
    }
    
    private function simple_organization_search($string) {
        $sql = "select * from organization"
            . " where organization.organization like '%$string%'"      
            . ";";
        return $this->db->query($sql);
    }
    
    private function simple_location_search($string) {
        $sql = "select ol.*, o.organization from organization_location as ol, organization as o"
            . " where (address like '%$string%'"   
            . " or ol.city like '%$string%'"      
            . " or ol.state like '%$string%'"
            . " or ol.zip like '%$string%'"
            . " or ol.email1 like '%$string%'"
            . " or ol.email2 like '%$string%'"
            . " or ol.phone1 like '%$string%'"
            . " or ol.phone2 like '%$string%')"
            . " and o.organization_id=ol.organization_id"
            . ";";
        return $this->db->query($sql);
    }
    
    private function simple_person_search($string) {
        $sql = "select * from person"
            . " where first_name like '%$string%'"   
            . " or last_name like '%$string%'"   
            . " or address like '%$string%'"   
            . " or city like '%$string%'"      
            . " or state like '%$string%'"
            . " or zip like '%$string%'"
            . " or email like '%$string%'"
            . " or phone1 like '%$string%'"
            . " or phone2 like '%$string%'"
            . " or notes like '%$string%'"
            . ";";
        return $this->db->query($sql);
    }
    
    private function simple_specialty_search($string) {
        $sql = "select * from specialty"
            . " where specialty.specialty like '%$string%'"          
            . ";";
        return $this->db->query($sql);
    }
        
    function list_map($lat, $lon)
    {    	
    	$sql = "select l.location_id, l.is_primary, l.address , l.city , l.state , l.zip , l.latitude , l.longitude, o.organization from organization_location as l, organization as o where latitude <= {$lat} + 1 and latitude >= {$lat} - 1 and longitude <= {$lon} + 1 and longitude >= {$lon} - 1 and o.organization_id=l.organization_id";
    	$locations = $this->db->query($sql);    	
    	$sql = "select person_id, first_name, last_name, address , city , state , zip , latitude , longitude from person where latitude <= {$lat} + 1 and latitude >= {$lat} - 1 and longitude <= {$lon} + 1 and longitude >= {$lon} - 1";
    	$people = $this->db->query($sql);
    	$sql = "select m.member_id, m.person_id, m.latitude , m.longitude, m.location_time, p.first_name, p.last_name from member as m, person as p where m.latitude <= {$lat} + 1 and m.latitude >= {$lat} - 1 and m.longitude <= {$lon} + 1 and m.longitude >= {$lon} - 1 and p.person_id=m.person_id";
    	$members = $this->db->query($sql);
    	return array_merge($locations, $people, $members);
    }
    
    // TODO these confirm remove functions should be called inside 'edit_flag'.
    
    //confirm remove
    function confirm_remove_person($person_id)
    { 
    	$sql = "update person set remove_approved = 1 where person_id = {$person_id}";    	
    	return $this->db->query($sql);
    }
    
    function confirm_remove_organization($organization_id)
    { 
    	$sql = "update organization set remove_approved = 1 where organization_id = {$organization_id}";    	
    	return $this->db->query($sql);
    }
    
    function confirm_remove_organization_location ($location_id)
    { 
    	$sql = "update organization_location  set remove_approved = 1 where location_id = {$location_id}";    	
    	return $this->db->query($sql);
    }
    
    function confirm_remove_link($link_id)
    { 
    	$sql = "update link set remove_approved = 1 where link_id = {$link_id}";    	
    	return $this->db->query($sql);
    }
    
    function confirm_remove_member($member_id)
    { 
    	$sql = "update member set remove_approved = 1 where member_id = {$member_id}";    	
    	return $this->db->query($sql);
    }
    
    function confirm_remove_specialty($specialty_id)
    { 
    	$sql = "update specialty set remove_approved = 1 where specialty_id = {$specialty_id}";    	
    	return $this->db->query($sql);
    }
}
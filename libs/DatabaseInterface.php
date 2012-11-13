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
    const LIMIT = 30;
    
    private $db;
    
    function __construct(DatabaseHelper $db) {
        $this->db = $db;
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
    	$sql = "select person.first_name , person.last_name from person where person_id in (select person_specialty.person_id from person_specialty where specialty_id ={$specialty_id})";
    	$sql = $sql." limit {$start},".self::LIMIT;
    	return $this->db->query($sql);
    }
    
    //test function for searching organizations with certain area
    function locations_search($lat, $lon)
    {    	
    	$sql = "select organization_location.address , organization_location.city , organization_location.state , organization_location.zip , organization_location.latitude , organization_location.longitude from organization_location where latitude <= {$lat} + 0.1 and latitude >= {$lat} - 0.1 and longitude <= {$lon} + 0.1 and longitude >= {$lon} - 0.1";
    	return $this->db->query($sql);
    }
    
    //9Get Organization Locations
    function organization_locations($organization_id, $start = 0)
    {    	
    	$sql = "select organization_location.location_id , organization_location.address from organization_location where organization_id = {$organization_id}";
    	$sql = $sql." limit {$start},".self::LIMIT;
    	return $this->db->query($sql);
    }
    
    //10Get Person Detail
    function personDetail($id)
    {    	
    	$sql = "select first_name,last_name,role.role,username,member.email as 'user email',address,city,state,zip,person.email,phone_1,phone_2,notes,`person`.`remove`,modified_by,modified_time from person,member,role where person.person_id=member.person_id and member.role_id=role.role_id and person.person_id={$id}";
    	return $this->db->query($sql);
    }
    
    //11Get (Organization) Location Detail
    function location($location_id)
    {    	
    	$sql = "select organization_location.address , organization_location.city , organization_location.state , organization_location.zip , organization_location.latitude , organization_location.longitude from organization_location where location_id = {$location_id}";
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
    	$sql = "insert into person set first_name ='{$first_name}', last_name ='{$last_name}', address ='{$address}', city ='{$city}', state ='{$state}', zip ='{$zip}', phone_1='{$phone1}', modified_time = now()";
    	if($latitude != "" && $longitude != "")
    		$sql = $sql.", latitude = {$latitude}, longitude = {$longitude}";
    	if($email != "")
    		$sql = $sql.", email = '{$email}'";
    	if($phone2 != "")
    		$sql = $sql.", phone_2 = '{$phone2}'";
    	if($note != "")
    		$sql = $sql.", notes = '{$note}'";
    	
    	// insert
    	$this->db->query($sql);    	
    	
    	// return new id
    	$sql = "select person_id from person order by person_id DESC limit 1";
    	return $this->db->result($sql);
    }
    
    //16 - Create Specialty
    function create_specialty($specialty)
    {
    	$sql = "insert into specialty set specialty ='{$specialty}'";
    	$this->db->query($sql);
    	
    	$sql = "select specialty_id from specialty order by specialty_id DESC limit 1";
        return $this->db->result($sql);    	
    }
    
    //17 - Create Organization
    function create_organization($organization)
    {
    	$sql = "insert into organization set organization ='{$organization}'";
    	$this->db->query($sql);
    	
    	$sql = "select organization_id from organization order by organization_id DESC limit 1";
        return $this->db->result($sql);
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
    	
    	$sql = "select link_id from link order by link_id DESC limit 1";
        return $this->db->result($sql);       
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
    	
    	$sql = "select location_id from organization_location order by location_id DESC limit 1";
        return $this->db->result($sql);
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
    	if($phone_1 != "")
    		$sql .= ", phone_1 =='{$phone1}'";
    	if($latitude != "" && $longitude != "")
    		$sql = $sql.", latitude = {$latitude}, longitude = {$longitude}";
    	if($email != "")
    		$sql = $sql.", email = '{$email}'";
    	if($phone2 != "")
    		$sql = $sql.", phone_2 = '{$phone2}'";
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
    	if($phone_1 != "")
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
    	
    	$sql = "select flag_id from flag order by flag_id DESC limit 1";
        return $this->db->result($sql);    	
    }
    
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
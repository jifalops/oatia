<?php
require_once('../header.php');
//set records limit
define(LIMIT, '50');

function getConnection()
{
	$connection = mysql_connect(Secure::DB_HOST, Secure::DB_USERNAME, Secure::DB_PASSWORD);
	if (!$connection)
		{
		die('Could not connect: ' . mysql_error());
		}
	return $connection;
}

function print_results($resources)
{
	while($resource = mysql_fetch_assoc($resources))
	{
		print_r($resource);
	}
}

function showerror( )
{

      die("Error " . mysql_errno( ) . " : " . mysql_error( ));

}

//2Get Specialties
function specialty($connection, $start = 0)
{
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	$sql = "select specialty.specialty_id , specialty.specialty from specialty";
	$sql = $sql." limit {$start},".LIMIT;
	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}
//4Get Organizations 
function organization($connection, $start = 0)
{
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	$sql = "select organization.organization_id , organization.organization from organization";
	$sql = $sql." limit {$start},".LIMIT;
	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}
//5Get Links
function links($connection, $start = 0)
{
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	$sql = "select link.link_id , link.link , link.title from link";
	$sql = $sql." limit {$start},".LIMIT;
	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}
//6Get Pending Removals
// TODO this should use the "flag" table
function get_pending_remove( $table, $connection, $start = 0)
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
		
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	$sql = $sql." limit {$start},".LIMIT;
	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}
//7Get Roles
function role($connection, $start = 0)
{
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	$sql = "select role.role_id , role.role from role";
	$sql = $sql." limit {$start},".LIMIT;
	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}
//8Get People by Specialty
function specialty_person($specialty_id, $connection, $start = 0)
{
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	$sql = "select person.first_name , person.last_name from person where person_id in (select person_specialty.person_id from person_specialty where specialty_id ={$specialty_id})";
	$sql = $sql." limit {$start},".LIMIT;
	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}
//test function for searching organizations with certain area
function locations_search($lat, $lon, $connection, $start = 0)
{
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	$sql = "select organization_location.address , organization_location.city , organization_location.state , organization_location.zip , organization_location.latitude , organization_location.longitude from organization_location where latitude <= {$lat} + 0.1 and latitude >= {$lat} - 0.1 and longitude <= {$lon} + 0.1 and longitude >= {$lon} - 0.1";
	$sql = $sql." limit {$start},".LIMIT;
	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}
//9Get Organization Locations
function organization_locations($organization_id, $connection, $start = 0)
{
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	$sql = "select organization_location.location_id , organization_location.address ,  from organization_location where organization_id = {$organization_id}";
	$sql = $sql." limit {$start},".LIMIT;
	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}
//10Get Person Detail
function personDetail($id, $connection)
{
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	$sql = "select first_name,last_name,role.role,username,member.email as 'user email',address,city,state,zip,person.email,phone_1,phone_2,notes,`person`.`remove`,modified_by,modified_time from person,member,role where person.person_id=member.person_id and member.role_id=role.role_id and person.person_id={$id}";
	$sql = $sql." limit {$start},".LIMIT;
	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}
//11Get (Organization) Location Detail
function location($location_id, $connection, $start = 0)
{
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	$sql = "select organization_location.address , organization_location.city , organization_location.state , organization_location.zip , organization_location.latitude , organization_location.longitude from organization_location where location_id = {$location_id}";
	$sql = $sql." limit {$start},".LIMIT;
	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}
//12Get Approved Removals
function get_remove_approved( $table, $connection, $order = "FF", $start = 0)
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
		
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	$sql = $sql." limit {$start},".LIMIT;
	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}

//15Create Person
function create_person($connection , $first_name , $last_name , $address , $city , $state , $zip , $phone1 , $latitude = "", $longitude = "", $email = "", $phone2 = "", $note = "")
{
	$sql = "insert into person set first_name ='{$first_name}', last_name ='{$last_name}', address ='{$address}', city ='{$city}', state ='{$state}', zip ='{$zip}', phone_1 =='{$phone1}', modified_time = now()";
	if($latitude != "" && $longitude != "")
		$sql = $sql.", latitude = {$latitude}, longitude = {$longitude}";
	if($email != "")
		$sql = $sql.", email = '{$email}'";
	if($phone2 != "")
		$sql = $sql.", phone_2 = '{$phone2}'";
	if($note != "")
		$sql = $sql.", notes = '{$note}'";
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);

	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
	{
		if(!($return_values = @ mysql_query("select person_id from person order by person_id DESC limit 1", $connection)))
			showerror();
		else
		{
			$return = mysql_fetch_array($return_values);
			return $return[0];
		}
	}
}
//16 - Create Specialty
function create_specialty($connection , $specialty)
{
	$sql = "insert into specialty set specialty ='{$specialty}'";
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);

	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
	{
		if(!($return_values = @ mysql_query("select specialty_id from specialty order by specialty_id DESC limit 1", $connection)))
			showerror();
		else
		{
			$return = mysql_fetch_array($return_values);
			return $return[0];
		}
	}
}
//17 - Create Organization
function create_organization($connection , $organization)
{
	$sql = "insert into organization set organization ='{$organization}'";
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);

	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
	{
		if(!($return_values = @ mysql_query("select organization_id from organization order by organization_id DESC limit 1", $connection)))
			showerror();
		else
		{
			$return = mysql_fetch_array($return_values);
			return $return[0];
		}
	}
}
//18 - Create Link
function create_link($connection , $role_id , $link , $title , $top_level = 0, $organization_id = "", $location_id = "", $member_id = "", $person_id = "", $specialty_id = "")
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
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);

	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
	{
		if(!($return_values = @ mysql_query("select link_id from link order by link_id DESC limit 1", $connection)))
			showerror();
		else
		{
			$return = mysql_fetch_array($return_values);
			return $return[0];
		}
	}
}
//19 - Create Organization Location
function create_location($connection , $organization_id , $primary , $address , $city , $state , $zip , $latitude , $longitude , $email1 = "", $email2 = "", $phone1 = "", $phone2 = "")
{
	$sql = "insert into organization_location set organization_id ={$organization_id}, primary ={$primary}, address ='{$address}', city ='{$city}', state ='{$state}', zip ='{$zip}', latitude = {$latitude}, longitude = {$longitude}, modified_time = now()";
	if($email1 != "")
		$sql = $sql.", email1 = '{$email1}'";
	if($email2 != "")
		$sql = $sql.", email2 = '{$email2}'";
	if($phone1 != "")
		$sql = $sql.", phone1 = '{$phone1}'";
	if($phone2 != "")
		$sql = $sql.", phone2 = '{$phone2}'";
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);

	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
	{
		if(!($return_values = @ mysql_query("select location_id from organization_location order by location_id DESC limit 1", $connection)))
			showerror();
		else
		{
			$return = mysql_fetch_array($return_values);
			return $return[0];
		}
	}
}
//20 - Edit Specialty
function edit_specialty($connection , $specialty_id ,$specialty)
{
	$sql = "update specialty set specialty = '{$specialty}' where specialty_id = {$specialty_id}";
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}
//21 - Edit Organization
function edit_organization($connection , $organization_id, $organization)
{
	$sql = "update organization set organization = '{$organization}' where organization_id = {$organization_id}";
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}
//22 - Edit Link
function edit_link($connection , $link_id, $role_id ="", $link ="", $title ="", $top_level = "", $organization_id = "", $location_id = "", $member_id = "", $person_id = "", $specialty_id = "")
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
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}
//23 - Edit Person/Member
function edit_person($connection , $person_id ,$first_name ="", $last_name ="", $address ="", $city ="", $state ="", $zip ="", $phone1 ="", $latitude = "", $longitude = "", $email = "", $phone2 = "", $note = "", $modified_by = "")
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
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);

	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}
//24 - Edit Organization Location
function edit_organization_location($connection , $location_id ,$organization_id ="", $primary ="", $address ="", $city ="", $state ="", $zip ="", $latitude = "", $longitude = "", $email1 = "", $email2 = "", $phone1 ="", $phone2 = "", $modified_by = "")
{
	$sql = "update organization_location set modified_time = now()";
	if($organization_id != "")
		$sql .= ", organization_id ={$organization_id}";
	if($primary != "")
		$sql .= ", primary ={$primary}";
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
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);

	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}
//25 - Edit Flagged Item
function edit_flag($connection, $flag_id, $verdict = "", $verdict_by = "", $verdict_comment = "", $organization_id = "", $location_id = "", $link_id = "", $member_id = "", $person_id = "", $specialty_id = "")
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
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}
//26 - Edit Role
function edit_role($connection, $role_id, $role = "" , $edits_per_day = "" ,  $edit_top_links = "" ,  $flag_for_removal = "" ,  $approve_removal = "" ,  $unremove = "" ,  $change_member_roles = "" ,  $manage_roles = "" )
{
	$sql = "update role set $role_id = {$role_id}";
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
	$sql .= " where role_id = {role_id}";
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}
//27 - Flag For Removal
function create_flag($connection, $flag_by, $flag_comment = "", $organization_id="", $location_id="", $link_id="", $member_id="", $person_id="", $specialty_id="")
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
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
	{
		if(!($return_values = @ mysql_query("select flag_id from flag order by flag_id DESC limit 1", $connection)))
			showerror();
		else
		{
			$return = mysql_fetch_array($return_values);
			return $return[0];
		}
	}
}
//confirm remove
function confirm_remove_person($connection, $person_id)
{ 
	$sql = "update person set remove_approved = 1 where person_id = {$person_id}";
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}
function confirm_remove_organization($connection, $organization_id)
{ 
	$sql = "update organization set remove_approved = 1 where organization_id = {$organization_id}";
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}
function confirm_remove_organization_location ($connection, $location_id)
{ 
	$sql = "update organization_location  set remove_approved = 1 where location_id = {$location_id}";
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}
function confirm_remove_link($connection, $link_id)
{ 
	$sql = "update link set remove_approved = 1 where link_id = {$link_id}";
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}
function confirm_remove_member($connection, $member_id)
{ 
	$sql = "update member set remove_approved = 1 where member_id = {$member_id}";
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}
function confirm_remove_specialty($connection, $specialty_id)
{ 
	$sql = "update specialty set remove_approved = 1 where specialty_id = {$specialty_id}";
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	if(!($resource = @ mysql_query($sql, $connection)))
		showerror();
		//echo $sql;
	else
		return $resource;
}
?>
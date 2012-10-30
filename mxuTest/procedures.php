<?php
include('../header.php');

function getConnection()
{
	$connection = mysql_connect(Secure::DB_HOST, Secure::DB_USERNAME, Secure::DB_PASSWORD);
	if (!$connection)
		{
		die('Could not connect: ' . mysql_error());
		}
	return $connection;
}

function test($connection)
{
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	$resource = mysql_query("select * from person where person_id < 5", $connection);
	return $resource;
}

function procedureTest($connection)
{
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	$resource = mysql_query("select first_name,last_name,role.role,username,member.email as 'user email',address,city,state,zip,person.email,phone_1,phone_2,notes,`person`.`remove`,modified_by,modified_time from person,member,role where person.person_id=member.person_id and member.role_id=role.role_id and person.person_id=6", $connection);
	return $resource;
}

function personDetail($id, $connection)
{
	$db_selected = mysql_select_db(Secure::DB_DATABASE, $connection);
	$resource = mysql_query("select first_name,last_name,role.role,username,member.email as 'user email',address,city,state,zip,person.email,phone_1,phone_2,notes,`person`.`remove`,modified_by,modified_time from person,member,role where person.person_id=member.person_id and member.role_id=role.role_id and person.person_id={$id}", $connection);
	return $resource;
}

<?php
require_once(dirname(__FILE__).'/../DatabaseHelper.php');
require_once(dirname(__FILE__).'/../DatabaseInterface.php');

class DatabaseInterfaceTest {
    private static $helper;
    
    static function run(DatabaseHelper $helper) {
        self::$helper = $helper;
        $di = new DatabaseInterface($helper);
          
        self::show("specialty", $di->specialty());
        self::show("organization", $di->organization());
        self::show("links", $di->links());
        self::show("get_pending_remove", $di->get_pending_remove('LK'));
        self::show("role", $di->role());
        self::show("specialty_person", $di->specialty_person(3));
        self::show("locations_search", $di->locations_search(41.6638073, -83.6073732));
        self::show("organization_locations", $di->organization_locations(1));
        self::show("personDetail", $di->personDetail(2));
        self::show("location", $di->location(4));
        self::show("get_remove_approved", $di->get_remove_approved('LK'));
        self::show("create_person", $di->create_person('first_name', 'last_name', 'address', 'city', 'state', 'zip', 'phone1', 42.6638073, -82.6073732, 'email', 'phone2', 'note'));        
        self::show("create_specialty", $di->create_specialty('testspec'));
        self::show("create_organization", $di->create_organization('testorg'));
        self::show("create_link", $di->create_link(1, 'www.example.com', 'example'));
        self::show("create_location", $di->create_location(5, 1, 'address', 'city', 'state', 'zip', 40.6638073, -84.6073732, 'email1', 'email2', 'phone1', 'phone2'));
        self::show("edit_specialty", $di->edit_specialty(3, 'editspec'));
        self::show("edit_organization", $di->edit_organization(2, 'editorg'));
        self::show("edit_link", $di->edit_link(5, 1, 'www.edit.com', 'edit'));
        self::show("edit_person", $di->edit_person(7, 'edit', 'last_name', 'address', 'city', 'state', 'zip', 'phone1', 41.7638073, -83.7073732, 'email', 'phone2', 'note'));
        self::show("edit_organization_location", $di->edit_organization_location(6, 5, 1, 'editted', 'city', 'state', 'zip', 40.5638073, -84.5073732, 'email1', 'email2', 'phone1', 'phone2'));
        self::show("edit_flag", $di->edit_flag(2, 1, 4, 'approved', 0, 0, 6, 0, 0, 0));
        self::show("edit_role", $di->edit_role(4, '', 5));
        self::show("create_flag", $di->create_flag(4, 'wtf', 0, 0, 6, 0, 0, 0));
        self::show("confirm_remove_person", $di->confirm_remove_person(1));
        self::show("confirm_remove_organization", $di->confirm_remove_organization(1));
        self::show("confirm_remove_organization_location", $di->confirm_remove_organization_location(1));
        self::show("confirm_remove_link", $di->confirm_remove_link(1));
        self::show("confirm_remove_member", $di->confirm_remove_member(1));
        self::show("confirm_remove_specialty", $di->confirm_remove_specialty(1));
    }
    
    private static function show($title, $results) {
        echo "<hr /><h3>$title</h3>\n";
        echo nl2br(print_r($results, true));
        echo "<h4>Errors</h4>\n";
        echo nl2br(print_r(self::$helper->get_errors(), true));
    }   
}
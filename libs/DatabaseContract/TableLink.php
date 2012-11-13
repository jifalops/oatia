<?php
class TableLink {
    const TABLE_NAME            = 'link';
    
    const COL_ID                = 'link_id';
    const COL_ROLE_ID           = 'role_id';
    const COL_LINK              = 'link';
    const COL_TITLE             = 'title';
    const COL_TOP_LEVEL         = 'top_level';
    const COL_ORGANIZATION_ID   = 'organization_id';
    const COL_LOCATION_ID       = 'location_id';
    const COL_MEMBER_ID         = 'member_id';
    const COL_PERSON_ID         = 'person_id';
    const COL_SPECIALTY_ID      = 'specialty_id';
    const COL_REMOVE            = 'remove';
    const COL_REMOVE_APPROVED   = 'remove_approved';

    static function show_create_table() {
        return 'CREATE TABLE '.self::TABLE_NAME.' ('
            .self::COL_ID               .' int(10) unsigned NOT NULL AUTO_INCREMENT, '
            .self::COL_ROLE_ID	        .' int(11) unsigned NOT NULL DEFAULT "1" COMMENT "minimum role that can see link", '
            .self::COL_LINK	            .' text NOT NULL, '
            .self::COL_TITLE	        .' varchar(50) NOT NULL, '
            .self::COL_TOP_LEVEL	    .' tinyint(1) NOT NULL DEFAULT "0" COMMENT "is top-level in the app", '
            .self::COL_ORGANIZATION_ID  .' int(10) unsigned DEFAULT NULL, '
            .self::COL_LOCATION_ID	    .' int(10) unsigned DEFAULT NULL, '
            .self::COL_MEMBER_ID	    .' int(10) unsigned DEFAULT NULL, '
            .self::COL_PERSON_ID	    .' int(10) unsigned DEFAULT NULL, '
            .self::COL_SPECIALTY_ID	    .' int(10) unsigned DEFAULT NULL, '
            .self::COL_REMOVE	        .' tinyint(1) NOT NULL DEFAULT "0", '
            .self::COL_REMOVE_APPROVED	.' tinyint(1) NOT NULL DEFAULT "0", '
            .'PRIMARY KEY ('.self::COL_ID.') '
            .');';
     }
}
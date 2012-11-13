<?php
class TableMember {
    const TABLE_NAME            = 'member';
    
    const COL_ID                = 'member_id';
    const COL_ROLE_ID           = 'role_id';
    const COL_PERSON_ID         = 'person_id';
    const COL_USERNAME          = 'username';
    const COL_PASSWORD          = 'password';
    const COL_EMAIL             = 'email';
    const COL_LATITUDE          = 'latitude';
    const COL_LONGITUDE         = 'longitude';
    const COL_LOCATION_TIME     = 'location_time';
    const COL_EDITS             = 'edits';
    const COL_EDITS_TODAY       = 'edits_today';
    const COL_LOGINS            = 'logins';
    const COL_LOGIN_TIME        = 'login_time';
    const COL_EXPIRE_DATE       = 'expire_date';
    const COL_DISCLAIMER_ACCEPT = 'disclaimer_accept';
    const COL_REMOVE            = 'remove';
    const COL_REMOVE_APPROVED   = 'remove_approved';

    static function show_create_table() {
        return 'CREATE TABLE '.self::TABLE_NAME.' ('
            .self::COL_ID                   .' int(10) unsigned NOT NULL AUTO_INCREMENT, '
            .self::COL_ROLE_ID              .' int(10) unsigned NOT NULL DEFAULT "1", '
            .self::COL_PERSON_ID            .' int(10) unsigned NOT NULL, '
            .self::COL_USERNAME             .' varchar(20) NOT NULL, '
            .self::COL_PASSWORD             .' varchar(40) NOT NULL, '
            .self::COL_EMAIL                .' varchar(40) NOT NULL, '
            .self::COL_LATITUDE             .' double DEFAULT NULL, '
            .self::COL_LONGITUDE            .' double DEFAULT NULL, '
            .self::COL_LOCATION_TIME        .' timestamp NULL DEFAULT NULL, '
            .self::COL_EDITS                .' int(10) unsigned NOT NULL DEFAULT "0" COMMENT "count of modifications member makes", '
            .self::COL_EDITS_TODAY          .' int(10) unsigned NOT NULL DEFAULT "0", '
            .self::COL_LOGINS               .' int(10) unsigned NOT NULL DEFAULT "0", '
            .self::COL_LOGIN_TIME           .' timestamp NULL DEFAULT NULL COMMENT "last login", '
            .self::COL_EXPIRE_DATE          .' date NOT NULL, '
            .self::COL_DISCLAIMER_ACCEPT    .' tinyint(1) NOT NULL DEFAULT "0", '
            .self::COL_REMOVE               .' tinyint(1) NOT NULL DEFAULT "0", '
            .self::COL_REMOVE_APPROVED      .' tinyint(1) NOT NULL DEFAULT "0", '
            .'PRIMARY KEY ('.self::COL_ID.'), '
            .'UNIQUE KEY '.self::COL_USERNAME.'('.self::COL_USERNAME.','.self::COL_EMAIL.') '
            .');';
     }
}
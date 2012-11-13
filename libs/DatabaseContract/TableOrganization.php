<?php
class TableOrganization {
    const TABLE_NAME            = 'organization';
    
    const COL_ID                = 'organization_id';
    const COL_ORGANIZATION      = 'organization';
    const COL_REMOVE            = 'remove';
    const COL_REMOVE_APPROVED   = 'remove_approved';

    static function show_create_table() {
        return 'CREATE TABLE '.self::TABLE_NAME.' ('
            .self::COL_ID               .' int(11) unsigned NOT NULL AUTO_INCREMENT, '
            .self::COL_ORGANIZATION     .' varchar(40) NOT NULL, '
            .self::COL_REMOVE           .' tinyint(1) NOT NULL DEFAULT "0", '
            .self::COL_REMOVE_APPROVED  .' tinyint(1) NOT NULL DEFAULT "0", '
            .'PRIMARY KEY ('.self::COL_ID.'), '
            .'UNIQUE KEY '.self::COL_ORGANIZATION.'('.self::COL_ORGANIZATION.') '
            .');';
     }
}
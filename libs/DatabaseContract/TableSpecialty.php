<?php
class TableSpecialty {
    const TABLE_NAME            = 'specialty';
    
    const COL_ID                = 'specialty_id';
    const COL_SPECIALTY         = 'specialty';
    const COL_REMOVE            = 'remove';
    const COL_REMOVE_APPROVED   = 'remove_approved';

    static function show_create_table() {
        return 'CREATE TABLE '.self::TABLE_NAME.' ('
            .self::COL_ID               .' int(11) unsigned NOT NULL AUTO_INCREMENT, '
            .self::COL_SPECIALTY        .' varchar(40) NOT NULL, '
            .self::COL_REMOVE           .' tinyint(1) NOT NULL DEFAULT "0", '
            .self::COL_REMOVE_APPROVED  .' tinyint(1) NOT NULL DEFAULT "0", '
            .'PRIMARY KEY ('.self::COL_ID.'), '
            .'UNIQUE KEY '.self::COL_SPECIALTY.'('.self::COL_SPECIALTY.') '
            .');';
     }
}
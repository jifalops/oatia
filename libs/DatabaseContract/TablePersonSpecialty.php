<?php
class TablePersonSpecialty {
    const TABLE_NAME            = 'person_specialty';
    
    const COL_PERSON_ID         = 'person_id';
    const COL_SPECIALTY_ID      = 'specialty_id';

    static function show_create_table() {
        return 'CREATE TABLE '.self::TABLE_NAME.' ('
            .self::COL_PERSON_ID    .' int(10) unsigned NOT NULL, '
            .self::COL_SPECIALTY_ID .' int(10) unsigned NOT NULL, '
            .'KEY '.self::COL_PERSON_ID.' ('.self::COL_PERSON_ID.','.self::COL_SPECIALTY_ID.') '
            .');';
     }
}
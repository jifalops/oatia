<?php
class TablePersonOrganization {
    const TABLE_NAME            = 'person_organization';
    
    const COL_PERSON_ID         = 'person_id';
    const COL_ORGANIZATION_ID   = 'organization_id';

    static function show_create_table() {
        return 'CREATE TABLE '.self::TABLE_NAME.' ('
            .self::COL_PERSON_ID        .' int(10) unsigned NOT NULL, '
            .self::COL_ORGANIZATION_ID  .' int(10) unsigned NOT NULL COMMENT "should be changed to location_id", '
            .'KEY '.self::COL_PERSON_ID.' ('.self::COL_PERSON_ID.','.self::COL_ORGANIZATION_ID.') '
            .');';
     }
}
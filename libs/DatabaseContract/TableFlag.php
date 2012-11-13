<?php
class TableFlag {
    const TABLE_NAME            = 'flag';
    
    const COL_ID                = 'flag_id';
    const COL_FLAG_BY           = 'flag_by';
    const COL_FLAG_TIME         = 'flag_time';
    const COL_FLAG_COMMENT      = 'flag_comment';
    const COL_VERDICT           = 'verdict';
    const COL_VERDICT_BY        = 'verdict_by';
    const COL_VERDICT_TIME      = 'verdict_time';
    const COL_VERDICT_COMMENT   = 'verdict_comment';
    const COL_ORGANIZATION_ID   = 'organization_id';
    const COL_LOCATION_ID       = 'location_id';
    const COL_LINK_ID           = 'link_id';
    const COL_MEMBER_ID         = 'member_id';
    const COL_PERSON_ID         = 'person_id';
    const COL_SPECIALTY_ID      = 'specialty_id';

    static function show_create_table() {
        return 'CREATE TABLE '.self::TABLE_NAME.' ('
            .self::COL_ID                .' int(10) unsigned NOT NULL AUTO_INCREMENT, '
            .self::COL_FLAG_BY           .' int(10) unsigned NOT NULL,, '
            .self::COL_FLAG_TIME         .' timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, '
            .self::COL_FLAG_COMMENT      .' text, '
            .self::COL_VERDICT           .' tinyint(1) DEFAULT NULL, '
            .self::COL_VERDICT_BY        .' int(10) unsigned DEFAULT NULL, '
            .self::COL_VERDICT_TIME      .' timestamp NULL DEFAULT NULL, '
            .self::COL_VERDICT_COMMENT   .' text, '
            .self::COL_ORGANIZATION_ID   .' int(10) unsigned DEFAULT NULL, '
            .self::COL_LOCATION_ID       .' int(10) unsigned DEFAULT NULL, '
            .self::COL_LINK_ID           .' int(10) unsigned DEFAULT NULL, '
            .self::COL_MEMBER_ID         .' int(10) unsigned DEFAULT NULL, '
            .self::COL_PERSON_ID         .' int(10) unsigned DEFAULT NULL, '
            .self::COL_SPECIALTY_ID      .' int(10) unsigned DEFAULT NULL, '
            .'PRIMARY KEY ('.self::COL_ID.') '
            .'KEY ('.self::COL_VERDICT.') '
            .');';
     }
}
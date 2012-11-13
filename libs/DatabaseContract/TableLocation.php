<?php
class TableLocation {
    const TABLE_NAME            = 'organization_location';
    
    const COL_ID                = 'location_id';
    const COL_ORGANIZATION_ID   = 'organization_id';
    const COL_PRIMARY           = 'is_primary';
    const COL_ADDRESS           = 'address';
    const COL_CITY              = 'city';
    const COL_STATE             = 'state';
    const COL_ZIP               = 'zip';
    const COL_LATITUDE          = 'latitude';
    const COL_LONGITUDE         = 'longitude';
    const COL_EMAIL1            = 'email1';
    const COL_EMAIL2            = 'email2';
    const COL_PHONE1            = 'phone1';
    const COL_PHONE2            = 'phone2';
    const COL_REMOVE            = 'remove';
    const COL_REMOVE_APPROVED   = 'remove_approved';
    const COL_MODIFIED_BY       = 'modified_by';
    const COL_MODIFIED_TIME     = 'modified_time';

    static function show_create_table() {
        return 'CREATE TABLE '.self::TABLE_NAME.' ('
            .self::COL_ID               .' int(11) unsigned NOT NULL AUTO_INCREMENT, '
            .self::COL_ORGANIZATION_ID  .' int(10) unsigned NOT NULL, '
            .self::COL_IS_PRIMARY       .' tinyint(1) NOT NULL DEFAULT "0" COMMENT "primary location of agency", '
            .self::COL_ADDRESS          .' varchar(50) DEFAULT NULL COMMENT "address or gps coords", '
            .self::COL_CITY             .' varchar(30) DEFAULT NULL, '
            .self::COL_STATE            .' char(2) DEFAULT NULL, '
            .self::COL_ZIP              .' varchar(10) DEFAULT NULL, '
            .self::COL_LATITUDE         .' double DEFAULT NULL, '
            .self::COL_LONGITUDE        .' double DEFAULT NULL, '
            .self::COL_EMAIL1           .' varchar(40) DEFAULT NULL, '
            .self::COL_EMAIL2           .' varchar(40) DEFAULT NULL, '
            .self::COL_PHONE1           .' varchar(20) DEFAULT NULL, '
            .self::COL_PHONE2           .' varchar(20) DEFAULT NULL, '
            .self::COL_REMOVE           .' tinyint(1) NOT NULL DEFAULT "0" COMMENT "flag a user can set for removal", '
            .self::COL_REMOVE_APPROVED  .' tinyint(1) NOT NULL DEFAULT "0" COMMENT "if set, should not be shown to users", '
            .self::COL_MODIFIED_BY      .' int(10) unsigned NOT NULL, '
            .self::COL_MODIFIED_TIME    .' timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, '
            .'PRIMARY KEY ('.self::COL_ID .'), '
            .'KEY '.self::COL_ORGANIZATION_ID .' ('.self::COL_ORGANIZATION_ID.') '
            .');';
     }
}
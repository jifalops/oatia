<?php
class TableAuthKeys {
    const TABLE_NAME        = 'AuthKeys';
    
    const COL_ID            = 'idAuthKeys';
    const COL_AUTH_KEY      = 'AuthKey';
    const COL_USERNAME      = 'username';
    const COL_EXPIRATION    = 'ExpireDate';

    static function show_create_table() {
        return 'CREATE TABLE '.self::TABLE_NAME.' ('
            .self::COL_ID            .' int(11) NOT NULL AUTO_INCREMENT, '
            .self::COL_AUTH_KEY      .' varchar(45) DEFAULT NULL, '
            .self::COL_USERNAME      .' varchar(45) DEFAULT NULL, '
            .self::COL_EXPIRATION    .' datetime DEFAULT NULL, '
            .'PRIMARY KEY ('.self::COL_ID.') '
            .');';
    }
}
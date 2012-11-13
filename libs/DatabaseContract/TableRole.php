<?php
class TableRole {
    const TABLE_NAME                = 'role';
    
    const COL_ID                    = 'role_id';
    const COL_ROLE                  = 'role';
    const COL_EDITS_PER_DAY         = 'edits_per_day';
    const COL_EDIT_TOP_LINKS        = 'edit_top_links';
    const COL_FLAG_FOR_REMOVAL      = 'flag_for_removal';
    const COL_APPROVE_REMOVAL       = 'approve_removal';
    const COL_UNREMOVE              = 'unremove';
    const COL_CHANGE_MEMBER_ROLES   = 'change_member_roles';
    const COL_MANAGE_ROLES          = 'manage_roles';

    static function show_create_table() {
        return 'CREATE TABLE '.self::TABLE_NAME.' ('
            .self::COL_ID                   .' int(10) unsigned NOT NULL AUTO_INCREMENT, '
            .self::COL_ROLE                 .' varchar(20) NOT NULL, '
            .self::COL_EDITS_PER_DAY        .' int(10) unsigned NOT NULL, '
            .self::COL_EDIT_TOP_LINKS       .' tinyint(1) NOT NULL, '
            .self::COL_FLAG_FOR_REMOVAL     .' tinyint(1) NOT NULL, '
            .self::COL_APPROVE_REMOVAL      .' tinyint(1) NOT NULL, '
            .self::COL_UNREMOVE             .' tinyint(1) NOT NULL, '
            .self::COL_CHANGE_MEMBER_ROLES  .' tinyint(1) NOT NULL, '
            .self::COL_MANAGE_ROLES         .' tinyint(1) NOT NULL, '
            .'PRIMARY KEY ('.self::COL_ID.') '
            .');';
     }
}
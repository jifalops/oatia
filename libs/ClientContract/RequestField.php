<?php
    /**
     * Fields that can be in an HTTP request understood by the server.
     */
    class RequestField {

        /*
         * HTTP POST fields
         */

        // Except for the hash, everything else is embedded inside the request field.
        const REQUEST                   = "request";
        const HASH                      = "hash";


        /*
         * Fields shared between request types
         */

        // All requests share these
        // The auth key may be empty for login requests
        const REQUEST_TYPE              = "request_type";
        const AUTH_KEY                  = "auth_key";

        // Endless list types of requests use this to get the next x results.
        const   START_POSITION          = "start_position";

        // Address/location fields
        const ADDRESS                   = "address";
        const CITY                      = "city";
        const STATE                     = "state";
        const ZIP                       = "zip";
        const LATITUDE                  = "latitude";
        const LONGITUDE                 = "longitude";

        // Common contact fields.
        const EMAIL                     = "email";
        const EMAIL1                    = "email1";
        const EMAIL2                    = "email2";
        const PHONE1                    = "phone1";
        const PHONE2                    = "phone2";

        // Other common fields
        const REMOVE                    = "remove";
        const REMOVE_APPROVED           = "remove_approved";
        const MODIFIED_BY               = "modified_by";
        const MODIFIED_TIME             = "modified_time";


        /*
         * Request-type-specific fields
         */

        // Specialty fields
        const SPECIALTY_ID              = "specialty_id";
        const SPECIALTY                 = "specialty";

        // Organization
        const ORGANIZATION_ID           = "organization_id";
        const ORGANIZATION              = "organization";

        // Organization Location
        const LOCATION_ID               = "location_id";
        const PRIMARY                   = "primary_location";

        // User Flags
        const FLAG_ID                   = "flag_id";
        const FLAG_BY                   = "flag_by";
        const FLAG_TIME                 = "flag_time";
        const FLAG_COMMENT              = "flag_comment";
        const VERDICT                   = "verdict";
        const VERDICT_BY                = "verdict_by";
        const VERDICT_TIME              = "verdict_time";
        const VERDICT_COMMENT           = "verdict_comment";

        // Hyperlinks
        const LINK_ID                   = "link_id";
        const LINK                      = "link";
        const LINK_TEXT                 = "link_text";
        const TOP_LEVEL                 = "link_top_level";

        // Member
        const MEMBER_ID                 = "member_id";        
        const USERNAME                  = "username";
        const PASSWORD                  = "password";
        const LOCATION_TIME             = "location_time";
        const EDITS                     = "edits";
        const EDITS_TODAY               = "edits_today";
        const LOGINS                    = "logins";
        const LOGIN_TIME                = "login_time";
        const EXPIRE_DATE               = "expire_date";
        const DISCLAIMER_ACCEPT         = "disclaimer_accept";

        // Person
        const PERSON_ID                 = "person_id";
        const FIRST_NAME                = "first_name";
        const LAST_NAME                 = "last_name";
        const NOTES                     = "notes";

        // Role
        const ROLE_ID                   = "role_id";
        const ROLE                      = "role";
        const DAILY_EDITS               = "daily_edits";
        const EDIT_TOP_LINKS            = "edit_top_links";
        const FLAG_FOR_REMOVAL          = "flag_for_removal";
        const APPROVE_REMOVAL           = "approve_removal";
        const UNREMOVE                  = "unremove";
        const CHANGE_MEMBER_ROLES       = "change_member_roles";
        const MANAGE_ROLES              = "manage_roles";

        // Search
        const SEARCH_TERMS              = "search_terms";
    }
<?php    
    /**
     * The types of requests the app can make
     */
    class RequestType {
        
        const LOGIN                             = "login";
        const CHECK_IN                          = "check_in";

        const DETAIL_PERSON                     = "detail_person";
        const DETAIL_LOCATION                   = "detail_location";

        const LIST_SPECIALTIES                  = "list_specialties";
        const LIST_MAP                          = "list_coordinates";
        const LIST_ORGANIZATIONS                = "list_organizations";
        const LIST_LINKS                        = "list_links";
        const LIST_PENDING_FLAGS                = "list_pending_flags";
        const LIST_ROLES                        = "list_roles";
        const LIST_PEOPLE_BY_SPECIALTY          = "list_people_by_specialty";
        const LIST_LOCATIONS_BY_ORGANIZATION    = "list_locations";
        const LIST_APPROVED_FLAGS               = "list_approved_flags";
        // TODO update documentation
        const LIST_LAST_EDITED_BY_MEMBER        = "list_last_edited_by_member";

        const SEARCH_ALL                        = "search_all";
        const SEARCH_SPECIALTIES                = "search_specialties";
        const SEARCH_MAP                        = "search_map";
        const SEARCH_ORGANIZATIONS              = "search_organizations";
        const SEARCH_LINKS                      = "search_links";
        const SEARCH_PEOPLE_BY_SPECIALTY        = "search_people_by_specialty";
        const SEARCH_LOCATIONS_BY_ORGANIZATION  = "search_locations";
        const SEARCH_PENDING_FLAGS              = "search_pending_flags";
        const SEARCH_APPROVED_FLAGS             = "search_approved_flags";

        const CREATE_PERSON                     = "create_person";
        const CREATE_SPECIALTY                  = "create_specialty";
        const CREATE_ORGANIZATION               = "create_organization";
        const CREATE_LINK                       = "create_link";
        const CREATE_LOCATION                   = "create_location";
        const CREATE_FLAG                       = "create_flag";

        const EDIT_SPECIALTY                    = "edit_specialty";
        const EDIT_ORGANIZATION                 = "edit_organization";
        const EDIT_LINK                         = "edit_link";
        const EDIT_PERSON                       = "edit_person";
        const EDIT_LOCATION                     = "edit_location";
        const EDIT_FLAG                         = "edit_flag";
        const EDIT_ROLE                         = "edit_role";
    }
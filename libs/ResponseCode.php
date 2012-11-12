<?php
    /**
     * Response codes given by the server
     */
    class ResponseCode {
        const OK                            = 0;

        // Business logic errors
        const INVALID_USERNAME              = 10;
        const INVALID_EMAIL                 = 11;
        const INVALID_PASSWORD              = 12;
        const INVALID_REQUEST_TYPE          = 13;
        const INVALID_COORD_BOX             = 14;
        const PERMISSION_DENIED             = 15;
        const INVALID_AUTH_KEY              = 16;
        const EXPIRED_AUTH_KEY              = 17;
        const INVALID_SEARCH_TYPE           = 18;
        const EDIT_LIMIT_REACHED            = 19;
        const ACCOUNT_SUSPENDED             = 20;

        // General database errors
        const INVALID_ID                    = 100;

        // Database read errors
        const NO_RESULTS                    = 110;
        const START_PAST_END                = 111;
        const PERSON_WITHOUT_MEMBER         = 112;

        // Database write errors
        const UNKNOWN_FIELD                 = 120;
        const INSUFFICIENT_DATA             = 121;
        const INVALID_DATA_TYPE             = 122;
        const INVALID_DATA_COMBINATION      = 123;

        // Other errors
        const UNKNOWN_ERROR                 = 900;
        const OUT_OF_MEMORY                 = 901;
    }
<?php
    /**
     * Fields that can be in a response understood by the app.
     */
    class ResponseField {
         // All responses must have these
        const RESPONSE_CODE             = "response_code";
        const RESPONSE_MESSAGE          = "response_message";
        // Send this array even if results are empty
        const RESULTS                   = "results";
    }
<?php    
    $dont_show_navigation = true;
	require_once('header.php');
	
	class Fields {
    	// HTTP Post variable names
        const REQUEST 						= 'request';
        const HASH   			 				= 'hash';
    
        // All requests share these
        const REQUEST_TYPE 					= 'request_type';
        const SESSION_ID     					= 'session_id';
    
        // Valid request types
        const TYPE_LOGIN       				= 'login';
        const TYPE_CHECK_IN       			= 'check_in';
    
        const TYPE_DETAIL_PERSON   			= 'detail_person';
        const TYPE_DETAIL_LOCATION    		= 'detail_location';
    
        const TYPE_LIST_SPECIALTIES 			= 'list_specialties';
        const TYPE_LIST_COORDINATES    		= 'list_coordinates';
        const TYPE_LIST_ORGANIZATIONS   		= 'list_organizations';
        const TYPE_LIST_LINKS    				= 'list_links';
        const TYPE_LIST_PENDING_FLAGS   	 	= 'list_pending_flags';
        const TYPE_LIST_ROLES    				= 'list_roles';
        const TYPE_LIST_PEOPLE_BY_SPECIALTY 	= 'list_people_by_specialty';
        const TYPE_LIST_LOCATIONS     		= 'list_locations';
        const TYPE_LIST_APPROVED_FLAGS  		= 'list_approved_flags';
    
        const TYPE_SEARCH_ALL      			= 'search_all';
        const TYPE_SEARCH_SPECIALTIES 		= 'search_specialties';
        const TYPE_SEARCH_MAP      			= 'search_map';
        const TYPE_SEARCH_ORGANIZATIONS    	= 'search_organizations';
        const TYPE_SEARCH_LINKS               = 'search_links';
        const TYPE_SEARCH_PEOPLE_BY_SPECIALTY	= 'search_people_by_specialty';
        const TYPE_SEARCH_locations     		= 'search_locations';
        const TYPE_SEARCH_PENDING_FLAGS    	= 'search_pending_flags';
        const TYPE_SEARCH_APPROVED_FLAGS 		= 'search_approved_flags';
    
        const TYPE_CREATE_PERSON    			= 'create_person';
        const TYPE_CREATE_SPECIALTY  			= 'create_specialty';
        const TYPE_CREATE_ORGANIZATION 		= 'create_organization';
        const TYPE_CREATE_LINK   				= 'create_link';
        const TYPE_CREATE_LOCATION   			= 'create_location';
        const TYPE_CREATE_FLAG   				= 'create_flag';
    
        const TYPE_EDIT_SPECIALTY  			= 'edit_specialty';
        const TYPE_EDIT_ORGANIZATION 			= 'edit_organization';
        const TYPE_EDIT_LINK   				= 'edit_link';
        const TYPE_EDIT_PERSON   				= 'edit_person';
        const TYPE_EDIT_LOCATION    			= 'edit_location';
        const TYPE_EDIT_FLAG     				= 'edit_flag';
        const TYPE_EDIT_ROLE      	 		= 'edit_role';
    
        // Several request types use this
        const	START_POSITION 					= 'start_position';

        // Address/location fields
        const ADDRESS  						= 'address';
        const CITY  							= 'city';
        const STATE  							= 'state';
        const ZIP  							= 'zip';
        const LATITUDE  						= 'latitude';
        const LONGITUDE  						= 'longitude';
    
        // Common contact fields. Watch out for numbered vs not-numbered (email vs email1)
        const EMAIL							= 'email';
        const EMAIL1  						= 'email1';
        const EMAIL2  						= 'email2';
        const PHONE  							= 'phone';
        const PHONE1  						= 'phone1';
        const PHONE2  						= 'phone2';
    
        // Common flag fields
        const REMOVE  						= 'remove';
        const REMOVE_APPROVED 				= 'remove_approved';
    
        // Other fields
        const SPECIALTY_ID 					= 'specialty_id';
        const SPECIALTY 						= 'specialty';
    
        // Shared response variable names
        const RESPONSE_CODE  					= 'response_code';
        const RESPONSE_MESSAGE   				= 'response_message';
        const RESULTS   						= 'results';
    
        const 	RESPONSE_CODE_OK					= 0;
	}
	
	

	// POST request
    $json = stripslashes($_POST['request']);
    $hash = $_POST['hash'];

	// Process request
    $request = json_decode($json, true);
    $hash_req = Secure::hash($json);
    
    if ($request[Fields::REQUEST_TYPE] == Fields::TYPE_LIST_SPECIALTIES) {
        $sql = 'SELECT * FROM `specialty`';
        
        $db->connect();
        
        $results = $db->get_records($sql);
        
        $response[Fields::RESULTS] = $results;
        
        $response[Fields::RESPONSE_CODE] = Fields::RESPONSE_CODE_OK;
        $response[Fields::RESPONSE_MESSAGE] = $hash == $hash_req;
        
        $response = json_encode($response);
	    $hash_resp = Secure::hash($response);
    
        // Format for app
        $output = $response . "\n" . $hash_resp . "\n";
        
        $output .= NL.'<br /><br />(above is what will be interpretted by the app, it ignores the rest of this)'.NL;
    
        $output .= 
        	  '<h3><u>POST Request</u></h3>' 				. $json
       		. '<h4>POST Request as array</h4>' 				. nl2br(print_r($request, true))
       		. '<h4>POST Hash</h4>' 							. $hash
       		. '<h4>Hash of Request</h4>' 					. $hash_req
       		. '<h3><u>Response (same as request)</u></h3>'  . $response 
       		. '<h4>Hash of Response</h4>' 					. $hash_resp
       		. '<h3><u>Full contents of $_POST</u></h3>'	 	. nl2br(print_r($_POST, true));    
    
        echo $output;
       	file_put_contents('output.html', $output);
    }
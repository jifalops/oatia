<?php    
    $dont_show_navigation = true;
	require_once('header.php');
	require_once(LIBRARY_DIR.DS.'DatabaseInterface.php');
	require_once(LIBRARY_DIR.DS.'ClientContract/RequestField.php');
    require_once(LIBRARY_DIR.DS.'ClientContract/RequestType.php');
    require_once(LIBRARY_DIR.DS.'ClientContract/ResponseCode.php');
    require_once(LIBRARY_DIR.DS.'ClientContract/ResponseField.php');
	
	// POST request
    $json = stripslashes($_POST[RequestField::REQUEST]);
    $hash = $_POST[RequestField::HASH];

	// Process request
    $request = json_decode($json, true);
    $hash_req = Secure::hash($json);

    $q = new DatabaseInterface($db);

    if (    $request[RequestField::REQUEST_TYPE] == RequestType::SEARCH_ALL) {
        $results = $q->search_all(
            $request[RequestField::SEARCH_TERMS],
            $request[RequestField::START_POSITION]
        );
    }
    elseif (    $request[RequestField::REQUEST_TYPE] == RequestType::LIST_SPECIALTIES) {
        $results = $q->specialty(
            $request[RequestField::START_POSITION]
        );
    }
    elseif ($request[RequestField::REQUEST_TYPE] == RequestType::LIST_ORGANIZATIONS) {
        $results = $q->organization(
            $request[RequestField::START_POSITION]
        );
    }
    elseif ($request[RequestField::REQUEST_TYPE] == RequestType::LIST_LINKS) {
        $results = $q->links(
            $request[RequestField::START_POSITION]
        );
    }
    elseif ($request[RequestField::REQUEST_TYPE] == RequestType::LIST_ROLES) {
        $results = $q->role(
            $request[RequestField::START_POSITION]
        );
    }
    elseif ($request[RequestField::REQUEST_TYPE] == RequestType::LIST_PEOPLE_BY_SPECIALTY) {
        $results = $q->specialty_person(
            $request[RequestField::SPECIALTY_ID],
            $request[RequestField::START_POSITION]
        );
    }
    elseif ($request[RequestField::REQUEST_TYPE] == RequestType::LIST_LOCATIONS_BY_ORGANIZATION) {
        $results = $q->organization_locations(
            $request[RequestField::ORGANIZATION_ID],
            $request[RequestField::START_POSITION]
        );
    }
    elseif ($request[RequestField::REQUEST_TYPE] == RequestType::DETAIL_PERSON) {        
        $results = $q->personDetail(
            $request[RequestField::PERSON_ID]
        );
    }
    elseif ($request[RequestField::REQUEST_TYPE] == RequestType::CREATE_PERSON) {                
        $results = $q->create_person(
            $request[RequestField::FIRST_NAME],
            $request[RequestField::LAST_NAME],
            $request[RequestField::ADRESS],
            $request[RequestField::CITY],
            $request[RequestField::STATE],
            $request[RequestField::ZIP],
            $request[RequestField::PHONE1],
            $request[RequestField::LATITUDE],
            $request[RequestField::LONGITUDE],
            $request[RequestField::EMAIL],
            $request[RequestField::PHONE2],
            $request[RequestField::NOTES]           
        );
    }
    elseif ($request[RequestField::REQUEST_TYPE] == RequestType::CREATE_SPECIALTY) {                
        $results = $q->create_specialty(
            $request[RequestField::SPECIALTY]
        );
    }
    elseif ($request[RequestField::REQUEST_TYPE] == RequestType::CREATE_ORGANIZATION) {                
        $results = $q->create_organization(
            $request[RequestField::ORGANIZATION]
        );
    }
    elseif ($request[RequestField::REQUEST_TYPE] == RequestType::CREATE_LINK) {                
        $results = $q->create_link(
            $request[RequestField::ROLE_ID],
            $request[RequestField::LINK],
            $request[RequestField::LINK_TEXT],
            $request[RequestField::TOP_LEVEL],
            $request[RequestField::ORGANIZATION_ID],
            $request[RequestField::LOCATION_ID],
            $request[RequestField::MEMBER_ID],
            $request[RequestField::PERSON_ID],
            $request[RequestField::SPECIALTY_ID]          
        );
    }
    elseif ($request[RequestField::REQUEST_TYPE] == RequestType::CREATE_LOCATION) {                
        $results = $q->create_location(
            $request[RequestField::ORGANIZATION_ID],
            $request[RequestField::PRIMARY],
            $request[RequestField::ADRESS],
            $request[RequestField::CITY],
            $request[RequestField::STATE],
            $request[RequestField::ZIP],            
            $request[RequestField::LATITUDE],
            $request[RequestField::LONGITUDE],
            $request[RequestField::EMAIL1],
            $request[RequestField::EMAIL2],
            $request[RequestField::PHONE1],
            $request[RequestField::PHONE2]           
        );
    }
    elseif ($request[RequestField::REQUEST_TYPE] == RequestType::EDIT_SPECIALTY) {                
        $results = $q->edit_specialty(
            $request[RequestField::SPECIALTY_ID],
            $request[RequestField::SPECIALTY]
        );
    }
    elseif ($request[RequestField::REQUEST_TYPE] == RequestType::EDIT_ORGANIZATION) {                
        $results = $q->edit_organization(
            $request[RequestField::ORGANIZATION_ID],
            $request[RequestField::ORGANIZATION]
        );
    }
    elseif ($request[RequestField::REQUEST_TYPE] == RequestType::EDIT_LINK) {                
        $results = $q->edit_link(
            $request[RequestField::LINK_ID],
            $request[RequestField::ROLE_ID],
            $request[RequestField::LINK],
            $request[RequestField::LINK_TEXT],
            $request[RequestField::TOP_LEVEL],
            $request[RequestField::ORGANIZATION_ID],
            $request[RequestField::LOCATION_ID],
            $request[RequestField::MEMBER_ID],
            $request[RequestField::PERSON_ID],
            $request[RequestField::SPECIALTY_ID]          
        );
    }
    elseif ($request[RequestField::REQUEST_TYPE] == RequestType::EDIT_PERSON) {                
        $results = $q->edit_person(
            $request[RequestField::PERSON_ID],
            $request[RequestField::FIRST_NAME],
            $request[RequestField::LAST_NAME],
            $request[RequestField::ADRESS],
            $request[RequestField::CITY],
            $request[RequestField::STATE],
            $request[RequestField::ZIP],
            $request[RequestField::PHONE1],
            $request[RequestField::LATITUDE],
            $request[RequestField::LONGITUDE],
            $request[RequestField::EMAIL],
            $request[RequestField::PHONE2],
            $request[RequestField::NOTES],            
            $request[RequestField::MODIFIED_BY]
        );
    }
    elseif ($request[RequestField::REQUEST_TYPE] == RequestType::EDIT_LOCATION) {                
        $results = $q->edit_organization_location(
            $request[RequestField::LOCATION_ID],
            $request[RequestField::ORGANIZATION_ID],
            $request[RequestField::PRIMARY],
            $request[RequestField::ADRESS],
            $request[RequestField::CITY],
            $request[RequestField::STATE],
            $request[RequestField::ZIP],            
            $request[RequestField::LATITUDE],
            $request[RequestField::LONGITUDE],
            $request[RequestField::EMAIL1],
            $request[RequestField::EMAIL2],
            $request[RequestField::PHONE1],
            $request[RequestField::PHONE2],    
            $request[RequestField::MODIFIED_BY]       
        );
    }
    elseif ($request[RequestField::REQUEST_TYPE] == RequestType::EDIT_FLAG) {                
        $results = $q->edit_flag(
            $request[RequestField::FLAG_ID],
            $request[RequestField::VERDICT],
            $request[RequestField::VERDICT_BY],
            $request[RequestField::VERDICT_COMMENT],
            $request[RequestField::ORGANIZATION_ID],
            $request[RequestField::LOCATION_ID],
            $request[RequestField::LINK_ID],
            $request[RequestField::MEMBER_ID],
            $request[RequestField::PERSON_ID],
            $request[RequestField::SPECIALTY_ID]
        );
    }
    elseif ($request[RequestField::REQUEST_TYPE] == RequestType::EDIT_ROLE) {                
        $results = $q->edit_role(
            $request[RequestField::ROLE_ID],
            $request[RequestField::ROLE],
            $request[RequestField::DAILY_EDITS],
            $request[RequestField::EDIT_TOP_LINKS],
            $request[RequestField::FLAG_FOR_REMOVAL],
            $request[RequestField::APPROVE_REMOVAL],
            $request[RequestField::UNREMOVE],
            $request[RequestField::CHANGE_MEMBER_ROLES],
            $request[RequestField::MANAGE_ROLES]
        );
    }
    elseif ($request[RequestField::REQUEST_TYPE] == RequestType::CREATE_FLAG) {                
        $results = $q->create_flag(
            $request[RequestField::FLAG_BY],
            $request[RequestField::FLAG_COMMENT],
            $request[RequestField::ORGANIZATION_ID],
            $request[RequestField::LOCATION_ID],
            $request[RequestField::LINK_ID],
            $request[RequestField::MEMBER_ID],
            $request[RequestField::PERSON_ID],
            $request[RequestField::SPECIALTY_ID]
        );
    }
    
    

        
    $response[ResponseField::RESULTS] = $results;

    // TODO actually check for errors    
    $response[ResponseField::RESPONSE_CODE] = ResponseCode::OK;
    $response[ResponseField::RESPONSE_MESSAGE] = "(didn't check for errors)";
        
    $response = json_encode($response);
    $hash_resp = Secure::hash($response);

    // Format for app
    $output = $response . "\n" . $hash_resp . "\n";        
    
    $output .= NL.'<br /><br />(above is what will be interpretted by the app, it ignores the rest of this)<br />'.NL;
    $output .= date('Y-m-d H:i:s O');

    $output .= 
    	  '<h3><u>POST Request</u></h3>' 				. $json
   		. '<h4>POST Request as array</h4>' 				. nl2br(print_r($request, true))
   		. '<h4>POST Hash</h4>' 							. $hash
   		. '<h4>Hash of Request</h4>' 					. $hash_req
   		. '<h3><u>Response</u></h3>'                    . $response 
   		. '<h4>Hash of Response</h4>' 					. $hash_resp
   		. '<h3><u>Full contents of $_POST</u></h3>'	 	. nl2br(print_r($_POST, true));    

    echo $output;
   	file_put_contents('output.html', $output);    
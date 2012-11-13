<?php    
    $dont_show_navigation = true;
	require_once('header.php');

	// POST request
    $json = stripslashes($_POST['request']);
    $hash = $_POST['hash'];

	// Process request
    $request = json_decode($json, true);
    $hash_req = Secure::hash($json);
    
    $db->connect(); 
    
    if ($request[RequestField::REQUEST_TYPE] == RequestType::LIST_SPECIALTIES) {
        $sql = 'SELECT * FROM `specialty`';        
    }
    elseif ($request[RequestField::REQUEST_TYPE] == RequestType::LIST_ORGANIZATIONS) {
        $sql = 'SELECT * FROM `organization`'; 
    }
    elseif ($request[RequestField::REQUEST_TYPE] == RequestType::LIST_LINKS) {
        $sql = 'SELECT * FROM `link`'; 
    }
    
        $results = $db->get_records($sql);
        
        $response[ResponseField::RESULTS] = $results;
        
        $response[ResponseField::RESPONSE_CODE] = ResponseCode::OK;
        $response[ResponseField::RESPONSE_MESSAGE] = $hash == $hash_req;
        
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
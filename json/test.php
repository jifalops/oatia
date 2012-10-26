<?php
	/* The purpose of this is to test that the client and server
	 * are getting the same results when hashing data. Steps:
	 *
	 * 1. Client creates request (a JSON string) and hash of request, sends data to server.
	 * 2. Server gets request and the client's hash of the request
	 * 3. Server does its own hash of the request (should be the same).
	 * 4. Server decodes request it into an array (represents data used to create response).
	 * 5. Server encodes the array back into a response (a JSON string) and creates a hash of the response.
	 * 6. Server echos the response string and the response hash on separate lines
	 * 7. Client recieves response and response hash, checks for validity.
	 * 
	 * All but the first and last steps are done below.
	 */

	// Header contains hashing algorithm
	$dont_show_navigation = true;
	require_once('../header.php');

	// POST request
    $json = stripslashes($_POST['request']);
    $hash = $_POST['hash'];

	// Process request
    $request = json_decode($json, true);
    $hash_req = Secure::hash($json);

    // Response
    if (!empty($request)) {
	    $response = json_encode($request);
	    $hash_resp = Secure::hash($response);
    }
    
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
    
   
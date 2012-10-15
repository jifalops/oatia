<?php
/* author: Shazad Bakhsh
 *  This file accepts all GET and POST requests. On each request it will talk to 
 *  database and will give appropriate response in JSON forma
 */
// TODO Add All Server Interactions --SSB

if (isset($_POST['tag']) && $_POST['tag'] != '') {
    // get tag
    $tag = $_POST['tag'];
 
    // include db handler
    require_once 'DBMonkey.php';
    $db = new DBMonkey();
 
    // response Array
    $response = array("tag" => $tag, "success" => 0, "error" => 0);
 
    // check for tag type
    if ($tag == 'login') {
        // Request type is check Login
        $name = $_POST['name'];
        $password = $_POST['password'];
 
        // check for user
        $user = $db->getUserByNameAndPassword($name, $password);
        if ($user != false) {
            // user found
            // echo json with success = 1
            $response["success"] = 1;
            $response["id"] = $user["person_id"];
            $response["user"] = $user["username"];
            echo json_encode($response);
        } else {
            // user not found
            // echo json with error = 1
            $response["error"] = 1;
            $response["error_msg"] = "Incorrect user name or password!";
            echo json_encode($response);
        }
	}else if ($tag == 'GetSpecialties'){	
		$temp = $db->getSpecialties();
		if ($temp != false){
			$response["success"] = 1;
			
			$tempa = array("tag" => "GetSpecialties", "success" => 1, "error" => 0, "DATA" => $temp); 
			echo json_encode($tempa);
		} else {
			$response["error"] = 1;
            $response["error_msg"] = "Getting specialties fail";
            echo json_encode($response);
		}
	}else if ($tag == 'GetAgency'){	
		$temp = $db->getAgency();
		if ($temp != false){
			$response["success"] = 1;
			
			$tempa = array("tag" => "GetAgency", "success" => 1, "error" => 0,"DATA" => $temp); 
			echo json_encode($tempa);
		} else {
			$response["error"] = 1;
            $response["error_msg"] = "Getting Agency fail";
            echo json_encode($response);
		}
	}else if ($tag == 'GetLinks'){	
		$temp = $db->getLinks();
		if ($temp != false){
			$response["success"] = 1;
			
			$tempa = array("tag" => "GetLinks", "success" => 1, "error" => 0, "DATA" => $temp); 
			echo json_encode($tempa);
		} else {
			$response["error"] = 1;
            $response["error_msg"] = "Getting links fail";
            echo json_encode($response);
		}
	}else if ($tag == 'GetPeoplebySpecialty'){	
		$specialty_id = $_POST['specialty_id'];
		$temp = $db->getPeoplebySpecialty($specialty_id);
		if ($temp != false){
			$response["success"] = 1;
			
			$tempa = array("tag" => "getPeoplebySpecialty", "success" => 1, "error" => 0, "DATA" => $temp); 
			echo json_encode($tempa);
		} else {
			$response["error"] = 1;
            $response["error_msg"] = "getting People by specialty fail";
            echo json_encode($response);
		}
    } else {
        echo "Invalid Request";
    }
} else {
    echo "Access Denied"; // What you see if you try to view the page
}

?>
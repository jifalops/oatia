<?php
/* author: Shazad Bakhsh
 *  This file accepts all GET and POST requests. On each request it will talk to 
 *  database and will give appropriate response in JSON forma
 */
// todo


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
            $response["uid"] = $user["unique_id"];
            $response["user"]["name"] = $user["name"];
            echo json_encode($response);
        } else {
            // user not found
            // echo json with error = 1
            $response["error"] = 1;
            $response["error_msg"] = "Incorrect user name or password!";
            echo json_encode($response);
        }
    } else {
        echo "Invalid Request";
    }
} else {
    echo "Access Denied"; // What you see if you try to view the page
}

?>
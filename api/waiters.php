<?php 

require_once '../db.php';

function get_all() {
	global $connection;
	$sql = "SELECT username FROM waiters";
	$stmt = $connection->prepare($sql);
	$stmt->execute();
	$waitersData = array();

	while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
		$waitersData['waiters'][] = $row;
	}

	return $waitersData;
}

function get_by_username($username) {
	global $connection;
	$sql = "SELECT * FROM waiters WHERE username=:username";
	$stmt = $connection->prepare($sql);
	$stmt->bindParam(":username", $username);
	$stmt->execute();
	$result = $stmt->fetch();
	return $result;
}

function login() {
	$json = file_get_contents('php://input');
	$data = json_decode($json);

	// json response array
	$response = array("message" => "", "code" => 200);
	 
	if (isset($data->username) && isset($data->password)) {
	 
	    // receiving the post params
	    $username = $data->username;
	    $password = $data->password;
	 
	    // get the user by username
	    $user = get_by_username($username);
	 
	    if ($user) {
	        // user is found
	    	if ($user["password"] == sha1($password)) {
	    		$response["message"] = "Successful login";
	        	$response["code"] = 200;
	        	$response["data"]["user"]["username"] = $user["username"];
	        	$response["data"]["user"]["first_name"] = $user["first_name"];
	        	$response["data"]["user"]["last_name"] = $user["last_name"];
	        	echo json_encode($response);
	    	}
	    	else {
	    		$response["code"] = 402;
	        	$response["message"] = "Password is wrong. Please try again!";
	        	echo json_encode($response);
	    	}	        
	    } 
	    else {
	        // user is not found with the credentials
	        $response["code"] = 402;
	        $response["message"] = "Username is wrong. Please try again!";
	        echo json_encode($response);
	    }
	} 
	else {
	    // required post params is missing
	    $response["code"] = 404;
	    $response["message"] = "Required parameters username or password is missing!";
	    echo json_encode($response);
	}
}

function request_usernames() {
	// json response array
	$response = array("message" => "", "code" => 200);
	$data = get_all();
	$response["data"] = $data;

	echo json_encode($response);
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	request_usernames();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	login();
}

?>
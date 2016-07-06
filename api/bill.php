<?php

$billState = ""; 

function get_state() {
	echo $GLOBALS['billState'];
}

function request_bill() {
	$json = file_get_contents('php://input');
	$data = json_decode($json);

	// json response array
	$response = array("message" => "", "code" => 200);
	 
	if (isset($data->state) && isset($data->table_id)) {
	 
	    // receiving the post params
	    $state = $data->state;
	    $table_id = $data->table_id;	 

	    if ($state == 1) {
	    	$response["message"] = "User requested to pay bill";
	        $response["code"] = 200;
	        $response["data"]["table_id"] = $table_id;
	        $GLOBALS['billState'] = json_encode($response);
	        echo json_encode($response);
	    }
	    else {
	    	$GLOBALS['billState'] = json_encode($response);	    	
	        echo json_encode($response);
	    }
	}
	else {
		$response["code"] = 404;
	    $response["message"] = "Required parameters are missing!";
	    echo json_encode($response);
	}
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	request_bill();
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	get_state();
}


 ?>
<?php 

require_once '../db.php';

function get_by_name($name) {
	global $connection;	
	$sql = "SELECT id FROM tables WHERE name=:name";
	$stmt = $connection->prepare($sql);
	$stmt->bindParam(":name", $name);
	$stmt->execute();

	$data = array();

	while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
		$data = $row;
	}

	return $data;
}

function get_all() {
	global $connection;
	$sql = "SELECT * FROM tables";
	$stmt = $connection->prepare($sql);
	$stmt->execute();
	$data = array();

	while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
		$data['tables'][] = $row;
	}

	return $data;
}

function request_table($name) {
	// json response array
	$response = array("message" => "", "code" => 200);
	$data = get_by_name($name);

	if (!$data) {
		$response["code"] = 404;
	    $response["message"] = "Required parameter name is not valid!";
	}
	else {
		$response["data"]["table"] = $data;
	}
	
	echo json_encode($response);
}

function request_tables() {
	// json response array
	$response = array("message" => "", "code" => 200);
	$data = get_all();

	$response["data"] = $data;
	
	echo json_encode($response);
}
 
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["name"])) {
	request_table($_GET["name"]);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && !isset($_GET["name"])) {
	request_tables();
}


?>
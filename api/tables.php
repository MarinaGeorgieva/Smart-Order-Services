<?php 

require_once '../db.php';

function get_by_id($id) {
	global $connection;	
	$sql = "SELECT * FROM tables WHERE id=:id";
	$stmt = $connection->prepare($sql);
	$stmt->bindParam(":id", $id);
	$stmt->execute();

	$data = array();

	while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
		$data["tables"][] = $row;
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

function request_table($id) {
	// json response array
	$response = array("message" => "", "code" => 200);
	$data = get_by_id($id);

	if (!$data) {
		$response["code"] = 404;
	    $response["message"] = "Required parameter id is not valid!";
	}
	else {
		$response["data"] = $data;
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
 
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
	request_table($_GET["id"]);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && !isset($_GET["id"])) {
	request_tables();
}


?>
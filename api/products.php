<?php 

require_once '../db.php';

function get_by_id($id) {
	global $connection;	
	$sql = "SELECT * FROM products WHERE id=:id";
	$stmt = $connection->prepare($sql);
	$stmt->bindParam(":id", $id);
	$stmt->execute();

	$data = array();

	while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
		$data["products"][] = $row;
	}

	return $data;
}

function get_all() {
	global $connection;
	$sql = "SELECT * FROM products";
	$stmt = $connection->prepare($sql);
	$stmt->execute();
	$productsData = array();

	while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
		$productsData['products'][] = $row;
	}

	return $productsData;
}

function request_product($id) {
	// json response array
	$response = array("message" => "", "code" => 200);
	$data = get_by_id($id);

	if (!$data) {
		$response["code"] = 404;
	    $response["message"] = "Required parameter id is not valid!";
	}

	$response["data"] = $data;

	
	echo json_encode($response);
}

function request_products() {
	// json response array
	$response = array("message" => "", "code" => 200);
	$data = get_all();

	$response["data"] = $data;
	
	echo json_encode($response);
}
 
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
	request_product($_GET["id"]);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && !isset($_GET["id"])) {
	request_products();
}

?>
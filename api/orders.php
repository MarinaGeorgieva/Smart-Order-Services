<?php  

require_once '../db.php';


// get order by table id -> finished
// get all orders by table id -> finished
// post order 
// put order

function total_price_per_order($id, $table_id) {
	global $connection;	
	$sql = "SELECT p.price * po.quantity as 'total' FROM orders o 
			JOIN products_orders po ON o.id=po.order_id 
			JOIN products p ON p.id=po.product_id
			WHERE o.table_id=:table_id AND o.id=:id";
	$stmt = $connection->prepare($sql);
	$stmt->bindParam(":table_id", $table_id);
	$stmt->bindParam(":id", $id);
	$stmt->execute();

	$data = array();
	while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
		$data[] = $row;
	}

	$total = 0;

	foreach ($data as $value) {
		$total += number_format((float) $value["total"], 2, '.', ''); 
	}

	return $total;
}

function get_by_id_and_table($id, $table_id) {
	global $connection;	
	$sql = "SELECT po.product_id, p.price, po.quantity FROM orders o 
			JOIN products_orders po ON o.id=po.order_id 
			JOIN products p ON p.id=po.product_id
			WHERE o.table_id=:table_id AND o.id=:id";
	$stmt = $connection->prepare($sql);
	$stmt->bindParam(":table_id", $table_id);
	$stmt->bindParam(":id", $id);
	$stmt->execute();

	$data = array();
	$data["id"] = $id;
	$data["table_id"] = $table_id;
	$data["total"] = total_price_per_order($id, $table_id);

	while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
		$data["products"][] = $row;
	}

	return $data;
}

function get_all_by_table($table_id) {
	global $connection;
	$sql = "SELECT id FROM orders WHERE table_id=:table_id";
	$stmt = $connection->prepare($sql);
	$stmt->bindParam(":table_id", $table_id);
	$stmt->execute();

	$tableIds = array();
	while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
		$tableIds[] = $row;
	}

	$data = array();

	foreach ($tableIds as $value) {
		$order = array();
		$order["id"] = $value["id"];
		$order["table_id"] = $table_id;
		$order["total"] = total_price_per_order($value["id"], $table_id);

		$sql = "SELECT po.product_id, p.price, po.quantity FROM orders o 
			JOIN products_orders po ON o.id=po.order_id 
			JOIN products p ON p.id=po.product_id
			WHERE o.table_id=:table_id AND o.id=:id";
		$stmt = $connection->prepare($sql);
		$stmt->bindParam(":table_id", $table_id);
		$stmt->bindParam(":id", $value["id"]);
		$stmt->execute();

		while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			$order["products"][] = $row;
		}

		$data["orders"][] = $order;
	}

	return $data;
}

function request_order($id, $table_id) {
	// json response array
	$response = array("message" => "", "code" => 200);
	$data = get_by_id_and_table($id, $table_id);

	if (!$data) {
		$response["code"] = 404;
	    $response["message"] = "Required parameters id and table are invalid!";
	}

	$response["data"] = $data;

	echo json_encode($response);
}

function request_orders($table_id) {
	$response = array("message" => "", "code" => 200);
	$data = get_all_by_table($table_id);

	if (!$data) {
		$response["code"] = 404;
	    $response["message"] = "Required parameter id is not valid!";
	}

	$response["data"] = $data;

	echo json_encode($response);
}

function make_order() {
	$json = file_get_contents('php://input');
	$order = json_decode($json);
	
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"]) && isset($_GET["table"])) {
	request_order($_GET["id"], $_GET["table"]);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["table"]) && !isset($_GET["id"])) {
	request_orders($_GET["table"]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	make_order();
}

?>
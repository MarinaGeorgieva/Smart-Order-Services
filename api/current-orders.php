<?php 

require_once '../db.php';

function total_price_per_order($id, $table_id) {
	global $connection;	
	$sql = "SELECT p.price * po.quantity as 'total' FROM orders o 
			JOIN products_orders po ON o.id=po.order_id 
			JOIN products p ON p.id=po.product_id
			WHERE o.table_id=:table_id AND o.id=:id AND o.state=0";
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

function get_all() {
	global $connection;
	$sql = "SELECT id, table_id FROM orders WHERE state=0";
	$stmt = $connection->prepare($sql);
	$stmt->execute();

	$resOrders = array();
	while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
		$resOrders[] = $row;
	}

	$data = array();

	foreach ($resOrders as $value) {
		$order = array();
		$order["id"] = $value["id"];
		$order["table_id"] = $value["table_id"];
		$order["total"] = total_price_per_order($value["id"], $value["table_id"]);

		$sql = "SELECT p.name, p.price, po.quantity FROM orders o 
			JOIN products_orders po ON o.id=po.order_id 
			JOIN products p ON p.id=po.product_id
			WHERE o.table_id=:table_id AND o.id=:id AND o.state=0";
		$stmt = $connection->prepare($sql);
		$stmt->bindParam(":table_id", $value["table_id"]);
		$stmt->bindParam(":id", $value["id"]);
		$stmt->execute();

		while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			$order["products"][] = $row;
		}

		$data["orders"][] = $order;
	}

	return $data;
}

function request_orders() {
	$response = array("message" => "", "code" => 200);
	$data = get_all();

	if (!$data) {
		$response["code"] = 404;
	    $response["message"] = "No orders!";
	}

	$response["data"] = $data;

	echo json_encode($response);
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	request_orders();
}

 ?>


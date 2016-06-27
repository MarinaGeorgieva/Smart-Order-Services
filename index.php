<?php 
	require_once("db.php");

	$query = "SELECT * FROM products";
	$result = $conn->query($query);
	while ($row = $result->fetch_array()) {
			echo $row["description"] . " " . $row["price"];
			echo "<br />";
	}
	echo "Hello";
?>

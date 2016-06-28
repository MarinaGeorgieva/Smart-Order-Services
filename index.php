<?php 
	require_once("db.php");
?>

<?php
	$query = "SELECT * FROM products";
	$result = $conn->query($query);
	while ($row = $result->fetch_array()) {
			echo $row["description"] . " " . $row["price"];
			echo "<br />";
	}
	echo "Hello";
?>


<?php
	 // $sql = "INSERT INTO products (description, price, category";
	// $sql .= ") VALUES ('fanta lemon', 1.70, 'drinks')";
	// $result = $conn->query($sql);
	// if($result){
		// 	echo "New record created successfully!";
	// }
	// else{	
		// 	echo "Error!" . $sql . "<br />" . $conn->error;
	// }

?>
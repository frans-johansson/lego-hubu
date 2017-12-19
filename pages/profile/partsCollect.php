<?php

	// Testa om det går bra att koppla upp mot databasen
	$connection = mysqli_connect("mysql.itn.liu.se","lego","","lego");

		
	if (!$connection) {
		echo "Error: Unable to connect to MySQL." . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_errno() . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
		exit;
	}

	// Fråga för att få fram antalet satser som finns
	$result = mysqli_query($connection, "SELECT SUM(inventory.Quantity*collection.Quantity) FROM inventory, collection
										 WHERE inventory.SetID = collection.SetID");
	
	
	while($row = mysqli_fetch_array($result)) {
		$Parts = $row["SUM(inventory.Quantity*collection.Quantity)"];
		
		print "<p>$Parts</p>";
	}
	
	mysqli_close($connection);
	
?>
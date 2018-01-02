<?php

	// Testa om det går bra att koppla upp mot databasen
	$connection = mysqli_connect("mysql.itn.liu.se","lego","","lego");

    // Om uppkopplingen inte fungerade, lämna ett felmeddelande
	if (!$connection) {
		echo "Error: Unable to connect to MySQL." . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_errno() . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
		exit;
	}

	// Fråga för att få fram antalet bitar som finns i samlingen
	$result = mysqli_query($connection, "SELECT SUM(inventory.Quantity*collection.Quantity) FROM inventory, collection
										 WHERE inventory.SetID = collection.SetID");


	// Medan ett resultat finns, skriv ut detta
	while($row = mysqli_fetch_array($result)) {
	    // Lägg antalet bitar i en variabel
		$Parts = $row["SUM(inventory.Quantity*collection.Quantity)"];

		// Skriv ut antalet bitar
		print "<p>$Parts</p>";
	}

	// Stäng kopplingen
	mysqli_close($connection);

?>

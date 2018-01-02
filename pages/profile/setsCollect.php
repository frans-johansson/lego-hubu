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


	// Fråga för att få fram antalet satser som finns i samlingen
	$result = mysqli_query($connection, "SELECT SUM(Quantity) FROM collection");


	// Medan ett resultat finns, skriv ut detta
	while($row = mysqli_fetch_array($result)) {
	    // Lägg antalet satser i en variabel
		$Sets = $row["SUM(Quantity)"];

		// Skriv ut antalet satser
		print "<p>$Sets</p>";
	}


	// Stäng kopplingen
	mysqli_close($connection);

?>

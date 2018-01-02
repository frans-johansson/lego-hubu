<?php

	// Testa om det går bra att koppla upp mot databasen
	$connection = mysqli_connect("mysql.itn.liu.se","lego","","lego");

    // Om kopplingen inte fungerar, ge ett felmeddelande
	if (!$connection) {
		echo "Error: Unable to connect to MySQL." . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_errno() . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
		exit;
	}

	// Fråga till databasen för att få fram antalet satser som finns
	$result = mysqli_query($connection, "SELECT COUNT(SetID) FROM sets"); // DISTINCT behövs ej eftersom varje SetID bara förekommer en gång i tabellen sets


	// Medan det finns resultat, skriv ut dem
	while($row = mysqli_fetch_array($result)) {
	    // Spara antalet satser i en variabel
		$Sets = $row["COUNT(SetID)"];

		// Skriv ut antalet satser
		print "<p>$Sets</p>";
	}

	// Stäng kopplingen
	mysqli_close($connection);

?>

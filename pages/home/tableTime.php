<?php

	// Testa om det går bra att koppla upp mot databasen
	$connection = mysqli_connect("mysql.itn.liu.se","lego","","lego");

    // Om uppkopplingen misslyckades, ge ett felmeddelande
	if (!$connection) {
		echo "Error: Unable to connect to MySQL." . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_errno() . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
		exit;
	}

	// Fråga för att få fram antalet nya satser för varje år
	$result = mysqli_query($connection, "SELECT Year, COUNT(SetID) FROM sets
						GROUP BY Year ORDER BY Year ASC");
    // DISTINCT behövs ej eftersom varje SetID bara förekommer en gång i tabellen sets


	// Medan det finns resultat, skriv ut detta
	while($row = mysqli_fetch_array($result)) {
	    // Spara året och antalet satser i varsin variabel
		$Year = $row["Year"];
		$Quantity = $row["COUNT(SetID)"];

		// Skriv ut året och antalet satser
		print "<tr><td>$Year</td><td>$Quantity</td></tr>";
	}

	// Stäng kopplingen
	mysqli_close($connection);

?>

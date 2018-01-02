<?php

	// Testa om det går bra att koppla upp mot databasen
	$connection = mysqli_connect("mysql.itn.liu.se","lego","","lego");

    // Om uppkopplingen misslyckas, ge ett felmeddelande
	if (!$connection) {
		echo "Error: Unable to connect to MySQL." . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_errno() . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
		exit;
	}

	// Fråga till databasen för att få fram antalet bitar som finns
	$result = mysqli_query($connection, "SELECT COUNT(PartID) FROM parts"); // DISTINCT behövs ej eftersom varje PartID bara förekommer en gång i tabellen parts

	// Medan det finns resultat, skriv ut detta
	while($row = mysqli_fetch_array($result)) {
	    // Spara antalet bitar i en variabel
		$Parts = $row["COUNT(DISTINCT PartID)"];

        // Skriv ut antalet bitar
		print "<p>$Parts</p>";
	}

    // Stäng kopplingen
	mysqli_close($connection);

?>

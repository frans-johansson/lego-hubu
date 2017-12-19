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
	$result = mysqli_query($connection, "SELECT COUNT(SetID) FROM sets");
	
	
	while($row = mysqli_fetch_array($result)) {
		$Sets = $row["COUNT(SetID)"];
		
		print "<p>$Sets</p>";
	}
	
	mysqli_close($connection);
	
?>
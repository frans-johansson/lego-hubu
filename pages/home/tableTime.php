<?php

	// Testa om det går bra att koppla upp mot databasen
	$connection = mysqli_connect("mysql.itn.liu.se","lego","","lego");
		
	if (!$connection) {
		echo "Error: Unable to connect to MySQL." . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_errno() . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
		exit;
	}

	// Fråga för att få fram antalet nya satser för varje år
	$result = mysqli_query($connection, "SELECT Year, COUNT(SetID) FROM sets 
						GROUP BY Year ORDER BY Year ASC");
	
	
	while($row = mysqli_fetch_array($result)) {
		$Year = $row["Year"];
		$Quantity = $row["COUNT(SetID)"];
		
		print "<tr><td>$Year</td><td>$Quantity</td></tr>";
	}
	
	mysqli_close($connection);
	
?>
<?php

// Koppla upp mot databasen
    include "searchEngine/connect.php";

// Skapa frågan till topplistan över antalet satser med flest bitar
// Vi valde LIMIT 10 så att vi får en topp tio-lista
	$searchQuery = "SELECT Setname, SUM(inventory.Quantity) FROM sets, inventory 
					WHERE ItemTypeID = 'P' AND sets.SetID = inventory.SetID GROUP BY sets.SetID
					ORDER BY SUM(inventory.Quantity) DESC LIMIT 10";

/* Påbörja tabellen och skriv ut tabellhuvudena
Tabellen ligger i en div och första tabellhuvudet spänner över två kolonner,
en för vilket nummer satsen ligger på och en för namnet*/
	print "<div class='toplist' id='setsList'>
			<h2>Top ten list over the largest sets Lego has released according to our database</h2>
			<table>
				<tr>
					<th colspan=2>Setname</th>
					<th>Number of parts</th>
				</tr>";
		
// Denna variabel används för att skriva ut vilken placering i topplistan satsen har		
	$toplistRow = 1;
					
// Ställ frågan till databasen
	$result	= mysqli_query($connection, "$searchQuery");
	
	
// Medan det finns ett resultat, skriv ut detta
	while($row = mysqli_fetch_array($result)) {
		// Lägg informationen som ska visas i separata variabler
			$name = $row["Setname"];
			$quantity = $row["SUM(inventory.Quantity)"];
			
		// Skriv ut placeringsnumret, namnet och antalet bitar satsen består av
			print 	"<tr>
						<td>$toplistRow</td>
						<td>$name</td>
						<td>$quantity</td>
					</tr>";
					
		// Öka variabeln med ett så att nästa sats visas med det efterföljande placeringsnumret
			$toplistRow += 1;
	}
	
// Avsluta tabellen och diven
	print "</table></div>";

	
// Stäng kopplingen till databasen
    mysqli_close($connection);

?>


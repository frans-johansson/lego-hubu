<?php



// Koppla upp mot databasen
    include "searchEngine/connect.php";

// Skapa frågan till topplistan över antalet satser med flest bitar 
	$searchQuery = "SELECT Setname, SUM(inventory.Quantity) FROM sets, inventory 
					WHERE ItemTypeID = 'P' AND sets.SetID = inventory.SetID GROUP BY sets.SetID
					ORDER BY SUM(inventory.Quantity) DESC LIMIT 10";

	print "<div class='toplist' id='setsList'>
			<table>
				<tr>
					<th colspan=2>Setname</th>
					<th>Number of parts</th>
				</tr>";
				
	$toplistRow = 1;
					
// Ställ frågan till databasen
	$result	= mysqli_query($connection, "$searchQuery");
	
	
// Medan det finns ett resultat, skriv ut detta
	while($row = mysqli_fetch_array($result)) {
		// Lägg informationen som ska visas i separata variabler
			$name = $row["Setname"];
			$quantity = $row["SUM(inventory.Quantity)"];
			
			print 	"<tr>
						<td>$toplistRow</td>
						<td>$name</td>
						<td>$quantity</td>
					<tr>";
					
			$toplistRow += 1;
	}
	
	print "</div>";

	// Skapa frågan till databasen	
		$searchQuery = "SELECT Partname, COUNT(DISTINCT inventory.SetID)
							FROM parts, inventory, sets WHERE PartID = ItemID AND
							ItemTypeID = 'P' AND inventory.SetID = sets.SetID 
							GROUP BY PartID ORDER BY COUNT(DISTINCT inventory.SetID) DESC LIMIT 10";
							
		print "<div class='toplist' id='partsList'>
			<table>
				<tr>
					<th colspan=2>Partname</th>
					<th>Included in sets</th>
				</tr>";
				
	$toplistRow = 1;
					
// Ställ frågan till databasen
	$result	= mysqli_query($connection, "$searchQuery");
	
// Medan det finns ett resultat, skriv ut detta
	while($row = mysqli_fetch_array($result)) {
		// Lägg informationen som ska visas i separata variabler
			$name = $row["Partname"];
			$quantity = $row["COUNT(DISTINCT inventory.SetID)"];
			
			print 	"<tr>
						<td>$toplistRow</td>
						<td>$name</td>
						<td>$quantity</td>
					<tr>";
					
			$toplistRow += 1;
	}
								
	// Stänger kopplingen till databasen
        mysqli_close($connection);

?>


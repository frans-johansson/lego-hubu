<?php
// Koppla till databasen
include 'connect.php';


inventory.SetID = sets.SetID = collection.SetID

inventory.ItemID = sets.SetID = parts.PartID = minifigs.MinifigID = images.ItemID

Antal satser:
	Count funktion för distinkta sets.SetID
	SELECT Count(SetID) FROM sets;
	
	$row = mysqli_query($connection, "SELECT Count(SetID) FROM sets");

Summera alla bitar i ett set:

	// Lägg till Limits senare <3 Stegu!
	$row = mysqli_query($connection, "SELECT SetID, SUM(Quantity) FROM inventory WHERE ItemTypeID = 'P' 
										GROUP BY SetID ORDER BY SUM(Quantity) DESC LIMIT 20");

	
Hur många satser enskilda bitar varit del av och sortera efter det:
	//SELECT COUNT(SetID) FROM inventory WHERE ItemID = ' ' LIMIT $i

	$row = mysqli_query($connection, "SELECT ItemID, COUNT(DISTINCT SetID) FROM inventory 
	WHERE ItemTypeID = 'P' GROUP BY ItemID ORDER BY COUNT(DISTINCT SetID) DESC LIMIT 20")
	
/*	
	SELECT SUM(Quantity) FROM inventory WHERE inventory.SetID = ''
	
	$setAmount = mysqli_query($connection, "SELECT COUNT(SetID) FROM sets");
	
	for($i = 0; $i < $setAmount[0]-1; $i++) {
		$setID = mysqli_query($connection, "SELECT SetID FROM sets LIMIT $i, 1");
	 
		$row = mysqli_query($connection, "SELECT SUM(Quantity) FROM inventory WHERE SetID = '$setID'");
	
		// Fortsätt här när listan ska göras, displaya detta utan att spara?
	}
	

	$partAmount = mysqli_query($connection, "SELECT COUNT(PartID) FROM parts");
	for($i = 0; $i < $partAmount[0]-1; $i++) {
		$itemID = mysqli_query($connection, "SELECT PartID FROM parts LIMIT $i, 1");
		$row = mysqli_query($connection, "SELECT COUNT(SetID) FROM inventory WHERE ItemID = '$itemID'" );
		
		if($i < 10) {
			$top10[$i] = $row;
		} else {
			// Sorteringsfunktion för att sortera i storleksordning
			for ($pass = 1; $pass < 10; $pass++) {
				for ($k = 0; k < 9; $k++) {
					if ($top10[k] > $top10[k + 1]) {
							$temp = $top10[k];
							$top10[k] = $top10[k+1];
							$top10[k+1] = $temp;
					}
				}
			}
				if($row > $top10[0]) {
					$top10[0] = $row;
				}
		}
	}
	
	for ($pass = 1; $pass < 10; $pass++) {
			for ($k = 0; k < 9; $k++) {
				if ($top10[k] > $top10[k + 1]) {
						$temp = $top10[k];
						$top10[k] = $top10[k+1];
						$top10[k+1] = $temp;
				}
			}
		}
*/
	
Hur många olika bitar finns det.
	SELECT DISTINCT COUNT(PartID) FROM parts
	
	$row = mysqli_query($connection, "SELECT DISTINCT COUNT(PartID) FROM parts");
	
Antalet nya satser per år
	
	Första året är 1949
	
	// Sorterar antalet satser utgivna per år sorterat efter år
	$row = mysqli_query($connection, "SELECT Year, COUNT(SetID) FROM sets 
						GROUP BY Year ORDER BY Year ASC LIMIT 20")
	
	
Antalet satser i samling
	
	$row = mysqli_query($connection, "SELECT SUM(Quantity) FROM collection");
	
Antalet bitar i samling	
	
	// Beräknar det sammanlagda antalet bitar som ingår i personens samling
	$row = mysqli_query($connection, "SELECT SUM(inventory.Quantity*collection.Quantity) 
				FROM inventory, collection WHERE inventory.SetID = collection.SetID ");
										
	
Totala Quantity för en bit

	// Det totala antalet bitar i alla sets den ingår i
	$row = mysqli_query($connection, "SELECT ItemID, SUM(inventory.Quantity) 
						FROM inventory WHERE ItemtypeID = 'P' GROUP BY ItemID 
						ORDER BY SUM(inventory.Quantity) DESC LIMIT 10");
	
Satser med flest bitar av en visst sort

	$itemID = // Biten som sökes på

	// Sök på en specifik bit och få fram i vilka satser det finns flest/minst utav den
	$row = mysqli_query($connection, "SELECT SetID, SUM(inventory.Quantity) FROM inventory WHERE ItemID = '$itemID' 
								AND ItemtypeID = 'P' GROUP BY SetID ORDER BY SUM(inventory.Quantity) DESC LIMIT 20");
	
Satser med flest bitar av en viss färg
	SELECT SUM(Quantity) FROM inventory WHERE ColorID = ' ' ORDER BY Quantity
	
	$color = // Färg som sökes efter
	
	// Ger lista över de satser med flest antal bitar i den givna färgen
	$row = mysqli_query($connection, "SELECT SetID, SUM(Quantity) FROM inventory, colors 
					WHERE Colorname = '$color' AND colors.ColorID = inventory.ColorID 
					AND ItemtypeID = 'P' GROUP BY SetID ORDER BY SUM(Quantity) DESC LIMIT 20");
	
Bitar efter år av ursprung
	
	
	$row = mysqli_query($connection, "SELECT DISTINCT parts.Partname, sets.Year FROM parts, sets, inventory
	WHERE ItemID = PartID AND sets.SetID = inventory.SetID ORDER BY Year ASC LIMIT 10");

/*
$lowerLimit;
$showAmount;
	
$query = basfråga desc limit;

$row = mysqli_query($connection, );

if (collection.quantity > 1)
{
	$name = collection.SetID;
	SELECT Quantity FROM inventory WHERE '$name' = inventory.SetID;
	
}





if($distinct) {
	$distinctBoolRes = 'DISTINCT';
} else {
	$distinctBoolRes = ' ';
}

' SELECT ' . $distinctBoolRes . ' ' . $columnSelect . ' FROM ' . $table . ' WHERE ' . $condition .
' ORDER BY ' . $columnOrder . ' ' . $ascOrDesc . ' LIMIT ' . $lowerLimit . ' , ' . $showAmount

ehco 'testing testing';





*/




?>





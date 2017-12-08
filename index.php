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
	SELECT SUM(Quantity) FROM inventory WHERE inventory.SetID = ''
	
	$setID = "nånting";
	
	$row = mysqli_query($connection, "SELECT SUM(Quantity) FROM inventory WHERE inventory.SetID = '$setID'");

Hur många satser enskilda bitar varit del av och sortera efter det:
	//SELECT COUNT(SetID) FROM inventory WHERE ItemID = ' ' LIMIT $i
	
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
	
	
	
	
	
	
	$top10 = {0};

	$itemID = "";
	
	$row = mysqli_query($connection, "SELECT COUNT(SetID) FROM inventory WHERE ItemID = '$itemID'" );
	
Hur många olika bitar finns det.
	SELECT DISTINCT COUNT(PartID) FROM parts
	
	$row = mysqli_query($connection, );
	
Antalet nya satser per år
	SELECT DISTINCT COUNT(SetID) FROM sets WHERE Year = ''
	
	Första året är 1949
	
	$row = mysqli_query($connection, );
	
Antalet satser i samling
	SELECT SUM(Quantity) FROM collection
	
	$row = mysqli_query($connection, );
	
Antalet bitar i samling
	SELECT SetID FROM collection
	
	SELECT SUM(inventory.Quantity) FROM inventory, collection WHERE inventory.SetID = collection.SetID
	
	loopa men räkna bara med de som har quantity i+1
	
	$row = mysqli_query($connection, );
	
Totala Quantity för en bit
	SELECT SUM(Quantity) FROM inventory WHERE ItemID = ' '
	
	$row = mysqli_query($connection, );
	
Satser med flest bitar av en visst sort
	SELECT SetID, Quantity FROM inventory WHERE ItemID = ' ' ORDER BY Quantity DESC
	
	bslot02
	
	$row = mysqli_query($connection, );
	
Satser med flest bitar av en viss färg
	SELECT SUM(Quantity) FROM inventory WHERE ColorID = ' ' ORDER BY Quantity
	
	$row = mysqli_query($connection, );
	
Bitar efter år av ursprung
	SELECT DISTINCT parts.Partname, sets.Year
	FROM parts, sets, inventory 
	WHERE ItemID = PartID AND sets.SetID = inventory.SetID 
	ORDER BY Year ASC LIMIT 10
	
	$row = mysqli_query($connection, );


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











?>





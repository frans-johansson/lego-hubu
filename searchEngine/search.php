<?php
//Hämta sida
$lowerLimit = $_GET["page"];

include "searchEngine/separate.php";

//Nollställ sida om ingen finns eller är mindre än noll
if(!$lowerLimit || $lowerLimit < 0) {
	$lowerLimit = 0;
}

$displaylimit = 20;




// Funktion för att formulera sökvillkoret för SQL-frågan
function getToSQL($getpara, $condition1, $condition2, $whereAdd){
	$getArray = splitGet($getpara);
	$length = count($getArray);

    // Kolla om användaren kryssat i exakt sökning eller om sökningen ska vara ungefär
	$precision = $_GET["exact"];


    // Formulera villkoren för själva frågan
	for($i = 0; $i < $length; $i++) {
	    // Om sökningen inte är exakt så formulera frågan på detta sätt
		if(!$precision) {
			$whereString .= $condition1 . " LIKE '%" . $getArray[$i] . "%'";

            // Om det är två villkor som ska uppfyllas, lägg till det andra
			if($condition2) {
				$whereString .= " OR " . $condition2 . " LIKE '%" . $getArray[$i] . "%'";
			}
		}
		else if($precision){        // Om sökningen är exakt så formulera frågan på detta sätt
			$whereString .= $condition1 . " = '" . $getArray[$i] . "'";

            // Om det är två villkor som ska uppfyllas, lägg till det andra
			if($condition2) {
				$whereString .= " OR " . $condition2 . " = '" . $getArray[$i] . "'";
			}
		}

        // Om det söktes på flera saker inom samma kategori, lägg till ett or innan loopen börjas om
		if($i != $length-1) {
			$whereString .= " OR ";
		}
	}

    // Lägg sökvillkoret inom parantes så att eventuella OR inte gör att frågan blir fel
	$whereGet = "(" . $whereString . ")";				// Kom på bättre variabelnamn!
	// Lägg ihop till en fråga
	$where .= " AND " . $whereGet . $whereAdd;

    // Returnera sökvillkoret
	return $where;
}


// Läs in om vi är inne på sidan parts eller sets
$page = $_GET["p"];


// Läs in ifall användaren har sökt på en sats
if($_GET["set"])
	$where .= getToSQL("set", "inventory.SetID", "Setname", "");

// Läs in ifall användaren har sökt på en bit
if($_GET["par"]) {
	if($page == parts) {
		$where .= getToSQL("par", "PartID", "Partname", "");
	}
	else if($page == sets) {
		$where .= getToSQL("par", "PartID", "Partname", " AND PartID = ItemID");
		$table .= ", parts";
	}
}

// Läs in ifall användaren har sökt på en färg
if($_GET["col"]) {
	if($page == parts) {
		$where .= getToSQL("col", "Colorname", "", "");
	}
	else if($page == sets) {
		$where .= getToSQL("col", "Colorname", "", " AND inventory.ColorID = colors.ColorID");
		$table .= ", colors";
	}
}

// Läs in ifall användaren har sökt på ett år
if($_GET["yea"])
	$where .= getToSQL("yea", "Year", "", "");

// Läs in vilket filtreringsalternativ anvöndaren valt
$filter = $_GET["f"];

// Få fram i vilken ordning obejekten ska visas
if($filter == "ageAsc") {
	$order = "MIN(Year) ASC";
}
else if($filter == "ageDesc") {
	$order = "MIN(Year) DESC";
}
else if($filter == "rarityAsc" && $page == 'parts') {
	$order = "COUNT(DISTINCT inventory.SetID) DESC";
}
else if($filter == "rarityAsc" && $page == 'sets') {
	$order = "SUM(Quantity) DESC";
}
else if($filter == "rarityDesc" && $page == 'parts' ) {
	$order = "COUNT(DISTINCT inventory.SetID) ASC";
}
else if($filter == "rarityDesc" && $page == 'sets' ) {
	$order = "SUM(Quantity) ASC";
}
else {
	// Om användaren inte valt filter så blir detta det förvalda alternativet
	$order = "COUNT(DISTINCT inventory.SetID) DESC";
}


// Kolla om sökningen ska vara inom den egna samlingen
if($_GET["c"]) {
	$where .= " AND collection.SetID = inventory.SetID";
	$table .= ", collection";
}


// Välj hur resultatet ska grupperas beroende på vilket sida man är inne och söker på
if($page == parts) {
	$group = "Colorname, PartID";
}
else if($page == sets) {
	$group = "sets.SetID";
}


// Om en fråga har ställts så koppla upp mot databasen
if($where) {
	// Testa om det går bra att koppla upp mot databasen
	$connection = mysqli_connect("mysql.itn.liu.se","lego","","lego");

		if (!$connection) {
			echo "Error: Unable to connect to MySQL." . PHP_EOL;
			echo "Debugging error: " . mysqli_connect_errno() . PHP_EOL;
			echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
			exit;
		}
}


if(!$where) {  // Eventuellt ändra
	// Do nothing
}
else if($page == parts) {
    if($_GET["set"]) {
        // Skapa sökfrågan som är specifik för om man sökt på ett set i parts
        $searchQuery = "SELECT PartID, Partname, Colorname, COUNT(DISTINCT inventory.SetID), MIN(Year)
                        FROM parts, inventory, sets, colors " . $table . " WHERE PartID = ItemID AND inventory.ColorID = colors.ColorID
                        AND ItemTypeID = 'P' AND inventory.SetID = sets.SetID AND (ItemID, Colorname) IN
                        (SELECT ItemID, Colorname FROM inventory, sets, colors WHERE sets.SetID = inventory.SetID
                        AND colors.ColorID = inventory.ColorID " . $where . ") GROUP BY " . $group . "
                        ORDER BY " . $order . " LIMIT " . $lowerLimit * $displaylimit . ", " . $displaylimit;
	}
	else {
        // Skapa sökfrågan
        $searchQuery = "SELECT	PartID, Partname, Colorname, COUNT(DISTINCT inventory.SetID), MIN(Year)
                        FROM parts, inventory, sets, colors " . $table . " WHERE PartID = ItemID AND
                        inventory.ColorID = colors.ColorID AND ItemTypeID = 'P' AND
                        inventory.SetID = sets.SetID" . $where . " GROUP BY " . $group . "
                        ORDER BY " . $order . " LIMIT " . $lowerLimit * $displaylimit . ", " . $displaylimit;
    }
}
else if($page == sets) {
	$searchQuery = "SELECT sets.SetID, Setname, MIN(Year), SUM(Quantity) FROM sets, inventory" . $table . " WHERE ItemTypeID = 'P' AND sets.SetID = inventory.SetID" . $where .
					" GROUP BY " . $group . " ORDER BY " . $order . " LIMIT " .  $lowerLimit * $displaylimit . ", " . $displaylimit;

	$maxPartsQuery = "SELECT SUM(Quantity) FROM sets, inventory" . $table . " WHERE ItemTypeID = 'P' AND sets.SetID = inventory.SetID" . $where .
					" GROUP BY " . $group . " ORDER BY SUM(Quantity) DESC LIMIT 1";
}


// TEST FÖR ATT SKRIVA UT MAX-ANTAL //
if($page == sets){

	$maxPartsResult = mysqli_query($connection, "$maxPartsQuery");

	$maxPartsArray = mysqli_fetch_array($maxPartsResult);

	$maxPartsAmount = $maxPartsArray[0];

	print ("<p>$maxPartsAmount</p>");

}

// SLUT PÅ TEST //


//	Ställ	frågan

	print "$searchQuery";

	$result	= mysqli_query($connection, "$searchQuery");

	$row = mysqli_fetch_array($result);

    // Detta borde nu kunna tas bort, kolla igenom senare
	/*if($_GET["set"] && $page == parts) {

		print "$row[0]['PartID']";

		while($row) {
			$ID = $row["PartID"];
			$Partname = $row["Partname"];
			$Color = $row["Colorname"];
			$numSets = $row["COUNT(DISTINCT inventory.SetID)"];
			$Year = $row["MIN(Year)"];

			if($_GET["set"]) {
				$specialCase = mysqli_query($connection, "SELECT COUNT(DISTINCT inventory.SetID), MIN(Year) FROM inventory, parts, colors, sets WHERE ItemID = PartID
											AND PartID = '" . $ID . "' AND inventory.ColorID = colors.ColorID AND Colorname = '" . $Color . "' AND sets.SetID = inventory.SetID");
				$hej = mysqli_fetch_array($specialCase);
				$row["COUNT(DISTINCT inventory.SetID)"] = $hej["COUNT(DISTINCT inventory.SetID)"];
				$row["MIN(Year)"] = $hej["MIN(Year)"];
			}
		}

		$array_length = sizeof($row);

		if($filter == "ageAsc") {
			for($i = 1; $i < $array_length; $i++) {
				for ($k = 0; k < $array_length-1; $k++) {
					if ($row[k]["MIN(Year)"] > $row[k + 1]["MIN(Year)"]) {
							$temp = $row[k]["MIN(Year)"];
							$row[k]["MIN(Year)"] = $row[k+1]["MIN(Year)"];
							$row[k+1]["MIN(Year)"] = $temp;
					}
				}
			}
		}
		else if($filter == "ageDesc") {
			for($i = 1; $i < $array_length; $i++) {
				for ($k = 0; k < array_length-1; $k++) {
					if ($row[k]["MIN(Year)"] < $row[k + 1]["MIN(Year)"]) {
							$temp = $row[k]["MIN(Year)"];
							$row[k]["MIN(Year)"] = $row[k+1]["MIN(Year)"];
							$row[k+1]["MIN(Year)"] = $temp;
					}
				}
			}
		}
		else if($filter == "rarityAsc") {
			for($i = 1; $i < $array_length; $i++) {
				for ($k = 0; k < array_length-1; $k++) {
					if ($row[k]["COUNT(DISTINCT inventory.SetID)"] < $row[k + 1]["COUNT(DISTINCT inventory.SetID)"]) {
							$temp = $row[k]["COUNT(DISTINCT inventory.SetID)"];
							$row[k]["COUNT(DISTINCT inventory.SetID)"] = $row[k+1]["COUNT(DISTINCT inventory.SetID)"];
							$row[k+1]["COUNT(DISTINCT inventory.SetID)"] = $temp;
					}
				}
			}
		}
		else if($filter == "rarityDesc") {
			for($i = 1; $i < $array_length; $i++) {
				for ($k = 0; k > array_length-1; $k++) {
					if ($row[k]["COUNT(DISTINCT inventory.SetID)"] < $row[k + 1]["COUNT(DISTINCT inventory.SetID)"]) {
							$temp = $row[k]["COUNT(DISTINCT inventory.SetID)"];
							$row[k]["COUNT(DISTINCT inventory.SetID)"] = $row[k+1]["COUNT(DISTINCT inventory.SetID)"];
							$row[k+1]["COUNT(DISTINCT inventory.SetID)"] = $temp;
					}
				}
			}
		}

		print "	<tr>
					<th>Image</th>
					<th>ID</th>
					<th>Name</th>
					<th>Color</th>
					<th>Included in sets</th>
					<th>Release year</th>
				</tr>";

		for($i = 0; $i < $array_length; $i++) {
			$ID = $row[$i]["PartID"];
			$Partname = $row[$i]["Partname"];
			$Color = $row[$i]["Colorname"];
			$numSets = $row[$i]["COUNT(DISTINCT inventory.SetID)"];
			$Year = $row[$i]["MIN(Year)"];

			// Fråga efter den information som är relevant för att få fram en bild
			$info = mysqli_query($connection, "SELECT colors.ColorID, ItemTypeID, has_gif, has_jpg, has_largegif, has_largejpg FROM images, colors
			WHERE ItemID = '$ID' AND Colorname = '$Color' AND colors.ColorID = images.ColorID");

			$format = mysqli_fetch_array($info);

			// Lägg den nödvändiga informationen för bildnamnet i variabler
			$Itemtype = $format["ItemTypeID"];
			$ColorID = $format["ColorID"];


			// Bilda länken till den bild som ska visas
			if($format["has_jpg"]) {
				$name = $Itemtype . '/' . $ColorID . '/' . $ID . '.jpg';
				$link = "http://www.itn.liu.se/~stegu76/img.bricklink.com/$name";
			}
			else if($format["has_gif"]) {
				$name = $Itemtype . '/' . $ColorID . '/' . $ID . '.gif';
				$link = "http://www.itn.liu.se/~stegu76/img.bricklink.com/$name";
			}
			else if($format["has_largejpg"]) {
				$name = $Itemtype . 'L/' . $ID . '.jpg';
				$link = "http://www.itn.liu.se/~stegu76/img.bricklink.com/$name";
			}
			else if($format["has_largegif"]) {
				$name = $Itemtype . 'L/' . $ID . '.gif';
				$link = "http://www.itn.liu.se/~stegu76/img.bricklink.com/$name";
			}
				print "hej";
				// Skriv ut detta i tabellen
				print "<tr><td><img src=\"$link\" alt=\"$name\"></td><td>$ID</td><td>$Partname</td><td>$Color</td><td>$numSets</td><td>$Year</td></tr>";
			}
	} */

// Ge felmeddelande om sökningen inte ger några resultat
if(!$row && $where) {
	print "Your search generated no results. Please search for something else!";
}
else if($row && $page == parts) {
	print "	<tr>
				<th>Image</th>
				<th>ID</th>
				<th>Name</th>
				<th>Color</th>
				<th>Included in sets</th>
				<th>Release year</th>
			</tr>";
}
else if($row && $page == sets) {
	print "	<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Release Year</th>
				<th colspan=\"2\">Number of parts</th>
			</tr>";
}


$result	= mysqli_query($connection, "$searchQuery");


//if(!$_GET["set"]) {
	while($row = mysqli_fetch_array($result)) {

        // Gå igenom och ändra i detta nu när bättre lösning funnen
		if($page == parts) {
			// Lägg informationen som ska visas i separata variabler
			$ID = $row["PartID"];
			$Partname = $row["Partname"];
			$Color = $row["Colorname"];
			$numSets = $row["COUNT(DISTINCT inventory.SetID)"];
			$Year = $row["MIN(Year)"];

			if($_GET["set"]) {
				$specialCase = mysqli_query($connection, "SELECT COUNT(DISTINCT inventory.SetID), MIN(Year) FROM inventory, parts, colors, sets WHERE ItemID = PartID
											AND PartID = '" . $ID . "' AND inventory.ColorID = colors.ColorID AND Colorname = '" . $Color . "' AND sets.SetID = inventory.SetID");
				$hej = mysqli_fetch_array($specialCase);
				$numSets = $hej["COUNT(DISTINCT inventory.SetID)"];
				$Year = $hej["MIN(Year)"];
			}

			// Fråga efter den information som är relevant för att få fram en bild
			$info = mysqli_query($connection, "SELECT colors.ColorID, ItemTypeID, has_gif, has_jpg, has_largegif, has_largejpg FROM images, colors
			WHERE ItemID = '$ID' AND Colorname = '$Color' AND colors.ColorID = images.ColorID");

			$format = mysqli_fetch_array($info);

			// Lägg den nödvändiga informationen för bildnamnet i variabler
			$Itemtype = $format["ItemTypeID"];
			$ColorID = $format["ColorID"];


			// Bilda länken till den bild som ska visas
			if($format["has_jpg"]) {
				$name = $Itemtype . '/' . $ColorID . '/' . $ID . '.jpg';
				$link = "http://www.itn.liu.se/~stegu76/img.bricklink.com/$name";
			}
			else if($format["has_gif"]) {
				$name = $Itemtype . '/' . $ColorID . '/' . $ID . '.gif';
				$link = "http://www.itn.liu.se/~stegu76/img.bricklink.com/$name";
			}
			else if($format["has_largejpg"]) {
				$name = $Itemtype . 'L/' . $ID . '.jpg';
				$link = "http://www.itn.liu.se/~stegu76/img.bricklink.com/$name";
			}
			else if($format["has_largegif"]) {
				$name = $Itemtype . 'L/' . $ID . '.gif';
				$link = "http://www.itn.liu.se/~stegu76/img.bricklink.com/$name";
			}

			// Skriv ut detta i tabellen
			print "<tr><td><img src=\"$link\" alt=\"$name\"></td><td>$ID</td><td>$Partname</td><td>$Color</td><td>$numSets</td><td>$Year</td></tr>";
		}
		else if($page == sets) {
			$ID = $row["SetID"];
			$Setname = $row["Setname"];
			$Year = $row["MIN(Year)"];
			$numParts = $row["SUM(Quantity)"];
			$percentage = 100 * $numParts / $maxPartsAmount; // Beräkna den relativa bredden av varje histogrampelare


			// Skriv ut detta i tabellen
			print "<tr><td>$ID</td><td>$Setname</td><td>$Year</td><td>$numParts </td><td><div class=\"histogram\" style=\"width: $percentage%\"></div></td></tr>";
		}
	}
//}

$rowcount = mysqli_num_rows($result);


if($searchQuery) {
	mysqli_close($connection);
}

/* SELECT PartID, Partname, Colorname, COUNT(DISTINCT inventory.SetID), MIN(Year) FROM parts, inventory, sets, colors
WHERE PartID = ItemID AND inventory.ColorID = colors.ColorID AND ItemTypeID = 'P' AND inventory.SetID = sets.SetID
AND (ItemID, Colorname) IN (SELECT ItemID, Colorname FROM inventory, sets, colors WHERE sets.SetID = inventory.SetID
AND colors.ColorID = inventory.ColorID AND Setname = 'Taj mahal') GROUP BY Colorname, PartID ORDER BY MIN(Year) ASC */

?>

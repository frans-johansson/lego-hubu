<?php
//Hämta sida
$lowerLimit = $_GET["page"];

include "searchEngine/separate.php";

//Nollställ sida om ingen finns eller är mindre än noll
if(!$lowerLimit || $lowerLimit < 0) {
	$lowerLimit = 0;
}

$displaylimit = 20;





function getToSQL($getpara, $condition1, $condition2, $whereAdd){
	$getArray = splitGet($getpara);
	$length = count($getArray);
	
	$precision = $_GET["exact"];
	
	for($i = 0; $i < $length; $i++) {
		if(!$precision) {
			$whereString .= $condition1 . " LIKE '%" . $getArray[$i] . "%'";
			
			if($condition2) {
				$whereString .= " OR " . $condition2 . " LIKE '%" . $getArray[$i] . "%'";
			}
		}
		else if($precision){
			$whereString .= $condition1 . " = '" . $getArray[$i] . "'";
			
			if($condition2) {
				$whereString .= " OR " . $condition2 . " = '" . $getArray[$i] . "'";
			}
		}
		
		if($i != $length-1) {
			$whereString .= " OR ";
		}
	}
	
	$whereGet = "(" . $whereString . ")";				// Kom på bättre variabelnamn!
	// Lägg ihop till en fråga
	$where .= " AND " . $whereGet . $whereAdd;
	
	
	return $where;
}


// Läs in om vi är inne på sidan parts eller sets
$page = $_GET["p"];


if($_GET["set"])
	$where .= getToSQL("set", "inventory.SetID", "Setname", "");

if($_GET["par"]) {
	if($page == parts) {
		$where .= getToSQL("par", "PartID", "Partname", "");
	}
	else if($page == sets) {
		$where .= getToSQL("par", "PartID", "Partname", " AND PartID = ItemID");
		$table .= ", parts";
	}
}
	
if($_GET["col"]) {
	if($page == parts) {
		$where .= getToSQL("col", "Colorname", "", "");
	}
	else if($page == sets) {
		$where .= getToSQL("col", "Colorname", "", " AND inventory.ColorID = colors.ColorID");
		$table .= ", colors";
	}
}

if($_GET["yea"])
	$where .= getToSQL("yea", "Year", "", "");


$filter = $_GET["f"];

// Få fram i vilken ordning obejekten ska visas
if($filter == "ageAsc") {
	$order = "MIN(Year) ASC";
}
else if($filter == "ageDesc") {
	$order = "MIN(Year) DESC";
}
else if($filter == "rarityAsc") {
	$order = "COUNT(DISTINCT inventory.SetID) DESC";
}
else if($filter == "rarityDesc") {
	$order = "COUNT(DISTINCT inventory.SetID) ASC";
}
else {
	// Ett default till innan användaren valt sortering
	$order = "COUNT(DISTINCT inventory.SetID) DESC";
}


// Kolla om sökningen ska vara inom den egna samlingen
if($_GET["c"]) {
	$where .= " AND collection.SetID = inventory.SetID";
	$table .= ", collection";
}


if($page == parts) {
	$group = "Colorname, PartID";
}
else if($page == sets) {
	$group = "sets.SetID";
}



if(!$where) {
	// Do nothing
}
else if($page == parts) {	
	// Skapa sökfrågan
	$searchQuery = "SELECT	PartID, Partname, Colorname, COUNT(DISTINCT inventory.SetID), MIN(Year) FROM parts, inventory, sets, colors " . $table . " WHERE PartID = ItemID AND inventory.ColorID = colors.ColorID AND 
					ItemTypeID = 'P' AND inventory.SetID = sets.SetID" . $where . " GROUP BY " . $group . " ORDER BY " . $order . " LIMIT " . $lowerLimit * $displaylimit . " ," . $displaylimit;			
}
else if($page == sets) {
	$searchQuery = "SELECT sets.SetID, Setname, MIN(Year), SUM(Quantity) FROM sets, inventory" . $table . " WHERE ItemTypeID = 'P' AND sets.SetID = inventory.SetID" . $where . 
					" GROUP BY " . $group . " ORDER BY " . $order . " LIMIT " .  $lowerLimit * $displaylimit . ", " . $displaylimit;
					
	$maxPartsQuery = "SELECT SUM(Quantity) FROM sets, inventory" . $table . " WHERE ItemTypeID = 'P' AND sets.SetID = inventory.SetID" . $where . 
					" GROUP BY " . $group . " ORDER BY SUM(Quantity) DESC LIMIT 1";
}


	// Testa om det går bra att koppla upp mot databasen
	$connection = mysqli_connect("mysql.itn.liu.se","lego","","lego");
		
		if (!$connection) {
			echo "Error: Unable to connect to MySQL." . PHP_EOL;
			echo "Debugging error: " . mysqli_connect_errno() . PHP_EOL;
			echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
			exit;
		}
	
	print "$searchQuery";
	
//	Ställ	frågan																																																			
	$result	= mysqli_query($connection, "$searchQuery");

	$row = mysqli_fetch_array($result);

// TEST FÖR ATT SKRIVA UT MAX-ANTAL //

	$maxPartsResult = mysqli_query($connection, "$maxPartsQuery");
	
	$maxPartsArray = mysqli_fetch_array($maxPartsResult);
	
	$maxPartsAmount = $maxPartsArray[0];
	
	print ("<p>$maxPartsAmount</p>");
	
// SLUT PÅ TEST //


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
				<th>Number of parts</th>
				<th class=\"histogramCol\">Histogram</th>
			</tr>";
}
	
	$result	= mysqli_query($connection, "$searchQuery");
	
while($row = mysqli_fetch_array($result)) {

	if($page == parts) {
		// Lägg informationen som ska visas i separata variabler
		$ID = $row["PartID"];
		$Partname = $row["Partname"];
		$Color = $row["Colorname"];
		$numSets = $row["COUNT(DISTINCT inventory.SetID)"];
		$Year = $row["MIN(Year)"];

		
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
		$percentage = 100 * $numParts / $maxPartsAmount;
	
	
		// Skriv ut detta i tabellen
		print "<tr><td>$ID</td><td>$Setname</td><td>$Year</td><td>$numParts</td><td><div class=\"histogram\" style=\"width: $percentage%\">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</div></td></tr>";
	}
}

if($searchQuery) {
	mysqli_close($connection);
}



/*
	$searchQuery = "SELECT	PartID, Partname, Colorname, COUNT(DISTINCT SetID) FROM parts, inventory, sets" . $table . " WHERE ItemTypeID = 'P' AND " . $where1 . " LIKE '%" . $search . "%' 
	" . $or . " GROUP BY ItemID ORDER BY COUNT(DISTINCT SetID) LIMIT " . $lowerLimit * $displaylimit . " ," . $displaylimit;

	
	SELECT PartID, Partname, Colorname, COUNT(DISTINCT inventory.SetID), MIN(Year) FROM parts, inventory, colors, sets WHERE PartID = '3003' AND PartID = ItemID 
	AND inventory.ColorID = colors.ColorID AND ItemTypeID = 'P' AND inventory.SetID = sets.SetID GROUP BY Colorname ORDER BY MIN(Year) ASC LIMIT 20
*/
?>
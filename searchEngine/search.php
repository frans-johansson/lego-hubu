<?php
//Hämta sida
$lowerLimit = $_GET["page"];


//Nollställ sida om ingen finns eller är mindre än noll
if(!$lowerLimit || $lowerLimit < 0) {
	$lowerLimit = 0;
}

$displaylimit = 20;

// Få fram vilka frågor som ska ställas
if($_GET["set"] != ""){
	$set = "set";
	$setGetArray = splitGet($set);
	$length = count($setGetArray);
	
	for($i = 0; $i < $length; $i++) {
		$whereSetsString += "inventory.SetID LIKE '%" . $setGetArray[$i] . "%' OR Setname LIKE '%" . $setGetArray[$i] . "%'";
		if($i != $length-1) {
			$whereSetsString += " OR ";
		}
	}
	
	$table = "";
	$whereSets = "(" . $whereSetsString . ")";				// Kom på bättre variabelnamn!
}
else {
	$whereSets = "";
}

if($_GET["par"] != ""){
	$par = "par";
	$parGetArray = splitGet($par);
	$length = count($parGetArray);
	
	for($i = 0; $i < $length; $i++) {
		$wherePartsString += "PartID LIKE '%" . $parGetArray[$i] . "%' OR Partname LIKE '%" . $parGetArray[$i] . "%'";
		if($i != $length-1) {
			$wherePartsString += " OR ";
		}
	}
	
	$table = "";
	$wherePart = "(" . $wherePartsString . ")";
}
else {
	$wherePart = "";
}

if($_GET["col"] != ""){
	$col = "col";
	$colGetArray = splitGet($col);
	$length = count($colGetArray);
	
	for($i = 0; $i < $length; $i++) {
		$whereColString += "Colorname LIKE '%" . $colGetArray[$i] . "%'";
		if($i != $length-1) {
			$whereColString += " OR ";
		}
	}
	
	$table = "";
	$whereColor = "(" . $whereColString . ")";
}

if($_GET["yea"] != ""){
	$yea = "yea";
	$yeaGetArray = splitGet($yea);
	$length = count($yeaGetArray);
	
	for($i = 0; $i < $length; $i++) {
		$whereYearString += "MIN(Year) LIKE '%" . $yeaGetArray[$i] . "%'";
		if($i != $length-1) {
			$whereYearString += " OR ";
		}
	}
	
	$table = "";
	$whereYear = "(" . $whereYearString . ")";
}
else {
	$whereYear = "";
}

if($_GET["cat"] != ""){
	$cat = "cat";
	$catGetArray = splitGet($cat);
	$length = count($catGetArray);
	
	for($i = 0; $i < $length; $i++) {
		$whereCatString += "Categoryname LIKE '%" . $catGetArray[$i] . "%'";
		if($i != $length-1) {
			$whereCatString += " OR ";
		}
	}
	
	$table = ", categories";
	$whereCat = "(" . $whereCatString . ") AND parts.CatID = categories.CatID AND categories.CatID = sets.SetID";
}
else {
	$whereCat = "";
}


// Få fram i vilken ordning obejekten ska visas
if($_GET["f"] == "ageAsc") {
	$order = "MIN(Year) ASC";
}
else if($_GET["f"] == "ageDesc") {
	$order = "MIN(Year) DESC";
}
else if($_GET["f"] == "rarityAsc") {
	$order = "COUNT(DISTINCT inventory.SetID) ASC";
}
else if($_GET["f"] == "rarityDesc") {
	$order = "COUNT(DISTINCT inventory.SetID) DESC";
}

$group = "Colorname, PartID";
// Eller $group = vad? Eventuellt group by Colorname and group by PartID


//Sök
if (!empty($_GET["search"])) {
		$search = $_GET["search"];
	}
	
// Skapa sökfrågan
$searchQuery = "SELECT	PartID, Partname, Colorname, COUNT(DISTINCT inventory.SetID), MIN(Year) FROM parts, inventory, sets, colors" . $table . " WHERE PartID = ItemID AND inventory.ColorID = colors.ColorID AND 
				ItemTypeID = 'P' AND inventory.SetID = sets.SetID AND" . $where . " GROUP BY " . $group . " ORDER BY " . $order . " LIMIT " . $lowerLimit * $displaylimit . " ," . $displaylimit;

	
// Testa om det går bra att koppla upp mot databasen
$connection = mysqli_connect("mysql.itn.liu.se","lego","","lego");
	
	if (!$link) {
		echo "Error: Unable to connect to MySQL." . PHP_EOL;
		echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
		exit;
	}
	
//	Ställ	frågan																																																			
	$result	= mysqli_query($connection, "$searchQuery");
	
while($row = mysqli_fetch_array($result)) {
	// Lägg informationen som ska visas i separata variabler
	$ID = $row["PartID"];
	$Partname = $row["Partname"];
	$Color = $row["Colorname"];
	$numSets = $row["COUNT(DISTINCT inventory.SetID)"];
	$Year = $row["MIN(Year)"];

		
	// Fråga efter den information som är relevant för att få fram en bild
	$format = mysqli_query($connection, "SELECT colors.ColorID, ItemTypeID, has_gif, has_jpg, has_largegif, has_largejpg FROM images, colors 
		WHERE ItemID = '$ID' AND Colorname = '$Color' AND colors.ColorID = images.ColorID");
	
	
	// Lägg den nödvändiga informationen för bildnamnet i variabler
	$Itemtype = $format["ItemTypeID"];
	$ColorID = $format["ColorID"];
	
								
	// Bilda länken till den bild som ska visas
	if($format["has_jpg"]) {
		$name = $Itemtype . '/' . $ColorID . '/' . $ID . '.jpg';
		$link = 'http://www.itn.liu.se/~stegu76/img.bricklink.com/$name';
	}
	else if($format["has_gif"]) {
		$name = $Itemtype . '/' . $ColorID . '/' . $ID . '.gif';
		$link = 'http://www.itn.liu.se/~stegu76/img.bricklink.com/$name';
	}
	else if($format["has_largejpg"]) {
		$name = $Itemtype . 'L/' . $ID . '.jpg';
		$link = 'http://www.itn.liu.se/~stegu76/img.bricklink.com/$name';
	}
	else if($format["has_largegif"]) {
		$name = $Itemtype . 'L/' . $ID . '.gif';
		$link = 'http://www.itn.liu.se/~stegu76/img.bricklink.com/$name';
	}
}

/*
	$searchQuery = "SELECT	PartID, Partname, Colorname, COUNT(DISTINCT SetID) FROM parts, inventory, sets" . $table . " WHERE ItemTypeID = 'P' AND " . $where1 . " LIKE '%" . $search . "%' 
	" . $or . " GROUP BY ItemID ORDER BY COUNT(DISTINCT SetID) LIMIT " . $lowerLimit * $displaylimit . " ," . $displaylimit;

	
	SELECT PartID, Partname, Colorname, COUNT(DISTINCT inventory.SetID), MIN(Year) FROM parts, inventory, colors, sets WHERE PartID = '3003' AND PartID = ItemID 
	AND inventory.ColorID = colors.ColorID AND ItemTypeID = 'P' AND inventory.SetID = sets.SetID GROUP BY Colorname ORDER BY MIN(Year) ASC LIMIT 20
*/
?>
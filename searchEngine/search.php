<?php
//Hämta sida
$lowerLimit = $_GET["page"];

include "searchEngine/separate.php";

//Nollställ sida om ingen finns eller är mindre än noll
if(!$lowerLimit || $lowerLimit < 0) {
	$lowerLimit = 0;
}

$displaylimit = 20;


function getToSQL($getpara, $condition1, $condition2, $tableAdd, $whereAdd){
	$getArray = splitGet($getpara);
	$length = count($getArray);
	
	for($i = 0; $i < $length; $i++) {
		$whereString .= $condition1 . " LIKE '%" . $getArray[$i] . "%'";
		
		if($condition2) {
			$whereString .= " OR " . $condition2 . " LIKE '%" . $getArray[$i] . "%'";
		}
		
		if($i != $length-1) {
			$whereString .= " OR ";
		}
	}
	
	$table .= "";
	
	$whereGet = "(" . $whereString . ")";				// Kom på bättre variabelnamn!
	// Lägg ihop till en fråga
	$where .= " AND " . $whereGet . $whereAdd;
	
	
	return $where;
}

if($_GET["set"])
	$where .= getToSQL("set", "inventory.SetID", "Setname", "", "");

if($_GET["par"])
	$where .= getToSQL("par", "PartID", "Partname", "", "");

if($_GET["col"])
	$where .= getToSQL("col", "Colorname", "", "", "");

if($_GET["yea"])
	$where .= getToSQL("yea", "MIN(Year)", "", "", "");

if($_GET["cat"])
	$where .= getToSQL("cat", "Categoryname", "", ", categories", " AND parts.CatID = categories.CatID AND categories.CatID = sets.SetID");


/*
// Få fram vilka frågor som ska ställas
if($_GET["set"]){
	$set = "set";
	$setGetArray = splitGet($set);
	$length = count($setGetArray);
	
	for($i = 0; $i < $length; $i++) {
		$whereSetsString .= "inventory.SetID LIKE '%" . $setGetArray[$i] . "%' OR Setname LIKE '%" . $setGetArray[$i] . "%'";
		if($i != $length-1) {
			$whereSetsString .= " OR ";
		}
	}
	
	$table .= "";
	$whereSets = "(" . $whereSetsString . ")";				// Kom på bättre variabelnamn!
	// Lägg ihop till en fråga
	$where .= " AND " . $whereSets;
}


if($_GET["par"]){
	$par = "par";
	$parGetArray = splitGet($par);
	$length = count($parGetArray);
	
	for($i = 0; $i < $length; $i++) {
		$wherePartsString .= "PartID LIKE '%" . $parGetArray[$i] . "%' OR Partname LIKE '%" . $parGetArray[$i] . "%'";
		if($i != $length-1) {
			$wherePartsString .= " OR ";
		}
	}
	
	$table .= "";
	$wherePart = "(" . $wherePartsString . ")";
	// Lägg ihop till en fråga
	$where .= " AND " . $wherePart;
}


if($_GET["col"]){
	$col = "col";
	$colGetArray = splitGet($col);
	$length = count($colGetArray);
	
	for($i = 0; $i < $length; $i++) {
		$whereColString .= "Colorname LIKE '%" . $colGetArray[$i] . "%'";
		if($i != $length-1) {
			$whereColString .= " OR ";
		}
	}
	
	$table .= "";
	$whereColor = "(" . $whereColString . ")";
	// Lägg ihop till en fråga
	$where .= " AND " . $whereColor;
}

if($_GET["yea"]){
	$yea = "yea";
	$yeaGetArray = splitGet($yea);
	$length = count($yeaGetArray);
	
	for($i = 0; $i < $length; $i++) {
		$whereYearString .= "MIN(Year) LIKE '%" . $yeaGetArray[$i] . "%'";
		if($i != $length-1) {
			$whereYearString .= " OR ";
		}
	}
	
	$table .= "";
	$whereYear = "(" . $whereYearString . ")";
	// Lägg ihop till en fråga
	$where .= " AND " . $whereYear;
}


if($_GET["cat"]){
	$cat = "cat";
	$catGetArray = splitGet($cat);
	$length = count($catGetArray);
	
	for($i = 0; $i < $length; $i++) {
		$whereCatString .= "Categoryname LIKE '%" . $catGetArray[$i] . "%'";
		if($i != $length-1) {
			$whereCatString .= " OR ";
		}
	}
	
	$table .= ", categories";
	$whereCat = "(" . $whereCatString . ") AND parts.CatID = categories.CatID AND categories.CatID = sets.SetID";
	// Lägg ihop till en fråga
	$where .= " AND " . $whereCat;
}

*/

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

$group = "Colorname, PartID";
// Eller $group = vad? Eventuellt group by Colorname and group by PartID


// Kolla om sökningen ska vara inom den egna samlingen
if($_GET["c"]) {
	$where .= " AND collection.SetID = inventory.SetID";
	$table .= ", collection";
}


if(!$where) {
	// Do nothing
}
else {	
// Skapa sökfrågan
$searchQuery = "SELECT	PartID, Partname, Colorname, COUNT(DISTINCT inventory.SetID), MIN(Year) FROM parts, inventory, sets, colors" . $table . " WHERE PartID = ItemID AND inventory.ColorID = colors.ColorID AND 
				ItemTypeID = 'P' AND inventory.SetID = sets.SetID" . $where . " GROUP BY " . $group . " ORDER BY " . $order . " LIMIT " . $lowerLimit * $displaylimit . " ," . $displaylimit;

	
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
}


// Ge felmeddelande om sökningen inte ger några resultat
if(!$row && $where) {
	print "Your search generated no results. Please search for something else!";
}


while($row = mysqli_fetch_array($result)) {
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
	print "<tr><td><img src=\"$link\" alt=\"$name\"></td><td>" . $ID . "</td><td>" . $Partname . "</td><td>" . $Color . "</td><td>" . $numSets . "</td><td>" . $Year . "</td></tr>";
}



/*
	$searchQuery = "SELECT	PartID, Partname, Colorname, COUNT(DISTINCT SetID) FROM parts, inventory, sets" . $table . " WHERE ItemTypeID = 'P' AND " . $where1 . " LIKE '%" . $search . "%' 
	" . $or . " GROUP BY ItemID ORDER BY COUNT(DISTINCT SetID) LIMIT " . $lowerLimit * $displaylimit . " ," . $displaylimit;

	
	SELECT PartID, Partname, Colorname, COUNT(DISTINCT inventory.SetID), MIN(Year) FROM parts, inventory, colors, sets WHERE PartID = '3003' AND PartID = ItemID 
	AND inventory.ColorID = colors.ColorID AND ItemTypeID = 'P' AND inventory.SetID = sets.SetID GROUP BY Colorname ORDER BY MIN(Year) ASC LIMIT 20
*/
?>
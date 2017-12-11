<?php
//Hämta sida
$lowerLimit = $_GET["page"];SELECT ItemID, COUNT(DISTINCT SetID) FROM inventory 
	WHERE ItemTypeID = 'P' GROUP BY ItemID ORDER BY COUNT(DISTINCT SetID) DESC LIMIT 20

//Nollställ sida om ingen finns eller är mindre än noll
if(!$lowerLimit || $lowerLimit < 0) {
	$lowerLimit = 0;
}

$displaylimit = 20;

if($_GET["condition"] == "sets"){
	$table = "sets";
	$where1 = "SetID";				// Kom på bättre variabelnamn!
	$where2 = "Setname";
	$or = "OR " . $where2 . " LIKE '%" . $search . "%'";
}else if($_GET["condition"] == "parts"){
	$table = "parts";
	$where1 = "PartID";	
	$where2 = "Partname";	
	$or = "OR " . $where2 . " LIKE '%" . $search . "%'";
}else if($_GET["condition"] == "color"){
	$table = "colors";
	$where1 = "Colorname";	
	$or = "";
}else if($_GET["condition"] == "year"){
	$table = "sets";
	$where1 = "Year";	
	$or = "";
}else if($_GET["condition"] == "category"){
	$table = "categories";
	$where1 = "Categoryname";	
	$or = "";
}	

//Sök
if (!empty($_GET["search"])) {
		$search = $_GET["search"];
	}
	$searchQuery = "SELECT	PartID, Partname, Colorname FROM " . $table . " WHERE " . $where1 . " LIKE " . $search . "%' 
	" . $or . " LIMIT " . $lowerLimit * $displaylimit . " ," . $displaylimit;
	
//	Ställ	frågan																																																			
	$result	= mysqli_query($connection,  $searchQuery);
	
	
	
	
/*
Detta är en tanke för att få fram antalet satser en bit ingått i

SELECT COUNT(DISTINCT SetID) FROM inventory WHERE ItemTypeID = 'P' AND PartID = '$search' 
AND PartID = ItemID
 
*/
?>
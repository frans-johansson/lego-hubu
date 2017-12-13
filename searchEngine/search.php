<?php
//Hämta sida
$lowerLimit = $_GET["page"];


//Nollställ sida om ingen finns eller är mindre än noll
if(!$lowerLimit || $lowerLimit < 0) {
	$lowerLimit = 0;
}

$displaylimit = 20;

if($_GET["condition"] == "sets"){
	$table = "";
	$where1 = "SetID";				// Kom på bättre variabelnamn!
	$where2 = "Setname";
	$or = "OR " . $where2 . " LIKE '%" . $search . "%'";
}
else if($_GET["condition"] == "parts"){
	$table = "";
	$where1 = "PartID";	
	$where2 = "Partname";
	$or = "OR " . $where2 . " LIKE '%" . $search . "%'";
}
else if($_GET["condition"] == "color"){
	$table = ", colors";
	$where1 = "Colorname";	
	$or = "";
}
else if($_GET["condition"] == "year"){
	$table = "";
	$where1 = "Year";	
	$or = "";
}
else if($_GET["condition"] == "category"){
	$table = ", categories";
	$where1 = "Categoryname";	
	$or = "";
}	

//Sök
if (!empty($_GET["search"])) {
		$search = $_GET["search"];
	}
	$searchQuery = "SELECT	PartID, Partname, Colorname FROM parts, inventory, sets, colors" . $table . " WHERE PartID = ItemID AND inventory.ColorID = colors.ColorID AND " . $where1 . " LIKE '%" . $search . "%' 
	" . $or . " LIMIT " . $lowerLimit * $displaylimit . " ," . $displaylimit;
	
	// parts, inventory, sets, colors, categories
	
	
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
	$ID = $row["PartID"];
	$Partname = $row["Partname"];
	$Color = $row["Colorname"];
	
	$numSets = mysqli_query($connection, "SELECT COUNT(DISTINCT SetID) FROM inventory, parts 
							WHERE ItemTypeID = 'P' AND PartID = '$ID' AND PartID = ItemID");
	
	$Year = mysqli_query($connection, "SELECT DISTINCT Year FROM sets, inventory WHERE ItemID = '$ID' 
										AND sets.SetID = inventory.SetID ORDER BY Year ASC LIMIT 1");
								
	$format = mysqli_query($connection, "SELECT colors.ColorID, ItemTypeID, ItemID, has_gif, has_jpg, has_largegif, has_largejpg FROM images, colors 
										WHERE ItemID = '$ID' AND Colorname = '$Color' AND colors.ColorID = images.ColorID");
								
	$Itemtype = $format["ItemTypeID"];
	$ColorID = $format["ColorID"];
	$ItemID = $format["ItemID"];
								
	// Bild
	if($format["has_jpg"]) {
		$name = $Itemtype . '/' . $ColorID . '/' . $ItemID . '.jpg';
		$link = 'http://www.itn.liu.se/~stegu76/img.bricklink.com/$name';
	}
	else if($format["has_gif"]) {
		$name = $Itemtype . '/' . $ColorID . '/' . $ItemID . '.gif';
		$link = 'http://www.itn.liu.se/~stegu76/img.bricklink.com/$name';
	}
	else if($format["has_largejpg"]) {
		$name = $Itemtype . 'L/' . $ItemID . '.jpg';
		$link = 'http://www.itn.liu.se/~stegu76/img.bricklink.com/$name';
	}
	else if($format["has_largegif"]) {
		$name = $Itemtype . 'L/' . $ItemID . '.gif';
		$link = 'http://www.itn.liu.se/~stegu76/img.bricklink.com/$name';
	}
}

/*
	$searchQuery = "SELECT	PartID, Partname, Colorname, COUNT(DISTINCT SetID) FROM parts, inventory, sets" . $table . " WHERE ItemTypeID = 'P' AND " . $where1 . " LIKE '%" . $search . "%' 
	" . $or . " GROUP BY ItemID ORDER BY COUNT(DISTINCT SetID) LIMIT " . $lowerLimit * $displaylimit . " ," . $displaylimit;

	
	SELECT	PartID, Partname, Colorname, COUNT(DISTINCT inventory.SetID) FROM parts, inventory, colors WHERE PartID = ItemID AND inventory.ColorID = colors.ColorID 
	AND ItemTypeID = 'P' AND PartID LIKE '%320%' GROUP BY ItemID ORDER BY COUNT(DISTINCT inventory.SetID) DESC LIMIT 20
*/
?>
<?php
//Hämta sida
$lowerLimit = $_GET["page"];

//Nollställ sida om ingen finns eller är mindre än noll
if(!$lowerLimit || $lowerLimit < 0) {
	$lowerLimit = 0;
}


//Sök
if (!empty($_GET["search"])) {
		$search = $_GET["search"];
	}
	$searchQuery = "WHERE  SetID LIKE '" . $search . "%' 
	OR SetID LIKE '%" . $search . "%' LIMIT " . $lowerLimit . " ,10";
	
//	Ställ	frågan																																																			
	$result	= mysqli_query($connection, "SELECT	SetID FROM sets " . $searchQuery);
?>
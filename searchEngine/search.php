<?php

//Hämta vilken sida vi är på, exemepelvis sida 0, sida 1, sida 2 osv.
$lowerLimit = $_GET["page"];

// Inkludera funktionen som separerar det som står i get-parametrarna
include "searchEngine/separate.php";

//Nollställ sida om ingen finns eller är mindre än noll
if(!$lowerLimit || $lowerLimit < 0) {
	$lowerLimit = 0;
}

// Den gräns vi valt för hur många sökresultat som visas i taget
$displaylimit = 20;



// Funktion för att formulera sökvillkoret för SQL-frågan
function getToSQL($getpara, $condition1, $condition2, $whereAdd){
    // Hämta sökordet/sökorden och dela upp dem för sig
	$getArray = splitGet($getpara);

	// Ta fram hur många saker som sökts på
	$length = count($getArray);

    // Kolla om användaren kryssat i exakt sökning eller om sökningen ska vara ungefärlig
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

        // Om det söktes på flera saker inom samma kategori, lägg till ett OR innan loopen börjas om
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

// Läs in vad som sökts på och anropa funktionen för att formulera SQL-frågan

// Läs in ifall användaren har sökt på en sats
if($_GET["set"])
	$where .= getToSQL("set", "inventory.SetID", "Setname", "");

// Läs in ifall användaren har sökt på en bit
if($_GET["par"]) {
    // Kolla vilken sida användaren är inne och söker på och formulera frågan olika utefter det
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
    // Kolla vilken sida användaren är inne och söker på och formulera frågan olika utefter det
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


// Få fram i vilken ordning obejekten ska visas utefter den valda sorteringen
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

    // Om uppkopplingen misslyckades, visa ett felmeddelande
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
        // Skapa sökfrågan som är specifik för om man sökt på ett set i parts (Detta är ett specialfall)
        $searchQuery = "SELECT PartID, Partname, Colorname, COUNT(DISTINCT inventory.SetID), MIN(Year)
                        FROM parts, inventory, sets, colors " . $table . " WHERE PartID = ItemID AND inventory.ColorID = colors.ColorID
                        AND ItemTypeID = 'P' AND inventory.SetID = sets.SetID AND (ItemID, Colorname) IN
                        (SELECT ItemID, Colorname FROM inventory, sets, colors WHERE sets.SetID = inventory.SetID
                        AND colors.ColorID = inventory.ColorID " . $where . ") GROUP BY " . $group . "
                        ORDER BY " . $order . " LIMIT " . $lowerLimit * $displaylimit . ", " . $displaylimit;
	}
	else {
        // Skapa sökfrågan så som den ska se ut annars
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

    // Skapa en sökfråga för att få fram vilket set som innehåller flest bitar utav alla i resultatet
    // Detta är nödvändig information för hur histogrammet ska ritas upp
	$maxPartsQuery = "SELECT SUM(Quantity) FROM sets, inventory" . $table . " WHERE ItemTypeID = 'P' AND sets.SetID = inventory.SetID" . $where .
					" GROUP BY " . $group . " ORDER BY SUM(Quantity) DESC LIMIT 1";
}


// Ta fram antalet bitar hos det set som innehåller flest bitar av de set som matchar sökningen
// Detta är nödvändig information för histgramet och används när resultatet skrivs ut
if($page == sets){

    // Ställ frågan och läs in resultatet
	$maxPartsResult = mysqli_query($connection, "$maxPartsQuery");

    // Hämta arrayen med resultatet
	$maxPartsArray = mysqli_fetch_array($maxPartsResult);

    // Hämta det första värdet i arrayen då det frågan är ställd så att detta är det största värdet
	$maxPartsAmount = $maxPartsArray[0];
}


//	Ställ	frågan

print "$searchQuery";

// Ställ frågan
$result	= mysqli_query($connection, "$searchQuery");

// Hämta arrayen med resultatet
$row = mysqli_fetch_array($result);


// Ge felmeddelande om sökningen inte ger några resultat
if(!$row && $where) {
	print "Your search generated no results. Please search for something else!";
}
else if($row && $page == parts) {
    // Skriv ut tabellhuvudena
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
    // Skriv ut tabellhuvudena
	print "	<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Release Year</th>
				<th colspan=\"2\">Number of parts</th>
			</tr>";
}

// Hämta resultatet igen, nödvändigt eftersom SQL är konstigt och arrayen har blivit tom vid det här laget?
$result	= mysqli_query($connection, "$searchQuery");



// Hämta arrayen med resultatet igen, annars kör den inte igenom arrayen utan visar bara resultatet för samma bit om och om igen i alla evighet
while($row = mysqli_fetch_array($result)) {

    // Gå igenom och ändra i detta nu när bättre lösning funnen
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

        // Hämta arrayen med denna information
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
        // Lägg informationen som ska visas i separata variabler
        $ID = $row["SetID"];
        $Setname = $row["Setname"];
        $Year = $row["MIN(Year)"];
        $numParts = $row["SUM(Quantity)"];

        // Beräkna den relativa bredden av varje histogrampelare
        $percentage = 100 * $numParts / $maxPartsAmount;


        // Skriv ut detta i tabellen
        print "<tr><td>$ID</td><td>$Setname</td><td>$Year</td><td>$numParts </td><td><div class=\"histogram\" style=\"width: $percentage%\"></div></td></tr>";
    }
}


// Beräkna antalet rader i resultatet för att få fram om next-knappen ska visas eller ej, detta görs i en annan fil
$rowcount = mysqli_num_rows($result);


// Om en fråga ställdes så ska nu kopplingen till databasen stängas stängas
if($searchQuery) {
	mysqli_close($connection);
}

?>

<?php

//Hämta vilken sida vi är på, exemepelvis sida 0, sida 1, sida 2 osv.
    $lowerLimit = $_GET["page"];
	

//Nollställ sida om ingen finns eller är mindre än noll, vilket inte ska vara möjligt
    if(!$lowerLimit || $lowerLimit < 0) {
        $lowerLimit = 0;
    }


// Den gräns vi valt för hur många sökresultat som visas i taget
    $displaylimit = 20;


// Läs in om vi är inne på sidan parts eller sets
    $page = $_GET["p"];

	
	
	
	/* Formulera SQL-frågan */
	

// Inkludera funktionen getToSQL som anropas nedan och används för att formulera SQL-frågan
    include "searchEngine/condition.php";

// Läs in ifall användaren har sökt på en sats och formulera sökvillkoret beroende av detta
    if($_GET["set"])
        $where .= getToSQL("set", "inventory.SetID", "Setname", "");

// Läs in ifall användaren har sökt på en bit och formulera sökvillkoret beroende av detta
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

// Läs in ifall användaren har sökt på en färg och formulera sökvillkoret beroende av detta
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

// Läs in ifall användaren har sökt på ett år och formulera sökvillkoret beroende av detta
    if($_GET["yea"])
        $where .= getToSQL("yea", "Year", "", "");


// Läs in vilket filtreringsalternativ användaren valt
// Det finns ett default satt för om användaren inte aktivt valt någonting
    $filter = $_GET["f"];


// Få fram i vilken ordning obejekten ska visas utefter den valda sorteringen´
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
        $order = "SUM(inventory.Quantity) DESC";
    }
    else if($filter == "rarityDesc" && $page == 'parts' ) {
        $order = "COUNT(DISTINCT inventory.SetID) ASC";
    }
    else if($filter == "rarityDesc" && $page == 'sets' ) {
        $order = "SUM(inventory.Quantity) ASC";
    }


// Kolla om sökningen ska vara inom den egna samlingen
// Om get-parametern är satt så har användaren valt att visa enbart den egna samlingen
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

	
	
// Om en fråga har ställts så koppla upp mot databasen och skapa frågan
    if($where) {
        // Koppla upp mot databasen
            include "searchEngine/connect.php";

        // Skapa frågan $searchQuery som sedan ställs till databasen
            include "searchEngine/query.php";
    }


// Om användaren är inne på sets och en sökning gjorts, ta fram antalet bitar hos det set som innehåller flest bitar av de set som matchar sökningen
// Detta är nödvändig information för histogrammet och används när resultatet skrivs ut
    if($where && $page == sets){

        // Ställ frågan och läs in resultatet
        $maxPartsResult = mysqli_query($connection, "$maxPartsQuery");

        // Hämta arrayen med resultatet
        $maxPartsArray = mysqli_fetch_array($maxPartsResult);

        // Hämta det första värdet i arrayen då det frågan är ställd så att detta är det största värdet
        $maxPartsAmount = $maxPartsArray[0];
    }


// Ställ frågan till databasen, $searchQuery skapades i den inkluderade filen query.php ovan
    $result	= mysqli_query($connection, "$searchQuery");


// Hämta hur många rader som funnits i resultatet om det inte funnits någon LIMIT
// Måste göras direkt efter att frågan ställts eftersom det inte sparas annars
// Detta behövs för att få fram om next-knappen ska visas eller ej, detta görs i en annan fil, samt för att visa antalet resultat sökningen gav
	$rowCountResult = mysqli_query($connection, "SELECT FOUND_ROWS()");
	
	$rowCount = mysqli_fetch_row($rowCountResult);
	
// Se om det gjorts en sökning genom att se om det definierats ett $where
// Se om sökningen gett ett resultat genom att se om $rowCount är noll eller om det har ett värde
// Om sökningen inte ger något resultat så visa ett felmeddelande
// Annars om en sökning gjorts och det finns ett resultat så inkludera filen som visar detta
	if($rowCount[0] == 0 && $where) {
        print '<div id="searchError">Your search generated no results. Please search for something else!</div>';
    }
    else if($rowCount[0] != 0 && $where) {
		// Inkludera filen som skriver ut resultatet av sökningen
			include "searchEngine/display.php";
    }


// Om en fråga ställdes så ska nu kopplingen till databasen stängas
    if($where) {
        mysqli_close($connection);
    }

?>
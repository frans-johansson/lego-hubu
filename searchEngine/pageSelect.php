<!-- Knappar för att välja sida -->

<form method="get">


<?php

// Läser in sökningens alla getparametrar och skapar hidden input av dem så sökningsparametrarna är med vid sidbyte
//Allt är inom if-satser så att html:en validerar


	if($_GET["col"]) {
		$col = $_GET["col"];
		print "<input type='hidden' name='col' value='$col'>";
	}
	
	if($_GET["set"]) {
		$set = $_GET["set"];
		print "<input type='hidden' name='set' value='$set'>";
	}
	
	if($_GET["par"]) {
		$par = $_GET["par"];
		print "<input type='hidden' name='par' value='$par'>";
	}
	
	if($_GET["yea"]) {
		$yea = $_GET["yea"];
		print "<input type='hidden' name='yea' value='$yea'>";
	}
	
	if($_GET["cat"]) {
		$cat = $_GET["cat"];
		print "<input type='hidden' name='cat' value='$cat'>";
	}
	
	if($_GET["p"]) {
		$p = $_GET["p"];
		print "<input type='hidden' name='p' value='$p'>";
	}
	
	if($_GET["f"]) {
		$f = $_GET["f"];
		print "<input type='hidden' name='f' value='$f'>";
	}
	
	if($_GET["exact"]) {
		$exact = $_GET["exact"];
		print "<input type='hidden' name='exact' value='$exact'>";
	}
	
	if($_GET["c"]){
		$c = $_GET["c"];
		print "<input type='hidden' name='c' value='$c'>";
	}

// Läs in vilken sida som användaren inne på, exemepel sida 1, sida 2 osv
	$pageNumber = $_GET["page"];


// Se om previous-knappen ska visas eller ej, alltså om användaren är inne på sida 0 eller inte
	if($pageNumber > 0){
		// Ange vilken sida som ska bytas till
		$prev = $pageNumber-1;
		print "<button id='prevPage' type='submit' name='page' value='$prev'>Previous</button>";
	}

// Läs in vilken sida användaren beifnner sig på, parts eller sets
	$page = $_GET["p"];


// Koppla upp mot databasen
	include "searchEngine/connect.php";
	
// Se vilken sida användaren befinner sig på och bestäm frågan utefter det
	if($page == sets) {
		$countResultQuery = "SELECT COUNT(DISTINCT sets.SetID) FROM inventory, sets, colors WHERE sets.SetID = inventory.SetID AND ItemTypeID = 'P' AND inventory.ColorID = colors.ColorID $where";
	}
	else if($page == parts) {
		$countResultQuery = "SELECT COUNT(PartID) FROM inventory, parts, sets, colors WHERE sets.SetID = inventory.SetID AND ItemTypeID = 'P' AND inventory.ColorID = colors.ColorID AND ItemID = PartID $where GROUP BY PartID, Colorname";
	}
	
// Ställ frågan till databasen
	$countResult = mysqli_query($connection, "$countResultQuery");

// Hämta arrayen med resultatet
	$rowCountArray = mysqli_fetch_array($countResult);
	
// Hämta resultatet från arrayen
	if($page == sets) {
		$rowCount = $rowCountArray["COUNT(DISTINCT sets.SetID)"];
	}
	else if($page == parts) {
		$rowCount = $rowCountArray["COUNT(PartID)"];
	}
	
	
	print "$rowCount";
	

// Om antalet rader i resultatet är samma som antalet som ska visas så ska det finnas en next-knapp för att se resten
	if($rowCount && $rowCount > $displaylimit + $displayFrom) {
		// Next-knappen skrivs ut och leder till sidan som kommer eefter den närvarande
		$next = $pageNumber+1;
		print "<button id='nextPage' type='submit' name='page' value='$next'>Next</button>";
	} // TEST 3514-1

?>


</form>

<!-- Knappar för att välja sida -->
<div id='pageSelect'>
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
	if(!$_GET["page"])
		$pageNumber = 0;

// Läs in vilken sida användaren befinner sig på, parts eller sets
	$page = $_GET["p"];

// Koppla upp mot databasen
	include "searchEngine/connect.php";
	
// First page knapp
	if($pageNumber != 0)
	print "<button class='pageSelectButton firstNextPrevLastPage' type='submit' name='page' value=0>First</button>";
	
// Se om previous-knappen ska visas eller ej, alltså om användaren är inne på sida 0 eller inte
	if($pageNumber > 0){
		// Ange vilken sida som ska bytas till
		$prev = $pageNumber-1;
		print "<button class='pageSelectButton firstNextPrevLastPage' type='submit' name='page' value='$prev'>Previous</button>";
	}
	
/* 
	Välj sida med klickbara siffror
*/

// Skapar array av nummer att välja sidor med, den nuvarande sidan befinner sig i mitten om plats finns på sidorna
	$totalPages = ceil($rowCount[0] / $displayLimit) - 1;
	$amountPageSelectors = 15 - 1;
	$pageSelectArray[0] = $pageNumber;
	$fillLower = true;
	$lowerPage = $pageNumber - 1;
	$higherPage = $pageNumber + 1;
	for($i = 1; $i <= $amountPageSelectors; $i++) {
		if($lowerPage >= 0 && $fillLower) {
			$pageSelectArray[$i] = $lowerPage;
			$fillLower = false;
			$lowerPage--;
		} else if($higherPage <= $totalPages) {
			$pageSelectArray[$i] = $higherPage;
			$fillLower = true;
			$higherPage++;
		} else if($lowerPage >= 0) {
			$pageSelectArray[$i] = $lowerPage;
			$lowerPage--;
		} else {	
			break;
		}
	}
// Sortera arrayen i storleksordning
	sort($pageSelectArray);
// Skriv ut alla knapparna
	for($j = 0; $j < count($pageSelectArray); $j++) {
		$pageSelectValue = $pageSelectArray[$j];
		// Är knappen för sidan man är på deaktiveras den och får ett Id för att förtydliga med CSS
		$disabler = "";
		if($pageSelectValue == $pageNumber)
			$disabler = "disabled id='currentPageNumberButton'";
		print "<button class='pageSelectButton numberPage' type='submit' name='page' value='$pageSelectValue' $disabler>$pageSelectValue</button>";
	}
	

// Om antalet rader i resultatet är samma som antalet som ska visas så ska det finnas en next-knapp för att se resten
		if($pageNumber < $totalPages) {
		// Next-knappen skrivs ut och leder till sidan som kommer eefter den närvarande
		$next = $pageNumber+1;
		print "<button class='pageSelectButton firstNextPrevLastPage' type='submit' name='page' value='$next'>Next</button>";
	}
	
// Last page knapp
	if($pageNumber != $totalPages && $rowCount[0] != 0)
	print "<button class='pageSelectButton firstNextPrevLastPage' type='submit' name='page' value='$totalPages'>Last</button>";

?>
</form>
</div>
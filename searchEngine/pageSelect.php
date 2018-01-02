<!-- Knappar för att välja sida -->




<!-- Anslut till javascript, som manipulerar länkarnas/knapparnas href -->
<!-- <script src="searchFormManipulate.js"></script> -->

<form method="get">


<?php

// Lägg till kommentarer för detta den som vet vad detta gör

$col = $_GET["col"];
print '<input type="hidden" name="col" value="$col">';
$set = $_GET["set"];
print '<input type="hidden" name="set" value="$set">';
$par = $_GET["par"];
print '<input type="hidden" name="par" value="$par">';
$yea = $_GET["yea"];
print '<input type="hidden" name="yea" value="$yea">';
$cat = $_GET["cat"];
print '<input type="hidden" name="cat" value="$cat">';
$p = $_GET["p"];
print '<input type="hidden" name="p" value="$p">';
$f = $_GET["f"];
print '<input type="hidden" name="f" value="$f">';


// Läs in vilken sida som användaren inne på, exemepel sida 1, sida 2 osv
$page = $_GET["page"];


// Se om previous-knappen ska visas eller ej, alltså om användaren är inne på sida 0 eller inte
if($page > 0){
    // Ange vilken sida som ska bytas till
	$prev = $page-1;
	print "<button id='prevPage' type='submit' name='page' value='$prev'>Previous</button>";
}

// Ta bort dessa rader när vi testat att if-satsen nedan fungerar som den ska
// Räkna ut om next-knappen ska visas eller ej
//$upperLimit = floor($rowcount / 20);


// Om antalet rader i resultatet är samma som antalet som ska visas så ska det finnas en next-knapp för att se resten
if($rowcount == $displaylimit) {
	$next = $page+1;
	print '<button id="nextPage" type="submit" name="page" value="' . $next . '">Next</button>';
} // TEST 3514-1

// Om en sökning ger exakt 20 svar så kommer next-knappen att visas ändå
// Eventuell lösning, separat SQL-fråga som enbart räknar antalet resultat med en COUNT-funktion, eller lägg in detta i frågan?

?>


</form>


<!-- Knapparna -->
<!--
<a id="prevlink" href="">Förra</a>
<a id="nextlink" href="">Nästa</a>
-->

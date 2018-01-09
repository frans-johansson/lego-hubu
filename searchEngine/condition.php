<?php

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

                $whereString .= "$condition1 LIKE '%$getArray[$i]%'";

                // Om det är två villkor som ska uppfyllas, lägg till det andra
                    if($condition2) {
                        $whereString .= " OR $condition2 LIKE '%$getArray[$i]%'";
                    }
            }
            else if($precision){        // Om sökningen är exakt så formulera frågan på detta sätt

                $whereString .= "$condition1 = '$getArray[$i]'";

                // Om det är två villkor som ska uppfyllas, lägg till det andra
                    if($condition2) {
                        $whereString .= " OR $condition2 = '$getArray[$i]'";
                    }
            }

            // Om det söktes på flera saker inom samma kategori, lägg till ett OR innan loopen börjas om
                if($i != $length-1) {
                    $whereString .= " OR ";
                }
        } // For-loopen avslutas


        // Lägg sökvillkoret inom parantes så att eventuella OR inte gör att frågan blir fel
            $whereGet = "($whereString)";	// Kom på bättre variabelnamn!

        // Lägg ihop till en fråga
            $where .= " AND $whereGet $whereAdd";

        // Returnera sökvillkoret
            return $where;
    }

?>

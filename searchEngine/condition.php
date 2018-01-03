<?php

// Funktion f�r att formulera s�kvillkoret f�r SQL-fr�gan

function getToSQL($getpara, $condition1, $condition2, $whereAdd){

    // H�mta s�kordet/s�korden och dela upp dem f�r sig
        $getArray = splitGet($getpara);

    // Ta fram hur m�nga saker som s�kts p�
        $length = count($getArray);

    // Kolla om anv�ndaren kryssat i exakt s�kning eller om s�kningen ska vara ungef�rlig
        $precision = $_GET["exact"];


    // Formulera villkoren f�r sj�lva fr�gan
        for($i = 0; $i < $length; $i++) {

            // Om s�kningen inte �r exakt s� formulera fr�gan p� detta s�tt
            if(!$precision) {

                $whereString .= "$condition1 LIKE '%$getArray[$i]%'";

                // Om det �r tv� villkor som ska uppfyllas, l�gg till det andra
                    if($condition2) {
                        $whereString .= " OR $condition2 LIKE '%$getArray[$i]%'";
                    }
            }
            else if($precision){        // Om s�kningen �r exakt s� formulera fr�gan p� detta s�tt

                $whereString .= "$condition1 = '$getArray[$i]'";

                // Om det �r tv� villkor som ska uppfyllas, l�gg till det andra
                    if($condition2) {
                        $whereString .= " OR $condition2 = '$getArray[$i]'";
                    }
            }

            // Om det s�ktes p� flera saker inom samma kategori, l�gg till ett OR innan loopen b�rjas om
                if($i != $length-1) {
                    $whereString .= " OR ";
                }
        } // For-loopen avslutas


        // L�gg s�kvillkoret inom parantes s� att eventuella OR inte g�r att fr�gan blir fel
            $whereGet = "($whereString)";	// Kom p� b�ttre variabelnamn!

        // L�gg ihop till en fr�ga
            $where .= " AND $whereGet $whereAdd";

        // Returnera s�kvillkoret
            return $where;
    }

?>

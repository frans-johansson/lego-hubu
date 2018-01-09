<?php

    // Koppla upp mot databasen
        include "searchEngine/connect.php";


	// Fråga för att få fram antalet satser som finns i samlingen
        $result = mysqli_query($connection, "SELECT SUM(Quantity) FROM collection");


	// Medan ett resultat finns, skriv ut detta
        while($row = mysqli_fetch_array($result)) {

            // Lägg antalet satser i en variabel
            $Sets = $row["SUM(Quantity)"];

            // Skriv ut antalet satser
            print "<p class='numprofile'>$Sets</p>";
        }


	// Stäng kopplingen
        mysqli_close($connection);

?>

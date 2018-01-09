<?php

    // Koppla upp mot databasen
        include "searchEngine/connect.php";

	// Fråga till databasen för att få fram antalet bitar som finns
        $result = mysqli_query($connection, "SELECT COUNT(PartID) FROM parts");
        // DISTINCT behövs ej eftersom varje PartID bara förekommer en gång i tabellen parts

	// Medan det finns resultat, skriv ut detta
        while($row = mysqli_fetch_array($result)) {

            // Spara antalet bitar i en variabel
            $Parts = $row["COUNT(PartID)"];

            // Skriv ut antalet bitar
            print "<p>$Parts</p>";
        }

    // Stäng kopplingen
        mysqli_close($connection);

?>

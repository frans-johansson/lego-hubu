<?php

    // Koppla upp mot databasen
        include "searchEngine/connect.php";

	// Fråga till databasen för att få fram antalet satser som finns
        $result = mysqli_query($connection, "SELECT COUNT(SetID) FROM sets");
        // DISTINCT behövs ej eftersom varje SetID bara förekommer en gång i tabellen sets


	// Medan det finns resultat, skriv ut dem
        while($row = mysqli_fetch_array($result)) {

            // Spara antalet satser i en variabel
            $Sets = $row["COUNT(SetID)"];

            // Skriv ut antalet satser
            print "<p class='num'>$Sets</p>";
        }

	// Stäng kopplingen
        mysqli_close($connection);

?>

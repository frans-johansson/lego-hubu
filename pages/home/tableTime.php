<?php

    // Koppla upp mot databasen
        include "searchEngine/connect.php";

	// Fråga för att få fram antalet nya satser för varje år
        $result = mysqli_query($connection, "SELECT Year, COUNT(SetID) FROM sets
                                                GROUP BY Year ORDER BY Year ASC");
    // DISTINCT behövs ej eftersom varje SetID bara förekommer en gång i tabellen sets


	// Medan det finns resultat, skriv ut detta
        while($row = mysqli_fetch_array($result)) {
            // Spara året och antalet satser i varsin variabel
            $Year = $row["Year"];
            $Quantity = $row["COUNT(SetID)"];

            // Skriv ut året och antalet satser
            print "<tr><td>$Year</td><td>$Quantity</td></tr>";
        }

	// Stäng kopplingen
        mysqli_close($connection);

?>

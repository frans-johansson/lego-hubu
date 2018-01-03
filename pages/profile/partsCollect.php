<?php

    // Koppla upp mot databasen
        include "searchEngine/connect.php";

	// Fråga för att få fram antalet bitar som finns i samlingen
        $result = mysqli_query($connection, "SELECT SUM(inventory.Quantity*collection.Quantity)
                                FROM inventory, collection WHERE inventory.SetID = collection.SetID");


	// Medan ett resultat finns, skriv ut detta
        while($row = mysqli_fetch_array($result)) {

            // Lägg antalet bitar i en variabel
            $Parts = $row["SUM(inventory.Quantity*collection.Quantity)"];

            // Skriv ut antalet bitar
            print "<p>$Parts</p>";
        }

	// Stäng kopplingen
        mysqli_close($connection);

?>

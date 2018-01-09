<?php

	$displayFrom = $lowerLimit * $displaylimit; 

    if($page == parts) {
        if($_GET["set"]) {
            // Skapa sökfrågan som är specifik för om man sökt på ett set i parts (Detta är ett specialfall)
            $searchQuery = "SELECT PartID, Partname, Colorname, COUNT(DISTINCT inventory.SetID), MIN(Year)
                            FROM parts, inventory, sets, colors $table WHERE PartID = ItemID AND inventory.ColorID = colors.ColorID
                            AND ItemTypeID = 'P' AND inventory.SetID = sets.SetID AND (ItemID, Colorname) IN
                            (SELECT ItemID, Colorname FROM inventory, sets, colors WHERE sets.SetID = inventory.SetID
                            AND colors.ColorID = inventory.ColorID $where) GROUP BY $group
                            ORDER BY $order LIMIT $displayFrom, $displaylimit";
        }
        else {
            // Skapa sökfrågan så som den ska se ut annars
            $searchQuery = "SELECT	PartID, Partname, Colorname, COUNT(DISTINCT inventory.SetID), MIN(Year)
                            FROM parts, inventory, sets, colors $table WHERE PartID = ItemID AND
                            inventory.ColorID = colors.ColorID AND ItemTypeID = 'P' AND
                            inventory.SetID = sets.SetID $where GROUP BY $group
                            ORDER BY $order LIMIT $displayFrom, $displaylimit";
        }
    }
    else if($page == sets) {
        $searchQuery = "SELECT sets.SetID, Setname, MIN(Year), SUM(Quantity) FROM sets, inventory $table WHERE ItemTypeID = 'P'
                        AND sets.SetID = inventory.SetID $where GROUP BY $group ORDER BY $order LIMIT $displayFrom, $displaylimit";

        // Skapa en sökfråga för att få fram vilket set som innehåller flest bitar utav alla i resultatet
        // Detta är nödvändig information för hur histogrammet ska ritas upp
        $maxPartsQuery = "SELECT SUM(Quantity) FROM sets, inventory $table WHERE ItemTypeID = 'P' AND sets.SetID = inventory.SetID $where
                         GROUP BY $group ORDER BY SUM(Quantity) DESC LIMIT 1";
    }

?>

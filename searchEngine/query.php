<?php

    if($page == parts) {
        if($_GET["set"]) {
            // Skapa s�kfr�gan som �r specifik f�r om man s�kt p� ett set i parts (Detta �r ett specialfall)
            $searchQuery = "SELECT PartID, Partname, Colorname, COUNT(DISTINCT inventory.SetID), MIN(Year)
                            FROM parts, inventory, sets, colors $table WHERE PartID = ItemID AND inventory.ColorID = colors.ColorID
                            AND ItemTypeID = 'P' AND inventory.SetID = sets.SetID AND (ItemID, Colorname) IN
                            (SELECT ItemID, Colorname FROM inventory, sets, colors WHERE sets.SetID = inventory.SetID
                            AND colors.ColorID = inventory.ColorID $where) GROUP BY $group
                            ORDER BY $order LIMIT $lowerLimit * $displaylimit, $displaylimit";
        }
        else {
            // Skapa s�kfr�gan s� som den ska se ut annars
            $searchQuery = "SELECT	PartID, Partname, Colorname, COUNT(DISTINCT inventory.SetID), MIN(Year)
                            FROM parts, inventory, sets, colors $table WHERE PartID = ItemID AND
                            inventory.ColorID = colors.ColorID AND ItemTypeID = 'P' AND
                            inventory.SetID = sets.SetID $where GROUP BY $group
                            ORDER BY $order LIMIT $lowerLimit * $displaylimit, $displaylimit";
        }
    }
    else if($page == sets) {
        $searchQuery = "SELECT sets.SetID, Setname, MIN(Year), SUM(Quantity) FROM sets, inventory $table WHERE ItemTypeID = 'P'
                        AND sets.SetID = inventory.SetID $where GROUP BY $group ORDER BY $order LIMIT $lowerLimit * $displaylimit, $displaylimit";

        // Skapa en s�kfr�ga f�r att f� fram vilket set som inneh�ller flest bitar utav alla i resultatet
        // Detta �r n�dv�ndig information f�r hur histogrammet ska ritas upp
        $maxPartsQuery = "SELECT SUM(Quantity) FROM sets, inventory $table WHERE ItemTypeID = 'P' AND sets.SetID = inventory.SetID $where
                         GROUP BY $group ORDER BY SUM(Quantity) DESC LIMIT 1";
    }

?>

<?php

	$displayFrom = $lowerLimit * $displaylimit; 

    if($page == parts) {
        /*if($_GET["set"]) {
            // Skapa sökfrågan som är specifik för om man sökt på ett set i parts (Detta är ett specialfall)
            $searchQuery = "SELECT SQL_CALC_FOUND_ROWS PartID, Partname, Colorname, COUNT(DISTINCT inventory.SetID), MIN(Year)
                            FROM parts, inventory, sets, colors $table WHERE PartID = ItemID AND inventory.ColorID = colors.ColorID
                            AND ItemTypeID = 'P' AND inventory.SetID = sets.SetID AND (ItemID, Colorname) IN
                            (SELECT ItemID, Colorname FROM inventory, sets, colors WHERE sets.SetID = inventory.SetID
                            AND colors.ColorID = inventory.ColorID $where) GROUP BY $group
                            ORDER BY $order LIMIT $displayFrom, $displaylimit";
        }
        else {
			
			Kommentar: Vi skapade den fråga som innehåller en IN SELECT för att kunna söka på satser på sidan bitar och då få fram vilka bitar som
			ingår i satsen, i vilken färg den förekommer i inuti satsen, vilket år biten först kom och hur många satser den ingår i. Samma information
			som man får fram annars helt enkelt. Det gick inte att lösa på något annat sätt, så vitt vi kom på åtminstone, för när man sökte på en sats
			med hjälp av frågan nedan så blev informationen fel, för då tittade den på hur många satser biten ingår i så länge som satsen heter det du
			sökt på, alltså bara en sats, och året blev även året då satsen kom istället för året då biten först kom, eftersom den sökte bara där satsen
			var den eftersökta. Frågan ovan fungerar och visar rätt resultat, alltså rätt utgivningsår och rätt antal satser för bitarna so ingår i den
			sats som man sökt på, men den tar över 30 minuter att ladda, vilket vi absolut inte upplever är rimligt, och vi har därför valt att ta bort
			möjligheten att söka på satser på sidan parts. Frågan fungerar som sagt dock, den tar bara orimligt lång tid.
			
			*/
            // Skapa sökfrågan så som den ska se ut annars
            $searchQuery = "SELECT SQL_CALC_FOUND_ROWS PartID, Partname, Colorname, COUNT(DISTINCT inventory.SetID), MIN(Year)
                            FROM parts, inventory, sets, colors $table WHERE PartID = ItemID AND
                            inventory.ColorID = colors.ColorID AND ItemTypeID = 'P' AND
                            inventory.SetID = sets.SetID $where GROUP BY $group
                            ORDER BY $order LIMIT $displayFrom, $displaylimit";
        //}
    }
    else if($page == sets) {
        $searchQuery = "SELECT SQL_CALC_FOUND_ROWS sets.SetID, Setname, MIN(Year), SUM(inventory.Quantity) FROM sets, inventory $table WHERE ItemTypeID = 'P'
                        AND sets.SetID = inventory.SetID $where GROUP BY $group ORDER BY $order LIMIT $displayFrom, $displaylimit";

        // Skapa en sökfråga för att få fram vilket set som innehåller flest bitar utav alla i resultatet
        // Detta är nödvändig information för hur histogrammet ska ritas upp
        $maxPartsQuery = "SELECT SUM(inventory.Quantity) FROM sets, inventory $table WHERE ItemTypeID = 'P' AND sets.SetID = inventory.SetID $where
                         GROUP BY $group ORDER BY SUM(inventory.Quantity) DESC LIMIT 1";
    }

?>

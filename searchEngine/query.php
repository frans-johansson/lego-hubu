<?php

	$displayFrom = $lowerLimit * $displaylimit; 

    if($page == parts) {
        /*if($_GET["set"]) {
            // Skapa s�kfr�gan som �r specifik f�r om man s�kt p� ett set i parts (Detta �r ett specialfall)
            $searchQuery = "SELECT SQL_CALC_FOUND_ROWS PartID, Partname, Colorname, COUNT(DISTINCT inventory.SetID), MIN(Year)
                            FROM parts, inventory, sets, colors $table WHERE PartID = ItemID AND inventory.ColorID = colors.ColorID
                            AND ItemTypeID = 'P' AND inventory.SetID = sets.SetID AND (ItemID, Colorname) IN
                            (SELECT ItemID, Colorname FROM inventory, sets, colors WHERE sets.SetID = inventory.SetID
                            AND colors.ColorID = inventory.ColorID $where) GROUP BY $group
                            ORDER BY $order LIMIT $displayFrom, $displaylimit";
        }
        else {
			
			Kommentar: Vi skapade den fr�ga som inneh�ller en IN SELECT f�r att kunna s�ka p� satser p� sidan bitar och d� f� fram vilka bitar som
			ing�r i satsen, i vilken f�rg den f�rekommer i inuti satsen, vilket �r biten f�rst kom och hur m�nga satser den ing�r i. Samma information
			som man f�r fram annars helt enkelt. Det gick inte att l�sa p� n�got annat s�tt, s� vitt vi kom p� �tminstone, f�r n�r man s�kte p� en sats
			med hj�lp av fr�gan nedan s� blev informationen fel, f�r d� tittade den p� hur m�nga satser biten ing�r i s� l�nge som satsen heter det du
			s�kt p�, allts� bara en sats, och �ret blev �ven �ret d� satsen kom ist�llet f�r �ret d� biten f�rst kom, eftersom den s�kte bara d�r satsen
			var den efters�kta. Fr�gan ovan fungerar och visar r�tt resultat, allts� r�tt utgivnings�r och r�tt antal satser f�r bitarna so ing�r i den
			sats som man s�kt p�, men den tar �ver 30 minuter att ladda, vilket vi absolut inte upplever �r rimligt, och vi har d�rf�r valt att ta bort
			m�jligheten att s�ka p� satser p� sidan parts. Fr�gan fungerar som sagt dock, den tar bara orimligt l�ng tid.
			
			*/
            // Skapa s�kfr�gan s� som den ska se ut annars
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

        // Skapa en s�kfr�ga f�r att f� fram vilket set som inneh�ller flest bitar utav alla i resultatet
        // Detta �r n�dv�ndig information f�r hur histogrammet ska ritas upp
        $maxPartsQuery = "SELECT SUM(inventory.Quantity) FROM sets, inventory $table WHERE ItemTypeID = 'P' AND sets.SetID = inventory.SetID $where
                         GROUP BY $group ORDER BY SUM(inventory.Quantity) DESC LIMIT 1";
    }

?>

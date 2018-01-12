<?php

    if($page == parts) {
        // Skriv ut tabellhuvudena
        print "<div id='searchSuccess'>Thank you for your patience. Your search generated $rowCount[0] results.</div>
				<table>
					<tr>
						<th>Image</th>
						<th>ID</th>
						<th>Name</th>
						<th>Color</th>
						<th>Included in sets</th>
						<th>Release year</th>
					</tr>";
    }
    else if($page == sets) {
        // Skriv ut tabellhuvudena
        print "<div id='searchSuccess'>Thank you for your patience. Your search generated $rowCount[0] results.</div>
				<table>
					<tr>
						<th id='idColumn' class='dataColumn'>ID</th>
						<th class='dataColumn'>Name</th>
						<th class='dataColumn'>Release Year</th>
						<th id='histogramColumn' colspan='2'>Number of parts</th>
					</tr>";
    }


// Hämta resultatet igen, nödvändigt eftersom SQL är konstigt och arrayen har blivit tom vid det här laget? HJGDJGDJGD
    $result	= mysqli_query($connection, "$searchQuery");
	

// Hämta arrayen med resultatet igen, annars kör den inte igenom arrayen utan visar bara resultatet för samma bit om och om igen i alla evighet
    while($row = mysqli_fetch_array($result)) {

        // Gå igenom och ändra i detta nu när bättre lösning funnen
        if($page == parts) {
            // Lägg informationen som ska visas i separata variabler
                $ID = $row["PartID"];
                $Partname = $row["Partname"];
                $Color = $row["Colorname"];
                $numSets = $row["COUNT(DISTINCT inventory.SetID)"];
                $Year = $row["MIN(Year)"];

            // Fråga efter den information som är relevant för att få fram en bild
                $info = mysqli_query($connection, "SELECT colors.ColorID, ItemTypeID, has_gif, has_jpg, has_largegif, has_largejpg
                                FROM images, colors WHERE ItemID = '$ID' AND Colorname = '$Color' AND colors.ColorID = images.ColorID");

            // Hämta arrayen med denna information
                $format = mysqli_fetch_array($info);

            // Lägg den nödvändiga informationen för bildnamnet i variabler
                $Itemtype = $format["ItemTypeID"];
                $ColorID = $format["ColorID"];


            // Bilda länken till den bild som ska visas
                if($format["has_jpg"]) {
                    $name = "$Itemtype/$ColorID/$ID.jpg";
                    $link = "http://www.itn.liu.se/~stegu76/img.bricklink.com/$name";
                }
                else if($format["has_gif"]) {
                    $name = "$Itemtype/$ColorID/$ID.gif";
                    $link = "http://www.itn.liu.se/~stegu76/img.bricklink.com/$name";
                }
                else if($format["has_largejpg"]) {
                    $name = $Itemtype . "L/$ID.jpg";
                    $link = "http://www.itn.liu.se/~stegu76/img.bricklink.com/$name";
                }
                else if($format["has_largegif"]) {
                    $name = $Itemtype . "L/$ID.gif";
                    $link = "http://www.itn.liu.se/~stegu76/img.bricklink.com/$name";
                }

            // Skriv ut detta i tabellen
                print "<tr><td><img class='partpicture' src=\"$link\" alt=\"$name\"></td><td>$ID</td><td>$Partname</td>
                        <td>$Color</td><td>$numSets</td><td>$Year</td></tr>";
        }
        else if($page == sets) {
            // Lägg informationen som ska visas i separata variabler
                $ID = $row["SetID"];
                $Setname = $row["Setname"];
                $Year = $row["MIN(Year)"];
                $numParts = $row["SUM(inventory.Quantity)"];

            // Beräkna den relativa bredden av varje histogrampelare
                $percentage = 100 * $numParts / $maxPartsAmount;


            // Skriv ut detta i tabellen
                print "<tr><td class=\"dataColumn\">$ID</td><td class=\"dataColumn\">$Setname</td><td class=\"dataColumn\">$Year</td><td class=\"partsAmount\">$numParts</td><td class=\"histogramCell\">
                      <div class=\"histogram\" style=\"width: $percentage%\"></div></td></tr>";
        }
    }
	
	print "</table>";

?>

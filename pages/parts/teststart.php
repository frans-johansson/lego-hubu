  <?php
  $connection	=	mysqli_connect("mysql.itn.liu.se","lego","","lego"); //öppnar upp kontakt med databasen

          $result	=	mysqli_query($connection,	'SELECT inventory.SetID, inventory.Quantity, inventory.ItemID, colors.Colorname, colors.ColorID, parts.Partname, 
          parts.PartID FROM inventory, colors, parts WHERE 
           inventory.ItemtypeID="P" AND colors.ColorID=inventory.ColorID 
          AND parts.PartID=inventory.ItemID LIMIT 3  ');
		  while	($row	=	mysqli_fetch_array($result))	{
			//skapar variabler med information från den rad jag befinner mig i databasen
				$set	=	$row['SetID'];
                $antal	=	$row['Quantity'];
                $color	=	$row['Colorname'];
                $part	=	$row['PartID'];	
	            $colorid	=	$row['ColorID'];
				
				
			
				
				
				//ställer frågor för att få fram det jag behöver för att veta om bilden är en gif eller jpg
				$images	=	mysqli_query($connection,	"SELECT * FROM images WHERE ItemID='$part' AND ColorID='$colorid' ");
				$images1	=	mysqli_fetch_array($images);
				//skapar variabel beroende på om gif eller jpg
				if($images1['has_gif']){
					$format='.gif';
				}
				if($images1['has_jpg']){
                     $format='.jpg';
				}

	            print("<tr><td>$set</td>");																																																																	
				print("<td>P/$colorid/$part$format</td>");        																																				
				print("<td>$color</td>");				              																																					
				print("<td>$antal</td>");
				print("<td><img src=\"http://weber.itn.liu.se/~stegu76/img.bricklink.com/P/$colorid/$part$format\" alt=\"Lego\"></td></tr>");
			
																																																			
		}	//	end	while
		print("</table>");
		mysqli_close($connection);	
 ?>

<?php
//	Skriv ut alla poster i svaret																																		
while ($row = mysqli_fetch_array($result)) {																						
	$heading = $row['SetID'];																															
	print("<h2>$heading</h2>\n");																																																
}	//	end	while	

?>
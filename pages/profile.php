<!-- Kod för profile pop up -->
<div id="pageBackground">
	<!-- När man klickar på profil bilden kommer profil pop up up -->
	<div id="profilePopup" onclick="togglePopup()">
	
		<!-- Själva profil innehållet -->
		<h1 id="profileheader">Your collection</h1>
		
		<div id="profileWrapper">
		
		<img  id="profilePartslarge" src="style/legoprofillarge.jpg" alt="Profile picture">
			
			<div id="profilePSwrapper">
			
			<div id="profileParts">
				<p class="profileinfo" >Parts</p>
				<?php
					include "profile/partsCollect.php";
				?>
			</div>
			
			<div id="profileSets">
				
				<p class="profileinfo" >Sets</p>
				<?php
					include "profile/setsCollect.php";
				?>
			</div>
			
			</div>
			
		</div>
	</div>
</div>
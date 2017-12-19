<div id="profilePopup" onclick="togglePopup()">
	<h1>Your collection</h1>
	<div id="profileWrapper">
		<div id="profileSets">
			<p>Sets</p>
			<?php
				include "profile/setsCollect.php";
			?>
		</div>
		<div id="profileParts">
			<p>Parts</p>
			<?php
				include "profile/partsCollect.php";
			?>
		</div>
	</div>
</div>
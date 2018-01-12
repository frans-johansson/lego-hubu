<div id="pageBackground">
	<div id="profilePopup" onclick="togglePopup()">
		<h1 id="profileheader">Your collection</h1>
		<div id="profileWrapper">
			<div id="profileSets">
				<p class="profileinfo" >Sets</p>
				<?php
					include "profile/setsCollect.php";
				?>
			</div>
			<div id="profileParts">
				<p class="profileinfo" >Parts</p>
				<?php
					include "profile/partsCollect.php";
				?>
			</div>
		</div>
	</div>
</div>
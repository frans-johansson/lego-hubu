<!doctype html>

<html>
	<head>
		<?php
			include "pages/res/head.php";
		?>
	</head>
	<body>
		<?php
			include "pages/profile.php";

			if (!isset($_GET['p']))
			{
				include "pages/home.php";
			}
			else
			{
				$page = $_GET['p'];	
				include "pages/$page.php";
			}
		?>
	</body>
</html>

<!-- Alla länkar som behövs beroende på vilken sida av hemsidan man är på -->
<meta charset="utf-8">
<title>Legu Hubu</title>
<link type="text/css" rel="stylesheet" href="style/style.css">
<link href='https://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet'>
<?php
	$page = $_GET['p'];

	if($page == 'home' || !isset($_GET['p'])) /* Inkluderar rätt JS för home */ {
		print "<script src=\"scripts/profile.js\"></script>";
		print "<script src=\"scripts/canvas.js\"></script>";
	}
	else if ($page == 'sets') /* Inkluderar rätt JS för sets */ {
		print "<script src=\"scripts/profile.js\"></script>";
		print "<script src=\"scripts/searchbar.js\"></script>";
	}
	else if ($page == 'parts') /* Inkluderar rätt JS för parts */ {
		print "<script src=\"scripts/profile.js\"></script>";
		print "<script src=\"scripts/searchbar.js\"></script>";
	}
?>
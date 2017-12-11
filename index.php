<?php
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

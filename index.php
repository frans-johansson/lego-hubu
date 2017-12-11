<?php
    if (!isset($_GET['page']))
    {
        include "pages/home.php";
    }
	else
	{
		$page = $_GET['page'];	
		include "pages/$page.php";
	}
?>

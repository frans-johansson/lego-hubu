<?php

    //	Koppla	upp	mot	databasen, ska endast läsas
        $connection	= mysqli_connect("mysql.itn.liu.se","lego","","lego");

    // Kolla im uppkopplingen misslyckades och visa i så fall ett felmeddelande
		if(mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
?>

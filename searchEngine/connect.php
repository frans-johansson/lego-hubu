<?php

    //	Koppla	upp	mot	databasen, ska endast läsas
        $connection	= mysqli_connect("mysql.itn.liu.se","lego","","lego");

    // Kolla im uppkopplingen misslyckades och visa i så fall ett felmeddelande
        if (!$connection) {
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }

?>

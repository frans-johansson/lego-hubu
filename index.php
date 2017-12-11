<?php
    if (!isset($GET['page']))
    {
        include "pages/home.php";
    }
    else if ($GET['page'] == "home")
    {
        include "pages/home.php";
    }
    else if ($GET['page'] == "sets")
    {
        include "pages/sets.php";
    }
    else if ($GET['page'] == "parts")
    {
        include "pages/parts.php";
    }
?>

<div id="page">
        <!--Inkludera fil med övre sidinnehållet-->
        <?php
            include "upperHome.php";
			include "diagram.html";
        ?>

		<!--Inkludera informationen om satser per år-->
		<table id="diagramData">
		<?php
			include "tableTime.php";
		?>
		</table>
        <!--Inkludera diagram-fil-->
</div>

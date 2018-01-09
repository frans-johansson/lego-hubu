<div id="page">
        <!--Inkludera fil med övre sidinnehållet-->
        <?php
            include "upperHome.html"
			
        ?>
		<!--Inkludera informationen om satser per år-->
		<table id="diagramData">
		<?php
			include "tableTime.php"
		?>
		</table>
        <!--Inkludera diagram-fil-->
        <?php
            include "diagram.html"
        ?>
</div>

<div id="page">
	<h1 class="pageHeader">SETS</h1>
	
    <!-- Inkludera sökfält -->
    <?php
        include "pages/res/searchbar.php";
    ?>

	<!-- Inkludera tabellen med histogram-->
    <?php
        include "pages/sets/setsTable.php";
		
		if($where) {
			include "searchEngine/pageSelect.php";
		}
    ?>
</div>

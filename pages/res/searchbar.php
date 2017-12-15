<div id="searchBar">

	<!-- Vänliga hjälpmeddelanden -->
	<noscript>
		VÄNLIGEN AKTIVERA JAVASCRIPT, TACK!
	</noscript>

	<form action="" method="get" onsubmit="setParams()">
		<div id="searchField">
			<!-- Skriv in sökord -->
			<input type="text" id="searchText" autocomplete="off" onkeyup="updateTagList()" onclick="updateTagList()">

			<!-- Lista med val av taggar -->
			<div id="tagList">
				<p class="tagOption" id="colorTag">Color: <span class="searchContent"></span> </p>
				<p class="tagOption" id="setTag">Set: <span class="searchContent"></span> </p>
				<p class="tagOption" id="partTag">Part: <span class="searchContent"></span> </p>
				<p class="tagOption" id="yearTag">Year: <span class="searchContent"></span> </p>
				<p class="tagOption" id="catTag">Category: <span class="searchContent"></span> </p>
			</div>
			
			<!-- För att få med sidans GET-variabel i sökningen (annars blir det okul) -->
			<?php
				$page = $_GET['p'];
				echo "<input type=\"hidden\" name=\"p\" value=\"$page\">";
			?>
			
			<!-- Samla taggar i dessa med JS -->
			<input id="colorTagList" type="hidden" name="col">
			<input id="setTagList" type="hidden" name="set">
			<input id="partTagList" type="hidden" name="par">
			<input id="yearTagList" type="hidden" name="yea">
			<input id="catTagList" type="hidden" name="cat">
		</div>
		
		<div id="tagContainer"></div>
		
		<label>
			Show only my collection
		</label>
		
		<!-- Lägg till checkbox och se till att den är markerad även efter sökning -->
		<?php
			if (isset($_GET['c']))
				echo "<input type=\"checkbox\" name=\"c\" value=\"true\" checked=\"checked\">";
			else
				echo "<input type=\"checkbox\" name=\"c\" value=\"true\">";
		?>
		
		<!-- Drop-down-lista för sorteringsalternativ -->
		<label>
			Filter
		</label>
		<select id="searchFilter" name="f">
			<option value="ageAsc">Oldest - Newest</option>
			<option value="ageDesc">Newest - Oldest</option>
			<option value="rarityAsc">Common - Rare</option>
			<option value="rarityDesc">Rare - Common</option>
		</select>
	</form>
</div>

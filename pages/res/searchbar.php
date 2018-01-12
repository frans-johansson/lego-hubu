<div id="searchBar">

	<!-- Vänliga hjälpmeddelanden -->
	<noscript>
		VÄNLIGEN AKTIVERA JAVASCRIPT, TACK!
	</noscript>

	<form method="get" onsubmit="doSubmit()">
		<div id="searchField">
			<!-- Skriv in sökord -->
			<input type="text" id="searchText" autocomplete="off" onkeyup="updateTagList()" onclick="updateTagList()">

			<?php
			
			$page = $_GET["p"];
			
			if($page == parts) {
				// Lista med val av taggar
				print '<div id="tagList" onmousemove="clearSelected()">
							<p class="tagOption" id="partTag">Part: <span class="searchContent"></span> </p>
							<p class="tagOption" id="colorTag">Color: <span class="searchContent"></span> </p>
							<p class="tagOption" id="setTag">Set: <span class="searchContent"></span> </p>
							<p class="tagOption" id="yearTag">Year: <span class="searchContent"></span> </p>
						</div>';
			} else if($page == sets) {
				print '<div id="tagList" onmousemove="clearSelected()">
							<p class="tagOption" id="setTag">Set: <span class="searchContent"></span> </p>
							<p class="tagOption" id="partTag">Part: <span class="searchContent"></span> </p>
							<p class="tagOption" id="colorTag">Color: <span class="searchContent"></span> </p>
							<p class="tagOption" id="yearTag">Year: <span class="searchContent"></span> </p>
						</div>';
			}
			
			// För att få med sidans GET-variabel i sökningen
				echo "<input type=\"hidden\" name=\"p\" value=\"$page\">";
			?>
			
			<!-- Samla taggar i dessa med JS -->
			<input id="colorTagList" type="hidden" name="col">
			<input id="setTagList" type="hidden" name="set">
			<input id="partTagList" type="hidden" name="par">
			<input id="yearTagList" type="hidden" name="yea">
		</div>
		
		<input type="submit" id="searchButton" value="Search">
		
		<div id="tagContainer"></div>
		<div class= "labelcontainer">
		<label>
			Exact search
		</label>
		
		<?php
			if (isset($_GET['exact']))
				echo "<input type=\"checkbox\" name=\"exact\" value=\"true\" checked=\"checked\">";
			else
				echo "<input type=\"checkbox\" name=\"exact\" value=\"true\">";
		?>
		</div>
		
		<div class= "labelcontainer">
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
		</div>
		<label>
			Filter
		</label>
		
		<?php
			
			// Läs in vilket filter som är satt, om något
			$filter = $_GET["f"];
			
			// Avgör vilket filter som ska synligt i menyn så att det valda filtret hänger med vid sökning och sidbyte
			if($filter == ageAsc || !$filter) {
				$oldNew = "selected";
			}
			else if($filter == ageDesc) {
				$newOld = "selected";
			}
			else if($filter == rarityAsc) {
				$commonRare = "selected";
			}
			else if($filter == rarityDesc) {
				$rareCommon = "selected";
			}
				
			// Skriv ut menyn för val av filter med rätt filter som förhandsvisas
			print 	"<select id='searchFilter' name='f'>
						<option value='ageAsc' $oldNew>Oldest - Newest</option>
						<option value='ageDesc' $newOld>Newest - Oldest</option>
						<option value='rarityAsc' $commonRare>Common - Rare</option>
						<option value='rarityDesc' $rareCommon>Rare - Common</option>
					</select>";
		
		?>
		
	</form>
</div>

<div id="loadBar">
	<span id="message">Loading</span> <span id="loadingDots"></span>
</div>

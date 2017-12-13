<div id="searchBar">
	<form action="" method="get">
		<div id="searchField">
			<!-- Skriv in sökord -->
			<input type="text" id="searchText" onkeyup="updateTagList()" onclick="updateTagList()">
			
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
			
			<!-- Samla taggar -->
			<input id="colorTagList" type="hidden" name="col">
			<input id="setTagList" type="hidden" name="set">
			<input id="partTagList" type="hidden" name="bri">
			<input id="yearTagList" type="hidden" name="yea">
			<input id="catTagList" type="hidden" name="cat">
		</div>
		
		<div id="tagContainer">
			tags hererrr
		</div>
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

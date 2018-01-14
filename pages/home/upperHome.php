<!-- Knapparna med statistik om satser och bitar -->
<div class="outerWrapper">
	<div class="wrapper" id="amountsWrapper">
		<a class="amountBox" href="?p=sets">
			<!-- Hur många Sets som finns, och en länk till sidan för Sets-->
			<h1>Total amount of sets</h1>
			<?php
				include "numSets.php"
			?>
	   </a>

	   <a class="amountBox" href="?p=parts">
		   <!-- Hur många Parts som finns, och en länk till sidan för Parts-->
			<h1>Total amount of parts</h1>
			<?php
				include "numParts.php"
			?>
	   </a>
	</div>
	<!-- Infotext -->
	<div class="wrapper">
	<div id="textBox">
		<h1 id="textHeader"> Welcome to Legu-Hubu </h1>
			<p> This is a site for lego fans, the perfect place for you to satisfy your lego-curiosity! In sets you can search for lego by color, year, set and/or parts
			and find which sets include what you searched for. In parts you can search for lego by color, year and/or parts and find out fascinating information about your
			favourite parts, such as what year they were released, what colors they exist in or how many sets they're included in. To search you just write in the searchbar
			and choose the tag you want to search by. You can search with more than one tag and choose in what order you want the search results to be displayed in. You can
			even choose to see only the search results that match with what you have in your own collection!
             </p>
			<p>
				This is a site were Lego fans such as yourself can find information about your favorite pass-time.
				If you are interested, for example, in knowing how many parts sets released in 1996 containing parts with "Volkswagen" in the name contains, why not head over to the <em>sets</em> section.
				There you can find out how many parts sets contains based on release year, part or set name, part or set ID as well as color.
				You can also look up individual parts based on color, release year as well as name or ID. To do this, head to the <em>parts</em> section.
			</p>
			<p>
				Want find out how many parts and sets <em>your own collection</em> contains? Click your handsome-looking profile picture up in the right-hand corner of the website!
             </p>
			<p>
				For the curious individual, generally interested in what these colorful pieces of plastic are all about, take a look at our brief summary down below. There you can also find an
				<em>interactive diagram</em>, showing how many new Lego sets have been released each year going back all the way to the beginning of... this database.
			</p>
			<p>
				This site is brought to you by a lovely group of students, also known as the Legu-Hubu team, as an examination project
				for the course <em>Electronic Publishing</em> at the <a href="https://liu.se/">University of Linköping.</a>
			</p>
			</div>
		</div>
	</div>

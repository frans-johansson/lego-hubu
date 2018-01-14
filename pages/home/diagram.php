<!-- Diagram som ritas upp med hjälp av canvas -->
<div id="information">
   <h1>So what is Lego?</h1>
	<p> Lego is a line of plastic construction toys that consists of interlocking bits, gears, minifigures and various other parts.
	Lego can be assembled in many different ways and then disassembled and be used again. It is made by the lego group, a family owned
	company based in denmark, billund founded by Ole Kirk Christiansen. Lego begun manufacturing in 1949 . Since then lego has become
	something more than just plastic bits that you can build new creations with. Under the brand there have been several legoland amusement
	parks, movies, games, competitions.
	</p>
	<p>The name lego was made by its founder Ole Kirk Christiansen. It comes from the danish word leg godt meaning “play well”.
	</p>
	<p>Legoland is a chain of family theme parks, that is not fully owned by the lego group . The first legoland opened in 1968 in billund denmark.
	Now you can find legolands in malaysia, japan, usa, United Arab Emirates, germany and england. One of the big attractions in legoland are models
	of landmarks and scenes from around the world, made from millions of genuine Lego bricks.
	</p>

   <div id="diagram">
   	<h2>Lego sets released through the years</h2>
      <p>
   		Interact with the diagram below using your mouse cursor to find out more about how many Lego sets have been released throughout the years!
      </p>

      <!-- Text som manipuleras med JS för att visa information från diagrammet -->
      <p id="diagramInformation"><span id="diagramAmount"></span> new Lego sets were released in <span id="diagramYear"></span></p>

      <!-- Här ritas diagrammet -->
      <canvas id="canvas" onmouseover="toggleInformation()" onmouseout="toggleInformation()"></canvas>
   </div>

   <!-- Visas för de som inte har JS aktiverat och därmed inte kan leka med diagrammet -->
   <noscript id="noCanvas">
		<h2>Hello, user!</h2>
		<p>	We here at Legu Hubu have noticed that you currently do not have JavaScript enabled.
			JavaScript is required to access the majority of the content on this website. </p>
		<p>	If you had JavaScript enabled, you would be able to view and interact with a very interesting lego-related diagram.
			Since you do not have JavaScript enabled, all you get is this message from the developers of this website, which was not generated using JavaScript seeing as such a thing would be impossible
			with you having disabled it. </p>
		<p>	We hope you will have a nice day despite not using JavaScript and thus not experiencing the Internet and all of its JavaScript-dependent content like most of us do. </p>
   </noscript>
   
   <?php
		// Inkludera fil för att visa toplistor över antalet satser med flest bitar och antalet bitar som ingår i flest satser
			include "toplist.php";
	?>
   
</div>

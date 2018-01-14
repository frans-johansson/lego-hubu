/* Globala variablar */
var c; // Canvas-elementet
var ctx; // 2D-context för canvas-elementet
var width, height; // Dimensioner för canvas-elementet
var data = new Array(); // En array med DataEntry för varje stapel i diagrammet
var rects = new Array(); // En array med Rect, innehåller alla staplar i diagrammet
var maxAmount; // Högsta antalet satser i diagrammet
var infoBox; // Informationsruta för diagrammet

/*	Definierar "klasser" för DataEntry (information från databasen)
	och för Rect (en rektangel i canvas som motsvarar en stapel i diagrammet)
*/
function DataEntry (year, amount) {
	this.year = year;
	this.amount = amount;
}

function Rect (x, y, w, h, data) {
	// koordinater
	this.x = x;
	this.y = y;
	//dimensioner
	this.w = w;
	this.h = h;

	this.data = data; 		// Detta ska vara det DataEntry som motsvarar just den stapeln

	var hasHover = false; 	// För att avgöra om en stapel har musen över sig
}

/*	En samlingsfunktion för att initiera canvas-elementet med diagrammet när sidan laddas.
	Bundet till onload för window.
*/
initCanvas = function() {
	c = document.getElementById("canvas");
	infoBox = document.getElementById("diagramInformation");

	setArray();
	getMax();
	stageCanvas();
	drawCanvas();

	c.addEventListener("mousemove", checkHover);
}

/* Beräkna dimensionerna för elementet. Uppdateras även om fönstrets storlek ändras.
*/
stageCanvas = function() {
	var ratio = 16/9; // Utgå från ett 16:9 format för canvas-elementet

	width = c.parentNode.clientWidth;
	height = width/ratio;

	// Sätter den beräknade höjden och bredden i canvas-elementet
	c.width = width;
	c.height = height;

	addRects(); //Lägger till alla staplar efter alla höjd- och breddparametrar har beräknats (då även onresize)
}

/* Beräknar rätt höjd och bredd för alla staplar och lägger till dessa som Rect i den globala arrayen rects.
	Notera att eftersom canvas-elementet har sitt origo i det övre vänstra hörnet, så ser vi en del till synes underliga
	minustecken i beräkningarna samt att y koordinaten för varje stapel blir hela höjden då vi vill att varje stapel ska börja
	i underkanten av elementet.
*/
addRects = function() {
	var unitY = height/maxAmount; // enhetsavståndet i y-led, baseras på höjden och det högsta värdet data
	var unitX = width/data.length; // enhetsavståndet i x-led, baseras på bredden och mängden staplar (datas storlek)

	for (var i = 0; i < data.length; i++) {
		x = unitX + i * unitX
		y = height;
		w = -unitX;
		h = -data[i].amount * unitY;

		rects[i] = new Rect(x, y, w, h, data[i]); // Lägger till stapeln som nu ska ritas i arrayen med alla staplar
	}
}

/*	Ritar ut alla staplar (lagrade i rects) i canavas-elementet. Kollar även om en stapel har muspekaren över sig och ritar den med annan färg.
	samt skriver ut information om den stapeln i en textruta i elementets övre hörn.
*/
drawCanvas = function() {
	ctx = c.getContext("2d");

	for (var i = 0; i < rects.length; i++) {
		ctx.beginPath();

		// Se om "onhover"-information borde visas
		if (rects[i].hasHover) {
			ctx.fillStyle = "#F0C247";

			// Uppdatera informationsrutan
			document.getElementById("diagramYear").innerHTML = rects[i].data.year;
			document.getElementById("diagramAmount").innerHTML = rects[i].data.amount;
		}
		else {
			ctx.fillStyle = "#fff";
		}

		ctx.rect(rects[i].x, rects[i].y, rects[i].w, rects[i].h);
		ctx.fill();
	}
}

/*	Avmarkerar alla staplar och ritar om diagrammet. Bunden till onmouseout för diagrammet.
*/
resetHover = function() {
	for (var i = 0; i < rects.length; i++) {
		rects[i].hasHover = false;
	}
	
	drawCanvas();
}

/*	Flaggar en stapel om muspekaren befinner sig över den. Tar emot "pointer" vilket motsvarar muspekarens
 	och låter oss beräkna dess koordinater på canvas-elementet. Bunden till onmousemove i canvas.
*/
checkHover = function(pointer) {
	// Sätter x och y till muspekarens koordinater inom canvas-elementet (inte på hela sidan)
	var bounds = c.getBoundingClientRect(),
		x = pointer.clientX - bounds.left,
		y = pointer.clientY - bounds.top;


	for (var i = 0; i < rects.length; i++) {
		ctx.beginPath();
		ctx.rect(rects[i].x, rects[i].y, rects[i].w, -height); // Gör en spökrektangel för varje stapel

		if (ctx.isPointInPath(x, y)) { // Se om muspekaren ligger i denna spökrektangel och flagga dess motsvarande stapel
			rects[i].hasHover = true;
		}
		else {
			rects[i].hasHover = false;
		}

		ctx.closePath(); // Slut med spökandet
	}

	// rita om diagrammet (borde kanske sättas inom en if-sats)
	ctx.clearRect(0, 0, width, height);
	drawCanvas();
}

/*	Visar/döljer informationsrutan för diagrammet. Kallas både vid mouseenter och mouseleave.
	Variabeln isHovering reglerar om rutan ska visas eller döljas när funktionen kallas.
*/
var isHovering = false;
toggleInformation = function() {
	isHovering = !isHovering;

	if (isHovering) {
		infoBox.style.display = "block";
	}
	else {
		infoBox.style.display = "none";
	}
}

/*	Hämta information från den osynliga HTML-tabellen skapad med PHP vilken innehåller all nödvändig information från databasen
	och lägg detta i vår data array.
*/
setArray = function() {
	// Arbetar oss igenom tabellhierarkin för att nå våra datainlägg (raderna i tabellen)
	var resultTable = document.getElementById("diagramData").children[0];
	var resultRows = resultTable.children;

	// Utgår från att tabellen redan är sorterad efter år i och med SQL-frågan
	// samt att raderna är organiserade <år> <mängd>.
	var firstYear = resultRows[0].children[0].innerHTML;
	var lastYear = resultRows[resultRows.length - 2].children[0].innerHTML; //pga frågetecken (alla utan år får ? och läggs i sista raden, därav -2)

	// Används för att hålla reda på när tabellen gör "hopp" pga att mängden bitar för ett år var 0
	var currentYear = firstYear;

	for(var i = 0; i < lastYear - firstYear - 1; i++) {
		var year = resultRows[i].children[0].innerHTML;
		var amount = resultRows[i].children[1].innerHTML;

		while (year > currentYear) { // Fyller ut "tomma" år med 0-inlägg
			data.push(new DataEntry(currentYear, 0));
			currentYear++;
		}

		data.push(new DataEntry(year, amount));

		currentYear++;
	}
}

/*	Hittar det största värdet i data-arrayen
*/
getMax = function() {
	maxAmount = 0;

	for (var i = 0; i < data.length; i++) {
		if (parseInt(data[i].amount) > maxAmount) {
			maxAmount = data[i].amount;
		}
	}
}

/* Kopplar funktioner till respektive event */
window.addEventListener("load", initCanvas);
window.addEventListener("resize", stageCanvas);
window.addEventListener("resize", drawCanvas);

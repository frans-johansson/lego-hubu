var c;
var ctx;
var width, height;
var data = new Array(); // En array med DataEntry
var maxAmount;
var rects = new Array(); // En array med Rect

function DataEntry (year, amount) {
	this.year = year;
	this.amount = amount;
}

function Rect (x, y, w, h, data) {
	this.x = x;
	this.y = y;
	this.w = w;
	this.h = h;
	this.data = data; 		// Detta ska vara det DataEntry som motsvarar just den stapeln
	
	var hasHover = false; 	// För att avgöra om en stapel har musen över sig
}

initCanvas = function() {
	c = document.getElementById("canvas");
	
	setArray();
	getMax();
	stageCanvas();
	drawCanvas();
	
	c.addEventListener("mousemove", checkHover);
}

stageCanvas = function() {
	var ratio = 16/9;
	
	width = c.parentNode.clientWidth;
	height = width/ratio;
	
	c.width = width;
	c.height = height;
	
	addRects(); //Lägger till alla staplar efter alla höjd- och breddparametrar har beräknats (då även onresize)
}

addRects = function() {
	var unitY = height/maxAmount;
	var unitX = width/data.length;
	
	for (var i = 0; i < data.length; i++) {
		x = unitX + i * unitX
		y = height;
		w = -unitX;
		h = -data[i].amount * unitY;
		
		rects[i] = new Rect(x, y, w, h, data[i]); // Lägger till stapeln som nu ska ritas i arrayen med alla staplar
	}
}

drawCanvas = function() {
	ctx = c.getContext("2d");
	ctx.font = "30px Arial";
	
	var rectInfo = "";
	
	for (var i = 0; i < rects.length; i++) {
		ctx.beginPath();
		
		// Se om "onhover"-information borde visas
		if (rects[i].hasHover) {
			
			rectInfo = 	"Year: " + rects[i].data.year +
						"\nAmount: " + rects[i].data.amount;
						
			var textWidth = ctx.measureText(rectInfo);
			
			ctx.rect(10, 30, textWidth, 30);
			ctx.fillStyle = "#3872A9";
			ctx.fill();
			
			ctx.fillStyle = "#F0C247";
		}
		else {
			ctx.fillStyle = "#fff";
			rectInfo = "";
		}
		
		ctx.rect(rects[i].x, rects[i].y, rects[i].w, rects[i].h);
		ctx.fill();
		
		ctx.fillText(rectInfo, 10, 30);
	}
}

checkHover = function(pointer) {
	// Sätter x och y till muspekarens koordinater i canvas-elementet (inte på hela sidan)
	var bounds = c.getBoundingClientRect(),
		x = pointer.clientX - bounds.left,
		y = pointer.clientY - bounds.top;
	
	
	for (var i = 0; i < rects.length; i++) {
		ctx.beginPath();
		ctx.rect(rects[i].x, rects[i].y, rects[i].w, -height); //Gör en spökrektangel för varje stapel
		
		if (ctx.isPointInPath(x, y)) { // Se om muspekaren ligger i denna spökrektangel
			rects[i].hasHover = true;
		}
		else {
			rects[i].hasHover = false;
		}
		
		ctx.closePath();
	}
	
	ctx.clearRect(0, 0, width, height);
	drawCanvas();
}

setArray = function() {
	var resultTable = document.getElementById("diagramData").children[0];
	var resultRows = resultTable.children;
	
	var firstYear = resultRows[0].children[0].innerHTML;
	var lastYear = resultRows[resultRows.length - 2].children[0].innerHTML; //pga frågetecken (alla utan år får ? och läggs i sista raden, därav -3)

	
	var currentYear = firstYear;

	for(var i = 0; i < lastYear - firstYear - 1; i++){
		var year = resultRows[i].children[0].innerHTML;
		var amount = resultRows[i].children[1].innerHTML;
		
		while (year > currentYear) {
			data.push(new DataEntry(currentYear, 0));
			currentYear++;
		}

		data.push(new DataEntry(year, amount));
		
		currentYear++;
	}
}

getMax = function() {
	maxAmount = 0;
	
	for (var i = 0; i < data.length; i++) {
		if (parseInt(data[i].amount) > maxAmount) {
			maxAmount = data[i].amount;
		}
	}
}

window.addEventListener("load", initCanvas);
window.addEventListener("resize", stageCanvas);
window.addEventListener("resize", drawCanvas);
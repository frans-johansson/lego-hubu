var c;
var width, height;
var data = new Array();
var maxAmount;

function DataEntry (year, amount) {
	this.year = year;
	this.amount = amount;
}

initCanvas = function() {
	c = document.getElementById("canvas");
	
	setArray();
	getMax();
	stageCanvas();
	drawCanvas();
}

stageCanvas = function() {
	var ratio = 16/9;
	
	width = c.parentNode.clientWidth;
	height = width/ratio;
	
	c.width = width;
	c.height = height;
}

drawCanvas = function() {
	var ctx = c.getContext("2d");
	
	var unitY = height/maxAmount;
	var unitX = width/data.length;
	
	ctx.fillStyle = "#fff";
	
	for (var i = 0; i < data.length; i++) {
		ctx.fillRect(unitX + i * unitX, height, -unitX, -data[i].amount * unitY);
	}
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
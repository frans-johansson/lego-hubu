
window.onload = function(){
	
	var c=document.getElementById("canvas");
	var ctx=c.getContext("2d");
	
	/* Bestämma bredd och höjd */
	
	var parentWidth = c.parentNode.getBoundingClientRect().width;
	
	c.width = parentWidth;
	c.height = 400px;

	ctx.beginPath();
	ctx.moveTo(0,0);
	ctx.lineTo(100,150);
	ctx.lineTo(200,200);
	ctx.lineTo(300,100);
	ctx.lineTo(400,150);
	ctx.lineTo(500,10);
	ctx.lineTo(600,100);
	ctx.lineTo(700,300);
	ctx.lineTo(800,200);
	ctx.stroke();
	
	ctx.beginPath();
    ctx.arc(100,150,10,0,2*Math.PI);
    ctx.stroke();
	
}



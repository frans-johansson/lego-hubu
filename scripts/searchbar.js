function updateTagList() {
	document.getElementById("tagList").style.display = "inline-block";	
	var searchText = document.getElementById("searchText").value;
	var listthing = document.getElementsByClassName("searchContent");
	
	for (var i=0; i < listthing.length; i++) 
		listthing[i].innerHTML = searchText;
}

window.onclick = function(click) {
	if (click.target.className == "tagOption") {
		var tagID = click.target.id;
		var tagContent = document.getElementById(tagID).lastChild.innerHTML;
		
		
		alert(tagContent);
	}
	else if (click.target.className == "searchContent") {
	
		var tagContent = click.target.innerHTML;
		alert(tagContent);
	}
	else if (click.target.className != "tagList") {
		document.getElementById("tagList").style.display = "none";
	}
}


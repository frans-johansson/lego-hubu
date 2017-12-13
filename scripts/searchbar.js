function updateTagList() {
	document.getElementById("tagList").style.display = "inline-block";	
	var searchText = document.getElementById("searchText").value;
	var listthing = document.getElementsByClassName("searchContent");
	
	for (var i=0; i < listthing.length; i++) 
		listthing[i].innerHTML = searchText;
}

window.onclick = function(click) {
	if (click.target.id != "tagList") {
		document.getElementById("tagList").style.display = "none";
	}
}


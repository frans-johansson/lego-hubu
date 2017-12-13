var searchText

function updateTagList() {
	searchText = document.getElementById("searchText").value;
	
	if (searchText)
		document.getElementById("tagList").style.display = "inline-block";
	else 
		document.getElementById("tagList").style.display = "none";
	
	var listthing = document.getElementsByClassName("searchContent");
	
	for (var i=0; i < listthing.length; i++) 
		listthing[i].innerHTML = searchText;
}

window.onclick = function(click) {
	if (click.target.className == "tagOption") {
		var hiddenInputId = click.target.id + "List";
		
		if(document.getElementById(hiddenInputId).value == "")
			document.getElementById(hiddenInputId).value = searchText;
		else {
			document.getElementById(hiddenInputId).value += ('&' + searchText);
		}
		
		alert(document.getElementById(hiddenInputId).value);
	}
	else if (!(click.target.className == "tagList" || click.target.id == "searchText")) {
		document.getElementById("tagList").style.display = "none";
	}
}


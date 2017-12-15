var searchText;

updateTagList = function() {
	searchText = document.getElementById("searchText").value;
	
	if (searchText)
		document.getElementById("tagList").style.display = "inline-block";
	else 
		document.getElementById("tagList").style.display = "none";
	
	var listthing = document.getElementsByClassName("searchContent");
	
	for (var i=0; i < listthing.length; i++) 
		listthing[i].innerHTML = searchText;
}

/* Lägger till ny klass för taggar */

function Tag(type, content, ID) {
	this.type = type;
	this.content = content;
	this.ID = ID;
}



window.onclick = function(click) {
	if (click.target.className == "tagOption") {
		var inputID = click.target.id + "List";
		
		/*
		if(document.getElementById(hiddenInputId).value == "")
			document.getElementById(hiddenInputId).value = searchText;
		else {
			document.getElementById(hiddenInputId).value += ('&' + searchText);
		}
		*/
		
		tag = new Tag(click.target.id, searchText, inputID);
	
		makeTag(tag);
	}
	else if (!(click.target.className == "tagList" || click.target.id == "searchText")) {
		document.getElementById("tagList").style.display = "none";
	}
}

function makeTag(tag) {
	/* Skapar ny tag och lägger till den i dokumentet*/
	
	var newTag = document.createElement("div");
	newTag.className = tag.type;
	
	var tagContent = document.createTextNode(tag.content);
	newTag.appendChild(tagContent);
	
	var removeButton = document.createElement("div");
	removeButton.className = "removeButton";
	removeButton.onclick = function() { newTag.parentNode.removeChild(newTag); };
	newTag.appendChild(removeButton);
	
	document.getElementById("tagContainer").appendChild(newTag);
}

function setParams() {
	var tagContainer = document.getElementById("tagContainer");
	var tags = tagContainer.childNodes;
	
	for (var i = 0; i < tags.length; i++) {
		tags[i] /*Hantera GET-parametrar till hidden här*/
	}
}
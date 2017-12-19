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
		
		tag = new Tag(click.target.id, searchText, inputID);
	
		makeTag(tag);
	}
	else if (!(click.target.className == "tagList" || click.target.id == "searchText")) {
		document.getElementById("tagList").style.display = "none";
	}
}

function DecodeURLParameter(Parameter)
{
	var FullURL = window.location.search.substring(1);
	var ParametersArray = FullURL.split('&');
	for (var i = 0; i < ParametersArray.length; i++)
	{
		var CurrentParameter = ParametersArray[i].split('=');
		if(CurrentParameter[0] == Parameter && CurrentParameter[1] != "")
		{
			var fullGet = CurrentParameter[1];
			fullGet = decodeURIComponent(fullGet);
			var getArray = fullGet.split('&');
			return getArray;
		}
	}
}

function recreateTags(tagArray, type) {
	for(var i = 0; i < tagArray.length; i++) {
		var ID = type + "List";
		tag = new Tag(type, tagArray[i], ID);
		
		makeTag(tag);
	}
}

window.onload = function() {
	var colors = DecodeURLParameter("col");
	var sets = DecodeURLParameter("set");
	var parts = DecodeURLParameter("par");
	var years = DecodeURLParameter("yea");
	
	/*lägg till taggar*/
	if (colors)
		recreateTags(colors, "colorTag");
	
	if (sets)
		recreateTags(sets, "setTag");
	
	if (parts)
		recreateTags(parts, "partTag");
	
	if (years)
		recreateTags(years, "yearTag");
}

function makeTag(tag) {
	/* Skapar ny tag (div) och lägger till den i dokumentet*/
	
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
		
		if(document.getElementById(tags[i].className + "List").value == "") {
			document.getElementById(tags[i].className + "List").value = tags[i].textContent;
		}
		else {
			document.getElementById(tags[i].className + "List").value += "&" + tags[i].textContent;
		}
	}
}








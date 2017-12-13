var searchText;

/* Arrays för arr hålla de olika tag-objekten
var colTags[];
var setTags[];
var parTags[];
var yeaTags[];
var catTags[];
*/

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

/* Lägger till ny klass för taggar
function Tag(type, content) {
	this.type = type;
	this.content = content;
	var ID;
}
*/

window.onclick = function(click) {
	if (click.target.className == "tagOption") {
		
		/*
		var hiddenInputId = click.target.id + "List";
		
		
		if(document.getElementById(hiddenInputId).value == "")
			document.getElementById(hiddenInputId).value = searchText;
		else {
			document.getElementById(hiddenInputId).value += ('&' + searchText);
		}
		*/
		
		/* Skapar tag beroende på vad som klickades på */
		
		tag = new Tag(click.target.id, document.getElementById(hiddenInputId).value);
		
		/* Bestäm vilken array tag-objektet ska ligga i */
		if (tag.type == "colorTag") {
			colTags.push(tag);
			tag.ID = colTags.length; /* Bestäm ID beroende på array length */
			alert(tag.ID);
		}
	}
	else if (!(click.target.className == "tagList" || click.target.id == "searchText")) {
		document.getElementById("tagList").style.display = "none";
	}
}

/*
function makeTag(tag) {
	var newTag = document.createElement("DIV");
	
	newTag.appendChild(document.createTextNode(content));
	
	document.getElementById("tagContainer").appendChild(newTag);
}
*/

/* LÄGG TILL: 	funktion som tar bort tagga
				funktion som lägger ihop taggar till en sökning(?)
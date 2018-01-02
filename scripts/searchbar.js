var searchText; // Global variabel för söktexten i sökfältet
var dropDownFields;
var currentSelected;

updateTagList = function() {
	searchText = document.getElementById("searchText").value;

	// Gör drop-down-menyn synlig om användaren skriver i sökfältet
	if (searchText)
		document.getElementById("tagList").style.display = "inline-block"; // Gör den synlig
	else
		document.getElementById("tagList").style.display = "none"; // Gör den osynlig om sökfältet är tomt

	var tagSearchContent = document.getElementsByClassName("searchContent"); // Hämtar delen av tag-fälten som ska innehålla söktexten

	for (var i=0; i < tagSearchContent.length; i++)
		tagSearchContent[i].innerHTML = searchText; // Lägger in söktexten i varje tag-fält

	dropDownFields = document.getElementsByClassName("tagOption");

	// Gör så att första taggen markeras automatiskt när användaren börjar skriva för första gången
	if (typeof(currentSelected) == "undefined") {
		currentSelected = 0;
		dropDownFields[currentSelected].classList.toggle("selectedTag");
	}
}

// Keycodes
// 40 = pil ner
// 38 = pil upp
// 13 = enterknappen

navigateTagList = function(pressed) {
	var key = pressed.keyCode || pressed.which; // För att också fungera på webbläsare som inte stödjer keycode

	if (key == 40 && currentSelected < 0) {
		currentSelected = 0;
		dropDownFields[currentSelected].classList.toggle("selectedTag");
	}
	else if (key == 40 && currentSelected < dropDownFields.length - 1) {
		document.getElementById("searchText").blur(); // Avmarkera sökfältet
		dropDownFields[currentSelected].classList.toggle("selectedTag");
		dropDownFields[++currentSelected].classList.toggle("selectedTag");
	}
	else if (key == 38 && currentSelected > 0) {
		dropDownFields[currentSelected].classList.toggle("selectedTag");
		dropDownFields[--currentSelected].classList.toggle("selectedTag");
	}
	else if (key == 38 && currentSelected == 0) {
		document.getElementById("searchText").focus(); // Markera sökfältet igen
		dropDownFields[currentSelected--].classList.toggle("selectedTag");
	}
}

/*
	För addTag lägg till funktion för när enterknappen trycks ner
	och leta efter elementet med selectedTag. Gör även så att man kan söka genom att trycka enter igen.
*/
activateTagOnPress = function(pressed) {
	var key = pressed.keyCode || pressed.which; // För att också fungera på webbläsare som inte stödjer keycode

	// Avgör om en tag är markerad och om enterknappen har tryckts
	if (key == 13 && currentSelected >= 0) {

		// Sök efter den markerade taggen (dvs. den som har klasssen selectedTag) och skapa en ny tag
		for (var i = 0; i < dropDownFields.length; i++) {
			if (dropDownFields[i].classList.contains("selectedTag")) {
				var inputID = dropDownFields[i].id + "List";

				tag = new Tag(dropDownFields[i].id, searchText, inputID);

				makeTag(tag);
			}
		}
	}
}

/* Lägger till ny "klass" för taggar */

function Tag(type, content, ID) {
	this.type = type;
	this.content = content;
	this.ID = ID;
}

/* Funktion som tar emot ett click-event, lägger till motsvarande tag som användaren klickar på
   Gömmer drop-downen om användaren klickar utanför */
activateTagOnClick = function(click) {
	if (click.target.className == "tagOption") {
		var inputID = click.target.id + "List";

		tag = new Tag(click.target.id, searchText, inputID);

		makeTag(tag);
	}
	else if (!(click.target.className == "tagList" || click.target.id == "searchText")) {
		document.getElementById("tagList").style.display = "none";
	}
}

/* Ser till att alla taggar är kvar även efter sökningen är gjord */
DecodeURLParameter = function(Parameter)
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

recreateTags = function(tagArray, type) {
	for(var i = 0; i < tagArray.length; i++) {
		var ID = type + "List";
		tag = new Tag(type, tagArray[i], ID);

		makeTag(tag);
	}
}

restoreTags = function() {
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

/* Placerar all information från taggarna i korrekt hidden-input-element i formuläret för att sökningen ska funka
   aktiveras on-submit */
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

/* Kopplar funktioner till respektive event */
window.addEventListener("click", activateTagOnClick);
window.addEventListener("keydown", activateTagOnPress);
window.addEventListener("load", restoreTags);
window.addEventListener("keydown", navigateTagList);

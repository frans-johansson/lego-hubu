/* Globala variablar */
var searchText; // Texten användaren skrivit in i sökfältet
var dropDownFields; // Array med alla alternativen i dropdown-menyn
var currentSelected; // Det markerade elementet i dropdown-menyn. Sätts till -1 om inget element är markerat.

/*  Funktion för att dels initiera dropdown-menyn, samt uppdatera denna när användaren skriver i sökfältet
 	Kallas onkeyup i sökfältet
*/
updateTagList = function() {
	searchText = document.getElementById("searchText").value;

	// Gör drop-down-menyn synlig om användaren skriver i sökfältet
	if (searchText)
		document.getElementById("tagList").style.display = "inline-block";
	else
		document.getElementById("tagList").style.display = "none"; // Gör den osynlig om sökfältet är tomt

	var tagSearchContent = document.getElementsByClassName("searchContent"); // Hämtar delen av tag-fälten som ska innehålla söktexten

	// Lägger in söktexten i varje tag-fält i dropdownen
	for (var i=0; i < tagSearchContent.length; i++)
		tagSearchContent[i].innerHTML = searchText;


	dropDownFields = document.getElementsByClassName("tagOption"); // Definierar global variabel för lista med taggar i dropdownen

	// Gör så att första taggen markeras automatiskt när användaren börjar skriva för första gången
	if (typeof(currentSelected) == "undefined") {
		currentSelected = 0; // Definierar global variabel för den nuvarande markerade taggen
		dropDownFields[currentSelected].classList.toggle("selectedTag");
	}
}

/*	Numeriska koder för tangentbordsknappar
 	40 = pil ner
 	38 = pil upp
 	13 = enterknappen (används i activateTagOnPress)

	Funktion för att navigera genom dropdown-menyn genom att använda pil upp och pil ner på tangentbordet.
	Tar emot ett event "pressed" vilken används för att avgöra vilken knapp användaren tryckt. Se tabell ovan.
	Är bunden till onkeydown i window.
*/
navigateTagList = function(pressed) {
	var key = pressed.keyCode || pressed.which; // För att också fungera på webbläsare som inte stödjer keycode

	if (key == 40 && currentSelected < 0) {
		for (var i = 0; i < dropDownFields.length; i++) {
			dropDownFields[i].classList.remove("hashover"); // tar bort koppling till pseudoklassen hover i CSS:en från alla taggar
		}

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

/*	Lägger till den markerade taggen i sökfältet till sökningen vid nertryckning av enterknappen.
	Tar liksom funktionen ovan emot ett event vilket används för att avgöra den nedtryckta knappen.
	Är bunden till onkeydown i window.
*/
activateTagOnPress = function(pressed) {
	var key = pressed.keyCode || pressed.which; // För att också fungera på webbläsare som inte stödjer keycode

	// Avgör om ett tagalternativ är markerat (currentSelected är ej negativ) och om enterknappen har tryckts
	if (key == 13 && currentSelected >= 0) {

		// Sök efter det markerade alternativet (dvs. den som har klasssen selectedTag) och skapa en ny tag
		for (var i = 0; i < dropDownFields.length; i++) {
			if (dropDownFields[i].classList.contains("selectedTag")) {
				var inputID = dropDownFields[i].id + "List";

				tag = new Tag(dropDownFields[i].id, searchText, inputID);

				makeTag(tag);
			}
		}
	}
}

/*	För att relativt smidigt byta till att navigera dropdownen med musen.
	Avmarkerar alla alternativ och lägger till CSS-klass med onhover-effekt.
	Bunden till onmousemove för dropdown-menyn.
*/
clearSelected = function() {
	if (currentSelected >= 0) { 	// kolla först om det finns ett valt alternativ genom tangentbordsnavigation
		currentSelected = -1; 		// ingen tag är nu vald genom tangentbordet

		for (var i = 0; i < dropDownFields.length; i++) {
			dropDownFields[i].classList.remove("selectedTag"); 	// tar bort selectedTag från alla alternativ
			dropDownFields[i].classList.add("hashover"); 		// ger alla elementen i listan koppling till pseudoklassen med hover i CSS:en
		}
	}
}

/* 	Lägger till ny "klass" för taggarna som läggs till bredvid sökfältet.
	Används främst för att organisera koden.
*/
function Tag(type, content, ID) {
	this.type = type;
	this.content = content;
	this.ID = ID;
}

/* 	Funktion som tar emot ett click-event, lägger till motsvarande tag som användaren klickar på.
   	Gömmer drop-downen om användaren klickar utanför listan.
*/
activateTagOnClick = function(click) {
	if (click.target.classList.contains("tagOption")) {
		var inputID = click.target.id + "List";

		tag = new Tag(click.target.id, searchText, inputID);

		makeTag(tag);
	}
	else if (!(click.target.className == "tagList" || click.target.id == "searchText")) {
		document.getElementById("tagList").style.display = "none";
	}
}

/* 	Funktionerna nedan ser till att alla taggar är kvar bredvid sökfältet även efter sökningen är gjord
	och om sidan av någon annan anledning skulle laddas om.

	DecodeURLParameter: Utifrån de GET-parametrar som är definierade gällande taggarna i URL:en returneras en Array
						med taggarna för en given parameter (färg, sats, bit, år)

	recreateTags: Lägger till taggarna i HTML:en ingen för en given typ och array med taggar given från funktionen ovan

	restoreTags: Funktion bunden till window.onload. Kopplar samman funktionerna ovan.
*/
DecodeURLParameter = function(parameter)
{
	var fullURL = window.location.search.substring(1); // Hämtar själva URL:en
	var parametersArray = fullURL.split('&'); // Delar upp URL:en i stycken för varje parameter (år, färg, bit, sats)
	for (var i = 0; i < parametersArray.length; i++)
	{
		var currentParameter = parametersArray[i].split('='); // Tar fram de själva enskilda parametrarna
		if(currentParameter[0] == parameter && currentParameter[1] != "") // Kollar om parametertypen är den som söks efter, samt om den är tom
		{
			var fullGet = currentParameter[1]; // Hämtar listan med alla parametrarna på URL-form (ex. röd, grön, blå)
			fullGet = decodeURIComponent(fullGet); // Avkodar URL-form till vanliga karaktärer
			var getArray = fullGet.split('&'); // Delar upp i individuella parametrar vilka sedan returneras
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
	// hämta alla arrayer för de olika GET-parametrarna
	var colors = DecodeURLParameter("col");
	var sets = DecodeURLParameter("set");
	var parts = DecodeURLParameter("par");
	var years = DecodeURLParameter("yea");

	// lägg till taggar
	if (colors)
		recreateTags(colors, "colorTag");

	if (sets)
		recreateTags(sets, "setTag");

	if (parts)
		recreateTags(parts, "partTag");

	if (years)
		recreateTags(years, "yearTag");
}

/*	Skapar ny tag (div) utifrån en given Tag (definierad "klass") och lägger till den i dokumentet bredvid sökfältet
*/
function makeTag(tag) {
	var newTag = document.createElement("div"); // Skapar själva div:en för taggen och ger den rätt klass (beroende på typ)
	newTag.className = tag.type;

	var tagContent = document.createTextNode(tag.content); // Lägger till textinnehåll
	newTag.appendChild(tagContent);

	var removeButton = document.createElement("div"); // Lägger till knapp för att ta bort taggen
	removeButton.className = "removeButton";
	removeButton.onclick = function() { newTag.parentNode.removeChild(newTag); };
	newTag.appendChild(removeButton);

	document.getElementById("tagContainer").appendChild(newTag); // Lägger in hela taggen i dokumentet på rätt plats
}

/* 	Placerar all information från taggarna i korrekt hidden-input-element i formuläret för att sökningen ska funka.
   	Bunden till onsubmit för sökformuläret.
*/
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

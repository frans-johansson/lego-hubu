var hidden = true; // Global variabel för att avgöra om profil-popupen ska vara dold eller inte

/* 	Funktion för att byta mellan att visa och dölja popup-fönstret.
	Ändrar endast display-typ för de relevanta HTML-elementen.
*/
togglePopup = function(){
	if(hidden) {
		document.getElementById("profile").style.display = "none";
		document.getElementById("profilePopup").style.display = "block";
		document.getElementById("pageBackground").style.display = "flex";
		hidden = !hidden;
	}
	else {
		document.getElementById("profilePopup").style.display = "none";
		document.getElementById("pageBackground").style.display = "none";
		document.getElementById("profile").style.display = "block";
		hidden = !hidden;
	}
}

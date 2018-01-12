var hidden = true; // Global variabel för att avgöra om profil-popupen ska vara dold eller inte

/* 	Funktion för att byta mellan att visa och dölja popup-fönstret.
	Ändrar endast display-typ för de relevanta HTML-elementen.
*/
togglePopup = function(){
	if(hidden) {
		document.getElementById("profile").style.display = "none";
		document.getElementById("profilePopup").style.display = "block";
		document.getElementById("pageBackground").style.display = "flex";
		
		// Så att användaren kan klicka var som helst på sidan för att stänga popup-fönstret
		document.getElementById("pageBackground").addEventListener("click", togglePopup);
		
		hidden = !hidden;
	}
	else {
		document.getElementById("profilePopup").style.display = "none";
		document.getElementById("pageBackground").style.display = "none";
		document.getElementById("profile").style.display = "block";
		
		// Så att inte popup-fönstret öppnas av att användaren klickar var som helst på sidan när inte popup-fönstret visas
		document.getElementById("pageBackground").removeEventListener("click", togglePopup);
		
		hidden = !hidden;
	}
}

var hidden = true;

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
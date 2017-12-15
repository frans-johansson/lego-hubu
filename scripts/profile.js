var hidden = true;

function togglePopup(){
	
	if(hidden) {
		document.getElementById("profile").style.display = "none";
		document.getElementById("profilePopup").style.display = "inline-block";
		hidden = !hidden;
	}
	else {
		document.getElementById("profilePopup").style.display = "none";
		document.getElementById("profile").style.display = "inline-block";
		hidden = !hidden;
	}
}
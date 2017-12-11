
// Källa för DecodeURLParameter(): https://www.youtube.com/watch?v=LYfQY836E2w

// Kör koden endast när hela sidan laddat klart för att förhindra fel
window.onload = function(){
	
	// Hämtar URL:en, delar upp den och hämtar värdet för GET-parametern med samma namn som funktionsparametern
	function DecodeURLParameter(Parameter)
	{
		var FullURL = window.location.search.substring(1);
		var ParametersArray = FullURL.split('&');
		for (var i = 0; i < ParametersArray.length; i++)
		{
			var CurrentParameter = ParametersArray[i].split('=');
			if(CurrentParameter[0] == Parameter)
			{
				return CurrentParameter[1];
			}
		}
	}
	
	//Hämta vad man sökt på från URL
	var Search = DecodeURLParameter('search');
	
	//Hämta sidonummer från URL
	var PageName = parseInt(DecodeURLParameter('page'));

	
	//Förhindrar sidonummer från att vara negativt
	if(!PageName || PageName < 0){
		PageName = 0;
		var page_value_prev = 0;
	}else{
		var page_value_prev = PageName - 1;
	}
	
	//Om ingen sök-parameter hittats så nollställ sök-parametern
	if(!Search){
		Search = 0;	
	}
	
	
	//Bygg upp URL för knapparna
	var search_para = "&search=";
	var page_str = "?page=";
	var page_value_next = PageName + 1;
	//page_value_prev deklareras tidigare
	var baselink = "http://www.student.itn.liu.se/~oskan037/";
	var whole_url_next = baselink + page_str + page_value_next + search_para + Search;
	var whole_url_prev = baselink + page_str + page_value_prev + search_para + Search;
	
	//Slutmål
	document.getElementById("nextlink").href = whole_url_next;
	document.getElementById("prevlink").href = whole_url_prev;
	
	
}
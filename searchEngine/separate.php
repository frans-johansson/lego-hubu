<!-- Vi kan ha flera färgtaggar men allt sparas i en GET-parameter, denna funktion delar upp alla taggarna till enskilda värden i en array. -->
<!-- Vi gör detta genom att avkoda URL och gör t.ex '%26' till '&' och delar upp med hjälp av '&'. Så 'green%26red' blir 'green&red' som blir green och red i en array  -->
<?php
function splitGet($parameter) {
	$fullGet = $_GET[$parameter];
	$fullGet = urldecode($fullGet);
	$getArray = explode("&", $fullGet);
	return $getArray;
}
?>
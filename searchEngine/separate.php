<?php
function splitGet($parameter) {
	$fullGet = $_GET[$parameter];
	$fullGet = urldecode($fullGet);
	$getArray = explode("&", $fullGet);
	return $getArray;
}
?>
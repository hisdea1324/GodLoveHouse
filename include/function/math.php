<?php 
function Ceil($a,$b) {
	if (($a%$b)>0) {
		$c=intval($a/$b)+1;
	} else {
		$c=intval($a/$b);
	} 

	return $c;
} 

?>

<?php
if( is_string($messages) ) {
	echo notice('error',$messages);
}
else {
	foreach ($messages as $message) {
		echo notice('error',$message);
	}
}
?>

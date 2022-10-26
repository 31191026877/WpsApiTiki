<?php
	$url 	= Url::permalink($val->slug);
	$title 	= $val->title;
	printf('<p class="heading"><a href="%s" title="%s">%s</a></p>', $url, $title, $title );
?>
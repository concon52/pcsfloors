<?php

	ini_set("user_agent", 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36');

	$img = imagecreatefromjpeg("http://www.vangelder-inc.com/wp-content/gallery/champion-super-nop-tile/thumbs/thumbs_crimson-red-low_0.jpg");

	header('Content-Type: image/jpeg');

	imagejpeg($img, "pictures/test.png", 0);

	imagedestroy($img);

?>
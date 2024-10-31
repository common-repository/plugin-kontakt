<?php
	session_start();
	header("Content-Type: image/jpeg");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	$md5_hash = md5(rand(0, 999));
	$security_code = substr($md5_hash, 15, 5);
	$_SESSION['kontakt_captcha'] = $security_code;
	$width = 100;
	$height = 20;
	$image = ImageCreate($width, $height);
	$white = ImageColorAllocate($image, 255, 255, 255);
	$black = ImageColorAllocate($image, 0, 0, 0);
	$grey = ImageColorAllocate($image, 204, 204, 204);
	
	ImageFill($image, 0, 0, $black);
	ImageString($image, 3, 30, 3, $security_code, $white);
	ImageRectangle($image, 0, 0, $width - 1, $height - 1, $grey);
	imageline($image, 0, $height/2, $width, $height / 2, $grey);
	imageline($image, $width / 2, 0, $width / 2, $height, $grey);
	ImageJpeg($image);
	ImageDestroy($image);
?>
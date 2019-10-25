<?php
	session_start();
	if (isset($_GET['filtre']) && isset($_GET['img']) && $_GET['filtre'] != "none"){
		$png = imagecreatefrompng($_GET['filtre']);
		$jpeg = imagecreatefrompng($_GET['img']);
		$width = 480;
		$height = 480;
		list($newwidth, $newheight) = getimagesize($_GET['filtre']);
		$out = imagecreatetruecolor($width, $height);
		imagecopyresampled($out, $jpeg, 0, 0, 80, 0, $width, $height, $width, $height);
		imageflip($out, IMG_FLIP_HORIZONTAL);
		imagecopyresampled($out, $png, 200, 190, 0, 0, $newwidth, $newheight, $newwidth, $newheight);
	}
	else {
		$jpeg = imagecreatefrompng($_GET['img']);
		$width = 480;
		$height = 480;
		$out = imagecreatetruecolor($width, $height);
		imagecopyresampled($out, $jpeg, 0, 0, 80, 0, $width, $height, $width, $height);
		imageflip($out, IMG_FLIP_HORIZONTAL);
	}
	imagejpeg($out, $_GET['img'], 100);
	$_SESSION['img'] = $_GET['img'];
?>
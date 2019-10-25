<?php
	$source = $_FILES["upimage"]["tmp_name"];
	$destination = uniqid() . '.png';
	$file = "./ressources/screenshots/" . $destination;
	echo move_uploaded_file($source, $file) ? $file : "ERROR UPLOADING";
?>
<?php
	session_start();
	require '../config/database.php';
	require '../config/connexiondb.php';
	if (!isset($total_image))
	{
		$sth = $db->prepare('SELECT count(*) as total from image');
		$sth->execute();
		$total_image = $sth->fetch()[0];
		echo $total_image;
	}
?>
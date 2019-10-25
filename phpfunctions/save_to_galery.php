<?php
session_start();
function save_to_galery()
{
	require '../config/database.php';
	require '../config/connexiondb.php';
	if (!isset($_SESSION['logged_on'])) {
		echo "Erreur, vous n'etes pas connecte";
	}
	else if (!isset($_SESSION['img']))
	{
		echo "Pas de screenshot a upload";
	}
	else
	{
		echo "Upload du screenshot...";
		$stmt = $db->prepare('INSERT INTO `Image` (`user_id`, `image_name`, `image_path`, `like`) VALUES (?, ?, ?, 0)');
		//Bind value.
		$stmt->execute(array($_SESSION['id'], strrchr($_SESSION['img'], "/"), $_SESSION['img']));
	}
}

function upload_to_galery()
{
	require '../config/database.php';
	require '../config/connexiondb.php';
	if (!isset($_SESSION['logged_on'])) {
		echo "Erreur, vous n'etes pas connecte";
	}
	else if (!isset($_POST['src']))
	{
		echo "Pas de screenshot a upload";
	}
	else
	{
		$_POST['src'] =  "../" . substr($_POST['src'], 22);
		$name = substr(strrchr($_POST['src'], "/"), 1);
		$_POST['filtre'] = "../" . substr($_POST['filtre'], 22);
		$jpeg = @imagecreatefrompng($_POST['src']);
		if (!$jpeg)
		{
			$jpeg = @imagecreatefromjpeg($_POST['src']);
			if (!$jpeg)
			{
				echo "Fichier n'est pas un bon jpeg/jpg/png";
				exit;
			}
		}
		echo "Upload du screenshot...";
		$width = 480;
		$height = 480;
		list($newwidth, $newheight) = getimagesize($_POST['filtre']);
		$out = imagecreatetruecolor($width, $height);
		$white = imagecolorallocate($out, 255, 255, 255);
		imagefill($out, 0, 0, $white);
		imagecopyresampled($out, $jpeg, 0, 0, 0, 0, $width, $height, $width, $height);
		if (file_exists($_POST['filtre']) && substr($_POST['filtre'], - 4) === ".png") {
			$png = imagecreatefrompng($_POST['filtre']);
			imagecopyresampled($out, $png, 200, 190, 0, 0, $newwidth, $newheight, $newwidth, $newheight);
		}
		$destination = "../ressources/screenshots/" . uniqid() . '.png';
		imagejpeg($out, $destination, 100);
		$stmt = $db->prepare('INSERT INTO `Image` (`user_id`, `image_name`, `image_path`, `like`) VALUES (?, ?, ?, 0)');
		$stmt->execute(array($_SESSION['id'], strrchr($_SESSION['img'], "/"), $destination));
	}
}
if (isset($_POST['call']) && $_POST['call'] === "save_to_galery")
{
	save_to_galery();
}

if (isset($_POST['call']) && $_POST['call'] === "upload_to_galery")
{
	upload_to_galery();
}

?>
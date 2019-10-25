<?php
	session_start();
	if (!isset($_SESSION['logged_on']) || !isset($_POST['submit'])
		|| $_POST['submit'] !== "Upload Image"){
		header('Location: ../'); 
		exit; 
	}
	unset($_SESSION['error']);
	unset($_SESSION['img_uploaded']);
	$target_dir = "../ressources/uploads/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	// Check if image file is a actual image or fake image
	if (isset($_POST["submit"]) && isset($_FILES['fileToUpload']) && $_FILES["fileToUpload"]["tmp_name"]) {
		if (($check = getimagesize($_FILES["fileToUpload"]["tmp_name"])) == NULL)
		{
			$_SESSION['error'] = "There is a problem with this file!";
			header('Location: ../upload_an_image.php');
			exit;
		}
		if ($check) {
			$_SESSION['error'] = "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		}
	}
	else {
		$_SESSION['error'] = "There is a problem with this file!";
		header('Location: ../upload_an_image.php');
		exit;
	}

	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 500000) {
		$_SESSION['error'] = "Sorry, your file is too large.";
		$uploadOk = 0;
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
		$_SESSION['error'] = "Sorry, only JPG, JPEG, PNG files are allowed.";
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 1) {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			$_SESSION['error'] = "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
			$_SESSION['img_uploaded'] = basename( $_FILES["fileToUpload"]["name"]);
		} else {
			$_SESSION['error'] = "Sorry, there was an error uploading your file.";
		}
	}
	header('Location: ../upload_an_image.php');
	exit ;
?>
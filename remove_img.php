<?php
	session_start();
	require './config/database.php';
	require './config/connexiondb.php'; 
	extract($_GET);
	//verifier si dans le acs pas super admin, limage appartient bien au gars
	if (isset($_SESSION['id']) && isset($path) && $path === "1")
		;
	else if (!isset($_SESSION['id']) || !isset($_SESSION['sa']) || (isset($_SESSION['sa']) && $_SESSION['sa'] != "1")) {
		header('Location: ./'); 
		exit;
	}
	// On récupère les informations de l'utilisateur grâce à son ID
	$afficher_profil = $db->query('SELECT * FROM `Image` WHERE id = "'.$id.'"');
	$afficher_profil = $afficher_profil->fetch();
	if(!isset($afficher_profil['id'])){
		header('Location: ./list_img.php');
		;exit;
	}
	$req = $db->query('DELETE FROM `Image` WHERE `Image`.`id` = "'.$id.'"');
	if ($path == "1")
	header("Location: ./profil.php");
	else
		header("Location: ./list_img.php");
	exit;
?>
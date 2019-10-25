<?php
	session_start();
	require './config/database.php';
	require './config/connexiondb.php'; 
	// S'il n'y a pas de session alors on ne va pas sur cette page
	if (!isset($_SESSION['logged_on']) || $_SESSION['logged_on'] == '0'
		|| !isset($_SESSION['id']) || $_SESSION['id'] == "0" || !isset($_GET)) {
		header('Location: ./'); 
		exit; 
	}
	extract($_GET);
	extract($_POST);
	$com = htmlentities(trim($com));
	if (isset($_GET) && isset($_POST['com']))
	{
		$req = $db->exec('UPDATE `comment` SET `description` = "'.$com.'" WHERE `id` = "'.$id.'"');
		header('Location: ./modif_com.php?id=' . $img);
		exit;
	}
?>

<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Mon profil</title>
		<link rel="stylesheet" href="./css/voir_profil.css">
		<link rel="stylesheet" type="text/css" href="./css/table2.css">
		<style>
		ul {
			list-style: none;
			text-align: left;
			margin-left: 200px;;
		}
		img {
			margin-top:10px;
			margin-left:10px;
			float:left;
			height: 200px;
			width: 200px;
		}
		</style>
	<head>
	<body>
		<?php include("menu.php") ?>
		<table class="users">
			<tr>
				<th><h2 style="text-align : center">Modification de commentaire</h2></th>
			<tr>
			<tr scope="row">
			</tr>
			<td>
			<center>
			<form method="post" >
				<?php
				if (isset($er_auteur)){
					?>
					<div><?= $er_auteur ?></div>
					<?php
				}
				?>
				<input size=50 type="text" placeholder="Commentaire" name="com" value="" maxlength="250">
				<br>
				<br>
				<button type="submit" name="modification_com">Envoyer</button>
			</form>
			</td>
		</table>
	<br>
	</body>
	</center>
</html>
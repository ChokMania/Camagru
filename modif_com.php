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
	$profil_image = $db->query('SELECT User.id, User.login,
									Image.id as id_image, Image.`user_id`,
									Image.image_path, Image.image_name, Image.like,
									Image.creation_date FROM `User`
									INNER JOIN `Image` ON User.id = Image.`user_id`
									WHERE Image.id = "'.$id.'"');
	$profil_image = $profil_image->fetch();
	if(!isset($profil_image['id_image'])){
		header('Location: ./list_img.php');
		exit;
	}
?>

<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Mon profil</title>
		<style>
		ul {
			list-style: none;
			text-align: left;
			margin-left: 200px;;
		}
		img {
			float:left;
			margin-left:100px;
			height: 300px;
			width: 300px;
		}
		#mega_box {
			border: black solid 1px;
			border-radius: 10px;
			height : auto;
			padding: 10px;
			max-width: 1000px;
			max-height: 300px;
			overflow: auto;
			margin-left: 0px;
		}
		#box {
			text-align:left;
			border: black solid 1px;
			max-width: 950px;
			margin: auto;
			padding: 1px;
			margin-left: 10px;
			word-wrap: break-word;
		}
		#info {
			margin-top: -20px;
		}
		</style>
	<head>
	<body>
		<?php include("menu.php") ?>
		<center>
		<h2>Image : "<?php echo $profil_image['image_name'] . '" => id : ' . $profil_image['id_image']?></h2>
		<br>
		<h3 style="float:left; text-align:left; margin-left:100px"><?= $profil_image['image_name']?></h3>
		<h3 style="text-align:center; margin-left:400px">Commentaires</h3>
		<img src="<?= $profil_image['image_path']?>">
		<div id="mega_box">
		<?php
		$reponse = $db->query('SELECT Comment.id, Comment.user_id, Comment.id_image,
							Comment.description, Comment.creation_date,
							Image.id as img_id FROM `Comment`
							INNER JOIN `Image` ON Comment.id_image = Image.id
							WHERE Image.id = "'.$profil_image['id_image'].'"');
		$reponse = $reponse->fetchAll();
		foreach ($reponse as $donnees) {
		?>
			<div id="box"><p style="margin-left: 5px;"><?php echo $donnees['description']?></p>
			<a href="./modif_commentaire.php?id=<?= $donnees['id']?>&img=<?=$profil_image['id_image']?>"><img style ="margin-left:1px;width:10px; height:10px; margin-top:5px;" id="remove" src="./ressources/img/modifier.png" alt="Supprimer"></a></td>
			<a href="./remove_com.php?id=<?= $donnees['id']?>"><img style ="margin-left:1px;width:10px; height:10px; margin-top:5px;" id="remove" src="./ressources/img/remove.png" alt="Supprimer"></a></td>
			</div>
			<br>
		<?php } ?>
		</div>
		</div>
		</center>
		<a style="margin-left:100px" href="./list_img.php">Revenir a la liste</a>
		<a style="margin-left:90px" href="./voir_image.php?id=<?= $profil_image['id_image'] ?>">Revenir au profil</a>
	</body>
</html>
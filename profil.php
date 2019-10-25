<?php
	session_start();
	require './config/database.php';
	require './config/connexiondb.php'; 
	require './phpfunctions/number_format.php';
	// S'il n'y a pas de session alors on ne va pas sur cette page
	if (!isset($_SESSION['logged_on']) || $_SESSION['logged_on'] == '0'
		|| !isset($_SESSION['id']) || $_SESSION['id'] == "0") {
		header('Location: ./'); 
		exit; 
	}
	$res = $_SESSION['id'];
	// On récupère les informations de l'utilisateur connecté
	$req = $db->query("SELECT * FROM User WHERE id = $res");
	$afficher_profil = $req->fetch();
	$reponse = $db->query('SELECT User.id, User.login, User.`super-root`,
							Image.id as id_image,
							Image.image_path, Image.image_name, Image.like,
							Image.creation_date FROM `User`
							INNER JOIN `Image` ON User.id = Image.user_id
							WHERE User.id= "'.$res.'"');
	$reponse = $reponse->fetchAll();
	$reponse = array_reverse($reponse, TRUE);
?>

<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Mon profil</title>
	<link rel="stylesheet" type="text/css" href="./css/profil.css">
	<link rel="stylesheet" type="text/css" href="./css/galerie.css">
	<link rel="stylesheet" type="text/css" href="./css/modal.css">
	<link rel="stylesheet" type="text/css" href="./css/post.css">


	<head>
	<body>
		<?php include("menu.php") ?>
		<center>
		<h2 class="name-galery-txt">Votre profil</h2>
		<br>
		<div id="info"><h3>Quelques informations sur vous : </h3></div>
		<ul>
			<li>Votre login est : <?= $afficher_profil['login'] ?></li>
			<li>Votre prenom est : <?= ucfirst($afficher_profil['prenom']) ?></li>
			<li>Votre nom est : <?= ucfirst($afficher_profil['nom']) ?></li>
			<li>Votre mail est : <?= $afficher_profil['mail'] ?></li>
			<li>Votre compte a été crée le : <?= $afficher_profil['creation_date'] ?></li>
		</ul>
		<h2 class="name-galery-txt">Vos photos</h2>
		<div class="galery">
			<article class="galery-flex-container" style="margin-bottom: 100px;">
			<?php 
				$count = 1000;
				$div = 0;
				foreach ($reponse as $donnees) {
					if ($div % 3 == 0)
						echo '<div class="column-galery">';
				?>
				<div style="position: relative;" class="galery-img-container-margin<?php if ($div % 3 == 2) echo '_last'?>">
						<a onclick="modal_onclick(<?= $count ?>)" href="#">
							<div class="galery-img-container">
								<img class="img-in-galery"  src="<?php echo $donnees['image_path'] ?>">
								<div class="overlay">
								<a style="position: absolute; top: 4px; right: 4px;" href="./remove_img.php?id=<?= $donnees['id_image']?>&path=1"><img id="remove" src="./ressources/img/remove.png" alt="Supprimer"></a>

									<div class="text"><img class="jaimee" src="./ressources/img/jaime.png"><?= number_format_short($donnees['like']) ?>
								</div>
								</div>
							</div>
						</a>
					</div>
					<!-- The Modal Images -->
					<div id="modal<?= $count ?>" class="modal">
						<div class="modal-image-post">
							<?php include "./post.php" ?>
						</div>
					</div>
				<!-- End of Modal -->
				<?php
					$count++;
					if ($div % 3 == 2)
						echo "</div>";
					$div++;
				}
				while ($div % 3 != 0)
				{
				?>
					<div class="galery-img-container-margin<?php if ($div % 3 == 2) echo '_last'?>">
						<div class="galery-img-container">
						</div>
					</div>
				<?php
					$div++;
				}?>
			</article>
			</center>
		</div>
	</body>
</html>
<script src="./script/modal.js"></script>

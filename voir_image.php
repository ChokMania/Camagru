<?php
	session_start();
	require './config/database.php';
	require './config/connexiondb.php';
	if (!isset($_SESSION['id']) || !isset($_SESSION['sa']) || (isset($_SESSION['sa']) && $_SESSION['sa'] != "1")) {
		header('Location: ./');
		exit;
	}

	// Récupèration de l'id passer en argument dans l'URL
	extract($_GET);
	// On récupère les informations de l'utilisateur grâce à son ID
	$afficher_image = $db->query('SELECT User.id, User.login,
									Image.id as id_image, Image.`user_id`,
									Image.image_path, Image.image_name, Image.like,
									Image.creation_date FROM `User`
									INNER JOIN `Image` ON User.id = Image.`user_id`
									WHERE Image.id = "'.$id.'"');
	$afficher_image = $afficher_image->fetch();
	if(!isset($afficher_image['id_image'])){
		header('Location: ./list_img.php');
		exit;
	}
	if (!empty($_POST)) {
		extract($_POST);
		$valid = true;
		if (isset($_POST['modification_image'])){
			$auteur = htmlentities(trim($auteur));
			echo($auteur);
			if (!empty($auteur)) {
				$valid = false;
				$sql = $db->query('SELECT COUNT(*) AS existe_pseudo FROM User WHERE `login` = "'.$auteur.'"');
				while ($data = $sql->fetch()) // recup sous formne de tab les donnes de la table
				{
					//Si il n'y a aucune ligne le login est inexistant
					if (($data['existe_pseudo'] != '0')) {
						$valid = true;
						$er_auteur = ("Le pseudo choisis existe");
					}
				}
				if (!$valid) {
					$er_auteur = ("Le pseudo choisis n'existe pas");
				}
				$sql = $db->query('SELECT `id` AS id_to_put FROM User WHERE `login` = "'.$auteur.'"');
				$sql = $sql->fetch();
			}
			if ($valid) {
				$sql['id_to_put'] = htmlentities(trim($sql['id_to_put']));
				htmlentities(trim($name));
				htmlentities(trim($path));
				htmlentities(trim($like));
				htmlentities(trim($name));
				htmlentities(trim($date));
				//On insert de facon securisé les donnees recup
				if ($_POST['auteur']) {
					$req = $db->query('UPDATE `Image` SET `user_id` = "'.$sql['id_to_put'].'" WHERE `id` = "'.$id.'"');
				}
				if ($_POST['name']) {
					//doit modifier le nom du fichier
					$req = $db->query('UPDATE `Image` SET `image_name` = "'.$name.'" WHERE `id` = "'.$afficher_image['id_image'].'"');
				}
				if ($_POST['path']) {
					//doit deplacer le fichier
					$req = $db->query('UPDATE `Image` SET `image_path` = "'.$path.'" WHERE `id` = "'.$afficher_image['id_image'].'"');
				}
				if ($_POST['like']) {
					$req = $db->query('UPDATE `Image` SET `like` = "'.$like.'" WHERE `id` = "'.$afficher_image['id_image'].'"');
				}
				if ($_POST['date']) {
					//rajouter l'heure
					$req = $db->query('UPDATE `Image` SET `creation_date` = "'.$date.'" WHERE `id` = "'.$afficher_image['id_image'].'"');
				}
				header('Location: .'.$_SERVER['REQUEST_URI']);
			}
		}
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
				<th><h2 style="text-align : center">Profil de l'image -> id : <?php echo $id ?></h2></th>
				<th><h2 style="text-align : center">Modification de l'Image</h2></th>
			<tr>
			<tr scope="row">
			</tr>
			<td>
			<br>
			<div>Quelques informations sur L'Image : </div>
			<img src="<?= $afficher_image['image_path']?>">
			<ul>
			<br>
				<li>Le login de l'auteur est : <?php echo $afficher_image['login'] . " => id : "  . $afficher_image['user_id']?></li>
				<li>Le nom est : <?= $afficher_image['image_name'] ?></li>
				<li>Son path est : <?= $afficher_image['image_path'] ?></li>
				<li>Elle possede : <?= $afficher_image['like'] ?> like(s)</li>
				<li>L'image a été posté le : <?= $afficher_image['creation_date'] ?></li>
				<br>
			</ul>
			<br>
			<center><form action="list_img.php"><button type="submit">Retour</button></form></center>
			</td>
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
				<input size=50 type="text" placeholder="Nouvel auteur" name="auteur" value="">
				<br>
				<br>
				<input size=50 type="text" placeholder="Nouveau nom" name="name" value="">
				<br>
				<br>
				<input size=50 type="text" placeholder="Nouveau path" name="path" value="">
				<br>
				<br>
				<input size=50 type="text" placeholder="Nouveau nombre de like" name="like" value="">
				<br>
				<br>
				<p>Date de creation : <input size=10 style="height:30px" type="date" name="date"></p>
				<button type="submit" name="modification_image">Envoyer</button>
			</form>
			<a href="modif_com.php?id=<?= $afficher_image['id_image'] ?>">Modifier ses commentaires</a>
			</td>
		</table>
	<br>
	</body>
	</center>
</html>
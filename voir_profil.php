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
	$afficher_profil = $db->query('SELECT * FROM User WHERE id = "'.$id.'"');
	$afficher_profil = $afficher_profil->fetch();
	if(!isset($afficher_profil['id'])){
		header('Location: ./list_users.php');
		exit;
	}
	if (!empty($_POST)) {
		extract($_POST);
		$valid = true;
		if (isset($_POST['modification_profil'])){
			$login = htmlentities(trim($login));
			$prenom = htmlentities(trim($prenom));
			$nom = htmlentities(trim($nom));
			$mail = htmlentities(strtolower(trim($mail)));
			if ($valid) {
				//On insert de facon securisé les donnees recup
				if ($_POST['login'])
				{
					$req = $db->query('UPDATE `User` SET `login` = "'.$login.'" WHERE `id` = "'.$id.'"');
				}
				if ($_POST['prenom'])
				{
					$req = $db->query('UPDATE `User` SET `prenom` = "'.$prenom.'" WHERE `id` = "'.$id.'"');
				}
				if ($_POST['nom'])
				{
					$req = $db->query('UPDATE `User` SET `nom` = "'.$nom.'" WHERE `id` = "'.$id.'"');
				}
				if ($_POST['mdp'])
				{
					$mdph = shamalo($mdp);
					$req = $db->query('UPDATE `User` SET `pwd` = "'.$mdph.'" WHERE `id` = "'.$id.'"');
				}
				if ($_POST['mail'])
				{
					$req = $db->query('UPDATE `User` SET `mail` = "'.$mail.'" WHERE `id` = "'.$id.'"');
				}
				if ($_POST['root']) {
					$req = $db->query('UPDATE `User` SET `super-root` = 1 WHERE `id` = "'.$id.'"');
				}
				else {
					$req = $db->query('UPDATE `User` SET `super-root` = 0 WHERE `id` = "'.$id.'"');
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
	<head>
	<body>
		<?php include("menu.php") ?>
		<table class="users">
			<tr>
				<th><h2 style="text-align : center">Profil de l'id : <?php echo $id ?></h2></th>
				<th><h2 style="text-align : center">Modification du profil</h2></th>
			<tr>
			<tr scope="row">
			</tr>
			<td>
			<br><br>
			<br>
			<div>Quelques informations sur vous : </div>
			<ul>
			<br>
				<li>Le login est : <?= $afficher_profil['login'] ?></li>
				<li>Le prenom est : <?= ucfirst($afficher_profil['prenom']) ?></li>
				<li>Le nom est : <?= ucfirst($afficher_profil['nom']) ?></li>
				<li>Le mail est : <?= $afficher_profil['mail'] ?></li>
				<li>Le compte a été crée le : <?= $afficher_profil['creation_date'] ?></li>
				<?php if ($afficher_profil['super-root'] == 1)
								echo "<li>Le compte est root</li>";?>
				<br>
				<br>
			</ul>
			<br>
			<center><form action="list_users.php"><button type="submit">Retour</button></form></center>
			</td>
			<td>
			<center>
			<form method="post" >
				<input size=50 type="text" placeholder="Nouveau login" name="login" value="" maxlength="10">
				<br>
				<br>
				<input size=50 type="text" placeholder="Nouveau prénom" name="prenom" value="" maxlength="50">
				<br>
				<br>
				<input size=50 type="text" placeholder="Nouveau nom" name="nom" value="" maxlength="50">
				<br>
				<br>
				<input size=50 type="email" placeholder="Nouvelle Adresse mail" name="mail" value="" maxlength="50">
				<br>
				<br>
				<input size=50 type="password" placeholder="Nouveau mot de passe" name="mdp" value="" maxlength="50">
				<br>
				<br>
				<p>Root :
				<label class="switch">
				<input style="margin-top:4px"type="checkbox" name="root" <?php if ($afficher_profil['super-root'] == 1) echo "checked";?>>
					<span class="slider round"></span>
				</label></p>
				<button type="submit" name="modification_profil">Envoyer</button>
			</form>
			</td>
		</table>
	<br>
	</body>
	</center>
</html>
<?php
	session_start();
	require './config/database.php';
	require './config/connexiondb.php';
	require './phpfunctions/mdp_is_secure.php';
	require './hashing/hash.php';
	// Si session dans ce cas go index
	if (!isset($_SESSION['logged_on']) || !isset($_SESSION['id'])) {
		header('Location: ./');
		exit;
	}
	$modif = 0;
	if (!empty($_POST)) {
		extract($_POST);
		$valid = true;
		if (isset($_POST['modification_profil'])){
			$old_mdp = htmlentities(trim($old_mdp));
			$mdp = htmlentities(trim($mdp));
			$confmdp = htmlentities(trim($confmdp));
			$sql = $db->query('SELECT `pwd` FROM `user` WHERE `id` = "'.$_SESSION['id'].'"');
			$data = $sql->fetch();
			if (!isset($data)) {
				$valid = false;
				$er_mdp = "Nous avons rencontré un problème avec votre requête.";
			}
			$old_mdp = shamalo($old_mdp);
			if ($old_mdp !== $data['pwd']) {
				$valid = false;
				$er_mdp = "L'ancien mot de passe ne correspond pas";
			}
			if (($er_mdp = mdp_is_secure($mdp)) != 1)
			{
				$valid = false;
			}
			else if ($mdp !== $confmdp){
				$valid = false;
				$er_mdp = "La confirmation du mot de passe ne correspond pas";
			}

			if ($valid) {
				$mdph = shamalo($mdp);
				//On insert de facon securisé les donnees recup
				if ($_POST['mdp'])
				{
					$modif = 1;
					$req = $db->exec('UPDATE `user` SET `pwd` = "'.$mdph.'" WHERE `id` = "'.$_SESSION['id'].'"');
				}
			}
		}
	}
?>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Modification du profil</title>
		<style>
		input {
			height : 25px;
			text-align : center;
		}
		.btn {
			background-color: green;
			border: none;
			color: white;
			padding: 16px 32px;
			text-align: center;
			font-size: 16px;
			margin: 4px 2px;
			opacity: 0.6;
			transition: 0.3s;
		}
		.btn:hover {opacity: 1}
		</style>
	</head>
	<body>
		<?php include 'menu.php' ?>
		<center>
		<h1 style="center">Modification du mot de passe</h1>
		<form method="post" >
			<?php
				if (isset($er_mdp)){
				?>
				<p style="color:red;"><?= $er_mdp ?></p>
				<?php
				}
				else if ($modif == 1){
					?><div>Le mot de passe a bien ete modifie.</div>
				<?php
					$modif = 0;
				}
			?>
			<input size=50 type="password" placeholder="Ancien mot de passe" name="old_mdp" value="" maxlength="25" required>
			<br>
			<br>
			<input size=50 type="password" placeholder="Mot de passe" name="mdp" value="" maxlength="25" required>
			<br>
			<br>
			<input size=50 type="password" placeholder="Confirmer le mot de passe" name="confmdp" maxlength="25" required>
			<br>
			<br>
			<button class="btn" type="submit" name="modification_profil">Envoyer</button>
		</form>
		</center>
	</body>
</html>
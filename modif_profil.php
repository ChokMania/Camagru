<?php
	session_start();
	require './config/database.php';
	require './config/connexiondb.php';
	// Si pas de session dans ce cas go index
	if (!isset($_SESSION['logged_on']) || $_SESSION['logged_on'] == '0'
			|| !isset($_SESSION['id']) || $_SESSION['id'] == "0") {
		header('Location: ./');
		exit();
	}
	if (!empty($_POST)) {
		extract($_POST);
		$valid = true;
		if (isset($_POST['modification_profil'])){
			$login = htmlentities(trim($login));
			$prenom = htmlentities(trim($prenom));
			$nom  = htmlentities(trim($nom));
			$mail = htmlentities(strtolower(trim($mail)));
			if (!empty($login))
			{
				$sql = $db->query('SELECT COUNT(*) AS existe_pseudo FROM User WHERE `login` = "'.$login.'"');
				while ($data = $sql->fetch()) // recup sous formne de tab les donnes de la table
				{
					//Si il n'y a aucune ligne le login est inexistant
					if (($data['existe_pseudo'] != '0')) {
						$valid = false;
						$er_login = ("Le pseudo choisis existe déjà");
					}
				}
			}
			if (!empty($mail) && !preg_match("/^[a-z0-9\-_.]+@[a-z]+\.[a-z]{2,3}$/i", $mail))
			{
				$valid = false;
				$er_mail = "Le mail n'est pas valide";
			}
			else if (!empty($login) && !empty ($mail) && $mail === $login) {
				$valid = false;
				$er_mail = "Le login et le mail ne peuvent pas etre les memes";
			}
			else if (!empty($mail))
			{
				$sql = $db->query('SELECT COUNT(*) AS existe_mail FROM User WHERE `mail` = "'.$mail.'"');
				while ($data = $sql->fetch()) // recup sous formne de tab les donnes de la table
				{
					//Si il n'y a aucune ligne le login est inexistant
					if (($data['existe_mail'] != '0')) {
						$valid = false;
						$er_login = ("Adresse email deja prise.");
					}
				}
			}
			if ($valid) {
				//On insert de facon securisé les donnees recup
				if ($_POST['login']){
					$req = $db->query('UPDATE `User` SET `login` = "'.$login.'" WHERE `id` = "'.$_SESSION['id'].'"');
				}
				if ($_POST['prenom']){
					$req = $db->query('UPDATE `User` SET `prenom` = "'.$prenom.'" WHERE `id` = "'.$_SESSION['id'].'"');
				}
				if ($_POST['nom']){
					$req = $db->query('UPDATE `User` SET `nom` = "'.$nom.'" WHERE `id` = "'.$_SESSION.'"');
				}
				if ($_POST['mail']){
					$req = $db->query('UPDATE `User` SET `mail` = "'.$mail.'" WHERE `id` = "'.$_SESSION['id'].'"');
				}
				if ($_POST['notifications']) {
					$req = $db->query('UPDATE `User` SET `notifications` = 1 WHERE `id` = "'.$_SESSION['id'].'"');
				}
				else {
					$req = $db->query('UPDATE `User` SET `notifications` = 0 WHERE `id` = "'.$_SESSION['id'].'"');
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

			.switch {
			position: relative;
			display: inline-block;
			width: 30px;
			height: 17px;
			}

			.switch input {
				opacity: 0;
				width: 0;
				height: 0;
			}

			.slider {
				position: absolute;
				cursor: pointer;
				top: -12;
				left: 0;
				right: 0;
				bottom: 12;
				background-color: #ccc;
				-webkit-transition: .4s;
				transition: .4s;
			}

			.slider:before {
				position: absolute;
				content: "";
				height: 13px;
				width: 13px;
				left: 2px;
				bottom: 2px;
				background-color: white;
				-webkit-transition: .4s;
				transition: .4s;
			}

			input:checked+.slider {
				background-color: rgb(93, 243, 33);
			}

			input:focus+.slider {
				box-shadow: 0 0 1px #2196F3;
			}

			input:checked+.slider:before {
				-webkit-transform: translateX(13px);
				-ms-transform: translateX(13px);
				transform: translateX(13px);
			}

			.slider.round {
				border-radius: 34px;
			}

			.slider.round:before {
				border-radius: 50%;
			}
		</style>
	</head>
	<body>
		<?php include 'menu.php' ?>
		<center>
		<h1 style="center">Modification du profil</h1>
		<form method="post" >
			<?php
				if (isset($er_login)){
					?>
					<div><?= $er_login ?></div>
					<?php
				}
				$sql = $db->query('SELECT * FROM User WHERE `id` = "'.$_SESSION['id'].'"');
				$data = $sql->fetch();
				?>
			<p>Ancien Login : <?php echo $data['login'] ?></p>
			<input size=50 type="text" placeholder="Nouveau login" name="login" value="" maxlength="10">
			<br>
			<br>
			<p>Ancien Prenom : <?php echo ucfirst($data['prenom']) ?></p>
			<input size=50 type="text" placeholder="Nouveau prénom" name="prenom" value="" maxlength="50">
			<br>
			<br>
			<p>Ancien Nom : <?php echo ucfirst($data['nom']) ?></p>
			<input size=50 type="text" placeholder="Nouveau nom" name="nom" value="" maxlength="50">
			<br>
			<br>
			<?php
				if (isset($er_mail)){
				?>
					<div><?= $er_mail ?></div>
				<?php
				}
			?>
			<p>Ancien Mail : <?php $data['mail'] ?></p>
			<input size=50 type="email" placeholder="Nouvelle Adresse mail" name="mail" value="" maxlength="50">
			<br>
			<p>Activer notifications :
				<label class="switch">
				<input style="margin-top:4px"type="checkbox" name="notifications" <?php if ($data['notifications'] == 1) echo "checked";?>>
					<span class="slider round"></span>
				</label></p>
			<br>
			<button class="btn" type="submit" name="modification_profil">Envoyer</button>
		</form>
		</center>
	</body>
</html>
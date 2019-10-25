<?php
session_start();
require './config/database.php';
require './config/connexiondb.php';
if (!isset($_SESSION['id']) || !isset($_SESSION['sa']) || (isset($_SESSION['sa']) && $_SESSION['sa'] != "1")) {
	header('Location: ./');
	exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="utf-8" />
	<title> Liste des Users </title>
	<link rel="stylesheet" type="text/css" href="./css/table.css">	
	<style>
		#remove {
			width: 10px;
			height: 10px;
			margin-left: 4vw;
		}

		.marge {
			margin-left: 2em;
		}

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
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
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
			background-color: #2196F3;
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
	<?php
	include("menu.php");
	?>
	<h2 style="text-align: center"> Liste des Users </h2>
	<br>
	<form method="GET">
		<center>
			<input style="width:250px;" type="search" name="research" placeholder="Recherche..." value="" />
			<input type="submit" value="Valider" />
			<br>
			<br>
			<p>Id: <input type="radio" name="by" value="id" />
				<span class="marge">Login: <input type="radio" name="by" value="login" /></span>
				<span class="marge">Prenom: <input type="radio" name="by" value="prenom" /></span>
				<span class="marge">Nom: <input type="radio" name="by" value="nom" /></span></p>
				<br>
				<p>Afficher les roots seulement :
					<label class="switch">
						<input type="checkbox" name="root">
						<span class="slider round"></span>
					</label></p>
	</form>
	<br>
	<table class="users">
		<tr>
			<th>ID</th>
			<th>Login</th>
			<th>Email</th>
			<th>Prenom</th>
			<th>Nom</th>
			<th>PP</th>
			<th>Date de Creation</th>
			<th>Root</th>
			<th>Modification</th>
			<th>Suppression</th>
		</tr>
		<?php
		$reponse = $db->query("SELECT * FROM `User`");
		if (isset($_GET) && !empty($_GET['root'])) {
			$root = 1;
			$reponse = $db->query("SELECT * FROM `User` WHERE `super-root` = 1");
		}
		if (isset($_GET) && !empty($_GET['research'])) {
			extract($_GET);
			$root = 0;
			if (!empty($_GET['root']))
				$root = 1;
			if (isset($research) && !empty($research) && isset($by) && $root == 0) {
				if ($by == "id")
					$reponse = $db->query('SELECT * FROM `User` WHERE `id` LIKE "' . $research . '"');
				if ($by == "login")
					$reponse = $db->query('SELECT * FROM `User` WHERE `login` LIKE "%' . $research . '%"');
				if ($by == "prenom")
					$reponse = $db->query('SELECT * FROM `User` WHERE `prenom` LIKE "%' . $research . '%"');
				if ($by == "nom")
					$reponse = $db->query('SELECT * FROM `User` WHERE `nom` LIKE "%' . $research . '%"');
			} else if (isset($research) && !empty($research) && isset($by) && $root == 1) {
				if ($by == "id")
					$reponse = $db->query('SELECT * FROM `User` WHERE `id` LIKE "' . $research . '" AND `super-root`= 1');
				if ($by == "login")
					$reponse = $db->query('SELECT * FROM `User` WHERE `login` LIKE "%' . $research . '%" AND `super-root`= 1');
				if ($by == "prenom")
					$reponse = $db->query('SELECT * FROM `User` WHERE `prenom` LIKE "%' . $research . '%" AND `super-root`= 1');
				if ($by == "nom")
					$reponse = $db->query('SELECT * FROM `User` WHERE `nom` LIKE "%' . $research . '%" AND `super-root`= 1');
			}
		}
		$reponse = $reponse->fetchAll();
		foreach ($reponse as $donnees) {
			?>
			<tr scope="row">
				<td><?php echo $donnees['id']; ?></td>
				<td><?php echo $donnees['login']; ?></td>
				<td><?php echo $donnees['mail']; ?></td>
				<td><?php echo $donnees['prenom']; ?></td>
				<td><?php echo $donnees['nom']; ?></td>
				<td><?php echo $donnees['pp']; ?></td>
				<td><?php echo $donnees['creation_date']; ?></td>
				<td><?php
					if ($donnees['super-root'] == "1")
						echo "Oui";
					else echo "Non"; ?></td>
				<td><a href="./voir_profil.php?id=<?= $donnees['id'] ?>">Modifier le profil</a></td>
				<td><a href="./remove_user.php?id=<?= $donnees['id'] ?>"><img id="remove" src="./ressources/img/remove.png" alt="Supprimer"></a></td>
			</tr>
		<?php } ?>
	</table>
	<center>
		<br>
		<form action="./list_users.php"><button class="btn rea" type="submit">Reactualiser</button>
	</center>
</body>

</html>
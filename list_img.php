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
	<title> Liste des Images </title>
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
	<h2 style="text-align: center"> Liste des Images </h2>
	<br>
	<table class="users">
		<tr>
			<th>Id</th>
			<th>Id_User</th>
			<th>Login</th>
			<th>Path</th>
			<th>Nom</th>
			<th>Like</th>
			<th>Date de Creation</th>
			<th>Utilisateur root</th>
			<th>Modification</th>
			<th>Suppression</th>
		</tr>
		<?php
		$reponse = $db->query("SELECT User.id, User.login, User.`super-root`,
							Image.id as id_image,
							Image.image_path, Image.image_name, Image.like,
							Image.creation_date FROM `User`
							INNER JOIN `Image` ON User.id = Image.user_id");
		$reponse = $reponse->fetchAll();
		foreach ($reponse as $donnees) {
			?>
			<tr scope="row">
				<td><?php echo $donnees['id_image']; ?></td>
				<td><?php echo $donnees['id']; ?></td>
				<td><?php echo $donnees['login']; ?></td>
				<td><?php echo $donnees['image_path']; ?></td>
				<td><?php echo $donnees['image_name']; ?></td>
				<td><?php echo $donnees['like']; ?></td>
				<td><?php echo $donnees['creation_date']; ?></td>
				<td><?php
					if ($donnees['super-root'] == "1")
					echo "Oui";
					else echo "Non"; ?></td>
				<td><a href="./voir_image.php?id=<?= $donnees['id_image']?>">Modifier le post</a></td>
				<td><a href="./remove_img.php?id=<?= $donnees['id_image']?>"><img id="remove" src="./ressources/img/remove.png" alt="Supprimer"></a></td>
			</tr>
		<?php } ?>
	</table>
	<center>
		<br>
		<form action="./list_img.php"><button class="btn rea" type="submit">Reactualiser</button>
	</center>
</body>

</html>
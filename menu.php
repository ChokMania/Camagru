
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="./css/menu.css">
		<link rel="stylesheet" type="text/css" href="./css/modal.css">
	</head>
	<body>
		<div class="topnav" id="myTopnav">
			<a href="./"
				<?php if ($_SERVER['PHP_SELF'] === "/index.php")
				echo 'class="active"'?>
			>Accueil</a>
			<a href="./galerie.php"
			<?php if ($_SERVER['PHP_SELF'] === "/galerie.php")
				echo 'class="active"'?>
			>Galerie</a>
			<?php
				if (!isset($_SESSION['logged_on']) || $_SESSION['logged_on'] == 0) {
					echo '<a href="#" onclick="modal_onclick(1)" >Connexion</a>';
					echo '<a href="#" onclick="modal_onclick(2)" >Inscription</a>';
				}
				else {
					echo '<div class="dropdown">
								<button class="dropbtn">Mon Compte
									<i class="arrow">&#9660;</i>
								</button>
								<div class="dropdown-content">
									<a href="./profil.php">Afficher mon profil</a>
									<a href="./modif_profil.php">Modifier mon profil</a>
									<a href="./modifier_mdp.php">Modifier mon mot de passe</a>
								</div>
							</div> ';
					if (isset($_SESSION['sa'], $_SESSION['logged_on'])) {
						echo '<div class="dropdown">
								<button class="dropbtn">Administration
									<i class="arrow">&#9660;</i>
								</button>
								<div class="dropdown-content">
									<a href="./list_users.php">Liste des Users</a>
									<a href="list_img.php">Liste des Images</a>
								</div>
							</div> ';
					}
					echo '<a href="logout.php">Logout</a>';
					echo '<img href="#" src="ressources\img\logo-insta.png" onclick="modal_onclick(3)" alt="take a picture" class="insta-logo">';
				}
			?>
			<a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
		</div>
		<div style="padding-left:16px"></div>
			<!-- The Modal take a picture -->
	<div id="modal03" class="modal">
	<div class="modal-image">
		<?php
		include './camera.php' ?>
	</div>
	</div>
	<!-- End of Modal -->
		<script>
			function myFunction() {
				var x = document.getElementById("myTopnav");
				if (x.className === "topnav") {
					x.className += " responsive";
				} else {
					x.className = "topnav";
				}
			}
		</script>
	<script src="./script/modal.js"></script>
	</body>
</html>

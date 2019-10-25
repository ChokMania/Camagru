<?php
session_start();
require './config/database.php';
require './config/connexiondb.php';
require './hashing/hash.php';
require './phpfunctions/mdp_is_secure.php';
$magic = "c00f0c4675b91fb8b918e4079a0b1bac";

if (!empty($_SESSION) && $_SESSION['logged_on'] == 1)
{
	echo "deja log\n";
	header('Location: ../');
	exit();
}
if (isset($_POST) && !empty($_POST)) {
	extract($_POST);
	if (isset($_POST['login']) && isset($_POST['code'])) {
		$login = htmlentities(trim($login));
		$codepost = htmlentities(trim($code));
		$mdp = htmlentities(trim($mdp));
		$confmdp = htmlentities(trim($confmdp));
		$sql = "SELECT `login`, `verified`, `mail` FROM user WHERE `login` = :login";
		$stmt = $db->prepare($sql);
		//Bind value.
		$stmt->bindValue(':login', $login);
		//Execute.
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($user === false) {
			$er_login_forget_two = ("Le login ou l'email ne correspond pas a un utilisateur inscrit");
		}
		else if ($user['verified'] == '0') {
			$er_login_forget_two = ("Le compte n'est pas actif");
		}
		else if (($er_mdp = mdp_is_secure($mdp)) != 1)
		{
			;
		}
		else {
			if ($_SESSION['code'] === $codepost)
			{
				//bon code, verrification du mdp
				$valid = true;
				if ($mdp !== $confmdp){
					$valid = false;
					$er_login_forget_two = "La confirmation du mot de passe ne correspond pas";
				}
				if ($valid) {
					$mdph = shamalo($mdp);
					//On insert de facon securisé les donnees recup
					$req = $db->exec('UPDATE `user` SET `pwd` = "'.$mdph.'" WHERE `login` = "'.$login.'"');
					header('Location: ../');
					exit();
				}
			}
			else
			{
				unset($er_mdp);
				$er_login_forget_two = ("Le code inscrit n'est pas valide");
			}
		}
	}
}
?>

<html>
	<head>
		<meta charset="UTF-16">
		<title>forget_password_change</title>
		<link rel="stylesheet" type="text/css" href="./css/modal.css">
	</head>
	<body>
		<?php include 'menu.php' ?>
		<center>
			<?php
				if (isset($er_mdp)) {
					?>
					<p style="color:red;"><?= $er_mdp ?></p>
				<?php
				}
			?>
			<h1> mot de passe oublie?</h1>
			<?php
					if (isset($er_login_forget_two)) {
				?>
					<p style="color:red;"><?= $er_login_forget_two ?></p>
				<?php
					}
				?>
			<p>Vous trouverez le code dans un mail que nous venons de vous envoyer.</p>
			<form action="" method="post">
				<label for="Inputlogin4">Votre login</label>
				<br>
				<input id="Inputlogin4" type="text" name="login" placeholder="Votre login" maxlength="10" required>
				<br>
				<label for="code">Votre code</label>
				<p style="display: none;"><?php echo $_SESSION['code'];?></p>
				<br>
				<input id="code" type="text" name="code" placeholder="Votre code" maxlength="6" minlength="6" required>
				<br>
				<label for="mdp">Votre nouveau mdp</label>
				<br>
				<input size=50 type="password" placeholder="Mot de passe" name="mdp" maxlength="25" required>
				<br>
				<label for="confmdp">confmdp</label>
				<br>
				<input size=50 type="password" placeholder="Confirmer le mot de passe" name="confmdp" maxlength="25" required>
				<br>
				<button type="submit" name="send-forget-passwd-code" value="ok">Valider</button>
			</form>
			<?php
				$code = $_SESSION['code'];
				$login = $_SESSION['login'];
				$mail = $_SESSION['mail'];
			?>
			<a href="#" onclick="myAjaxSendMailForget('<?=$code?>', '<?=$login?>', '<?=$mail?>')">renvoyer le mail</a>
		</center>
	<!-- The Modal connection -->
	<div id="modal01" class="modal">
		<div class="modal-content">
			<?php
			include './login/connexion.php' ?>
		</div>
	</div>
	<!-- End of Modal -->
	<!-- The Modal inscription -->
	<div id="modal02" class="modal">
		<div class="modal-content">
			<?php
			include './login/inscription.php' ?>
		</div>
	</div>
	<!-- End of Modal -->
	</body>
	<?php include 'footer.html' ?>
</html>
<script src="./script/modal.js"></script>
<script src="./script/Ajax.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
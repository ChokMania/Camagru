<?php
session_start();
require './config/database.php';
require './config/connexiondb.php';
require './hashing/hash.php';
$magic = "c00f0c4675b91fb8b918e4079a0b1bac";

// Si user logged go index
if (!empty($_SESSION) && $_SESSION['logged_on'] == 1)
{
	header('Location: ../');
	exit();
}
if (isset($_POST) && !empty($_POST)) {
	extract($_POST);

	if (isset($_POST['login'])) {
		$login = htmlentities(trim($login));
		$sql = "SELECT `login`, `mail`, `verified` FROM user WHERE `login` = :login AND `mail` = :mail";
		$stmt = $db->prepare($sql);
		//Bind value.
		$stmt->bindValue(':login', $login);
		$stmt->bindValue(':mail', $mail);

		//Execute.
		$stmt->execute();
		//Fetch row return the result of the sql request as an array. NULL if no result.
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($user === false) {
			$er_login_forget = ("Le login ou l'email ne correspond pas a un utilisateur inscrit");
		}
		else if ($user['verified'] == '0') {
			$er_login_forget = ("Le compte n'est pas actif");
		}
		else {
			require 'mail.php';
			$code = substr(shamalo(strval(calcsumchar($user['login']) + rand(1, 10000))), rand(0, 194), 6);
			ft_sendmail_forget($user['mail'], $user['login'], $code);
			$_SESSION['code'] = $code;
			$_SESSION['login'] = $user['login'];
			$_SESSION['mail'] = $user['mail'];
			header('Location: ./mdp_forget_change.php');
			exit();
		}
	}
	else
		$er_login_forget = "entre un login valide!";
}
?>
<html>
	<head>
		<meta charset="UTF-16">
		<title>forget_password</title>
		<link rel="stylesheet" type="text/css" href="./css/modal.css">
	</head>
	<body>
		<?php include 'menu.php' ?>
		<center>
				<h1> mot de passe oublie?</h1>
				<?php
					if (isset($er_login_forget)) {
				?>
					<p style="color:red;"><?= $er_login_forget ?></p>
				<?php
					}
				?>
				<form action="" method="post">
				<label for="Inputlogin1">Votre login</label>
				<br>
				<input id="Inputlogin5" type="text" name="login" placeholder="Votre login" maxlength="10" required>
				<br>
				<label for="Inputemail1">Votre email</label>
					<br>
					<input type="email" class="form-control" id="Inputemail2" placeholder="Adresse mail" name="mail"
					value="<?php if (isset($mail)) {
						echo $mail;
					} ?>" maxlength="50" required>
					<br>
				<button type="submit" name="send-forget-passwd-mail" value="ok">Valider</button>
				</form>
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
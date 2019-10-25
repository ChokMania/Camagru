<?php
session_start();
require './config/database.php';
require './config/connexiondb.php';

function ft_sendnotif($mail, $login, $liker, $commentorlike, $comment) {
	if ($commentorlike == 1) {
		$commentorlike = "like";
	}
	else {
		$commentorlike = "commentaire";
	}
	$sujet = ucfirst($commentorlike) . " sur un de vos postes camagru";
	$entete = "From: Camagru" ;
	$message = "L'utilisateur " . $liker. " a mis un " . $commentorlike . " sur l'un de vos postes.";
	if ($commentorlike != "like")
	{
		$message = $message . '
Commentaire : "' . $comment . '"';
	}
	mail($mail, $sujet, $message, $entete);
}

function ft_sendmail($mail, $cle, $login) {
	$sujet = "Activation de votre compte Camagru";
	$entete = "From: Camagru" ;
	$message = 'Bienvenue sur le Camagru de Julien et Malo powered by 42 school mac,
		Pour activer votre compte, veuillez cliquer sur le lien ci dessous ou le copier/coller dans votre navigateur internet.

		http://' . $_SERVER['HTTP_HOST'] . '/login/activation.php?log='.urlencode($login).'&cle='.urlencode($cle).'

		Merci de votre confiance.
		---------------
		Ceci est un mail automatique, Merci de ne pas y repondre.';
	mail($mail, $sujet, $message, $entete);
}

function ft_sendmail_forget($mail, $login, $code) {
	$sujet = "Modification de votre compte Camagru";
	$entete = "From: Camagru" ;
	$message = "Bienvenue sur le Camagru de Julien et Malo powered by 42 school mac,
	Pour modifier votre mdp, veuillez inscrire ce code : " . $code . " et entrez votre nouveau
	mot de passe sur la page.

	---------------
	Ceci est un mail automatique, Merci de ne pas y repondre.";
	mail($mail, $sujet, $message, $entete);
	}

if(isset($_POST) && $_POST['call'] === "ft_sendmail_forget") {
	ft_sendmail_forget($_POST['mail'], $_POST['login'], $_POST['code']);
	echo "email sent!";
}
?>
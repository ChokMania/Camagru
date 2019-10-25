<?php
session_start();
require '../config/database.php';
require '../config/connexiondb.php';
// Récupération des variables nécessaires à l'activation
if (!isset($_GET['log']) || !isset($_GET['cle'])) {
	header('Location: ../.');
	exit;
}
$login = $_GET['log'];
$cle = $_GET['cle'];
// Récupération de la clé correspondant au $login dans la base de données
$req = $db->query('SELECT `verified`, `act-key`, `login` FROM user WHERE `login` = "' . $login . '"');
$row = $req->fetch();
$clebdd = $row['act-key'];	// Récupération de la clé
$actif = $row['verified']; // $actif contiendra alors 0 ou 1

// On teste la valeur de la variable $actif récupéré dans la BDD
if($actif == '1') // Si le compte est déjà actif on prévient
{
	echo "Votre compte est déjà actif !";
}
else // Si ce n'est pas le cas on passe aux comparaisons
{
	if($cle == $clebdd) // On compare nos deux clés	
	{
		// Si elles correspondent on active le compte !	
		echo "Votre compte a bien été activé !";

		// La requête qui va passer notre champ actif de 0 à 1
		$req = $db->exec('UPDATE user SET verified = 1 WHERE `login` = "' . $login . '"');
	}
	else // Si les deux clés sont différentes on provoque une erreur...
	{
		echo "Erreur ! Ce compte ne peut être activé...";
	}
}
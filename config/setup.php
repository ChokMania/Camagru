<?php
$_SESSION['logged_on'] = "0";
$_SESSION['sa'] = "0";
$_SESSION['id'] = "0";
//lancer avec php config/setup.sh
require 'database.php';
require 'hashing/hash.php';
$dsn = "mysql:host=".$DB_HOST;
try {
	$db = new PDO($dsn, $DB_USER, $DB_PASSWORD);
}
catch (Exception $e) {
	print "Error message :\t" . $e->getMessage() . "\n";
	exit();
}
echo("connection to db ok!\n");
// On se connecte (user:root mdp:root)
$sql = "CREATE DATABASE IF NOT EXISTS ".$DB_NAME;
$db->exec($sql);
$sql = "USE ".$DB_NAME;
$db->exec($sql);
$sql = "CREATE TABLE IF NOT EXISTS `User` (
		`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		`login` VARCHAR(10) NOT NULL,
		`mail` VARCHAR(255) NOT NULL,
		`prenom` VARCHAR(255) NOT NULL,
		`nom` VARCHAR(255) NOT NULL,
		`pp` VARCHAR(255) NOT NULL,
		`pwd` VARCHAR(255) NOT NULL,
		`creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		`act-key` VARCHAR(255) NOT NULL DEFAULT '0',
		`verified` INT NOT NULL DEFAULT '0',
		`super-root` INT NOT NULL DEFAULT '0',
		`notifications` INT NOT NULL DEFAULT '1'
	)";
$db->exec($sql);
$sql = "CREATE TABLE IF NOT EXISTS `Image` (
		`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		`user_id` VARCHAR(10) NOT NULL,
		`image_path` VARCHAR(255) NOT NULL,
		`image_name` VARCHAR(255) NOT NULL,
		`like` INT NOT NULL DEFAULT '0',
		`creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
	)";
$db->exec($sql);
$sql = "CREATE TABLE IF NOT EXISTS `Comment` (
		`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		`user_id` VARCHAR(10) NOT NULL,
		`id_image` INT UNSIGNED,
		`description` VARCHAR(255) NOT NULL,
		`creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
	)";
$db->exec($sql);
$sql = "CREATE TABLE IF NOT EXISTS `Like` (
	`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	`img_id` INT UNSIGNED,
	`liker_id` INT UNSIGNED,
	`like_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
)";
$db->exec($sql);
// Creation admin root
$mdph = shamalo("root");
$req = $db->prepare('INSERT INTO `User` (`login`, `prenom`, `nom`, `mail`, `pp`, `pwd`, `act-key`, `verified`, `super-root`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
$req->execute(array('judumay', 'julien', 'dumay', 'julien.dumay@hotmail.fr', './ressources/img/default.png', $mdph, 'abcd', 1, 1));
$req = $db->prepare('INSERT INTO `User` (`login`, `prenom`, `nom`, `mail`, `pp`, `pwd`, `act-key`, `verified`, `super-root`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
$req->execute(array('mabouce', 'malo', 'bouce', 'ma.sithis@gmail.com', './ressources/img/default.png',$mdph, 'abcd', 1, 1));
$mdph = shamalo("test");
$req = $db->prepare('INSERT INTO `User` (`login`, `prenom`, `nom`, `mail`, `pp`, `pwd`, `verified`, `act-key`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
$req->execute(array('test', 'test', 'test', 'test@gmail.com', './ressources/img/default.png', $mdph, 1, 'ahd'));
// creation d'une image
$db->exec("INSERT INTO `Image` (`user_id`, `image_name`, `image_path`, `like`) VALUES ('3', '5daf03a206625.png', './ressources/screenshots/5daf03a206625.png', 0);");
$db->exec("INSERT INTO `Image` (`user_id`, `image_name`, `image_path`, `like`) VALUES ('3', '5daf03a206626.png', './ressources/screenshots/5daf03a206626.png', 0);");
$db->exec("INSERT INTO `Image` (`user_id`, `image_name`, `image_path`, `like`) VALUES ('3', '5daf03a206627.png', './ressources/screenshots/5daf03a206627.png', 0);");
$db->exec("INSERT INTO `Image` (`user_id`, `image_name`, `image_path`, `like`) VALUES ('3', '5daf03a206628.png', './ressources/screenshots/5daf03a206628.png', 0);");
$db->exec("INSERT INTO `Image` (`user_id`, `image_name`, `image_path`, `like`) VALUES ('3', '5daf03a206629.png', './ressources/screenshots/5daf03a206629.png', 0);");
$db->exec("INSERT INTO `Image` (`user_id`, `image_name`, `image_path`, `like`) VALUES ('3', '5daf03a206630.png', './ressources/screenshots/5daf03a206630.png', 0);");
$db->exec("INSERT INTO `Image` (`user_id`, `image_name`, `image_path`, `like`) VALUES ('3', '5daf03a206625.png', './ressources/screenshots/5daf03a206625.png', 0);");
$db->exec("INSERT INTO `Image` (`user_id`, `image_name`, `image_path`, `like`) VALUES ('3', '5daf03a206626.png', './ressources/screenshots/5daf03a206626.png', 0);");
$db->exec("INSERT INTO `Image` (`user_id`, `image_name`, `image_path`, `like`) VALUES ('3', '5daf03a206627.png', './ressources/screenshots/5daf03a206627.png', 0);");

// creation d'un commentaire
$db->exec("INSERT INTO `Comment` (`user_id`, `id_image`,`description`) VALUES ('1', '1', 'Ceci est un test')");
//creation d'un like
$db->exec("INSERT INTO `like` (`img_id`, `liker_id`) VALUES ('1', '1')");
echo "FIN DU SETUP\n";





?>
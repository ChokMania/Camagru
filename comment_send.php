<?php
	session_start();
	require './config/database.php';
	require './config/connexiondb.php';
	require './mail.php';

		if (isset($_SESSION['logged_on']) && isset($_SESSION['id']) && isset($_POST) && isset($_POST)) {
			extract($_POST);
			$id = $_SESSION['id'];
			$com = htmlentities(trim($com));
			$req = $db->prepare('INSERT INTO `Comment` (`user_id`, `id_image`,`description`) VALUES (?, ?, ?)');
			$req->execute(array($id, $image, $com));
			unset($_POST);
			
			$notif = $db->query('SELECT `image`.`id`, `user_id`, `user`.`notifications`, `user`.`mail`, `user`.`login`
			FROM `image`, `user`
			WHERE `image`.`id` = "'. $image .'" AND `user_id` = `user`.`id`');
			$notif = $notif->fetch();
			$liker = $db->query('SELECT `id`, `login` FROM `user` WHERE `id` = "' . $_SESSION['id']. '"');
			$liker = $liker->fetch();

			if ($notif['notifications'] == 1)
			{
				ft_sendnotif($notif['mail'], $notif['login'], $liker['login'], 0, $com);
			}
			header('Location: ./galerie.php');
		}
?>
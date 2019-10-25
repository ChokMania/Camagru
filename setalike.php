<?php
session_start();
require './config/database.php';
require './config/connexiondb.php';
require './mail.php';
// Si user logged go index
if (empty($_SESSION) || $_SESSION['logged_on'] != 1)
{
	echo "Connect if you want to like this image.";
	exit();
}
if ($_POST['action'] == 'call_a_like') {
	$users = $db->query('SELECT id, img_id, liker_id  FROM `like`
						WHERE img_id = "'.$_POST['img_id'].'"
						AND liker_id = "'.$_SESSION['id'].'"');
	$users_liked = $users->fetch();
	$data = array();
	if (isset($users_liked) && $users_liked['id'] >= 1)
	{
		$like = -1;
		$users = $db->query('DELETE FROM `like` WHERE id = "'.$users_liked['id'].'"');
		$data['one'] = "jaime_pas.png";
	}
	else
	{
		$like = 1;
		$req = $db->prepare("INSERT INTO `like` (`img_id`, `liker_id`) VALUES (?, ?)");
		$req->execute(array($_POST['img_id'], $_SESSION['id']));
		$img_id = $_POST['img_id'];
		$notif = $db->query('SELECT `image`.`id`, `user_id`, `user`.`notifications`, `user`.`mail`, `user`.`login`
							FROM `image`, `user`
							WHERE `image`.`id` = "'. $img_id .'" AND `user_id` = `user`.`id`');
		$notif = $notif->fetch();
		$liker = $db->query('SELECT `id`, `login` FROM `user` WHERE `id` = "' . $_SESSION['id']. '"');
		$liker = $liker->fetch();

		if ($notif['notifications'] == 1)
		{
			ft_sendnotif($notif['mail'], $notif['login'], $liker['login'], 1, 0);
		}
		$data['one'] = "jaime.png";
	}
	$db->query('UPDATE `image` SET `like` = `like` + "'.$like.'" WHERE id = "'.$_POST['img_id'].'"');
	$nb_like = $db->query('SELECT `like`, `id` FROM `Image` WHERE id = "'.$_POST['img_id'].'"');
	$nb_like = $nb_like->fetch();
	$data['two'] = $nb_like[0];
	echo json_encode($data);
}
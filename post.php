<?php
	session_start();
	require './config/database.php';
	require './config/connexiondb.php';
	// S'il n'y a pas de session alors on ne va pas sur cette page
	$profil_image = $db->query('SELECT User.id, User.login,
									Image.id as id_image, Image.`user_id`,
									Image.image_path, Image.image_name, Image.like,
									Image.creation_date FROM `User`
									INNER JOIN `Image` ON User.id = Image.`user_id`
									WHERE Image.id = "'.$donnees['id_image'].'"');
	$profil_image = $profil_image->fetch();
	$id_img = $profil_image['id_image'];
?>

<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Mon profil</title>
		<link rel="stylesheet" type="text/css" href="./css/galerie.css">
	<head>
	<body>
		<?php
			$reponse = $db->query('SELECT `Comment`.`id`, `Comment`.`user_id`, Comment.`id_image`,
			`Comment`.`description`, `Comment`.`creation_date`,
			`Image`.`id` as img_id, `Image`.`like`,
			`Image`.`user_id` as `id_user_img` FROM `Comment`
			INNER JOIN `Image` ON Comment.id_image = `Image`.`id`
			WHERE `Image`.`id`= "'.$profil_image['id_image'].'"');
			$reponse = $reponse->fetchAll();
		?>
		<div class="parent_div_container_2">
			<?php echo '<a id="close-img" onclick="hide_modal('.$count.')">&#10006</a>'?>
			<div class="child-post-1">
				<img id="img-post-1" src="<?=$profil_image['image_path']?>">
				<div class="overlay">
					<div class="text-post">
						<?php
							$is_liked = $db->query('SELECT id, img_id, liker_id  FROM `like`
													WHERE img_id = "'.$id_img.'"
													AND liker_id = "'.$_SESSION['id'].'"');
							$is_like = $is_liked->fetch();
							if (isset($is_like) && $is_like['id'] >= 1){$tqt = 'jaime.png';}
							else {$tqt = 'jaime_pas.png';}
						?>
						<img onclick="myAjaxChangeLike(`<?=$id_img?>`, `<?=$count?>`)" class="jaime "id="jaime<?=$count?>"
						src="./ressources/img/<?=$tqt?>">
						<a id="nb_like<?=$count?>"><?= number_format_short($donnees['like']) ?></a>
					</div>
				</div>
			</div>
			<div class="child-post-2">
				<center>
					<p width="100%"><?php echo "Posted By: " . $profil_image['login']?></p>
				</center>
				<div id="mega_box">
					<?php
					if (isset($reponse['0']))
					{
						foreach ($reponse as $donnees) {
							$sql = $db->query ('SELECT `user`.`id`, `user`.`login`, `comment`.`user_id` FROM `comment` INNER JOIN `user` ON `user`.`id` = `comment`.`user_id`
												WHERE `user`.`id`="'.$donnees['user_id'].'" LIMIT 0,1');
							$sql = $sql->fetchAll();
						?>
						<div id="box"><p style="margin-left: 5px;"><?php echo $donnees['description']?></p>
							<small style="margin-left: 10px;">By: <?=$sql[0]['login']?> at: <?=$donnees['creation_date']?></small>
						</div>
						<br>
					<?php }
					}
					?>
				</div>
				<!---after megabox--->
				<div class="like-comment">
					<?php
						if ((isset($_SESSION['logged_on']) && isset($_SESSION['id'])))
						{
						?>
							<form method="post" action="comment_send.php">
							<input type="hidden" name="image" value="<?=$profil_image['id_image']?>">
							<input type="hidden" name="modal_id" value="<?=$count?>">
							<textarea cols="4" maxlength="250" name="com" required></textarea>
							<input type="submit" value="Send">
						</form>
						<?php }
						else {
							?><small>CONNECTE TOI</small>
						<?php } ?>
				</div>
			</div>
		</div>
	</body>
</html>
<script src="./script/ajax.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
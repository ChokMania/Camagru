<?php
session_start();
require './config/database.php';
require './config/connexiondb.php';
require './hashing/hash.php';
require './phpfunctions/mdp_is_secure.php';
require './phpfunctions/number_format.php';
$magic = "c00f0c4675b91fb8b918e4079a0b1bac";

$sth = $db->prepare('SELECT count(*) as total from image');
$sth->execute();
$image_total = $sth->fetch()[0];
$element_per_page = 12;
$start = 0;
?>
<html>

<head>
	<meta charset="UTF-16">
	<title>galerie</title>
	<link rel="stylesheet" type="text/css" href="./css/galerie.css">
	<link rel="stylesheet" type="text/css" href="./css/modal.css">
	<link rel="stylesheet" type="text/css" href="./css/post.css">
</head>

<body>
	<?php include 'menu.php' ?>
	<center>
	<span style="text-decoration: underline;">
		<h2 class="name-galery-txt">WALL OF FAME</h2>
	</span>
	<center>
	<div class="galery">
		<article class="galery-flex-container" style="margin-bottom: 5px;">
			<?php
			$liste = $db->query("SELECT `Image`.image_path, `Image`.id as id_image, `Image`.`like` FROM `Image` ORDER BY `id`");
			$liste = $liste->fetchALL(PDO::FETCH_ASSOC);
			$liste = array_reverse($liste);
			$count = 10;
			$div = 0;
			foreach ($liste as $donnees) {
				if ($div % 3 == 0)
					echo '<div class="column-galery">';
				?>
				<div class="galery-img-container-margin<?php if ($div % 3 == 2) echo '_last'?>">
					<a onclick="modal_onclick(<?= $count ?>)" href="#">
						<div id="picid<?php echo $count?>" style="display: none;" class="galery-img-container">
							<img class="img-in-galery"  src="<?php echo $donnees['image_path'] ?>">
							<div class="overlay">
								<div class="text"><img class="jaimee" src="./ressources/img/jaime.png"><p id="jaime_galery<?=$count?>"><?= number_format_short($donnees['like'])?></p>
								</div>
							</div>
						</div>
					</a>
				</div>
				<!-- The Modal Images -->
				<div id="modal<?= $count ?>" class="modal">
					<div class="modal-image-post">
						<?php include "./post.php" ?>
					</div>
				</div>
			<!-- End of Modal -->
			<?php
				$count++;
				if ($div % 3 == 2)
					echo "</div>";
				$div++;
			}
			while ($div % 3 != 0)
			{
			?>
				<div class="galery-img-container-margin<?php if ($div % 3 == 2) echo '_last'?>">
					<div class="galery-img-container">
					</div>
				</div>
			<?php
				$div++;
			}?>
	
		</article>
				<div id="loader">
				<img src="https://scrollmagic.io/assets/img/example_loading.gif">
				LOADING...
			</div>
			<div onclick="add_displayed_img(9, <?=$image_total?>)" id="clicktoloadmore">
				Click to load more...
			</div>
	</div>

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


<script>

//id of first image;
var start_displayed = 10;
var display = 0;

//number to display
var NbDisplayed = 9;


$.ajax({
	type: "POST",
	url: '../phpfunctions/GetTotalImage.php',
	data: {},
	success: function(html) {
		 total_img = html;
		 if (total_img == 0)
			document.getElementById("clicktoloadmore").style.display = "none";
		//mise en place du scroll event

		$(window).scroll(function() {;
		if (display < total_img)
		{
			if ($(window).scrollTop() + window.innerHeight == $(document).height()) {
				if (!$("#loader").hasClass("activee")) {
					document.getElementById("clicktoloadmore").style.display = "none";
					$("#loader").addClass("activee");
					setTimeout(add_displayed_img, 1000, NbDisplayed, total_img);
				}
			}
		}
		});
		if (!$("#loader").hasClass("activee") && display < total_img && total_img != 0) {
					document.getElementById("clicktoloadmore").style.display = "none";
					$("#loader").addClass("activee");
					setTimeout(add_displayed_img, 1000, NbDisplayed, total_img);
		}
		//fin scroll event
	}
});

function add_displayed_img(NbDisplayed, total_img)
{
	let save_start_displayed = start_displayed;
	while(start_displayed < save_start_displayed + NbDisplayed)
	{
		let elem = document.getElementById("picid" + start_displayed);
		elem.style.display = "block";
		start_displayed++;
		display++;
		if (display == total_img)
		{
			break ;
		}
	}
	$("#loader").removeClass("activee");
	document.getElementById("clicktoloadmore").style.display = "block";
	if (display == total_img)
	{
		document.getElementById("clicktoloadmore").style.display = "none";
	}
}

</script>

<script src="./script/modal.js"></script>
<script src="./script/ajax.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
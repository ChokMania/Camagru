<!DOCTYPE html>
<html>
<head>
<title>The page you were looking for doesn't exist (404)</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<style>

body {
	color: grey;
	text-align: center;
	font-family: arial, sans-serif;
	margin: 0;
	background-color: grey;
}


.logo {
	position: absolute;
	left: 1%;
	top: 2%;
	width: 5%;
	height: auto;
}

.title {
	position: relative;
	margin: 60px auto;
	filter: grayscale(1);
	-webkit-filter: grayscale(1);
}

.main-image {
	position: absolute;
	top: 25px;
	background-color: rgba(0, 0, 0, 0.5);
	bottom: 35px;
	left: 82px;
	margin: auto;
	right: 82px;
	background-repeat: no-repeat;
	background-position: 50% 50%;
	background-size: auto 85%;
}

.screen {
	position: relative;
	width: 700px;
	margin: auto;
}

h1, h2 {
	font-weight: normal;
	margin: 50px auto;
	color: white;
}
</style>
</head>

<body>
<div class="title"><img src="ressources/img/404.png" alt=""></div>
<div class="screen">
	<div class="main-image">
	</div>
	<img src="ressources/img/macbook.png" alt="">
</div>
<h1>Seems like your page doesn't exist anymore !</h1>
<script>
	$(document).ready(function() {
	var image = "ressources/img/gif/" + (Math.floor(Math.random() * 100 % 8) + 1) + "-sorry.gif";
	$(".main-image").css("background-image", "url(" + image + ")");
	});
</script>
</body>
</html>
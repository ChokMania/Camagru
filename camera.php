<?php
require './phpfunctions/save_to_galery.php';
?>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" />
	<link rel="stylesheet" type="text/css" href="./css/camera.css">
</head>
<body>
	<div class="parent_div_container_3">
		<!-- left part of modal-->
		<div class="child-camera-1">
		<center>
			<div class="camera-div" ondrop="drop(event)" ondragover="allowDrop(event)">
					<img style="display:none ;" id="img-preview-in-camera">
					<video class="vid-class" id="vid-show" autoplay></video>
			</div>
				<p>
					<button class="btn-camera" onclick="startWebcam();">Start WebCam</button>
					<button class="btn-camera" onclick="stopWebcam();">Stop WebCam</button> 
					<button id="vid-take" class="btn-camera" value="Take Photo" disabled="true" >Take a Picture</button>
					<br>
					<a href="upload_an_image.php">Si vous n'avez pas de caméra, cliquez ici</a>
				</p>
			</center>
			<div id="vid-canvas">
			</div>
		</div>
		<!-- right part of modal-->
		<div class="child-camera-2">
		<center>
			<?php
				$filter = -2;
				$d = dir("./ressources/filters/.");
				while (false !== ($entry = $d->read())) {
					if (++$filter > 0) {
			?>
			<img id="<?php $result = "f" . $filter;
				echo $result ?>" class="filter-img" onclick="onclickfilter(this.id)" href="#" draggable="true" ondragstart="drag(event)" src="./ressources/filters/<?=$entry?>" alt="filter">
			<?php
					}
				}
				$d->close();
			?>
			<div style="margin-bottom: 5px;" class="screenshot-div">
				<?php if (isset($_SESSION['img']) && file_exists($_SESSION['img'])) {
					echo '<img id="img-screenshot" src="' . $_SESSION['img'] . '">';}
				else
					echo '<img id="img-screenshot" src="./ressources/img/blank.png">';?>
			</div>
			<button onclick="myAjaxSendToGalery()" id="save-screenshot" class="btn-camera" value="save Picture">Save to galery</button>
		</div>
	</center>
	</div>
</body>
<script src="./script/upload.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="./script/ajax.js"></script>

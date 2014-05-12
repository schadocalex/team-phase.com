<?php
	$url = '../../';
	include($url."include.php");
	include($url."header.php");

	$pictures = Select::all('picture');
	$videos = Select::all('video');
?>
<div id="medias" >
	<div class="bg5" >
		<h2>PICTURES</h2>
	<!--
		<img src="include/img/medias/picture.png" style="background:url('include/img/gallery/DSC06494.JPG');
		" />
	-->
		<?php foreach($pictures as $picture) { ?>
			<a href="#picture_<?= $picture['id'] ?>"
				class="fancybox link_miniature" >
				<img style="background:url('<?= srcImgMin($picture['image_id']) ?>') center center;" 
				class="miniature" src="include/img/medias/picture_w.png"/>
			</a>
			<div id="picture_<?= $picture['id'] ?>" class="popup" ><img class="img_popup"
				src="<?= srcImg($picture['image_id']) ?>" /></div>
		<?php } ?>
	</div>
	<div class="bg6" >
		<h2>VIDEOS</h2>
	<!--
		<img src="include/img/medias/picture.png" style="background:url('include/img/gallery/DSC06494.JPG');
		" />
	-->
		<?php foreach($videos as $video) { ?>
			<a href="#video<?= $video['id'] ?>" class="fancybox link_miniature" >
				<img style="background:url('<?= srcImgYoutube($video['id_youtube']) ?>') center center;" 
				class="miniature" src="include/img/medias/video_w.png"/>
			</a>
			<div id="video<?= $video['id'] ?>" class="popup" >
				<iframe width="853" height="480" src="//www.youtube.com/embed/<?= $video['id_youtube'] ?>" frameborder="0" allowfullscreen></iframe>
			</div>
		<?php } ?>
	</div>
</div>
<?php
	include($url."footer.php");
?>
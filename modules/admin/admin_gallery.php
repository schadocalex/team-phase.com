<?php
	$url = '../../';
	include($url."include.php");

	$user->accessRight('ADMIN_MEDIAS');

	$pictures = Select::all('picture');
	$videos = Select::all('video');

	include($url."header.php");
	include("menu.php");
?>

<div id="admin_gallery" >
	<?php showMessages(true); ?>
	<div class="admin bg4" >
		<h2>PICTURES</h2>
		<a href="#add_new_picture" class="fancybox" >Add picture</a><br /><br />
		<table>
			<?php foreach($pictures as $picture) { ?>
				<tr style="width:214px;float:left;" >
					<td class="td_admin_gallery" >
						<img src="<?= srcImgMin($picture['image_id']) ?>" width="64" height="64" />
					</td>
					<td class="td_admin_gallery" >
						<?= $picture['date'] ?>
					</td>
					<td class="icon" >
						<a href="Admin-Gallery-Edit-Picture-<?= $picture['id'] ?>" >
							<img src="include/img/icon/edit.gif" alt="Edit" />
						</a>
					</td>
					<td class="icon" >
						<a href="Admin-Gallery-Delete-Picture-<?= $picture['id'] ?>" >
							<img src="include/img/icon/delete.png" alt="Edit" />
						</a>
					</td>
				</tr>
			<?php } ?>
		</table>
		<br />
		<a href="#add_new_picture" class="fancybox" >Add picture</a>
		<div id="add_new_picture" class="popup" >
			<?php include('gallery/add_picture_embedded.php'); ?>
		</div>
	</div>
	<div class="admin bg4" >
		<h2>VIDEOS</h2>
		<a href="#add_new_video" class="fancybox" >Add video</a><br /><br />
		<table>
			<?php foreach($videos as $video) { ?>
				<tr style="width:214px;float:left;" >
					<td class="td_admin_gallery" >
						<img src="<?= srcImgYoutube($video['id_youtube']) ?>" width="64" height="64" />
					</td>
					<td class="td_admin_gallery" >
						<?= $video['date'] ?>
					</td>
					<td class="icon" >
						<a href="Admin-Gallery-Edit-Video-<?= $video['id'] ?>" >
							<img src="include/img/icon/edit.gif" alt="Edit" />
						</a>
					</td>
					<td class="icon" >
						<a href="Admin-Gallery-Delete-Video-<?= $video['id'] ?>" >
							<img src="include/img/icon/delete.png" alt="Edit" />
						</a>
					</td>
				</tr>
			<?php } ?>
		</table>
		<br />
		<a href="#add_new_video" class="fancybox" >Add video</a>
		<div id="add_new_video" class="popup" >
			<?php include('gallery/add_video_embedded.php'); ?>
		</div>
	</div>
</div>
<?php
	include($url."footer.php");
?>
<?php
	$url = '../../';
	include($url."include.php");

	$user->accessRight('ADMIN_NEWS');

	$newses = Select::all('news');

	include($url."header.php");
	include("menu.php");
?>

<div id="admin_menu" >
	<?php showMessages(true); ?>
	<div class="admin bg4" >
		<h2>NEWS</h2>
		<a href="#add_new_news" class="fancybox" >Add news</a><br /><br />
		<table>
			<?php foreach($newses as $news) { ?>
				<tr>
					<td class="td_admin_news" >
						<img src="<?= srcImg($news['image_id']) ?>" height="64" class="news_image" style="position:relative;left:1px;top:1px;">
					</td>
					<td class="td_admin_news" ><?= $news['title'] ?></td>
					<td class="td_admin_news" ><?= $news['author'] ?></td>
					<td class="td_admin_news" ><?= $news['date'] ?></td>
					<td class="icon" >
						<a href="Admin-News-Edit-News-<?= $news['id'] ?>" >
							<img src="include/img/icon/edit.gif" alt="Edit" />
						</a>
					</td>
					<td class="icon" >
						<a href="Admin-News-Delete-News-<?= $news['id'] ?>" >
							<img src="include/img/icon/delete.png" alt="Edit" />
						</a>
					</td>
				</tr>
			<?php } ?>
		</table>
		<br />
		<a href="#add_new_news" class="fancybox" >Add news</a>
		<div id="add_new_news" class="popup" >
			<?php include('news/add_news_embedded.php'); ?>
		</div>
	</div>
</div>
<?php
	include($url."footer.php");
?>
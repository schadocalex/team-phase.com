<?php
	$url = '../../';
	include($url."include.php");
	
	$i = 0;
	$newses = Select::all('news');
	$matches = MySQL::selectAll('match2', 1, 6);
	$competitions = Select::all('competition');
	$opponents = Select::all('opponent');
	$games = Select::all('game');
	$comments = MySQL::selectAll('comment', 1, 3);
	$pictures = MySQL::selectAll('picture', 1, 3);
	$videos = MySQL::selectAll('video', 1, 1);
	$upcoming_match = MySQL::selectLast('upcoming_match');

	foreach($newses as $news)
	{
		$i++;
		${'title_' . $i} = $news['title'];
		${'author_' . $i} = $news['author'];
		${'image_id_' . $i} = $news['image_id'];
		${'news_url_' . $i} = 'News-'.$news['id'].'#'.getCanonical($news['title']);

		if($i == 3)
			break;
	}
	include($url."header.php");
?>
		<div id="slider" >
			<div class="slider left" >
			<?php
			echo '
				<a href="' . $news_url_1. '" ><img id="slider_img_1" class="img_slider" src="'.srcImg($image_id_1).'" /></a>
				<a href="' . $news_url_2. '" ><img id="slider_img_2" class="img_slider" style="display:none;" src="'.srcImg($image_id_2).'" /></a>
				<a href="' . $news_url_3. '" ><img id="slider_img_3" class="img_slider" style="display:none;" src="'.srcImg($image_id_3).'" /></a>';
				
				?>
				<a onclick="slider_left_arrow();" ><img class="left_arrow" src="include/img/slider/arrow_left.png" /></a>
				<a onclick="slider_right_arrow();" ><img class="right_arrow" src="include/img/slider/arrow_right.png" /></a>
				<div class="title_slider">
				<?php
				echo '
					<div id="title_slider_1" >
						<a href="' . $news_url_1. '" ><h3>' . $title_1. '</h3></a>
					</div>
					<div id="title_slider_2" style="display:none;" >
						<a href="' . $news_url_2. '" ><h3>' . $title_2. '</h3></a>
					</div>
					<div id="title_slider_3" style="display:none;" >
						<a href="' . $news_url_3. '" ><h3>' . $title_3. '</h3></a>
					</div>	';

				?>					
				</div>
				<div class="buttons_select" >
					<a onclick="slider_select(1);" >
						<img id="slider_button_on_1" src="include/img/slider/buttons_select_on.png" />
						<img id="slider_button_off_1" style="display:none;" src="include/img/slider/buttons_select.png" />
					</a>
					<a onclick="slider_select(2);" >
						<img id="slider_button_on_2" style="display:none;" src="include/img/slider/buttons_select_on.png" />
						<img id="slider_button_off_2" src="include/img/slider/buttons_select.png" />
					</a>
					<a onclick="slider_select(3);" >
						<img id="slider_button_on_3" style="display:none;" src="include/img/slider/buttons_select_on.png" />
						<img id="slider_button_off_3" src="include/img/slider/buttons_select.png" />
					</a>
				</div>
			</div>
			<div class="slider2 left" >
			<?php
			echo '
				<a onclick="slider_select(1);" class="img_slider2" ><img id="slider_img2_1" width="345" src="'.srcImg($image_id_1).'" /></a>
				<a onclick="slider_select(2);" class="img_slider2" ><img id="slider_img2_2" width="345" src="'.srcImg($image_id_2).'" /></a>
				<a onclick="slider_select(3);" class="img_slider2" ><img id="slider_img2_3" width="345" src="'.srcImg($image_id_3).'" /></a>';
			?>
			</div>
		</div>
			<div id="div_last_matches" >
				<div id="upcoming_match" class="bg7" >
					<h2>UPCOMING MATCH</h2>
					<img class="left team team_phase" src="<?= srcImg($upcoming_match['image_phase_id']) ?>" />
					<img class="right team team_vs" src="<?= srcImg($upcoming_match['image_opponent_id']) ?>" />
					<img class="versus" src="include/img/upcoming/versus.png" />
					<div style="clear:both;" ></div>
					<p class="left" ><?= $upcoming_match['name_phase'] ?></p>
					<p class="right" ><?= $upcoming_match['name_opponent'] ?></p>
					<div style="clear:both;" ></div>
					<h6 class="date_upcoming" ><?= DateTime::createFromFormat('Y-m-d', $upcoming_match['date'])->format('l j M Y'); ?></h6>
					<p class="matchlink_upcoming" ><a href="<?= $upcoming_match['matchlink'] ?>" target="_blank" >matchlink</a></p>
				</div>
				<div id="recent_matches" class="bg8" >
					<h2><a href="Results" >RECENT MATCHES</a></h2>
					<?php $first = true; foreach($matches as $match) { ?>
						<?php
							if($first) $first = false; else echo '<hr />';
						?>
						<p class="last_match" >
							<a href="<?= $match['matchlink'] ?>" target="_blank" >
							<span class="right">
								<?php
									if($match['score_phase'] < $match['score_opponent'])
										echo '<img class="icon_score" src="include/img/icon/lost.png" />';
									else if($match['score_phase'] == $match['score_opponent'])
										echo '<img class="icon_score" src="include/img/icon/draw.png" />';
									else
										echo '<img class="icon_score" src="include/img/icon/win.png" />';
								?>
							</span>
							<span class="game" ><?= dispImg($games[$match['game_id']]['icon_id']) ?></span>
							<span class="date" ><?= DateTime::createFromFormat('Y-m-d', $match['date'])->format('d/m/Y') ?></span>
							<span class="versius" >
								phase <img id="img_vs" src="include/img/icon/vs.png" /> <!-- <em>vs</em> -->
								<img style="margin-right:10px;" src="<?= srcFlag($opponents[$match['opponent_id']]['flag_id']) ?>">
								<?= $opponents[$match['opponent_id']]['name'] ?>
							</span>
						</a></p>
					<?php } ?>
				</div>
				<div style="clear:both;" ></div>
			</div>
<!--
			<div id="latest_video" class="bg7" >
				<h2><a href="Gallery" >LATEST VIDEO</a></h2>
<?php /*
					foreach($videos as $video) { ?>
					<a href="#video<?= $video['id'] ?>" class="fancybox link_miniature" rel="videos" title="<?= @$video['title'] ?>">
						<img style="background:url('<?= srcImgYoutube($video['id_youtube']) ?>') center center;" 
						class="miniature" src="include/img/medias/video_w.png"/>
					</a>
					<div id="video<?= $video['id'] ?>" class="popup" >
						<iframe width="853" height="480" src="//www.youtube.com/embed/<?= $video['id_youtube'] ?>" frameborder="0" allowfullscreen></iframe>
					</div>
				<?php } ?>
			</div>
			<div id="latest_picture" class="bg8" style="text-align:center;" >
				<h2><a href="Gallery" >LATEST PICTURES</a></h2>
				<?php foreach($pictures as $picture) { ?>
					<a href="#picture_<?= $picture['id'] ?>"
						class="fancybox link_miniature" rel="pictures" title="<?= @$picture['title'] ?>" >
						<img style="background:url('<?= srcImgMin($picture['image_id']) ?>') center center;" 
						class="miniature" src="include/img/medias/picture_w.png"/>
					</a>
					<div id="picture_<?= $picture['id'] ?>" class="popup" ><img class="img_popup"
						src="<?= srcImg($picture['image_id']) ?>" /></div>
				<?php }
*/ ?>
			</div>
-->
			<div id="last_video" class="bg7" >
				<h2><a href="Gallery" >LATEST VIDEO</a></h2>
				<?php foreach($videos as $video) { ?>
					<!--
					<div class="video" >
						<iframe width="300" height="169" src="http://www.youtube.com/embed/<?= $video['id_youtube'] ?>?rel=0" frameborder="0" allowfullscreen></iframe>
					</div>
				-->
					<a href="#video<?= $video['id'] ?>" class="fancybox link_miniature" title="<?= @$video['title'] ?>" ><img class="miniature"
					src="<?= srcImgYoutube($video['id_youtube']) ?>" /></a>
					<div id="video<?= $video['id'] ?>" class="popup popup_gallery" >
						<iframe width="853" height="480" src="//www.youtube.com/embed/<?= $video['id_youtube'] ?>" frameborder="0" allowfullscreen></iframe>
					</div>
				<?php } ?>
			</div>
			<div id="home_gallery" class="bg8" style="text-align:center;" >
				<h2><a href="Gallery" >LATEST PICTURES</a></h2>
				<?php foreach($pictures as $picture) { ?>
				<a href="#picture_<?= $picture['id'] ?>" class="fancybox link_miniature" rel="pictures_home" title="<?= @$picture['title'] ?>" ><img class="miniature"
					src="<?= srcImgMin($picture['image_id']) ?>" /></a>
				<div id="picture_<?= $picture['id'] ?>" class="popup popup_gallery" ><img class="img_popup"
					src="<?= srcImg($picture['image_id']) ?>" /></div>
				<?php } ?>
			</div>
			<div id="about_us" class="bg8" >
				<h2>ABOUT US</h2>
				<p style="text-align: center;" >
					TEAM-PHASE was founded in May 2013 by Côme "aacid" M & Pierre-Antoine "drkje" Dubard.
					The organisation is primarily focused on supporting top FPS teams competing both online and offline at LAN events across Europe.
					Our goal is to turn TEAM-PHASE into a household gaming brand that is recognized throughout the world.
					If you are interested in joining TEAM-PHASE:<br>
					<a href="mailto:contact@team-phase.com" >contact@team-phase.com</a>
				</p>
			</div>
			<div id="latest_comment" class="bg7"  >
				<h2>LATEST COMMENTS</h2>
				<?php
					$first = true;
					foreach($comments as $comment) {
						if($first) $first = false; else echo '<hr />';

						$title_canonical = getCanonical($newses[$comment['news_id']]['title']);
						$url_news = 'News-'.$comment['news_id'].'#'.$title_canonical;

						$content = strip_tags($comment['content']);
						$lim_char = 35;
						if(strlen($content) > $lim_char)
							$content = substr($content, 0, $lim_char).'...';

						$user_comment = new User($comment['author_id']);
						$date_comment = DateTime::createFromFormat('Y-m-d H:i:s', $comment['date']);

						echo '<a href="'.$url_news.'" class="comment_author" >
									« <em>' . parse($content) .'</em> »<br />
									<span>by ' . dispFlag($user_comment->country_id) . ' ' . $user_comment->username . ' - 
									' . $date_comment->format('d/m/Y \a\t H:i') . '</span>
							</a>';
					}
				?>
			</div>
			<div style="clear:both;" ></div>
			
<?php
	include($url."footer.php");
?>
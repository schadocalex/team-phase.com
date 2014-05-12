<?php
	$url = '../../';
	include($url."include.php");
	
	$i = 0;
	$newses = Select::all('news');
	$matches = MySQL::selectAll('match2', 1, 5);
	$competitions = Select::all('competition');
	$opponents = Select::all('opponent');
	$games = Select::all('game');
	$comments = MySQL::selectAll('comment', 1, 3);
	$pictures = MySQL::selectAll('picture', 1, 3);
	$videos = MySQL::selectAll('video', 1, 1);

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
				<div id="upcoming_match" >
					<h2>UPCOMING MATCH</h2>
					<img class="left" src="include/img/logo_square.png" />
					<img class="right" src="include/img/logo_square_defaut.png" />
					<!-- <img class="right" src="http://snsejsave.info/raw.png" /> -->
					<div style="clear:both;" ></div>
					<p class="left team_phase" >phase</p>
					<p class="right team_vs" ></p>
				</div>
				<div id="last_matches" class="right" >
					<h2><a href="Results" >RECENT MATCHES</a></h2>
					<?php $first = true; foreach($matches as $match) { ?>
						<?php
							if($first) $first = false; else echo '<hr />';

							/*
							$color = ($match['score_phase'] > $match['score_opponent'])? 'win' : 'defeat';
							$color = ($match['score_phase'] == $match['score_opponent'])? 'draw' : $color;
							*/
						?>
						<p class="last_match" >
							<a href="<?= $match['matchlink'] ?>" target="_blank" >
							<!-- <span class="score score_<?= $color ?>">
								<?php /*
									if($match['score_phase'] < 0 OR $match['score_opponent'] < 0)
										echo 'Forfeit';
									else
										echo $match['score_phase'].' - '.$match['score_opponent'];
										*/
								?>
							</span>
						-->
							<span class="">
								<?php
									if($match['score_phase'] <= $match['score_opponent'])
										echo '<img class="icon_score" src="include/img/icon/lost.png" />';
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
			<div id="last_video" >
				<h2><a href="Medias" >LATEST VIDEO</a></h2>
				<?php foreach($videos as $video) { ?>
					<div class="video" >
						<iframe width="380" height="214" src="http://www.youtube.com/embed/<?= $video['id_youtube'] ?>?rel=0" frameborder="0" allowfullscreen></iframe>
					</div>
				<?php } ?>
			</div>
			<div id="home_gallery" style="text-align:center;" >
				<h2><a href="Medias" >LATEST PICTURES</a></h2>
				<?php foreach($pictures as $picture) { ?>
				<a href="#picture_<?= $picture['id'] ?>" class="fancybox link_miniature" ><img class="miniature"
					src="<?= srcImgMin($picture['image_id']) ?>" /></a>
				<div id="picture_<?= $picture['id'] ?>" class="popup" ><img class="img_popup"
					src="<?= srcImg($picture['image_id']) ?>" /></div>
				<?php } ?>
			</div>
			<div id="about_us" >
				<h2>ABOUT US</h2>
				<p style="text-align: center;" >
					TEAM-PHASE was founded in May 2013 by two close friends:<br /> Côme "aacid" M & Pierre-Antoine "drkje" Dubard.<br />
					Back to gaming after a long break, we started out with Call of Duty 4 and Wolfenstein: Enemy Territory
					and now support teams in Return to Castle Wolfenstein and Shootmania Storm also. Our goal is to have a
					stable and serious organization with active FPS teams competing in cups and ladders on CyberGamer as
					well as other leagues.
				</p>
			</div>
			<div id="latest_comment" >
				<h2>LATEST COMMENTS</h2>
				<?php
					$first = true;
					foreach($comments as $comment) {
						if($first) $first = false; else echo '<hr />';

						$title_canonical = getCanonical($newses[$comment['news_id']]['title']);
						$url_news = 'News-'.$comment['news_id'].'#'.$title_canonical;

						$content = $comment['content'];
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
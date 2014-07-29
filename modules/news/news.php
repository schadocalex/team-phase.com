<?php
	$url = '../../';
	include($url."include.php");

	$news_id = @$_GET['id'];
	
	if(isset($_POST['jeton']) AND $user->is('MEMBER'))
	{
		if($user->is('MEMBER'))
		{
			$requete_comment = $bdd->prepare('SELECT * FROM comment WHERE author_id = ? AND date > DATE_SUB(NOW(), INTERVAL 10 SECOND)');
			$requete_comment->bindValue(1, $user->id, PDO::PARAM_STR);
			$requete_comment->execute();
			$can_comment = $requete_comment->rowCount() == 0;
		}
		else
			$can_comment = false;

		if(!$can_comment)
			$form->error('Please wait few seconds between two comments.');
		else
		{
			$form->verify_jeton($_POST['jeton']);
			$content = htmlentities(@$_POST['comment']);

			if(empty($content))
				$form->error('You must add some text to the comment.');
			if(!MySQL::exist('news', 'id', $news_id))
				$form->error("The news with id $news_id doesn't exist.");

			if($form->error == '')
			{
				$insert = new Insert('comment');
				$insert->news_id = $news_id;
				$insert->author_id = $user->id;
				$insert->content = $content;
				$insert->date = date("Y-m-d H:i:s");
				$insert->execute();
				$_SESSION['success'] = 'Your comment has been sent.';
				$_POST['comment'] = '';
			}
		}
	}
	$newses = Select::all('news');

	if(!empty($news_id)) 
	{
		$news_to = @$newses[$news_id];
		if(!empty($news_to))
		{
			if(!empty($_GET['share']))
			{
				$title_canonical = getCanonical($news_to['title']);
				$url_news = 'News-'.$news_to['id'].'#'.$title_canonical;
				$user->redirect($url_news);
			}
			else
			{
				$fb_share_meta_tags = '
				<meta property="og:url" content="http://www.team-phase.com/Share-News-'.$news_to['id'].'"/>
				<meta property="og:title" content="'.$news_to['title'].'"/>
				<meta property="og:description" content="'.htmlentities($news_to['content'], ENT_QUOTES).'"/>
				<meta property="og:image" content="http://www.team-phase.com/'.srcImg($news_to['image_id']).'"/>
				';
			}
		}
	}

	include($url."header.php");
?>
<div id="list_news" >
	<?php
	
	//showMessages(true);
	
	$i = 0;

	foreach($newses as $news)
	{
		if(!empty($news_id))
			$news_commented = ($news['id'] == $news_id);
		else
			$news_commented = false;

		$title_canonical = getCanonical($news['title']);
		$url_news = 'News-'.$news['id'].'#'.$title_canonical;

		$comments = MySQL::selectAll('comment', 'news_id = '.$news['id']);
		$id_comments = 'comments_'.id(6);
		$i++;
		$align = ($i%2) ? 'left' : 'right';

		echo '
			<div class="news bg4" id="'.$title_canonical.'" >
			<h2><a href="'.$url_news.'" >' . $news['title'] . '</a></h2>
			<img class="img_news img_news_' . $align . '" src="'.srcImg($news['image_id']).'" />
			<p>' . parse($news['content']) . '</p>

			<div class="share_buttons" >
				<div class="fb-share-button"
					data-href="http://www.team-phase.com/Share-News-'.$news['id'].'"
					data-type="button"
				></div>';
		?>
				<a href="https://twitter.com/share" class="twitter-share-button" data-count="none" data-url="http://www.team-phase.com/Share-News-<?= $news['id'] ?>" data-text="<?= $news['title'] ?>" data-hashtags="teamphase" data-dnt="true">Tweet</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
		<?php echo '	
			</div>
			<div class="news_red_bar" onclick="$(\'#'.$id_comments.'\').toggle();" >
				<p class="right" ><img src="include/img/icon/com.png" /><strong>'.count($comments).'</strong> comment(s)</p>
				<p class="left" ><img src="include/img/icon/write.png" />by <strong>' . $news['author'] . '</strong>
				- ' . DateTime::createFromFormat('Y-m-d H:i:s', $news['date'])->format('D\, j M Y') . '</p>
			</div>
			<div id="'.$id_comments.'" class="news_comments'.($news_commented?'':' hidden').'" >';
			if($news_commented)
				showMessages();
			if($user->is('MEMBER'))
			{
				$form->initialize('comment', 'width:520px;margin:auto;', $url_news);
				$form->textarea('comment', 'Add a comment:', '', 60, 5);
				$form->hidden('id', $news['id']);
				$form->end('Send');
			}
			else
			{
				if($user->is('MEMBER'))
					echo 'Please wait one minute between two comments.<br /><br />';
				else
					echo 'You must be online to comment!<br /><br />';
			}

			foreach($comments as $comment)
			{
				$user_comment = new User($comment['author_id']);
				$date_comment = DateTime::createFromFormat('Y-m-d H:i:s', $comment['date']);
				echo '
					<p class="comment_author" >
						' . $user_comment->username() . ' - 
					' . $date_comment->format('d/m/Y \a\t H:i') . '
					</p>
					<hr class="comment_hr" />
					' . parse($comment['content']) .'
					<br /><br />';
			}
			echo'</div>
		</div>';
	}


	?>
</div>
<?php
	include($url."footer.php");
?>
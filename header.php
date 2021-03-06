<?php
	include_once('include.php');
	
	$users = Select::all('user');
	$user_registered = '<ul>';
	foreach($users as $u)
	{
		$user_registered .=
		'<li>
			'.getUsername($u).'
		</li>';
	}
	
	$user_registered .= '</ul>';

	$users_online = $bdd->query('SELECT * FROM user WHERE last_visit > DATE_SUB(NOW(), INTERVAL 5 MINUTE)');
	$user_online = '<ul>';
	foreach($users_online as $u)
	{
		$user_online .=
		'<li>
			'.getUsername($u).'
		</li>';
	}
	$user_online .= '</ul>';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>TEAM-PHASE - Electronic Sports Team</title>

        <link rel="stylesheet" href="include/css/style.css?d=<?= time() ?>" type="text/css" />
		<link rel="stylesheet" href="include/css/jquery.fancybox.css" type="text/css" />
		<link href='http://fonts.googleapis.com/css?family=Oswald:300,400' rel='stylesheet' type='text/css'>
        <!-- <link rel="icon" type="image/x-icon" href="favicon.ico" /> -->
        <script type="text/javascript" src="include/js/jquery.min.js" ></script>
		<script type="text/javascript" src="include/js/functions.js" ></script>
		<script type="text/javascript" src="include/js/slider.js" ></script>
		<script type="text/javascript" src="include/js/jquery.fancybox.js" ></script>
		<script type="text/javascript" src="include/js/ckeditor/ckeditor.js" ></script>

		<?= @$fb_share_meta_tags ?>
	</head>
    <body>

    	<div id="fb-root"></div>
		<script>
			(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_EN/sdk.js#xfbml=1&version=v2.0";
			fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
		</script>
		
		<div id="body" >
				<div id="header" >
					<div id="header_bartop" class="bg1" >
						<p id="header_bartop_left" >
							<a href="Admin" >
								<img style="position:relative;top:3px;right:3px;" src="include/img/bar_top/cle.png" >
							</a>
							<a href="#popup_stats" class="fancybox" >
								<img id="icon_stats" src="include/img/bar_top/stats.png" />
							</a>
							<?php number_connected(); ?> <span>online -</span>
							<?php number_registered(); ?> <span>registered</span>
						</p>
						<!--<p id="header_bartop_middle" >
							<marquee width="285"> <span style="color:#e11d1d;" >/</span>!<span style="color:#e11d1d;" >\</span> DEATHADDER contest : check your profile to get your number! <span style="color:#e11d1d;" >/</span>!<span style="color:#e11d1d;" >\</span> </marquee>
						</p-->
						<p id="header_bartop_right" >
							<?php if($user->is('MEMBER')) {
								if(empty($_SESSION['jeton_logout']))
									$_SESSION['jeton_logout'] = id(8);
							?>
								<span>Welcome </span>
								<?php echo $user->username(); ?> <a href="#popup_edit_profile" class="fancybox" >[edit]</a>
								<?php /* if($user->is('SUPER_ADMIN')) { ?> <a href="#popup_team" class="fancybox" >[team]</a> <?php } */ ?>
								<a href="Logout-<?= $_SESSION['jeton_logout']; ?>" ><img class="lien_btn" src="include/img/icon/logout.png" /></a>
							<?php } else { ?>
								<a href="#popup_login" class="fancybox" ><img class="lien_btn" src="include/img/icon/login.png" /></a>
								<!-- <span>or</span> -->
								<a href="#popup_register" class="fancybox" ><img class="lien_btn" src="include/img/icon/register.png" /></a>
							<?php } ?>
						</p>
					</div>
					<div id="header_space" >
						<a href="Home" ><img id="header_logo" src="include/img/logo.png" alt="logo" /></a>
						<div id="header_sponsors2" >
							<!--<a href="http://www.ycn-hosting.com/" ><img class="sponsors_img_ycn sponsors_img" src="include/img/sponsors/ycn.png" /></a>-->
							<a href="http://www.fragnet.net/" ><img class="sponsors_img_fg sponsors_img" src="include/img/sponsors/fragnet_w.png" /></a>
							<a href="http://fragwise.eu/" ><img class="sponsors_img_fw sponsors_img" src="include/img/sponsors/fragwise_w.png" /></a>
							<!-- <a href="http://fshost.me/" ><img class="sponsors_img_fg sponsors_img" src="include/img/sponsors/fshost_w.png" /></a> -->
						</div>
					</div>
				</div>
				<div id="menu" class="bg1" >
					<a href="Home" id="img_navigation" ></a>
					<a href="News" >NEWS</a>
					<a href="Teams" >TEAMS</a>
					<a href="Results" >RESULTS</a>
					<a href="Gallery" >GALLERY</a>
					<a href="Tournament" >TOURNAMENT</a>
					<!-- <a href="Phasetv" >PHASETV</a> -->
					<a href="Sponsors" >SPONSORS</a>
					<?php if($user->is('SUPER_ADMIN')) { ?>
					<?php } ?>
					<a href="http://www.youtube.com/channel/UCVv-kVQjK0M8fVVCFfymRmQ" target="_blank" class="shake lien_reseau_social" ><img src="include/img/yt.png" /></a>
					<a href="https://twitter.com/theteamphase" target="_blank" class="shake lien_reseau_social" ><img src="include/img/tw.png" /></a>
					<!-- <a href="#" class="shake lien_reseau_social" ><img src="include/img/fb.png" /></a> -->
				</div>
				<div class="red_bar" ></div>
				<div id="content_body" class="bg2" >

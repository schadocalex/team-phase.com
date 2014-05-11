<?php
	include_once('include.php');
	
	$users = Select::all('user');
	$user_registered = '<ul>';
	foreach($users as $u)
	{
		$user_registered .=
		'<li>
			<a href="Profile-'.$u['id'].'-'.getCanonical($u['username']).'">
				' . dispFlag($u['country_id']) . ' '. $u['username'] . '
			</a>
		</li>';
	}
	
	$user_registered .= '</ul>';

	$users = $bdd->query('SELECT * FROM user WHERE last_visit > DATE_SUB(NOW(), INTERVAL 5 MINUTE)');
	$user_online = '<ul>';
	foreach($users as $u)
	{
		$user_online .=
		'<li>
			<a href="Profile-'.$u['id'].'-'.getCanonical($u['username']).'">
				' . dispFlag($u['country_id']) . ' '. $u['username'] . '
			</a>
		</li>';
	}
	$user_online .= '</ul>';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>TEAM-PHASE - Electronic Sport Team</title>

        <link rel="stylesheet" href="include/css/style.css?d=<?php echo date('dm'); ?>" type="text/css" />
		<link rel="stylesheet" href="include/css/jquery.fancybox.css" type="text/css" />
        <!-- <link rel="icon" type="image/x-icon" href="favicon.ico" /> -->
        <script type="text/javascript" src="include/js/jquery.min.js" ></script>
		<script type="text/javascript" src="include/js/functions.js" ></script>
		<script type="text/javascript" src="include/js/slider.js" ></script>
		<script type="text/javascript" src="include/js/jquery.fancybox.js" ></script>
    </head>
    <body>
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
							<?php if($user->is('MEMBER')) { // MEMBRE
								if(empty($_SESSION['jeton_logout']))
									$_SESSION['jeton_logout'] = id(8);
							?>
								<span>Welcome </span>
								<?php echo $user->username(); ?> <a href="#popup_edit_profile" class="fancybox" >[edit]</a>
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
							<a href="http://fshost.me/" ><img class="sponsors_img_fg sponsors_img" src="include/img/sponsors/fshost_w.png" /></a>
						</div>
					</div>
				</div>
				<div id="menu" class="bg1" >
					<a href="Home" id="img_navigation" ></a>
					<a href="News" >NEWS</a>
					<a href="Teams" >TEAMS</a>
					<a href="Results" >RESULTS</a>
					<a href="Medias" >MEDIAS</a>
					<a href="Phasetv" >PHASETV</a><em id="status_phastv"> OFF</em>
					<a href="http://fr.twitch.tv/teamphase" class="shake lien_reseau_social" ><img src="include/img/tv.png" /></a>
					<a href="http://www.youtube.com/channel/UCVv-kVQjK0M8fVVCFfymRmQ" target="blank" class="shake lien_reseau_social" ><img src="include/img/yt.png" /></a>
					<a href="#" class="shake lien_reseau_social" ><img src="include/img/fb.png" /></a>
				</div>
				<div class="red_bar" ></div>
				<div id="content_body" class="bg2" >

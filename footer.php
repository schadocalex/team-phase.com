
					<div style="clear:both;" ></div>
				</div>
				<div id="footer_sponsors" class="bg1" >
					<!--<a href="http://www.ycn-hosting.com/" ><img class="sponsors_img_ycn sponsors_img" src="include/img/sponsors/ycn_w.png" /></a>-->
					<a href="http://www.fragnet.net/" ><img class="sponsors_img_fg sponsors_img" src="include/img/sponsors/fragnet_w.png" /></a>
					<a href="http://fragwise.eu/" ><img class="sponsors_img_fw sponsors_img" src="include/img/sponsors/fragwise_w.png" /></a>
					<!-- <a href="http://fshost.me/" ><img class="sponsors_img_fshost sponsors_img" src="include/img/sponsors/fshost_w.png" /></a>-->
				</div>
				
				<div id="footer" class="bg1" >
					<p class="legal" >
						<!-- <img src="include/img/logo_footer.png" /> <br /> -->
						Copyright © 2013 - 2014 <a href="http://www.team-phase.com/" >team-phase.com</a> - All Rights Reserved<br />
						Contact us at <a href="mailto:contact@team-phase.com" >contact@team-phase.com</a> - irc @ QuakeNet <a href="irc://qnet/team-phase" >#team-phase</a>
					<!-- </p>
					<p class="social" > -->
						<br/><br/>
						<a href="http://www.youtube.com/channel/UCVv-kVQjK0M8fVVCFfymRmQ" target="_blank" class="shake lien_reseau_social" ><img src="include/img/yt.png" /></a>
						<a href="https://twitter.com/theteamphase" target="_blank" class="shake lien_reseau_social" ><img src="include/img/tw.png" /></a>
						<!-- <a href="#" class="shake lien_reseau_social" ><img src="include/img/fb.png" /></a> -->
					</p>
					<div style="clear:both;" ></div>
				</div>
		</div>
		<div id="popup_stats" class="popup" >
			<h4>Users online:</h4>
			<?php if($user_online != '<ul></ul>') { ?>
				<?php echo $user_online; ?><br /><br />
			<?php } else { ?>
				Nobody is online.<br /><br />
			<?php } ?>
			<h4>Users registered:</h4>
				<?php echo $user_registered; ?>
		</div>
		<div id="popup_login" class="popup" ><?php include('modules/log/login_embedded.php');?></div>
		<div id="popup_register" class="popup" ><?php include('modules/registration/registration_embedded.php');?></div>
		<div id="popup_edit_profile" class="popup" >
			<?php include('modules/profile/edit_embedded.php');?>
			<a href="Profile-<?= $user->id ?>-<?= getCanonical($user->username) ?>" >
				Go to the profile page
			</a>
		</div>
		<div id="popup_team" class="popup" ><?php include('modules/tournament/team_embedded.php');?></div>
    </body>
</html>
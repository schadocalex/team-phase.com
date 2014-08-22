<?php
	$url = '../../';
	include($url."include.php");

	$user->accessRight('SUPER_ADMIN');

	//$teams = Select::all('teams');
	
	include($url."header.php");
?>
<div id="tournament" >
	
	<div class="bg4" >
		<img src="include/img/tournament/phasethetournament.png" style="border-radius:5px;position:relative;left:-20px;top:-10px;" width="898" />
		<p style="text-align:center;" ><a href="#popup_rules" class="fancybox" >Click here to view rules</a></p>
	</div>
	<div id="popup_rules" class="popup bg9" >
		<h2>RULES!</h2>
		<p><center>
			1) All participants are required to register at www.team-phase.com. A player's nickname on the website needs to match a player's in-game nickname.
		</p>
		<p>
			2) The maximum amount of slots per team is 4. Teams can add a player to their roster whilst the cup is in progress as long as that player hasn't previously played for a different team.
		</p>
		<p>
			3) A team is allowed to use one stand-in if required, the stand-in will however have to be approved by the admin team based on resemblance of skill level in regards to the player the stand-in is replacing.
		</p>
		<p>
			4) Cheating is not allowed. Players are required to record demos of their matches and are not allowed to delete those demos until the end of the cup. An admin can at all times request those demos.
		</p>
		<p>
			5) Weapon boosting is not allowed. A player committing intentional boosting will be banned from playing the next match besides further consequences based on the conditions of the event.
		</p>
		<p>
			6) Allowed player classes : Medic - Lieutenant - Engineer and 1 Soldier with the exclusion of the following weapons: Panzer and Flamethrower
		</p>
		<p>
			7) The games are played in the standard ABBA format. By default teams have to play 2 maps, but in the case of a draw by the end of those 2 maps a match deciding map will be played.
			<br />If teams still draw by the end of the decider map an AB round will be added until one team comes out victorious.
		</p>
		<p>
			8) During the group stage the winning teams will receive 1 point per win. The losing team won't receive any points.
			<br />If by the end of the group stage several teams have the same amount of points their positions will be determined by the results of the games where the teams with the same amount of points played each other. Naturally the winning team will get the higher position.
			<br />The first and second placed teams in each group will advance to the winners bracket playoffs.
		</center></p>
	</div>
	<div class="bg4" >
		<h2>YOUR TEAM</h2>
	</div>
</div>

<?php
	include($url."footer.php");
?>
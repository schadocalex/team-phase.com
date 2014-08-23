<?php
	$url = '../../';
	include($url."include.php");

	$user->accessRight('SUPER_ADMIN');

	define('TEAM_STATUS__MEMBER', 1);
	define('TEAM_STATUS__ASKING', 5);

	if(isset($_POST['create_team']))
	{
		$form->verify_jeton($_POST['jeton']);
		$name = @$_POST['name'];
		$flag = @$_POST['flag'];
		$irc = @$_POST['irc'];
		$website = @$_POST['website'];

		if(empty($name))
			$form->error('You must enter a team name.');

		if($form->error == '')
		{
			$insert_team = new Insert('team');
			$insert_team->name = $name;
			$insert_team->flag_id = $flag;
			$insert_team->irc = $irc;
			$insert_team->website = $website;
			$insert_team->execute();

			$insert_user_team = new Insert('user_team');
			$insert_user_team->user_id = $user->id;
			$insert_user_team->team_id = $bdd->lastInsertId();
			$insert_user_team->status = TEAM_STATUS__MEMBER;
			$insert_user_team->execute();

			$_SESSION['success'] = 'Team <em>'.$name.'</em> has been created.';
			$user->redirect('Tournament');
		}
	}

	include($url."header.php");

	$users = Select::all('user');
	$teams = Select::all('team');
	$users_teams = Select::all('user_team');
	$user_teams = array();
	$team_users = array();

	foreach($users_teams as $ut)
	{
		if($ut['user_id'] == $user->id)
		{
			array_push($user_teams, $ut['team_id']);
		}
		if(!isset($team_users[$ut['team_id']]))
			$team_users[$ut['team_id']] = array();
		array_push($team_users[$ut['team_id']], $ut['user_id']);
	}
	/*
	echo '<pre>';
	var_dump($team_users);
	echo '</pre>';
	*/

?>
<div id="tournament" >
	<div class="bg4" >
		<img src="include/img/tournament/phasethetournament.png" style="border-radius:5px;position:relative;left:-20px;top:-10px;" width="898" />
		<p style="text-align:center;" ><a href="#popup_rules" class="fancybox" >Click here to view rules</a></p>
	</div>
	<div id="popup_rules" class="popup" >
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
		<?php
			showMessages();
			$form->initialize('accept_team', '', 'Team-Accept-1');
			echo 'Team <em>phase</em> invited you to join it.';
			$form->end('Accept');
		?>
		You can also create your own team:<br />
		<?php
			$form->initializeImg('create_team', '', 'Tournament');
			$form->hidden('create_team');
			$form->input('text', 'Name:', 'name');
			$form->input('flag', 'Flag:', 'flag');
			$form->input('text', 'Channel IRC:', 'irc');
			$form->input('text', 'Website:', 'website');
			$form->end('Create team');
		?>
		If you want to join an existing team, ask a member from it to invite you.
	</div>
</div>

<?php
	include($url."footer.php");
?>
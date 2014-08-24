<?php
	$url = '../../';
	include($url."include.php");

	$user->accessRight('SUPER_ADMIN');

	define('TEAM_STATUS__MEMBER', 1);
	define('TEAM_STATUS__LEADER', 2);
	define('TEAM_STATUS__ASKING', 5);

	$teams = Select::all('team');
	$users_teams = Select::all('user_team');
	$user_teams = array();
	$team_users = array();
	$user_has_team = false;
	$leader = false;
	foreach($users_teams as $ut)
	{
		if($ut['user_id'] == $user->id)
		{
			array_push($user_teams, array($ut['team_id'], $ut['status']));
			if($ut['status'] == TEAM_STATUS__MEMBER OR $ut['status'] == TEAM_STATUS__LEADER)
			{
				$user_has_team = true;
				$user_team = $teams[$ut['team_id']];
				if($ut['status'] == TEAM_STATUS__LEADER)
				{
					$leader = true;
				}
			}
		}
		if(!isset($team_users[$ut['team_id']]))
			$team_users[$ut['team_id']] = array();
		array_push($team_users[$ut['team_id']], array($ut['user_id'], $ut['status']));
	}

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
			$insert_user_team->status = TEAM_STATUS__LEADER;
			$insert_user_team->execute();

			$_SESSION['success'] = 'Team <em>'.$name.'</em> has been created.';
			$user->redirect('Tournament');
		}
	}
	if(isset($_POST['accept_team']))
	{
		$form->verify_jeton($_POST['jeton']);
		$team_id = @$_POST['accept_team'];

		if(!isset($teams[$team_id]))
			$form->error("The team doesn't exist.");

		$user_in_team = false;
		foreach($users_teams as $u)
		{
			if($u['user_id'] == $user->id AND $u['team_id'] == $team_id AND $u['status'] == TEAM_STATUS__ASKING)
			{
				$user_in_team = true;
				$id_of_table_user_team = $u['id'];
				break;
			}
		}
		if(!$user_in_team)
			$form->error("The team didn't invite you.");

		if($form->error == '')
		{
			$user_accept = $bdd->prepare('UPDATE user_team SET status = ? WHERE id = ?');
			$user_accept->bindValue(1, TEAM_STATUS__MEMBER);
			$user_accept->bindValue(2, $id_of_table_user_team);
			$user_accept->execute();

			$_SESSION['success'] = "You're now a member of team <em>{$teams[$team_id]['name']}</em>.";
			$user->redirect('Tournament');
		}
	}
	if(isset($_POST['invite_member']))
	{
		$form->verify_jeton($_POST['jeton']);
		$pseudo = @$_POST['pseudo'];

		if(empty($pseudo))
			$form->error('You must enter a pseudo to invite a member.');

		if($form->error == '')
		{
			$user_id = $bdd->prepare('SELECT id FROM user WHERE username = ?');
			$user_id->bindValue(1, $pseudo);
			$user_id->execute();
			if($user_id->rowCount() > 0)
			{
				$user_invited_id = $user_id->fetchAll();
				$user_invited_id = $user_invited_id[0]['id'];

				$already_in_team = false;
				foreach($users_teams as $u)
				{
					if($u['user_id'] == $user_invited_id AND
						($u['status'] == TEAM_STATUS__MEMBER OR $u['status'] == TEAM_STATUS__LEADER))
					{
						$already_in_team = true;
						break;
					}
				}

				if($already_in_team)
				{
					$_SESSION['error'] = "Member <em>".$pseudo."</em> is already in an other team.";
				}
				else
				{
					$insert_user_team = new Insert('user_team');
					$insert_user_team->user_id = $user_invited_id;
					$insert_user_team->team_id = $user_team['id'];
					$insert_user_team->status = TEAM_STATUS__ASKING;
					$insert_user_team->execute();

					$_SESSION['success'] = 'Member <em>'.$pseudo.'</em> has been asked to join the team.';
					$user->redirect('Tournament');
				}
			}
			else
			{
				$_SESSION['error'] = "Pseudo <em>".$pseudo."</em> doesn't exist. Ask him to register in team-phase.com.";
			}
		}
	}

	include($url."header.php");

	$users = Select::all('user');

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
	<?php showMessages(); ?>
	<?php if(!$user_has_team) { ?>
		<?php
			echo "You don't have team yet.<br/>";
			foreach ($user_teams as $t_array) {
				$t_id = $t_array[0];
				$t_status = $t_array[1];
				if($t_status == TEAM_STATUS__ASKING)
				{
					echo '<div>';
					$form->initialize('accept_team', '', 'Tournament');
					$form->hidden('accept_team', $t_id);
					echo '<span style="display:inline-block;width:250px;margin-right:-150px;" >Team <em>'.$teams[$t_id]['name'].'</em> invited you to join it.</span>';
					$form->end('Accept');
					echo '</div>';
				}
			}
		?>
		<br />Create your own team:<br />
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
	<?php } else { ?>
		<h2><?=dispFlag($user_team['flag_id']) ?> <?= $user_team['name'] ?></h2>
		<?php
			if(!empty($user_team['irc']))
				echo 'IRC: ' . $user_team['irc'] . '<br />';
			if(!empty($user_team['website']))
				echo 'Website: ' . $user_team['website'] . '<br />';
			echo 'Members:<br/>
				<ul>';
					foreach ($team_users[$user_team['id']] as $u_array) {
						$u_id = $u_array[0];
						$u_status = $u_array[1];
						$u = $users[$u_id];
						echo '<li>'.getUsername($u);
							if($u_status == TEAM_STATUS__MEMBER)
								echo ' (member)';
							if($u_status == TEAM_STATUS__LEADER)
								echo ' (leader)';
							if($u_status == TEAM_STATUS__ASKING)
								echo ' (invited, waiting for answer)';
						echo '</li>';
					}
			echo '
				</ul>';

			if($leader)
			{
				$form->initializeImg('invite_member', '', 'Tournament');
				$form->hidden('invite_member');
				$form->input('text', 'Invite member (pseudo):', 'pseudo');
				$form->end('Invite member');
			}
		?>
		<!-- <ul>
			<li><strong>Name</strong>: <?= $user_team['name'] ?></li>
			<li><strong>Flag</strong>: <?= $user_team['flag_id'] ?></li>
			<li><strong>Irc</strong>: <?= $user_team['irc'] ?></li>
			<li><strong>Website</strong>: <?= $user_team['website'] ?></li>
		</ul> -->
	<?php } ?>
	</div>
</div>

<?php
	include($url."footer.php");
?>
<?php
	$url = '../../';
	include($url."include.php");

	$user->accessRight('SUPER_ADMIN');

	define('TEAM_STATUS__MEMBER', 1);
	define('TEAM_STATUS__LEADER', 2);
	define('TEAM_STATUS__ASKING', 5);

	$teams = Select::all('team');
	$users_teams = Select::all('user_team');
	$user_teams = array(); // Tableau des teams relatifs à $user, array of 'key => array(team_id, status)'
	$team_users = array(); // Tableau des users contenus dans les teams, array of 'team_id => array(user_id, status)'
	$user_has_team = false;
	$leader = false;

	foreach($teams as $t)
	{
		$team_users[$t['id']] = array();
	}
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
					$_SESSION['error'] = "Member <em>".$pseudo."</em> is already in a team.";
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
	if(isset($_GET['promote']) OR isset($_GET['demote']) OR isset($_GET['exclude']))
	{
		$user_id = @$_GET['id_user'];

		$user_in_team = false;
		foreach($team_users[$user_team['id']] as $u_array)
		{
			if($user_id == $u_array[0])
			{
				$user_in_team = true;
				break;
			}
		}
		if(!$user_in_team)
			$_SESSION['error'] = "An error occured. This member isn't in your team.";
		else if(!$leader)
			$_SESSION['error'] = "You're not a leader of your team.";

		else
		{
			$users = Select::all('user');

			if(isset($_GET['exclude']))
			{
				$user_exclude_request = $bdd->prepare('DELETE FROM user_team WHERE user_id = ? AND team_id = ?');
				$user_exclude_request->bindValue(1, $user_id);
				$user_exclude_request->bindValue(2, $user_team['id']);
				$user_exclude_request->execute();
				$_SESSION['success'] = "Member <em>{$users[$user_id]['username']}</em> has been excluded.";
			}
			else
			{
				$user_status_request = $bdd->prepare('UPDATE user_team SET status = ? WHERE user_id = ? AND team_id = ?');
				if(isset($_GET['promote']))
				{
					$user_status_request->bindValue(1, TEAM_STATUS__LEADER);
					$_SESSION['success'] = "Member <em>{$users[$user_id]['username']}</em> has been promoted.";
				}
				if(isset($_GET['demote']))
				{
					$user_status_request->bindValue(1, TEAM_STATUS__MEMBER);
					$_SESSION['success'] = "Member <em>{$users[$user_id]['username']}</em> has been demoted.";
				}
				$user_status_request->bindValue(2, $user_id);
				$user_status_request->bindValue(3, $user_team['id']);
				$user_status_request->execute();
			}
		}
		$user->redirect('Tournament');
	}

	include($url."header.php");

	$users = Select::all('user');

?>
<div id="tournament" >
	<img src="include/img/tournament/phasethetournament.png" style="border-radius:5px;position:relative;left:1px;" width="898" />

	<div class="bg4" >
		<h2 onclick="$('#tournament_rules').fadeToggle('slow');" >RULES</h2>
		<div id="tournament_rules" style="display:none;" >
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
	</div>
	<div class="bg4" >
		<h2 onclick="$('#tournament_your_team').fadeToggle('slow');" >YOUR TEAM</h2>
		<div id="tournament_your_team" style="display:none;" >
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
							{
								echo ' (member)';
								if($leader)
								{
									echo ' - <a href="Tournament-Exclude-'.$u['id'].'" >Exclude</a> - <a href="Tournament-Promote-'.$u['id'].'" >Promote</a>';
								}
							}
							if($u_status == TEAM_STATUS__LEADER)
							{
								echo ' (leader)';
								if($leader AND $u_id != $user->id)
								{
									echo ' - <a href="Tournament-Demote-'.$u['id'].'" >Demote</a>';
								}
							}
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
	<div class="bg4" >
		<h2 onclick="$('#tournament_teams').fadeToggle('slow');" >TEAMS</h2>
		<div id="tournament_teams" style="display:none;" >
			<?php
				foreach($teams as $t)
				{
					echo '<h3>'.dispFlag($t['flag_id']).' '.$t['name'].'</h3>';

					if(!empty($t['irc']))
						echo 'IRC: ' . $t['irc'] . '<br />';
					if(!empty($t['website']))
						echo 'Website: ' . $t['website'] . '<br />';
					echo 'Members:<br/>
						<ul>';
					foreach ($team_users[$t['id']] as $u_array) {
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
						</ul><br /><br />';
				}
			?>
		</div>
	</div>
	<div class="bg4" >
		<h2>GROUPS</h2>
	</div>
	<div class="bg4" >
		<h2>WINNERS BRACKET</h2>
	</div>
</div>

<?php
	include($url."footer.php");
?>
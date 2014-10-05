<?php
	$url = '../../';
	include($url."include.php");

	define('TEAM_STATUS__MEMBER', 1);
	define('TEAM_STATUS__LEADER', 2);
	define('TEAM_STATUS__ASKING', 5);

	$teams = Select::all('team');
	$users_teams = Select::all('user_team');
	$groups = Select::all('groups');
	$tournament_matches = Select::all('tournament_match');
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

	if(isset($_POST['create_team']) AND $user->is('MEMBER'))
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
	if(isset($_POST['edit_team']) AND $user->is('MEMBER'))
	{
		$form->verify_jeton($_POST['jeton']);
		$name = @$_POST['name'];
		$flag = @$_POST['flag'];
		$irc = @$_POST['irc'];
		$website = @$_POST['website'];

		if(empty($name))
			$form->error('You must enter a team name.');
		if(!$leader)
			$form->error('You\'re not a leader of your team');

		if($form->error == '')
		{
			$edit_team = $bdd->prepare('UPDATE team SET name = ?, flag_id = ?, irc = ?, website = ? WHERE id = ?');
			$edit_team->bindValue(1, $name);
			$edit_team->bindValue(2, $flag);
			$edit_team->bindValue(3, $irc);
			$edit_team->bindValue(4, $website);
			$edit_team->bindValue(5, $user_team['id']);
			$edit_team->execute();

			$_SESSION['success'] = 'Team <em>'.$name.'</em> has been edited.';
			$user->redirect('Tournament');
		}
	}
	if(isset($_POST['delete_team']) AND $user->is('MEMBER'))
	{
		$user->accessRight('SUPER_ADMIN');

		$form->verify_jeton($_POST['jeton']);
		$team_id = @$_POST['delete_team'];

		if(!isset($teams[$team_id]))
			$form->error("The team doesn't exist.");

		if($form->error == '')
		{
			$delete_team = $bdd->prepare('DELETE FROM user_team WHERE team_id = ?');
			$delete_team->bindValue(1, $team_id);
			$delete_team->execute();

			$delete_team = $bdd->prepare('DELETE FROM team WHERE id = ?');
			$delete_team->bindValue(1, $team_id);
			$delete_team->execute();

			$_SESSION['success'] = "The team <em>{$teams[$team_id]['name']}</em> has been deleted.";
			$user->redirect('Tournament');
		}
	}
	if(isset($_POST['accept_team']) AND $user->is('MEMBER'))
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

			$_SESSION['success'] = "You're now a team member of <em>{$teams[$team_id]['name']}</em>.";
			$user->redirect('Tournament');
		}
	}
	if(isset($_POST['invite_member']) AND $user->is('MEMBER'))
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
					$_SESSION['error'] = "Member <em>$pseudo</em> is already in a team.";
				}
				else
				{
					$insert_user_team = new Insert('user_team');
					$insert_user_team->user_id = $user_invited_id;
					$insert_user_team->team_id = $user_team['id'];
					$insert_user_team->status = TEAM_STATUS__ASKING;
					$insert_user_team->execute();

					$_SESSION['success'] = "Member <em>$pseudo</em> has been asked to join the team.";
					$user->redirect('Tournament');
				}
			}
			else
			{
				$_SESSION['error'] = "Pseudo <em>$pseudo</em> doesn't exist. Ask him to register in team-phase.com.";
			}
		}
	}
	if((isset($_GET['promote']) OR isset($_GET['demote']) OR isset($_GET['exclude'])) AND $user->is('MEMBER'))
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
			<p>
				These are the rules for the TEAM-PHASE eSports Cup, please feel free to contact someone in the admin team if you think there needs to be additions.<br />
<br />
				Ignorance of the following rules will not be a viable excuse for competing players. Please learn and follow them throughout the tournament.<br />
<br />
				If you have any problem, please contact us at <a href="mailto:contact@team-phase.com" >contact@team-phase.com</a> - irc @ QuakeNet <a href="irc://qnet/team-phase" >#team-phase</a>.<br />
<br />
				<span style="color:#f00" >1) Admin team:</span><br />
<br />
				aacid, drkje & chilax<br />
<br />
				<span style="color:#f00" >2) General rules:</span><br />
<br />
				- The team captain is responsible for his own members knowing and following the rules.<br />
				- Each team has 1 wildcard in the group stage, and 1 in the playoffs.<br />
				  (If 1 team used their wildcard, the match have to be played within the next week)<br />
				- Matches will be forced on Thursday to the following Sunday or Monday at either 20 or 21 CET.<br />
				- All players have to record their matches!<br />
<br />
				<span style="color:#f00" >3) Teams & players</span><br />
<br />
				- All participants are required to register at www.team-phase.com. A player's nickname on the website <br />
				  needs to match a player's in-game nickname.<br />
				- 5 players/Team, all players have to be in the team before the sign ups are closed. <br />
				- Using a 'merc' is only allowed if the opponent agree with it.<br />
				- A player can only play for 1 team in this cup!<br />
<br />
				<span style="color:#f00" >4) Match rules:</span><br />
<br />
				- The maps in the 'groupstage' are forced:<br />
				  You can use cointoss to decide which team may choose the first map. The winner of the cointoss<br />
				  can vote the map, the loser of the cointoss can vote for the side (allies/axis).<br />
				- The maps in the 'playoffs' are not forced.<br />
				- Ties are not allowed, the decider will be choosen by using cointoss.<br />
<br />
				<span style="color:#f00" >5) Result info:</span><br />
<br />
				For the avoidance of doubt: <br />
<br />
				- Forfeit loss in group stage = 4-0 loss.<br />
				- Forfeit loss in playoffs = 1-0 loss.<br />
<br />
				Also in a tie at the end of the groupstage, we look at the individual game between the 2 teams!<br />
<br />
				<span style="color:#f00" >6) Maplist:</span><br />
<br />
				The maplist for the TEAM-PHASE eSports Cup is:<br />
<br />
				adlernest<br />
				braundorf_b4<br />
				erdenberg_t1<br />
				et_ice<br />
				frostbite<br />
				sp_delivery_te<br />
				supply<br />
				sw_goldrush_te<br />
<br />
				All maps can be downloaded here. - http://www.gamestv.org/gtvd.repository.php<br />
<br />
				<span style="color:#f00" >7) Demos, cheating & abuse:</span><br />
<br />
				- Since there is no anticheat, players are required to record demos of their matches and are not allowed to <br />
				  delete those demos until the end of the cup!<br />
				- An admin can request demos at any time during the cup. <br />
				- If you accuse someone of cheating, contact the admins.<br />
				- Players banned on CyberGamer are not allowed to play the cup.<br />
<br />
				If we catch someone who cheated in this cup, we'll ban the player from our cup, and the last match where he<br />
				cheated will result in a forfeit loss.<br />
<br />
				<span style="color:#f00" >8) Prizes:</span><br />
<br />
				Prizes will be awarded to 1st and 2nd place in the tournament, once the cup is completed.<br />
<br />
				<span style="color:#f00" >9) Donations:</span><br />
<br />
				You can help donate for this TEAM-PHASE eSports Cup. The money donated will go 100% to the prizepool of the TEAM-PHASE eSports Cup.
<br /><br />
				<center><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
					<input type="hidden" name="cmd" value="_donations">
					<input type="hidden" name="business" value="NTCATJNDW6HFL">
					<input type="hidden" name="lc" value="FR">
					<input type="hidden" name="item_name" value="TEAM-PHASE eSports Cup">
					<input type="hidden" name="currency_code" value="EUR">
					<input type="hidden" name="bn" value="PP-DonationsBF:paypaldonate.png:NonHosted">
					<input type="image" src="http://www.team-phase.com/include/img/icon/paypaldonate.png" style="background:none;" border="0" name="submit" alt="PayPal">
					<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
				</form></center>

			</p>
		</div>
	</div>
	<div class="bg4" >
		<h2 onclick="$('#tournament_your_team').fadeToggle('slow');" >YOUR TEAM</h2>
	<?php showMessages(); ?>
		<div id="tournament_your_team" style="display:none;" >
	<?php if($user->is('VISITOR')) { ?>
		You have to register in team-phase.com to be a player in this cup.
	<?php } else if(!$user_has_team) { ?>
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
		<h1><span style="position:relative;top:-3px;"><?=dispFlag($user_team['flag_id']) ?></span> <?= $user_team['name'] ?></h1>
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
				// Invite member
				$form->initializeImg('invite_member', '', 'Tournament');
				$form->hidden('invite_member');
				$form->input('text', 'Invite member (pseudo):', 'pseudo');
				$form->end('Invite member');

				// Edit team
				echo '<a href="#popup_edit_team" class="fancybox" >Edit team</a>';
				echo '<div id="popup_edit_team" class="popup" >';

				$form->initialize('edit_team', '', 'Tournament');
				$form->hidden('edit_team');
				$form->input('text', 'Name', 'name', $user_team['name']);
				$form->input('flag', 'Flag:', 'flag', $user_team['flag_id']);
				$form->input('text', 'Channel IRC:', 'irc', $user_team['irc']);
				$form->input('text', 'Website:', 'website', $user_team['website']);
				$form->end('Edit team');

				echo '</div>';
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
					echo '<div class="team" >';
					echo '<h3>'.dispFlag($t['flag_id']).' '.$t['name'];
					if($user->is('SUPER_ADMIN')) echo ' (#'.$t['id'].')';
					echo '</h3>';

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
						</ul>';

					if($user->is('SUPER_ADMIN'))
					{
						$form->initializeImg('delete_team', '', 'Tournament');
						$form->hidden('delete_team', $t['id']);
						$form->end('Delete team');
					}

					echo '</div>';
				}
			?>
		</div>
	</div>
	<div class="bg4" >
		<h2 onclick="$('#tournament_groups').fadeToggle('slow');" >GROUPS</h2>
		<div id="tournament_groups" style="display:one;" >
			<?php
				$actual_group = '-1';
				foreach ($groups as $g)
				{
					if($actual_group != $g['letter'])
					{
						if($actual_group != '-1')
							echo '
						</table>
						<br />';

						$actual_group = $g['letter'];

						echo '
						<h3>GROUP '.$actual_group.'</h3>
						<table class="group" >
							<tr>
								<th style="width:60px;" >Rank</th>
								<th style="width:350px;">Team</th>
								<th style="width:60px;" >Points</th>
								<th style="width:60px;" >Win</th>
								<th style="width:60px;" >Draw</th>
								<th style="width:60px;" >Loss</th>
								<th style="width:60px;" >Played</th>
								<th style="width:60px;" >Diff</th>
							</tr>';
					}
					echo '
					<tr>
						<td>'.$g['rank'].'</td>
						<td>'.dispFlag($teams[$g['team_id']]['flag_id']).' '.$teams[$g['team_id']]['name'].'</td>
						<td>'.$g['points'].'</td>
						<td>'.$g['win'].'</td>
						<td>'.$g['draw'].'</td>
						<td>'.$g['loss'].'</td>
						<td>'.$g['played'].'</td>
						<td>'.($g['diff']>=0?'+':'').$g['diff'].'</td>
					</tr>';
				}
				echo '
				</table>';
				echo '
					<h2>MATCHES</h2>
					<table class="group" >
						<tr>
							<th style="width:20px;" >Group</th>
							<th style="width:250px;" >Team 1</th>
							<th style="width:250px;">Team 2</th>
							<th style="width:60px;" >Score</th>
							<th style="width:120px;" >Date</th>
							<th style="width:60px;" > </th>
						</tr>';
				foreach ($tournament_matches as $m) {
					echo '<tr>
							<td>'.$m['group'].'</td>
							<td>'.dispFlag($teams[$m['team_1_id']]['flag_id']).' '.$teams[$m['team_1_id']]['name'].'</td>
							<td>'.dispFlag($teams[$m['team_2_id']]['flag_id']).' '.$teams[$m['team_2_id']]['name'].'</td>
							<td>'.$m['score_team_1'].' - '.$m['score_team_2'].'</td>
							<td>'.$m['date'].'</td>
							<td>'.($m['matchlink']?('<a href="'.$m['matchlink'].'">matchlink</a>'):'').'</td>
						';
				}
				echo '
					</table>';
			?>
		</div>
	</div>
	<div class="bg4" >
		<h2 onclick="$('#tournament_playoffs').fadeToggle('slow');" >PLAYOFFS</h2>
		<div id="tournament_playoffs" style="display:none;" >
			This stage will start after groups stage.
		</div>
	</div>
</div>

<?php
	include($url."footer.php");
?>
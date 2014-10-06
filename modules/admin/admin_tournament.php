<?php
	$url = '../../';
	include($url."include.php");

	$user->accessRight('ADMIN_TOURNAMENT');

	$teams = Select::all('team');
	$groups = Select::all('groups');
	$tournament_matches = Select::all('tournament_match');

	if(isset($_SESSION['compute_groups']))
	{
		unset($_SESSION['compute_groups']);
		// Recalcul du classement
		$new_groups = computeGroups($groups, $tournament_matches);
		$new_group_request = $bdd->prepare('UPDATE groups SET
			rank = ?,
			points = ?,
			win = ?,
			draw = ?,
			loss = ?,
			played = ?,
			diff = ?
			WHERE id = ?');
		foreach ($new_groups as $g) {
			$new_group_request->bindValue(1, $g['rank']);
			$new_group_request->bindValue(2, $g['points']);
			$new_group_request->bindValue(3, $g['win']);
			$new_group_request->bindValue(4, $g['draw']);
			$new_group_request->bindValue(5, $g['loss']);
			$new_group_request->bindValue(6, $g['played']);
			$new_group_request->bindValue(7, $g['diff']);
			$new_group_request->bindValue(8, $g['id']);
			$new_group_request->execute();
		}
		$user->redirect('Admin-Tournament');
	}

	include($url."header.php");
	include("menu.php");
?>

<div id="admin_tournament" >
	<?php showMessages(true); ?>
	<div class="admin bg4" >
		<h2>MATCHES</h2>
		<a href="#add_new_match" class="fancybox" >Add match</a><br /><br />
			<?php
				echo 
				'<table class="group" >
					<tr>
						<th style="width:20px;" >Group</th>
						<th style="width:250px;" >Team 1</th>
						<th style="width:250px;">Team 2</th>
						<th style="width:60px;" >Score</th>
						<th style="width:120px;" >Date</th>
						<th style="width:60px;" > </th>
						<th style="width:20px;" > </th>
						<th style="width:20px;" > </th>
					</tr>';
			foreach ($tournament_matches as $m) {
				echo '<tr>
						<td>'.$m['group_letter'].'</td>
						<td>'.dispFlag($teams[$m['team_1_id']]['flag_id']).' '.$teams[$m['team_1_id']]['name'].'</td>
						<td>'.dispFlag($teams[$m['team_2_id']]['flag_id']).' '.$teams[$m['team_2_id']]['name'].'</td>
						<td>'.$m['score_team_1'].' - '.$m['score_team_2'].'</td>
						<td>'.$m['date'].'</td>
						<td>'.($m['matchlink']?('<a href="'.$m['matchlink'].'">matchlink</a>'):'').'</td>
						<td class="icon" >
						<a href="Admin-Tournament-Edit-Match-'.$m['id'].'" >
							<img src="include/img/icon/edit.gif" alt="Edit" />
						</a>
						</td>
						<td class="icon" >
							<a href="Admin-Tournament-Delete-Tournament_Match-'.$m['id'].'" >
								<img src="include/img/icon/delete.png" alt="Edit" />
							</a>
						</td>
					';
			}
			echo '
				</table>';
		?>
		<br /><a href="#add_new_match" class="fancybox" >Add match</a><br /><br />
		<div id="add_new_match" class="popup" >
			<?php include('tournament/add_match_embedded.php'); ?>
		</div>
	</div>
	<div class="admin bg4" >
		<h2>GROUPS</h2>
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
		?>
	</div>
</div>
<?php
	include($url."footer.php");
?>
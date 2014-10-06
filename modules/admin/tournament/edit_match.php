<?php
	$url= '../../../';
	include($url . 'include.php');

	$user->accessRight('ADMIN_TOURNAMENT');

	$id = @$_GET['id'];
	if(!MySQL::exist('tournament_match', 'id', $id))
	{
		$_SESSION['error'] = "Match with id $id doesn't exist.";
		$user->redirect('Admin-Tournament');
	}

	$match = Select::withId('tournament_match', $id);

	if(isset($_POST['jeton']))
	{
		$form->verify_jeton($_POST['jeton']);
		$date = $form->verify_date('date');
		$team_1_id = $form->verify_table('team', 'team_1');
		$team_2_id = $form->verify_table('team', 'team_2');
		$score_team_1 = @$_POST['score_team_1'];
		$score_team_2 = @$_POST['score_team_2'];
		$group_letter = @$_POST['group_letter'];
		$matchlink = @$_POST['matchlink'];

		if(empty($matchlink))
			$matchlink = '';

		if(empty($group_letter))
			$form->error('You must enter a group letter for the match.');
		if(is_nan($score_team_1))
			$form->error('You must enter a score to team 1.');
		if(is_nan($score_team_2))
			$form->error('You must enter a score to team 2.');

		if($form->error == "")
		{
			$update = $bdd->query("UPDATE tournament_match SET
				date = \"$date\",
				team_1_id = \"$team_1_id\",
				team_2_id = \"$team_2_id\",
				score_team_1 = \"$score_team_1\",
				score_team_2 = \"$score_team_2\",
				matchlink = \"$matchlink\",
				group_letter = \"$group_letter\"
				WHERE id = \"$id\"");

			$_SESSION['success'] = 'Match has been edited.';
			$_SESSION['compute_groups'] = true;
			$user->redirect('Admin-Tournament');
		}
	}

	include($url . 'header.php');
	include("../menu.php");
?>
	<div class="bg4" >
		<?php
			showMessages();
			$form->initializeImg('edit_match', '', 'Admin-Tournament-Edit-Match-'.$match['id']);
			$form->input('date', 'Date:', 'date', $match['date']);
			$form->select_table('team', 'name', 'Team 1:', 'team_1', $match['team_1_id']);
			$form->select_table('team', 'name', 'Team 2:', 'team_2', $match['team_2_id']);
			$form->input('number', 'Score team 1:', 'score_team_1', $match['score_team_1']);
			$form->input('number', 'Score team 2:', 'score_team_2', $match['score_team_2']);
			$form->input('text', 'Group letter', 'group_letter', $match['group_letter']);
			$form->input('text', 'Matchlink', 'matchlink', $match['matchlink']);
			$form->end('Edit match');
		?>
	</div>
<?php
	include($url."footer.php");
?>
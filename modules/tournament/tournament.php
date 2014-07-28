<?php
	$url = '../../';
	include($url."include.php");

	$user->accessRight('SUPER_ADMIN');

	$NB_TEAMS = 16;
	$NB_TEAMS_PER_GROUPS = 4;
	$NB_MATCHES_PER_TEAMS_IN_GROUPS = 1;
	
	$teams_bdd = Select::all('opponent');
	shuffle($teams_bdd);
	$groups = array();

	for($i = 0; $i < $NB_TEAMS; $i++)
	{
		$t = $teams_bdd[$i];
		$t['id'] = $i;
		$t['n'] = $i+1;
		$teams[$i] = $t;
	}

	function generateGroups($teams, $nb_teams_per_groups)
	{
		$nb_teams = count($teams);
		$nb_groups = ceil($nb_teams / $nb_teams_per_groups);
		shuffle($teams);
		$groups = array();
		for($i = 0; $i < $nb_groups; $i++)
		{
			for($j = 0; $j < $nb_teams_per_groups; $j++)
				$groups[$i][$j] = $teams[$i*$nb_teams_per_groups+$j]['id'];
		}
		return $groups;
	}

	function generateMatchesGroups($groups, $nb_matches)
	{
		$matches_groups = array();
		foreach ($groups as $id => $group)
		{
			$matches_group = array();
			for($i = 0; $i < count($group)-1; $i++)
			{
				$matches_group[$i] = array();
				$middle = floor(count($group)/2);
				for($j = 0; $j < count($group); $j+=2)
				{
					$matches_group[$i][] = array($group[$j], $group[($j+$i+1)%count($group)]);
				}
				//array_push($group, array_shift($group));
			}
			$matches_groups[$id] = $matches_group;
		}
		return $matches_groups;
	}

	$groups = generateGroups($teams, $NB_TEAMS_PER_GROUPS);
	$matches_groups = generateMatchesGroups($groups, $NB_MATCHES_PER_TEAMS_IN_GROUPS);
	//var_dump($matches_groups);
	
	include($url."header.php");
?>
<div id="tournament" >
	<div>
		<img src="include/img/tournament/phasethetournament.png" style="border-radius:5px;position:relative;left:1px;" width="898" />
	</div>
	<div class="bg4" >
		<h2>RULES<span style="color:red;" >!</span></h2>
		<p><center>
			<span style="color:red;" >1</span>) All participants are required to register at www.team-phase.com. A player's nickname on the website needs to match a player's in-game nickname.
		</p>
		<p>
			<span style="color:red;" >2</span>) The maximum amount of slots per team is 4. Teams can add a player to their roster whilst the cup is in progress as long as that player hasn't previously played for a different team.
		</p>
		<p>
			<span style="color:red;" >3</span>) A team is allowed to use one stand-in if required, the stand-in will however have to be approved by the admin team based on resemblance of skill level in regards to the player the stand-in is replacing.
		</p>
		<p>
			<span style="color:red;" >4</span>) Cheating is not allowed. Players are required to record demos of their matches and are not allowed to delete those demos until the end of the cup. An admin can at all times request those demos.
		</p>
		<p>
			<span style="color:red;" >5</span>) Weapon boosting is not allowed. A player committing intentional boosting will be banned from playing the next match besides further consequences based on the conditions of the event.
		</p>
		<p>
			<span style="color:red;" >6</span>) Allowed player classes : Medic - Lieutenant - Engineer and 1 Soldier with the exclusion of the following weapons: Panzer and Flamethrower
		</p>
		<p>
			<span style="color:red;" >7</span>) The games are played in the standard ABBA format. By default teams have to play 2 maps, but in the case of a draw by the end of those 2 maps a match deciding map will be played.
			<br />If teams still draw by the end of the decider map an AB round will be added until one team comes out victorious.
		</p>
		<p>
			<span style="color:red;" >8</span>) During the group stage the winning teams will receive 1 point per win. The losing team won't receive any points.
			<br />If by the end of the group stage several teams have the same amount of points their positions will be determined by the results of the games where the teams with the same amount of points played each other. Naturally the winning team will get the higher position.
			<br />The first and second placed teams in each group will advance to the winners bracket playoffs.
		</center></p>
	</div>
	<!--
	<div class="bg4" >
		<h2>TEST GENERATION</h2>
		<?php
		/*
			foreach($teams as $team)
			{
				echo 'Team n°'.$team['n'].': '.$team['name'].'<br/>';
			}
			echo '<br />';
			foreach($groups as $id => $group)
			{
				echo 'Group n°'.($id+1).':
				<ul>';
				foreach($group as $team_group)
					echo '<li>('.$teams[$team_group]['n'].') '.$teams[$team_group]['name'] . '</li>';
				echo '</ul><br />Matches :<ul>';
				foreach ($matches_groups[$id] as $day => $matches)
				{
					echo '<li>Day '.$day.':<ul>';
					foreach($matches as $match)
						echo '<li>'.$teams[$match[0]]['name'].' <span style="color:#f00;" >vs</span> '.$teams[$match[1]]['name'].'</li>';
					echo '</ul></li>';
				}
				echo '</ul><br /><br />';
			}
		*/
		?>
	</div>
-->
</div>

<?php
	include($url."footer.php");
?>
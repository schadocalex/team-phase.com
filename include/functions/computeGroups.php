<?php

	function computeGroups($groups, $matches)
	{
		$NB_POINTS_WIN = 1;
		$NB_POINTS_DRAW = 0;
		$NB_POINTS_LOSS = -1;

		// Remise à 0
		$groups_key_team_id = array();
		foreach ($groups as $g) {
			$groups_key_team_id[$g['team_id']] = $g;
			$groups_key_team_id[$g['team_id']]['rank'] = 0;
			$groups_key_team_id[$g['team_id']]['points'] = 0;
			$groups_key_team_id[$g['team_id']]['win'] = 0;
			$groups_key_team_id[$g['team_id']]['draw'] = 0;
			$groups_key_team_id[$g['team_id']]['loss'] = 0;
			$groups_key_team_id[$g['team_id']]['played'] = 0;
			$groups_key_team_id[$g['team_id']]['diff'] = 0;
		}

		// Calcul des points/win/draw/loss/played/diff
		foreach ($matches as $m) {
			$diff = $m['score_team_1'] - $m['score_team_2'];
			$groups_key_team_id[$m['team_1_id']]['diff'] += $diff;
			$groups_key_team_id[$m['team_2_id']]['diff'] -= $diff;
			$groups_key_team_id[$m['team_1_id']]['played'] ++;
			$groups_key_team_id[$m['team_2_id']]['played'] ++;

			if($diff > 0)
			{
				$groups_key_team_id[$m['team_1_id']]['points'] += $NB_POINTS_WIN;
				$groups_key_team_id[$m['team_2_id']]['points'] += $NB_POINTS_LOSS;
				$groups_key_team_id[$m['team_1_id']]['win']++;
				$groups_key_team_id[$m['team_2_id']]['loss']++;
			}
			else if($diff < 0)
			{
				$groups_key_team_id[$m['team_1_id']]['points'] += $NB_POINTS_LOSS;
				$groups_key_team_id[$m['team_2_id']]['points'] += $NB_POINTS_WIN;
				$groups_key_team_id[$m['team_1_id']]['loss']++;
				$groups_key_team_id[$m['team_2_id']]['win']++;
			}
			else
			{
				$groups_key_team_id[$m['team_1_id']]['points'] += $NB_POINTS_DRAW;
				$groups_key_team_id[$m['team_2_id']]['points'] += $NB_POINTS_DRAW;
				$groups_key_team_id[$m['team_1_id']]['draw']++;
				$groups_key_team_id[$m['team_2_id']]['draw']++;
			}
			if($groups_key_team_id[$m['team_1_id']]['points'] < 0)
				$groups_key_team_id[$m['team_1_id']]['points'] = 0;
			if($groups_key_team_id[$m['team_2_id']]['points'] < 0)
				$groups_key_team_id[$m['team_2_id']]['points'] = 0;
		}

		usort($groups_key_team_id, "compareTeam");

		$letter = '0';
		$rank = 0;
		foreach ($groups_key_team_id as $k => $g) {
			if($g['letter'] != $letter)
			{
				$rank = 0;
				$letter = $g['letter'];
			}
			$groups_key_team_id[$k]['rank'] = ++$rank;
		}

		return $groups_key_team_id;
	}

	function compareTeam($t1, $t2)
	{
		$CRITERES_CLASSEMENT = array('points', 'win', 'draw', 'diff');

		$compare_group = strnatcasecmp($t1['letter'], $t2['letter']);
		if($compare_group != 0)
			return $compare_group;

		foreach ($CRITERES_CLASSEMENT as $c) {
			if($t1[$c] != $t2[$c])
				return $t2[$c] - $t1[$c];
		}
		return 0;
	}
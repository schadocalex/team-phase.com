<?php
	$url = '../../';
	include($url."include.php");
	
	$match = Select::all('match2');
	$competition = Select::all('competition');
	$opponent = Select::all('opponent');
	$game = Select::all('game');
	$image = Select::all('image');

	include($url."header.php");
?>
<div id="results" >
<div id="results" >
		<div id="awards" class="bg4" >
			<h2>AWARDS</h2>
			<table>
				</tr>
				<tr class="no_match_link" ><td colspan="5" ><hr/></td></tr>
				<tr onclick="window.open('http://eu.cybergamer.com/bracket/38/CG-EU-ET-Season-1-6on6-Div-1/')">
					<td class="game" ><img src="include/img/games/et.gif"></td>
					<td class="date" >15/06/2014</td>
					<td class="award" ><img class="awards_icon" src="include/img/awards/silver.png" /> CyberGamer EU ET Season 1 6on6 Division 1</td>
				</tr>
				<tr class="no_match_link" ><td colspan="5" ><hr/></td></tr>
				<tr onclick="window.open('http://eu.cybergamer.com/bracket/43/CG-EU-ET-Season-1-3on3-Div-1/')">
					<td class="game" ><img src="include/img/games/et.gif"></td>
					<td class="date" >15/06/2014</td>
					<td class="award" ><img class="awards_icon" src="include/img/awards/silver.png" /> CyberGamer EU ET Season 1 3on3 Division 1</td>
				</tr>
				<tr class="no_match_link" ><td colspan="5" ><hr/></td></tr>
				<tr onclick="window.open('http://eu.cybergamer.com/forums/thread/542557/Granted-come-out-victorious-and-take-the-100-prize/')">
					<td class="game" ><img src="include/img/games/cod4.gif"></td>
					<td class="date" >27/04/2014</td>
					<td class="award" ><img class="awards_icon" src="include/img/awards/silver.png" /> CyberGamer CoD4 Tournament #1</td>
				</tr>
				<tr class="no_match_link" ><td colspan="5" ><hr/></td></tr>
				<tr onclick="window.open('http://www.crossfire.nu/news/8425/alliance-cup-grand-final')">
					<td class="game" ><img src="include/img/games/rtcw.gif"></td>
					<td class="date" >31/03/2014</td>
					<td class="award" ><img class="awards_icon" src="include/img/awards/gold.png" /> RtCW ASUS Alliance 6on6 Cup</td>
				</tr>
				<tr class="no_match_link" ><td colspan="5" ><hr/></td></tr>
				<tr onclick="window.open('http://www.crossfire.nu/news/8356/cf-winter-grand-final')">
					<td class="game" ><img src="include/img/games/et.gif"></td>
					<td class="date" >15/12/2013</td>
					<td class="award" ><img class="awards_icon" src="include/img/awards/gold.png" /> CROSSFIRE ET WinterCup 6on6 2013</td>
				</tr>
				<tr class="no_match_link" ><td colspan="5" ><hr/></td></tr>
				<tr onclick="window.open('http://www.crossfire.nu/news/8325/rtcw-legacy-cup-grand-finale')" >
					<td class="game" ><img src="include/img/games/rtcw.gif"></td>
					<td class="date" >11/11/2013</td>
					<td class="award" ><img class="awards_icon" src="include/img/awards/bronze.png" /> Return to Castle Wolfenstein 6on6 Legacy Cup 2013</td>
				</tr>
				<tr class="no_match_link" ><td colspan="5" ><hr/></td></tr>
				<tr onclick="window.open('http://www.crossfire.nu/news/8300/cb-et-summer-2013-winners')" >
					<td class="game" ><img src="include/img/games/et.gif"></td>
					<td class="date" >22/09/2013</td>
					<td class="award" ><img class="awards_icon" src="include/img/awards/gold.png" /> Enemy Territory Clanbase 3on3 Summer Cup Premier league 2013</td></tr>
			</table>
			<br />
		</div>
		<div id="matches" class="bg4" >
			<h2>MATCHES<br/>
				<span style="font-size:10px;font-weight:normal;" >
					(We don't add ODC results)
				</span>
			</h2>
			<table>
				<tr class="head_tab no_match_link" >
					<th><br/><br/></th>
					<th>Date</th>
					<th>Competition</th>
					<th>Opponent</th>
					<th>Score</th>
				</tr>
				<?php
					$first = true;
					foreach($match as $id => $one_match)
					{
						$user_opponent = new User($one_match['opponent_id']);
						$date = DateTime::createFromFormat('Y-m-d', $one_match['date']);
						if($first) $first = false; else
							echo '<tr class="no_match_link" ><td colspan="5" ><hr/></td></tr>';
						echo '
						<tr onclick="window.open(\'' . $one_match['matchlink'] . '\')" >
						<td class="game" ><img src="include/img/upload/' . $image[$game[$one_match['game_id']]['icon_id']]['url'] . '" /></td>
						<td class="date" >' .  $date->format('d/m/Y') . '</td>
						<td class="compet" >' . $competition[$one_match['competition_id']]['name'] . '</td>
						<td class="versius" >' . dispFlag($opponent[$one_match['opponent_id']]['flag_id']) . ' ' . $opponent[$one_match['opponent_id']]['name'] . '</td>';
						if($one_match['score_phase'] < 0 OR $one_match['score_opponent'] < 0)
						{
							$color = ($one_match['score_opponent'] < 0) ? 'win' : 'defeat';
							echo'
							<td class="score score_' . $color . '">
								Forfeit
							</td>';
						}
						else
						{
							$color = ($one_match['score_phase'] > $one_match['score_opponent'])? 'win' : 'defeat';
							$color = ($one_match['score_phase'] == $one_match['score_opponent'])? 'draw' : $color;
							
							echo'
							<td class="score score_'.$color.'">
							' . $one_match['score_phase'] . ' - ' . $one_match['score_opponent'] . '
							</td>';
						}
						echo '
					</tr>';
					}
				?>
			</table>
		</div>
	</div>
</div>
<?php
	include($url."footer.php");
?>
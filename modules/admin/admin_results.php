<?php
	$url = '../../';
	include($url."include.php");

	$user->accessRight('ADMIN_RESULTS');

	$games = Select::all('game');
	$competitions = Select::all('competition');
	$opponents = Select::all('opponent');
	$matches = Select::all('match2');

	include($url."header.php");
	include("menu.php");
?>

<div id="admin_menu" >
	<?php showMessages(true); ?>
	<div class="admin bg4" >
		<h2>GAMES</h2>
		<a href="#add_new_game" class="fancybox" >Add game</a><br /><br />
		<table>
			<?php foreach($games as $game) { ?>
				<tr style="float:left;width:286px;" >
					<td class="admin_matches_game" >
						<?= dispImg($game['icon_id'], 'game_icon') ?>
						<?= $game['name'] ?>
					</td>
					<td class="icon" >
						<a href="Admin-Results-Edit-Game-<?= $game['id'] ?>" >
							<img src="include/img/icon/edit.gif" alt="Edit" />
						</a>
					</td>
					<td class="icon" >
						<a href="Admin-Results-Delete-Game-<?= $game['id'] ?>" >
							<img src="include/img/icon/delete.png" alt="Edit" />
						</a>
					</td>
				</tr>
			<?php } ?>
		</table>
		<br />
		<a href="#add_new_game" class="fancybox" >Add game</a>
		<div id="add_new_game" class="popup" >
			<?php include('results/add_game_embedded.php'); ?>
		</div>
	</div>
	<div class="admin bg4" >
		<h2>COMPETITIONS</h2>
		<a href="#add_new_competition" class="fancybox" >Add competition</a><br /><br />
		<table>
			<?php foreach($competitions as $competition) { ?>
				<tr style="float:left;width:286px;" >
					<td class="admin_matches_competition" >
						<?= $competition['name'] ?>
					</td>
					<td class="icon" >
						<a href="Admin-Results-Edit-Competition-<?= $competition['id'] ?>" >
							<img src="include/img/icon/edit.gif" alt="Edit" />
						</a>
					</td>
					<td class="icon" >
						<a href="Admin-Results-Delete-Competition-<?= $competition['id'] ?>" >
							<img src="include/img/icon/delete.png" alt="Edit" />
						</a>
					</td>
				</tr>
			<?php } ?>
		</table>
		<br />
		<a href="#add_new_competition" class="fancybox" >Add competition</a>
		<div id="add_new_competition" class="popup" >
			<?php include('results/add_competition_embedded.php'); ?>
		</div>
	</div>
	<div class="admin bg4" >
		<h2>OPPONENTS</h2>
		<a href="#add_new_opponent" class="fancybox" >Add opponent</a><br /><br />
		<table>
			<?php foreach($opponents as $opponent) { ?>
				<tr style="float:left;width:286px;" >
					<td class="admin_matches_opponent" >
						<?= dispFlag($opponent['flag_id']) ?> <?= $opponent['name'] ?>
					</td>
					<td class="icon" >
						<a href="Admin-Results-Edit-Opponent-<?= $opponent['id'] ?>" >
							<img src="include/img/icon/edit.gif" alt="Edit" />
						</a>
					</td>
					<td class="icon" >
						<a href="Admin-Results-Delete-Opponent-<?= $opponent['id'] ?>" >
							<img src="include/img/icon/delete.png" alt="Edit" />
						</a>
					</td>
				</tr>
			<?php } ?>
		</table>
		<br />
		<a href="#add_new_opponent" class="fancybox" >Add opponent</a>
		<div id="add_new_opponent" class="popup" >
			<?php include('results/add_opponent_embedded.php'); ?>
		</div>
	</div>
	<div class="admin bg4" >
		<h2>MATCHES</h2>
		<a href="#add_new_match" class="fancybox" >Add match</a><br /><br />
		<table>
			<?php foreach($matches as $match) { ?>
				<tr>
					<td class="td_admin_matches"><?= dispImg($games[$match['game_id']]['icon_id']) ?> <?= $match['date'] ?></td>
					<td class="td_admin_matches"><?= $competitions[$match['competition_id']]['name'] ?></td>
					<td class="td_admin_matches"><?= dispFlag($opponents[$match['opponent_id']]['flag_id']) ?> <?= $opponents[$match['opponent_id']]['name'] ?></td>
					<td class="td_admin_matches"><?= $match['score_phase'] ?> - <?= $match['score_opponent'] ?></td>
					<td class="td_admin_matches"><?php if(!empty($match['matchlink'])) { ?><a href="<?= $match['matchlink'] ?>">matchlink</a><?php } ?></td>
					<td class="icon" >
						<a href="Admin-Results-Edit-Match-<?= $match['id'] ?>" >
							<img src="include/img/icon/edit.gif" alt="Edit" />
						</a>
					</td>
					<td class="icon" >
						<a href="Admin-Results-Delete-Match-<?= $match['id'] ?>" >
							<img src="include/img/icon/delete.png" alt="Edit" />
						</a>
					</td>
				</tr>
			<?php } ?>
		</table>
		<br />
		<a href="#add_new_match" class="fancybox" >Add match</a>
		<div id="add_new_match" class="popup" >
			<?php include('results/add_match_embedded.php'); ?>
		</div>
	</div>
</div>
<?php
	include($url."footer.php");
?>
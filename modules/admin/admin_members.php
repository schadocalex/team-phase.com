<?php
	$url = '../../';
	include($url."include.php");

	$user->accessRight('ADMIN_MEMBERS');

	$users2 = Select::all('user');

	include($url."header.php");
	include("menu.php");
?>

<div id="admin_members" >
	<?php showMessages(true); ?>
	<div class="admin bg4" >
		<h2>MEMBERS</h2>
		<table>
			<tr>
				<th style="width:5%" >Id</th>
				<th style="width:20%" >Pseudo</th>
				<th style="width:10%" >IP</th>
				<th style="width:30%" >Email</th>
				<th style="width:15%" >Last visit</th>
				<th style="width:10%" >Activate</th>
				<th style="width:10%" >Login With</th>
			</tr>
		<?php
			usort($users2, function ($a, $b) {
				return strcasecmp($a["username"], $b["username"]);
			});
			foreach ($users2 as $u) {
				echo 
				'<tr>
					<td>'.$u['id'].'</td>
					<td>
						<a href="Profile-'.$u['id'].'-'.getCanonical($u['username']).'">
							' . dispFlag($u['country_id']) . ' '. $u['username'] . '
						</a>
					</td>
					<td>'.$u['ip'].'</td>
					<td>'.$u['email'].'</td>
					<td>'.$u['last_visit'].'</td>
				';
				if($u['enabled'] == 1)
					echo '<td></td>';
				else
					echo '<td><a href="Registration-Confirm-'.$u['confirmation_token'].'" >Activate</a></td>';

				if($u['rank'] >= 3)
					echo '<td></td>';
				else
					echo '<td><a href="Login-With-'.$u['id'].'" >Login</a></td>';

				echo '</tr>';
			}
		?>
		</table>
	</div>
</div>
<?php
	include($url."footer.php");
?>
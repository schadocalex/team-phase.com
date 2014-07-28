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
				<th>Id</th>
				<th>Pseudo</th>
				<th>IP</th>
				<th>Email</th>
				<th></th>
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
					<td><a href="Login-With-'.$u['id'].'" >Login with</a></td>
				</tr>';
			}
		?>
		</table>
	</div>
</div>
<?php
	include($url."footer.php");
?>
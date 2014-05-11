<?php
	$url = '../../';
	include($url."include.php");

	$user->accessRight('ADMIN');

	include($url."header.php");
	include("menu.php");
?>

<div id="admin_menu" >
	<div class="admin bg4" >
		<h2>WELCOME TO THE CONTROL PANEL!</h2>
	</div>
</div>
<?php
	include($url."footer.php");
?>
<?php
	$url= '../../';
	include($url . 'include.php');

	$id = @$_GET['id_profile'];
	if(!MySQL::exist('user', 'id', $id))
		$user->redirect('Home');
	
	include($url."header.php");
?>
	<div class="bg4" >
		<p style="line-height:25px;">
			<?php 
				showMessages();

				$user_profile = new User($id);
				echo 'Username: '.$user_profile->username() . '<br />';
				if($user->id == $id)
					echo 'Email : '.$user->email .' (not shown to others)<br />';
				if(!empty($user_profile->name))
					echo 'Name: '.$user_profile->name .'<br />';
				if(!empty($user_profile->age))
					echo 'Age: ' . $user_profile->age. '<br />';
				if(!empty($user_profile->city))
					echo 'City: ' . $user_profile->city. '<br />';
			?>
			
		</p>
	</div>
<?php
	include($url."footer.php");
?>
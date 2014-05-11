<?php
	function showMessages($block = false)
	{
		if(!empty($_SESSION['error']))
		{
			$msgs = '<p style="color:red;" >'.$_SESSION['error'].'</p>';
			unset($_SESSION['error']);
		}
		if(!empty($_SESSION['success']))
		{
			$msgs = '<p style="color:green;" >'.$_SESSION['success'].'</p>';
			unset($_SESSION['success']);
		}

		if(!empty($msgs))
		{
			if($block)
				echo '<div class="bg4" >'.$msgs.'</div>';
			else
				echo $msgs;
		}
	}
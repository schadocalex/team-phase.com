<?php
	$url = '../../';
	include($url."include.php");
	include($url."header.php");
?>
<div id="gallery" >
	<div class="bg4" >
		<object style="position:relative;left:-6px;" bgcolor='#000000' 
					data='http://www.twitch.tv/widgets/archive_embed_player.swf' 
					height='527' 
					id='clip_embed_player_flash' 
					type='application/x-shockwave-flash' 
					width='870'> 
			  <param  name='movie' 
					  value='http://www.twitch.tv/widgets/archive_embed_player.swf' /> 
			  <param  name='allowScriptAccess' 
					  value='always' /> 
			  <param  name='allowNetworking' 
					  value='all' /> 
			  <param  name='allowFullScreen' 
					  value='true' /> 
			  <param  name='flashvars' 
					  value='channel=teamphase' />
			</object>
	</div>
</div>
<?php
	include($url."footer.php");
?>
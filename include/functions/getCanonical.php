<?php

	function getCanonical($s)
	{
		$s = utf8_decode(trim($s));
		$s = strtr($s,
			utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'),
						'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
		$s = preg_replace("#[^a-zA-Z0-9]#", "-", $s);
		$s = preg_replace('#-{2,}#','-',$s); 
		$s = preg_replace('#-$#','',$s); 
		$s = preg_replace('#^-#','',$s);

		return $s;
	}

?>
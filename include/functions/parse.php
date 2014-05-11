<?php
	function parse($content)
	{
		$content = nl2br($content);

		$flags = Select::all('flag');
		$search  = array(); 
		$replace = array(); 

		foreach ($flags as $flag) {
			$search[] = '[['.$flag['name'].']]';
			$replace[] = dispFlag($flag['id']);
		}

		$content = str_replace($search, $replace, $content);
		return $content;
	}
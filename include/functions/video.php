<?php
	function getIdYoutube($url)
	{
		parse_str(parse_url($url, PHP_URL_QUERY), $params);
		return @$params['v']; 
	}

	function srcImgYoutube($id)
	{
		return 'http://i.ytimg.com/vi/'.$id.'/0.jpg';
	}
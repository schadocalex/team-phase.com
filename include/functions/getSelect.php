<?php
	function getSelectOrder($table)
	{
		if($table == 'match2')
			return 'ORDER BY date DESC, id DESC';
		if($table == 'picture')
			return 'ORDER BY date DESC, id DESC';
		if($table == 'video')
			return 'ORDER BY date DESC, id DESC';
		if($table == 'news')
			return 'ORDER BY date DESC';
		if($table == 'comment')
			return 'ORDER BY date DESC';
		if($table == 'opponent')
			return 'ORDER BY name ASC';
		if($table == 'competition')
			return 'ORDER BY name ASC';
		if($table == 'upcoming_match')
			return 'ORDER BY id DESC';

		return '';
	}
	function getSelectWhat($table)
	{
		if($table == 'user')
			return 'id,email,username,rank,country_id,ip,last_visit,enabled,confirmation_token';

		return '*';
	}
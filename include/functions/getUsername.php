<?php
	function getUsername($u)
	{
		return '<a href="Profile-'.$u['id'].'-'.getCanonical($u['username']).'">
				' . dispFlag($u['country_id']) . ' '. $u['username'] . '
				</a>';
	}
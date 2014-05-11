<?php
	function srcFlag($id)
	{
		$flag = Select::withId('flag', $id);
		return 'include/img/flag/' . $flag['name'] . '.png';
	}
	function dispFlag($id)
	{
		return '<img class="flag" src="'.srcFlag($id).'" />';
	}

	function srcImg($id)
	{
		$img = Select::withId('image', $id);
		return 'include/img/upload/' . $img['url'];
	}
	function dispImg($id, $class = '')
	{
		return '<img src="'.srcImg($id).'" class="'.$class.'" />';
	}
	function srcImgMin($id)
	{
		$img = Select::withId('image', $id);
		return 'include/img/upload/' . $img['url_min'];
	}
	function dispImgMin($id, $class = '')
	{
		return '<img src="'.srcImg($id).'" class="'.$class.'" />';
	}

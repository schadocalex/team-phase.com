<?php
	function encodePasswordSf2($password, $salt)
	{
		$salted = $password.'{'.$salt.'}';
		$digest = hash('sha512', $salted, true);

		for ($i = 1; $i < 5000; $i++) {
			$digest = hash('sha512', $digest.$salted, true);
		}

		return base64_encode($digest);
	}
?>
<?php

require_once "lib/base.php";


	# Check to see if the uname and password works out
	if (OC_User::checkPassword($uid, $pwd) !== false) {
		$params = array('uid' => $uid)
		
		OC_Hook::emit('DeactivateUser', 'post_deactivate', $params);
		OC_User::logout();
		header("Location: http://"  . \OCP\Config::getAppValue('multiinstance', 'ip') . "/owncloud/index.php");
	} 

?>

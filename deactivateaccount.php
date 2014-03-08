<?php


//namespace OC\Settings\DeactivateAccount;

//use OCA\AppFramework\Http\RedirectResponse as RedirectResponse;

//class Controller {
//        public static function deactivateAccount($args) {
                // Check if we are an user
                
//		\OC_JSON::callCheck();
//                \OC_JSON::checkLoggedIn();

//                // Manually load apps to ensure hooks work correctly (workaround for issue 1503)
//                OC_App::loadApps();
//
//                $uid = OC_User::getUser();

//		$params = array('uid' => $uid)
//        	OC_Hook::emit('DeactivateUser', 'post_deactivate', $params);
		OC_User::logout();
       	 	//\OC_User::getLogoutAttribute();
        	header("Location: http://128.111.52.151/owncloud/?logout=true");
		//return new RedirectResponse($uri);
		exit;
//        }
//}

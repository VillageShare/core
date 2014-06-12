<?php



class OC_Sync {
/**
         * @var \OC\Group\Manager $manager
         */
        private static $manager;

        /**
         * @var \OC\User\Manager
         */
        private static $userManager;

        /**
         * @return \OC\Group\Manager
         */
        public static function getManager() {
                if (self::$manager) {
                        return self::$manager;
                }
                self::$userManager = \OC_User::getManager();
                self::$manager = new \OC\Sync\Manager(self::$userManager);
                return self::$manager;
        }

	public static function keepInSync($uid, $filepath) {
                if (self::getManager()->keepInSync($uid, $filepath)) {
                        return true;
                } else {
                        return false;
                }
        }

	public static function removeFromSync($uid, $filepath) {
		if (self::getManager()->removeFromSync($uid, $filepath)) {
                        return true;
                } else {
                        return false;
                }
	}
}

?>

<?php

namespace OC\Sync;

use OC\Hooks\PublicEmitter;

/**
 * Class Manager
 *
 * Hooks available in scope \OC\Group:
 * - preAddUser(\OC\Group\Group $group, \OC\User\User $user)
 * - postAddUser(\OC\Group\Group $group, \OC\User\User $user)
 * - preRemoveUser(\OC\Group\Group $group, \OC\User\User $user)
 * - postRemoveUser(\OC\Group\Group $group, \OC\User\User $user)
 * - preDelete(\OC\Group\Group $group)
 * - postDelete(\OC\Group\Group $group)
 * - preCreate(string $groupId)
 * - postCreate(\OC\Group\Group $group)
 *
 * @package OC\Group
 */
class Manager extends PublicEmitter {
	/**
         * @var \OC_Group_Backend[] | \OC_Group_Database[] $backends
         */
        private $backends = array();

        /**
         * @var \OC\User\Manager $userManager
         */
        private $userManager;

        /**
         * @var \OC\Group\Group[]
         */
        private $cachedGroups;

        /**
         * @param \OC\User\Manager $userManager
         */
        public function __construct($userManager) {
                $this->userManager = $userManager;
                $cache = & $this->cachedGroups;
                $this->listen('\OC\Group', 'postDelete', function ($group) use (&$cache) {
                        /**
                         * @var \OC\Group\Group $group
                         */
                        unset($cache[$group->getGID()]);
                });
        }


}

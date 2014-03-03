<?php
/**
*Modified user registration for android client
*
*/
require_once "lib/base.php";

$gid = $_POST["gid"];
$uid = $_POST["uid"];
$myuid = $_POST["myuid"];
$pwd = $_POST["password"];
$action = $_POST["action"];

//$data;
$error=array();
$params=array();
if(is_null($uid)) {
        $error[] = "NULL_UID";
} else if(is_null($gid)) {
	$error[] = "NULL_GID";
} else if(is_null($action)) {
	$error[] = "NULL_ACTION";
} else if(is_null($myuid)) {
	$error[] = "NULL_MYUID";
} else if(is_null($pwd)) {
	$error[] = "NULL_PASSWORD";
} else if(OC_User::checkPassword($myuid, $pwd) == false) {
	$error[] = "INVALID_PASSWORD";
} else {

		// Action: CREATE_GROUP
		if $action === "CREATE_GROUP" {
			try {
				if (!OC_Group::groupExists($gid)) {
					OC_Group::createGroup($gid);
					OC_Group::addToGroup($uid, $gid);
				} else {
					// Throw already exists exception
					throw new Exception("Group already exists.");
				}

			} catch(Exception $exception) {
				$error[] = $exception->getMessage();
                                if($exception->getMessage() === "Group already exists") {

                                        $params = array('reply' => "GROUP_ALREADY_EXISTS");
                                }
                                echo json_encode($params);
			}

		}

		// Action: REQUEST_GROUP
                /*if $action === "REQUEST_GROUP" {
                        try {


                        } catch(Exception $exception) {

                        }

                }
		*/

		// Action: LEAVE_GROUP
		/*
                if $action === "LEAVE_GROUP" {
                        try {
				if (OC_Group::inGroup($uid, $gid) {
					OC_Group::removeFromGroup($uid, $gid);
				} else {
					throw new Exception("User not in group");
				}

                        } catch(Exception $exception) {
				$error[] = $exception->getMessage();
                                if($exception->getMessage() === "User not in group") {

                                        $params = array('reply' => "USER_NOT_IN_GROUP");
                                }
                                echo json_encode($params);
                        }

                }
		*/

		/* SubAdmin functions ONLY */

		// Action: DELETE_GROUP
                if $action === "DELETE_GROUP" {
                        try {
				$subadmins = OC_SubAdmin::getGroupsSubAdmins($gid);
				if (in_array($myuid, $subadmins)) {
					OC_Group::deleteGroup($gid);
				} else {
					throw new Exception("Permission denied");
				}
                        } catch(Exception $exception) {
				$error[] = $exception->getMessage();
                        	if($exception->getMessage() === "Permission denied") {

                                	$params = array('reply' => "PERMISSION_DENIED");
				}
				echo json_encode($params);
                        }

                }

		// Action: Add to group
                if $action === "ADD_USER_TO_GROUP" {
                        try {
                                $subadmins = OC_SubAdmin::getGroupsSubAdmins($gid);
                                if (in_array($myuid, $subadmins)) {
					if (!OC_Group::inGroup($uid, $gid)) {
                                        	OC_Group::addToGroup($uid, $gid);
					} else{
						throw new Exception("User not in group");
					}
                                } else {
                                        throw new Exception("Permission denied");
                                }
                        } catch(Exception $exception) {
                                $error[] = $exception->getMessage();
                                if($exception->getMessage() === "Permission denied") {

                                        $params = array('reply' => "PERMISSION_DENIED");
                                } 
				if ($exception->getMessage() === "User not in group") {
					$params = array('reply' => "USER_NOT_IN_GROUP");
				}
                                echo json_encode($params);
                        }
                }

		// Action: Delete from group
                if $action === "DELETE_USER_FROM_GROUP" {
                        try {
                                $subadmins = OC_SubAdmin::getGroupsSubAdmins($gid);
                                if (in_array($myuid, $subadmins)) {
					if (!OC_Group::inGroup($uid, $gid)) {
                                                OC_Group::removeFromGroup($uid, $gid);
                                        } else{
                                                throw new Exception("User not in group");
                                        }
                                } else {
                                        throw new Exception("Permission denied");
                                }
                        } catch(Exception $exception) {
                                $error[] = $exception->getMessage();
                                if($exception->getMessage() === "Permission denied") {

                                        $params = array('reply' => "PERMISSION_DENIED");
                                }
				if ($exception->getMessage() === "User not in group") {
                                        $params = array('reply' => "USER_NOT_IN_GROUP");
                                }
                                echo json_encode($params);
                        }
		}
		
		// Action: Delete from group
                /*if $action === "GET_GROUP_REQUESTS" {
                        try {
				// TODO: Get group requests given an admin ID
                        } catch(Exception $exception) {
                                $error[] = $exception->getMessage();
                                if($exception->getMessage() === "Permission denied") {

                                        $params = array('reply' => "PERMISSION_DENIED");
                                }
                                if ($exception->getMessage() === "User not in group") {
                                        $params = array('reply' => "USER_NOT_IN_GROUP");
                                }
                                echo json_encode($params);
                        }
                }
		*/
		
	}		
if(count($error) == 0) {
        $params = array('reply' => "ACTION_EXECUTED",'location' => $uid_location);
        echo json_encode($params);
} else {
        $params = array('reply' => "ERROR");
        echo json_encode($params);
}
?>

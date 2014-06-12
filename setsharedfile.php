<?php
/**
*Share script for sharing with android phone
*VillageShare project
*Smruthi Manjunath
*Jane Iedemska
*/

require_once "lib/base.php";

$uid_owner = $_POST["name_location"];
$item_type = $_POST["file_type"];
$file_id = $_POST["server_id"];
$share_with_friends = $_POST["friends"];
$share_with_groups = $_POST["groups"];
$permissions = OCP\PERMISSION_READ;	//shared files can only be read

$debug = sprintf("echo \"%s \" >> /home/owncloud/public_html/owncloud/setsharedfile.log", $uid_owner);
shell_exec($debug);

$debug = sprintf("echo \"%s \" >> /home/owncloud/public_html/owncloud/setsharedfile.log", $item_type);
shell_exec($debug);
$debug = sprintf("echo \"%s \" >> /home/owncloud/public_html/owncloud/setsharedfile.log", $file_id);
shell_exec($debug);

$retval = array();

foreach ($share_with_friends as $friend) {
$debug = sprintf("echo \"%s \" >> /home/owncloud/public_html/owncloud/setsharedfile.log", $friend);
shell_exec($debug);
}

foreach ($share_with_groups as $group) {
$debug = sprintf("echo \"%s \" >> /home/owncloud/public_html/owncloud/setsharedfile.log", $group);
shell_exec($debug);
}


//????
OC_User::setUserId($uid_owner);
error_log("user set");
//we need to setup the filesystem for the user, otherwise OC_FileSystem::getRoot will fail and break
OC_Util::setupFS($uid_owner);
error_log("file system set");

foreach ($share_with_friends as $friend ){
	error_log("enter loop");
	
	try {       		
		OCP\Share::shareItem($item_type, $file_id, OCP\Share::SHARE_TYPE_USER, $friend, $permissions,$uid_owner);
		error_log("Sharing".$file_id."file");
			
	} catch(Exception $e) {
		error_log("share failed");
        	$update_error = true;
                error_log(" ".$share_type." ".$friend." ".$permissions." ".$uid_owner);
                error_log($e->getMessage());
                OCP\Util::writeLog('files_sharing',
                                        'Upgrade Routine: Skipping sharing " to "'.$friend
                                        .'" (error is "'.$e->getMessage().'")',
                                        OCP\Util::WARN);
		$retval["status"] = "false";
                $retval["error_typ"] = "SHARE_FAILED";
                $retval["name"] = $friend;
		echo json_encode($retval);
		break;
	}
}
foreach ($share_with_groups as $group ){
	error_log("enter loop");	

	//execute share
	try {
       		
		OCP\Share::shareItem($item_type, $file_id, OCP\Share::SHARE_TYPE_GROUP, $group, $permissions,$uid_owner);
		error_log("Sharing".$file_id."file");
			
	} catch(Exception $e) {
		error_log("share failed");
        	$update_error = true;
                error_log(" ".$share_type." ".$friend." ".$permissions." ".$uid_owner);
                error_log($e->getMessage());
                OCP\Util::writeLog('files_sharing',
                                        'Upgrade Routine: Skipping sharing " to "'.$friend
                                        .'" (error is "'.$e->getMessage().'")',
                                        OCP\Util::WARN);
		$retval["status"] = "fail";
                $retval["error_typ"] = "SHARE_FAILED";
                $retval["name"] = $friend;
		echo json_encode($retval);
		break;
	}
}	
error_log("Shared".$file_id."file");
$retval["status"] = "success";
echo json_encode($retval);

?>

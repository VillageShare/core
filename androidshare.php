<?php
/**
*Share script for sharing with android phone
*VillageShare project
*Smruthi Manjunath
*Jane Iedemska
*/
require_once "lib/base.php";

$uid_owner = $_POST["name_location"];


//decoding json_string from the raw input string, POST won't populate
$var_dump = json_decode(file_get_contents("php://input"));
$uid_owner = $var_dump->uidOwner;
$itemType = $var_dump->itemType;	//file or folder
$itemSource = $var_dump->itemSource;	//path to the file
$shareType = $var_dump->shareType;	//group, user or link
$toShareWith = $var_dump->toShareWith; 	//array of with whom to share
//$uid_owner = $var_dump->uidOwner; 	//who is owner
$permissions = OCP\PERMISSION_READ;	//shared files can only be read by users


$retval = array();

error_log("test");
error_log((string)$itemType);
error_log((string)$itemSource);
error_log((string)$shareType);
error_log((string)$uid_owner);

foreach ($toShareWith as $i => $value) {
error_log((string)$i);
}

//get the file info from oc_filecache with path=itemSource
$stmt = OC_DB::prepare( "SELECT `fileid` FROM `*PREFIX*filecache` WHERE `path` = ?" );
$result = $stmt->execute( array( $itemSource ));

//get file id
$row = $result->fetchRow();
if($row)
{
        error_log("file found");
        $fileId = $row['fileid'];
} else
{
        error_log("No such file ".$itemSource." exists");
	$retval["SHARE_STATUS"] = "false";
        $retval["ERROR_TYPE"] = "NO_SUCH_FILE";   
        echo json_encode($retval);
}


OC_User::setUserId($uid_owner);
error_log("user set");
//we need to setup the filesystem for the user, otherwise OC_FileSystem::getRoot will fail and break
OC_Util::setupFS($uid_owner);
error_log("file system set");
foreach ($toShareWith as $i => $value) {
error_log((string)$i);
}

foreach ($toShareWith as $shareWith => $shareType ){
	error_log("enter loop");
	//set sharetype
	switch($shareType)
	{
        	case "0": $shareType = OCP\Share::SHARE_TYPE_USER; error_log("usertype"); break;
        	case "1": $shareType = OCP\Share::SHARE_TYPE_GROUP; break;
      		case "3": $shareType = OCP\Share::SHARE_TYPE_LINK; break;
        	case "5": $shareType = OCP\Share::SHARE_TYPE_CONTACT; break;
        	case "6": $shareType = OCP\Share::SHARE_TYPE_REMOTE; break;
        	default: {
			error_log("bad sharetype".$shareType);
			$retval["SHARE_STATUS"] = "false";
			$retval["ERROR_TYPE"] = "INVALID_SHARETYPE";
			$retval["NAME"] = $shareWith;	
                	echo json_encode($retval);
			break;
                }
	}
	//execute share
	try {
       		
		OCP\Share::shareItem($itemType, $fileId, $shareType, $shareWith, $permissions,$uid_owner);
		error_log("Sharing".$fileId."file");
			
	} catch(Exception $e) {
		error_log("share failed");
        	$update_error = true;
                error_log($itemType." ".$itemSource." ".$shareType." ".$shareWith." ".$permissions." ".$uid_owner);
                error_log($e->getMessage());
                OCP\Util::writeLog('files_sharing',
                                        'Upgrade Routine: Skipping sharing " to "'.$shareWith
                                        .'" (error is "'.$e->getMessage().'")',
                                        OCP\Util::WARN);
		$retval["SHARE_STATUS"] = "false";
                $retval["ERROR_TYPE"] = "SHARE_FAILED";
                $retval["NAME"] = $shareWith;
		echo json_encode($retval);
		break;
	}
		
error_log("Shared".$fileId."file");
$retval["SHARE_STATUS"] = "true";
echo json_encode($retval);
}
?>

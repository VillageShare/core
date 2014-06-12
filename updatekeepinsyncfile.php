<?php
/**
*Modified user registration for android client
*
*/
require_once "lib/base.php";

shell_exec("echo \"Script Running\" >> /home/owncloud/public_html/owncloud/updatekeepinsync.log");

$owner_location = $_POST["name_location"]; //user that is making this request
$server_id = $_POST["server_id"];	// file server id 
$state = $_POST["new_state"]; 	//new state of the file


//$data;
$error=array();
$params=array();

if(is_null($owner_location)) {
        $error[] = "NULL_UID";
	shell_exec("echo \"owner location is null\" >> /home/owncloud/public_html/owncloud/updatekeepinsync.log");
} else if(is_null($server_id)) {
	$error[] = "NULL_LOCATION";
	shell_exec("echo \"server_id is null\" >> /home/owncloud/public_html/owncloud/updatekeepinsync.log");
} else if(is_null($pathname)) {
	$error[] = "NULL_PATH";
	shell_exec("echo \"pathname is null\" >> /home/owncloud/public_html/owncloud/updatekeepinsync.log");
#} else if(is_null($pwd)) {
#	$error[] = "NULL_PASSWORD";
} else if(is_null($state)) {
	$error[] = "NULL_STATE";
	shell_exec("echo \"state is null\" >> /home/owncloud/public_html/owncloud/updatekeepinsync.log");
#} else if(OC_User::checkPassword($myuid, $pwd) == false) {
#	$error[] = "INVALID_PASSWORD";
} else {
		$con=mysqli_connect("localhost","owncloud","owncloud","owncloud");
		// Check connection
		if (mysqli_connect_errno()) {
			shell_exec("echo \"Cannot connect to MYSQL database\" >> /home/owncloud/public_html/owncloud/updatekeepinsync.log");
			return;
		}
		shell_exec("echo \"Successful connection to MYSQL database\" >> /home/owncloud/public_html/owncloud/updatekeepinsync.log");

		// Action: CREATE_GROUP
		if ($state === "TRUE") {
			try {
				// TODO check to see if file is already in sync
				shell_exec("echo \"Action is TRUE\" >> /home/owncloud/public_html/owncloud/updatekeepinsync.log");

				$sql="SELECT `uid` FROM `oc_multiinstance_sync_files` WHERE `uid`='morgan', `path`='morgan'";
				$result = mysqli_query($con,$sql);
				shell_exec("echo \"Executed MYSQL query\" >> /home/owncloud/public_html/owncloud/updatekeepinsync.log");
				while($row = mysqli_fetch_array($result)) {
					if (empty($row)) {
                                        	shell_exec("echo \"Entry does not exist in the database\" >> /home/owncloud/public_html/owncloud/updatekeepinsync.log");
                                        	$sql="INSERT INTO `oc_multiinstance_sync_files` (`uid`, `path`) VALUES ('morgan', 'morgan')";
                                        	if (!mysqli_query($con,$sql){
                                                	shell_exec("echo \"Error executing SQL entry\" >> /home/owncloud/public_html/owncloud/updatekeepinsync.log");
                                       	 	}
                                        	shell_exec("echo \"Successfully executed query\" >> /home/owncloud/public_html/owncloud/updatekeepinsync.log");
                                	}
				}

			} catch(Exception $exception) {
				// TODO pass silently
			}
		} else if($state === "FALSE") {
			try {
				shell_exec("echo \"Action is FALSE\" >> /home/owncloud/public_html/owncloud/updatekeepinsync.log");

		//		$sql="SELECT `uid` FROM `oc_multiinstance_sync_files` WHERE `uid`='$uid', `path`='$pathname'";
                //                $result = mysqli_query($con,$sql);
                 //               if($row = mysqli_fetch_array($result)) {
                //                        $sql="DELETE FROM `oc_multiinstance_sync_files` (`uid`, `path`) VALUES ('$uid', '$pathname')";
                 //                       if (!mysqli_query($con,$sql){
                 //                               shell_exec("echo \"Error executing SQL entry\" >> /home/owncloud/public_html/owncloud/updatekeepinsync.log");
                 //                       }
                 //               }

			} catch(Exception $exception) {

			}
		}

		mysqli_close($con);
		
}		
if(count($error) == 0) {
        $params = array('reply' => "ACTION_EXECUTED",'location' => $location);
        echo json_encode($params);
} else {
	shell_exec("echo \"ERROR\" >> /home/owncloud/public_html/owncloud/updatekeepinsync.log");
        $params = array('reply' => "ERROR");
        echo json_encode($params);
}

?>

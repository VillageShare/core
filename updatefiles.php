<?php
/**
*Modified user registration for android client
*
*/
require_once "lib/base.php";

shell_exec("echo \"Script Running\" >> /home/owncloud/public_html/owncloud/updatefiles.log");

$uid = $_POST["uid"];
#$pwd = $_POST["password"];
$location = $_POST["location"];
$pathname = $_POST["pathname"];
$state = $_POST["state"];


//$data;
$error=array();
$params=array();
if(is_null($uid)) {
        $error[] = "NULL_UID";
	shell_exec("echo \"uid is null\" >> /home/owncloud/public_html/owncloud/updatefiles.log");
} else if(is_null($location)) {
	$error[] = "NULL_LOCATION";
	shell_exec("echo \"location is null\" >> /home/owncloud/public_html/owncloud/updatefiles.log");
} else if(is_null($pathname)) {
	$error[] = "NULL_PATH";
	shell_exec("echo \"pathname is null\" >> /home/owncloud/public_html/owncloud/updatefiles.log");
#} else if(is_null($pwd)) {
#	$error[] = "NULL_PASSWORD";
} else if(is_null($state)) {
	$error[] = "NULL_STATE";
	shell_exec("echo \"state is null\" >> /home/owncloud/public_html/owncloud/updatefiles.log");
#} else if(OC_User::checkPassword($myuid, $pwd) == false) {
#	$error[] = "INVALID_PASSWORD";
} else {
		$con=mysqli_connect("localhost","owncloud","owncloud","owncloud");
		// Check connection
		if (mysqli_connect_errno()) {
			shell_exec("echo \"Cannot connect to MYSQL database\" >> /home/owncloud/public_html/owncloud/updatefiles.log");
			return;
		}
		shell_exec("echo \"Successful connection to MYSQL database\" >> /home/owncloud/public_html/owncloud/updatefiles.log");

		// Action: CREATE_GROUP
		if ($state === "TRUE") {
			try {
				// TODO check to see if file is already in sync
				shell_exec("echo \"Action is TRUE\" >> /home/owncloud/public_html/owncloud/updatefiles.log");

				$sql="SELECT `uid` FROM `oc_multiinstance_sync_files` WHERE `uid`='morgan', `path`='morgan'";
				$result = mysqli_query($con,$sql);
				shell_exec("echo \"Executed MYSQL query\" >> /home/owncloud/public_html/owncloud/updatefiles.log");
				while($row = mysqli_fetch_array($result)) {
					if (empty($row)) {
                                        	shell_exec("echo \"Entry does not exist in the database\" >> /home/owncloud/public_html/owncloud/updatefiles.log");
                                        	$sql="INSERT INTO `oc_multiinstance_sync_files` (`uid`, `path`) VALUES ('morgan', 'morgan')";
                                        	if (!mysqli_query($con,$sql){
                                                	shell_exec("echo \"Error executing SQL entry\" >> /home/owncloud/public_html/owncloud/updatefiles.log");
                                       	 	}
                                        	shell_exec("echo \"Successfully executed query\" >> /home/owncloud/public_html/owncloud/updatefiles.log");
                                	}
				}

			} catch(Exception $exception) {
				// TODO pass silently
			}
		} else if($state === "FALSE") {
			try {
				shell_exec("echo \"Action is FALSE\" >> /home/owncloud/public_html/owncloud/updatefiles.log");

		//		$sql="SELECT `uid` FROM `oc_multiinstance_sync_files` WHERE `uid`='$uid', `path`='$pathname'";
                //                $result = mysqli_query($con,$sql);
                 //               if($row = mysqli_fetch_array($result)) {
                //                        $sql="DELETE FROM `oc_multiinstance_sync_files` (`uid`, `path`) VALUES ('$uid', '$pathname')";
                 //                       if (!mysqli_query($con,$sql){
                 //                               shell_exec("echo \"Error executing SQL entry\" >> /home/owncloud/public_html/owncloud/updatefiles.log");
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
	shell_exec("echo \"ERROR\" >> /home/owncloud/public_html/owncloud/updatefiles.log");
        $params = array('reply' => "ERROR");
        echo json_encode($params);
}

?>

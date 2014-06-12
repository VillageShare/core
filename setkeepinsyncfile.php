<?php
/**
* Updates files that should be kept in sync
* Jane Iedemska
*/
require_once "lib/base.php";

shell_exec("echo \"Script Running\" >> /home/owncloud/public_html/owncloud/setkeepinsync.log");

$owner_location = $_POST["name_location"]; //user that is making this request
$server_id = $_POST["server_id"];	// file server id 
$state = $_POST["state"]; 	//new state of the file
$owner = $_POST["file_owner"]; //who owns the file to be synced

$error=array();
$params=array();

$debug = sprintf("echo \" %s %s %s %s\" >> /home/owncloud/public_html/owncloud/setkeepinsync.log",$server_id, $state, $owner, $owner_location);
shell_exec($debug);

if(is_null($owner_location)) {
        $error[] = "NULL_UID";
	shell_exec("echo \"owner location is null\" >> /home/owncloud/public_html/owncloud/setkeepinsync.log");
} else if(is_null($server_id)) {
	$error[] = "NULL_LOCATION";
	shell_exec("echo \"server_id is null\" >> /home/owncloud/public_html/owncloud/setkeepinsync.log");
} else if(is_null($state)) {
	$error[] = "NULL_STATE";
	shell_exec("echo \"state is null\" >> /home/owncloud/public_html/owncloud/setkeepinsync.log");
} else if(is_null($owner)) {
	$error[] = "NULL_OWNER";
	shell_exec("echo \"owner is null\" >> /home/owncloud/public_html/owncloud/setkeepinsync.log");
}else {
		$con=mysqli_connect("localhost","owncloud","owncloud","owncloud");
		// Check connection
		if (mysqli_connect_errno()) {
			shell_exec("echo \"Cannot connect to MYSQL database\" >> /home/owncloud/public_html/owncloud/setkeepinsync.log");
			return;
		}
		shell_exec("echo \"Successful connection to MYSQL database\" >> /home/owncloud/public_html/owncloud/setkeepinsync.log");

		//get original path from filecache
		$sql=sprintf("SELECT path FROM oc_filecache WHERE fileid ='%s'", $server_id);
		
		if(!($result = mysqli_query($con,$sql))){
        		shell_exec("echo \"Query has failed\" >> /home/owncloud/public_html/owncloud/setkeepinsync.log");
		}
			
		if (mysqli_num_rows($result) == 0){ //no such file
				//TODO return error
			shell_exec("echo \"Cannot find the file in the database\" >> /home/owncloud/public_html/owncloud/setkeepinsync.log");
		}	
			
		$row = mysqli_fetch_assoc($result); 
		$path = $owner.$row["path"]; ///file/[path]
	if ($state === "TRUE") {
			try {
				//check to see if file is already in sync
				shell_exec("echo \"Action is TRUE\" >> /home/owncloud/public_html/owncloud/setkeepinsync.log");

				$sql=sprintf("SELECT * FROM oc_multiinstance_sync_files WHERE uid= '%s', fileid ='%s'", $name_location, $servr_id);
				$result = mysqli_query($con,$sql);
				if(mysqli_num_rows($result) == 0){ //there is no entry and file is not kept in sync	
                                       	$sql= sprintf("INSERT INTO oc_multiinstance_sync_files (uid, path, fileid) VALUES ( '%s', '%s', '%s')",
								$owner_location, $path, $server_id);
					shell_exec("echo \"Executed MYSQL query\" >> /home/owncloud/public_html/owncloud/setkeepinsync.log");
					if (!mysqli_query($con,$sql)){
                                                shell_exec("echo \"Error executing SQL entry\" >> /home/owncloud/public_html/owncloud/setkeepinsync.log");
                                       	}
				} //else file is already in sync

			} catch(Exception $exception) {
				// pass silently
			}
		} else if($state === "FALSE") {
			try {
				shell_exec("echo \"Action is FALSE\" >> /home/owncloud/public_html/owncloud/setkeepinsync.log");
				//check if file is present
				$sql=sprintf("SELECT uid FROM oc_multiinstance_sync_files WHERE uid='%s' and fileid ='%s'", $owner_location, $server_id);
                                $result = mysqli_query($con,$sql);
				//debug	
                                $debug = sprintf("echo \"There are %d rows\" >> /home/owncloud/public_html/owncloud/setkeepinsync.log",mysqli_num_rows($result));
				shell_exec($debug);
				shell_exec("echo \"Point 1\" >> /home/owncloud/public_html/owncloud/setkeepinsync.log");
				if(mysqli_num_rows($result) > 0){ //there is an entry and file is  kept in sync	
                                        $sql=sprintf("DELETE FROM oc_multiinstance_sync_files WHERE  uid = '%s' and fileid = '%s'",$owner_location, $server_id);
                                        $debug = sprintf("echo \"%s\" >> /home/owncloud/public_html/owncloud/setkeepinsync.log",$sql);
					shell_exec($debug);
                                        if (!mysqli_query($con,$sql)){
                                                shell_exec("echo \"Error executing SQL entry\" >> /home/owncloud/public_html/owncloud/setkeepinsync.log");
                                        }
                                }//else there is no entry and the file is not in sync	
				shell_exec("echo \"Point 2\" >> /home/owncloud/public_html/owncloud/setkeepinsync.log");

			} catch(Exception $exception) {

			}
		}

		mysqli_close($con);
		
}		
if(count($error) == 0) {
        $params = array("status" => "success",'location' => $location);
        echo json_encode($params);
} else {
	shell_exec("echo \"ERROR\" >> /home/owncloud/public_html/owncloud/setkeepinsync.log");
        $params = array("status" => "fail");
        echo json_encode($params);
}

?>

<?php
/**
*This file is used to mirror oc_multiinstance_sync_files table into client's "files"
*The script gets account information from simple POST request and returns
*the list of files that user wishes to keep in sync.
*Jane Iedemska
*/


require_once "lib/base.php";

$name_location = $_POST["name_location"]; //owner@node

if(is_null($name_location)) {
	shell_exec("echo \"POST is null\" >> /home/owncloud/public_html/owncloud/getkeepinsync.log");
	$retVal = array(array( "status" => "fail"), array("reason" => "empty post"));
	echo json_encode($retVal);
}

//Create and check connection
$con=mysqli_connect("localhost","owncloud","owncloud","owncloud");
if (mysqli_connect_errno()) {
	shell_exec("echo \"Cannot connect to MYSQL database\" >> /home/owncloud/public_html/owncloud/getkeepinsync.log");	
	$retVal = array(array( "status" => "fail"), array("reason" => "mySQL connection error"));
	echo json_encode($retVal);
}

// Query the server
$sql= sprintf("SELECT fileid FROM oc_multiinstance_sync_files WHERE uid = '%s'",$name_location);

if(!($result = mysqli_query($con,$sql))){
	shell_exec("echo \"Query has failed\" >> /home/owncloud/public_html/owncloud/getkeepinsync.log");
	$retVal = array(array( "status" => "fail"), array("reason" => "query error"));
	echo json_encode($retVal);
}

$retVal = array(array( "status" => "success"));
//Log record
$debug = sprintf("echo \"There are %d rows\" >> /home/owncloud/public_html/owncloud/getkeepinsync.log", mysqli_num_rows($result));
shell_exec($debug);

//Parsing the result
while($row = mysqli_fetch_assoc($result)) { //associative array
	$retVal[] = array(
		 		"server_id" => $row["fileid"],
                		);	
}

mysqli_close($con);
mysql_free_result($result);
echo json_encode($retVal);

?>


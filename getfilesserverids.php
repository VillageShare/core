<?php
/**
*This file is used to mirror serverid into the client's "file" table
*The script gets account information from simpl POST request and returns
*the list of server ids of files that are owned by user
 and shared specificaly with this user.
*Jane Iedemska
*/


require_once "lib/base.php";

$name_location = $_POST["name_location"]; //owner@node

if(is_null($name_location)) {
	shell_exec("echo \"POST is null\" >> /home/owncloud/public_html/owncloud/getserverids.log");
	$retVal = array(array( "status" => "fail"), array("reason" => "empty post"));
	echo json_encode($retVal);
}

$retVal = array(array( "status" => "success"));
//Create and check connection
$con=mysqli_connect("localhost","owncloud","owncloud","owncloud");
if (mysqli_connect_errno()) {
	shell_exec("echo \"Cannot connect to MYSQL database\" >> /home/owncloud/public_html/owncloud/getserverids.log");	
	$retVal = array(array( "status" => "fail"), array("reason" => "mySQL connection error"));
	echo json_encode($retVal);
}

//get files shared with the user
$sql= sprintf("SELECT item_source, file_target FROM oc_share WHERE share_with = '%s'",$name_location);

if(!($result = mysqli_query($con,$sql))){
	shell_exec("echo \"Query has failed\" >> /home/owncloud/public_html/owncloud/getserverids.log");
	$retVal = array(array( "status" => "fail"), array("reason" => " oc_share query error"));
	echo json_encode($retVal);
}

//log record
$debug = sprintf("echo \"There are %d rows in oc_share\" >> /home/owncloud/public_html/owncloud/getserverids.log", mysqli_num_rows($result));
shell_exec($debug);

//parsing the result
while($row = mysqli_fetch_assoc($result)) { //associative array
	$retVal[] = array(
                       	 	"local_path" => "/Shared".$row["file_target"],
                        	"server_id" => $row["item_source"]
                		);	
}


//get storage numeric ID
$sql= sprintf("SELECT numeric_id FROM oc_storages WHERE id LIKE '%%%s%%'",$name_location);
//log record
$debug = sprintf("echo \"%s\" >> /home/owncloud/public_html/owncloud/getserverids.log", $sql);
shell_exec($debug);


if(!($result = mysqli_query($con,$sql))){
	shell_exec("echo \"Query has failed\" >> /home/owncloud/public_html/owncloud/getserverids.log");
	$retVal = array(array( "status" => "fail"), array("reason" => "oc_storage query error"));
	echo json_encode($retVal);
}
//log record
$debug = sprintf("echo \"There are %d rows in storages\" >> /home/owncloud/public_html/owncloud/getserverids.log", mysqli_num_rows($result));
shell_exec($debug);


$row = mysqli_fetch_assoc($result);
$id = $row["numeric_id"];

//get user's files
$sql= sprintf("SELECT fileid, path FROM oc_filecache WHERE storage = '%s' AND path LIKE '%s%%'",$id,"files/");

if(!($result = mysqli_query($con,$sql))){
	shell_exec("echo \"Query has failed\" >> /home/owncloud/public_html/owncloud/getserverids.log");
	$retVal = array(array( "status" => "fail"), array("reason" => "oc_filecache query error"));
	echo json_encode($retVal);
}
//log record
$debug = sprintf("echo \"There are %d rows in files\" >> /home/owncloud/public_html/owncloud/getserverids.log", mysqli_num_rows($result));
shell_exec($debug);



//Parsing the result
while($row = mysqli_fetch_assoc($result)) { //associative array
	$retVal[] = array(
                       	 	"local_path" => substr($row["path"],5), //remove "files" part
                        	"server_id" => $row["fileid"]
                		);	
}

mysqli_close($con);
mysql_free_result($result);
echo json_encode($retVal);

?>


<?php
/**
*This file is used to mirror oc_groups table into client's "groups"
*The script gets account information from simple POST request and returns
*the list of groups that user is in and if he is an admin of the group.
*Jane Iedemska
*/

require_once "lib/base.php";

$retVal = array(array( "status" => "success"));
$name_location = $_POST["name_location"]; //owner@node

if(is_null($name_location)) {
	shell_exec("echo \"POST is null\" >> /home/owncloud/public_html/owncloud/getusergroups.log");
	$retVal = array(array( "status" => "fail"), array("reason" => "empty post"));
	echo json_encode($retVal);
}

//Create and check connection
$con=mysqli_connect("localhost","owncloud","owncloud","owncloud");
if (mysqli_connect_errno()) {
	shell_exec("echo \"Cannot connect to MYSQL database\" >> /home/owncloud/public_html/owncloud/getusergroups.log");	
	$retVal = array(array( "status" => "fail"), array("reason" => "mySQL connection error"));
	echo json_encode($retVal);
}

//Select groups where user is not an admin
$sql= sprintf("SELECT oc_group_user.gid FROM oc_group_user WHERE oc_group_user.uid = '%s' AND oc_group_user.gid NOT IN (SELECT gid FROM oc_group_admin WHERE uid = '%s')",$name_location, $name_location);

if(!($result = mysqli_query($con,$sql))){
	shell_exec("echo \"Query has failed\" >> /home/owncloud/public_html/owncloud/getusergroups.log");
	$retVal = array(array( "status" => "fail"), array("reason" => "query error"));
	echo json_encode($retVal);
}


//Log record
$debug = sprintf("echo \"There are %d rows in the non admin groups\" >> /home/owncloud/public_html/owncloud/getusergroups.log", mysqli_num_rows($result));
shell_exec($debug);



//Parsing the result
while($row = mysqli_fetch_assoc($result)) { //associative array
	$retVal[] = array(
		 		"group" => $row["gid"],
                       	 	"admin" => "0"
                		);	
}


mysql_free_result($result);

//Select groups where user is an admin
//record exists in both tables
$sql= sprintf("SELECT gid FROM oc_group_admin
		WHERE uid = '%s'", $name_location);


if(!($result = mysqli_query($con,$sql))){
	shell_exec("echo \"Query has failed\" >> /home/owncloud/public_html/owncloud/getusergroups.log");
	$retVal = array(array( "status" => "fail"), array("reason" => "query error"));
	echo json_encode($retVal);
}

//Log record
$debug = sprintf("echo \"There are %d rows in the admin groups\" >> /home/owncloud/public_html/owncloud/getusergroups.log", mysqli_num_rows($result));
shell_exec($debug);

//Parsing the result
while($row = mysqli_fetch_assoc($result)) { //associative array
	$retVal[] = array(
		 		"group" => $row["gid"],
                       	 	"admin" => "1"
                		);	
}


mysqli_close($con);
mysql_free_result($result);

echo json_encode($retVal);

?>


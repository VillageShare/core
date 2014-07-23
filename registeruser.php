<?php
/*if($_GET["regname"] && $_GET["regpass1"] && $_GET["regpass2"] ){
	if($_GET["regpass1"]==$_GET["regpass2"]) {
		$servername="localhost";
		$username="morganoc";
		$password="morganoc";
		$pass = sha1($_GET['regpass1']);
		$conn= mysql_connect($servername,$username,$password)or die(mysql_error());
		$mysql_select_db("morganoc",$conn);
		$sql="insert into oc_users (uid,displayname,password)values('$_GET[regname]','$_GET[regemail]','$pass')";
		$result=mysql_query($sql,$conn) or die(mysql_error());
	}
	else print "passwords doesnt match";
}
else print"invaild data";

header("Location: http://triumph-server.cs.ucsb.edu/owncloud/");*/
require_once "lib/base.php";

$uid = $_POST["regname"];
$pass1 = $_POST["regpass1"];
$pass2 = $_POST["regpass2"];
shell_exec("echo ${uid} >> /home/owncloud/public_html/owncloud/register.log");
if(is_null($uid)) {
	echo "$uid is null";
}

// Make sure all fields are entered
if($uid && $pass1 && $pass2) {
	$error = null;
	// Make sure the password is equal
	if($pass1==$pass2) { 
		// User was successfully created and their name was modified with a location
		if (OC_App::isEnabled('multiinstance')) {
                       	if (\OCA\MultiInstance\Lib\MILocation::uidContainsLocation($uid)){
				shell_exec("echo \"MultiInstance enabled; UID includes location\" >> /home/owncloud/public_html/owncloud/register.log");
                               	$uid_location = $uid;
                       	}
                       	else { //Always add for this location 
				shell_exec("echo \"MultiInstance enabled; UID DOES NOT include location\" >> /home/owncloud/public_html/owncloud/register.log");
                               	$location = \OCP\Config::getAppValue('multiinstance', 'location');
                               	$uid_location = $uid . "@" . $location;
                       	}
                } else {
			shell_exec("echo \"MultiInstance not enabled.\" >> /home/owncloud/public_html/owncloud/register.log");
                  	$uid_location = $uid;
                }
		try {
                	OC_User::createUser($uid_location, $pass1);
               	} catch(Exception $exception) {
                   	     $error[] = $exception->getMessage();
               	}
		if (count($error) == 0) {
                	OC_User::login($uid_location, $pass1);
		} else {
			?>
				<html>
					<body>
					<?php foreach ($error as $e) {
						echo $e;
					} ?>
					</body>
				</html>
			<?php
		}
	} else print "passwords do not match";
} else print "invalid data";
header("Location: http://"  . \OCP\Config::getAppValue('multiinstance', 'ip') . "/owncloud/index.php");
exit;
?>

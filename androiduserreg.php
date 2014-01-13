<?php
/**
*Modified user registration for android client
*
*/
require_once "lib/base.php";

$uid = $_POST["regname"];
$pass1 = $_POST["regpass1"];
$pass2 = $_POST["regpass2"];
//$data;
$error=array();
if(is_null($uid)) {
        echo "$uid is null";
}
                try {
                         OC_User::createUser($uid, $pass1);
                         //$params = array('reply' => "ACCOUNT_CREATED");
                         //echo json_encode($params);         
                }
                catch(Exception $exception) {
                        //$data = false;
                        $error[] = $exception->getMessage();
                        if($exception->getMessage() === "The username is already being used") {
                        
                        $params = array('reply' => "USER_ALREADY_EXISTS");
        }                echo json_encode($params);
                        //error_log($params);
                        
                }
                if(count($error) == 0) {
                        // User was successfully created and their name was modified with a location
                        if (OC_App::isEnabled('multiinstance')) {
                                if (\OCA\MultiInstance\Lib\MILocation::uidContainsLocation($uid)){
                                        $uid_location = $uid;
                                        error_log($uid_location);
                                }
                                else { //Always add for this location 
                                        $location = \OCP\Config::getAppValue('multiinstance', 'location');
                                        error_log("jwoqje ".$location);
                                        $uid_location = $uid . "@" . $location;
                                }
                        }
                        else {
                                $uid_location = $uid;
                        }
                        error_log($uid_location);
                        $params = array('reply' => "ACCOUNT_CREATED",'location' => $uid_location);
                                  echo json_encode($params);

                        //OC_User::login($uid_location, $pass1);
                        //$data = true;
                        //error_log($params);
                        //return $this->renderJSON($params);
                        //echo json_encode(array('reply' => $params));
                }
                else {
                        //$data = false;
                        //echo json_encode(array('reply' => $params));
                        //return $this->renderJSON($params);
                        $params = array('reply' => "ERROR");
                        echo json_encode($params);
                }
                ?>

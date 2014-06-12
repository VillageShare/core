<?

require_once "lib/base.php";
//use \OC\group\OC_Group as OC_Group;

$operation = $_POST["operation"];
$groupid = $_POST["GID"];
$userid = $_POST["UID"];

switch($operation)
{
        case 0:{
                 error_log($userid);
                 $retval = OC_Group::createGroup($groupid,$userid);
                 error_log($userid);
                 $params = array(
                        'createGroup' => $retval
                 );
                error_log("back ".$retval);
                echo json_encode($params);
                }break;
        case 1:{
		$sql = 'SELECT * FROM `oc_group_admin` WHERE `gid` = ? AND `uid` = ?';
		$params = array(groupid, userid);
		$retval = array();
		$retval = OC_DB::executeAudited(OC_DB::prepare($sql),$params);
		if ($retval !== null) {
                	$retval = OC_Group::deleteGroup($groupid);
                	$params = array(
                        	'deleteGroup'=>$retval
                	);
                	echo json_encode($params);
		}
                }break;
        case 2: {
                $retval = OC_Group::addToGroup($userid,$groupid);
                error_log($userid.' '.$groupid);
                $params = array(
                        'addToGroup' => $retval
                 );
                echo json_encode($params);
                }break;
        case 3: {
		$sql = 'SELECT * FROM `oc_group_admin` WHERE `gid` = ? AND `uid` = ?';
                $params = array(groupid, userid);
                $retval = array();
                $retval = OC_DB::executeAudited(OC_DB::prepare($sql),$params);
		if ($retval !== null) {
                	$retval = OC_Group::removeFromGroup($userid,$groupid);
                	$params = array(
                        	'removeFromGroups'=> $retval
                	);
                	echo json_encode($params);
		}
                }break;
        case 4: {
                $retval = OC_Group::getuserGroups($userid);
                $params = array(
                        'getUserGroups'=>$retval
                );
                echo json_encode($params);
                }break;
        case 5: {
                $retval = OC_Group::usersinGroup($groupid);
                $params = array(
                        'usersinGrup'=>$retval
                );
                echo json_encode($params);
                }break;
        }

?>


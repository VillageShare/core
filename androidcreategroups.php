<?

require_once "lib/base.php";
//use \OC\group\OC_Group as OC_Group;

$operation = $_POST["operation"];
$groupid = $_POST["GID"];
$userid = $_POST["UID"];
$subid = $_POST["SID"];
switch($operation)
{
        case 0:{ // Create Group
                 #error_log($userid);
                 $retval = OC_Group::createGroup($groupid,$userid);
                # error_log($userid);
                 $params = array(
                        'createGroup' => $retval
                 );
		$retval = OC_SubAdmin::createSubAdmin($userid, $groupid);
		#error_log($userid)
		$params = array(
                        'addAdmin' => $retval
                 );
		$retval = OC_Group::addToGroup($userid,$groupid);
                #error_log($userid.' '.$groupid.' '.$subid);
                $params = array(
                            'addToGroup' => $retval
                );
                echo json_encode($params);
                }break;
        case 1:{ // Delete group
		$sql = 'SELECT * FROM `oc_group_admin` WHERE `gid` = ? AND `uid` = ?';
		$params = array($groupid, $userid);
		$retval = array();
		$retval = OC_DB::executeAudited(OC_DB::prepare($sql),$params);
		$result = $retval->fetchRow();
		if ($result != null) {
					$retval = OC_Group::deleteGroup($groupid);
                        		$params = array(
		                                'deleteGroup'=>$retval
                		        );
                        		echo json_encode($params);
		}
                }break;
        case 2: { // Add to group
		$sql = 'SELECT * FROM `oc_group_admin` WHERE `gid` = ? AND `uid` = ?';
                $params = array($groupid, $userid);
                $retval = array();
                $retval = OC_DB::executeAudited(OC_DB::prepare($sql),$params);
		$result = $retval->fetchRow();
		if ($result != null) {
					$retval = OC_Group::addToGroup($subid,$groupid);
                			#error_log($userid.' '.$groupid.' '.$subid);
		                	$params = array(
                		        	'addToGroup' => $retval
		                	);
                			echo json_encode($params);
		}
                }break;
        case 3: { // Opt out
		$sql = 'SELECT * FROM `oc_group_admin` WHERE `gid` = ? AND `uid` = ?';
                $params = array($groupid, $userid);
                $retval = array();
                $retval = OC_DB::executeAudited(OC_DB::prepare($sql),$params);
		$result = $retval->fetchRow();
		if ($result != null || $userid == $subid) {

		                	$retval = OC_Group::removeFromGroup($subid,$groupid);
                			$params = array(
                        			'removeFromGroups'=> $retval
		                	);
                			echo json_encode($params);
		}
                }break;
        case 4: { // Get groups
                $retval = OC_Group::getuserGroups($userid);
                $params = array(
                        'getUserGroups'=>$retval
                );
                echo json_encode($params);
                }break;
        case 5: {
                $retval = OC_Group::usersinGroup($groupid);
                $params = array(
                        'usersinGroup'=>$retval
                );
                echo json_encode($params);
                }break;
        }

?>


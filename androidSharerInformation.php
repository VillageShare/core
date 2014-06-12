<?php
/**
*
*Script that sends the sharer's information when http request is made from android client
*VillageShare
*Smruthi Manjunath
*/
require_once "lib/base.php";

$shareWith = $_POST["accountName"];
$stmt = OC_DB::prepare( "SELECT `uid_owner`,`file_target` FROM `*PREFIX*share` WHERE `share_with` = ?" );
$result = $stmt->execute( array( $shareWith));
$sharerDetails = array();
error_log("in android sharer information");
while($row = $result->fetchRow())
{
        error_log($row['uid_owner']." ".$row['file_target']);
        $sharerDetails[] = $row['uid_owner'].":".$row['file_target'];

}

$params = array(
        'SHARER_LIST' => $sharerDetails
        );

echo json_encode($params);

?>
 

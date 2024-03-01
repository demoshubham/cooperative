<?php
date_default_timezone_set('Asia/Calcutta');
$time = mktime(true);
include("settings.php");
include("setting_sms.php");
//print_r($_POST);
$q = htmlspecialchars(urldecode(strtoupper($_REQUEST["term"])), ENT_QUOTES);
if (!$q) return;

if(isset($_REQUEST['id'])){
	$id = $_REQUEST['id'];
}
else {
	$id='';
}

$data = array();

if($id=='approver_name'){
	//print_r($_POST);
	$sql = 'INSERT INTO `survey_invoice_verification` (`survey_id`, `approver_id`, `approver_remarks`, `status`, `creation_time`) VALUES ("'.$_POST['survey_id'].'", "'.$_SESSION['username'].'", "'.$_POST['approver_remarks'].'", "'.$_POST['survey_status'].'", "'.date("Y-m-d H:i:s").'")';
	execute_query($sql);
	if(mysqli_error($db)){
		$data[] = array("status"=>"error", "msg"=>"Error # 203 : ".mysqli_error($db).' >> '.$sql);
	}
	else{
		$data[] = array("status"=>"success", "msg"=>"Data Saved.");
	}
}

if(empty($data)!=true){
	echo json_encode($data);
}
?>
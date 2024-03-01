<?php
date_default_timezone_set('Asia/Calcutta');
$time = mktime(true);
include("scripts/settings.php");
//print_r($_POST);

$sql = 'select * from survey_invoice_verification where survey_id="'.$_GET['id'].'"';
$result = execute_query($sql);
echo '<table border=1>
<tr>
<td>S.No.</td>
<td>Approver</td>
<td>Remarks</td>
<td>Status</td>
<td>Date Time</td>
</tr>';
$i=1;
while($row = mysqli_fetch_assoc($result)){
	echo '
	<tr>
	<td>'.$i++.'</td>
	<td>'.$row['approver_id'].'</td>
	<td>'.$row['approver_remarks'].'</td>
	<td>'.$row['status'].'</td>
	<td>'.$row['creation_time'].'</td>
	</tr>';
}
?>
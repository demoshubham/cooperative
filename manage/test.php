<?php
set_time_limit(0);
include("scripts/settings.php");
//update_division();
//update_district();
//create_tehseel();
//create_block();
//create_users();
//create_survey_validation();
//missing_validation();
//incorrect_status();
//missing_sec_5();
//generate_estimate();
//report_latitude();
//society_wo_land();
//society_liquidation();
//all_societies();
//upcbp_report_1(); //Asked by Sumit Awasthi on 25-08-2023
//upcbp_report_2(); //Asked by Sumit Awasthi on 25-08-2023
//upcb_report_3(); //Asked by MD Kulshreshta Sir on 21-09-2023
//upcb_report_4(); //Asked by Sumit Awasht on 03-01-2024

function report_latitude(){
	$sql = 'SELECT test2.sno as sno, col1, col2, col3, col4, col5, col6, col7, mobile_number, respondent_name, approval_status, latitude, longitude
	FROM `survey_invoice` 
	left join test2 on test2.sno = society_id 
	where approval_status=4 and (test2.status!=1 or test2.status is null)';
	$result = execute_query($sql);
	echo '<table>
	<td>S.No.</td>
	<td>Division</td>
	<td>District</td>
	<td>Tehseel</td>
	<td>Block</td>
	<td>Society Name</td>
	<td>Longitude</td>
	<td>Latitude</td>
	<td>Mobile Number</td>
	<td>Address</td>
	</tr>';
	$i=1;
	while($row = mysqli_fetch_assoc($result)){
		$sql = 'select * from master_division where sno="'.$row['col1'].'"';
		$result_division = execute_query($sql);
		$division = mysqli_fetch_assoc($result_division);
		
		$sql = 'select * from master_district where sno="'.$row['col2'].'"';
		$result_district = execute_query($sql);
		$district = mysqli_fetch_assoc($result_district);
		
		$sql = 'select * from master_tehseel where sno="'.$row['col5'].'"';
		$result_tehseel = execute_query($sql);
		$tehseel = mysqli_fetch_assoc($result_tehseel);
		
		$sql = 'select * from master_block where sno="'.$row['col6'].'"';
		$result_block = execute_query($sql);
		$block = mysqli_fetch_assoc($result_block);
		
		echo '<tr>
		<td>'.$i++.'</td>
		<td>'.$division['division_name'].'</td>
		<td>'.$district['district_name'].'</td>
		<td>'.$tehseel['tehseel_name'].'</td>
		<td>'.$block['block_name'].'</td>
		<td>'.$row['col4'].'</td>
		<td>'.$row['longitude'].'</td>
		<td>'.$row['latitude'].'</td>
		<td>'.$row['mobile_number'].'</td>
		<td>'.$row['col7'].'</td>
		</tr>';
	}
}

function update_division(){
	$sql = 'select * from test2 group by col1';
	$result = execute_query($sql);
	while($row = mysqli_fetch_assoc($result)){
		$sql = 'select * from master_division where division_name="'.$row['col1'].'"';
		$result_district = execute_query($sql);
		$district = mysqli_fetch_assoc($result_district);
		if($row['col1']!='DivisionCodeText'){
			$sql = 'update test2 set col1="'.$district['sno'].'" where col1="'.$row['col1'].'"';
			//echo $sql.'<br>';
			execute_query($sql);
		}
	}
	echo 'Division<br/>';
}


function update_district(){
	$sql = 'select * from test2 group by col2';
	$result = execute_query($sql);
	while($row = mysqli_fetch_assoc($result)){
		if($row['col1']!='DivisionCodeText'){
			$sql = 'select * from master_district where district_name="'.$row['col2'].'"';
			$result_district = execute_query($sql);
			if(mysqli_num_rows($result_district)==0){
				//echo '<h4>'.$row['col2'].'</h4>';
			}
			$district = mysqli_fetch_assoc($result_district);

			$sql = 'update test2 set col2="'.$district['sno'].'" where col2="'.$row['col2'].'"';
			execute_query($sql);
			//echo $sql.'<br>';
		}
	}
	echo 'District<br/>';
}

function create_block(){
	global $db;
	$sql = 'SELECT * FROM `test2` where col1!="DivisionCodeText" group by col6, col5, col2';
	$result = execute_query($sql);
	while($row = mysqli_fetch_assoc($result)){
		$sql = 'insert into master_block (block_name, tehseel_id, district_id) values("'.$row['col6'].'", "'.$row['col5'].'", "'.$row['col2'].'")';
		//echo $sql.'<br>';
		execute_query($sql);
		$id = mysqli_insert_id($db);
		
		$sql = 'update test2 set col6="'.$id.'" where col6="'.$row['col6'].'" and col5="'.$row['col5'].'" and col2="'.$row['col2'].'"';
		execute_query($sql);
		//echo $sql.'<br>';
	}
	echo 'Block<br/>';
}

function create_tehseel(){
	global $db;
	$sql = 'select * from test2 group by col5, col2';
	$result = execute_query($sql);
	while($row = mysqli_fetch_assoc($result)){
		if($row['col1']!='DivisionCodeText'){
			$sql = 'insert into master_tehseel (tehseel_name, district_id) values("'.$row['col5'].'", "'.$row['col2'].'")';
			//echo $sql.'<br>';
			execute_query($sql);
			$id = mysqli_insert_id($db);

			$sql = 'update test2 set col5="'.$id.'" where col5="'.$row['col5'].'" and col2="'.$row['col2'].'"';
			execute_query($sql);
			//echo $sql.'<br>';
		}
	}
	echo 'Tehseel<br/>';
}

function create_users(){
	$sql = 'select * from adco';
	$result = execute_query($sql);
	echo '<table border=1>
	<tr>
	<th>S.No.</th>
	<th>Name</th>
	<th>ID</th>
	<th>PWD</th>
	</tr>';
	$i=1;
	while($row = mysqli_fetch_assoc($result)){
		$full_name = explode(" ", strtolower($row['adco_name']));
		$n1 = substr($full_name[0], 0, 4);
		if(!$full_name[1]){
			$full_name[1] = 'usr';
		}
		$n2 = substr($full_name[1], 0, 3);
		$username = 'adco_'.$n1.'_'.$n2;
		$pwd = randompassword();
		echo '<tr>
		<td>'.$i++.'</td>
		<td>'.$row['adco_name'].'</td>
		<td>'.$username.'</td>
		<td>'.$pwd.'</td>
		</tr>';
		$sql = 'update adco set username="'.$username.'", pwd="'.$pwd.'" where sno='.$row['sno'];
		execute_query($sql);
	}
}


/*


<!DOCTYPE html>
<input type="text" id="side1">
<input type="text" id="side2">
<input type="text" id="side3">
<input type="text" id="side4">
<input type="text" id="side5">
<button type="button" onClick="draw()">Draw</button>
<canvas id="c" />

<script>
function draw(){

	var ctx = document.getElementById('c').getContext('2d');
	var a = document.getElementById('side1').value();
	var b = document.getElementById('side2').value();
	var c = document.getElementById('side3').value();
	var d = document.getElementById('side4').value();
	var e = document.getElementById('side5').value();
	ctx.fillStyle = '#f00';
	ctx.beginPath();
	ctx.moveTo(0, 0);
	ctx.lineTo(a, 0);
	ctx.lineTo(0, b);
	ctx.lineTo(c, 0);
	ctx.lineTo(d, 0);
	ctx.lineTo(e, 0);
	ctx.closePath();
	ctx.fill();

}
</script>


<script>
 
// Javascript implementation of the approach
 
 
// Function that returns true if it is possible
// to form a polygon with the given sides
function isPossible( a, n)
{
 
    // Sum stores the sum of all the sides
    // and maxS stores the length of
    // the largest side
    let sum = 0, maxS = 0;
    for (let i = 0; i < n; i++) {
        sum += a[i];
        maxS = Math.max(a[i], maxS);
    }
 
    // If the length of the largest side
    // is less than the sum of the
    // other remaining sides
    if ((sum - maxS) > maxS)
        return true;
 
    return false;
}
 
    // Driver Code
     
    let a = [ 2, 3, 4 ];
    let n = a.length;
 
    if (isPossible(a, n))
        document.write("Yes");
    else
        document.write("No");
     
     
</script>*/

function create_survey_validation(){
	global $db;
	$sql = 'select * from survey_invoice where approval_status=1';
	$result = execute_query($sql);
	$i=1;
	echo '<table border=1>
	<tr>
	<th>S.No.</th>
	<th>Survey ID</th>
	<th>Mobile</th>
	<th>Send On Date</th>
	';
	$missing=array();
	while($row = mysqli_fetch_assoc($result)){
		$row['mobile_number'] = substr(trim($row['mobile_number']), -10);
		$sql = 'select * from sms_report where originalnumber="91'.$row['mobile_number'].'" order by sno desc limit 1';
		$result_sms = execute_query($sql);
		
		if(mysqli_num_rows($result_sms)==0){
			echo '<tr style="background:"#f00">
			<td>'.$i++.'</td>
			<td>'.$row['sno'].'</td>
			<td>'.$row['mobile_number'].'</td>
			<td><h3>Not Found</h3></td></tr>';
			$missing[] = $row['mobile_number'];
		}
		else{
			$row_sms = mysqli_fetch_assoc($result_sms);
			echo '<tr>
			<td>'.$i++.'</td>
			<td>'.$row['sno'].'</td>
			<td>'.$row['mobile_number'].'</td>
			<td>'.$row_sms['sendondate'].'</td>
			</tr>';
			$sql = 'insert into survey_invoice_validation (survey_id, user_id, user_type, mobile_number, otp_verify, `ip_address`, `http_referer`, `http_user_agent`, `approval_status`, status, creation_time) values ("'.$row['sno'].'", "", "secretary", "'.$row['mobile_number'].'", "1", "'.$row['ip_address'].'", "'.$row['http_referer'].'", "'.$row['http_user_agent'].'", "approve", 1,  "'.$row_sms['sendondate'].'")';
			execute_query($sql);
			if(mysqli_error($db)){
				echo mysqli_error($db).' >> '.$sql;
				die();
			}
		}
	}
	print_r($missing);
}

function missing_validation(){
	$sql = 'select * from survey_invoice where approval_status in (1, 2, 3, 4)';
	$result_invoice = execute_query($sql);
	$i=1;
	echo '<h3>Missing Validation</h3>
	<table border="1">';
	while($row = mysqli_fetch_assoc($result_invoice)){
		$sql = 'select * from survey_invoice_validation where approval_status="approve" and survey_id="'.$row['sno'].'"';
		$res_validate = execute_query($sql);
		if(mysqli_num_rows($res_validate)==0){
			$sql = 'select * from survey_invoice_validation where approval_status="approve" and mobile_number="'.$row['mobile_number'].'"';
			$res_validate = execute_query($sql);
			if(mysqli_num_rows($res_validate)==0){
				$stat = '<p style="color:#f00">Double Blank</p>';
			}
			else{
				$row_validate = mysqli_fetch_assoc($res_validate);
				$stat = 'Found';
				$sql = 'update survey_invoice_validation set survey_id="'.$row['sno'].'" where sno='.$row_validate['sno'];
				//execute_query($sql);
			}

		
			echo '<tr>
			<td>'.$i++.'</td>
			<td>'.$row['sno'].'</td>
			<td>'.$row['mobile_number'].'</td>
			<td>'.$stat.'</td>
			</tr>';
		}
	}
}

function incorrect_status(){
	$sql = 'select * from survey_invoice where approval_status=1';
	$res = execute_query($sql);
	echo '<table border="1">';
	$count=0;
	$count_array = array();
	while($row = mysqli_fetch_assoc($res)){
		echo '<tr>
		<td>'.$row['sno'].'</td>
		<td>'.$row['mobile_number'].'</td>';
		$sql = 'select * from survey_invoice_validation where survey_id="'.$row['sno'].'" order by sno desc limit 1';
		$r = execute_query($sql);
		if(mysqli_num_rows($r)!=0){
			while($row_r = mysqli_fetch_assoc($r)){
				if($row_r['approval_status']=='reject' && $row_r['user_type']=='ado'){
					echo '
					<td>'.$row_r['user_type'].'</td>
					<td>'.$row_r['approval_status'].'</td>
					<td>'.$row_r['status'].'</td>
					';
					$count++;
					$count_array[] = $row['sno'];
				}
			}
		}
		echo '</tr>';
		
	}
	echo $count;
	echo implode(", ", $count_array);
}

function generate_estimate(){
	global $db;
	$sql = 'update `survey_invoice_sec_5` set building_status="good" where building_status is null or building_status=""';
	execute_query($sql);
	
	$sql = 'select survey_invoice.sno as sno, survey_invoice.mobile_number as mobile_number, survey_invoice.approval_status as approval_status, survey_invoice.creation_time as creation_time, col1, col2, col3, col4, col5, col6, otp_verify, latitude, longitude, mobile_number, `survey_invoice_sec_5`.`survey_id`, `survey_invoice_sec_5`.`building_status`, `survey_invoice_sec_5`.`building_status_remarks`, `survey_invoice_sec_5`.`floor_length`, `survey_invoice_sec_5`.`floor_width`, `survey_invoice_sec_5`.`floor_image`, `survey_invoice_sec_5`.`wall_length`, `survey_invoice_sec_5`.`wall_width`, `survey_invoice_sec_5`.`wall_image`, `survey_invoice_sec_5`.`paint_length`, `survey_invoice_sec_5`.`paint_width`, `survey_invoice_sec_5`.`paint_image`, `survey_invoice_sec_5`.`roof_length`, `survey_invoice_sec_5`.`roof_width`, `survey_invoice_sec_5`.`roof_image`, `survey_invoice_sec_5`.`washroom_floor`, `survey_invoice_sec_5`.`washroom_plaster`, `survey_invoice_sec_5`.`washroom_roof`, `survey_invoice_sec_5`.`washroom_seat`, `survey_invoice_sec_5`.`washroom_plumbing`, `survey_invoice_sec_5`.`doors`, `survey_invoice_sec_5`.`windows`, `survey_invoice_sec_5`.`plaster_wall`, `survey_invoice_sec_5`.`plaster_roof`, `survey_invoice_sec_5`.`others`, `survey_invoice_sec_5`.`status` from survey_invoice left join test2 on test2.sno = survey_invoice.society_id  left join survey_invoice_sec_5 on survey_invoice_sec_5.survey_id = survey_invoice.sno where col2 !="DivisionCodeText" and `building_status` ="repairable" and `approval_status` ="4"';
	$result = execute_query($sql);
	$i=0;
	while($row = mysqli_fetch_array($result)){
		$estimate = estimate($row['sno']);
		$sql = 'INSERT INTO `upcod_survey`.`survey_estimate` (`survey_id`, `estimate_time`, `floor_area`, `floor_rate`, `floor_cess`, `floor_centage`, `floor_gst`, `floor_net_rate`, `floor_total`, `wall_area`, `wall_rate`, `wall_cess`, `wall_centage`, `wall_gst`, `wall_net_rate`, `wall_total`, `paint_area`, `paint_rate`, `paint_cess`, `paint_centage`, `paint_gst`, `paint_net_rate`, `paint_total`, `roof_area`, `roof_rate`, `roof_cess`, `roof_centage`, `roof_gst`, `roof_net_rate`, `roof_total`, `plaster_wall_area`, `plaster_wall_rate`, `plaster_wall_cess`, `plaster_wall_centage`, `plaster_wall_gst`, `plaster_wall_net_rate`, `plaster_wall_total`, `plaster_roof_area`, `plaster_roof_rate`, `plaster_roof_cess`, `plaster_roof_centage`, `plaster_roof_gst`, `plaster_roof_net_rate`, `plaster_roof_total`, `washroom_total`, `washroom_cess`, `washroom_centage`, `washroom_gst`, `washroom_net_rate`, `washroom_grand_total`, `grand_total_a_b`) VALUES ("'.$row['sno'].'", "'.date("Y-m-d H:i:s").'", "'.$estimate['floor_area'].'", "'.$estimate['floor_rate'].'", "'.$estimate['floor_cess'].'", "'.$estimate['floor_centage'].'", "'.$estimate['floor_gst'].'", "'.$estimate['floor_net_rate'].'", "'.$estimate['floor_total'].'", "'.$estimate['wall_area'].'", "'.$estimate['wall_rate'].'", "'.$estimate['wall_cess'].'", "'.$estimate['wall_centage'].'", "'.$estimate['wall_gst'].'", "'.$estimate['wall_net_rate'].'", "'.$estimate['wall_total'].'", "'.$estimate['paint_area'].'", "'.$estimate['paint_rate'].'", "'.$estimate['paint_cess'].'", "'.$estimate['paint_centage'].'", "'.$estimate['paint_gst'].'", "'.$estimate['paint_net_rate'].'", "'.$estimate['paint_total'].'", "'.$estimate['roof_area'].'", "'.$estimate['roof_rate'].'", "'.$estimate['roof_cess'].'", "'.$estimate['roof_centage'].'", "'.$estimate['roof_gst'].'", "'.$estimate['roof_net_rate'].'", "'.$estimate['roof_total'].'", "'.$estimate['plaster_wall_area'].'", "'.$estimate['plaster_wall_rate'].'", "'.$estimate['plaster_wall_cess'].'", "'.$estimate['plaster_wall_centage'].'", "'.$estimate['plaster_wall_gst'].'", "'.$estimate['plaster_wall_net_rate'].'", "'.$estimate['plaster_wall_total'].'", "'.$estimate['plaster_roof_area'].'", "'.$estimate['plaster_roof_rate'].'", "'.$estimate['plaster_roof_cess'].'", "'.$estimate['plaster_roof_centage'].'", "'.$estimate['plaster_roof_gst'].'", "'.$estimate['plaster_roof_net_rate'].'", "'.$estimate['plaster_roof_total'].'", "'.$estimate['washroom_total'].'", "'.$estimate['washroom_cess'].'", "'.$estimate['washroom_centage'].'", "'.$estimate['washroom_gst'].'", "'.$estimate['washroom_net_rate'].'", "'.$estimate['washroom_total'].'", "'.$estimate['grand_total_a_b'].'")';
		execute_query($sql);
		if(mysqli_error($db)){
			echo mysqli_error($db).' >> '.$sql.'<br>';
		}
		else{
			$i++;
		}
	}
	echo $i.' > Estimates Generated<br/>';
}

function missing_sec_5(){
	$sql = 'update `survey_invoice_sec_2_2` set secretary="no", secretary_status="", secretary_cader="", secretary_name="", secretary_mobile="", secretary_aadhaar="" where secretary in ("no","")';
	$sql = 'update  `survey_invoice_sec_2_2` set secretary_cader="non_cader" where secretary="yes" and secretary_cader=""';
	
	$sql = 'select survey_invoice.sno as sno, survey_invoice.mobile_number as mobile_number, survey_invoice.approval_status as approval_status, survey_invoice.creation_time as creation_time, col1, col2, col3, col4, col5, col6, otp_verify, latitude, longitude, mobile_number, `survey_invoice_sec_5`.`survey_id`, `survey_invoice_sec_5`.`building_status`, `survey_invoice_sec_5`.`building_status_remarks`, `survey_invoice_sec_5`.`floor_length`, `survey_invoice_sec_5`.`floor_width`, `survey_invoice_sec_5`.`floor_image`, `survey_invoice_sec_5`.`wall_length`, `survey_invoice_sec_5`.`wall_width`, `survey_invoice_sec_5`.`wall_image`, `survey_invoice_sec_5`.`paint_length`, `survey_invoice_sec_5`.`paint_width`, `survey_invoice_sec_5`.`paint_image`, `survey_invoice_sec_5`.`roof_length`, `survey_invoice_sec_5`.`roof_width`, `survey_invoice_sec_5`.`roof_image`, `survey_invoice_sec_5`.`washroom_floor`, `survey_invoice_sec_5`.`washroom_plaster`, `survey_invoice_sec_5`.`washroom_roof`, `survey_invoice_sec_5`.`washroom_seat`, `survey_invoice_sec_5`.`washroom_plumbing`, `survey_invoice_sec_5`.`doors`, `survey_invoice_sec_5`.`windows`, `survey_invoice_sec_5`.`plaster_wall`, `survey_invoice_sec_5`.`plaster_roof`, `survey_invoice_sec_5`.`others`, `survey_invoice_sec_5`.`status`, `survey_invoice`.`creation_time` from survey_invoice left join test2 on test2.sno = survey_invoice.society_id  left join survey_invoice_sec_5 on survey_invoice_sec_5.survey_id = survey_invoice.sno where col2 !="DivisionCodeText"  and `approval_status` ="4"';
	$result = execute_query($sql);
	$i=1;
	$a=0;
	while($row = mysqli_fetch_assoc($result)){
		$sql = 'select * from survey_invoice_sec_5 where survey_id="'.$row['sno'].'"';
		$res = execute_query($sql);
		if(mysqli_num_rows($res)==0){
			echo $i++.'>>Missing : '.$row['sno'].'<br>';
			$sql = 'insert into survey_invoice_sec_5 (survey_id, building_status, creation_time) values ("'.$row['sno'].'", "not_available", "'.$row['creation_time'].'")';
			execute_query($sql);
		}
		else{
			$a++;	
		}
	}
	echo 'All Good : '.$a;
}

function society_wo_land(){
	$sql = "SELECT test2.sno as sno, division_name, district_name, col4, col7, mobile_number, respondent_name
FROM `survey_invoice` 
left join survey_invoice_sec_3_1 on survey_invoice.sno = survey_invoice_sec_3_1.survey_id 
left join test2 on test2.sno = society_id 
left join master_division on master_division.sno = col1 
left join master_district on master_district.sno = col2 
where approval_status=4 and  (test2.status!=1 or test2.status is null) 
and total_area in ('-', '', 0, '0.', '0.00', '00.00', '000', '000.0', '0000', 'Chak bandi m', 'Gyat nhi', 'kiraye  par', 'N/A', 'Na', 'Nahi', 'Nil', 'Nill', 'No', 'NONE', 'O', 'Samiti  ke koi Jagnahi nahi.he', 'XXXXX', 'अभिलेख नही', 'आबादी में दर्ज', 'ज्ञात नही', 'ज्ञात नहीं', 'नहीं ', '०')
";
	$result = execute_query($sql);
	echo '<table border="1">
	<tr>
		<th>S.No.</th>
		<th>Division Name</th>
		<th>District Name</th>
		<th>Society Name</th>
		<th>Mobile Number</th>
		<th>Respondent Name</th>
	</tr>';
	$i=1;
	while($row = mysqli_fetch_assoc($result)){
		echo '<tr>
		<td>'.$i++.'</td>
		<td>'.$row['division_name'].'</td>
		<td>'.$row['district_name'].'</td>
		<td>'.$row['col4'].'</td>
		<td>'.$row['col7'].'</td>
		<td>'.$row['mobile_number'].'</td>
		<td>'.$row['respondent_name'].'</td>
		</tr>';
	}
	echo '</table>';
$i=1;
	echo '<br/>';
	echo '<br/>';
	$sql = "SELECT test2.sno as sno, division_name, district_name, col4, col7, mobile_number, respondent_name
FROM `survey_invoice` 
left join survey_invoice_sec_3_5 on survey_invoice.sno = survey_invoice_sec_3_5.survey_id 
left join test2 on test2.sno = society_id 
left join master_division on master_division.sno = col1 
left join master_district on master_district.sno = col2 
where approval_status=4 and  (test2.status!=1 or test2.status is null) 
and total_area in ('', ' Nill', ' शून्य', '-', '--', '0.', '0..00', '0.0', '0.00', '0.000', '0.0000', '0.0000', '000', '0000', '00000', 'Kali', 'N.A', 'N/A', 'N0', 'NA', 'Nahi', 'Nahi hai', 'Nahin Hai', 'Nhi', 'Ni', 'nil', 'Nill ', 'No', 'NONE', 'O', '_', 'अभिलेख नही', 'कुछ नही', 'कुछ नही है', 'कुछ नहीं', 'कुछ भी छेत्रफल नही है ', 'कोई भूमि खाली नही', 'खलिहान में बना है। ', 'खाली जमीन नहीं है।', 'खाली भूमि नहीं है', 'नही', 'नहीं', 'नहीं है', 'नहींहै', 'नील', 'शून्य', '०', '००', '०००')";
	$result = execute_query($sql);
	echo '<table border="1">
	<tr>
		<th>S.No.</th>
		<th>Division Name</th>
		<th>District Name</th>
		<th>Society Name</th>
		<th>Mobile Number</th>
		<th>Respondent Name</th>
	</tr>';
	$i=1;
	while($row = mysqli_fetch_assoc($result)){
		echo '<tr>
		<td>'.$i++.'</td>
		<td>'.$row['division_name'].'</td>
		<td>'.$row['district_name'].'</td>
		<td>'.$row['col4'].'</td>
		<td>'.$row['col7'].'</td>
		<td>'.$row['mobile_number'].'</td>
		<td>'.$row['respondent_name'].'</td>
		</tr>';
	}
}

function society_liquidation(){
	$sql = 'SELECT test2.sno as sno, col1, division_name, col2, district_name, col3, col4, col5, tehseel_name, col6, block_name, col7, mobile_number, respondent_name, liquidation, society_building_ownership, secretary, secretary_cader, accountant, assistant_accountant, seller, support_staff, guard, computer_operator
	FROM `survey_invoice` 
	left join survey_invoice_sec_2_2 on survey_invoice.sno = survey_id 
	left join test2 on test2.sno = society_id 
	left join master_division on master_division.sno = col1 
	left join master_district on master_district.sno = col2 
	left join master_tehseel on master_tehseel.sno = col5
	left join master_block on master_block.sno = col6 
	where approval_status=4 and  (test2.status!=1 or test2.status is null)
    and liquidation="yes"
	order by abs(col1), abs(col2)';
	$result = execute_query($sql);
	echo '<table border="1">
	<tr>
		<th>S.No.</th>
		<th>Division Name</th>
		<th>District Name</th>
		<th>Tehseel Name</th>
		<th>Block Name</th>
		<th>Society Name</th>
		<th>Address</th>
		<th>Mobile Number</th>
		<th>Respondent Name</th>
		<th>Liquidation Status</th>
		<th>Building Status</th>
		<th>Secretary Availability</th>
		<th>Secretary Cader/Non-Cader</th>
		<th>Accountant</th>
		<th>Asst. Accountant</th>
		<th>Computer Operator</th>
		<th>Seller</th>
		<th>Support Staff</th>
		<th>Guard</th>
	</tr>';
	$i=1;
	while($row = mysqli_fetch_assoc($result)){
		echo '<tr>
		<td>'.$i++.'</td>
		<td>'.$row['division_name'].'</td>
		<td>'.$row['district_name'].'</td>
		<td>'.$row['tehseel_name'].'</td>
		<td>'.$row['block_name'].'</td>
		<td>'.$row['col4'].'</td>
		<td>'.$row['col7'].'</td>
		<td>'.$row['mobile_number'].'</td>
		<td>'.$row['respondent_name'].'</td>
		<td>'.$row['liquidation'].'</td>
		<td>'.$row['society_building_ownership'].'</td>
		<td>'.$row['secretary'].'</td>
		<td>'.$row['secretary_cader'].'</td>
		<td>'.$row['accountant'].'</td>
		<td>'.$row['assistant_accountant'].'</td>
		<td>'.$row['computer_operator'].'</td>
		<td>'.$row['seller'].'</td>
		<td>'.$row['support_staff'].'</td>
		<td>'.$row['guard'].'</td>
		</tr>';
	}
}

function all_societies(){
	/*$sql = 'SELECT test2.sno as sno, col1, division_name, col2, district_name, col3, col4, col5, tehseel_name, col6, block_name, col7, mobile_number, respondent_name, secretary_name, secretary_mobile, latitude, longitude, govt_program
FROM `survey_invoice` 
left join survey_invoice_sec_2_2 on survey_invoice_sec_2_2.survey_id = survey_invoice.sno 
left join test2 on test2.sno = society_id 
left join master_division on master_division.sno = col1 
left join master_district on master_district.sno = col2 
left join master_tehseel on master_tehseel.sno = col5 
left join master_block on master_block.sno = col5 
where approval_status=4 and  (test2.status!=1 or test2.status is null) and govt_program="first"
order by abs(col1), abs(col2), abs(col5), abs(col6)';*/
	
	//Division Code	Division Name	District Code	District Name	Tehsil Code	Tehsil Name	Block Code	Block Name	Bank Code	Bank Name	PACS Code	PACS Name

	$sql = 'SELECT test2.sno as sno, col1, division_name, col2, district_name, col3, col4, col5, tehseel_name, col6, block_name, col7, mobile_number, respondent_name, secretary_name, secretary_mobile, latitude, longitude, govt_program
FROM `survey_invoice` 
left join survey_invoice_sec_2_2 on survey_invoice_sec_2_2.survey_id = survey_invoice.sno 
left join test2 on test2.sno = society_id 
left join master_division on master_division.sno = col1 
left join master_district on master_district.sno = col2 
left join master_tehseel on master_tehseel.sno = col5 
left join master_block on master_block.sno = col6 
where approval_status=4 and  (test2.status!=1 or test2.status is null)';
	$result = execute_query($sql);
	$i=1;
	echo '<table border="1">
	<tr>
		<th>S.No.</th>
		<th>Division Code</th>
		<th>Division Name</th>
		<th>District Code</th>
		<th>District Name</th>
		<th>Tehseel Code</th>
		<th>Tehseel Name</th>
		<th>Block Code</th>
		<th>Block Name</th>
		<th>Bank Code</th>
		<th>Bank Name</th>
		<th>PCAS Code</th>
		<th>PACS Name</th>
		<th>Address</th>
		<th>Mobile Number</th>
		<th>Respondent Name</th>
		<th>Secretary Name</th>
		<th>Mobile Number</th>
		<th>Latitude</th>
		<th>Longitude</th>
	</tr>';
	while($row = mysqli_fetch_assoc($result)){
		echo '<tr>
		<td>'.$i++.'</td>
		<td>'.$row['col1'].'</td>
		<td>'.$row['division_name'].'</td>
		<td>'.$row['col2'].'</td>
		<td>'.$row['district_name'].'</td>
		<td>'.$row['col5'].'</td>
		<td>'.$row['tehseel_name'].'</td>
		<td>'.$row['col6'].'</td>
		<td>'.$row['block_name'].'</td>
		<td></td>
		<td></td>
		<td>'.$row['sno'].'</td>
		<td>'.$row['col4'].'</td>
		<td>'.$row['col7'].'</td>
		<td>'.$row['mobile_number'].'</td>
		<td>'.$row['respondent_name'].'</td>
		<td>'.$row['secretary_name'].'</td>
		<td>'.$row['secretary_mobile'].'</td>
		<td>'.$row['latitude'].'</td>
		<td>'.$row['longitude'].'</td>
		</tr>
		';
	}
}

function upcbp_report_1(){
	$sql = 'SELECT 
	test2.sno as sno, 
	col1, 
	division_name, 
	col2, 
	district_name, 
	col3, 
	col4, 
	col36,
	col5, 
	tehseel_name, 
	col6, 
	block_name, 
	col7, 
	mobile_number, 
	respondent_name,
	secretary,
	secretary_status,
	secretary_cader,
	secretary_name, 
	secretary_mobile, 
	secretary_aadhaar
	FROM `survey_invoice` 
	left join survey_invoice_sec_2_2 on survey_invoice_sec_2_2.survey_id = survey_invoice.sno 
	left join test2 on test2.sno = society_id 
	left join master_division on master_division.sno = col1 
	left join master_district on master_district.sno = col2 
	left join master_tehseel on master_tehseel.sno = col5 
	left join master_block on master_block.sno = col6 
	where approval_status=4 and  (test2.status!=1 or test2.status is null)
	order by secretary, secretary_status, col1, col2';
	$result = execute_query($sql);
	$i=1;
	echo '<table border="1">
	<tr>
		<th>S.No.</th>
		<th>Division Name</th>
		<th>District Name</th>
		<th>Tehseel Name</th>
		<th>Block Name</th>
		<th>Address</th>
		<th>PIN</th>
		<th>Mobile Number</th>
		<th>Respondent Name</th>
		<th>Secretary (Yes/No)</th>
		<th>Secretary (Permanent/Additional Charge)</th>
		<th>Secretary (Cader/Non-Cader)</th>
		<th>Secretary Name</th>
		<th>Mobile Number</th>
		<th>Aadhaar</th>
	</tr>';
	while($row = mysqli_fetch_assoc($result)){
		echo '<tr>
		<td>'.$i++.'</td>
		<td>'.$row['division_name'].'</td>
		<td>'.$row['district_name'].'</td>
		<td>'.$row['tehseel_name'].'</td>
		<td>'.$row['block_name'].'</td>
		<td>'.$row['col4'].'</td>
		<td>'.$row['col36'].'</td>
		<td>'.$row['mobile_number'].'</td>
		<td>'.$row['respondent_name'].'</td>
		<td>'.$row['secretary'].'</td>
		<td>'.$row['secretary_status'].'</td>
		<td>'.$row['secretary_cader'].'</td>
		<td>'.$row['secretary_name'].'</td>
		<td>'.$row['secretary_mobile'].'</td>
		<td>'.$row['secretary_aadhaar'].'</td>
		</tr>
		';
	}
}

function upcbp_report_2(){
	$sql = 'SELECT 
	test2.sno as sno, 
	col1, 
	division_name, 
	col2, 
	district_name, 
	col3, 
	col4, 
	col36,
	col5, 
	tehseel_name, 
	col6, 
	block_name, 
	col7, 
	mobile_number, 
	respondent_name,
	secretary,
	secretary_status,
	secretary_cader,
	secretary_name, 
	secretary_mobile, 
	secretary_aadhaar,
	survey_invoice_sec_2_2.sno as sno_2,
	count(*) c
	FROM `survey_invoice_sec_2_2` 
	left join survey_invoice on survey_invoice_sec_2_2.survey_id = survey_invoice.sno 
	left join test2 on test2.sno = society_id 
	left join master_division on master_division.sno = col1 
	left join master_district on master_district.sno = col2 
	left join master_tehseel on master_tehseel.sno = col5 
	left join master_block on master_block.sno = col6 
	where approval_status=4 and  (test2.status!=1 or test2.status is null)
	and secretary_status="regular"
	and secretary_aadhaar!=""
	
	group by secretary_aadhaar
	having c>1
	order by secretary, secretary_status, col1, col2';
	$result = execute_query($sql);
	$i=1;
	echo '<table border="1">
	<tr>
		<th>S.No.</th>
		<th>Division Name</th>
		<th>District Name</th>
		<th>Tehseel Name</th>
		<th>Block Name</th>
		<th>Address</th>
		<th>PIN</th>
		<th>Mobile Number</th>
		<th>Respondent Name</th>
		<th>Secretary (Yes/No)</th>
		<th>Secretary (Permanent/Additional Charge)</th>
		<th>Secretary (Cader/Non-Cader)</th>
		<th>Secretary Name</th>
		<th>Mobile Number</th>
		<th>Aadhaar</th>
		<th>Count</th>
	</tr>';
	while($row = mysqli_fetch_assoc($result)){
		echo '<tr>
		<td>'.$i++.'</td>
		<td>'.$row['division_name'].'</td>
		<td>'.$row['district_name'].'</td>
		<td>'.$row['tehseel_name'].'</td>
		<td>'.$row['block_name'].'</td>
		<td>'.$row['col4'].'</td>
		<td>'.$row['col36'].'</td>
		<td>'.$row['mobile_number'].'</td>
		<td>'.$row['respondent_name'].'</td>
		<td>'.$row['secretary'].'</td>
		<td>'.$row['secretary_status'].'</td>
		<td>'.$row['secretary_cader'].'</td>
		<td>'.$row['secretary_name'].'</td>
		<td>'.$row['secretary_mobile'].'</td>
		<td>'.$row['secretary_aadhaar'].'</td>
		<td>'.$row['c'].'</td>';
		if($row['c']>1){
			$sql = 'select * from survey_invoice_sec_2_2 where secretary_aadhaar="'.$row['secretary_aadhaar'].'" and sno!="'.$row['sno_2'].'"';
			$result1 = execute_query($sql);
			while($row1 = mysqli_fetch_assoc($result1)){
				//echo '<td>'.$row1['sno'].'</td>';
			}
		}
		echo '</tr>
		';
	}
}

function upcb_report_3(){
	$sql_main_land = 'SELECT test2.sno as sno, survey_invoice_sec_3_1.survey_id, col1, division_name, col2, district_name, col3, col4, col5, col6, col7, mobile_number, respondent_name, abs(total_area) as total_area, last_year_profit_loss, last_year_pl_amount, seq_year_profit_loss, seq_year_pl_amount FROM `survey_invoice_sec_2_1` left join survey_invoice_sec_3_1 on survey_invoice_sec_2_1.survey_id = survey_invoice_sec_3_1.survey_id left join survey_invoice on survey_invoice.sno = survey_invoice_sec_3_1.survey_id left join test2 on test2.sno = society_id left join master_division on master_division.sno = col1 left join master_district on master_district.sno = col2 where approval_status=4 and (test2.status!=1 or test2.status is null) and abs(total_area)>="0.202343" order by col1, col2, abs(total_area)';
	
	$sql_other_land = 'SELECT test2.sno as sno, survey_invoice_sec_3_5.survey_id as survey_id, col1, division_name, col2, district_name, col3, col4, col5, col6, col7, mobile_number, respondent_name, abs(total_area) as total_area, last_year_profit_loss, last_year_pl_amount, seq_year_profit_loss, seq_year_pl_amount FROM `survey_invoice_sec_3_5` left join survey_invoice_sec_2_1 on survey_invoice_sec_2_1.survey_id = survey_invoice_sec_3_5.survey_id left join survey_invoice on survey_invoice.sno = survey_invoice_sec_3_5.survey_id left join test2 on test2.sno = society_id left join master_division on master_division.sno = col1 left join master_district on master_district.sno = col2 where approval_status=4 and (test2.status!=1 or test2.status is null) and land_type!="" and total_area!="" and abs(total_area)>="0.202343" order by col1, col2, abs(total_area)';
	$result = execute_query($sql_other_land);
?>
	<table class="table table-hover table-striped" border="1">
		<thead>
			<tr>
				<th>S.No.</th>
				<th>Division Name</th>
				<th>District Name</th>
				<th>Society Name</th>
				<th>Mobile Number</th>
				<th>Land Area (Hectare)</th>
				<th>Secretary</th>
				<th>Secretary (Regular/Add.Charge)</th>
				<th>Secretary (Cader/Non-Cader)</th>
				<th>Accountant</th>
				<th>Computer Operator</th>
				<th>Asst. Accountant</th>
				<th>Seller</th>
				<th>Support Staff</th>
				<th>Guard</th>
				<th>Profit/Loss</th>
				<th>Profit/Loss Amount</th>
				<th>Seq. Profit/Loss</th>
				<th>Seq. Profit/Loss Amount</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=1;


			while($row = mysqli_fetch_array($result)){
				$sql = 'select count(*) c from test2 where (test2.status!=1 or test2.status is null) and col2="'.$row['col2'].'"';
				$count = mysqli_fetch_assoc(execute_query($sql));
				echo '<tr>
				<td>'.$i++.'</td>
				<td>'.$row['division_name'].'</td>
				<td>'.$row['district_name'].'</td>
				<td>'.$row['col4'].'</td>
				<td>'.$row['mobile_number'].'</td>
				<td>'.$row['total_area'].'</td>';

				$sql = 'select * from survey_invoice_sec_2_2 where survey_id="'.$row['survey_id'].'"';
				$res_sec_2_2 = execute_query($sql);
				if(mysqli_num_rows($res_sec_2_2)!=0){
					$row_sec_2_2 = mysqli_fetch_assoc($res_sec_2_2);
					echo '<td>'.strtoupper($row_sec_2_2['secretary']).'</td>
					<td>'.strtoupper($row_sec_2_2['secretary_status']).'</td>
					<td>'.strtoupper($row_sec_2_2['secretary_cader']).'</td>
					<td>'.strtoupper($row_sec_2_2['accountant']).'</td>
					<td>'.strtoupper($row_sec_2_2['computer_operator']).'</td>
					<td>'.strtoupper($row_sec_2_2['assistant_accountant']).'</td>
					<td>'.strtoupper($row_sec_2_2['seller']).'</td>
					<td>'.strtoupper($row_sec_2_2['support_staff']).'</td>
					<td>'.strtoupper($row_sec_2_2['guard']).'</td>';

				}
				else{
					echo '<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>';
				}

				echo '<td>'.strtoupper($row['last_year_profit_loss']).'</td>
				<td>Rs.'.$row['last_year_pl_amount'].'</td>';
				echo '<td>'.strtoupper($row['seq_year_profit_loss']).'</td>
				<td>Rs.'.$row['seq_year_pl_amount'].'</td>';
				echo '</tr>';


			}	
			?>
		</tbody>
	</table>
<?php	
	
}

function upcb_report_4(){
	$sql_main_godown = 'SELECT 
	test2.sno as sno, survey_invoice_sec_3_3.survey_id, col1, division_name, col2, district_name, col3, col4, col5, col6, col7, mobile_number, respondent_name, length, width, remarks, type_of_construction, type_of_fund 
	FROM `survey_invoice_sec_2_1` 
	left join survey_invoice_sec_3_3 on survey_invoice_sec_2_1.survey_id = survey_invoice_sec_3_3.survey_id 
	left join survey_invoice on survey_invoice.sno = survey_invoice_sec_3_3.survey_id 
	left join test2 on test2.sno = society_id 
	left join master_division on master_division.sno = col1 
	left join master_district on master_district.sno = col2 
	where approval_status=4 and (test2.status!=1 or test2.status is null) and type_of_construction in (3, 5) and abs(length)>0';
	$result = execute_query($sql_main_godown);
	
	$sql_other_godown = 'SELECT 
	test2.sno as sno, survey_invoice_sec_3_4.survey_id, col1, division_name, col2, district_name, col3, col4, col5, col6, col7, mobile_number, respondent_name, survey_invoice_sec_3_4.construction_status as construction_status, storage_capacity, remarks, type_of_fund 
	FROM `survey_invoice_sec_2_1` 
	left join survey_invoice_sec_3_4 on survey_invoice_sec_2_1.survey_id = survey_invoice_sec_3_4.survey_id 
	left join survey_invoice on survey_invoice.sno = survey_invoice_sec_3_4.survey_id 
	left join test2 on test2.sno = society_id 
	left join master_division on master_division.sno = col1 
	left join master_district on master_district.sno = col2 
	where approval_status=4 and (test2.status!=1 or test2.status is null) and abs(storage_capacity)>0;';
	$result2 = execute_query($sql_other_godown);
	
?>
	<table class="table table-hover table-striped" border="1">
		<thead>
			<tr>
				<th>S.No.</th>
				<th>Division Name</th>
				<th>District Name</th>
				<th>Society Name</th>
				<th>Mobile Number</th>
				<th>Type of Construction</th>
				<th>Type of Fund</th>
				<th>Length</th>
				<th>Width</th>
				<th>Remarks</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=1;
			$funds = array("6"=>"RKVY", "7"=>"ICDP", "8"=>"AIF", "9"=>"World Bank", "10"=>"Other");
			while($row = mysqli_fetch_array($result)){
				$sql = 'select count(*) c from test2 where (test2.status!=1 or test2.status is null) and col2="'.$row['col2'].'"';
				$count = mysqli_fetch_assoc(execute_query($sql));
				//if (array_search(5, $array)) {
				if(isset($funds[$row['type_of_fund']])){
					$tof = $funds[$row['type_of_fund']];
				}
				else{
					$tof = 'NA';
				}
				echo '<tr>
				<td>'.$i++.'</td>
				<td>'.$row['division_name'].'</td>
				<td>'.$row['district_name'].'</td>
				<td>'.$row['col4'].'</td>
				<td>'.$row['mobile_number'].'</td>
				<td>'.($row['type_of_construction']=='3'?'गोदम':'समिति प्रांगण में गोदाम').'</td>
				<td>'.$tof.'</td>
				<td>'.$row['length'].'</td>
				<td>'.$row['width'].'</td>
				<td>'.$row['remarks'].'</td>';
				echo '</tr>';


			}	
			?>
		</tbody>
	</table>
	
	
	<table class="table table-hover table-striped" border="1">
		<thead>
			<tr>
				<th>S.No.</th>
				<th>Division Name</th>
				<th>District Name</th>
				<th>Society Name</th>
				<th>Mobile Number</th>
				<th>Status of Construction</th>
				<th>Type of Fund</th>
				<th>Storage Capacity</th>
				<th>Remarks</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=1;
			$funds = array("6"=>"RKVY", "7"=>"ICDP", "8"=>"AIF", "9"=>"World Bank", "10"=>"Other");
			while($row = mysqli_fetch_array($result2)){
				$sql = 'select count(*) c from test2 where (test2.status!=1 or test2.status is null) and col2="'.$row['col2'].'"';
				$count = mysqli_fetch_assoc(execute_query($sql));
				//if (array_search(5, $array)) {
				if(isset($funds[$row['type_of_fund']])){
					$tof = $funds[$row['type_of_fund']];
				}
				else{
					$tof = 'NA';
				}
				echo '<tr>
				<td>'.$i++.'</td>
				<td>'.$row['division_name'].'</td>
				<td>'.$row['district_name'].'</td>
				<td>'.$row['col4'].'</td>
				<td>'.$row['mobile_number'].'</td>
				<td>'.$row['construction_status'].'</td>
				<td>'.$tof.'</td>
				<td>'.$row['storage_capacity'].'</td>
				<td>'.$row['remarks'].'</td>';
				echo '</tr>';


			}	
			?>
		</tbody>
	</table>
<?php	
	
}
?>



<?php
$serverName = "DESKTOP-3G5O15N\SQLEXPRESS"; //serverName\instanceName

// Since UID and PWD are not specified in the $connectionInfo array,
// The connection will be attempted using Windows Authentication.
$connectionInfo = array( "Database"=>"uppacs");
$conn = sqlsrv_connect( $serverName, $connectionInfo);


if( $conn ) {
    echo "Connection established.<br />";
	$sql = 'SELECT [DCBID], [DCBName], [isDeleted], [DCBEngName] FROM [DCBMaster]';
	$stmt = sqlsrv_query( $conn, $sql);
	if(sqlsrv_errors()){
		print_r(sqlsrv_errors());
	}
	echo '<table>';
	while($row = sqlsrv_fetch_array($stmt)) {
		echo '<tr>';
		foreach($row as $k=>$v){
			echo '<td>'.$v.'</td>';
		}
		echo '</tr>';
	}

}else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}
?>

<?php
include("scripts/settings.php");
$msg='';
$response=1;
$tab=1;
if(isset($_GET['exdid'])){
	$_SESSION['survey_id'] = $_GET['exdid'];
}
if(!isset($_SESSION['survey_id'])){
	die("Invalid Request");
}
else{
	
	$sql = 'SELECT survey_invoice.sno as sno, survey_invoice.society_id as society_id, test2.col4 as society_name, master_society_type.type_name as type_name, division_name, district_name, tehseel_name, block_name,  survey_invoice.latitude as latitude, survey_invoice.longitude as longitude, survey_invoice.mobile_number as mobile_number, liquidation, litigation, concat("user_data/", col2, "/", col6, "/", photo_id) as photo_id, society_building_ownership, society_building_rent_amount, society_building_area, society_registration_no, society_registration_date, email_id, respondent_name, respondent_designation, respondent_aadhaar, active_members, inactive_members, others, col1, col2, col3, col5, col6, approval_status FROM `survey_invoice` left join test2 on test2.sno = society_id left join master_block on master_block.sno = col6 left join master_tehseel on master_tehseel.sno = col5 left join master_district on master_district.sno = col2 left join master_division on master_division.sno = col1 left join master_society_type on master_society_type.sno = col3  where survey_invoice.sno="'.$_SESSION['survey_id'].'"';
	//echo $sql;
	$result_invoice = execute_query($sql);
	if(mysqli_num_rows($result_invoice)==1){
		$row_invoice = mysqli_fetch_assoc($result_invoice);
		if($row_invoice['society_registration_date']==''){
			$row_invoice['society_registration_date'] = date("Y-m-d");
		}
		$_SESSION['survey_id'] = $row_invoice['sno'];
		$sql = 'select * from survey_invoice_sec_2_1 where survey_id="'.$row_invoice['sno'].'"';
		$res_2_1 = execute_query($sql);
		if(mysqli_num_rows($res_2_1)!=0){
			$row_2_1 = mysqli_fetch_assoc($res_2_1);
			$row_2_1['sec_6_access_road'] = $row_2_1['approach_road'];
			$row_2_1['sec_6_2_truck_not_reach'] = $row_2_1['distance_from_approach_road'];
			$row_2_1['sec_7_electrical_connection'] = $row_2_1['electric_connection'];
			$row_2_1['sec_7_electrical_connection_working'] = $row_2_1['electric_connection_working'];
			$row_2_1['sec_7_if_yes'] = $row_2_1['electric_connection_proposal'];
			$row_2_1['sec_8_internet_connection'] = $row_2_1['internet_connectivity'];
			$row_2_1['sec_8_if_yes'] = $row_2_1['internet_service_provider'];
			$row_2_1['sec_6_narrow_tubes'] = $row_2_1['water_govt_tap'];
			$row_2_1['sec_6_water_tank'] = $row_2_1['water_tank'];
			$row_2_1['sec_6_samarsabel'] = $row_2_1['water_submersible'];
			$row_2_1['sec_6_handpump'] = $row_2_1['water_hand_pump'];

		}
		else{
			$row_2_1['investment'] = '';
			$row_2_1['loan'] = '';
			$row_2_1['msp'] = '';
			$row_2_1['msp_comm'] = '';
			$row_2_1['subscribers'] = '';
			$row_2_1['pds'] = '';
			$row_2_1['total_business'] = '';
			$row_2_1['last_year_profit_loss'] = '';
			$row_2_1['last_year_pl_amount'] = '';
			$row_2_1['seq_year_profit_loss'] = '';
			$row_2_1['seq_year_pl_amount'] = '';
			$row_2_1['financial_audit_year'] = '';
			$row_2_1['balance_sheet_year'] = '';
			$row_2_1['construction_status'] = '';
			$row_2_1['approach_road'] = '';
			$row_2_1['distance_from_approach_road'] = '';
			$row_2_1['electric_connection'] = '';
			$row_2_1['electric_connection_proposal'] = '';
			$row_2_1['internet_connectivity'] = '';
			
		}
		
		$sql = 'select * from survey_invoice_sec_2_1_2 where survey_id="'.$row_invoice['sno'].'"';
		$res_2_1_2 = execute_query($sql);
		$i=1;
		$a=1;
		$other_msc = array();
		if(mysqli_num_rows($res_2_1_2)!=0){
			$row_2_1_2['count'] = mysqli_num_rows($res_2_1_2);
			while($row_temp = mysqli_fetch_assoc($res_2_1_2)){
				if($row_temp['other_description']=='msc'){
					$other_msc[$a] = $row_temp['other_amount'];
					$a++;
				}
				else{
					$row_2_1_2['sec_2_1_2_business_description_'.$i] = $row_temp['other_description'];
					$row_2_1_2['sec_2_1_2_value_'.$i] = $row_temp['other_amount'];
					$i++;
				}
			}
			$row_2_1_2['count'] = $i-1;
		}
		else{
			$row_2_1_2['count'] = 1;
			$row_2_1_2['sec_2_1_2_business_description_'.$i] = '';
			$row_2_1_2['sec_2_1_2_value_'.$i] = '';
		}
		
		$sql = 'select * from survey_invoice_sec_2_2 where survey_id="'.$row_invoice['sno'].'"';
		$res_2_2 = execute_query($sql);
		if(mysqli_num_rows($res_2_2)!=0){
			$row_2_2 = mysqli_fetch_assoc($res_2_2);
		}
		else{
			$row_2_2['secretary'] = '';
			$row_2_2['secretary_status'] = '';
			$row_2_2['secretary_cader'] = '';
			$row_2_2['secretary_name'] = '';
			$row_2_2['secretary_mobile'] = '';
			$row_2_2['secretary_aadhaar'] = '';
			$row_2_2['accountant'] = '';
			$row_2_2['assistant_accountant'] = '';
			$row_2_2['seller'] = '';
			$row_2_2['support_staff'] = '';
			$row_2_2['guard'] = '';
			$row_2_2['computer_operator'] = '';
			$row_2_2['govt_program'] = '';
			$row_2_2['other_description'] = '';

		}
		
		$sql = 'select *, concat("user_data/'.$row_invoice['col2'].'/'.$row_invoice['col6'].'/", photo_id) as new_photo_id from survey_invoice_sec_3_1 where survey_id="'.$row_invoice['sno'].'"';
		$res_3_1 = execute_query($sql);
		if(mysqli_num_rows($res_3_1)!=0){
			$row_3_1 = mysqli_fetch_assoc($res_3_1);
			$row_3_1['sec_3_ownership'] = $row_invoice['society_building_ownership'];
			$row_3_1['sec_3_d_boundry'] = $row_3_1['boundry_wall'];
			$row_3_1['sec_3_d_main_gate'] = $row_3_1['main_gate'];
		}
		else{
			$row_3_1['number_of_sides'] = '';
			$row_3_1['total_area'] = '';
			$row_3_1['govt_records'] = '';
			$row_3_1['gata_no'] = '';
			$row_3_1['new_photo_id'] = '';
			$row_3_1['east_side'] = '';
			$row_3_1['west_side'] = '';
			$row_3_1['south_side'] = '';
			$row_3_1['north_side'] = '';
			$row_3_1['on_road_land'] = '';
			$row_3_1['front_side'] = '';
			$row_3_1['remarks'] = '';
			$row_3_1['sec_3_ownership'] = '';
			$row_3_1['sec_3_d_boundry'] = '';
			$row_3_1['sec_3_d_main_gate'] = '';
		}
		
		$sql = 'select * from survey_invoice_sec_3_3 where survey_id="'.$row_invoice['sno'].'"';
		$res_3_3 = execute_query($sql);
		$i=1;
		$a=1;
		if(mysqli_num_rows($res_3_3)!=0){
			$row_3_3['count'] = mysqli_num_rows($res_3_3);
			while($row_temp = mysqli_fetch_assoc($res_3_3)){
				$row_3_3['sec_3_b_type_of_construction_'.$i] = $row_temp['type_of_construction'];
				$row_3_3['sec_3_b_type_of_fund_'.$i] = $row_temp['type_of_fund'];
				$row_3_3['sec_3_b_length_'.$i] = $row_temp['length'];
				$row_3_3['sec_3_b_width_'.$i] = $row_temp['width'];
				$row_3_3['sec_3_b_comment_'.$i] = $row_temp['remarks'];
				$i++;
			}
			$row_3_3['count'] = $i-1;
		}
		else{
			$row_3_3['count'] = 1;
			$row_3_3['sec_3_b_type_of_construction_'.$i] = '';
			$row_3_3['sec_3_b_type_of_fund_'.$i] = '';
			$row_3_3['sec_3_b_length_'.$i] = '';
			$row_3_3['sec_3_b_width_'.$i] = '';
			$row_3_3['sec_3_b_comment_'.$i] = '';
		}
		
		$sql = 'select * from survey_invoice_sec_3_1_sides where survey_id="'.$row_invoice['sno'].'"';
		$res_3_3_side = execute_query($sql);
		if(mysqli_num_rows($res_3_3_side)!=0){
			$sides_data = array();
			$i=1;
			while($row_3_3_side = mysqli_fetch_assoc($res_3_3_side)){
				$sides_data['sec_3_a_'.$i] = $row_3_3_side['length'];
				$i++;
			}
		}
		
		$sql = 'select * from survey_invoice_sec_3_4 where survey_id="'.$row_invoice['sno'].'"';
		//echo $sql;
		$res_3_4 = execute_query($sql);
		if(mysqli_num_rows($res_3_4)!=0){
			$i=1;
			while($row_3_4_temp = mysqli_fetch_assoc($res_3_4)){
				$row_3_4['sec_3_b_godown_length_'.$i] = $row_3_4_temp['length'];
				$row_3_4['sec_3_b_godown_width_'.$i] = $row_3_4_temp['width'];
				$row_3_4['sec_3_b_storage_capacity_'.$i] = $row_3_4_temp['storage_capacity'];
				$row_3_4['sec_3_b_godown_status_'.$i] = $row_3_4_temp['construction_status'];
				$row_3_4['sec_3_b_godown_type_of_fund_'.$i] = $row_3_4_temp['type_of_fund'];
				$row_3_4['sec_3_b_comment_'.$i] = $row_3_4_temp['remarks'];
				$i++;
			}
			$row_3_4['count'] = $i-1;
		}
		else{
			$i=1;
			$row_3_4['count'] = 1;
			$row_3_4['sec_3_b_godown_length_'.$i] = '';
			$row_3_4['sec_3_b_godown_width_'.$i] = '';
			$row_3_4['sec_3_b_storage_capacity_'.$i] = '';
			$row_3_4['sec_3_b_godown_status_'.$i] = '';
			$row_3_4['sec_3_b_godown_type_of_fund_'.$i] = '';
			$row_3_4['sec_3_b_comment_'.$i] = '';
			
		}
		$sql = 'select * from survey_invoice_sec_3_5 where survey_id="'.$row_invoice['sno'].'"';
		$res_3_5_side = execute_query($sql);
		if(mysqli_num_rows($res_3_5_side)!=0){
			$data_3_5 = array();
			$i=1;
			while($row_3_5_side = mysqli_fetch_assoc($res_3_5_side)){
				$row_3_5['sec_3_c_length_'.$i] = $row_3_5_side['total_area'];
				$row_3_5['sec_3_c_vacant_land_status_'.$i] = $row_3_5_side['land_type'];
				$row_3_5['sec_3_c_land_location_'.$i] = $row_3_5_side['location'];
				$row_3_5['sec_3_c_approach_road_'.$i] = $row_3_5_side['approach_road'];
				$row_3_5['sec_3_c_paved_road_'.$i] = $row_3_5_side['approach_road'];
				$i++;
			}
			$row_3_5['sec_3_c_id'] = $i-1;
		}
		else{
			$i=1;
			$row_3_5['sec_3_c_id'] = $i;
			$row_3_5['sec_3_c_length_'.$i] = '';
			$row_3_5['sec_3_c_vacant_land_status_'.$i] = '';
			$row_3_5['sec_3_c_land_location_'.$i] = '';
			$row_3_5['sec_3_c_approach_road_'.$i] = '';
			$row_3_5['sec_3_c_paved_road_'.$i] = '';
		}
		
		$sql = 'select * from survey_invoice_sec_3_6 where survey_id="'.$row_invoice['sno'].'"';
		$res_3_6_side = execute_query($sql);
		if(mysqli_num_rows($res_3_6_side)!=0){
			$data_3_6 = array();
			$i=1;
			while($row_3_6_side = mysqli_fetch_assoc($res_3_6_side)){
				$row_3_6['sec_3_6_type_of_construction'] = $row_3_6_side['type_of_construction'];
				$row_3_6['sec_3_6_rent'] = $row_3_6_side['rent_amount'];
				$row_3_6['sec_3_6_area'] = $row_3_6_side['total_area'];
			}
		}
		else{
			$i=1;
			$row_3_6['sec_3_6_type_of_construction'] = '';
			$row_3_6['sec_3_6_rent'] = '';
			$row_3_6['sec_3_6_area'] = '';
		}
		
		$sql = 'select * from survey_invoice_sec_4 where survey_id="'.$row_invoice['sno'].'"';
		$res_4 = execute_query($sql);
		if(mysqli_num_rows($res_4)!=0){
			$row_4 = mysqli_fetch_assoc($res_4);
			$row_4['sec_4_micro_atm'] = $row_4['micro_atm'];
			$row_4['sec_4_custom_hiring'] = $row_4['custom_hiring_center'];
			$row_4['sec_4_drone'] = $row_4['drone'];
			$row_4['sec_4_chhanna'] = $row_4['chalana'];
			$row_4['sec_4_power_duster'] = $row_4['power_duster'];
			$row_4['sec_4_tractor'] = $row_4['tractor'];
			$row_4['sec_4_chair'] = $row_4['office_chair'];
			$row_4['sec_4_table'] = $row_4['office_table'];
			$row_4['sec_4_almari'] = $row_4['office_almirah'];
			$row_4['sec_4_remarks'] = $row_4['remarks'];
		}
		else{
			$row_4['sec_4_micro_atm'] = '';
			$row_4['sec_4_custom_hiring'] = '';
			$row_4['sec_4_drone'] = '';
			$row_4['sec_4_chhanna'] = '';
			$row_4['sec_4_power_duster'] = '';
			$row_4['sec_4_tractor'] = '';
			$row_4['sec_4_chair'] = '';
			$row_4['sec_4_table'] = '';
			$row_4['sec_4_almari'] = '';
			$row_4['sec_4_remarks'] = '';

		}
		
		$custom_hiring_array = array();
		$sql = 'select survey_id, custom_hiring_id, msc_service from survey_invoice_sec_4_custom_hiring left join master_custom_hiring_center on master_custom_hiring_center.sno = custom_hiring_id where survey_id="'.$row_invoice['sno'].'"';
		//echo $sql;
		$result_sec_4_custom_hiring = execute_query($sql);
		if(mysqli_num_rows($result_sec_4_custom_hiring)!=0){
			$i=1;
			while($row_sec_4_custom_hiring = mysqli_fetch_assoc($result_sec_4_custom_hiring)){
				if($row_sec_4_custom_hiring['custom_hiring_id']=='other'){
					$row_sec_4_custom_hiring['msc_service'] = 'अन्य';
				}
				$custom_hiring_array[$row_sec_4_custom_hiring['custom_hiring_id']] = $row_sec_4_custom_hiring['msc_service'];
			}
		}
		
		$sql = 'select * from survey_invoice_sec_5 where survey_id="'.$row_invoice['sno'].'"';
		$res_5 = execute_query($sql);
		if(mysqli_num_rows($res_5)!=0){
			$row_5 = mysqli_fetch_assoc($res_5);
			$row_5['sec_5_built_building']=$row_5['building_status'];
			$row_5['sec_5_detailed_information']=$row_5['building_status_remarks'];
			$row_5['sec_6_a_length']=$row_5['floor_length'];
			$row_5['sec_6_a_width']=$row_5['floor_width'];
			$row_5['sec_6_b_length']=$row_5['wall_length'];
			$row_5['sec_6_b_width']=$row_5['wall_width'];
			$row_5['sec_6_c_length']=$row_5['paint_length'];
			$row_5['sec_6_c_width']=$row_5['paint_width'];
			$row_5['sec_6_d_length']=$row_5['roof_length'];
			$row_5['sec_6_d_width']=$row_5['roof_width'];
			
			$row_5['sec_6_e_floor']=$row_5['washroom_floor'];
			$row_5['sec_6_e_plaster']=$row_5['washroom_plaster'];
			$row_5['sec_6_e_ceiling']=$row_5['washroom_roof'];
			$row_5['sec_6_e_seat']=$row_5['washroom_seat'];
			$row_5['sec_6_e_plumbing']=$row_5['washroom_plumbing'];
			$row_5['sec_6_f_number_of_door']=$row_5['doors'];
			$row_5['sec_6_g_number_of_window']=$row_5['windows'];
			$row_5['sec_6_h_length']=$row_5['plaster_wall'];
			$row_5['sec_6_h_width']=$row_5['plaster_roof'];
			$row_5['sec_6_i_other']=$row_5['others'];
			
			if($row_5['floor_image']!=''){
				$row_5['sec_6_a_img'] = 'user_data/'.$row_invoice['col2'].'/'.$row_invoice['col6'].'/'.$row_5['floor_image'];	
			}
			else{
				$row_5['sec_6_a_img'] = '';
			}
			if($row_5['wall_image']!=''){
				$row_5['sec_6_b_img'] = 'user_data/'.$row_invoice['col2'].'/'.$row_invoice['col6'].'/'.$row_5['wall_image'];
			}
			else{
				$row_5['sec_6_b_img'] = '';
			}
			if($row_5['paint_image']!=''){
				$row_5['sec_6_c_img'] = 'user_data/'.$row_invoice['col2'].'/'.$row_invoice['col6'].'/'.$row_5['paint_image'];
			}
			else{
				$row_5['sec_6_c_img'] = '';
			}
			if($row_5['roof_image']!=''){
				$row_5['sec_6_d_img'] = 'user_data/'.$row_invoice['col2'].'/'.$row_invoice['col6'].'/'.$row_5['roof_image'];
			}
			else{
				$row_5['sec_6_d_img'] = '';
			}			
		}
		else{
			$row_5['sec_5_built_building'] = "";
			$row_5['sec_5_detailed_information'] = "";
			$row_5['sec_6_a_length'] = "";
			$row_5['sec_6_a_width'] = "";
			$row_5['sec_6_b_length'] = "";
			$row_5['sec_6_b_width'] = "";
			$row_5['sec_6_c_length'] = "";
			$row_5['sec_6_c_width'] = "";
			$row_5['sec_6_d_width'] = "";
			$row_5['sec_6_d_length'] = "";
			$row_5['sec_6_d_width'] = "";
			$row_5['sec_6_e_floor'] = "";
			$row_5['sec_6_e_plaster'] = "";
			$row_5['sec_6_e_ceiling'] = "";
			$row_5['sec_6_e_seat'] = "";
			$row_5['sec_6_e_plumbing'] = "";
			$row_5['sec_6_f_number_of_door'] = "";
			$row_5['sec_6_g_number_of_window'] = "";
			$row_5['sec_6_h_length'] = "";
			$row_5['sec_6_h_width'] = "";
			$row_5['sec_6_i_other'] = "";
			$row_5['sec_6_a_img'] = '';
			$row_5['sec_6_b_img'] = '';
			$row_5['sec_6_c_img'] = '';
			$row_5['sec_6_d_img'] = '';
		}
		
		
		$sql = 'select * from survey_invoice_sec_11 where survey_id="'.$row_invoice['sno'].'"';
		$res_9 = execute_query($sql);
		if(mysqli_num_rows($res_9)!=0){
			$row_9 = mysqli_fetch_assoc($res_9);
			$row_9['sec_9_a_length'] = $row_9['godown_length'];
			$row_9['sec_9_a_width'] = $row_9['godown_width'];
			$row_9['sec_9_a_capacity_in_mt'] = $row_9['godown_capacity'];
			$row_9['sec_9_b_length'] = $row_9['bathroom_length'];
			$row_9['sec_9_b_width'] = $row_9['bathroom_width'];
			$row_9['sec_9_c_length'] = $row_9['showroom_length'];
			$row_9['sec_9_c_width'] = $row_9['showroom_width'];
			$row_9['sec_9_d_boundary_wall_length'] = $row_9['boundary_length'];
			$row_9['sec_9_d_boundary_wall_width'] = $row_9['boundary_width'];
			$row_9['sec_9_e_multipurpose_hall_length'] = $row_9['multipurpose_length'];
			$row_9['sec_9_e_multipurpose_hall_width'] = $row_9['multipurpose_width'];
		}
		else{
			$row_9['sec_9_a_length'] = '';
			$row_9['sec_9_a_width'] = '';
			$row_9['sec_9_a_capacity_in_mt'] = '';
			$row_9['sec_9_b_length'] = '';
			$row_9['sec_9_b_width'] = '';
			$row_9['sec_9_c_length'] = '';
			$row_9['sec_9_c_width'] = '';
			$row_9['sec_9_d_boundary_wall_length'] = '';
			$row_9['sec_9_d_boundary_wall_width'] = '';
			$row_9['sec_9_e_multipurpose_hall_length'] = '';
			$row_9['sec_9_e_multipurpose_hall_width'] = '';

		}		
		$response=1;
	}
}
?>

<?php
page_header_start();
?>
<script src="js/survey_validate.js"></script>
<?php
page_header_end();

switch($response){
	case 1:{
?>
				<div class="row">					
					<div class="col-md-8 col-sm-12 mx-auto">
						<div class="card">
							<div class="card-body">
								<div class="row d-flex my-auto">
									<div class="col-md-12">
										<h2 class="text-center">सर्वेक्षण प्रपत्र</h2>
										<form action="scripts/ajax.php" method="post" enctype="multipart/form-data" id="user_form" name="user_form">
											<div id="steps-container">
	<!-------------------1st start--------------------------------------------------------------------->
												<div class="step">
												
													<h4>1.समिति का विवरण</h4>
													<div class="col-sm-12">
														<div class="row">
															<div class="col-md-4">
																<div class="row">
																	<div class="col-sm-4">
																		<h6>समिति का नाम : </h6>
																	</div>
																	<div class="col-sm-8">
																		<?php echo $row_invoice['society_name']; ?>
																		<input disabled type="hidden" id="society_code" value="<?php echo $row_invoice['society_id'];?>">
																	</div>
																</div>
																<div class="row">
																	<div class="col-md-4">
																		<h6>मण्डल : </h6>
																	</div>
																	<div class="col-md-8">
																		<?php echo $row_invoice['division_name']; ?>
																	</div>
																</div>
																<div class="row">
																	<div class="col-md-4">
																		<h6>जिला : </h6>
																	</div>
																	<div class="col-md-8">
																		<?php echo $row_invoice['district_name']; ?>
																	</div>
																</div>
																<div class="row">
																	<div class="col-md-4">
																		<h6>तहसील : </h6>
																	</div>
																	<div class="col-md-8">
																		<?php echo $row_invoice['tehseel_name']; ?>
																	</div>
																</div>
																<div class="row">
																	<div class="col-md-4">
																		<h6>ब्लाक : </h6>
																	</div>
																	<div class="col-md-8">
																		<?php echo $row_invoice['block_name']; ?>
																	</div>
																</div>
																<div class="row">
																	<div class="col-md-4">
																		<h6>समिति का प्रकार : </h6>
																	</div>
																	<div class="col-md-8">
																		<?php echo $row_invoice['type_name']; ?>
																	</div>
																</div>
																<div class="row">
																	<div class="col-md-4">
																		<h6>मोबाइल नंबर : </h6>
																	</div>
																	<div class="col-md-8">
																		<?php echo $row_invoice['mobile_number']; ?>
																	</div>
																</div>
															</div>
															<div class="col-md-8">
																<div class="row">
																	<div class="col-md-2">
																		<label>Latitude</label>
																		<input disabled type="text" id="lat" disabled="disabled" value="<?php echo $row_invoice['latitude']; ?>" class="form-control">
																		<label>Longitude</label>
																		<input disabled type="text" id="long" disabled="disabled" value="<?php echo $row_invoice['longitude']; ?>" class="form-control">
																	</div>
																	<div class="col-md-10" id="map_container">
																		<iframe id="googlemap" src="https://maps.google.com/maps?q=<?php echo $row_invoice['latitude'].','.$row_invoice['longitude'];?>&hl=en&z=13&amp;output=embed" width="100%" height="100%" style="border:1px solid; border-radius:10px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
																	</div>
																</div>
																
															</div>
														</div>
														<hr/>
														
														<div class="row">
															<div class="col-sm-4 form-group">
																<label>क्या समिति परिसमापन (Liquidation) में है?</label>
																<select disabled name="sec1_liquidation" id="sec1_liquidation" tabindex="<?php echo $tab++; ?>"  class="form-control">
																	<option value="">--Select--</option>
																	<option value="yes" <?php echo ($row_invoice['liquidation']=='yes')?' selected="selected"':'';?> >हां</option>
																	<option value="no" <?php echo ($row_invoice['liquidation']=='no')?' selected="selected"':'';?>>नहीं</option>
																</select>
															</div>
															<div class="col-sm-4 form-group">
																<label>क्या समिति पर कोई वाद (Litigation) न्यायालय में विचाराधीन हैं?</label>
																<select disabled name="sec_1_litigation" id="sec_1_litigation" tabindex="<?php echo $tab++; ?>"  class="form-control">
																	<option value="">--Select--</option>
																	<option value="yes" <?php echo ($row_invoice['litigation']=='yes')?' selected="selected"':'';?>>हां</option>
																	<option value="no" <?php echo ($row_invoice['litigation']=='no')?' selected="selected"':'';?>>नहीं</option>
																</select>
															</div>
														</div>
														<div class="row">
															<div class="col-sm-2 form-group">
																<label>समिति पंजीकरण संख्या</label>
																<br/>
																<input disabled type="text" name="society_registration_no" id="society_registration_no" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_invoice['society_registration_no']; ?>">
															</div>
															<div class="col-sm-2 form-group">
																<label>समिति पंजीकरण दिनांक</label>
																<input disabled type="text" name="society_registration_date" id="society_registration_date" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo date("d-m-Y", strtotime($row_invoice['society_registration_date'])); ?>">
															</div>
															<div class="col-sm-4 form-group">
																<label>ई-मेल आई.डी.</label>
																<input disabled type="text" name="sec1_email" id="sec1_email" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_invoice['email_id']; ?>">
															</div>
															<?php
															//echo $_SERVER['DOCUMENT_ROOT'].'/'.$row_invoice['photo_id'];
															if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$row_invoice['photo_id'])) {
															?>
															<div class="col-sm-2 form-group">
																<img src="<?php echo 'https://upcod.in/'.$row_invoice['photo_id']; ?>" class="img-fluid img-thumbnail" style="height:50px;">
																<label><a href="<?php echo 'https://upcod.in/'.$row_invoice['photo_id']; ?>" target="_blank">संलग्न फोटो देखें</a></label>
																
															</div>
															<?php
															}
															else{
															?>
															<div class="col-sm-4 form-group">
																<label>समिति कि फोटो संलग्न करें</label>
																<input disabled type="file" accept=".jpg, .jpeg, .gif, .png, .bmp"name="society_photo" id="society_photo" tabindex="<?php echo $tab++; ?>"  class="form-control">
																
															</div>
															
															<?php

															}
															?>
															

														</div>
														<div class="row">
															<div class="col-sm-4 form-group">
																<label>प्रपत्र भर रहे व्यक्ति का नाम</label>
																<input disabled type="text" name="person_name" id="person_name" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_invoice['respondent_name']; ?>">
															</div>
															<div class="col-sm-4 form-group">
																<label>प्रपत्र भर रहे व्यक्ति का पदनाम</label>
																<input disabled type="text" name="person_designation" id="person_designation" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_invoice['respondent_designation']; ?>">
															</div>
															<div class="col-sm-4 form-group">
																<label>प्रपत्र भर रहे व्यक्ति का आधार नम्बर</label>
																<input disabled type="text" name="person_aadhaar" id="person_aadhaar" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_invoice['respondent_aadhaar']; ?>">
															</div>
														</div>								   
														<div class="row">
																<div class="col-sm-4 form-group">
																	<label>सक्रिय सदस्य (संख्या)</label>
																	<input disabled type="text" name="sec_1_members_active" id="sec_1_members_active" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_invoice['active_members']; ?>">
																</div>
																<div class="col-sm-4 form-group">
																	<label>निष्क्रिय सदस्य (संख्या)</label>
																	<input disabled type="text" name="sec_1_members_non_active" id="sec_1_members_non_active" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_invoice['inactive_members']; ?>">
																</div>
																<div class="col-sm-4 form-group">
																	<label>अन्य</label>
																	<input disabled type="text" name="sec_1_others" id="sec_1_others" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_invoice['others']; ?>">
																</div>
															</div>
													</div>
													
												</div>
	<!----------------2.1 start-------------------------------------------------------->
												<div class="step">
													<h4>2.1. कार्य व व्यवसाय</h4>
													<div class="col-sm-12">
														<div class="row">
															<div class="col-sm-2 form-group">
																<label>उर्वरक / कृषि निवेश (कुल टर्नोवर रुपये में)</label>
																<input disabled type="text" name="sec_1_investment" id="sec_1_investment" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_2_1['investment'];?>">
															</div>
															<div class="col-sm-2 form-group">
																<label>ऋण (कुल टर्नोवर रुपये में) </label>
																<input disabled type="text" name="sec_1_loan" id="sec_1_loan" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_2_1['loan']; ?>">
															</div>
															<div class="col-sm-2 form-group">
																<label>मूल्य समर्थन (कुल टर्नोवर रुपये में)</label>
																<input disabled type="text" name="sec_1_msp" id="sec_1_msp" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_2_1['msp']; ?>">
															</div>
															<div class="col-sm-2 form-group">
																<label>मूल्य समर्थन से प्राप्त कमीशन (रुपये में)</label>
																<input disabled type="text" name="sec_1_msp_comm" id="sec_1_msp_comm" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_2_1['msp_comm']; ?>">
															</div>
															<div class="col-sm-2 form-group">
																<label>उपभोक्ता</label>
																<input disabled type="text" name="sec_1_subscriber" id="sec_1_subscriber" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_2_1['subscribers']; ?>">
															</div>
															<div class="col-sm-2 form-group">
																<label>सार्वजनिक वितरण प्रणाली (PDS)</label>
																<select disabled name="sec_1_pds" id="sec_1_pds" tabindex="<?php echo $tab++; ?>"  class="form-control">
																	<option value="">--Select--</option>
																	<option value="yes" <?php echo $row_2_1['pds']=='yes'?' selected="selected"':''?>>है</option>
																	<option value="no" <?php echo $row_2_1['pds']=='no'?' selected="selected"':''?>>नहीं</option>
																</select>
															</div>

														</div>
														<div class="row">
															<div class="col-sm-4 form-group">
																<label>कुल व्यवसाय (कुल टर्नोवर रुपये में)</label>
																<input disabled type="text" name="sec_1_total_business" id="sec_1_total_business" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_2_1['total_business']; ?>">
															</div>
															<div class="col-sm-2 form-group">
																<label>गत वित्तीय वर्ष में लाभ/हानि</label>
																<select disabled type="text" name="sec_1_profit_loss" id="sec_1_profit_loss" tabindex="<?php echo $tab++; ?>"  class="form-control" class="col-sm-1 form-group" tabindex="<?php echo $tab++; ?>">
																	<option value="">--Select--</option>
																	<option value="loss" <?php echo $row_2_1['last_year_profit_loss']=='loss'?' selected="selected"':''?>>हानि</option>
																	<option value="profit" <?php echo $row_2_1['last_year_profit_loss']=='profit'?' selected="selected"':''?>>लाभ</option>

																</select>
															</div>
															<div class="col-sm-2 form-group">
																<label>लाभ/हानि (धनराशि रुपये में)</label>
																<input disabled type="text" name="sec_1_profit_loss_amount" id="sec_1_profit_loss_amount" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_2_1['last_year_pl_amount']; ?>">
															</div>
															<div class="col-sm-2 form-group">
																<label>क्रमिक लाभ/हानि</label>
																<select disabled type="text" name="sec_1_sequentially" id="sec_1_sequentially" tabindex="<?php echo $tab++; ?>"  class="form-control" class="col-sm-1 form-group" tabindex="<?php echo $tab++; ?>">
																	<option value="">--Select--</option>
																	<option value="loss" <?php echo $row_2_1['seq_year_profit_loss']=='loss'?' selected="selected"':''?>>हानि</option>
																	<option value="profit" <?php echo $row_2_1['seq_year_profit_loss']=='profit'?' selected="selected"':''?>>लाभ</option>

																</select>
															</div>
															<div class="col-sm-2 form-group">
																<label>लाभ/हानि (धनराशि रुपये में)</label>
																<input disabled type="text" name="sec_1_sequentially_amount" id="sec_1_sequentially_amount" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_2_1['seq_year_pl_amount']; ?>">
															</div>

														</div>	
														<div class="row">
															<div class="col-sm-4 form-group">
																<label>संतुलन पत्र किस वर्ष तक बना है</label>
																<select disabled name="sec2_balance_sheet" id="sec2_balance_sheet" tabindex="<?php echo $tab++; ?>" class="form-control">
																	<option value="">--Select--</option>
																	<?php
																	for($i=2022; $i>=2015; $i--){
																		echo '<option value="'.$i.'" ';
																		if($i==$row_2_1['balance_sheet_year']){
																			echo ' selected="selected" ';
																		}
																		echo ' >'.$i.'</option>';	
																	}
																	?>
																	<option value="old" <?php echo $row_2_1['balance_sheet_year']=='old'?' selected="selected"':''?>>2015 से पूर्व में</option>																	
																</select>
															</div>
															<div class="col-sm-4 form-group">
																<label>वित्तिय आडिट किस वर्ष तक हुआ है</label>
																<select disabled name="sec2_financial_audit" id="sec2_financial_audit" tabindex="<?php echo $tab++; ?>" class="form-control">
																	<option value="">--Select--</option>
																	<?php
																	for($i=2022; $i>=2015; $i--){
																		echo '<option value="'.$i.'" ';
																		if($i==$row_2_1['financial_audit_year']){
																			echo ' selected="selected" ';
																		}
																		echo ' >'.$i.'</option>';	
																	}
																	?>
																	<option value="old" <?php echo $row_2_1['financial_audit_year']=='old'?' selected="selected"':''?>>2015 से पूर्व में</option>																		
																</select>
															</div>	
														</div>
															<h4>2.1.2. अन्य कार्य व व्यवसाय</h4>
															<div id="other_business">
																
																<?php
																for($i=1; $i<=$row_2_1_2['count']; $i++){	
																?>
																<div class="row">
																	<div class="col-sm-3 form-group">
																		<label>व्यवसाय का विवरण </label>
																		<input disabled type="text" name="sec_2_1_2_business_description_<?php echo $i; ?>" id="sec_2_1_2_business_description_<?php echo $i; ?>" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_2_1_2['sec_2_1_2_business_description_'.$i];?>">
																	</div>

																	<div class="col-sm-3 form-group">
																		<label>वार्षिक टर्नोवर</label>
																		<input disabled type="text" name="sec_2_1_2_value_<?php echo $i; ?>" id="sec_2_1_2_value_<?php echo $i; ?>" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_2_1_2['sec_2_1_2_value_'.$i];?>">
																	</div>
																</div>
																<?php } ?>
															</div>
															<div class="row">
																<div class="col-sm-3 form-group">
																	<label>क्या समिति बहुसेवा केंद्र के रूप में चयनित है</label>
																	<select disabled name="sec_1_1_2_msc" id="sec_1_1_2_msc" tabindex="<?php echo $tab++; ?>"  class="form-control" onChange="hide_show(this.value, '#msc_services', 'yes');">
																		<option value="">--Select--</option>
																		<option value="yes" <?php if(!empty($other_msc)){echo ' selected="selected"'; $msc_display='block';}?>>है</option>
																		<option value="no" <?php if(empty($other_msc)){echo ' selected="selected"'; $msc_display='none';}?>>नहीं</option>
																	</select>
																</div>
																<div class="col-sm-3 form-group" id="msc_services" style="display: <?php echo $msc_display;?>">
																	<label>बहुसेवा केंद्र कि गतिविधियों के बारे में बताये</label>
																	<select disabled name="sec_1_1_2_msc_service[]" id="sec_1_1_2_msc_service" tabindex="<?php echo $tab++; ?>"  class="form-control" multiple="multiple" onChange="msc_services_other()">
																		<?php 
																		$sql = 'select * from master_msc_services';
																		$result_msc = execute_query($sql);
																		while($row_msc = mysqli_fetch_assoc($result_msc)){
																			echo ' <option value="'.$row_msc['sno'].'" ';
																			if(in_array($row_msc['sno'], $other_msc)){
																				echo ' selected="selected" ';
																			}
																			echo '>'.$row_msc['msc_service'].'</option>';
																		}
																		?>
																		<option value="other">अन्य</option>
																	</select>
																</div>
																<div class="col-sm-3 form-group" id="msc_services_other" style="display: none;">
																	<label>अन्य गतिविधियों के बारे में बताये</label>
																	<input disabled type="text" name="sec_1_1_2_msc_service_other" id="sec_1_1_2_msc_service_other" tabindex="<?php echo $tab++; ?>"  class="form-control">
																</div>
															</div>
													</div>
													
												</div>
	<!------------------2.2 Start---------------------------------------------------------------------->
												<div class="step">
													<h4>2.2. मानव सम्पदा</h4>
													<div class="col-sm-12">
														<div class="row">
															<div class="col-sm-4 form-group">
																<label>सचिव</label>
																<select disabled class="form-control " type="select"  value="yes" name="sec_2_secretary" id="sec_2_secretary" tabindex="<?php echo $tab++;?>" onChange="hide_show(this.value, '#sachiv_status', 'yes'); if(this.value=='yes'){$('#secretary_detail').css('display', 'flex')}else{$('#secretary_detail').css('display', 'none'); $('#sachiv_cader').css('display', 'none');}" >
																	<option value="">--Select--</option>
																	<option value="yes" <?php $status_display='none'; $cader_display='none'; if($row_2_2['secretary']=='yes'){ echo ' selected="selected" '; $status_display='block'; $cader_display='block';}?>>है</option>
																	<option value="no" <?php if($row_2_2['secretary']=='no'){ echo ' selected="selected" '; $status_display='none';}?>>नहीं</option>
																</select>
															</div>
															<div class="col-sm-4 form-group" id="sachiv_status" style="display: <?php echo $status_display; ?>">
																<label>यदि सचिव है तो</label>
																	<select disabled class="form-control " type="checkbox" id="sec_2_if_yes_status"  name="sec_2_if_yes_status" tabindex="<?php echo $tab++; ?>" onChange="hide_show(this.value, '#sachiv_cader', ['regular', 'additional_charge']);">
																		<option value="">--Select--</option>
																		<option value="regular" <?php echo $row_2_2['secretary_status']=='regular'?' selected="selected" ':''; ?>>नियमित है</option>
																		<option value="additional_charge" <?php echo $row_2_2['secretary_status']=='additional_charge'?' selected="selected" ':''; ?>>अतिरिक्त प्रभार</option>
																	</select>
															</div>
															<div class="col-sm-4 form-group" id="sachiv_cader" style="display: <?php echo $cader_display; ?>">
																<label>कैडर/नान-कैडर</label>
																	<select disabled class="form-control " type="checkbox" id="sec_2_if_yes"  name="sec_2_if_yes" tabindex="<?php echo $tab++; ?>">
																		<option value="">--Select--</option>
																		<option value="cader" <?php echo $row_2_2['secretary_cader']=='cader'?' selected="selected" ':''; ?>>कैडर से हैं</option>
																		<option value="supervisor" <?php echo $row_2_2['secretary_cader']=='supervisor'?' selected="selected" ':''; ?>>सुपरवईजर</option>
																		<option value="non_cader" <?php echo $row_2_2['secretary_cader']=='non_cader'?' selected="selected" ':''; ?>>नान कैडर से हैं</option>
																	</select>
															</div>
														</div>
														<div class="row" id="secretary_detail" style="display: <?php if($row_2_2['secretary']=='yes'){ echo ' flex ';}else{echo ' none ';}?>">
															<div class="col-sm-4 form-group">
																<label>सचिव का नाम</label>
																<input disabled class="form-control " type="text" name="sec_2_secretary_name" id="sec_2_secretary_name" tabindex="<?php echo $tab++;?>" value="<?php echo $row_2_2['secretary_name']; ?>">
															</div>
															<div class="col-sm-4 form-group" id="sachiv_status">
																<label>सचिव का मोबाईल नम्बर</label>
																<input disabled class="form-control " type="text" name="sec_2_secretary_mobile_no" id="sec_2_secretary_mobile_no" tabindex="<?php echo $tab++;?>" value="<?php echo $row_2_2['secretary_mobile']; ?>">
															</div>
															<div class="col-sm-4 form-group" id="sachiv_cader">
																<label>सचिव का आधार नम्बर</label>
																<input disabled class="form-control " type="text" name="sec_2_secretary_aadhaar" id="sec_2_secretary_aadhaar" tabindex="<?php echo $tab++;?>" value="<?php echo $row_2_2['secretary_aadhaar']; ?>">
															</div>
														</div>
														<div class="row">										
															<div class="col-sm-4 form-group">
																<label>लेखाकार</label>
																	<select disabled class="form-control " type="checkbox"value="yes"  id="sec_2_accountant" name="sec_2_accountant" tabindex="<?php echo $tab++; ?>" onChange="hide_show(this.value, '#accountant_count', 'yes')">
																		<option value="">--Select--</option>
																		<option value="yes" <?php $accountant_display='none'; if($row_2_2['accountant']!='no'){ echo ' selected="selected" '; $accountant_display='block';}?>>है</option>
																		<option value="no" <?php if($row_2_2['accountant']=='no'){ echo ' selected="selected" '; $accountant_display='none';}?>>नहीं</option>
																	</select>
															</div>

															<div class="col-sm-4 form-group" id="accountant_count" style="display: <?php echo $accountant_display;?>">
																<label>लेखाकार (संख्या)</label>
																	<input disabled type="text" name="sec_2_accountant_count" id="sec_2_accountant_count" tabindex="<?php echo $tab++; ?>"  class="form-control" tabindex="<?php echo $tab++; ?>" value="<?php echo $row_2_2['accountant']; ?>">
															</div>
														</div>
														<div class="row">
															<div class="col-sm-4 form-group">
																<label>कंप्यूटर आपरेटर / कार्यालय सहायक</label>
																<select disabled class="form-control " type="checkbox" id="sec_2_computer_operator" name="sec_2_computer_operator" value="yes" tabindex="<?php echo $tab++; ?>"  onChange="computer_operator(this.value)">
																	<option value="">--Select--</option>
																	<option value="yes" <?php $computer_operator_display='none'; if($row_2_2['computer_operator']!='no'){ echo ' selected="selected" '; $computer_operator_display='block';}?>>है</option>
																	<option value="no" <?php if($row_2_2['computer_operator']=='no'){ echo ' selected="selected" '; $computer_operator_display='none';}?>>नहीं</option>
																</select>
															</div>
															<div class="col-sm-4 form-group" id="computer_operator_count" style="display: <?php echo $computer_operator_display;?>">
																<label>कंप्यूटर आपरेटर / कार्यालय सहायक (संख्या)</label>
																<input disabled type="text" name="sec_2_computer_operator_count" id="sec_2_computer_operator_count" tabindex="<?php echo $tab++; ?>"  class="form-control" tabindex="<?php echo $tab++; ?>" value="<?php echo $row_2_2['computer_operator']; ?>">
															</div>
														</div>
														
														<div class="row">										
															<div class="col-sm-4 form-group">
																<label>सहायक लेखाकार</label>
																<select disabled class="form-control " type="checkbox"value="yes"  id="sec_2_assistant_accountant" name="sec_2_assistant_accountant" tabindex="<?php echo $tab++; ?>" onChange="hide_show(this.value, '#assistant_accountant_count', 'yes')">
																	<option value="">--Select--</option>
																	<option value="yes" <?php $asst_accnt_display='none'; if($row_2_2['assistant_accountant']!='no'){ echo ' selected="selected" '; $asst_accnt_display='block';}?>>है</option>
																	<option value="no" <?php if($row_2_2['assistant_accountant']=='no'){ echo ' selected="selected" '; $asst_accnt_display='none';}?>>नहीं</option>
																</select>
															</div>
															<div class="col-sm-4 form-group" id="assistant_accountant_count" style="display: <?php echo $asst_accnt_display; ?>;">
																<label>सहायक लेखाकार (संख्या)</label>
																	<input disabled type="text" name="sec_2_assistant_accountant_count" id="sec_2_assistant_accountant_count" tabindex="<?php echo $tab++; ?>"  class="form-control" tabindex="<?php echo $tab++; ?>" value="<?php echo $row_2_2['assistant_accountant']; ?>">
															</div>
														</div>
														<div class="row">
															
															<div class="col-sm-4 form-group">
															<label>विक्रेता</label>
																<select disabled class="form-control " type="checkbox" id="sec_2_seller" name="sec_2_seller" value="yes" tabindex="<?php echo $tab++; ?>" onChange="seller(this.value)">
																	<option value="">--Select--</option>
																	<option value="yes" <?php $seller_display='none'; if($row_2_2['seller']!='no'){ echo ' selected="selected" '; $seller_display='block';}?>>है</option>
																	<option value="no" <?php if($row_2_2['seller']=='no'){ echo ' selected="selected" '; $seller_display='none';}?>>नहीं</option>
																</select>
															</div>
															<div class="col-sm-4 form-group" id="seller_count" style="display: <?php echo $seller_display; ?>;">
																<label>विक्रेता (संख्या)</label>
																<input disabled type="text" name="sec_2_seller_count" id="sec_2_seller_count" tabindex="<?php echo $tab++; ?>"  class="form-control" tabindex="<?php echo $tab++; ?>" value="<?php echo $row_2_2['seller']; ?>">
															</div>
														</div>
														<div class="row">
															<div class="col-sm-4 form-group">
																<label>सहयोगी</label>
																<select disabled class="form-control " type="checkbox" id="sec_2_support_staff" name="sec_2_support_staff" value="yes" tabindex="<?php echo $tab++; ?>"  onChange="support_staff(this.value)">
																	<option value="">--Select--</option>
																	<option value="yes" <?php $support_staff_display='none'; if($row_2_2['support_staff']!='no'){ echo ' selected="selected" '; $support_staff_display='block';}?>>है</option>
																	<option value="no" <?php if($row_2_2['support_staff']=='no'){ echo ' selected="selected" '; $support_staff_display='none';}?>>नहीं</option>
																</select>
															</div>
															<div class="col-sm-4 form-group" id="support_staff_count" style="display: <?php echo $support_staff_display; ?>;">
																<label>सहयोगी (संख्या)</label>
																<input disabled type="text" name="sec_2_support_staff_count" id="sec_2_support_staff_count" tabindex="<?php echo $tab++; ?>"  class="form-control" tabindex="<?php echo $tab++; ?>" value="<?php echo $row_2_2['support_staff']; ?>">
															</div>
														</div>
														<div class="row">
															<div class="col-sm-4 form-group">
																<label>चौकीदार</label>
																<select disabled class="form-control " type="checkbox" id="sec_2_guard" name="sec_2_guard" value="yes" tabindex="<?php echo $tab++; ?>"  onChange="guard(this.value)">
																	<option value="">--Select--</option>
																	<option value="yes" <?php $guard_display='none'; if($row_2_2['guard']!='no'){ echo ' selected="selected" '; $guard_display='block';}?>>है</option>
																	<option value="no" <?php if($row_2_2['guard']=='no'){ echo ' selected="selected" '; $guard_display='none';}?>>नहीं</option>
																</select>
															</div>
															<div class="col-sm-4 form-group" id="guard_count" style="display: <?php echo $guard_display; ?>">
																<label>चौकीदार (संख्या)</label>
																<input disabled type="text" name="sec_2_guard_count" id="sec_2_guard_count" tabindex="<?php echo $tab++; ?>"  class="form-control" tabindex="<?php echo $tab++; ?>" value="<?php echo $row_2_2['guard']; ?>">
															</div>
														</div>
														
														<div class="row">
															<div class="col-sm-6 form-group">
																<label>केंद्र सरकार की योजना अंतर्गत चयनित सघन सहकारी समिति के कंप्यूटरीकरण की कार्य योजना</label>
																<select disabled class="form-control " type="select"  value="yes" name="sec_2_govt_program" id="sec_2_govt_program" tabindex="<?php echo $tab++;?>">
																	<option value="">--Select--</option>
																	<option value="first" <?php echo $row_2_2['govt_program']=='first'?' selected="selected" ':'';?>>प्रथम चरण</option>
																	<option value="second" <?php echo $row_2_2['govt_program']=='second'?' selected="selected" ':'';?>>द्वितीय चरण</option>
																	<option value="third" <?php echo $row_2_2['govt_program']=='third'?' selected="selected" ':'';?>>तृतीय चरण</option>
																	<option value="na" <?php echo $row_2_2['govt_program']=='na'?' selected="selected" ':'';?>>अभी चयनित नहीं</option>
																</select>
															</div>	
															<div class="col-sm-6 form-group">
																<label>अन्य विवरण (यदि आवश्यक हो)</label>
																<input disabled type="text" name="sec_2_other_description" id="sec_2_other_description" tabindex="<?php echo $tab++; ?>"  class="form-control" tabindex="<?php echo $tab++; ?>" value="<?php echo $row_2_2['other_description']; ?>">
															</div>
														</div>												
															
													</div>
												</div>
	<!---------------3rd Start---------------------------------------------------------------->
												
												<div class="step">
													<h4>3.समिति भवन/सम्पत्ति का विवरण</h2>
													<div class="row">
														<div class="col-sm-12">
															<div class="col-sm-3 form-group">
																<label>समिति भवन का स्वामित्व </label>
																<select disabled name="sec_3_ownership" id="sec_3_ownership" tabindex="<?php echo $tab++; ?>" class="form-control" onChange="hide_show(this.value, '#sec_3_rented', 'rent'); hide_show(this.value, '#sec_3_other', 'other');">
																	<option value="">--Select--</option>
																	<option value="own" <?php $sec_3_rented_display='none'; $sec_3_other_display='none'; $sec_3_display='flex'; if($row_3_1['sec_3_ownership']=='own'){echo ' selected="selected" '; $sec_3_display='flex';}?>>समिति के स्वामित्व में है</option>
																	<option value="rent" <?php if($row_3_1['sec_3_ownership']=='rent'){echo ' selected="selected" '; $sec_3_rented_display='flex';}?>>किराये पर है</option>
																	<option value="other" <?php if($row_3_1['sec_3_ownership']!='rent' && $row_3_1['sec_3_ownership']!='own' && $row_3_1['sec_3_ownership']!=''){echo ' selected="selected" '; $sec_3_other_display='flex';}?>>अन्य स्थिती</option>
																</select>
															</div>
															<div id="sec_3_rented" style="display: <?php echo $sec_3_rented_display;?>">
																<div class="col-sm-3 form-group">
																	<label>समिति भवन का मासिक किराया </label>
																	<input disabled name="sec_3_building_rent" id="sec_3_building_rent" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_invoice['society_building_rent_amount'];?>">
																</div>
																<div class="col-sm-3 form-group">
																	<label>समिति भवन का क्षेत्रफल (स्क्वायर मीटर में)</label>
																	<input disabled name="sec_3_building_area" id="sec_3_building_area" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_invoice['society_building_area'];?>">
																</div>
																
															</div>
															<div id="sec_3_other" style="display: <?php echo $sec_3_other_display;?>">
																<div class="col-sm-3 form-group">
																	<label>कृपया विवरण दर्ज करें</label>
																	<input disabled name="sec_3_building_rent" id="sec_3_building_rent" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_invoice['society_building_rent_amount'];?>">
																</div>
															</div>
														</div>
													</div>
													<div class="row" id="sec_3" style="display: <?php echo $sec_3_display;?>;">
														<div class="col-sm-12">
															<h5> 3.1. भूखंड का विवरण </h5>				
															<div class="row">
																<div class="col-sm-3 form-group">
																	<label>भूखण्ड में भुजा कि संख्या</label>
																	<label><small>(उदाहरण के लिये - यदि भूखण्ड आयताकार है तो भुजाओं कि संख्या 4 लिखें)</small></label>
																	<select disabled name="sec_3_a_land_length" id="sec_3_a_land_length" tabindex="<?php echo $tab++; ?>" class="form-control" onChange="show_sides_of_land(this.value);">
																		<option value="">--Select--</option>
																		<option value="3" <?php echo $row_3_1['number_of_sides']=='3'?' selected="selected" ':'';?>>3</option>
																		<option value="4" <?php echo $row_3_1['number_of_sides']=='4'?' selected="selected" ':'';?>>4</option>
																		<option value="5" <?php echo $row_3_1['number_of_sides']=='5'?' selected="selected" ':'';?>>5</option>
																		<option value="6" <?php echo $row_3_1['number_of_sides']=='6'?' selected="selected" ':'';?>>6</option>
																	</select>
																</div>
																<div class="col-sm-3 form-group">
																	<label>क्षेत्रफल (हेक्टेयर में)</label><br/>
																	<label><small>&nbsp;</small></label>
																	<input disabled type="text" name="sec_3_a_area" id="sec_3_a_area" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_3_1['total_area'];?>">
																</div>	
																<div class="col-sm-3 form-group">
																	<label>राजस्व अभिलेख में दर्ज होने की स्थिति( हाँ /नहीं)</label><br/>
																	<label><small>&nbsp;</small></label>
																	<select disabled name="sec_3_a_govt_records" id="sec_3_a_govt_records" tabindex="<?php echo $tab++; ?>" class="form-control" onChange="hide_show(this.value, '#land_records', 'no')">
																		<option value="">--Select--</option>
																		<option value="yes" <?php $land_records_display = 'none'; if($row_3_1['govt_records']=='yes'){echo ' selected="selected" ';}?>>हाँ</option>
																		<option value="no" <?php if($row_3_1['govt_records']!='yes' && $row_3_1['govt_records']!=''){echo ' selected="selected" '; $land_records_display = 'block'; }?>>नहीं</option>
																	</select>
																</div>
																<div class="col-sm-3 form-group" id="land_records" style="display: <?php echo $land_records_display;?>;">
																	<label>यदि नहीं है तो किये जाने वाले प्रयास का विवरण</label>
																	<label><small>&nbsp;</small></label>
																	<input disabled type="text" name="sec_3_a_if_yes" id="sec_3_a_if_yes" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_3_1['govt_records'];?>">
																</div>
															</div>
															<div class="row">
																<div class="col-sm-3 form-group">
																	<label>गाटा/खसरा संख्या</label>
																	<input disabled type="text" name="sec_3_a_gata" id="sec_3_a_gata" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_3_1['gata_no'];?>">
																</div>
																<div class="col-sm-2 form-group">
																	<label>समिति भूखण्ड फोटो संलग्न करें</label>
																	<input disabled type="file" accept=".jpg, .jpeg, .gif, .png, .bmp"name="sec_3_a_image" id="sec_3_a_image" tabindex="<?php echo $tab++; ?>"  class="form-control">
																</div>
																<?php
																if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$row_3_1['new_photo_id'])){
																?>
																<div class="col-sm-2 form-group">
																	<img src="https://upcod.in/<?php echo $row_3_1['new_photo_id']; ?>" class="img-fluid img-thumbnail" style="height:50px;">
																	<label><a href="https://upcod.in/<?php echo $row_3_1['new_photo_id']; ?>" target="_blank">संलग्न फोटो देखें</a></label>

																</div>
																<?php
																}
																?>
																<div class="col-sm-3 form-group">
																	<label>टिप्पणी</label>
																	<input disabled type="text" name="sec_3_a_comment" id="sec_3_a_comment" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_3_1['remarks'];?>">
																</div>
																
															</div>
															<?php
															if(empty($sides_data)){
															?>
															<div id="sides_display" style="display: none;" class="row">
																
															</div>
															<?php
															}
															else{
																$col = ceil(12/sizeof($sides_data));
															?>
															<div id="sides_display" class="row">
															<?php
																$i=1;
																foreach($sides_data as $k=>$v){
																?>
																<div class="col-sm-<?php echo $col;?> form-group">
																	<label>भुजा <?php echo $i;?> की लम्बाई</label>
																	<input disabled type="text" name="sec_3_a_side_<?php echo $i;?>" id="sec_3_a_<?php echo $i;?>" class="form-control" value="<?php echo $v; ?>">
																</div>
																
																<?php
																	$i++;
																}
															?>
															</div>
															<?php
															}
															?>
															<h5> 3.2. भूखंड की चौहद्दी का विवरण </h5>				
															<div class="row">
																<div class="col-sm-3 form-group">
																	<label>भूखण्ड की पूर्व दिशा का विवरण</label>
																	<input disabled type="text" name="sec_3_a_land_chauhaddi_east" id="sec_3_a_land_chauhaddi_east" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_3_1['east_side'];?>">
																</div>	
																<div class="col-sm-3 form-group">
																	<label>भूखण्ड की पश्चिम दिशा का विवरण</label><input disabled type="text" name="sec_3_a_land_chauhaddi_west" id="sec_3_a_land_chauhaddi_west" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_3_1['west_side'];?>">
																</div>
																<div class="col-sm-3 form-group">
																	<label>भूखण्ड की उत्तर दिशा का विवरण</label><input disabled type="text" name="sec_3_a_land_chauhaddi_north" id="sec_3_a_land_chauhaddi_north" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_3_1['north_side'];?>">
																</div>
																<div class="col-sm-3 form-group">
																	<label>भूखण्ड की दक्षिण दिशा का विवरण</label><input disabled type="text" name="sec_3_a_land_chauhaddi_south" id="sec_3_a_land_chauhaddi_south" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_3_1['south_side'];?>">
																</div>
															</div>
															<div class="row">
																<div class="col-sm-3 form-group">
																	<label>सड़क पर भूमि कि लम्बाई (आन रोड जमीन) मीटर में</label>
																	<input disabled type="text" name="sec_3_a_land_on_road" id="sec_3_a_land_on_road" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_3_1['on_road_land'];?>">
																</div>	
																<div class="col-sm-3 form-group">
																	<label>प्रमुख द्वार कि दिशा (फ्र्न्ट साईड)</label>
																	<select disabled name="sec_3_a_land_frontage" id="sec_3_a_land_frontage" tabindex="<?php echo $tab++; ?>" class="form-control">
																		<option value="east" <?php if($row_3_1['front_side']=='east'){echo 'selected="selected"';}?>>पूर्व</option>
																		<option value="west" <?php if($row_3_1['front_side']=='west'){echo 'selected="selected"';}?>>पश्चिम</option>
																		<option value="north" <?php if($row_3_1['front_side']=='north'){echo 'selected="selected"';}?>>उत्तर</option>
																		<option value="south" <?php if($row_3_1['front_side']=='south'){echo 'selected="selected"';}?>>दक्षिण</option>
																	</select>
																</div>
															</div>
															<h5> 3.3. निर्मित भवन का विवरण </h5> 
															<div id="sec_3_b">
															<?php
															for($i=1;$i<=$row_3_3['count'];$i++){
															?>
																<div class="row">
																	<div class="col-sm-2 form-group">
																		<label>लंबाई (मीटर में)</label>
																		<input disabled type="text" name="sec_3_b_length_<?php echo $i; ?>" id="sec_3_b_length_<?php echo $i; ?>" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_3_3['sec_3_b_length_'.$i];?>">
																	</div>	
																	<div class="col-sm-2 form-group">
																		<label>चौड़ाई (मीटर में)</label>
																		<input disabled type="text" name="sec_3_b_width_<?php echo $i; ?>" id="sec_3_b_width_<?php echo $i; ?>" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_3_3['sec_3_b_width_'.$i];?>">
																	</div>
																	<div class="col-sm-2 form-group">
																		<label>भवन का प्रकार</label>
																		<select disabled name="sec_3_b_type_of_construction_<?php echo $i; ?>" id="sec_3_b_type_of_construction_<?php echo $i; ?>" tabindex="<?php echo $tab++; ?>" class="form-control">
																			<option value="">--Select--</option>
																			<?php
																			$sql = 'select * from master_type_of_construction';
																			$result_const = execute_query($sql);
																			while($row_const = mysqli_fetch_assoc($result_const)){
																				echo '<option value="'.$row_const['sno'].'" ';
																				if($row_const['sno']==$row_3_3['sec_3_b_type_of_construction_'.$i]){
																					echo ' selected="selected" ';
																				}
																				echo '>'.$row_const['type_of_construction'].'</option>';
																			}

																			?>
																		</select>
																	</div>
																	<div class="col-sm-2 form-group">
																		<label>किस फण्ड से बना है</label>
																		<select disabled name="sec_3_b_type_of_fund_<?php echo $i; ?>" id="sec_3_b_type_of_fund_<?php echo $i; ?>" tabindex="<?php echo $tab++; ?>" class="form-control">
																			<option value="">--Select--</option>
																			<?php
																			$sql = 'select * from master_type_of_fund';
																			$result_const = execute_query($sql);
																			while($row_const = mysqli_fetch_assoc($result_const)){
																				echo '<option value="'.$row_const['sno'].'" ';
																				if($row_const['sno']==$row_3_3['sec_3_b_type_of_fund_'.$i]){
																					echo ' selected="selected" ';
																				}
																				echo '>'.$row_const['type_of_fund'].'</option>';
																			}

																			?>
																		</select>
																	</div>	
																	<div class="col-sm-2 form-group">
																		<label>टिप्पणी</label>
																		<input disabled type="text" name="sec_3_b_comment_<?php echo $i; ?>" id="sec_3_b_comment_<?php echo $i; ?>" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_3_3['sec_3_b_comment_'.$i];?>">
																	</div>
																</div>
															<?php } ?>
															</div>
															<h5> 3.4. अन्य निर्मित गोदाम का विवरण </h5>
															<div id="sec_3_b_godown">
															<?php
															for($i=1;$i<=$row_3_4['count'];$i++){
															?>
																<div class="row">
																	<div class="col-sm-2 form-group">
																		<label>लंबाई (मीटर में)</label>
																		<input disabled type="text" name="sec_3_b_godown_length_<?php echo $i; ?>" id="sec_3_b_godown_length_<?php echo $i; ?>" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_3_4['sec_3_b_godown_length_'.$i];?>">
																	</div>	
																	<div class="col-sm-2 form-group">
																		<label>चौड़ाई (मीटर में)</label>
																		<input disabled type="text" name="sec_3_b_godown_width_<?php echo $i; ?>" id="sec_3_b_godown_width_<?php echo $i; ?>" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_3_4['sec_3_b_godown_width_'.$i];?>">
																	</div>
																	<div class="col-sm-2 form-group">
																		<label>क्षमता (मेट्रिक टन में)</label>
																		<input disabled type="text" name="sec_3_b_storage_capacity_<?php echo $i; ?>" id="sec_3_b_storage_capacity_<?php echo $i; ?>" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_3_4['sec_3_b_storage_capacity_'.$i];?>">
																	</div>
																	<div class="col-sm-2 form-group">
																		<label>किस फण्ड से बना है</label>
																		<select disabled name="sec_3_b_godown_type_of_fund_<?php echo $i; ?>" id="sec_3_b_godown_type_of_fund_<?php echo $i; ?>" tabindex="<?php echo $tab++; ?>" class="form-control">
																			<option value="">--Select--</option>
																			<?php
																			$sql = 'select * from master_type_of_fund';
																			$result_const = execute_query($sql);
																			while($row_const = mysqli_fetch_assoc($result_const)){
																				echo '<option value="'.$row_const['sno'].'" ';
																				if($row_const['sno']==$row_3_4['sec_3_b_godown_type_of_fund_'.$i]){
																					echo ' selected="selected" ';
																				}
																				echo '>'.$row_const['type_of_fund'].'</option>';
																			}

																			?>
																		</select>
																	</div>	
																	<div class="col-sm-2 form-group">
																		<label>गोदाम के निर्माण कि स्थिति</label>
																		<select disabled name="sec_3_b_godown_status_<?php echo $i; ?>" id="sec_3_b_godown_status_<?php echo $i; ?>" tabindex="<?php echo $tab++; ?>" class="form-control">
																			<option value="">--select-- </option>
																			<option value="good" <?php echo ($row_3_4['sec_3_b_godown_status_'.$i]=='good')?' selected="selected"':'';?>>अच्छा</option>
																			<option value="repairable" <?php echo ($row_3_4['sec_3_b_godown_status_'.$i]=='repairable')?' selected="selected"':'';?>>खराब/मरम्मत योग्य</option>
																			<option value="discarded" <?php echo ($row_3_4['sec_3_b_godown_status_'.$i]=='discarded')?' selected="selected"':'';?>>जर्जर/निषप्रयोज्य</option>
																		</select>
																	</div>
																	<div class="col-sm-1 form-group">
																		<label>टिप्पणी</label>
																		<input disabled type="text" name="sec_3_b_godown_comment_<?php echo $i; ?>" id="sec_3_b_godown_comment_<?php echo $i; ?>" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_3_4['sec_3_b_comment_'.$i];?>">
																	</div>
																</div>
															<?php } ?>
															</div>
															<h5> 3.5. खाली पड़ी भूमि का विवरण </h5>
															<div id="sec_3_c"> 
																<?php
																for($i=1; $i<=$row_3_5['sec_3_c_id']; $i++){
																	
																?>
																<div class="row">
																	<div class="col-sm-2 form-group">
																		<label>क्षेत्रफल (हेक्टेयर में)</label>
																		<input disabled type="text" name="sec_3_c_length_<?php echo $i; ?>" id="sec_3_c_length_<?php echo $i; ?>" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_3_5['sec_3_c_length_'.$i]; ?>">
																	</div>	
																	<div class="col-sm-2 form-group">
																		<label>भूमि की स्थिति (उपजाऊ /बंजर)</label>
																		<select disabled name="sec_3_c_vacant_land_status_<?php echo $i; ?>" id="sec_3_c_vacant_land_status_<?php echo $i; ?>" tabindex="<?php echo $tab++; ?>"  class="form-control">
																		<option value="">--select-- </option>
																		<option value="fertile" <?php if($row_3_5['sec_3_c_vacant_land_status_'.$i]=='fertile'){echo ' selected="selected"';} ?>>उपजाऊ </option>
																		<option value="barren" <?php if($row_3_5['sec_3_c_vacant_land_status_'.$i]=='barren'){echo ' selected="selected"';} ?>>बंजर </option>
																		</select>
																	</div>						

																	<div class="col-sm-2 form-group">
																		<label>स्थान (समिति प्रांगण या अन्य स्थान)*</label>
																		<select disabled name="sec_3_c_land_location_<?php echo $i; ?>" id="sec_3_c_land_location_<?php echo $i; ?>" tabindex="<?php echo $tab++; ?>"  class="form-control" onChange="hide_show(this.value, '#land_connectivity1', 'other'); hide_show(this.value, '#land_access_road', 'na');">
																		<option value="">--select-- </option>
																		<option value="inpremise" <?php $approach_road_display='none'; $land_location_display='none'; if($row_3_5['sec_3_c_land_location_'.$i]=='inpremise'){echo ' selected="selected"';} ?> >समिति प्रांगण </option>
																		<option value="other" <?php if($row_3_5['sec_3_c_land_location_'.$i]=='other'){echo ' selected="selected"'; $land_location_display='block';} ?>>अन्य स्थान </option>
																		</select>
																	</div>
																	<?php if($row_3_5['sec_3_c_land_location_'.$i]=='other'){?>
																	<div class="col-sm-2 form-group" id="land_connectivity<?php echo $i; ?>" style="display: <?php echo $land_location_display;?>">
																		<label>संपर्क मार्ग*</label>
																		<select disabled name="sec_3_c_approach_road_<?php echo $i; ?>" id="sec_3_c_approach_road_<?php echo $i; ?>" tabindex="<?php echo $tab++; ?>"  class="form-control" onChange="hide_show(this.value, '#land_access_road<?php echo $i; ?>', 'proper');">
																		<option value="">--select-- </option>
																		<option value="ordinary" <?php $approach_road_display='none'; if($row_3_5['sec_3_c_approach_road_'.$i]=='ordinary'){echo ' selected="selected"';} ?>>कच्ची सड़क </option>
																		<option value="proper" <?php if($row_3_5['sec_3_c_approach_road_'.$i]!='ordinary'){echo ' selected="selected"'; $approach_road_display='block';} ?>>पक्की सड़क </option>
																		</select>
																	</div>
																	<?php } ?>
																	<div class="col-sm-2 form-group" id="land_access_road<?php echo $i; ?>" style="display: <?php echo $approach_road_display;?>">
																		<label>पक्की सड़क का प्रकार</label>
																		<select disabled name="sec_3_c_paved_road_<?php echo $i; ?>" id="sec_3_c_paved_road_<?php echo $i; ?>" tabindex="<?php echo $tab++; ?>"  class="form-control">
																		<option value="">--select-- </option>
																		<option value="nh" <?php if($row_3_5['sec_3_c_paved_road_'.$i]=='nh'){echo ' selected="selected"';}?>>नेशनल हाईवे</option>
																		<option value="sh" <?php if($row_3_5['sec_3_c_paved_road_'.$i]=='sh'){echo ' selected="selected"';}?>>स्टेट हाईवे</option>
																		<option value="mdr" <?php if($row_3_5['sec_3_c_paved_road_'.$i]=='mdr'){echo ' selected="selected"';}?>>एम.डी.आर.</option>
																		<option value="odr" <?php if($row_3_5['sec_3_c_paved_road_'.$i]=='odr'){echo ' selected="selected"';}?>>ओ.डी.आर.</option>
																		<option value="rural_road" <?php if($row_3_5['sec_3_c_paved_road_'.$i]=='rural_road'){echo ' selected="selected"';}?>>ग्रामीण सड़क</option>
																		<option value="other" <?php if($row_3_5['sec_3_c_paved_road_'.$i]=='other'){echo ' selected="selected"';}?>>अन्य</option>
																		</select>
																	</div>
																</div>	
																<?php } ?>
															</div>
															<h5> 3.6. मण्डी परिषद द्वारा कोई निर्माण कराया गया है? </h5>
															<h6>यदि कोई निर्माण समिति के लिये मण्डी परिषद द्वारा कराया गया हो उल्लेख करें अन्यथा खाली छोड़ दें </h6>
															<div class="row">
																<div class="col-sm-4 form-group">
																	<label>निर्माण का प्रकार</label>
																	<select disabled name="sec_3_6_type_of_construction" id="sec_3_6_type_of_construction" tabindex="<?php echo $tab++; ?>" class="form-control">
																		<option value="">--Select--</option>
																		<?php
																		$sql = 'select * from master_type_of_construction';
																		$result_const = execute_query($sql);
																		while($row_const = mysqli_fetch_assoc($result_const)){
																			echo '<option value="'.$row_const['sno'].'" ';
																			if($row_const['sno']==$row_3_6['sec_3_6_type_of_construction']){
																				echo ' selected="selected" ';
																			}
																			echo '>'.$row_const['type_of_construction'].'</option>';
																		}

																		?>
																	</select>
																</div>
																<div class="col-sm-4 form-group">
																	<label>प्राप्त हो रहा किराया</label>
																	<input disabled type="text" name="sec_3_6_rent" id="sec_3_6_rent" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_3_6['sec_3_6_rent']; ?>">
																</div>
																<div class="col-sm-4 form-group">
																	<label>क्षेत्रफल</label>
																	<input disabled type="text" name="sec_3_6_area" id="sec_3_6_area" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_3_6['sec_3_6_area']; ?>">
																</div>
															</div>
															<h5> 3.7. चारदिवारी (बाऊण्डरी वाल) </h5>	
															<div class="row">			
																<div class="col-sm-2 form-group">
																	<label>चारदिवारी (बाऊण्डरी वाल)</label>
																	<select disabled name="sec_3_d_boundry" id="sec_3_d_boundry" tabindex="" class="form-control">
																		<option value="">--Select--</option>
																		<option value="yes" <?php echo $row_3_1['sec_3_d_boundry']=='yes'?' selected="selected" ':'';?>>है</option>
																		<option value="no" <?php echo $row_3_1['sec_3_d_boundry']=='no'?' selected="selected" ':'';?>>नहीं</option>
																	</select>
																</div>
																<div class="col-sm-2 form-group">
																	<label>प्रमुख द्वार (मेन गेट)</label>
																	<select disabled name="sec_3_d_main_gate" id="sec_3_d_main_gate" tabindex="" class="form-control">
																		<option value="">--Select--</option>
																		<option value="yes" <?php echo $row_3_1['sec_3_d_main_gate']=='yes'?' selected="selected" ':'';?>>है</option>
																		<option value="no" <?php echo $row_3_1['sec_3_d_main_gate']=='no'?' selected="selected" ':'';?>>नहीं</option>
																	</select>
																</div>
															</div>
														</div>
													</div>
												</div>
	<!--------------4th Start---------------------------------------------------------------->
												<div class="step">
													<h4>4.चल सम्पत्ति का विवरण</h2>
													<div class="row">
														<div class="col-sm-12">
															<div class="row">
																<div class="col-sm-2 form-group">
																	<label>माईक्रो ए.टी.एम.</label>
																	<select disabled name="sec_4_micro_atm" id="sec_4_micro_atm" tabindex="<?php echo $tab++; ?>" class="form-control">
																		<option value="">--Select--</option>
																		<option value="yes" <?php echo $row_4['sec_4_micro_atm']=='yes'?' selected="selected"':'';?>>है</option>
																		<option value="no" <?php echo $row_4['sec_4_micro_atm']=='no'?' selected="selected"':'';?>>नहीं</option>
																	</select>
																</div>
																<div class="col-sm-2 form-group">
																	<label>ड्रोन</label>
																	<select disabled name="sec_4_drone" id="sec_4_drone" tabindex="<?php echo $tab++; ?>" class="form-control">
																		<option value="">--Select--</option>
																		<option value="yes" <?php echo $row_4['sec_4_drone']=='yes'?' selected="selected"':'';?>>है</option>
																		<option value="no" <?php echo $row_4['sec_4_drone']=='no'?' selected="selected"':'';?>>नहीं</option>
																	</select>
																</div>
																<div class="col-sm-2 form-group">
																	<label>छलना</label>
																	<select disabled name="sec_4_chhanna" id="sec_4_chhanna" tabindex="<?php echo $tab++; ?>" class="form-control">
																		<option value="">--Select--</option>
																		<option value="yes" <?php echo $row_4['sec_4_chhanna']=='yes'?' selected="selected"':'';?>>है</option>
																		<option value="no" <?php echo $row_4['sec_4_chhanna']=='no'?' selected="selected"':'';?>>नहीं</option>
																		<option value="rent" <?php echo $row_4['sec_4_chhanna']=='rent'?' selected="selected"':'';?>>जरूरत अनुसार किराये पर लाते है</option>
																	</select>
																</div>
																<div class="col-sm-2 form-group">
																	<label>पावर डस्टर</label>
																	<select disabled name="sec_4_power_duster" id="sec_4_power_duster" tabindex="<?php echo $tab++; ?>" class="form-control">
																		<option value="">--Select--</option>
																		<option value="yes" <?php echo $row_4['sec_4_power_duster']=='yes'?' selected="selected"':'';?>>है</option>
																		<option value="no" <?php echo $row_4['sec_4_power_duster']=='no'?' selected="selected"':'';?>>नहीं</option>
																		<option value="rent" <?php echo $row_4['sec_4_power_duster']=='rent'?' selected="selected"':'';?>>जरूरत अनुसार किराये पर लाते है</option>
																	</select>
																</div>
																<div class="col-sm-2 form-group">
																	<label>ट्रैक्टर</label>
																	<select disabled name="sec_4_tractor" id="sec_4_tractor" tabindex="<?php echo $tab++; ?>" class="form-control">
																		<option value="">--Select--</option>
																		<option value="yes" <?php echo $row_4['sec_4_tractor']=='yes'?' selected="selected"':'';?>>है</option>
																		<option value="no" <?php echo $row_4['sec_4_tractor']=='no'?' selected="selected"':'';?>>नहीं</option>
																		<option value="rent" <?php echo $row_4['sec_4_tractor']=='rent'?' selected="selected"':'';?>>जरूरत अनुसार किराये पर लाते है</option>
																	</select>
																</div>
															</div>	
															<div class="row">
																<div class="col-sm-2 form-group">
																	<label>कस्टम हाईरिंग सेंटर</label>
																	<select disabled name="sec_4_custom_hiring" id="sec_4_custom_hiring" tabindex="<?php echo $tab++; ?>" class="form-control" onChange="hide_show(this.value, 'custom_hiring', 'yes')">
																		<option value="">--Select--</option>
																		<option value="yes" <?php $custom_hiring=''; echo $row_4['sec_4_custom_hiring']=='yes'?' selected="selected"':'';?>>है</option>
																		<option value="no" <?php echo $row_4['sec_4_custom_hiring']=='no'?' selected="selected"':'';?>>नहीं</option>
																	</select>
																</div>
																<div class="col-sm-3 form-group" id="custom_hiring" style="display: <?php echo $custom_hiring;?>">
																	<label>कस्टम हाईरिंग सेंटर में उपलब्ध उपकरण</label>
																		<?php 
																		if(!empty($custom_hiring_array)){
																			echo '<p class="small border rounded p-1">';
																			foreach($custom_hiring_array as $k=>$v){
																				echo $v.' | ';
																			}
																			echo '</p>';
																		}
																		?>
																</div>
																<div class="col-sm-2 form-group">
																	<label>कुर्सी (संख्या)</label>
																	<input disabled type="text" name="sec_4_chair" id="sec_4_chair" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_4['sec_4_chair']; ?>">
																</div>
																<div class="col-sm-2 form-group">
																	<label>मेज(संख्या)</label>
																	<input disabled type="text" name="sec_4_table" id="sec_4_table" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_4['sec_4_table']; ?>">
																</div>
																<div class="col-sm-2 form-group">
																	<label>अलमारी (संख्या)</label>
																	<input disabled type="text" name="sec_4_almari" id="sec_4_almari" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_4['sec_4_almari']; ?>">
																</div>
															</div>
															<div class="row">
																<div class="col-sm-6 form-group">
																	<label>टिप्पणी (यदि कोई अन्य विवरण दर्ज करना चाहते हो तो उल्लेख करें)</label>
																	<input disabled type="text" name="sec_4_remarks" id="sec_4_remarks" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_4['sec_4_remarks']; ?>">
																</div>
															</div>
														</div>
													</div>
												</div>
<!-------------5th start-------------------------------------------------------->												
												<div class="step">
													<h4>5. निर्मित भवन की स्थिति </h2>
													<div class="col-sm-12">
														<div class="row">
															<div class="col-sm-4 form-group">
																<label>निर्मित भवन की स्थिति</label>
																<select disabled name="sec_5_built_building" id="sec_5_built_building" tabindex="<?php echo $tab++; ?>"  class="form-control" onChange="hide_show(this.value, '#jarjar_remarks', 'discarded'); hide_show(this.value, '#repairable', 'repairable')">
																<option value="">--select-- </option>
																<option value="good" <?php $repairable_display='none'; $jarjar_display='none'; if($row_5['sec_5_built_building']=='good'){echo ' selected="selected" ';}?>>अच्छा</option>
																<option value="repairable" <?php if($row_5['sec_5_built_building']=='repairable'){echo ' selected="selected" '; $repairable_display='block';}?>>खराब/मरम्मत योग्य</option>
																<option value="discarded" <?php if($row_5['sec_5_built_building']=='discarded'){echo ' selected="selected" '; $jarjar_display='block';}?>>जर्जर/निषप्रयोज्य</option>
																<option value="not_available" <?php if($row_5['sec_5_built_building']=='not_available'){echo ' selected="selected" '; $jarjar_display='block';}?>>भवन उपलब्ध नही है</option>
																</select>
															</div>
															<div class="col-sm-4 form-group" id="jarjar_remarks" style="display: <?php echo $jarjar_display;?>">
																<label>कृप्या विस्तृत जानकारी दर्ज करें</label>
																<input disabled type="text" name="sec_5_detailed_information" id="sec_5_detailed_information" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_5['sec_5_detailed_information']; ?>">
															</div>						
														</div>
													</div>
													
													<div id="repairable" style="display: <?php echo $repairable_display; ?>">
														<h4>6. यदि मरम्मत योग्य है तो आवश्यक्तावार विवरण दर्ज करें</h4>
														<h6>जिन चीजों कि मरम्मत की आवश्यक्ता हो उसे दर्ज करें अन्य को खाली छोड़ दें</h6>
														<div class="col-sm-12">
															<div class="row">
																<div class="col-sm-12">
																	<h5>6.1. फर्श</h5>
																	<div class="row">
																		<div class="col-sm-4 form-group">
																			<label>लंबाई (मीटर में)</label>
																			<input disabled type="text" name="sec_6_a_length" id="sec_6_a_length" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_5['sec_6_a_length']; ?>">
																		</div>	
																		<div class="col-sm-4 form-group">
																			<label>चौडाई (मीटर में)</label>
																			<input disabled type="text" name="sec_6_a_width" id="sec_6_a_width" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_5['sec_6_a_width']; ?>">
																		</div>
																			<div class="col-sm-2 form-group">
																			<label>फोटो संलग्न करें</label>
																			<input disabled type="file" accept=".jpg, .jpeg, .gif, .png, .bmp"name="sec_6_a_img" id="sec_6_a_img" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>					
																		<?php
																		if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$row_5['sec_6_a_img'])) {
																		?>
																		<div class="col-sm-2 form-group">
																			<img src="https://upcod.in/<?php echo $row_5['sec_6_a_img']; ?>" class="img-fluid img-thumbnail" style="height:50px;">
																			<label><a href="https://upcod.in/<?php echo $row_5['sec_6_a_img']; ?>" target="_blank">संलग्न फोटो देखें</a></label>

																		</div>
																		<?php
																		}
																		?>	
																	</div>
																	<h5>6.2. दीवार </h5>
																	<div class="row">
																		<div class="col-sm-4 form-group">
																			<label>लंबाई (मीटर में)</label>
																			<input disabled type="text" name="sec_6_b_length" id="sec_6_b_length" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_5['sec_6_b_length']; ?>">
																		</div>	
																		<div class="col-sm-4 form-group">
																			<label>चौडाई (मीटर में)</label>
																			<input disabled type="text" name="sec_6_b_width" id="sec_6_b_width" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_5['sec_6_b_width']; ?>">
																		</div>
																		<div class="col-sm-2 form-group">
																			<label>फोटो संलग्न करें</label>
																			<input disabled type="file" accept=".jpg, .jpeg, .gif, .png, .bmp"name="sec_6_b_img" id="sec_6_b_img" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>
																		<?php
																		if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$row_5['sec_6_b_img'])) {
																		?>
																		<div class="col-sm-2 form-group">
																			<img src="https://upcod.in/<?php echo $row_5['sec_6_b_img']; ?>" class="img-fluid img-thumbnail" style="height:50px;">
																			<label><a href="https://upcod.in/<?php echo $row_5['sec_6_b_img']; ?>" target="_blank">संलग्न फोटो देखें</a></label>

																		</div>
																		<?php
																		}
																		?>

																	</div>
																	<h5>6.3. पुताई</h5>
																	<div class="row">
																		<div class="col-sm-4 form-group">
																			<label>लंबाई (मीटर में)</label>
																			<input disabled type="text" name="sec_6_c_length" id="sec_6_c_length" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_5['sec_6_c_length']; ?>">
																		</div>	
																		<div class="col-sm-4 form-group">
																			<label>चौडाई (मीटर में)</label>
																			<input disabled type="text" name="sec_6_c_width" id="sec_6_c_width" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_5['sec_6_c_width']; ?>">
																		</div>
																		<div class="col-sm-2 form-group">
																			<label>फोटो संलग्न करें</label>
																			<input disabled type="file" accept=".jpg, .jpeg, .gif, .png, .bmp"name="sec_6_c_img" id="sec_6_c_img" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>
																		<?php
																		if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$row_5['sec_6_c_img'])) {
																		?>
																		<div class="col-sm-2 form-group">
																			<img src="https://upcod.in/<?php echo $row_5['sec_6_c_img']; ?>" class="img-fluid img-thumbnail" style="height:50px;">
																			<label><a href="https://upcod.in/<?php echo $row_5['sec_6_c_img']; ?>" target="_blank">संलग्न फोटो देखें</a></label>

																		</div>
																		<?php
																		}
																		?>
																	</div>
																	<h5>6.4. छत </h5>
																	<div class="row">
																		<div class="col-sm-4 form-group">
																			<label>लंबाई (मीटर में)</label>
																			<input disabled type="text" name="sec_6_d_length" id="sec_6_d_length" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_5['sec_6_d_length']; ?>">
																		</div>	
																		<div class="col-sm-4 form-group">
																			<label>चौडाई (मीटर में)</label>
																			<input disabled type="text" name="sec_6_d_width" id="sec_6_d_width" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_5['sec_6_d_width']; ?>">
																		</div>
																		<div class="col-sm-2 form-group">
																			<label>फोटो संलग्न करें</label>
																			<input disabled type="file" accept=".jpg, .jpeg, .gif, .png, .bmp"name="sec_6_d_img" id="sec_6_d_img" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>		
																		<?php
																		if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$row_5['sec_6_d_img'])) {
																		?>
																		<div class="col-sm-2 form-group">
																			<img src="https://upcod.in/<?php echo $row_5['sec_6_d_img']; ?>" class="img-fluid img-thumbnail" style="height:50px;">
																			<label><a href="https://upcod.in/<?php echo $row_5['sec_6_d_img']; ?>" target="_blank">संलग्न फोटो देखें</a></label>

																		</div>
																		<?php
																		}
																		?>
																	</div>
																	<h5>6.5. शौचालय</h5>
																	<div class="row">
																		<div class="col-sm-2 form-group">
																			<label>फर्श</label>
																			<select disabled name="sec_6_e_floor" id="sec_6_e_floor" tabindex="<?php echo $tab++; ?>" class="form-control" onChange="hide_show(this.value, '#bathroom_floor', 'repairable')">
																				<option value="">--Select--</option>
																				<option value="good" <?php $bathroom_floor_display='none'; if($row_5['sec_6_e_floor']=='good'){echo ' selected="selected"';}?>>सही है</option>
																				<option value="repairable" <?php if($row_5['sec_6_e_floor']!='good' && $row_5['sec_6_e_floor']!=''){echo ' selected="selected"'; $bathroom_floor_display='block';}?>>मरम्म्त योग्य</option>
																			</select>
																		</div>	
																		<div class="col-sm-2 form-group" style="display: <?php echo $bathroom_floor_display; ?>" id="bathroom_floor">
																			<label>अनुमानित लागत</label>
																			<input disabled type="text" name="sec_6_e_floor_cost" id="sec_6_e_floor_cost" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_5['sec_6_e_floor']; ?>">
																		</div>
																		<div class="col-sm-2 form-group">
																			<label>प्लासटर</label>
																			<select disabled name="sec_6_e_plaster" id="sec_6_e_plaster" tabindex="<?php echo $tab++; ?>" class="form-control" onChange="hide_show(this.value, '#bathroom_plaster', 'repairable')">
																				<option value="">--Select--</option>
																				<option value="good" <?php $bathroom_plaster_display='none'; if($row_5['sec_6_e_plaster']=='good'){echo ' selected="selected"';}?>>सही है</option>
																				<option value="repairable" <?php if($row_5['sec_6_e_plaster']!='good' && $row_5['sec_6_e_plaster']!=''){echo ' selected="selected"'; $bathroom_plaster_display='block';}?>>मरम्म्त योग्य</option>
																			</select>
																		</div>	
																		<div class="col-sm-2 form-group" style="display:  <?php echo $bathroom_plaster_display; ?>" id="bathroom_plaster">
																			<label>अनुमानित लागत</label>
																			<input disabled type="text" name="sec_6_e_plaster_cost" id="ec_6_e_plaster_cost" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_5['sec_6_e_plaster']; ?>">
																		</div>
																		<div class="col-sm-2 form-group">
																			<label>छत</label>
																			<select disabled name="sec_6_e_ceiling" id="sec_6_e_ceiling" tabindex="<?php echo $tab++; ?>" class="form-control" onChange="hide_show(this.value, '#bathroom_roof', 'repairable')">
																				<option value="">--Select--</option>
																				<option value="good" <?php $bathroom_ceiling_display='none'; if($row_5['sec_6_e_ceiling']=='good'){echo ' selected="selected"';}?>>सही है</option>
																				<option value="repairable" <?php if($row_5['sec_6_e_ceiling']!='good' && $row_5['sec_6_e_ceiling']!=''){echo ' selected="selected"'; $bathroom_ceiling_display='block';}?>>मरम्म्त योग्य</option>
																			</select>
																		</div>	
																		<div class="col-sm-2 form-group" style="display: <?php echo $bathroom_ceiling_display; ?>" id="bathroom_roof">
																			<label>अनुमानित लागत</label>
																			<input disabled type="text" name="sec_6_e_ceiling_cost" id="sec_6_e_ceiling_cost" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_5['sec_6_e_ceiling']; ?>">
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-sm-2 form-group">
																			<label>सीट</label>
																			<select disabled name="sec_6_e_seat" id="sec_6_e_seat" tabindex="<?php echo $tab++; ?>" class="form-control" onChange="hide_show(this.value, '#bathroom_seat', 'repairable')">
																				<option value="">--Select--</option>
																				<option value="good" <?php $bathroom_seat_display='none'; if($row_5['sec_6_e_seat']=='good'){echo ' selected="selected"';}?>>सही है</option>
																				<option value="repairable" <?php if($row_5['sec_6_e_seat']!='good' && $row_5['sec_6_e_seat']!=''){echo ' selected="selected"'; $bathroom_seat_display='block';}?>>मरम्म्त योग्य</option>
																			</select>
																		</div>
																		<div class="col-sm-2 form-group" style="display: <?php echo $bathroom_seat_display; ?>;" id="bathroom_seat">
																			<label>अनुमानित लागत</label>
																			<input disabled type="text" name="sec_6_e_seat_cost" id="sec_6_e_seat_cost" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_5['sec_6_e_seat']; ?>">
																		</div>	
																		<div class="col-sm-2 form-group">
																			<label>प्लम्बिंग</label>
																			<select disabled name="sec_6_e_plumbing" id="sec_6_e_plumbing" tabindex="<?php echo $tab++; ?>" class="form-control" onChange="hide_show(this.value, '#bathroom_plumbing', 'repairable')">
																				<option value="">--Select--</option>
																				<option value="good" <?php $bathroom_plumbing_display='none'; if($row_5['sec_6_e_plumbing']=='good'){echo ' selected="selected"';}?>>सही है</option>
																				<option value="repairable" <?php if($row_5['sec_6_e_plumbing']!='good' && $row_5['sec_6_e_plumbing']!=''){echo ' selected="selected"'; $bathroom_plumbing_display='block';}?>>मरम्म्त योग्य</option>
																			</select>
																		</div>	
																		<div class="col-sm-2 form-group" style="display: <?php echo $bathroom_plumbing_display; ?>" id="bathroom_plumbing">
																			<label>अनुमानित लागत</label>
																			<input disabled type="text" name="sec_6_e_plumbing_cost" id="sec_6_e_plumbing_cost" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_5['sec_6_e_plumbing']; ?>">
																		</div>	
																	</div>

																	<div class="row">
																		<div class="col-sm-4 form-group">
																			<h5>6.6. दरवाजा (संख्या)</h5>
																			<input disabled type="text" name="sec_6_f_number_of_door" id="sec_6_f_number_of_door" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_5['sec_6_f_number_of_door']; ?>">
																		</div>
																		<div class="col-sm-4 form-group">
																			<h5>6.7. खिडकी (संख्या)</h5>

																			<input disabled type="text" name="sec_6_g_number_of_window" id="sec_6_g_number_of_window" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_5['sec_6_g_number_of_window']; ?>">
																		</div>
																	</div>
																	<h5>6.8. प्लास्टर </h5>
																	<div class="row">
																		<div class="col-sm-4 form-group">
																			<label>दीवार (आवश्यकता अनुसार क्षेत्रफल स्क्वायर मीटर में लिखें)</label>
																			<input disabled type="text" name="sec_6_h_length" id="sec_6_h_length" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_5['sec_6_h_length']; ?>">
																		</div>	
																		<div class="col-sm-4 form-group">
																			<label>छत  (आवश्यकता अनुसार क्षेत्रफल स्क्वायर मीटर में लिखें)</label>
																			<input disabled type="text" name="sec_6_h_width" id="sec_6_h_width" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_5['sec_6_h_width']; ?>">
																		</div>
																	</div>	
																	<h5>6.9. अन्य </h5>
																	<div class="row">
																		<div class="col-sm-6 form-group">
																			<label>यदि उपरोक्त के अतिरिक्त किसी प्रकार कि मरम्म्त कि आवश्यक्ता हो तो उल्लेख करें</label>
																			<input disabled type="text" name="sec_6_i_other" id="sec_6_i_other" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_5['sec_6_i_other']; ?>">
																		</div>	
																	</div>									
																</div>
															</div>
														</div>
													</div>
												</div>
	<!---------------6th Start---------------------------------------------------------------->
												<div class="step">
													<h4>7.पहुंच मार्ग का विवरण</h2>
													<div class="col-sm-12">
														<div class="row">
															<div class="col-sm-4 form-group">
																<label>पहुंच मार्ग -</label>
																<select disabled name="sec_6_access_road" id="sec_6_access_road" tabindex="<?php echo $tab++; ?>"  class="form-control" onChange="hide_show(this.value, '#access_road', 'proper'); hide_show(this.value, '#access_road_truck', 'ordinary');">
																<option value="">--select-- </option>
																<option value="ordinary" <?php $access_road_display='none'; $access_road_truck='none'; if($row_2_1['sec_6_access_road']=='ordinary'){echo 'selected="selected"'; $access_road_truck='block';}?>>कच्ची सडक </option>
																<option value="proper" <?php if($row_2_1['sec_6_access_road']!='ordinary'){echo 'selected="selected"'; $access_road_display='block';}?>>पक्की सडक</option>
																</select>
															</div>				
															<div class="col-sm-4 form-group" id="access_road" style="display: <?php echo $access_road_display; ?>">
																<label>पक्की सड़क का प्रकार</label>
																<select disabled name="sec_6_paved_road" id="sec_6_paved_road_road" tabindex="<?php echo $tab++; ?>"  class="form-control">
																<option value="">--select-- </option>
																<option value="nh" <?php if($row_2_1['sec_6_access_road']=='nh'){echo 'selected="selected"';} ?>>नेशनल हाईवे</option>
																<option value="sh" <?php if($row_2_1['sec_6_access_road']=='sh'){echo 'selected="selected"';} ?>>स्टेट हाईवे</option>
																<option value="mdr" <?php if($row_2_1['sec_6_access_road']=='mdr'){echo 'selected="selected"';} ?>>एम.डी.आर.</option>
																<option value="odr" <?php if($row_2_1['sec_6_access_road']=='odr'){echo 'selected="selected"';} ?>>ओ.डी.आर.</option>
																<option value="rural_road" <?php if($row_2_1['sec_6_access_road']=='rural_road'){echo 'selected="selected"';} ?>>ग्रामीण सड़क</option>
																<option value="other" <?php if($row_2_1['sec_6_access_road']=='other'){echo 'selected="selected"';} ?>>अन्य</option>
																</select>
															</div>	
															<div class="col-sm-4 form-group" id="access_road_truck" style="display: <?php echo $access_road_truck; ?>">
																<label>यदि समिति भवन तक ट्रक नही पहुंचता है तो पक्के मार्ग से समिति भवन की दूरी (की. मी. में)</label>
																<input disabled type="text" name="sec_6_2_truck_not_reach" id="sec_6_2_truck_not_reach" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_2_1['sec_6_2_truck_not_reach']; ?>">
															</div>
														</div>	
													</div>
													<h4>8.विद्युत कनेक्शन</h2>
													<div class="col-sm-12">
														<div class="row">
															<div class="col-sm-4 form-group">
																<label>विद्युत कनेक्शन</label>
																<select disabled name="sec_7_electrical_connection" id="sec_7_electrical_connection" tabindex="<?php echo $tab++; ?>"  class="form-control" onChange="hide_show(this.value, '#electricity_not_available', 'no'); hide_show(this.value, '#electricity_available', 'yes'); hide_show(this.value, '#electricity_available_not_working', 'na');">
																<option value="">--select-- </option>
																<option value="yes" <?php $electricity_available_display='none'; $electricity_not_available_display='none'; if($row_2_1['sec_7_electrical_connection']=='yes'){echo 'selected="selected"'; $electricity_available_display='block';}?>>हाँ </option>
																<option value="no" <?php if($row_2_1['sec_7_electrical_connection']=='no'){echo 'selected="selected"'; $electricity_not_available_display='block';}?>>नहीं</option>
																<option value="solar" <?php if($row_2_1['sec_7_electrical_connection']=='solar'){echo 'selected="selected"'; }?>>पूर्णतया सोलर</option>
																</select>
															</div>
															<div class="col-sm-4 form-group" id="electricity_available" style="display: <?php echo $electricity_available_display;?>">
																<label>यदि है तो कार्यरत है या नहीं</label>
																<select disabled name="sec_7_electrical_connection_working" id="sec_7_electrical_connection_working" tabindex="<?php echo $tab++; ?>"  class="form-control" onChange="hide_show(this.value, '#electricity_available_not_working', 'no');">
																<option value="">--select-- </option>
																<option value="yes" <?php $electricity_available_not_working='none'; if($row_2_1['sec_7_electrical_connection_working']=='yes'){echo 'selected="selected"';}?>>हाँ </option>
																<option value="no" <?php if($row_2_1['sec_7_electrical_connection_working']!='yes'){echo 'selected="selected"'; $electricity_available_not_working='block';}?>>नहीं</option>
																</select>
															</div>
															<div class="col-sm-4 form-group" id="electricity_available_not_working" style="display: <?php echo $electricity_available_not_working; ?>">
																<label>यदि कार्यरत नहीं तो कारण</label>
																<input disabled type="text" name="sec_7_electrical_connection_notworking" id="sec_7_electrical_connection_notworking" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_2_1['sec_7_electrical_connection_working']; ?>">
															</div>						
															<div class="col-sm-4 form-group" id="electricity_not_available" style="display: <?php echo $electricity_not_available_display;?>">
																<label>यदि नहीं तो प्रस्ताव</label>
																<input disabled type="text" name="sec_7_if_yes" id="sec_7_if_yes" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php echo $row_2_1['sec_7_if_yes']; ?>">
															</div>
														</div>
													</div>
													<h4>9.इण्टरनेट कनेक्शन</h2>
													<div class="col-sm-12">
														<div class="row">
															<div class="col-sm-4 form-group">
																<label>इण्टरनेट कनेक्शन</label>
																<select disabled name="sec_8_internet_connection" id="sec_8_internet_connection" tabindex="<?php echo $tab++; ?>"  class="form-control" onChange="hide_show(this.value, '#net_con_available', 'yes'); hide_show(this.value, '#net_con_notavailable', 'no');">
																<option value="">--select-- </option>
																<option value="yes"<?php $net_con_available_display='none'; $net_con_notavailable_display='none'; if($row_2_1['sec_8_internet_connection']=='yes'){echo 'selected="selected"'; $net_con_available_display='block';}?>>हाँ </option>
																<option value="no" <?php if($row_2_1['sec_8_internet_connection']=='no'){echo 'selected="selected"'; $net_con_notavailable_display='block';}?>>नहीं</option>
																</select>
															</div>					
															<div class="col-sm-4 form-group" id="net_con_available" style="display: <?php echo $net_con_available_display;?>">
																<label>यदि है तो सर्विस प्रोवाइडर का नाम</label>
																<select disabled name="sec_8_if_yes" id="sec_8_if_yes" tabindex="<?php echo $tab++; ?>"  class="form-control">
																	<option value="">--Select--</option>
																	<option value="bsnl" <?php if($row_2_1['sec_8_if_yes']=='bsnl'){echo ' selected="selected"';}?>>BSNL</option>
																	<option value="jio" <?php if($row_2_1['sec_8_if_yes']=='jio'){echo ' selected="selected"';}?>>JIO</option>
																	<option value="vodafone" <?php if($row_2_1['sec_8_if_yes']=='vodafone'){echo ' selected="selected"';}?>>Vodafone</option>
																	<option value="airtel" <?php if($row_2_1['sec_8_if_yes']=='airtel'){echo ' selected="selected"';}?>>Airtel</option>
																	
																</select>
															</div>
															<div class="col-sm-4 form-group" id="net_con_notavailable" style="display: <?php echo $net_con_notavailable_display; ?>">
																<label>क्षेत्र में उपलब्ध ईण्टरनेट सर्विस प्रोवाइडर के नाम (सभी उपलब्ध आपरेटर का चयन करें)</label>
																<select disabled name="sec_6_select_operator[]" id="sec_6_select_operator" tabindex="<?php echo $tab++; ?>" multiple="multiple" class="form-control">
																<?php
																$internet_provider = explode(", ", $row_2_1['sec_8_if_yes']);
																?>
																	<option value="bsnl" <?php if(in_array('bsnl', $internet_provider)){echo ' selected="selected"';}?>>BSNL</option>
																	<option value="jio" <?php if(in_array('jio', $internet_provider)){echo ' selected="selected"';}?>>JIO</option>
																	<option value="vodafone" <?php if(in_array('vodafone', $internet_provider)){echo ' selected="selected"';}?>>Vodafone</option>
																	<option value="airtel" <?php if(in_array('airtel', $internet_provider)){echo ' selected="selected"';}?>>Airtel</option>
																	
																</select>
															</div>
														
														</div>
													</div>
													<h4>10.पेयजल की उपलब्धता</h2>
													<div class="col-sm-12">
														<div class="row">
															<div class="col-sm-3 form-group">
																<label>सरकारी नलके का पानी</label>
																<select disabled name="sec_6_narrow_tubes" id="sec_6_narrow_tubes" tabindex="<?php echo $tab++; ?>"  class="form-control">
																<option value="">--select-- </option>
																<option value="yes" <?php echo $row_2_1['sec_6_narrow_tubes']=='yes'?'selected="selected"':'';?>>हाँ </option>
																<option value="no" <?php echo $row_2_1['sec_6_narrow_tubes']=='no'?'selected="selected"':'';?>>नहीं</option>
																</select>
															</div>					
															<div class="col-sm-3 form-group">
																<label>पानी कि टंकी</label>
																<select disabled name="sec_6_water_tank" id="sec_6_water_tank" tabindex="<?php echo $tab++; ?>"  class="form-control">
																	<option value="">--select-- </option>
																	<option value="yes" <?php echo $row_2_1['sec_6_water_tank']=='yes'?'selected="selected"':'';?>>हाँ </option>
																	<option value="no" <?php echo $row_2_1['sec_6_water_tank']=='no'?'selected="selected"':'';?>>नहीं</option>																	
																</select>
															</div>
															<div class="col-sm-3 form-group">
																<label> सबमर्सिबल </label>
																<select disabled name="sec_6_samarsabel" id="ec_6_samarsabel" tabindex="<?php echo $tab++; ?>" class="form-control">
																	<option value="">--select-- </option>
																	<option value="yes" <?php echo $row_2_1['sec_6_samarsabel']=='yes'?'selected="selected"':'';?>>हाँ </option>
																	<option value="no" <?php echo $row_2_1['sec_6_samarsabel']=='no'?'selected="selected"':'';?>>नहीं</option>																	
																</select>
															</div>
															<div class="col-sm-3 form-group">
																<label> हैंड पंप </label>
																<select disabled name="sec_6_handpump" id="ec_6_handpump" tabindex="<?php echo $tab++; ?>" class="form-control">
																	<option value="">--select-- </option>
																	<option value="yes" <?php echo $row_2_1['sec_6_handpump']=='yes'?'selected="selected"':'';?>>हाँ </option>
																	<option value="no" <?php echo $row_2_1['sec_6_handpump']=='no'?'selected="selected"':'';?>>नहीं</option>																	
																</select>
															</div>
														
														</div>
													</div>
												</div>
	<!----------9th start-------------------------------------------------------->
												<div class="step">
													<h4>11.अतिरिक्त निर्माण की आवश्यक्ता </h2>
													<div class="col-sm-12">
															<div class="row">           
																<div class="col-sm-12">
																	<h5>11.1. गोदाम </h5>
																	<div class="row">
																		<div class="col-sm-3 form-group">
																			<label>लंबाई (मीटर में)</label>
																			<input disabled type="text" name="sec_9_a_length" id="sec_9_a_length" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_9['sec_9_a_length']; ?>">
																		</div>	
																		<div class="col-sm-3 form-group">
																			<label>चौडाई (मीटर में)</label>
																			<input disabled type="text" name="sec_9_a_width" id="sec_9_a_width" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_9['sec_9_a_width']; ?>">
																		</div>		
																		<div class="col-sm-3 form-group">
																			<label>क्षमता (मेट्रिक टन में)</label>
																			<input disabled type="text" name="sec_9_a_capacity_in_mt" id="sec_9_a_capacity_in_mt" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_9['sec_9_a_capacity_in_mt']; ?>">
																		</div>
																		
																	</div>
																	<h5>11.2. बाथरूम </h5>
																	<div class="row">
																		<div class="col-sm-3 form-group">
																			<label>लंबाई (मीटर में)</label>
																			<input disabled type="text" name="sec_9_b_length" id="sec_9_b_length" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_9['sec_9_b_length']; ?>">
																		</div>	
																		<div class="col-sm-3 form-group">
																			<label>चौडाई (मीटर में)</label>
																			<input disabled type="text" name="sec_9_b_width" id="sec_9_b_width" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_9['sec_9_b_width']; ?>">
																		</div>		
																	</div>	
																	<h5>11.3. शोरूम </h5>
																	<div class="row">
																		<div class="col-sm-3 form-group">
																			<label>लंबाई (मीटर में)</label>
																			<input disabled type="text" name="sec_9_c_length" id="sec_9_c_length" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_9['sec_9_c_length']; ?>">
																		</div>	
																		<div class="col-sm-3 form-group">
																			<label>चौडाई (मीटर में)</label>
																			<input disabled type="text" name="sec_9_c_width" id="sec_9_c_width" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_9['sec_9_c_width']; ?>">
																		</div>		
																	</div>
																	<h5>11.4. बाउंड्री वाल </h5>
																	<div class="row">
																		<div class="col-sm-3 form-group">
																			<label>लंबाई (मीटर में)</label>
																			<input disabled type="text" name="sec_9_d_boundary_wall_length" id="sec_9_d_boundary_wall_length" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_9['sec_9_d_boundary_wall_length']; ?>">
																		</div>	
																		<div class="col-sm-3 form-group">
																			<label>चौडाई (मीटर में)</label>
																			<input disabled type="text" name="sec_9_d_boundary_wall_width" id="sec_9_d_boundary_wall_width" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_9['sec_9_d_boundary_wall_width']; ?>">
																		</div>		
																	</div>
																	<h5>11.5. मल्टीपरपस हाल</h5>
																	<div class="row">
																		<div class="col-sm-3 form-group">
																			<label>लंबाई (मीटर में)</label>
																			<input disabled type="text" name="sec_9_e_multipurpose_hall_length" id="sec_9_e_multipurpose_hall_length" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_9['sec_9_e_multipurpose_hall_length']; ?>">
																		</div>	
																		<div class="col-sm-3 form-group">
																			<label>चौडाई (मीटर में)</label>
																			<input disabled type="text" name="sec_9_e_multipurpose_hall_width" id="sec_9_e_multipurpose_hall_width" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $row_9['sec_9_e_multipurpose_hall_width']; ?>">
																		</div>		
																	</div>						
																					
																</div>
															</div>
														</div>
													</div>	
											</div>
											
											<input disabled type="hidden" id="term" name="term" value="a">
											 <input disabled type="hidden" id="latitude" name="latitude" value="">
											 <input disabled type="hidden" id="longitude" name="longitude" value="">
											 <input disabled type="hidden" id="id" name="id" value="submit_form">
											 <input disabled type="hidden" id="current_step_count" name="current_step_count" value="">
											 <input disabled type="hidden" id="survey_id" name="survey_id" value="<?php echo $row_invoice['sno']; ?>">
									  </form>
									</div>                                	


								</div>
							</div>
						</div>
					</div>
				</div>
				
<div id="preloader-wrapper">
   <div id="preloader"></div>
   <div class="preloader-section section-left"></div>
   <div class="preloader-section section-right"></div>
</div>

<script>	

$(document).ready(function() {
	//getLocation();
});
</script>																						


<script type="text/javascript" src="js/multistepform.js">				
<?php
	
	break;
}
}
?>	
  								
				
<!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
<script src="js/light-bootstrap-dashboard.js?v=1.4.0"></script>

<?php		
page_footer_end();
?>

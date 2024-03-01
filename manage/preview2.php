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
												</div>
	<!----------------2.1 start-------------------------------------------------------->
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

<?php
include("scripts/settings.php");
$msg='';
$tab=1;
if(isset($_POST['aadhaar'])) {
	if($_POST['tehseel']==''){
		$msg .= '<h6 class="alert alert-danger">Select Tehseel</h6>';
	}
	if($_POST['mobile']==''){
		$msg .= '<h6 class="alert alert-danger">Enter Mobile Number</h6>';
	}
	if($_POST['full_name']==''){
		$msg .= '<h6 class="alert alert-danger">Enter Full Name</h6>';
	}
	if($msg==''){
		//print_r($_POST);
		$sql = 'select * from enquiry_customer where mobile="'.$_POST['mobile'].'"';
		$result = execute_query($sql);
		if(mysqli_num_rows($result)==0){
			$sql = 'insert into enquiry_customer (cus_name, fname, address, mobile, adhar_no, add_2, mob_4, city, created_by, creation_time) values ("'.$_POST['full_name'].'", "'.$_POST['father_name'].'", "'.$_POST['address'].'", "'.$_POST['mobile'].'", "'.$_POST['aadhaar'].'", "'.$_POST['tehseel'].'", "'.$_POST['email'].'", "'.$_POST['villages'].'",  "'.$_SESSION['username'].'", "'.date("Y-m-d H:i:s").'")';
			execute_query($sql);
			if(mysqli_error($db)){
				$msg .= '<h6 class="alert alert-danger">Error 1 # : '.mysqli_error($db).' >> '.$sql.'</h6>';
				$msg .= '<h6 class="alert alert-danger">Error 1.</h6>';
			}
			else{
				$insert_id = mysqli_insert_id($db);
			}
		}
		else{
			$insert_id = mysqli_fetch_assoc($result);
			$insert_id = $insert_id['sno'];
		}
		if($msg==''){
			
				
			
			foreach($_POST['plv_tasks'] as $k=>$v){
				$selected_tasks[] = '("'.$insert_id.'", "'.$v.'", "'.$_SESSION['username'].'", "'.date("Y-m-d H:i:s").'")';
			}
			$sql = 'insert into enquiry_customer_tasks (enquiry_id, plv_task_id, created_by, creation_time) values '.implode(", ", $selected_tasks);
			execute_query($sql);
			if(mysqli_error($db)){
				$msg .= '<h6 class="alert alert-danger">Error 2 # : '.mysqli_error($db).' >> '.$sql.'</h6>';
				$msg .= '<h6 class="alert alert-danger">Error 2.</h6>';
			}
			else{
				if($_POST['dataurl']!=''){
					$data = $_POST['dataurl'];
					list($type, $data) = explode(';', $data);
					list(, $data)      = explode(',', $data);
					$data = base64_decode($data);
					$newfilename = $insert_id . '.jpg';
					if(file_put_contents('user_data/visits/'.$newfilename, $data)){
						
					}
					else{
						$msg.='<div class="alert alert-danger">Upload Failed.</div>';
					}

				}
				elseif($_FILES['user_pic']['name']!=''){
					$allowed =  array('gif','png' ,'jpg', 'jpeg');
					$filename = $_FILES['user_pic']['name'];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					if(!in_array($ext,$allowed) ) {
						$msg .= '<div class="alert alert-danger">Invalid Image.</div>';
					}
					else{

						$temp = explode(".", $_FILES["user_pic"]["name"]);
						$newfilename = $insert_id . '.' . end($temp);	
						if(move_uploaded_file($_FILES["user_pic"]["tmp_name"], "user_data/visits/".$newfilename)){
						}
						else{
							$msg.='<div class="alert alert-danger">Upload Failed.</div>';
						}
					}
				}
				$sql = 'update enquiry_customer_invoice set photo_id="'.$newfilename.'" where sno='.$insert_id;
				execute_query($sql);
				if(mysqli_error($db)){
					$msg .= '<h6 class="alert alert-danger">Error 2 # : '.mysqli_error($db).' >> '.$sql.'</h6>';
					$msg .= '<h6 class="alert alert-danger">Error 2.</h6>';
				}
			}
		}
		$id = $insert_id;
		if($msg==''){
			$msg .= '<h6 class="alert alert-success">Success.</h6>';
			$_POST['aadhaar']='';
			$_POST['mobile']='';
			$_POST['email']='';
			$_POST['full_name']='';
			$_POST['father_name']='';
			$_POST['address']='';
			$_POST['problem']='';
			$_POST['solution']='';

		}
	}
}
elseif(isset($_POST['mobile'])){
	
	$sql = 'select * from enquiry_customer where mobile="'.$_POST['mobile'].'"';
	$result = execute_query($sql);
	if(mysqli_num_rows($result)!=0){
		$row = mysqli_fetch_assoc($result);
		$_POST['aadhaar']=$row['adhar_no'];
		$_POST['email']=$row['mob_4'];
		$_POST['full_name']=$row['cus_name'];
		$_POST['father_name']=$row['fname'];
		$_POST['address']='';
	}
	else{

		$_POST['aadhaar']='';
		$_POST['email']='';
		$_POST['full_name']='';
		$_POST['father_name']='';
		$_POST['address']='';
		$_POST['problem']='';
		$_POST['solution']='';
	}
}
else{
	$_POST['aadhaar']='';
	$_POST['mobile']='';
	$_POST['email']='';
	$_POST['full_name']='';
	$_POST['father_name']='';
	$_POST['problem']='';
	$_POST['solution']='';
	$_POST['address']='';
	$_POST['visit_date'] = date("Y-m-d");
}
?>

<?php
page_header_start();
?>
<link href="css/multistepform.css" rel="stylesheet" type="text/css" media="all" />
<script src="js/survey_validation.js"></script>
<?php
page_header_end();
page_sidebar();

?>	
  				
				<div class="row">					
					<div class="col-md-12">
						<div class="card">
							<div class="card-body">
								<div class="row d-flex my-auto">
									<div class="col-md-12">
										<div class="progress">
											<div aria-valuemax="100" aria-valuemin="0" aria-valuenow="50" class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: 0%"></div>
										</div>
										<form action="scripts/ajax.php" method="post" enctype="multipart/form-data" id="user_form" name="user_form">
											<div id="steps-container">
	<!-------------------1st start--------------------------------------------------------------------->
												<div class="step">
												
													<h4>1.समिति का विवरण</h4>
													<div class="col-sm-12">
														<div class="row">
															<div class="col-sm-3 form-group">
																<label>मण्डल</label>
																<select name="division_name" id="division_name" tabindex="<?php echo $tab++; ?>"  class="form-control" onChange="fill_district(this.value);">
																	<option value="">--Select--</option>
																	<?php
																	$sql = 'select * from master_division';
																	$result_division = execute_query($sql);
																	while($row_division = mysqli_fetch_assoc($result_division)){
																		echo '<option value="'.$row_division['sno'].'">'.$row_division['division_name'].'</option>';
																	}
																	?>
																</select>
															</div>
															<div class="col-sm-3 form-group">
																<label>जनपद</label>
																<select name="district_name" id="district_name" tabindex="<?php echo $tab++; ?>"  class="form-control" onChange="fill_tehseel(this.value);">
																
																</select>
															</div>
															<div class="col-sm-3 form-group">
																<label>तहसील</label>
																<select name="tehseel_name" id="tehseel_name" tabindex="<?php echo $tab++; ?>"  class="form-control" onChange="fill_block(this.value);">
																</select>
															</div>
															
															<div class="col-sm-3 form-group">
																<label>विकासखंड</label>
																<select name="block_name" id="block_name" tabindex="<?php echo $tab++; ?>"  class="form-control" onChange="fill_type(this.value);">
																</select>
															</div>
														</div>
														<div class="row">
														
															<div class="col-sm-4 form-group">
																<label>समिति का प्रकार</label>
																<select name="society_type" id="society_type" tabindex="<?php echo $tab++; ?>"  class="form-control" onChange="fill_society(this.value);">
																</select>
															</div>
															<div class="col-sm-4 form-group">
																<label>समिति का नाम</label>
																<select name="society_name" id="society_name" tabindex="<?php echo $tab++; ?>"  class="form-control"></select>
															</div>
															<div class="col-sm-4 form-group">
																<label>समिति पंजीकरण संख्या</label>
																<input type="text" name="society_registration_no" id="society_registration_no" tabindex="<?php echo $tab++; ?>"  class="form-control">
															</div>
														</div>
														<div class="row">
															<div class="col-sm-4 form-group">
																<label>क्या समिति परिसमापन (Liquidation) में है?</label>
																<select name="sec1_liquidation" id="sec1_liquidation" tabindex="<?php echo $tab++; ?>"  class="form-control">
																	<option value="">--Select--</option>
																	<option value="yes">हां</option>
																	<option value="no">नहीं</option>
																</select>
															</div>
															<div class="col-sm-4 form-group">
																<label>क्या समिति पर कोई वाद (Litigation) न्यायालय में विचाराधीन हैं?</label>
																<select name="sec_1_liquidation" id="sec_1_liquidation" tabindex="<?php echo $tab++; ?>"  class="form-control">
																	<option value="">--Select--</option>
																	<option value="yes">हां</option>
																	<option value="no">नहीं</option>
																</select>
															</div>
														</div>
														<div class="row">
															<div class="col-sm-4 form-group">
																<label>मोबाइल नंबर</label>
																<input type="text" name="mobile_number" id="mobile_number" tabindex="<?php echo $tab++; ?>"  class="form-control">
															</div>
															<div class="col-sm-4 form-group">
																<label>ई-मेल आई.डी.</label>
																<input type="text" name="sec1_email" id="sec1_email" tabindex="<?php echo $tab++; ?>"  class="form-control">
															</div>
															<div class="col-sm-4 form-group">
																<label>समिति कि फोटो संलग्न करें</label>
																<input type="file" name="society_photo" id="society_photo" tabindex="<?php echo $tab++; ?>"  class="form-control">
															</div>

														</div>
														<div class="row">
															<div class="col-sm-4 form-group">
																<label>प्रपत्र भर रहे व्यक्ति का नाम</label>
																<input type="text" name="person_name" id="person_name" tabindex="<?php echo $tab++; ?>" class="form-control">
															</div>
															<div class="col-sm-4 form-group">
																<label>प्रपत्र भर रहे व्यक्ति का पदनाम</label>
																<input type="text" name="person_designation" id="person_designation" tabindex="<?php echo $tab++; ?>" class="form-control">
															</div>
															<div class="col-sm-4 form-group">
																<label>प्रपत्र भर रहे व्यक्ति का आधार नम्बर</label>
																<input type="text" name="person_aadhaar" id="person_aadhaar" tabindex="<?php echo $tab++; ?>" class="form-control">
															</div>
														</div>								   
														<div class="row">
																<div class="col-sm-4 form-group">
																	<label>सक्रिय सदस्य (संख्या)</label>
																	<input type="text" name="sec_1_members_active" id="sec_1_members_active" tabindex="<?php echo $tab++; ?>"  class="form-control">
																</div>
																<div class="col-sm-4 form-group">
																	<label>निष्क्रिय सदस्य (संख्या)</label>
																	<input type="text" name="sec_1_members_non_active" id="sec_1_members_non_active" tabindex="<?php echo $tab++; ?>"  class="form-control">
																</div>
																<div class="col-sm-4 form-group">
																	<label>अन्य</label>
																	<input type="text" name="sec_1_others" id="sec_1_others" tabindex="<?php echo $tab++; ?>"  class="form-control">
																</div>
															</div>
													</div>
													
												</div>
	<!----------------2.1 start-------------------------------------------------------->
												<div class="step">
													<h4>2.1. कार्य व व्यवसाय</h4>
													<div class="col-sm-12">
														<div class="row">
															<div class="col-sm-3 form-group">
																<label>उर्वरक / कृषि निवेश</label>
																<input type="text" name="sec_1_investment" id="sec_1_investment" tabindex="<?php echo $tab++; ?>"  class="form-control">
															</div>
															<div class="col-sm-3 form-group">
																<label>ऋण </label>
																<input type="text" name="sec_1_loan" id="sec_1_loan" tabindex="<?php echo $tab++; ?>"  class="form-control">
															</div>
															<div class="col-sm-3 form-group">
																<label>मूल्य समर्थन</label>
																<input type="text" name="sec_1_msp" id="sec_1_msp" tabindex="<?php echo $tab++; ?>"  class="form-control">
															</div>
															<div class="col-sm-3 form-group">
																<label>सार्वजनिक वितरण प्रणाली (PDS)</label>
																<select name="sec_1_PDS" id="sec_1_PDS" tabindex="<?php echo $tab++; ?>"  class="form-control">
																	<option value="">--Select--</option>
																	<option value="yes">है</option>
																	<option value="no">नहीं</option>
																</select>
															</div>

														</div>
														<div class="row">
															<div class="col-sm-4 form-group">
																<label>कुल व्यवसाय</label>
																<input type="text" name="sec_1_total_business" id="sec_1_total_business" tabindex="<?php echo $tab++; ?>"  class="form-control">
															</div>
															<div class="col-sm-2 form-group">
																<label>गत वित्तीय वर्ष में लाभ/हानि</label>
																<select type="text" name="sec_1_profit_loss" id="sec_1_profit_loss" tabindex="<?php echo $tab++; ?>"  class="form-control" class="col-sm-1 form-group" tabindex="<?php echo $tab++; ?>">
																	<option value="">--Select--</option>
																	<option value="हानि" >हानि</option>
																	<option value="लाभ/">लाभ</option>

																</select>
															</div>
															<div class="col-sm-2 form-group">
																<label>लाभ/हानि (धनराशि)</label>
																<input type="text" name="sec_1_profit_loss_amount" id="sec_1_profit_loss_amount" tabindex="<?php echo $tab++; ?>"  class="form-control">
															</div>
															<div class="col-sm-2 form-group">
																<label>क्रमिक लाभ/हानि</label>
																<select type="text" name="sec_1_sequentially" id="sec_1_sequentially" tabindex="<?php echo $tab++; ?>"  class="form-control" class="col-sm-1 form-group" tabindex="<?php echo $tab++; ?>">
																	<option value="">--Select--</option>
																	<option value="हानि" >हानि</option>
																	<option value="लाभ/">लाभ</option>

																</select>
															</div>
															<div class="col-sm-2 form-group">
																<label>लाभ/हानि (धनराशि)</label>
																<input type="text" name="sec_1_sequentially_amount" id="sec_1_sequentially_amount" tabindex="<?php echo $tab++; ?>"  class="form-control">
															</div>

														</div>	
														<div class="row">
															<div class="col-sm-4 form-group">
																<label>वित्तिय आडिट किस वर्ष तक हुआ है</label>
																<select name="sec2_update_year" id="sec2_update_year" tabindex="<?php echo $tab++; ?>" class="form-control">
																	<option value="">--Select--</option>
																	<option value="2022">2022</option>
																	<option value="2021">2021</option>
																	<option value="2020">2020</option>
																	<option value="2019">2019</option>
																	<option value="2018">2018</option>																	
																	<option value="2017">2017</option>																	
																	<option value="2016">2016</option>																	
																	<option value="2015">2015</option>																	
																	<option value="old">2015 से पूर्व में</option>																	
																</select>
															</div>
															<div class="col-sm-4 form-group">
																<label>संतुलन पत्र किस वर्ष तक बना है</label>
																<select name="sec2_year" id="sec2_yaer" tabindex="<?php echo $tab++; ?>" class="form-control">
																	<option value="">--Select--</option>
																	<option value="2022">2022</option>
																	<option value="2021">2021</option>
																	<option value="2020">2020</option>
																	<option value="2019">2019</option>
																	<option value="2018">2018</option>																	
																	<option value="2017">2017</option>																	
																	<option value="2016">2016</option>																	
																	<option value="2015">2015</option>																	
																	<option value="old">2015 से पूर्व में</option>																	
																</select>
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
																<select class="form-control " type="select"  value="yes" name="sec_2_secretary" id="sec_2_secretary" tabindex="<?php echo $tab++;?>" onChange="display_sachiv_cader(this.value);">
																	<option value="">--Select--</option>
																	<option value="yes">है</option>
																	<option value="no">नहीं</option>
																</select>
															</div>
															<div class="col-sm-4 form-group" id="sachiv_cader" style="display: none;">
																<label>यदि सचिव है तो</label>
																	<select class="form-control " type="checkbox" id="sec_2_if_yes"  name="sec_2_if_yes" tabindex="<?php echo $tab++; ?>">
																		<option value="">--Select--</option>
																		<option value="कैडर">कैडर से हैं</option>
																		<option value="नान कैडर">नान कैडर से हैं</option>
																	</select>
															</div>
														</div>
														<div class="row">										
															<div class="col-sm-4 form-group">
																<label>लेखाकार</label>
																	<select class="form-control " type="checkbox"value="yes"  id="sec_2_accountant" name="sec_2_accountant" tabindex="<?php echo $tab++; ?>" onChange="accountant(this.value)">
																		<option value="">--Select--</option>
																		<option value="yes">है</option>
																		<option value="no">नहीं</option>
																	</select>
															</div>
															<div class="col-sm-4 form-group" id="accountant_count" style="display: none;">
																<label>लेखाकार (संख्या)</label>
																	<input type="text" name="sec_2_accountant_count" id="sec_2_accountant_count" tabindex="<?php echo $tab++; ?>"  class="form-control" tabindex="<?php echo $tab++; ?>">
															</div>
														</div>
														<div class="row">
															
															<div class="col-sm-4 form-group">
															<label>विक्रेता</label>
																<select class="form-control " type="checkbox" id="sec_2_seller" name="sec_2_seller" value="yes" tabindex="<?php echo $tab++; ?>" onChange="seller(this.value)">
																	<option value="">--Select--</option>
																	<option value="yes">है </option>
																	<option value="no">नहीं</option>
																</select>
															</div>
															<div class="col-sm-4 form-group" id="seller_count" style="display: none;">
																<label>विक्रेता (संख्या)</label>
																<input type="text" name="sec_2_seller_count" id="sec_2_seller_count" tabindex="<?php echo $tab++; ?>"  class="form-control" tabindex="<?php echo $tab++; ?>">
															</div>
														</div>
														<div class="row">
															<div class="col-sm-4 form-group">
																<label>सहयोगी</label>
																<select class="form-control " type="checkbox" id="sec_2_support_staff" name="sec_2_support_staff" value="yes" tabindex="<?php echo $tab++; ?>"  onChange="support_staff(this.value)">
																	<option value="">--Select--</option>
																	<option value="yes">है </option>
																	<option value="no">नहीं</option>
																</select>
															</div>
															<div class="col-sm-4 form-group" id="support_staff_count" style="display: none;">
																<label>सहयोगी (संख्या)</label>
																<input type="text" name="sec_2_support_staff_count" id="sec_2_support_staff_count" tabindex="<?php echo $tab++; ?>"  class="form-control" tabindex="<?php echo $tab++; ?>">
															</div>
														</div>
														<div class="row">
															<div class="col-sm-4 form-group">
																<label>चौकीदार</label>
																<select class="form-control " type="checkbox" id="sec_2_guard" name="sec_2_guard" value="yes" tabindex="<?php echo $tab++; ?>"  onChange="guard(this.value)">
																	<option value="">--Select--</option>
																	<option value="yes">है </option>
																	<option value="no">नहीं</option>
																</select>
															</div>
															<div class="col-sm-4 form-group" id="guard_count" style="display: none;">
																<label>चौकीदार (संख्या)</label>
																<input type="text" name="sec_2_guard_count" id="sec_2_guard_count" tabindex="<?php echo $tab++; ?>"  class="form-control" tabindex="<?php echo $tab++; ?>">
															</div>
														</div>
														
														<div class="row">
															<div class="col-sm-4 form-group">
																<label>कंप्यूटर आपरेटर</label>
																<select class="form-control " type="checkbox" id="sec_2_computer_operator" name="sec_2_computer_operator" value="yes" tabindex="<?php echo $tab++; ?>"  onChange="computer_operator(this.value)">
																	<option value="">--Select--</option>
																	<option value="yes">है </option>
																	<option value="no">नहीं</option>
																</select>
															</div>
															<div class="col-sm-4 form-group" id="computer_operator_count" style="display: none;">
																<label>कंप्यूटर आपरेटर (संख्या)</label>
																<input type="text" name="sec_2_computer_operator_count" id="sec_2_computer_operator_count" tabindex="<?php echo $tab++; ?>"  class="form-control" tabindex="<?php echo $tab++; ?>">
															</div>
														</div>
														<div class="row">
															<div class="col-sm-6 form-group">
																<label>केंद्र सरकार की योजना अंतर्गत चयनित सघन सहकारी समिति के कंप्यूटरीकरण की कार्य योजना</label>
																<select class="form-control " type="select"  value="yes" name="sec_2_govt_program" id="sec_2_govt_program" tabindex="<?php echo $tab++;?>">
																	<option value="">--Select--</option>
																	<option value="प्रथम चरण">प्रथम चरण</option>
																	<option value="द्वितीय चरण">द्वितीय चरण</option>
																	<option value="तृतीय चरण">तृतीय चरण</option>
																	<option value="लागू नहीं">लागू नहीं</option>
																</select>
															</div>	
															<div class="col-sm-6 form-group">
																<label>अन्य विवरण (यदि आवश्यक हो)</label>
																<input type="text" name="sec_2_other_description" id="sec_2_other_description" tabindex="<?php echo $tab++; ?>"  class="form-control" tabindex="<?php echo $tab++; ?>">
															</div>
														</div>												
															
													</div>
												</div>
	<!---------------3rd Start---------------------------------------------------------------->
												
												<div class="step">
													<h4>3.समिति भवन का विवरण</h2>
													<div class="row">
														<div class="col-sm-12">
															<h5> 3.1. भूखंड का विवरण </h5>				
															<div class="row">
																<div class="col-sm-3 form-group">
																	<label>भूखण्ड में भुजा कि संख्या</label>
																	<label><small>(उदाहरण के लिये - यदि भूखण्ड आयताकार है तो भुजाओं कि संख्या 4 लिखें)</small></label>
																	<input type="number" name="sec_3_a_land_length" id="sec_3_a_land_length" tabindex="<?php echo $tab++; ?>" class="form-control" onBlur="show_sides_of_land(this.value);" max="6">
																</div>
																<div class="col-sm-3 form-group">
																	<label>क्षेत्रफल (हेक्टेयर में)</label><br/>
																	<label><small>&nbsp;</small></label>
																	<input type="text" name="sec_3_a_area" id="sec_3_a_area" tabindex="<?php echo $tab++; ?>" class="form-control">
																</div>	
																<div class="col-sm-3 form-group">
																	<label>राजस्व अभिलेख में दर्ज होने की स्थिति( हाँ /नहीं)</label><br/>
																	<label><small>&nbsp;</small></label>
																	<select name="sec_3_a_govt_records" id="sec_3_a_govt_records" tabindex="<?php echo $tab++; ?>" class="form-control" onChange="hide_show(this.value, '#land_records', 'no')">
																		<option value="">--Select--</option>
																		<option value="yes">हाँ</option>
																		<option value="no">नहीं</option>
																	</select>
																</div>
																<div class="col-sm-3 form-group" id="land_records" style="display: none;">
																	<label>यदि नहीं है तो किये जाने वाले प्रयास का विवरण</label><br/>
																	<label><small>&nbsp;</small></label>
																	<input type="text" name="sec_3_a_if_yes" id="sec_3_a_if_yes" tabindex="<?php echo $tab++; ?>" class="form-control">
																</div>
															</div>
															<div class="row">
																<div class="col-sm-2 form-group">
																	<label>समिति भूखण्ड फोटो संलग्न करें</label><br/>
																	<label><small>&nbsp;</small></label>
																	<input type="file" name="sec_3_a_image" id="sec_3_a_image" tabindex="<?php echo $tab++; ?>"  class="form-control">
																</div>
																<div class="col-sm-3 form-group">
																	<label>टिप्पणी</label><br/>
																	<label><small>&nbsp;</small></label>
																	<input type="text" name="sec_3_a_comment" id="sec_3_a_comment" tabindex="<?php echo $tab++; ?>"  class="form-control">
																</div>
																
															</div>
															<div id="sides_display" style="display: none;" class="row">
																
															</div>
															<h5> 3.2. भूखंड की चौहद्दी का विवरण </h5>				
															<div class="row">
																<div class="col-sm-3 form-group">
																	<label>भूखण्ड की पूर्व दिशा का विवरण</label>
																	<input type="text" name="sec_3_a_land_chauhaddi_east" id="sec_3_a_land_chauhaddi_east" tabindex="<?php echo $tab++; ?>" class="form-control" onBlur="show_sides_of_land(this.value);" max="6">
																</div>	
																<div class="col-sm-3 form-group">
																	<label>भूखण्ड की पश्चिम दिशा का विवरण</label><input type="text" name="sec_3_a_land_chauhaddi_west" id="sec_3_a_land_chauhaddi_west" tabindex="<?php echo $tab++; ?>" class="form-control">
																</div>
																<div class="col-sm-3 form-group">
																	<label>भूखण्ड की उत्तर दिशा का विवरण</label><input type="text" name="sec_3_a_land_chauhaddi_north" id="sec_3_a_land_chauhaddi_north" tabindex="<?php echo $tab++; ?>"  class="form-control">
																</div>
																<div class="col-sm-3 form-group">
																	<label>भूखण्ड की दक्षिण दिशा का विवरण</label><input type="text" name="sec_3_a_land_chauhaddi_south" id="sec_3_a_land_chauhaddi_south" tabindex="<?php echo $tab++; ?>"  class="form-control">
																</div>
															</div>
															<div class="row">
																<div class="col-sm-3 form-group">
																	<label>सड़क पर भूमि कि लम्बाई (आन रोड जमीन) मीटर में</label>
																	<input type="text" name="sec_3_a_land_on_road" id="sec_3_a_land_on_road" tabindex="<?php echo $tab++; ?>" class="form-control" onBlur="show_sides_of_land(this.value);" max="6">
																</div>	
																<div class="col-sm-3 form-group">
																	<label>प्रमुख द्वार कि दिशा (फ्र्न्ट साईड)</label>
																	<select name="sec_3_a_land_frontage" id="sec_3_a_land_frontage" tabindex="<?php echo $tab++; ?>" class="form-control">
																		<option value="east">पूर्व</option>
																		<option value="west">पश्चिम</option>
																		<option value="north">उत्तर</option>
																		<option value="south">दक्षिण</option>
																	</select>
																</div>
															</div>
															<h5> 3.3. निर्मित भवन का विवरण </h5> 
															<div class="row"> 

															</div>
															<div class="row">
																<div class="col-sm-3 form-group">
																	<label>लंबाई (मीटर में)</label>
																	<input type="text" name="sec_3_b_length" id="sec_3_b_length" tabindex="<?php echo $tab++; ?>" class="form-control">
																</div>	
																<div class="col-sm-3 form-group">
																	<label>चौड़ाई (मीटर में)</label>
																	<input type="text" name="sec_3_b_width" id="sec_3_b_width" tabindex="<?php echo $tab++; ?>" class="form-control">
																</div>
																<div class="col-sm-2 form-group">
																	<label>भवन का प्रकार</label>
																	<select name="sec_3_b_type_of_construction" id="sec_3_b_type_of_construction" tabindex="<?php echo $tab++; ?>" class="form-control">
																		<option value="">--Select--</option>
																		<?php
																		$sql = 'select * from master_type_of_construction';
																		$result_const = execute_query($sql);
																		while($row_const = mysqli_fetch_assoc($result_const)){
																			echo '<option value="'.$row_const['sno'].'">'.$row_const['type_of_construction'].'</option>';
																		}
																		
																		?>
																	</select>
																</div>	
																<div class="col-sm-2 form-group">
																	<label>टिप्पणी</label>
																	<input type="text" name="sec_3_b_comment" id="sec_3_b_comment" tabindex="<?php echo $tab++; ?>"  class="form-control">
																</div>	
																<div class="col-sm-2 form-group my-auto">
																	<button type="button" class="btn btn-info">नई पंक्ति जोड़े</button>
																</div>	
															</div>
															<h5> 3.4. निर्मित गोदाम का विवरण </h5>
															<div class="row">
																<div class="col-sm-3 form-group">
																	<label>लंबाई (मीटर में)</label>
																	<input type="text" name="sec_3_b_godown_length" id="sec_3_b_godown_length" tabindex="<?php echo $tab++; ?>" class="form-control">
																</div>	
																<div class="col-sm-3 form-group">
																	<label>चौड़ाई (मीटर में)</label>
																	<input type="text" name="sec_3_b_godown_width" id="sec_3_b_godown_width" tabindex="<?php echo $tab++; ?>" class="form-control">
																</div>
																<div class="col-sm-3 form-group">
																	<label>क्षमता (मेट्रिक टन में)</label>
																	<input type="text" name="sec_3_b_storage_capacity" id="storage_capacity" tabindex="<?php echo $tab++; ?>" class="form-control">
																</div>	
																<div class="col-sm-3 form-group">
																	<label>टिप्पणी</label>
																	<input type="text" name="sec_3_b__comment" id="sec_3_b__comment" tabindex="<?php echo $tab++; ?>"  class="form-control">
																</div>		
															</div>
															<h5> 3.5. खाली पड़ी भूमि का विवरण </h5>
															<div class="row"> 

															</div>
															<div class="row">
																<div class="col-sm-2 form-group">
																	<label>क्षेत्रफल (हेक्टेयर में)</label>
																	<input type="text" name="sec_3_c_length" id="sec_3_c_length" tabindex="<?php echo $tab++; ?>" class="form-control">
																</div>	
																<div class="col-sm-2 form-group">
																	<label>भूमि की स्थिति (उपजाऊ /बंजर)</label>
																	<select name="sec_3_c_vacant_land_status" id="sec_3_c_vacant_land_status" tabindex="<?php echo $tab++; ?>"  class="form-control">
																	<option value="">--select-- </option>
																	<option value="उपजाऊ">उपजाऊ </option>
																	<option value="बंजर">बंजर </option>
																	</select>
																</div>						
																
																<div class="col-sm-2 form-group">
																	<label>स्थान (समिति प्रांगण या अन्य स्थान)*</label>
																	<select name="sec_3_c_land_location" id="sec_3_c_land_location" tabindex="<?php echo $tab++; ?>"  class="form-control" onChange="hide_show(this.value, '#land_connectivity', 'other'); hide_show(this.value, '#land_access_road', 'na');">
																	<option value="">--select-- </option>
																	<option value="inpremise">समिति प्रांगण </option>
																	<option value="other">अन्य स्थान </option>
																	</select>
																</div>
																<div class="col-sm-2 form-group" id="land_connectivity" style="display: none;">
																	<label>संपर्क मार्ग*</label>
																	<select name="sec_3_c_approach_road" id="sec_3_c_approach_road" tabindex="<?php echo $tab++; ?>"  class="form-control" onChange="hide_show(this.value, '#land_access_road', 'proper');">
																	<option value="">--select-- </option>
																	<option value="ordinary">कच्ची सड़क </option>
																	<option value="proper">पक्की सड़क </option>
																	</select>
																</div>
																<div class="col-sm-2 form-group" id="land_access_road" style="display: none;">
																	<label>पक्की सड़क का प्रकार</label>
																	<select name="sec_3_c_paved_road" id="sec_3_c_paved_road" tabindex="<?php echo $tab++; ?>"  class="form-control">
																	<option value="">--select-- </option>
																	<option value="कच्ची सडक">ग्रामीण सड़क</option>
																	<option value="पक्की सडक">नेशनल हाईवे</option>
																	<option value="पक्की सडक">स्टेट हाईवे</option>
																	<option value="पक्की सडक">एम.डी.आर.</option>
																	<option value="पक्की सडक">ओ.डी.आर.</option>
																	<option value="पक्की सडक">अन्य</option>
																	</select>
																</div>	
																<div class="col-sm-2 form-group my-auto">
																	<button type="button" class="btn btn-info">नई पंक्ति जोड़े</button>
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
																<div class="col-sm-3 form-group">
																	<label>माईक्रो ए.टी.एम.</label>
																	<select name="sec_4_micro_atm" id="sec_4_micro_atm" tabindex="<?php echo $tab++; ?>" class="form-control">
																		<option value="">--Select--</option>
																		<option value="yes">है</option>
																		<option value="no">नहीं</option>
																	</select>
																</div>
																<div class="col-sm-3 form-group">
																	<label>कस्टम हाईरिंग सेंटर</label>
																	<select name="sec_4_casting_hiring" id="sec_4_casting_hiring" tabindex="<?php echo $tab++; ?>" class="form-control">
																		<option value="">--Select--</option>
																		<option value="yes">है</option>
																		<option value="no">नहीं</option>
																	</select>
																</div>
																<div class="col-sm-3 form-group">
																	<label>ड्रोन</label>
																	<select name="sec_4_drone" id="sec_4_drone" tabindex="<?php echo $tab++; ?>" class="form-control">
																		<option value="">--Select--</option>
																		<option value="yes">है</option>
																		<option value="no">नहीं</option>
																	</select>
																</div>
																<div class="col-sm-3 form-group">
																	<label>छन्ना</label>
																	<select name="sec_4_chhanna" id="sec_4_chhanna" tabindex="<?php echo $tab++; ?>" class="form-control">
																		<option value="">--Select--</option>
																		<option value="yes">है</option>
																		<option value="no">नहीं</option>
																	</select>
																</div>
															</div>	
															<div class="row">
																<div class="col-sm-4 form-group">
																	<label>कुर्सी (संख्या)</label>
																	<input type="text" name="sec_4_chair" id="sec_4_chair" tabindex="<?php echo $tab++; ?>" class="form-control">
																</div>
																<div class="col-sm-4 form-group">
																	<label>मेज(संख्या)</label>
																	<input type="text" name="sec_4_table" id="sec_4_table" tabindex="<?php echo $tab++; ?>" class="form-control">
																</div>
																<div class="col-sm-4 form-group">
																	<label>अलमारी (संख्या)</label>
																	<input type="text" name="sec_4_almari" id="sec_4_almari" tabindex="<?php echo $tab++; ?>" class="form-control">
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
																<select name="sec_5_built_building" id="sec_5_built_building" tabindex="<?php echo $tab++; ?>"  class="form-control" onChange="hide_show(this.value, '#jarjar_remarks', 'discarded'); hide_show(this.value, '#repairable', 'repairable')">
																<option value="">--select-- </option>
																<option value="good">अच्छा</option>
																<option value="repairable">खराब/मरम्मत योग्य</option>
																<option value="discarded">जर्जर/निषप्रयोज्य</option>
																</select>
															</div>
															<div class="col-sm-4 form-group" id="jarjar_remarks" style="display: none;">
																<label>कृप्या विस्तृत जानकारी दर्ज करें</label>
																<input type="text" name="sec_5_detailed_information" id="sec_5_detailed_information" tabindex="<?php echo $tab++; ?>"  class="form-control">
															</div>						
														</div>
													</div>
													
													<div id="repairable" style="display: none;">
														<h4>6. यदि मरम्मत योग्य है तो आवश्यक्तावार विवरण दर्ज करें</h4>
														<h6>जिन चीजों कि मरम्मत की आवश्यक्ता हो उसे दर्ज करें अन्य को खाली छोड़ दें</h6>
														<div class="col-sm-12">
															<div class="row">
																<div class="col-sm-12">
																	<h5>6.1. फर्श</h5>
																	<div class="row">
																		<div class="col-sm-4 form-group">
																			<label>लंबाई (मीटर में)</label>
																			<input type="text" name="sec_5_a_length" id="sec_5_a_length" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>	
																		<div class="col-sm-4 form-group">
																			<label>चौडाई (मीटर में)</label>
																			<input type="text" name="sec_5_a_width" id="sec_5_a_width" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>
																			<div class="col-sm-4 form-group">
																			<label>फोटो संलग्न करें</label>
																			<input type="file" name="sec_5_a_img" id="sec_5_a_img" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>						
																	</div>
																	<h5>6.2. दीवार </h5>
																	<div class="row">
																		<div class="col-sm-4 form-group">
																			<label>लंबाई (मीटर में)</label>
																			<input type="text" name="sec_5_b_length" id="sec_5_b_length" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>	
																		<div class="col-sm-4 form-group">
																			<label>चौडाई (मीटर में)</label>
																			<input type="text" name="sec_5_b_width" id="sec_5_b_width" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>
																			<div class="col-sm-4 form-group">
																			<label>फोटो संलग्न करें</label>
																			<input type="file" name="sec_5_b_img" id="sec_5_b_img" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>

																	</div>
																	<h5>6.3. पुताई</h5>
																	<div class="row">
																		<div class="col-sm-4 form-group">
																			<label>लंबाई (मीटर में)</label>
																			<input type="text" name="sec_5_c_length" id="sec_5_c_length" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>	
																		<div class="col-sm-4 form-group">
																			<label>चौडाई (मीटर में)</label>
																			<input type="text" name="sec_5_c_width" id="sec_5_c_width" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>
																		<div class="col-sm-4 form-group">
																			<label>फोटो संलग्न करें</label>
																			<input type="file" name="sec_5_c_img" id="sec_5_c_img" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>
																	</div>
																	<h5>6.4. छत </h5>
																	<div class="row">
																		<div class="col-sm-4 form-group">
																			<label>लंबाई (मीटर में)</label>
																			<input type="text" name="sec_6_d_length" id="sec_6_d_length" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>	
																		<div class="col-sm-4 form-group">
																			<label>चौडाई (मीटर में)</label>
																			<input type="text" name="sec_6_d_width" id="sec_6_d_width" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>
																		<div class="col-sm-4 form-group">
																			<label>फोटो संलग्न करें</label>
																			<input type="file" name="sec_5_6_img" id="sec_6_d_img" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>		
																	</div>
																	<h5>6.5. शौचालय</h5>
																	<div class="row">
																		<div class="col-sm-2 form-group">
																			<label>फर्श</label>
																			<select name="sec_6_e_fers" id="sec_6_e_fers" tabindex="<?php echo $tab++; ?>" class="form-control" onChange="hide_show(this.value, '#bathroom_floor', 'repairable')">
																				<option value="">--Select--</option>
																				<option value="good">सही है</option>
																				<option value="repairable">मरम्म्त योग्य</option>
																			</select>
																		</div>	
																		<div class="col-sm-2 form-group" style="display: none;" id="bathroom_floor">
																			<label>अनुमानित लागत</label>
																			<input type="text" name="sec_6_e_permission" id="sec_6_e_permission" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>
																		<div class="col-sm-2 form-group">
																			<label>प्लासटर</label>
																			<select name="sec_6_e_plaster" id="sec_6_e_plaster" tabindex="<?php echo $tab++; ?>" class="form-control" onChange="hide_show(this.value, '#bathroom_plaster', 'repairable')">
																				<option value="">--Select--</option>
																				<option value="good">सही है</option>
																				<option value="repairable">मरम्म्त योग्य</option>
																			</select>
																		</div>	
																		<div class="col-sm-2 form-group" style="display: none;" id="bathroom_plaster">
																			<label>अनुमानित लागत</label>
																			<input type="text" name="sec_6_e_plaster_cost" id="ec_6_e_plaster_cost" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>
																		<div class="col-sm-2 form-group">
																			<label>छत</label>
																			<select name="sec_6_e_ceiling" id="sec_6_e_ceiling" tabindex="<?php echo $tab++; ?>" class="form-control" onChange="hide_show(this.value, '#bathroom_roof', 'repairable')">
																				<option value="">--Select--</option>
																				<option value="good">सही है</option>
																				<option value="repairable">मरम्म्त योग्य</option>
																			</select>
																		</div>	
																		<div class="col-sm-2 form-group" style="display: none;" id="bathroom_roof">
																			<label>अनुमानित लागत</label>
																			<input type="text" name="sec_6_e_ceiling_cost" id="sec_6_e_ceiling_cost" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-sm-2 form-group">
																			<label>सीट</label>
																			<select name="ssec_6_e_seat" id="sec_6_e_seat" tabindex="<?php echo $tab++; ?>" class="form-control" onChange="hide_show(this.value, '#bathroom_seat', 'repairable')">
																				<option value="">--Select--</option>
																				<option value="good">सही है</option>
																				<option value="repairable">मरम्म्त योग्य</option>
																			</select>
																		</div>
																		<div class="col-sm-2 form-group" style="display: none;" id="bathroom_seat">
																			<label>अनुमानित लागत</label>
																			<input type="text" name="sec_6_e_seat_cost" id="sec_6_e_seat_cost" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>	
																		<div class="col-sm-2 form-group">
																			<label>प्लम्बिंग</label>
																			<select name="sec_6_e_plumbing" id="sec_6_e_plumbing" tabindex="<?php echo $tab++; ?>" class="form-control" onChange="hide_show(this.value, '#bathroom_plumbing', 'repairable')">
																				<option value="">--Select--</option>
																				<option value="good">सही है</option>
																				<option value="repairable">मरम्म्त योग्य</option>
																			</select>
																		</div>	
																		<div class="col-sm-2 form-group" style="display: none;" id="bathroom_plumbing">
																			<label>अनुमानित लागत</label>
																			<input type="text" name="sec_6_e_plumbing_cost" id="sec_6_e_plumbing_cost" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>	
																	</div>

																	<div class="row">
																		<div class="col-sm-4 form-group">
																			<h5>6.6. दरवाजा (संख्या)</h5>
																			<input type="text" name="sec_6_e_number_of_door" id="sec_6_e_number_of_door" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>
																		<div class="col-sm-4 form-group">
																			<h5>6.7. खिडकी (संख्या)</h5>

																			<input type="text" name="sec_6_f_number_of_window" id="sec_6_f_number_of_window" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>
																	</div>
																	<h5>6.8. प्लास्टर </h5>
																	<div class="row">
																		<div class="col-sm-4 form-group">
																			<label>दीवार (आवश्यकता अनुसार क्षेत्रफल स्क्वायर मीटर में लिखें)</label>
																			<input type="text" name="sec_5_d_length" id="sec_5_d_length" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>	
																		<div class="col-sm-4 form-group">
																			<label>छत  (आवश्यकता अनुसार क्षेत्रफल स्क्वायर मीटर में लिखें)</label>
																			<input type="text" name="sec_5_d_width" id="sec_5_d_width" tabindex="<?php echo $tab++; ?>" class="form-control">
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
																<select name="sec_6_access_road" id="sec_6_access_road" tabindex="<?php echo $tab++; ?>"  class="form-control" onChange="hide_show(this.value, '#access_road', 'proper'); hide_show(this.value, '#access_road_truck', 'ordinary');">
																<option value="">--select-- </option>
																<option value="ordinary">कच्ची सडक </option>
																<option value="proper">पक्की सडक</option>
																</select>
															</div>				
															<div class="col-sm-4 form-group" id="access_road" style="display: none;">
																<label>पक्की सड़क का प्रकार</label>
																<select name="sec_6_paved_road" id="sec_6_paved_road_road" tabindex="<?php echo $tab++; ?>"  class="form-control">
																<option value="">--select-- </option>
																<option value="कच्ची सडक">ग्रामीण सड़क</option>
																<option value="पक्की सडक">नेशनल हाईवे</option>
																<option value="पक्की सडक">स्टेट हाईवे</option>
																<option value="पक्की सडक">एम.डी.आर.</option>
																<option value="पक्की सडक">ओ.डी.आर.</option>
																<option value="पक्की सडक">अन्य</option>
																</select>
															</div>	
															<div class="col-sm-4 form-group" id="access_road_truck" style="display: none;">
																<label>यदि मुख्यालय तक ट्रक नही पहुंचता है तो पक्के मार्ग से मुख्यालय की दूरी (की. मी. में)</label>
																<input type="text" name="sec_6_2_truck_not_reach" id="sec_6_2_truck_not_reach" tabindex="<?php echo $tab++; ?>"  class="form-control">
															</div>
														</div>	
													</div>
													<h4>8.विद्युत कनेक्शन</h2>
													<div class="col-sm-12">
														<div class="row">
															<div class="col-sm-4 form-group">
																<label>विद्युत कनेक्शन</label>
																<select name="sec_7_electrical_connection" id="sec_7_electrical_connection" tabindex="<?php echo $tab++; ?>"  class="form-control" onChange="hide_show(this.value, '#electricity_not_available', 'no'); hide_show(this.value, '#electricity_available', 'yes'); hide_show(this.value, '#electricity_available_not_working', 'na');">
																<option value="">--select-- </option>
																<option value="yes">हाँ </option>
																<option value="no">नहीं</option>
																</select>
															</div>
															<div class="col-sm-4 form-group" id="electricity_available" style="display: none;">
																<label>यदि है तो कार्यरत है या नहीं</label>
																<select name="sec_7_electrical_connection_working" id="sec_7_electrical_connection_working" tabindex="<?php echo $tab++; ?>"  class="form-control" onChange="hide_show(this.value, '#electricity_available_not_working', 'no');">
																<option value="">--select-- </option>
																<option value="yes">हाँ </option>
																<option value="no">नहीं</option>
																</select>
															</div>
															<div class="col-sm-4 form-group" id="electricity_available_not_working" style="display: none;">
																<label>यदि कार्यरत नहीं है तो कारण</label>
																<input type="text" name="sec_7_electrical_connection_notworking" id="sec_7_electrical_connection_notworking" tabindex="<?php echo $tab++; ?>"  class="form-control">
															</div>						
															<div class="col-sm-4 form-group" id="electricity_not_available" style="display: none;">
																<label>यदि नहीं तो प्रस्ताव</label>
																<input type="text" name="sec_7_if_yes" id="sec_7_if_yes" tabindex="<?php echo $tab++; ?>"  class="form-control">
															</div>
														</div>
													</div>
													<h4>9.इण्टरनेट कनेक्शन</h2>
													<div class="col-sm-12">
														<div class="row">
															<div class="col-sm-4 form-group">
																<label>इण्टरनेट कनेक्शन</label>
																<select name="sec_8_internet_connection" id="sec_8_internet_connection" tabindex="<?php echo $tab++; ?>"  class="form-control" onChange="hide_show(this.value, '#net_con_available', 'yes'); hide_show(this.value, '#net_con_notavailable', 'no');">
																<option value="">--select-- </option>
																<option value="yes">हाँ </option>
																<option value="no">नहीं</option>
																</select>
															</div>					
															<div class="col-sm-4 form-group" id="net_con_available" style="display: none;">
																<label>यदि है तो सर्विस प्रोवाइडर का नाम</label>
																<select name="sec_8_if_yes" id="sec_8_if_yes" tabindex="<?php echo $tab++; ?>"  class="form-control">
																	<option value="">--Select--</option>
																	<option value="bsnl">BSNL</option>
																	<option value="jio">JIO</option>
																	<option value="vodafone">Vodafone</option>
																	<option value="airtel">Airtel</option>
																	
																</select>
															</div>
															<div class="col-sm-4 form-group" id="net_con_notavailable" style="display: none;">
																<label>क्षेत्र में उपलब्ध ईण्टरनेट सर्विस प्रोवाइडर के नाम (सभी उपलब्ध आपरेटर का चयन करें)</label>
																<select name="sec_6_select_operator" id="sec_6_select_operator" tabindex="<?php echo $tab++; ?>" multiple="multiple" class="form-control">
																	<option value="">--Select--</option>
																	<option value="bsnl">BSNL</option>
																	<option value="jio">JIO</option>
																	<option value="vodafone">Vodafone</option>
																	<option value="airtel">Airtel</option>
																	
																</select>
															</div>
														
														</div>
													</div>
													<h4>10.पेयजल की उपलब्धता</h2>
													<div class="col-sm-12">
														<div class="row">
															<div class="col-sm-4 form-group">
																<label>सरकारी नलके का पानी</label>
																<select name="sec_6_narrow_tubes" id="sec_6_narrow_tubes" tabindex="<?php echo $tab++; ?>"  class="form-control">
																<option value="">--select-- </option>
																<option value="yes">हाँ </option>
																<option value="no">नहीं</option>
																</select>
															</div>					
															<div class="col-sm-4 form-group">
																<label>पानी कि टंकी</label>
																<select name="sec_6_water_tank" id="sec_6_water_tank" tabindex="<?php echo $tab++; ?>"  class="form-control">
																	<option value="">--select-- </option>
																	<option value="yes">हाँ </option>
																	<option value="no">नहीं</option>																	
																</select>
															</div>
															<div class="col-sm-4 form-group">
																<label> सबमर्सिबल </label>
																<select name="sec_6_samarsabel" id="ec_6_samarsabel" tabindex="<?php echo $tab++; ?>" class="form-control">
																	<option value="">--select-- </option>
																	<option value="yes">हाँ </option>
																	<option value="no">नहीं</option>																	
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
																			<input type="text" name="sec_9_a_length" id="sec_9_a_length" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>	
																		<div class="col-sm-3 form-group">
																			<label>चौडाई (मीटर में)</label>
																			<input type="text" name="sec_9_a_width" id="sec_9_a_width" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>		
																		<div class="col-sm-3 form-group">
																			<label>क्षमता (मेट्रिक टन में)</label>
																			<input type="text" name="sec_9_a_capacity_in_mt" id="sec_9_a_capacity_in_mt" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>
																		
																	</div>
																	<h5>11.2. बाथरूम </h5>
																	<div class="row">
																		<div class="col-sm-3 form-group">
																			<label>लंबाई (मीटर में)</label>
																			<input type="text" name="sec_9_b_length" id="sec_9_b_length" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>	
																		<div class="col-sm-3 form-group">
																			<label>चौडाई (मीटर में)</label>
																			<input type="text" name="sec_9_b_width" id="sec_9_b_width" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>		
																	</div>	
																	<h5>11.3. शोरूम </h5>
																	<div class="row">
																		<div class="col-sm-3 form-group">
																			<label>लंबाई (मीटर में)</label>
																			<input type="text" name="sec_9_c_length" id="sec_9_c_length" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>	
																		<div class="col-sm-3 form-group">
																			<label>चौडाई (मीटर में)</label>
																			<input type="text" name="sec_9_c_width" id="sec_9_c_width" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>		
																	</div>
																	<h5>11.4. बाउंड्री वाल </h5>
																	<div class="row">
																		<div class="col-sm-3 form-group">
																			<label>लंबाई (मीटर में)</label>
																			<input type="text" name="sec_9_d_boundary_wall_length" id="sec_9_d_boundary_wall_length" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>	
																		<div class="col-sm-3 form-group">
																			<label>चौडाई (मीटर में)</label>
																			<input type="text" name="sec_9_d_boundary_wall_width" id="sec_9_d_boundary_wall_width" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>		
																	</div>
																	<h5>11.5. मल्टीपरपस हाल</h5>
																	<div class="row">
																		<div class="col-sm-3 form-group">
																			<label>लंबाई (मीटर में)</label>
																			<input type="text" name="sec_9_e_multipurpose_hall_length" id="sec_9_e_multipurpose_hall_length" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>	
																		<div class="col-sm-3 form-group">
																			<label>चौडाई (मीटर में)</label>
																			<input type="text" name="sec_9_e_multipurpose_hall_width" id="sec_9_e_multipurpose_hall_width" tabindex="<?php echo $tab++; ?>" class="form-control">
																		</div>		
																	</div>						
																					
																</div>
															</div>
														</div>
													</div>	
												<div id="success">
												   <div class="mt-5">
													  <h4>Success! We'll get back to you ASAP!</h4>
													  <p>Meanwhile, clean your hands often, use soap and water, or an alcohol-based hand rub, maintain a safe distance from anyone who is coughing or sneezing and always wear a mask when physical distancing is not possible.</p>
													  <a class="back-link" href="">Go back from the beginning ➜</a>
												   </div>
												</div>
											</div>
											
											<div id="q-box__buttons">
												<button id="prev-btn" class="btn btn-info" type="button">Previous</button> 
												<button id="next-btn" class="btn btn-success" type="button">Next</button> 
												<button id="submit-btn" class="btn btn-danger" type="submit">Submit</button>
											 </div>
											 <button class="btn btn-warning" type="button" onClick="save_draft()"><i class="fas fa-save"></i> Save Darft</button>
											 <input type="hidden" id="term" name="term" value="a">
											 <input type="hidden" id="id" name="id" value="submit_form">
											 <input type="hidden" id="latitude" name="latitude" value="">
											 <input type="hidden" id="longitude" name="longitude" value="">
											 <input type="hidden" id="id" name="id" value="submit_form">
											 <input type="hidden" id="survey_id" name="survey_id" value="">
									  </form>
									</div>                                	


								</div>
							</div>
						</div>
					</div>
				</div>		
				
<?php
page_footer_start();
?>
<div id="preloader-wrapper">
   <div id="preloader"></div>
   <div class="preloader-section section-left"></div>
   <div class="preloader-section section-right"></div>
</div>

<!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
<script src="js/light-bootstrap-dashboard.js?v=1.4.0"></script>
<script>

function save_draft(){
	var form = $("#user_form");
	var actionUrl = form.attr('action');
	$.ajax({
		type: "POST",
		url: actionUrl,
		data: form.serialize(), // serializes the form's elements.
		success: function(data){
			data = JSON.parse(data);
			data = data[0];
			console.log(data);
			if(data.id=='error'){
				alert(data.error);
			}
			else{
				$("#survey_id").val(data.id);
			}
		}
	});
}
	
function fill_district(val){
	var form = $("#user_form");
    var actionUrl = form.attr('action');
	var data = {"term":"b", "id":"dist", "val":val};
	$.ajax({
        type: "POST",
        url: actionUrl,
        data: data, // serializes the form's elements.
        success: function(data){
			var txt = '<option value="">--Select--</option>';
			data = JSON.parse(data);
			$.each(data, function(key, value){
				txt += '<option value="'+value.id+'">'+value.district_name+'</option>';
				
			});
          	$("#district_name").html(txt);
        }
    });
}

function fill_tehseel(val){
	var form = $("#user_form");
    var actionUrl = form.attr('action');
	var data = {term:"b", id:"tehseel", val:val};
	$.ajax({
        type: "POST",
        url: actionUrl,
        data: data, // serializes the form's elements.
        success: function(data){
			var txt = '<option value="">--Select--</option>';
			data = JSON.parse(data);
			$.each(data, function(key, value){
				txt += '<option value="'+value.id+'">'+value.tehseel_name+'</option>';
				
			});
			$("#tehseel_name").html(txt);
        }
    });
}
	
function fill_block(val){
	var form = $("#user_form");
    var actionUrl = form.attr('action');
	var data = {term:"b", id:"block", val:val};
	$.ajax({
        type: "POST",
        url: actionUrl,
        data: data, // serializes the form's elements.
        success: function(data){
			var txt = '<option value="">--Select--</option>';
			data = JSON.parse(data);
			$.each(data, function(key, value){
				txt += '<option value="'+value.id+'">'+value.block_name+'</option>';
				
			});
          	$("#block_name").html(txt);
        }
    });
}

function fill_type(val){
	var form = $("#user_form");
    var actionUrl = form.attr('action');
	var data = {term:"b", id:"type", val:val};
	$.ajax({
        type: "POST",
        url: actionUrl,
        data: data, // serializes the form's elements.
        success: function(data){
			var txt = '<option value="">--Select--</option>';
			data = JSON.parse(data);
			$.each(data, function(key, value){
				txt += '<option value="'+value.id+'">'+value.type_name+'</option>';
				
			});
          	$("#society_type").html(txt);
        }
    });
}
	
function fill_society(val){
	var form = $("#user_form");
    var actionUrl = form.attr('action');
	var data = {term:"b", id:"society", val:val, division:$("#division_name").val(), district:$("#district_name").val(), tehseel:$("#tehseel_name").val(), block:$("#block_name").val()};
	$.ajax({
        type: "POST",
        url: actionUrl,
        data: data, // serializes the form's elements.
        success: function(data){
			var txt = '<option value="">--Select--</option>';
			data = JSON.parse(data);
			$.each(data, function(key, value){
				txt += '<option value="'+value.id+'">'+value.society_name+'</option>';
				
			});
          	$("#society_name").html(txt);
        }
    });
}

function getLocation() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(showPosition);
	} 
	else { 
		x.innerHTML = "Geolocation is not supported by this browser.";
	}
}	
	
function showPosition(position) {
	$("#latitude").val(position.coords.latitude);
	$("#longitude").val(position.coords.longitude);
}	

$(document).ready(function() {
	getLocation();
});
	
</script>
   
<script>
$('select[multiple]').multiselect({
    columns: 1,
    placeholder: 'Select options'
});
</script>

<script type="text/javascript" src="js/multistepform.js">
<?php		
page_footer_end();
?>

<?php

include("scripts/settings.php");
$msg='';
$tab=1;

?>

<?php
page_header_start();
?>
<script src="js/survey_validation.js"></script>

<?php
page_header_end();
page_sidebar();
if(isset($_POST['submit'])){
	foreach($_POST as $k=>$v){
		$_SESSION['sale_'.$k] = $v;
		/*$_SESSION['sale_division_name'] = !isset($_SESSION['sale_division_name'])?'':$_SESSION['sale_division_name'];
		$_SESSION['sale_district_name'] = !isset($_SESSION['sale_district_name'])?'':$_SESSION['sale_district_name'];
		$_SESSION['sale_tehseel_name'] = !isset($_SESSION['sale_tehseel_name'])?'':$_SESSION['sale_tehseel_name'];
		$_SESSION['sale_block_name'] = !isset($_SESSION['sale_block_name'])?'':$_SESSION['sale_block_name'];*/
	}
}
else{
		$_SESSION['sale_division_name'] = !isset($_SESSION['sale_division_name'])?'':$_SESSION['sale_division_name'];
		$_SESSION['sale_district_name'] = !isset($_SESSION['sale_district_name'])?'':$_SESSION['sale_district_name'];
		$_SESSION['sale_tehseel_name'] = !isset($_SESSION['sale_tehseel_name'])?'':$_SESSION['sale_tehseel_name'];
		$_SESSION['sale_block_name'] = !isset($_SESSION['sale_block_name'])?'':$_SESSION['sale_block_name'];
		$_SESSION['sale_mobile_number'] = !isset($_SESSION['sale_mobile_number'])?'':$_SESSION['sale_mobile_number'];
		$_SESSION['sale_approval_status'] = !isset($_SESSION['sale_approval_status'])?'':$_SESSION['sale_approval_status'];
}
?>
   				<!--<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<?php echo $msg; ?>
							</div>
							<div class="card-body">
								<form action="<?php echo $_SERVER['PHP_SELF'];?>" novalidate method="post" autocomplete="off" enctype="multipart/form-data" id="user_form" name="user_form">
									<div class="row">
										<div class="col-sm-3 form-group">
											<label>मण्डल</label>
											<select name="division_name" id="division_name" tabindex="<?php echo $tab++; ?>"  class="form-control" onChange="fill_district(this.value);">
												<option value="">--Select--</option>
												<?php
												$sql = 'select * from master_division';
												$result_division = execute_query($sql);
												while($row_division = mysqli_fetch_assoc($result_division)){
													echo '<option value="'.$row_division['sno'].'" ';
													if($row_division['sno']==$_SESSION['sale_division_name']){
														echo 'selected="selected" ';
													}
													echo '>'.$row_division['division_name'].'</option>';
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
										<div class="col-sm-3 form-group">
											<label>Mobile Number</label>
											<input type="text" class="form-control" name="mobile_number" id="mobile_number">
										</div>
									</div>
									<input type="submit" class="btn btn-info btn-fill pull-right" name="submit" value="Submit" >
									
									<!--<button type="submit" class="btn btn-info btn-fill pull-right">Search</button>
									
									<div class="clearfix"></div>
								</form>
							</div>
						</div>
					</div>
				</div>-->				
				
				<div class="row">
                        <div class="col-md-12">
                           	<h3 style="color: #f00;">निम्नवत रिपोर्ट सांकेतिक है । समितियों कि संख्या में कुछ अंतर अपेक्षित है । </h3>
                            <div class="card strpied-tabled-with-hover">
                                <!--<div class="card-header ">
                                    <h4 class="card-title">Location</h4>
                                    <p class="card-category">Space for Pagination</p>
                                </div>-->
                                <div class="card-body table-full-width table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
											<tr>
                                            <th>S.No.</th>
                                            <th>District Name</th>
                                            <th>Total Societies</th>
											<th>समिति प्रांगण मे उपलब्ध गोदाम की संख्या </th>
											<th>अतिरिक्त गोदाम जो अच्छी स्थिति मे है </th>
											<th>अतिरिक्त गोदाम जो मरम्मत योग्य है</th>
											<th>अतिरिक्त गोदाम जो जरजर स्थिति मे है </th>
											<th>अतिरिक्त गोदाम उपलब्ध नहीं</th>
                                        	</tr>
                                        </thead>
                                        <tbody>
                                        	<?php
											$sql = 'SET SQL_BIG_SELECTS=1';
											execute_query($sql);
											$data = array();
											$t=0;
											$i=1;
											
											$sql = 'SELECT district_name, col2, count(*) c FROM survey_invoice
											left join test2 on test2.sno = survey_invoice.society_id
											left join master_district on master_district.sno = test2.col2
											where approval_status=4 and  (test2.status!=1 or test2.status is null)
											group by col2
											order by abs(col2)';
											$result_main_godown = execute_query($sql);
											while($row_main_godown = mysqli_fetch_assoc($result_main_godown)){
												$data[$row_main_godown['col2']]['district_name'] = $row_main_godown['district_name'];
												$data[$row_main_godown['col2']]['district_id'] = $row_main_godown['col2'];
												$data[$row_main_godown['col2']]['tot'] = $row_main_godown['c'];
											}
											
											
											$sql = 'SELECT district_name, col2, count(*) c FROM survey_invoice
											left join survey_invoice_sec_3_3 on `survey_invoice_sec_3_3`.survey_id = survey_invoice.sno
											left join test2 on test2.sno = survey_invoice.society_id
											left join master_district on master_district.sno = test2.col2
											where approval_status=4 and  (test2.status!=1 or test2.status is null) and type_of_construction in (3, 5)
											group by col2
											order by abs(col2)';
											$result_main_godown = execute_query($sql);
											while($row_main_godown = mysqli_fetch_assoc($result_main_godown)){
												$data[$row_main_godown['col2']]['main_godown'] = $row_main_godown['c'];
											}
											$sql = 'SELECT 
											district_name,
											col2,
											count(*) c,
											count(if(construction_status="good", 1, NULL)) as godown_good, 
											count(if(construction_status="repairable", 1, NULL)) as godown_repairable, 
											count(if(construction_status="discarded", 1, NULL)) as godown_discarded, 
											count(if(construction_status="", 1, NULL)) as godown_not_available 
											FROM `survey_invoice` 
											left join survey_invoice_sec_3_4 on survey_invoice_sec_3_4.survey_id = survey_invoice.sno 
											left join test2 on test2.sno = survey_invoice.society_id
											left join master_district on master_district.sno = test2.col2
											where approval_status=4 and  (test2.status!=1 or test2.status is null)
											group by col2
											order by district_name';	
											$result = execute_query($sql);
											$i=1;
											$tot=0;
											$tot_godown_good = 0;
											$tot_godown_repairable = 0;
											$tot_godown_discarded = 0;
											$tot_godown_not_available = 0;
											$tot_washroom_good = 0;
											$tot_washroom_repairable = 0;
											$tot_washroom_new = 0;
											
											
											while($row = mysqli_fetch_assoc($result)){
												$data[$row['col2']]['godown_good'] = $row['godown_good'];
												$data[$row['col2']]['godown_repairable'] = $row['godown_repairable'];
												$data[$row['col2']]['godown_discarded'] = $row['godown_discarded'];
												$data[$row['col2']]['godown_not_available'] = $row['godown_not_available'];
												/*echo '<tr>
												<td>'.$i++.'</td>
												<td>'.$row['district_name'].'</td>
												<td>'.$row['c'].'</td>
												<td>'.$row['godown_good'].'</td>
												<td>'.$row['godown_repairable'].'</td>
												<td>'.$row['godown_discarded'].'</td>
												<td>'.$row['godown_not_available'].'</td>
												</tr>';*/
												$tot += $row['c'];
												$tot_godown_good += $row['godown_good'];
												$tot_godown_repairable += $row['godown_repairable'];
												$tot_godown_discarded += $row['godown_discarded'];
												$tot_godown_not_available += $row['godown_not_available'];
												
											}
											
											foreach($data as $k=>$v){
												$t+=$v['tot'];
												echo '<tr>
												<td>'.$i++.'</td>
												<td>'.$v['district_name'].'</td>
												<td>'.$v['tot'].'</td>
												<td>'.$v['main_godown'].'</td>
												<td>'.$v['godown_good'].'</td>
												<td>'.$v['godown_repairable'].'</td>
												<td>'.$v['godown_discarded'].'</td>
												<td>'.$v['godown_not_available'].'</td>
												</tr>';
											}
											
											
											echo '<tr>
											<th colspan="2">Total:</th>
											<th>'.$t.'</th>
											<th></th>
											<th>'.$tot_godown_good.'</th>
											<th>'.$tot_godown_repairable.'</th>
											<th>'.$tot_godown_discarded.'</th>
											<th>'.$tot_godown_not_available.'</th>
											</tr>';
											
											?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
<?php
page_footer_start();
?>

    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="js/light-bootstrap-dashboard.js?v=1.4.0"></script>

<?php		
page_footer_end();
?>

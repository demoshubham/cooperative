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
   				<div class="row">
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
										<div class="col-sm-3 form-group">
											<label>Approval Status</label>
											<select class="form-control" name="approval_status" id="approval_status">
												<option value=""></option>
												<option value="0">Initiated</option>
												<option value="1">Pending for ADO Approval</option>
												<option value="2">Pending for ADCO Approval</option>
												<option value="3">Pending for AR Approval</option>
												<option value="4">All Approved</option>
											</select>
										</div>
										<div class="col-sm-3 form-group">
											<label>Building Status</label>
											<select class="form-control" name="building_status" id="building_status">
												<option value=""></option>
												<option value="0">All</option>
												<option value="1">Repairable</option>
												<option value="2">Good</option>
												<option value="3">Not-Repairable</option>
												<option value="4">Not-Available</option>
											</select>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
											<input type="submit" class="btn btn-info btn-fill pull-right" name="submit" value="Submit" >
										</div>	
										<div class="col-md-3">
											<a href="visit_report_export.php"><button type="button" name="student_ledger" class="btn btn-warning">Download In Excel</button></a>		
										</div>
										<div class="col-md-3">
											<a href="visit_report_export_ncd.php"><button type="button" name="student_ledger" class="btn btn-warning">Download In Excel (NCD)</button></a>
										</div>
									</div>
									
									
									
									<!--<button type="submit" class="btn btn-info btn-fill pull-right">Search</button>-->
									
									<div class="clearfix"></div>
								</form>
							</div>
						</div>
					</div>
				</div>				
				
				<div class="row">
                        <div class="col-md-12">
                            <div class="card strpied-tabled-with-hover">
                                <!--<div class="card-header ">
                                    <h4 class="card-title">Location</h4>
                                    <p class="card-category">Space for Pagination</p>
                                </div>-->
                                <div class="card-body table-full-width table-responsive">
									<table class="table table-hover table-striped">
										<thead>
											<?php
											//print_r($_SESSION);
											if(isset($_SESSION['sale_building_status'])){
												if($_SESSION['sale_building_status']!='0'){
													$sql = 'select survey_invoice.sno as sno, survey_invoice.mobile_number as mobile_number, survey_invoice.approval_status as approval_status, survey_invoice.creation_time as creation_time, col1, col2, col3, col4, col5, col6, otp_verify, latitude, longitude, mobile_number, `survey_invoice_sec_5`.`survey_id`, `survey_invoice_sec_5`.`building_status`, `survey_invoice_sec_5`.`building_status_remarks`, `survey_invoice_sec_5`.`floor_length`, `survey_invoice_sec_5`.`floor_width`, `survey_invoice_sec_5`.`floor_image`, `survey_invoice_sec_5`.`wall_length`, `survey_invoice_sec_5`.`wall_width`, `survey_invoice_sec_5`.`wall_image`, `survey_invoice_sec_5`.`paint_length`, `survey_invoice_sec_5`.`paint_width`, `survey_invoice_sec_5`.`paint_image`, `survey_invoice_sec_5`.`roof_length`, `survey_invoice_sec_5`.`roof_width`, `survey_invoice_sec_5`.`roof_image`, `survey_invoice_sec_5`.`washroom_floor`, `survey_invoice_sec_5`.`washroom_plaster`, `survey_invoice_sec_5`.`washroom_roof`, `survey_invoice_sec_5`.`washroom_seat`, `survey_invoice_sec_5`.`washroom_plumbing`, `survey_invoice_sec_5`.`doors`, `survey_invoice_sec_5`.`windows`, `survey_invoice_sec_5`.`plaster_wall`, `survey_invoice_sec_5`.`plaster_roof`, `survey_invoice_sec_5`.`others`, `survey_invoice_sec_5`.`status` from survey_invoice left join test2 on test2.sno = survey_invoice.society_id  left join survey_invoice_sec_5 on survey_invoice_sec_5.survey_id = survey_invoice.sno where col2 !="DivisionCodeText"';	
												}
											}
											else{
												$sql = 'select survey_invoice.sno as sno, survey_invoice.mobile_number as mobile_number, survey_invoice.approval_status as approval_status, survey_invoice.creation_time as creation_time, col1, col2, col3, col4, col5, col6, otp_verify, latitude, longitude, mobile_number from survey_invoice left join test2 on test2.sno = survey_invoice.society_id where col2 !="DivisionCodeText"  ';
											}
											
											$sql = 'select survey_invoice.sno as sno, survey_invoice.mobile_number as mobile_number, survey_invoice.approval_status as approval_status, survey_invoice.society_building_ownership, survey_invoice.society_registration_no, survey_invoice.society_registration_date, survey_invoice.email_id, survey_invoice.respondent_name, survey_invoice.respondent_designation, survey_invoice.active_members, survey_invoice.inactive_members, survey_invoice.creation_time as creation_time, col1, col2, col3, col4, col5, col6, otp_verify, latitude, longitude, mobile_number, `survey_invoice_sec_5`.`survey_id`, `survey_invoice_sec_5`.`building_status`, `survey_invoice_sec_5`.`building_status_remarks`, `survey_invoice_sec_5`.`floor_length`, `survey_invoice_sec_5`.`floor_width`, `survey_invoice_sec_5`.`floor_image`, `survey_invoice_sec_5`.`wall_length`, `survey_invoice_sec_5`.`wall_width`, `survey_invoice_sec_5`.`wall_image`, `survey_invoice_sec_5`.`paint_length`, `survey_invoice_sec_5`.`paint_width`, `survey_invoice_sec_5`.`paint_image`, `survey_invoice_sec_5`.`roof_length`, `survey_invoice_sec_5`.`roof_width`, `survey_invoice_sec_5`.`roof_image`, `survey_invoice_sec_5`.`washroom_floor`, `survey_invoice_sec_5`.`washroom_plaster`, `survey_invoice_sec_5`.`washroom_roof`, `survey_invoice_sec_5`.`washroom_seat`, `survey_invoice_sec_5`.`washroom_plumbing`, `survey_invoice_sec_5`.`doors`, `survey_invoice_sec_5`.`windows`, `survey_invoice_sec_5`.`plaster_wall`, `survey_invoice_sec_5`.`plaster_roof`, `survey_invoice_sec_5`.`others`, `survey_invoice_sec_5`.`status` from survey_invoice left join test2 on test2.sno = survey_invoice.society_id  left join survey_invoice_sec_5 on survey_invoice_sec_5.survey_id = survey_invoice.sno where col2 !="DivisionCodeText"';
											
											
											if(isset($_SESSION['sale_division_name'])){
												if($_SESSION['sale_building_status']=='1'){
													$sql .= ' and `building_status` ="repairable"';
												}
												if($_SESSION['sale_building_status']=='2'){
													$sql .= ' and `building_status` ="good"';
												}
												if($_SESSION['sale_building_status']=='3'){
													$sql .= ' and `building_status` ="discarded"';
												}
												if($_SESSION['sale_building_status']=='4'){
													$sql .= ' and `building_status` ="not_available"';
												}
												
												if($_SESSION['sale_district_name']!=''){
													$sql .= ' and `col2` ="'.$_SESSION['sale_district_name'].'"';
												}
												if($_SESSION['sale_block_name']!=''){
													$sql .= ' and `col6` ="'.$_SESSION['sale_block_name'].'"';
												}
												if($_SESSION['sale_division_name']!=''){
													$sql .= ' and `col1` ="'.$_SESSION['sale_division_name'].'"';
												}

												if($_SESSION['sale_tehseel_name']!=''){
													$sql .= ' and `col5` ="'.$_SESSION['sale_tehseel_name'].'"';
												}
												if($_SESSION['sale_mobile_number']!=''){
													$sql .= ' and `mobile_number` ="'.$_SESSION['sale_mobile_number'].'"';
												}
												if($_SESSION['sale_approval_status']!=''){
													if($_SESSION['sale_approval_status']=='0'){
														$sql .= ' and (`approval_status` ="0" or `approval_status` ="" or `approval_status` is null)';
													}
													else{
														$sql .= ' and `approval_status` ="'.$_SESSION['sale_approval_status'].'"';	
													}
													
												}
											}
											//echo $sql;
											$_SESSION['sql5']= $sql;
											$result_data = execute_query($sql);?>
											
											<tr>
											<th colspan="6">
											<?php
												include ('pagination/paginate.php'); //include of paginat page
												$total_results = mysqli_num_rows($result_data);
												$total_pages = ceil($total_results / $per_page);//total pages we going to have
												$tpages=$total_pages;
												if (isset($_GET['page'])) {
													$show_page = $_GET['page'];             //it will telles the current page
													if ($show_page > 0 && $show_page <= $total_pages) {
														$start = ($show_page - 1) * $per_page;
														$end = $start + $per_page;
													} else {
														// error - show first set of results
														$start = 0;              
														$end = $per_page;
													}
												} else {
													// if page isn't set, show first set of results
													$_GET['page'] = 1;
													$show_page = 1;
													$start = 0;
													$end = $per_page;
												}
												// display pagination
												$page = intval($_GET['page']);

												if ($page <= 0)
													$page = 1;


												$reload = $_SERVER['PHP_SELF'] . "?tpages=" . $tpages . (isset($_GET['details'])?'&details=1':'');
												echo '<div class="pagination"><ul>';
												if ($total_pages > 1) {
													echo paginate($reload, $show_page, $total_pages);
												}
												echo "</ul></div>";
											?>
											</th>
										</tr>
										
										</thead>
									</table>
                                    <table class="table table-hover table-striped">
                                        <thead>
											<tr>
                                            <th>S.No.</th>
                                            <th>Society Name</th>
											<th>District Name</th>
											<th>Incharges Name</th>
											<th>Location</th>
											<th>Filing Date</th>
											<th>OTP</th>
                                       		<th>Approval Status</th>
                                       		<th></th>
                                       		<th></th>
                                       		
												
                                        </tr></thead>
                                        <tbody>
                                           
											
											<?php
											for ($pgid = $start; $pgid < $end; $pgid++) {
											//print_r($row);
											if ($pgid == $total_results) {
												break;
											}
											mysqli_data_seek($result_data, $pgid);
											$row = mysqli_fetch_array($result_data);
											
											$i = $pgid+1;
											
											$sql_division = 'select * from master_division where sno = "'.$row['col1'].'"';
											$result_division = mysqli_fetch_array(execute_query($sql_division));
											
											$sql_district = 'select * from master_district where sno = "'.$row['col2'].'"';
											$result_district = mysqli_fetch_array(execute_query($sql_district));
											if(!isset($result_district['district_name'])){
												$result_district['district_name'] = '';
												$result_district['sno'] = '';
											}
												
											$sql_tehseel = 'select * from master_tehseel where sno = "'.$row['col5'].'"';
											$result_tehseel = mysqli_fetch_array(execute_query($sql_tehseel));
											if(!isset($result_tehseel['tehseel_name'])){
												$result_tehseel['tehseel_name'] = '';
												$result_tehseel['sno'] = '';
											}
												
											
											$sql_block = 'select * from master_block where sno = "'.$row['col6'].'"';
											$result_block = mysqli_fetch_array(execute_query($sql_block));
											if(!isset($result_block['block_name'])){
												$result_block['block_name'] = '';
												$result_block['sno'] = '';
											}
											
											$sql_ado = 'select ado.sno as sno, ado_name, mobile_number from ado_details left join ado on ado.sno = ado_details.ado_id where block_id="'.$result_block['sno'].'"';
											$result_ado = mysqli_fetch_array(execute_query($sql_ado));
											if(!isset($result_ado['ado_name'])){
												$result_ado['ado_name'] = '';
												$result_ado['mobile_number'] = '';
												$result_ado['sno'] = '';
											}
											
											$sql_adco = 'select adco.sno as sno, adco_name, mobile_number from adco_details left join adco on adco.sno = adco_details.adco_id where tehseel_id="'.$result_tehseel['sno'].'"';
											$result_adco = mysqli_fetch_array(execute_query($sql_adco));
											if(!isset($result_adco['adco_name'])){
												$result_adco['adco_name'] = '';
												$result_adco['mobile_number'] = '';
												$result_adco['sno'] = '';
											}
											
											$sql_ar = 'select ar.sno as sno, ar_name, mobile_number from ar_details left join ar on ar.sno = ar_details.ar_id where district_id="'.$result_district['sno'].'"';
											$result_ar = mysqli_fetch_array(execute_query($sql_ar));
											if(!isset($result_ar['ar_name'])){
												$result_ar['ar_name'] = '';
												$result_ar['mobile_number'] = '';
												$result_ar['sno'] = '';
											}
											
											?>
											<tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $row['col4'].'&nbsp;<small>('.$row['sno'].')</small><br>'.$row['mobile_number']; ?></td>
                                                <td>
                                                	<?php 
													echo $result_district['district_name'].'&nbsp;<small>('.$result_district['sno'].')</small><br/>';
													echo 'Div: '.$result_division['division_name'].'&nbsp;<small>('.$result_division['sno'].')</small><br/>';
													echo 'Teh: '.$result_tehseel['tehseel_name'].'&nbsp;<small>('.$result_tehseel['sno'].')</small><br/>';
													echo 'Block: '.$result_block['block_name'].'&nbsp;<small>('.$result_block['sno'].')</small><br/>';
												?></td>
												<td><?php 
												echo 'ADO: '.$result_ado['ado_name'].'&nbsp;<small>('.$result_ado['sno'].') ('.$result_ado['mobile_number'].')</small><br>'; 
												echo 'ADCO: '.$result_adco['adco_name'].'&nbsp;<small>('.$result_adco['sno'].') ('.$result_adco['mobile_number'].')</small><br/>'; 
												echo 'AR: '.$result_ar['ar_name'].'&nbsp;<small>('.$result_ar['sno'].') ('.$result_ar['mobile_number'].')</small>'; ?></td>
												<td><?php echo $row['latitude'].', '.$row['longitude']; ?></td>												
												<td><?php echo $row['creation_time']; ?></td>												
												<td><?php echo $row['otp_verify']; ?></td>												
												<td><?php 
												switch($row['approval_status']){
													case 1 :{
														echo '<p class="text-danger">Pending Approval ADO</p>';
														break;
													}	
													case 2:{
														echo '<p class="text-primary">Pending Approval ADCO</p>';
														break;
													}
													case 3:{
														echo '<p class="text-warning">Pending Approval AR</p>';
														break;
													}
													case 4:{
														echo '<p class="text-success">All Approval Done</p>';
														break;
													}
													default:{
														echo '<p class="text-secondary">Initiated</p>';
													}
													
												}?></td>
												<td><a href="preview.php?exdid=<?php echo $row['sno']; ?>" target="_blank">View</a></td>			
												<td><a href="estimate.php?exdid=<?php echo $row['sno']; ?>" target="_blank">View Estimate</a></td>			
											</tr>
											<?php
											}
											
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

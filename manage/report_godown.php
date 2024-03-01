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
		$_SESSION['gdn_'.$k] = $v;
		/*$_SESSION['gdn_division_name'] = !isset($_SESSION['gdn_division_name'])?'':$_SESSION['gdn_division_name'];
		$_SESSION['gdn_district_name'] = !isset($_SESSION['gdn_district_name'])?'':$_SESSION['gdn_district_name'];
		$_SESSION['gdn_tehseel_name'] = !isset($_SESSION['gdn_tehseel_name'])?'':$_SESSION['gdn_tehseel_name'];
		$_SESSION['gdn_block_name'] = !isset($_SESSION['gdn_block_name'])?'':$_SESSION['gdn_block_name'];*/
	}
}
else{
		$_SESSION['gdn_division_name'] = !isset($_SESSION['gdn_division_name'])?'':$_SESSION['gdn_division_name'];
		$_SESSION['gdn_district_name'] = !isset($_SESSION['gdn_district_name'])?'':$_SESSION['gdn_district_name'];
		$_SESSION['gdn_tehseel_name'] = !isset($_SESSION['gdn_tehseel_name'])?'':$_SESSION['gdn_tehseel_name'];
		$_SESSION['gdn_block_name'] = !isset($_SESSION['gdn_block_name'])?'':$_SESSION['gdn_block_name'];
		$_SESSION['gdn_mobile_number'] = !isset($_SESSION['gdn_mobile_number'])?'':$_SESSION['gdn_mobile_number'];
		$_SESSION['gdn_approval_status'] = !isset($_SESSION['gdn_approval_status'])?'':$_SESSION['gdn_approval_status'];
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
													if($row_division['sno']==$_SESSION['gdn_division_name']){
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
											<label>Financial Status</label>
											<select class="form-control" name="financial_status" id="financial_status">
												<option value="0">All</option>
												<option value="1">Profitable</option>
												<option value="2">Under Loss</option>
											</select>
										</div>
										<div class="col-sm-3 form-group">
											<label>Godown Capacity</label>
											<div class="row border m-2">
												<div class="col-md-5">
													<input id="godown_capacity_from" name="godown_capacity_from" class="form-control" type="text" value="<?php if(isset($_SESSION['gdn_godown_capacity_from'])){echo $_SESSION['gdn_godown_capacity_from'];}?>">
												</div>
												<div class="col-md-1">
													<label>AND</label>
												</div>
												<div class="col-md-6">
													<input id="godown_capacity_to" name="godown_capacity_to" class="form-control" type="text" value="<?php if(isset($_SESSION['gdn_godown_capacity_to'])){echo $_SESSION['gdn_godown_capacity_to'];}?>">
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
											<input type="submit" class="btn btn-info btn-fill pull-right" name="submit" value="Submit" >
										</div>	
										<div class="col-md-3">
											<a href="report_godown_excel.php"><button type="button" name="student_ledger" class="btn btn-warning">Download In Excel</button></a>		
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
											$sql = 'select survey_invoice.sno as sno, survey_invoice.mobile_number as mobile_number, survey_invoice.approval_status as approval_status, survey_invoice.society_building_ownership, survey_invoice.society_registration_no, survey_invoice.society_registration_date, survey_invoice.email_id, survey_invoice.respondent_name, survey_invoice.respondent_designation, survey_invoice.active_members, survey_invoice.inactive_members, survey_invoice.creation_time as creation_time, col1, col2, col3, col4, col5, col6, otp_verify, latitude, longitude, mobile_number, type_of_construction, length, width, "" as storage_capacity from survey_invoice 
											left join test2 on test2.sno = survey_invoice.society_id  
											left join survey_invoice_sec_3_3 on survey_invoice_sec_3_3.survey_id = survey_invoice.sno 
											where col2 !="DivisionCodeText" and type_of_construction in (3, 5) and approval_status=4';
											
											
											$sql2 = 'select survey_invoice.sno as sno, survey_invoice.mobile_number as mobile_number, survey_invoice.approval_status as approval_status, survey_invoice.society_building_ownership, survey_invoice.society_registration_no, survey_invoice.society_registration_date, survey_invoice.email_id, survey_invoice.respondent_name, survey_invoice.respondent_designation, survey_invoice.active_members, survey_invoice.inactive_members, survey_invoice.creation_time as creation_time, col1, col2, col3, col4, col5, col6, otp_verify, latitude, longitude, mobile_number, "3" as type_of_construction, length, width, storage_capacity from survey_invoice 
											left join test2 on test2.sno = survey_invoice.society_id  
											left join survey_invoice_sec_3_4 on survey_invoice_sec_3_4.survey_id = survey_invoice.sno 
											where col2 !="DivisionCodeText" and approval_status=4';
											
											$sql = 'select survey_invoice.sno as sno, survey_invoice.mobile_number as mobile_number, survey_invoice.approval_status as approval_status, survey_invoice.society_building_ownership, survey_invoice.society_registration_no, survey_invoice.society_registration_date, survey_invoice.email_id, survey_invoice.respondent_name, survey_invoice.respondent_designation, survey_invoice.active_members, survey_invoice.inactive_members, survey_invoice.creation_time as creation_time, col1, col2, col3, col4, col5, col6, otp_verify, latitude, longitude, mobile_number 
											from survey_invoice 
											left join test2 on test2.sno = survey_invoice.society_id  
											where col2 !="DivisionCodeText" and approval_status=4';
											
											$sql = 'SELECT survey_invoice.sno as sno, respondent_name, email_id, col1, col2, col3, col4, col5, col6, last_year_profit_loss, mobile_number, type_of_fund, survey_invoice_sec_3_4.construction_status as construction_status, storage_capacity, length, width, last_year_pl_amount, seq_year_profit_loss, seq_year_pl_amount 
											FROM `survey_invoice_sec_3_4` 
											left join survey_invoice on survey_invoice.sno = survey_id  
											left join test2 on test2.sno = society_id 
											left join survey_invoice_sec_2_1 on survey_invoice_sec_2_1.survey_id = survey_invoice.sno  
											where 1=1';
											
											if(isset($_SESSION['gdn_division_name'])){
												if($_SESSION['gdn_district_name']!=''){
													$sql .= ' and `col2` ="'.$_SESSION['gdn_district_name'].'"';
												}
												if($_SESSION['gdn_block_name']!=''){
													$sql .= ' and `col6` ="'.$_SESSION['gdn_block_name'].'"';
												}
												if($_SESSION['gdn_division_name']!=''){
													$sql .= ' and `col1` ="'.$_SESSION['gdn_division_name'].'"';
												}

												if($_SESSION['gdn_tehseel_name']!=''){
													$sql .= ' and `col5` ="'.$_SESSION['gdn_tehseel_name'].'"';
												}
												if($_SESSION['gdn_mobile_number']!=''){
													$sql .= ' and `mobile_number` ="'.$_SESSION['gdn_mobile_number'].'"';
												}
												if($_SESSION['gdn_godown_capacity_from']!=''){
													$sql .= ' and abs(storage_capacity) between '.$_SESSION['gdn_godown_capacity_from'].' AND '.$_SESSION['gdn_godown_capacity_to'];
												}
												if($_SESSION['gdn_financial_status']!='0'){
													//$sql .= ' and last_year_profit_loss = '.$_SESSION['gdn_godown_capacity_from'].' AND '.$_SESSION['gdn_godown_capacity_from'];
												}
											}
											
											$sql .= '  order by col1, col2, seq_year_profit_loss desc';
											//$sql = '('.$sql.') union all ('.$sql2.')';
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
                                            <th>Division Name</th>
											<th>District Name</th>
											<th>Tehseel Name</th>
											<th>Block Name</th>
											<th>Godown Length</th>
											<th>Godown Width</th>
											<th>Storage Capacity</th>
											<th>Fund Name</th>
											<th>Construction Status</th>
											<th>Past Year Profit Status</th>
											<th>Past Year Profit Amount</th>
											<th>Sequential Year Profit/Loss</th>
											<th>Sequential Year Profit/Loss Amount</th>
                                       		<th></th>
                                       		<th></th>
                                        	</tr>
                                        </thead>
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
											if($row['construction_status']=='good' || $row['construction_status']==''){
												$row['construction_status'] = 'Good';
											}
												$sql = 'select * from master_type_of_fund where sno="'.$row['type_of_fund'].'"';
												//echo $sql.'<br>';
												$type_of_fund = execute_query($sql);
												if(mysqli_num_rows($type_of_fund)!=0){
													$type_of_fund = mysqli_fetch_assoc($type_of_fund);
													$row['type_of_fund'] = $type_of_fund['type_of_fund'];
												}
												else{
													//unset($type_of_fund);
													$row['type_of_fund'] = '';
												}
											
											?>
											<tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $row['col4'].'&nbsp;<small>('.$row['sno'].')</small><br>'.$row['mobile_number']; ?></td>
                                                <td><?php echo $result_division['division_name']; ?></td>
                                                <td><?php echo $result_district['district_name']; ?></td>
                                                <td><?php echo $result_tehseel['tehseel_name']; ?></td>
                                                <td><?php echo $result_block['block_name']; ?></td>
												<td><?php echo $row['length']; ?></td>
												<td><?php echo $row['width']; ?></td>
												<td><?php echo $row['storage_capacity']; ?></td>
												<td><?php echo $row['type_of_fund']; ?></td>
												<td><?php echo $row['construction_status']; ?></td>
												<td><?php echo $row['last_year_profit_loss']; ?></td>
                                                <td><?php echo $row['last_year_pl_amount']; ?></td>
												<td><?php echo $row['seq_year_profit_loss']; ?></td>
												<td><?php echo $row['seq_year_pl_amount']; ?></td>
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

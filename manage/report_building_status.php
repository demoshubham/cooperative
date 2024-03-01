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
											<input type="text" class="form-control" name="mobile_number" id="mobile_number" value="<?php echo $_SESSION['sale_mobile_number']; ?>">
										</div>
										<div class="col-sm-3 form-group">
											<label>Building Status</label>
											<select name="building_status" id="building_status" class="form-control">
												<option></option>
												<option value="discarded" <?php echo $_SESSION['sale_building_status']=='discarded'?'selected':''; ?>>Discarded</option>
												<option value="good" <?php echo $_SESSION['sale_building_status']=='good'?'selected':''; ?>>Good</option>
												<option value="not_available" <?php echo $_SESSION['sale_building_status']=='not_available'?'selected':''; ?>>Not Available</option>
												<option value="repairable" <?php echo $_SESSION['sale_building_status']=='repairable'?'selected':''; ?>>Repairable</option>
											</select>
										</div>
										<div class="col-sm-3 form-group">
											<label>Last Year Profit/Loss</label>
											<select class="form-control" name="last_year_pl" id="last_year_pl">
												<option value=""></option>
												<option value="PROFIT" <?php echo $_SESSION['sale_last_year_pl']=='PROFIT'?'selected':''; ?>>Profit</option>
												<option value="LOSS" <?php echo $_SESSION['sale_last_year_pl']=='LOSS'?'selected':''; ?>>Loss</option>
											</select>
										</div>
										<div class="col-sm-3 form-group">
											<label>Seq Year Profit/Loss</label>
											<select class="form-control" name="seq_year_pl" id="seq_year_pl">
												<option value=""></option>
												<option value="PROFIT" <?php echo $_SESSION['sale_seq_year_pl']=='PROFIT'?'selected':''; ?>>Profit</option>
												<option value="LOSS" <?php echo $_SESSION['sale_seq_year_pl']=='LOSS'?'selected':''; ?>>Loss</option>
											</select>
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
                           	<!--<h3 style="color: #f00;">समितियां जिसमें मुख्य भवन कि स्थिति अच्छी है । </h3>-->
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
                                            <th>Division Name</th>
                                            <th>District Name</th>
                                            <th>Society Name</th>
                                            <th>Respondent Name</th>
                                            <th>Mobile Number</th>
											<th>Building Status</th>
											<th>Washroom Status</th>
											<th>Washroom Details</th>
											<th>Electricity Status</th>
											<th>Electricity Details</th>
											<th>Internet</th>
                                       		<th>Profit/Loss</th>
											<th>Profit/Loss Amount</th>
											<th>Seq. Profit/Loss</th>
											<th>Seq. Profit/Loss Amount</th>
                                        	</tr>
                                        </thead>
                                        <tbody>
                                        	<?php
											$sql = 'SET SQL_BIG_SELECTS=1';
											execute_query($sql);
											$sql = 'SELECT test2.sno as sno, col1, division_name, col2, district_name, col3, col4, col5, col6, col7, mobile_number, respondent_name, building_status, washroom_floor, washroom_plaster, washroom_roof, washroom_seat, washroom_plumbing, electric_connection, electric_connection_working, electric_connection_proposal, internet_connectivity, internet_service_provider, last_year_profit_loss, last_year_pl_amount, seq_year_profit_loss, seq_year_pl_amount
											FROM `survey_invoice_sec_2_1` 
											left join survey_invoice on survey_invoice.sno = survey_invoice_sec_2_1.survey_id 
											left join survey_invoice_sec_5 on survey_invoice.sno = survey_invoice_sec_5.survey_id 
											left join test2 on test2.sno = society_id 
											left join master_division on master_division.sno = col1 
											left join master_district on master_district.sno = col2 
											where approval_status=4 and  (test2.status!=1 or test2.status is null)';
											
											if(isset($_SESSION['sale_building_status'])){
												if($_SESSION['sale_building_status']!=''){
													$sql .= ' and building_status="'.$_SESSION['sale_building_status'].'"';
												}
												if($_SESSION['sale_last_year_pl']!=''){
													$sql .= ' and last_year_profit_loss="'.strtolower($_SESSION['sale_last_year_pl']).'"';
												}
												if($_SESSION['sale_seq_year_pl']!=''){
													$sql .= ' and seq_year_profit_loss="'.strtolower($_SESSION['sale_seq_year_pl']).'"';
												}
												
											}
											
											$sql .= ' order by col1, col2';	
											echo $sql;
											$result = execute_query($sql);
											echo '<br><br>'.mysqli_error($db);
											$i=1;
											$tot=0;
											
											while($row = mysqli_fetch_assoc($result)){
												$sql = 'select count(*) c from test2 where (test2.status!=1 or test2.status is null) and col2="'.$row['col2'].'"';
												$count = mysqli_fetch_assoc(execute_query($sql));
												echo '<tr>
												<td>'.$i++.'</td>
												<td>'.$row['division_name'].'</td>
												<td>'.$row['district_name'].'</td>
												<td>'.$row['col4'].'</td>
												<td>'.$row['respondent_name'].'</td>
												<td>'.$row['mobile_number'].'</td>
												<td>'.$row['building_status'].'</td>';
												if($row['building_status']=='repairable'){
													$washroom_stat = '';
													if($row['washroom_floor']!='' && $row['washroom_floor']!='good'){
														$washroom_stat .= 'Floor Repair: '.$row['washroom_floor'].'<br/>';
													}
													if($row['washroom_plaster']!='' && $row['washroom_plaster']!='good'){
														$washroom_stat .= 'Plaster Repair: '.$row['washroom_plaster'].'<br/>';
													}
													if($row['washroom_roof']!='' && $row['washroom_roof']!='good'){
														$washroom_stat .= 'Roof Repair: '.$row['washroom_roof'].'<br/>';
													}
													if($row['washroom_seat']!='' && $row['washroom_seat']!='good'){
														$washroom_stat .= 'Seat Repair: '.$row['washroom_seat'].'<br/>';
													}
													if($row['washroom_plumbing']!='' && $row['washroom_plumbing']!='good'){
														$washroom_stat .= 'Plumbing Repair: '.$row['washroom_plumbing'].'<br/>';
													}
													if($washroom_stat!=''){
														echo '<td>Repairable</td>
														<td>'.$washroom_stat.'</td>';
													}
													else{
														echo '<td>Good</td><td></td>';
													}
												}
												else{
													echo '<td></td><td></td>';
												}
												if($row['electric_connection']=='yes'){
													if($row['electric_connection_working']=='yes'){
														echo '<td>Working</td><td></td>';
													}
													else{
														echo '<td>Not Working</td>
														<td>Reason: '.$row['electric_connection_working'].'</td>';
													}
												}
												else{
													echo '<td>Not Available</td>
													<td>Proposal: '.$row['electric_connection_proposal'].'</td>';
												}
												echo '
												<td>'.$row['internet_connectivity'].'</td>';
												echo '<td>'.strtoupper($row['last_year_profit_loss']).'</td>
												<td>Rs.'.$row['last_year_pl_amount'].'</td>';
												echo '<td>'.strtoupper($row['seq_year_profit_loss']).'</td>
												<td>Rs.'.$row['seq_year_pl_amount'].'</td>
												</tr>';
												

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

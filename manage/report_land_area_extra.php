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
											<label>Land Area</label>
											<input type="text" class="form-control" name="land_area" id="land_area" value="<?php echo $_SESSION['sale_land_area']; ?>">
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
                           	<h3 style="color: #f00;">समितियां जिसमें अतिरिक्त खाली पड़ी भूमि है । </h3>
                            <div class="card strpied-tabled-with-hover">
                                <!--<div class="card-header ">
                                    <h4 class="card-title">Location</h4>
                                    <p class="card-category">Space for Pagination</p>
                                </div>-->
                                <div class="card-body table-full-width table-responsive">
                                   
                                   <table class="table table-hover table-striped">
									<thead>
										<tr>
										 <th>
										 <?php
											$sql = 'SET SQL_BIG_SELECTS=1';
											execute_query($sql);
											$sql = 'SELECT test2.sno as sno, survey_invoice_sec_3_5.survey_id as survey_id, col1, division_name, col2, district_name, col3, col4, col5, col6, col7, mobile_number, respondent_name, abs(total_area) as total_area, 
											last_year_profit_loss, last_year_pl_amount, seq_year_profit_loss, seq_year_pl_amount
											FROM `survey_invoice_sec_3_5` 
											left join survey_invoice_sec_2_1 on survey_invoice_sec_2_1.survey_id = survey_invoice_sec_3_5.survey_id 
											left join survey_invoice on survey_invoice.sno = survey_invoice_sec_3_5.survey_id 
											left join test2 on test2.sno = society_id 
											left join master_division on master_division.sno = col1 
											left join master_district on master_district.sno = col2 
											where approval_status=4 and  (test2.status!=1 or test2.status is null) and land_type!="" and total_area!=""';
											if(isset($_SESSION['sale_land_area'])){
												if($_SESSION['sale_land_area']!=''){
													$sql .= ' and abs(total_area)>="'.$_SESSION['sale_land_area'].'"';
												}
												if($_SESSION['sale_last_year_pl']!=''){
													$sql .= ' and last_year_profit_loss="'.strtolower($_SESSION['sale_last_year_pl']).'"';
												}
												if($_SESSION['sale_seq_year_pl']!=''){
													$sql .= ' and seq_year_profit_loss="'.strtolower($_SESSION['sale_seq_year_pl']).'"';
												}
												
											}
											
											$sql .= ' order by col1, col2, abs(total_area)';	
											//echo $sql;
											$result = execute_query($sql);
											$i=1;
											$tot=0;
											include ('pagination/paginate.php'); //include of paginat page
											$total_results = mysqli_num_rows($result);
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
											$tot=0;
											
											for ($pgid = $start; $pgid < $end; $pgid++) {
												//print_r($row);
												if ($pgid == $total_results) {
													break;
												}
												$i = $pgid+1;
												mysqli_data_seek($result, $pgid);
												$row = mysqli_fetch_array($result);
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

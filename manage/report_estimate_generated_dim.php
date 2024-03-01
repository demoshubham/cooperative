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
		$_SESSION['estm_'.$k] = $v;
		/*$_SESSION['estm_division_name'] = !isset($_SESSION['estm_division_name'])?'':$_SESSION['estm_division_name'];
		$_SESSION['estm_district_name'] = !isset($_SESSION['estm_district_name'])?'':$_SESSION['estm_district_name'];
		$_SESSION['estm_tehseel_name'] = !isset($_SESSION['estm_tehseel_name'])?'':$_SESSION['estm_tehseel_name'];
		$_SESSION['estm_block_name'] = !isset($_SESSION['estm_block_name'])?'':$_SESSION['estm_block_name'];*/
	}
}
else{
		$_SESSION['estm_division_name'] = !isset($_SESSION['estm_division_name'])?'':$_SESSION['estm_division_name'];
		$_SESSION['estm_district_name'] = !isset($_SESSION['estm_district_name'])?'':$_SESSION['estm_district_name'];
		$_SESSION['estm_tehseel_name'] = !isset($_SESSION['estm_tehseel_name'])?'':$_SESSION['estm_tehseel_name'];
		$_SESSION['estm_block_name'] = !isset($_SESSION['estm_block_name'])?'':$_SESSION['estm_block_name'];
		$_SESSION['estm_mobile_number'] = !isset($_SESSION['estm_mobile_number'])?'':$_SESSION['estm_mobile_number'];
		$_SESSION['estm_estimate_amount_to'] = !isset($_SESSION['estm_estimate_amount_to'])?'':$_SESSION['estm_estimate_amount_to'];
		$_SESSION['estm_approval_status'] = !isset($_SESSION['estm_approval_status'])?'':$_SESSION['estm_approval_status'];
		$_SESSION['estm_wall_area'] = !isset($_SESSION['estm_wall_area'])?'':$_SESSION['estm_wall_area'];
		$_SESSION['estm_paint_area'] = !isset($_SESSION['estm_paint_area'])?'':$_SESSION['estm_paint_area'];
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
													if($row_division['sno']==$_SESSION['estm_division_name']){
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
											<label>Estimate Value Between</label>
											<div class="row border m-2">
												<div class="col-md-5">
													<input id="estimate_amount_from" name="estimate_amount_from" class="form-control" type="text" value="<?php if(isset($_SESSION['estm_estimate_amount_from'])){echo $_SESSION['estm_estimate_amount_from'];}?>">
												</div>
												<div class="col-md-1">
													<label>AND</label>
												</div>
												<div class="col-md-6">
													<input id="estimate_amount_to" name="estimate_amount_to" class="form-control" type="text" value="<?php if(isset($_SESSION['estm_estimate_amount_to'])){echo $_SESSION['estm_estimate_amount_to'];}?>">
												</div>
											</div>
										</div>
										<div class="col-sm-3 form-group">
											<label>Wall Area</label>
											<div class="row border m-2">
												<div class="col-md-6">
													<select name="wall_symbol" id="wall_symbol" class="form-control">
														<option value="=" <?php if(isset($_SESSION['estm_wall_symbol'])){if($_SESSION['estm_wall_symbol']=='='){echo 'selected';}}?>>=</option>
														<option value=">=" <?php if(isset($_SESSION['estm_wall_symbol'])){if($_SESSION['estm_wall_symbol']=='>='){echo 'selected';}}?>>>=</option>
														<option value="<=" <?php if(isset($_SESSION['estm_wall_symbol'])){if($_SESSION['estm_wall_symbol']=='<='){echo 'selected';}}?>><=</option>
													</select>
												</div>
												<div class="col-md-6">
													<input id="wall_area" name="wall_area" class="form-control" type="text" value="<?php if(isset($_SESSION['estm_wall_area'])){echo $_SESSION['estm_wall_area'];}?>">
												</div>
											</div>
										</div>
										<div class="col-sm-3 form-group">
											<label>Paint Area</label>
											<div class="row border m-2">
												<div class="col-md-6">
													<select name="paint_symbol" id="estimate_symbol" class="form-control">
														<option value="=" <?php if(isset($_SESSION['estm_paint_symbol'])){if($_SESSION['estm_paint_symbol']=='='){echo 'selected';}}?>>=</option>
														<option value=">=" <?php if(isset($_SESSION['estm_paint_symbol'])){if($_SESSION['estm_paint_symbol']=='>='){echo 'selected';}}?>>>=</option>
														<option value="<=" <?php if(isset($_SESSION['estm_paint_symbol'])){if($_SESSION['estm_paint_symbol']=='<='){echo 'selected';}}?>><=</option>
													</select>
												</div>
												<div class="col-md-6">
													<input id="paint_area" name="paint_area" class="form-control" type="text" value="<?php if(isset($_SESSION['estm_paint_amount'])){echo $_SESSION['estm_paint_amount'];}?>">
												</div>
											</div>
										</div>
									</div>
									<input type="submit" class="btn btn-info btn-fill pull-right" name="submit" value="Submit" >
									
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
											
											$sql = 'select survey_invoice.sno as sno, survey_invoice.mobile_number as mobile_number, survey_invoice.approval_status as approval_status, survey_invoice.creation_time as creation_time, col1, col2, col3, col4, col5, col6, otp_verify, latitude, longitude, mobile_number, `survey_invoice_sec_5`.`survey_id`, `survey_invoice_sec_5`.`building_status`, `survey_invoice_sec_5`.`building_status_remarks`, `survey_invoice_sec_5`.`floor_length`, `survey_invoice_sec_5`.`floor_width`, `survey_invoice_sec_5`.`floor_image`, `survey_invoice_sec_5`.`wall_length`, `survey_invoice_sec_5`.`wall_width`, `survey_invoice_sec_5`.`wall_image`, `survey_invoice_sec_5`.`paint_length`, `survey_invoice_sec_5`.`paint_width`, `survey_invoice_sec_5`.`paint_image`, `survey_invoice_sec_5`.`roof_length`, `survey_invoice_sec_5`.`roof_width`, `survey_invoice_sec_5`.`roof_image`, `survey_invoice_sec_5`.`washroom_floor`, `survey_invoice_sec_5`.`washroom_plaster`, `survey_invoice_sec_5`.`washroom_roof`, `survey_invoice_sec_5`.`washroom_seat`, `survey_invoice_sec_5`.`washroom_plumbing`, `survey_invoice_sec_5`.`doors`, `survey_invoice_sec_5`.`windows`, `survey_invoice_sec_5`.`plaster_wall`, `survey_invoice_sec_5`.`plaster_roof`, `survey_invoice_sec_5`.`others`, `survey_invoice_sec_5`.`status` from survey_estimate left join survey_invoice on survey_invoice.sno = survey_estimate.survey_id left join test2 on test2.sno = survey_invoice.society_id  left join survey_invoice_sec_5 on survey_invoice_sec_5.survey_id = survey_invoice.sno where col2 !="DivisionCodeText" and `building_status` ="repairable" and `approval_status` ="4"';
											
											
											if(isset($_SESSION['estm_division_name'])){
												if($_SESSION['estm_district_name']!=''){
													$sql .= ' and `col2` ="'.$_SESSION['estm_district_name'].'"';
												}
												if($_SESSION['estm_block_name']!=''){
													$sql .= ' and `col6` ="'.$_SESSION['estm_block_name'].'"';
												}
												if($_SESSION['estm_division_name']!=''){
													$sql .= ' and `col1` ="'.$_SESSION['estm_division_name'].'"';
												}

												if($_SESSION['estm_tehseel_name']!=''){
													$sql .= ' and `col5` ="'.$_SESSION['estm_tehseel_name'].'"';
												}
												if($_SESSION['estm_mobile_number']!=''){
													$sql .= ' and `mobile_number` ="'.$_SESSION['estm_mobile_number'].'"';
												}
												if($_SESSION['estm_estimate_amount_to']!=''){
													$sql .= ' and grand_total_a_b between '.$_SESSION['estm_estimate_amount_from'].' AND '.$_SESSION['estm_estimate_amount_to'];
												}
												if($_SESSION['estm_wall_area']!=''){
													$sql .= ' and `wall_area` '.$_SESSION['estm_wall_symbol'].$_SESSION['estm_wall_area'];
												}
												if($_SESSION['estm_paint_area']!=''){
													$sql .= ' and `paint_area` ="'.$_SESSION['estm_paint_area'].'"';
												}
											}
											
											$sql .= ' order by col1, col2';
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
											<tr  class="no-print">
												<td colspan="14" float="left" class="no-print">
												<a href="report_estimate_generated_dim_excel.php"><input type="button" style="margin-top:20px; color:#ffffff;" name="student_ledger" class="form-control btn btn-danger"  style="float: left;" value="Download In Excel"></a></span>
												</td>
											</tr>
                                            <tr>
                                            <th>S.No.</th>
                                            <th>Society Name</th>
											<th>District Name</th>
											<th>फर्श (Sq.Mt.)</th>
											<th>पुताई (Sq.Mt.)</th>
											<th>छत (Sq.Mt.)</th>
                                       		<th>प्लास्टर दिवार (Sq.Mt.)</th>
                                       		<th>प्लास्टर छत (Sq.Mt.)</th>
                                       		<th>शौचालय (Rs.)</th>
                                       		<th>Estimate (Rs.)</th>
                                       		<th></th>
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
											$estimate = estimate($row['sno']);
												
											$row['floor_length'] = floatval($row['floor_length']);
											$row['floor_width'] = floatval($row['floor_width']);
											$row['wall_length'] = floatval($row['wall_length']);
											$row['wall_width'] = floatval($row['wall_width']);
											$row['paint_length'] = floatval($row['paint_length']);
											$row['paint_width'] = floatval($row['paint_width']);
											$row['roof_length'] = floatval($row['roof_length']);
											$row['roof_width'] = floatval($row['roof_width']);
											$row['washroom_floor'] = floatval($row['washroom_floor']);
											$row['washroom_plaster'] = floatval($row['washroom_plaster']);
											$row['washroom_roof'] = floatval($row['washroom_roof']);
											$row['washroom_seat'] = floatval($row['washroom_seat']);
											$row['washroom_plumbing'] = floatval($row['washroom_plumbing']);
											$row['plaster_wall'] = floatval($row['plaster_wall']);
											$row['plaster_roof'] = floatval($row['plaster_roof']);
												
											
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
												<td><?php echo round($estimate['floor_area'],2); ?></td>
												<td><?php echo round($estimate['paint_area'],2); ?></td>
												<td><?php echo round($estimate['roof_area'],2); ?></td>
												<td><?php echo round($estimate['plaster_wall_area'],2); ?></td>
												<td><?php echo round($estimate['plaster_roof_area'],2); ?></td>
												<td><?php echo ($row['washroom_floor']+$row['washroom_plaster']+$row['washroom_roof']+$row['washroom_seat']+$row['washroom_plumbing']); ?></td>
												<td><?php echo $estimate['grand_total_a_b']; ?></td>
												<td><a href="preview.php?exdid=<?php echo $row['sno']; ?>" target="_blank">View</a></td>
												<td><a href="preview2.php?exdid=<?php echo $row['sno']; ?>" target="_blank">View 2</a></td>
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

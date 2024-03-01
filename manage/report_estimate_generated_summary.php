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
                                <?php
								
								if(isset($_POST['submit'])){
								?>
                                <div class="card-body table-full-width table-responsive">
									<?php
									//print_r($_SESSION);

									$sql = 'select count(*) c, col1, col2, district_name, division_name  from survey_estimate left join survey_invoice on survey_invoice.sno = survey_estimate.survey_id left join test2 on test2.sno = survey_invoice.society_id left join master_division on master_division.sno = col1 left join master_district on master_district.sno = col2 left join survey_invoice_sec_5 on survey_invoice_sec_5.survey_id = survey_invoice.sno where col2 !="DivisionCodeText" and `building_status` ="repairable" and `approval_status` ="4" group by col2 order by division_name, district_name';
											
											
									/*if(isset($_SESSION['estm_division_name'])){
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
									}*/
									//echo $sql;
									$_SESSION['sql5']= $sql;
									$result_data = execute_query($sql);?>
                                    <table class="table table-hover table-striped">
                                        <thead>
											<tr  class="no-print">
												<td colspan="11" float="left" class="no-print">
												<a href="report_estimate_generated_summary_excel.php"><input type="button" style="margin-top:20px; color:#ffffff;" name="student_ledger" class="form-control btn btn-danger"  style="float: left;" value="Download In Excel"></a></span>
												</td>
											</tr>
                                            <tr>
												<th rowspan="2">S.No.</th>
												<th rowspan="2">Division Name</th>
												<th rowspan="2">District Name</th>
												<th rowspan="2">Repairable</th>
												<th colspan="2">Upto 5 Lakhs</th>
												<th colspan="2">5 to 10 Lakhs</th>
												<th colspan="2">10 to 15 Lakhs</th>
												<th colspan="2">15 to 20 Lakhs</th>
												<th colspan="2">Above 20 Lakhs</th>
											</tr>
                                        	<tr>
                                        		<th>Count</th>
                                        		<th>Amount</th>
                                        		<th>Count</th>
                                        		<th>Amount</th>
                                        		<th>Count</th>
                                        		<th>Amount</th>
                                        		<th>Count</th>
                                        		<th>Amount</th>
                                        		<th>Count</th>
                                        		<th>Amount</th>
                                        	</tr>

                                        </thead>
                                        <tbody>
                                           
											
										<?php
										$i=1;
										$col1 = '';
										$total_count = 0;
										$total_count_5 = 0;
										$total_amt_5 = 0;
										$total_count_10 = 0;
										$total_amt_10 = 0;
										$total_count_15 = 0;
										$total_amt_15 = 0;
										$total_count_20 = 0;
										$total_amt_20 = 0;
										$total_count_above = 0;
										$total_amt_above = 0;
										$tot_repairable = 0;
										$gtotal_count = 0;
										$gtotal_count_5 = 0;
										$gtotal_amt_5 = 0;
										$gtotal_count_10 = 0;
										$gtotal_amt_10 = 0;
										$gtotal_count_15 = 0;
										$gtotal_amt_15 = 0;
										$gtotal_count_20 = 0;
										$gtotal_amt_20 = 0;
										$gtotal_count_above = 0;
										$gtotal_amt_above = 0;
										$gtot_repairable = 0;
										while($row = mysqli_fetch_array($result_data)){
											/*$sql = 'select count(*) c, col1, col2 from survey_invoice left join test2 on test2.sno = survey_invoice.society_id where `approval_status` ="4" and col2="'.$row['col2'].'"';
											//echo $sql.'<br>';
											$tot_count = mysqli_fetch_assoc(execute_query($sql));*/
											
											$sql = 'select count(*) c, sum(grand_total_a_b) as total from survey_estimate left join survey_invoice on survey_invoice.sno = survey_id left join test2 on test2.sno = society_id where col2="'.$row['col2'].'" and grand_total_a_b between 1 AND 500000';
											//echo $sql.'<br>';
											$upto_5_lakhs = mysqli_fetch_assoc(execute_query($sql));
											if($col1 == ''){
												$col1 = $row['col1'];
											}
											
											$sql = 'select count(*) c, sum(grand_total_a_b) as total from survey_estimate left join survey_invoice on survey_invoice.sno = survey_id left join test2 on test2.sno = society_id where col2="'.$row['col2'].'" and grand_total_a_b between 500001 AND 1000000';
											//echo $sql.'<br>';
											$upto_10_lakhs = mysqli_fetch_assoc(execute_query($sql));
											if($col1 == ''){
												$col1 = $row['col1'];
											}
											
											$sql = 'select count(*) c, sum(grand_total_a_b) as total from survey_estimate left join survey_invoice on survey_invoice.sno = survey_id left join test2 on test2.sno = society_id where col2="'.$row['col2'].'" and grand_total_a_b between 1000001 AND 1500000';
											//echo $sql.'<br>';
											$upto_15_lakhs = mysqli_fetch_assoc(execute_query($sql));
											if($col1 == ''){
												$col1 = $row['col1'];
											}
											
											$sql = 'select count(*) c, sum(grand_total_a_b) as total from survey_estimate left join survey_invoice on survey_invoice.sno = survey_id left join test2 on test2.sno = society_id where col2="'.$row['col2'].'" and grand_total_a_b between 1500001 AND 2000000';
											//echo $sql.'<br>';
											$upto_20_lakhs = mysqli_fetch_assoc(execute_query($sql));
											if($col1 == ''){
												$col1 = $row['col1'];
											}
											
											$sql = 'select count(*) c, sum(grand_total_a_b) as total from survey_estimate left join survey_invoice on survey_invoice.sno = survey_id left join test2 on test2.sno = society_id where col2="'.$row['col2'].'" and grand_total_a_b > 2000000';
											//echo $sql.'<br>';
											$upto_above_20_lakhs = mysqli_fetch_assoc(execute_query($sql));
											if($col1 == ''){
												$col1 = $row['col1'];
											}
											
											if($col1!=$row['col1']){
												echo '<tr>
												<th colspan="3" align="right">Division Total:</th>
												<th>'.$tot_repairable.'</th>
												<th>'.$total_count_5.'</th>
												<th>'.round($total_amt_5, 2).'</th>
												<th>'.$total_count_10.'</th>
												<th>'.round($total_amt_10, 2).'</th>
												<th>'.$total_count_15.'</th>
												<th>'.round($total_amt_15, 2).'</th>
												<th>'.$total_count_20.'</th>
												<th>'.round($total_amt_20, 2).'</th>
												<th>'.$total_count_above.'</th>
												<th>'.round($total_amt_above, 2).'</th>
												<th></th>
												<th></th>
												</tr>';
												$col1 = $row['col1'];
												$total_count = 0;
												$total_count_5 = 0;
												$total_amt_5 = 0;
												$total_count_10 = 0;
												$total_amt_10 = 0;
												$total_count_15 = 0;
												$total_amt_15 = 0;
												$total_count_20 = 0;
												$total_amt_20 = 0;
												$total_count_above = 0;
												$total_amt_above = 0;
												$tot_repairable = 0;

											}
											$tot_repairable+= $row['c'];
											//$total_count += $tot_count['c'];
											$total_count_5 += $upto_5_lakhs['c'];
											$total_count_10 += $upto_10_lakhs['c'];
											$total_count_15 += $upto_15_lakhs['c'];
											$total_count_20 += $upto_20_lakhs['c'];
											$total_count_above += $upto_above_20_lakhs['c'];
											$total_amt_5 += $upto_5_lakhs['total'];
											$total_amt_10 += $upto_10_lakhs['total'];
											$total_amt_15 += $upto_15_lakhs['total'];
											$total_amt_20 += $upto_20_lakhs['total'];
											$total_amt_above += $upto_above_20_lakhs['total'];
											$gtot_repairable+= $row['c'];
											//$gtotal_count += $tot_count['c'];
											$gtotal_count_5 += $upto_5_lakhs['c'];
											$gtotal_count_10 += $upto_10_lakhs['c'];
											$gtotal_count_15 += $upto_15_lakhs['c'];
											$gtotal_count_20 += $upto_20_lakhs['c'];
											$gtotal_count_above += $upto_above_20_lakhs['c'];
											$gtotal_amt_5 += $upto_5_lakhs['total'];
											$gtotal_amt_10 += $upto_10_lakhs['total'];
											$gtotal_amt_15 += $upto_15_lakhs['total'];
											$gtotal_amt_20 += $upto_20_lakhs['total'];
											$gtotal_amt_above += $upto_above_20_lakhs['total'];
										?>
										<tr>
											<td><?php echo $i++; ?></td>
											<td><?php echo $row['division_name']; ?></td>
											<td><?php echo $row['district_name']; ?></td>
											<td><?php echo $row['c']; ?></td>
											<td><?php echo $upto_5_lakhs['c']; ?></td>
											<td><?php echo 'Rs.'.round($upto_5_lakhs['total'],2); ?></td>
											<td><?php echo $upto_10_lakhs['c']; ?></td>
											<td><?php echo 'Rs.'.round($upto_10_lakhs['total'],2); ?></td>
											<td><?php echo $upto_15_lakhs['c']; ?></td>
											<td><?php echo 'Rs.'.round($upto_15_lakhs['total'],2); ?></td>
											<td><?php echo $upto_20_lakhs['c']; ?></td>
											<td><?php echo 'Rs.'.round($upto_20_lakhs['total'],2); ?></td>
											<td><?php echo $upto_above_20_lakhs['c']; ?></td>
											<td><?php echo 'Rs.'.round($upto_above_20_lakhs['total'],2); ?></td>
											<td><?php echo ''; ?></td>
										</tr>
										<?php
										}
										echo '<tr>
												<th colspan="3" align="right">Division Total:</th>
												<th>'.$tot_repairable.'</th>
												<th>'.$total_count_5.'</th>
												<th>'.round($total_amt_5, 2).'</th>
												<th>'.$total_count_10.'</th>
												<th>'.round($total_amt_10, 2).'</th>
												<th>'.$total_count_15.'</th>
												<th>'.round($total_amt_15, 2).'</th>
												<th>'.$total_count_20.'</th>
												<th>'.round($total_amt_20, 2).'</th>
												<th>'.$total_count_above.'</th>
												<th>'.round($total_amt_above, 2).'</th>
												<th></th>
												<th></th>
												</tr>';
										echo '<tr>
												<th colspan="3" align="right">Grand Total:</th>
												<th>'.$gtot_repairable.'</th>
												<th>'.$gtotal_count_5.'</th>
												<th>'.round($gtotal_amt_5, 2).'</th>
												<th>'.$gtotal_count_10.'</th>
												<th>'.round($gtotal_amt_10, 2).'</th>
												<th>'.$gtotal_count_15.'</th>
												<th>'.round($gtotal_amt_15, 2).'</th>
												<th>'.$gtotal_count_20.'</th>
												<th>'.round($gtotal_amt_20, 2).'</th>
												<th>'.$gtotal_count_above.'</th>
												<th>'.round($gtotal_amt_above, 2).'</th>
												<th></th>
												<th></th>
												</tr>';
										?>
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <?php } ?>
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

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
                                            <th>Total Processed</th>
											<th>भवन अच्छा</th>
											<th>भवन मरम्मत योग्य</th>
											<th>भवन जरजर</th>
											<th>भवन उपलब्ध नहीं</th>
											<th>शौचालय अच्छा</th>
                                       		<th>शौचालय मरम्मत योग्य</th>
                                       		<th>नये शौचालय कि आवश्यक्ता</th>
                                        	</tr>
                                        </thead>
                                        <tbody>
                                        	<?php
											$sql = 'SET SQL_BIG_SELECTS=1';
											execute_query($sql);
											$sql = 'SELECT 
											col2,
											district_name,
											count(*) c,
											count(if(building_status="good" or building_status="" or building_status is NULL, 1, NULL)) as building_good, 
											count(if(building_status="repairable", 1, NULL)) as building_repairable, 
											count(if(building_status="discarded", 1, NULL)) as building_discarded, 
											count(if(building_status="not_available", 1, NULL)) as building_not_available, 
											count(if(washroom_floor="good" or washroom_floor="" or washroom_floor is null, 1, NULL)) as washroom_good, 
											count(if(washroom_floor != "good" AND washroom_floor IS NOT NULL AND washroom_floor != "", 1, NULL)) as washroom_repairable, 
											count(if(abs( bathroom_length ) >0, 1, NULL)) as washroom_new
											FROM `survey_invoice` 
											left join survey_invoice_sec_5 on survey_invoice_sec_5.survey_id = survey_invoice.sno 
											left join survey_invoice_sec_11 on survey_invoice_sec_11.survey_id = survey_invoice.sno
											left join test2 on test2.sno = survey_invoice.society_id
											left join master_district on master_district.sno = test2.col2
											where approval_status=4 and  (test2.status!=1 or test2.status is null)
											group by col2
											order by district_name';	
											$result = execute_query($sql);
											$i=1;
											$tot=0;
											$tot_processed=0;
											$tot_building_good = 0;
											$tot_building_repairable = 0;
											$tot_building_discarded = 0;
											$tot_building_not_available = 0;
											$tot_washroom_good = 0;
											$tot_washroom_repairable = 0;
											$tot_washroom_new = 0;
											while($row = mysqli_fetch_assoc($result)){
												$sql = 'select count(*) c from test2 where (test2.status!=1 or test2.status is null) and col2="'.$row['col2'].'"';
												$count = mysqli_fetch_assoc(execute_query($sql));
												echo '<tr>
												<td>'.$i++.'</td>
												<td>'.$row['district_name'].'</td>
												<td>'.$count['c'].'</td>
												<td>'.$row['c'].'</td>
												<td>'.$row['building_good'].'</td>
												<td>'.$row['building_repairable'].'</td>
												<td>'.$row['building_discarded'].'</td>
												<td>'.$row['building_not_available'].'</td>
												<td>'.$row['washroom_good'].'</td>
												<td>'.$row['washroom_repairable'].'</td>
												<td>'.$row['washroom_new'].'</td>
												</tr>';
												$tot += $count['c'];
												$tot_processed += $row['c'];
												$tot_building_good += $row['building_good'];
												$tot_building_repairable += $row['building_repairable'];
												$tot_building_discarded += $row['building_discarded'];
												$tot_building_not_available += $row['building_not_available'];
												$tot_washroom_good += $row['washroom_good'];
												$tot_washroom_repairable += $row['washroom_repairable'];
												$tot_washroom_new += $row['washroom_new'];

											}
											echo '<tr>
											<th colspan="2">Total:</th>
											<th>'.$tot.'</th>
											<th>'.$tot_processed.'</th>
											<th>'.$tot_building_good.'</th>
											<th>'.$tot_building_repairable.'</th>
											<th>'.$tot_building_discarded.'</th>
											<th>'.$tot_building_not_available.'</th>
											<th>'.$tot_washroom_good.'</th>
											<th>'.$tot_washroom_repairable.'</th>
											<th>'.$tot_washroom_new.'</th>
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

<?php

include("scripts/settings.php");
$msg='';
$tab=1;

?>

<?php
page_header_start();
page_header_end();
page_sidebar();
if(isset($_POST['submit'])){
	foreach($_POST as $k=>$v){
		$_SESSION['sale_'.$k] = $v;
	}
}

if(isset($_GET['dis'])){
	$sql = 'update test2 set status=1 where sno='.$_GET['dis'];
	execute_query($sql);
	$msg .= '<p class="text-alert">Society Disabled</p>';
}

if(isset($_GET['act'])){
	$sql = 'update test2 set status=0 where sno='.$_GET['act'];
	execute_query($sql);
	$msg .= '<p class="text-alert">Society Disabled</p>';
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
										<div class="col-md-4 pr-1">
											<div class="form-group">
												<label>District Name</label>
												 
													<select name="district_name" id="district_name" tabindex="<?php echo $tab++; ?>" class="form-control" onChange="fill_tehseel(this.value)">
														<option value="ALL">ALL</option>
													<?php
													$sql = 'select * from master_district';
													$result_district = execute_query($sql);
													while($row_district = mysqli_fetch_assoc($result_district)){
														echo '<option value="'.$row_district['sno'].'" ';
														 if(isset($_SESSION['sale_district_name'])){
															 if($_SESSION['sale_district_name']==$row_district['sno']){
																 echo ' selected="selected" ';
															 }
														 }
														echo '>'.$row_district['district_name'].'</option>';
													}
													
													?>
													</select>

											</div>
										</div>	
										
										<div class="col-md-4 pr-1">
											<div class="form-group">
												<label>Tehseel Name</label>
												<select name="tehseel_name" id="tehseel_name" tabindex="<?php echo $tab++; ?>" class="form-control" onChange="fill_block(this.value)">
												</select>
											</div>
										</div>
										
										<div class="col-md-4 pr-1">
											<div class="form-group">
												<label>Block Name</label>
												<select name="block_name" id="block_name" tabindex="<?php echo $tab++; ?>" class="form-control">
												</select>
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
											$unint=0;
											$int=0;
											$sql = 'select * from test2 where col1 !="DivisionCodeText"  ';
											if(isset($_SESSION['sale_district_name'])){
												if($_SESSION['sale_district_name']!=''){
													$sql .= ' and `col2` ="'.$_SESSION['sale_district_name'].'"';
												}
												if($_SESSION['sale_block_name']!=''){
													$sql .= ' and `col6` ="'.$_SESSION['sale_block_name'].'"';
												}

												if($_SESSION['sale_tehseel_name']!=''){
													$sql .= ' and `col5` ="'.$_SESSION['sale_tehseel_name'].'"';
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
											<tr  class="no-print">
												<td colspan="14" float="left" class="no-print">
												<a href="visit_report_export.php"><input type="button" style="margin-top:20px; color:#ffffff;" name="student_ledger" class="form-control btn btn-danger"  style="float: left;" value="Download In Excel"></a></span>
												</td>
											</tr>
                                            <tr>
                                            <th>S.No.</th>
                                            <th>Society Name</th>
											<th>Division Name</th>
                                            <th>District Name</th>
											<th>Tehseel Name</th>
											<th>Block Name</th>
											<th>ADO/Incharge Name</th>
											<th>ADO/Incharge Mobile</th>
											<th>ADCO Name</th>
											<th>ADCO Mobile</th>
											<th>AR &amp; AC Name</th>
											<th>AR &amp; AC Mobile</th>
                                       		<th>Status</th>
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
											if($row['status']==1){
												$col = 'background:#f90';
											}
											else{
												$col = '';
											}
												
											$sql = 'select * from survey_invoice where society_id="'.$row['sno'].'" order by approval_status';
												//echo $sql.'<br>';
											$result_survey = execute_query($sql);
											$stat_display = '';
											if(mysqli_num_rows($result_survey)!=0){
												while($row_survey = mysqli_fetch_assoc($result_survey)){
													if($row_survey['approval_status']==0){
														$stat_display = '<p class="small text-secondary">Initiated</p>';
													}
													elseif($row_survey['approval_status']==1){
														$stat_display = '<p class="small text-info">Pending At ADO Level</p>';
													}
													elseif($row_survey['approval_status']==2){
														$stat_display = '<p class="small text-warning">Pending At ADCO Level</p>';
													}
													elseif($row_survey['approval_status']==3){
														$stat_display = '<p class="small text-primary">Pending At AR Level</p>';
													}
													elseif($row_survey['approval_status']==4){
														$stat_display = '<p class="small text-success">Sent To HO</p>';
													}
													elseif($row_survey['approval_status']==5){
														$stat_display = '<p class="small text-danger">Rejected</p>';
													}
												}
												$int++;
											}
											else{
												if($row['status']!=1){
													$unint++;
												}
											}
											?>
											<tr style="<?php echo $col; ?>">
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $row['col4'].'&nbsp;<small>('.$row['sno'].')</small>'; ?></td>
												<td><?php echo $result_division['division_name'].'&nbsp;<small>('.$result_division['sno'].')</small>'; ?></td>
                                                <td><?php echo $result_district['district_name'].'&nbsp;<small>('.$result_district['sno'].')</small>'; ?></td>
												<td><?php echo $result_tehseel['tehseel_name'].'&nbsp;<small>('.$result_tehseel['sno'].')</small>'; ?></td>
												<td><?php echo $result_block['block_name'].'&nbsp;<small>('.$result_block['sno'].')</small>'; ?></td>
												<td><?php echo $result_ado['ado_name'].'&nbsp;<small>('.$result_ado['sno'].')</small>'; ?></td>
												<td><?php echo $result_ado['mobile_number']; ?></td>
												<td><?php echo $result_adco['adco_name'].'&nbsp;<small>('.$result_adco['sno'].')</small>'; ?></td>
												<td><?php echo $result_adco['mobile_number']; ?></td>
												<td><?php echo $result_ar['ar_name'].'&nbsp;<small>('.$result_ar['sno'].')</small>'; ?></td>
												<td><?php echo $result_ar['mobile_number']; ?></td>
												<td><?php echo $stat_display; ?></td>
												<td>
													<?php
													if($row['status']=='1'){
														echo '<a href="visit_report.php?act='.$row['sno'].'" onClick="return confirm(\'Are you sure?\');">Reactivate</a>';
													}
													else{
														echo '<a href="visit_report.php?dis='.$row['sno'].'" onClick="return confirm(\'Are you sure?\');">Disable</a>';
													}
													?>
												</td>
												
											</tr>
											<?php
											}
											echo 'Initiated:'.$int.' >> Unintiated:'.$unint;
											
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
	<script>
		var actionUrl = 'scripts/ajax.php';

		function fill_tehseel(val){
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

	</script>

<?php		
page_footer_end();
?>

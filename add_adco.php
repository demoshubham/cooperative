<?php
include("scripts/settings.php");
$tab=1;
$msg='';
if(isset($_POST['submit'])) {
	if($_POST['adco_name']==''){
		$msg .= '<li>Please Enter ADCO Name.</li>';
	}
	if($msg==''){
		if($_POST['edit_sno']!=''){
			$sql = 'update adco set adco_name="'.$_POST['adco_name'].'",
			mobile_number="'.$_POST['mobile_number'].'",
			division_name="'.$_POST['division_name'].'",
			district_name="'.$_POST['district_name'].'"
			where sno='.$_POST['edit_sno'];
			//echo $sql;
			$res = execute_query($sql);
			if ($res) {
				$msg .= '<li>Update sucessful.</li>';

				$sql = 'delete from adco_details where adco_id= "'.$_POST['edit_sno'].'" ';
				execute_query($sql);
				$sql2 = 'select * from adco where sno='.$_POST['edit_sno'];
				$result2 = mysqli_fetch_array(execute_query($sql2));
				$inv2 = $result2['sno'];
				if($inv2!=''){
					foreach($_POST['tehseel_name'] as $k=>$v){
					$sql = 'insert into adco_details (adco_id, tehseel_id) values 
					("'.$inv2.'","'.$v.'")';
					//echo $sql;
					execute_query($sql);
						if(mysqli_error($db)){
							$msg .= '<li>Error # 2 : '.mysqli_error($db).' >> '.$sql.'</li>';
						}
					}
				}
			
			}
		}
		else{
			$sql = 'insert into `adco` (adco_name, mobile_number, division_name, district_name, created_by, creation_time) values ("'.$_POST['adco_name'].'", "'.$_POST['mobile_number'].'", "'.$_POST['division_name'].'", "'.$_POST['district_name'].'", "'.$_SESSION['username'].'", "'.date("Y-m-d H:i:s").'")';
			execute_query($sql);
			
			if(mysqli_error($db)){
				$msg .= '<li>Error # 1 : '.mysqli_error($db).' >> '.$sql.'</li>';
				$inv=0;
			}
			else{
				$inv = mysqli_insert_id($db);
			}		
			if($inv!=0){
				foreach($_POST['tehseel_name'] as $k=>$v){
					$sql = 'insert into adco_details (adco_id, tehseel_id) values 
					("'.$inv.'","'.$v.'")';
					//echo $sql;
					execute_query($sql);
					if(mysqli_error($db)){
						$msg .= '<li>Error # 2 : '.mysqli_error($db).' >> '.$sql.'</li>';
					}
				}
			}
		}
	}
}

if(isset($_GET['id'])){
	$sql = 'select * from adco where sno='.$_GET['id'];
	$result = execute_query($sql);
	$row_edit = mysqli_fetch_array($result);
		
}

if(isset($_GET['del'])){
	$sql = 'delete from adco where sno='.$_GET['del'];
	$result_ado = execute_query($sql);

	$sql = 'delete from adco_details where ado_id= '.$_GET['del'];
	$result_ado_details = execute_query($sql);
	
}

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
										<?php echo $msg; ?>
										<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" id="user_form" name="user_form">
											
											<div class="col-sm-12">
												<div class="row">
													<div class="col-sm-6 form-group">
														<label>ADCO Name</label>
														<input type="text" name="adco_name" id="adco_name" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php if(isset($_GET['id'])){echo $row_edit['adco_name'];} ?>">
													</div>
													<div class="col-sm-6 form-group">
														<label>Mobile Number</label>
														<input type="text" name="mobile_number" id="mobile_number" tabindex="<?php echo $tab++; ?>"  class="form-control" value="<?php if(isset($_GET['id'])){echo $row_edit['mobile_number'];} ?>">
													</div>
												</div>
											
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
																if(isset($_GET['id'])){
																	if($row_edit['division_name']==$row_division['sno']){
																		 echo ' selected="selected" ';
																	}
																}
																echo '>'.$row_division['division_name'].'</option>';
															}
															?>
														</select>
													</div>
													<div class="col-sm-3 form-group">
														<label>जनपद</label>
														<select name="district_name" id="district_name" tabindex="<?php echo $tab++; ?>"  class="form-control" onChange="fill_tehseel(this.value);">
														<option value="<?php  if(isset($_GET['id'])){echo $row_edit['district_name'];} ?>">
																<?php
																	$sql = 'select * from master_district';
																	$result_district = execute_query($sql);
																	while($row_district = mysqli_fetch_assoc($result_district)){
																		echo '<option value="'.$row_district['sno'].'" ';
																		if(isset($_GET['id'])){
																			if($row_edit['district_name']==$row_district['sno']){
																				 echo ' selected="selected" ';
																			}
																		}
																		echo '>'.$row_district['district_name'].'</option>';
																	}
																?>
															</option>
														</select>
													</div>
													<div class="col-sm-3 form-group">
														<label>तहसील</label>
														<select name="tehseel_name[]" id="tehseel_name" tabindex="<?php echo $tab++; ?>"  class="form-control" multiple="multiple">
																<?php
																		if(isset($_GET['id'])){
																			$sql = 'select * from adco_details where adco_id="'.$_GET['id'].'"';
																			$result_detail = execute_query($sql);
																			$array = array();
																			$a=0;
																			while($row_detail = mysqli_fetch_assoc($result_detail)){
																				$array[] = $row_detail['tehseel_id'];
																			}
																			$sql = 'select * from master_tehseel';
																			$result_district = execute_query($sql);
																			while($row_district = mysqli_fetch_assoc($result_district)){
																				
																			
																			if(in_array($row_district['sno'], $array)){
																				echo '<option value="'.$row_district['sno'].'" ';
																				 echo ' selected="selected" ';
																				 echo '>'.$row_district['tehseel_name'].'</option>';
																			}
																		}
																	}
																?>
														</select>
													</div>

													
												</div>
												<input type="submit" class="btn btn-info btn-fill pull-right" value="ADD ADCO" name="submit" id="submit" />
												<input type="hidden" name="edit_sno" value="<?php if(isset($_GET['id'])){echo $_GET['id'];}?>" />
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
                        <div class="col-md-12">
                            <div class="card strpied-tabled-with-hover">
                               
                                <div class="card-body table-full-width table-responsive">
								<table class="table table-hover table-striped text-center">
                                        <thead>
                                            <tr>
                                            <th>S.No.</th>
                                            <th>ADCO Name</th>
                                            <th>Mobile Number</th>
											<th>Division Name</th>
											<th>District Name</th>
											<th>Tehseel Name</th>
											<th>ID</th>
											<th>Edit</th>
											<th>Delete</th>
                                            
                                        </tr></thead>
                                        <tbody>
                                           	<?php
											$i=1;
											$sql = 'select adco.sno as sno, adco.adco_name as adco_name, adco.mobile_number as mobile_number, adco.district_name as district_name, adco.division_name as division_name,  adco_details.adco_id as adco_id, adco_details.tehseel_id as tehseel_id from adco left join adco_details on adco.sno = adco_details.adco_id';
											//echo $sql;
											$result = execute_query($sql);
											
											while($row=mysqli_fetch_array($result)){
												
											$sql_district = 'select * from master_district where sno = "'.$row['district_name'].'"';
											//echo $sql_district.'</br>';
											$result_district = mysqli_fetch_array(execute_query($sql_district));
											
											
											
											$sql_division = 'select * from master_division where sno = "'.$row['division_name'].'"';
											$result_division = mysqli_fetch_array(execute_query($sql_division));
											
											$sql_tehseel = 'select * from master_tehseel where sno = "'.$row['tehseel_id'].'"';
											$result_tehseel = mysqli_fetch_array(execute_query($sql_tehseel));
											
											
											
											?>
											<tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $row['adco_name']; ?></td>
												<td><?php echo $row['mobile_number']; ?></td>
												<td><?php echo $result_division['division_name']; ?></td>
												<td><?php echo $result_district['district_name']; ?></td>
												<td><?php echo $result_tehseel['tehseel_name']; ?></td>
												<td><?php echo $row['sno']; ?></td>
												<td class="text-center"><a href="<?php echo $_SERVER['PHP_SELF'].'?id='.$row['sno'];?>" alt="Edit" data-toggle="tooltip" title="Edit"><span class="far fa-edit" aria-hidden="true"></span></td>
												<td class="text-center"><a href="<?php echo $_SERVER['PHP_SELF'].'?del='.$row['sno'];?>" onclick="return confirm('Are you sure?');" style="color:#f00" alt="Delete"><span class="far fa-trash-alt" aria-hidden="true" data-toggle="tooltip" title="Delete"></span></a></td>
                                                
                                            </tr>
											
											
											<?php
											}
											
											?>
                                            
                                        </tbody>
                                    </table>
				


				
																						


				
<script>

</script>	
  								
				
<?php
page_footer_start();
?>
<!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
<script src="js/light-bootstrap-dashboard.js?v=1.4.0"></script>

<?php		
page_footer_end();
?>

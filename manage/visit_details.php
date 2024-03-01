<?php
include("scripts/settings.php");
$msg='';
$tab=1;
if(isset($_POST['accept'])){
	$sql = 'update enquiry_customer_invoice set status=1, approved_by="'.$_SESSION['username'].'", approval_date="'.date("Y-m-d H:i:s").'", edited_by="'.$_SESSION['username'].'", edition_time="'.date("Y-m-d H:i:s").'" where sno="'.$_POST['edit_sno'].'"';
	execute_query($sql);
	
}
if(isset($_POST['reject'])){
	$sql = 'update enquiry_customer_invoice set status=2, approved_by="'.$_SESSION['username'].'", approval_date="'.date("Y-m-d H:i:s").'", edited_by="'.$_SESSION['username'].'", edition_time="'.date("Y-m-d H:i:s").'" where sno="'.$_POST['edit_sno'].'"';
	execute_query($sql);
	
}
if(isset($_GET['id'])){
	
	$sql = 'select entry_date, enquiry_customer_invoice.status as status, adhar_no, mob_4, problem, solution, enquiry_customer.mobile as mobile, enquiry_customer_invoice.photo_id as photo_id, enquiry_customer.sno as sno, enquiry_customer.cus_name as cus_name, enquiry_customer.fname as fname, enquiry_customer.mobile as mobile, location_tehsil.location_name as tehsil, location_village.location_name as village, user_name from enquiry_customer_invoice left join enquiry_customer on enquiry_customer.sno = enquiry_customer_invoice.customer_id left join location_tehsil on location_tehsil.sno = add_2 left join location_village on location_village.sno = city left join users on users.sno = plv_id where enquiry_customer_invoice.sno="'.$_GET['id'].'"';
	//echo $sql;
	$result = execute_query($sql);
	if(mysqli_num_rows($result)!=0){
		$row = mysqli_fetch_assoc($result);
		$_POST['aadhaar']=$row['adhar_no'];
		$_POST['mobile']=$row['mobile'];
		$_POST['visit_date']=$row['entry_date'];
		$_POST['email']=$row['mob_4'];
		$_POST['full_name']=$row['cus_name'];
		$_POST['father_name']=$row['fname'];
		$_POST['tehsil']=$row['tehsil'];
		$_POST['village']=$row['village'];
		$_POST['photo_id'] = $row['photo_id'];
		$_POST['problem'] = $row['problem'];
		$_POST['solution'] = $row['solution'];
		$_POST['address']='';
	}
	else{

		$_POST['aadhaar']='';
		$_POST['email']='';
		$_POST['full_name']='';
		$_POST['father_name']='';
		$_POST['address']='';
	}
}
else{
	$_POST['aadhaar']='';
	$_POST['mobile']='';
	$_POST['email']='';
	$_POST['full_name']='';
	$_POST['father_name']='';
	$_POST['address']='';
	$_POST['visit_date'] = date("Y-m-d");
}
?>

<?php
page_header_start();
page_header_end();
page_sidebar();

?>	
  				<form action="<?php echo $_SERVER['PHP_SELF'];?>?id=<?php echo $_GET['id']; ?>" method="post" enctype="multipart/form-data" id="user_form" name="user_form">
   					<div class="row">					
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Visit Record</h4>
                                    <?php
									switch($row['status']){
										case 0:{
											echo '<div class="alert alert-info">Status : Pending</div>';
											break;
										}
										case 1:{
											echo '<div class="alert alert-success">Status : Accepted</div>';
											break;
										}
										case 2:{
											echo '<div class="alert alert-danger">Status : Rejected</div>';
											break;
										}
									}
                                    ?>
                                    <?php echo $msg; ?>
                                </div>
                                <div class="card-body">
                                	<div class="row">
                                		<div class="col-md-4">
											<label>Visit Date : </label>
											<?php echo $_POST['visit_date']; ?>
										</div>
										<div class="col-md-3 pr-1">
											<div class="form-group">
												<label>Mobile</label>
												<input type="text" name="mobile" id="mobile" tabindex="<?php echo $tab++; ?>" class="form-control" placeholder="Mobile Number" value="<?php echo $_POST['mobile']; ?>" readonly>
											</div>
										</div>
                                	</div>
									<div class="row">
									   <div class="col-md-3 pr-1">
											<div class="form-group">
												<label>Tehsil</label>
												<input type="text" name="mobile" id="mobile" tabindex="<?php echo $tab++; ?>" class="form-control" placeholder="Mobile Number" value="<?php echo $_POST['tehsil']; ?>" readonly>
											</div>
										</div>
										<div class="col-md-5 pr-1">
											<div class="form-group" id="villages_group">
												<label>Villages</label>
												<input type="text" name="mobile" id="mobile" tabindex="<?php echo $tab++; ?>" class="form-control" placeholder="Mobile Number" value="<?php echo $_POST['village']; ?>" readonly>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4 pr-1">
											<div class="form-group">
												<label>Aadhaar</label>
												<input type="text" name="aadhaar" id="aadhaar" tabindex="<?php echo $tab++; ?>" class="form-control" placeholder="Aadhaar Number" value="<?php echo $_POST['aadhaar']; ?>" readonly>
											</div>
										</div>
										<div class="col-md-5 pr-1">
											<div class="form-group">
												<label>Email</label>
												<input type="text" name="email" id="email" tabindex="<?php echo $tab++; ?>" class="form-control" placeholder="Email Address" value="<?php echo $_POST['email']; ?>" readonly>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6 pr-1">
											<div class="form-group">
												<label>Full Name</label>
												<input type="text" name="full_name" id="full_name" tabindex="<?php echo $tab++; ?>" class="form-control" placeholder="Full Name" value="<?php echo $_POST['full_name']; ?>" readonly>
											</div>
										</div>
										<div class="col-md-6 pl-1">
											<div class="form-group">
												<label>Father Name</label>
												<input type="text" name="father_name" id="father_name" tabindex="<?php echo $tab++; ?>" class="form-control" placeholder="Father Name" value="<?php echo $_POST['father_name']; ?>" readonly>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label>Address</label>
												<input type="text" name="address" id="address" tabindex="<?php echo $tab++; ?>" class="form-control" placeholder="Complete Address" value="<?php echo $_POST['address']; ?>" readonly>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Problem</label>
												<input type="text" name="problem" id="problem" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $_POST['problem']; ?>" readonly>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Solution</label>
												<input type="text" name="solution" id="solution" tabindex="<?php echo $tab++; ?>" class="form-control" value="<?php echo $_POST['solution']; ?>" readonly>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="card">
												<div class="card-header">
													<h5>PLV TASKS</h5>
												</div>
												<div class="card-body">
													<ol class="list-group" style="margin-left:15px;">
													<?php
													$sql = 'select * from enquiry_customer_tasks left join plv_tasks on plv_tasks.sno = plv_task_id where enquiry_id='.$_GET['id'];
													//echo $sql;
													$result_plv_tasks = execute_query($sql);
													while($row_plv_tasks = mysqli_fetch_assoc($result_plv_tasks)){
														echo '<li>'.$row_plv_tasks['plv_tasks'].'</li>';
													}
													?>
													</ol>
												</div>
											</div>
										</div>
									</div>
									<?php
									if($_SESSION['usertype']==1 && $row['status']==0){
									?>
									<div class="row">
										<div class="col-md-12 text-center">
											<button type="submit" name="accept" class="btn btn-success btn-fill pull-right" onClick="return confirm('Are you sure?');">Accept</button>
											<button type="submit" name="reject" class="btn btn-danger btn-fill pull-right" onClick="return confirm('Are you sure?');">Reject</button>
											<input type="hidden" value="<?php echo $_GET['id']; ?>" name="edit_sno">
										</div>	
									</div>
									<?php
									}
									?>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
                        <div class="col-md-4">
                            <div class="card card-user">
                                <div class="card-image">
                                   	<div class="row">
                                   		<div class="col-md-12">
                                   			<img src="user_data/visits/<?php echo $_POST['photo_id']; ?>" alt="..." id="img_preview" style="border-radius: 5px; width: 75%; height: 95%">
                                   		</div>
                                   		
                                   	</div>
									
                                </div>
                            </div>
                        </div>
                    </div>
				</form>				
<?php
page_footer_start();
?>
<!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
<script src="js/light-bootstrap-dashboard.js?v=1.4.0"></script>
<?php		
page_footer_end();
?>

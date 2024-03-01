<?php
include("scripts/settings.php");
 
$msg='';
$tab=1;

if(isset($_POST['submit'])){
	//print_r($_POST);
	if($_POST['edit_sno']==''){
		$sql = 'insert into users (userid, pwd, type, user_name, father_name, address, mobile, created_by, creation_time) values ("'.$_POST['user_name'].'", "'.$_POST['password'].'", "'.$_POST['user_type'].'", "'.$_POST['full_name'].'", "'.$_POST['father_name'].'", "'.$_POST['address'].'", "'.$_POST['mobile'].'", "'.$_SESSION['username'].'", "'.date("Y-m-d H:i:s").'")';
	}
	else{
		$sql = 'update users set 
		userid = "'.$_POST['user_name'].'", 
		pwd = "'.$_POST['password'].'", 
		type = "'.$_POST['user_type'].'",
		user_name = "'.$_POST['full_name'].'", 
		father_name = "'.$_POST['father_name'].'", 
		address = "'.$_POST['address'].'", 
		mobile = "'.$_POST['mobile'].'", 
		edited_by = "'.$_SESSION['username'].'", 
		edition_time = "'.date("Y-m-d H:i:s").'"
		where sno="'.$_POST['edit_sno'].'"';
		
	}
    execute_query($sql);
	if(mysqli_error($db)){ 
		$msg .= '<p class="text text-danger">Error # 1 : '.mysqli_error($db).'>> '.$sql.'</p>';
	}
	else{
		if($_POST['edit_sno']==''){
			$id = mysqli_insert_id($db);
		}
		else{
			$id = $_POST['edit_sno'];
		}
		
		$sql = 'delete from user_district where user_id="'.$id.'"';
		execute_query($sql);
		if(isset($_POST['district_id'])){
			foreach($_POST['district_id'] as $k=>$v){
				$sql = 'insert into user_district (user_id, district_id) values ("'.$id.'", "'.$v.'")';
				execute_query($sql);
				if(mysqli_error($db)){ 
					$msg .= '<p class="text text-danger">Error # 2 : '.mysqli_error($db).'>> '.$sql.'</p>';
				}
			}
		}
		
		$sql = 'delete from user_division where user_id="'.$id.'"';
		execute_query($sql);
		if(isset($_POST['division_id'])){
			foreach($_POST['division_id'] as $k=>$v){
				$sql = 'insert into user_division (user_id, division_id) values ("'.$id.'", "'.$v.'")';
				execute_query($sql);
				if(mysqli_error($db)){ 
					$msg .= '<p class="text text-danger">Error # 2 : '.mysqli_error($db).'>> '.$sql.'</p>';
				}
			}
		}
		
		
		if($msg==''){
			$msg .= '<p class="text text-success">Data Saved</p>';
			$_POST['edit_sno'] = '';
			$_POST['full_name'] = '';
			$_POST['user_name'] = '';
			$_POST['user_type'] = '';
			$_POST['father_name'] = '';
			$_POST['address'] = '';
			$_POST['mobile'] = '';
			$_POST['district_id'] = array();
			$_POST['division_id'] = array();
		}
	}
}
else{
	if(!isset($_POST['search'])){
		$_POST['edit_sno'] = '';
		$_POST['full_name'] = '';
		$_POST['user_name'] = '';
		$_POST['user_type'] = '';
		$_POST['father_name'] = '';
		$_POST['address'] = '';
		$_POST['mobile'] = '';
		$_POST['district_id'] = array();
		$_POST['department_name'] = array();
	}
}

if(isset($_GET['eid'])){
	$sql = 'select * from users where sno="'.$_GET['eid'].'"';
	$data = mysqli_fetch_assoc(execute_query($sql));
	$_POST['edit_sno'] = $data['sno'];
	$_POST['full_name'] = $data['user_name'];
	$_POST['user_name'] = $data['userid'];
	$_POST['user_type'] = $data['type'];
	$_POST['father_name'] = $data['father_name'];
	$_POST['address'] = $data['address'];
	$_POST['mobile'] = $data['mobile'];
	
	$district = array();
	$sql = 'select * from user_district where user_id="'.$_GET['eid'].'"';
	$result = execute_query($sql);
	if(mysqli_num_rows($result)!=0){
		while($row = mysqli_fetch_assoc($result)){
			$district[] = $row['district_id'];
		}
	}
	$_POST['district_id'] = $district;
	
	$division = array();
	$sql = 'select * from user_division where user_id="'.$_GET['eid'].'"';
	$result = execute_query($sql);
	if(mysqli_num_rows($result)!=0){
		while($row = mysqli_fetch_assoc($result)){
			$division[] = $row['division_id'];
		}
	}
	$_POST['division_id'] = $division;
	
}

if(isset($_GET['delid'])){
	$sql = 'delete from users where sno="'.$_GET['delid'].'"';
	execute_query($sql);
	
	$sql = 'delete from user_district where user_id="'.$_GET['delid'].'"';
	execute_query($sql);
	
	
	$msg .= '<p class="text text-danger">Data Deleted.</p>';
}

page_header_start();

?>
<script src="js/krutidev.js"></script>
<script src="js/unicode_keyboard.js"></script>
<style>
	#project_name_hindi{
		font-family: 'Kruti Dev 010';
		font-size: 20px;
	}
	textarea{
		font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
	}
</style>

<?php
page_header_end();
page_sidebar();

?>


   <form id="sale_form" name="sale_form" class="" autocomplete="off" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onSubmit="">

	
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center"></h4></br>
                    </div>
						<?php echo $msg; ?>
                    <div class="card-body">
                    	<div class="row">
                    		<div class="col-md-2">
								<div class="form-group">
									<label >User Name</label>
									<input type="text" name="user_name" id="user_name" class="form-control" tabindex="<?php echo $tab++; ?>" value="<?php echo $_POST['user_name']; ?>">
								</div>
                            </div>
                            <div class="col-md-2 ">
								<div class="form-group">
									<label >Password</label>
									<input type="password" name="password" id="password" class="form-control" tabindex="<?php echo $tab++; ?>">
								</div>
                            </div>
                            <div class="col-md-2 ">
								<div class="form-group">
									<label >Confirm Password</label>
									<input type="text" name="confirm_password" id="confirm_password" class="form-control" tabindex="<?php echo $tab++; ?>">
								</div>
                            </div>
						</div>
                    	<div class="row">
                    		<div class="col-md-3 ">
								<div class="form-group">
									<label >Full Name</label>
									<input type="text" name="full_name" id="full_name" class="form-control" tabindex="<?php echo $tab++; ?>" value="<?php echo $_POST['full_name']; ?>">
								</div>
                            </div>
                            <div class="col-md-3 ">
								<div class="form-group">
									<label >Father Name</label>
									<input type="text" name="father_name" id="father_name" class="form-control" tabindex="<?php echo $tab++; ?>" value="<?php echo $_POST['father_name']; ?>">
								</div>
                            </div>
                            <div class="col-md-3 ">
								<div class="form-group">
									<label >Address</label>
									<input type="text" name="address" id="address" class="form-control" tabindex="<?php echo $tab++; ?>" value="<?php echo $_POST['address']; ?>">
								</div>
                            </div>
                            <div class="col-md-3 ">
								<div class="form-group">
									<label >Mobile</label>
									<input type="text" name="mobile" id="mobile" class="form-control" tabindex="<?php echo $tab++; ?>" value="<?php echo $_POST['mobile']; ?>">
								</div>
                            </div>
						</div>
                        <div class="row">
                           <div class="col-md-2">
								<div class="form-group">
									<label >User Type</label>
									<select name="user_type" id="user_type" class="form-control" tabindex="<?php echo $tab++; ?>">
										<?php
										$sql = 'select * from user_type';
										$result = execute_query($sql);
										while($row = mysqli_fetch_assoc($result)){
											echo '<option value="'.$row['sno'].'" ';
											echo ($_POST['user_type']==$row['sno']?'selected="selected" ':''); 
											echo '>'.$row['user_type'].'</option>';
										}
										?>
									</select>
								</div>
                            </div>
							<div class="col-md-3 form-group">
								<label>मण्डल (कमिश्नरी)</label>	
								<select class="form-control" name="division_id[]" multiple id="division_id" tabindex="<?php echo $tab++; ?>">
									<?php
									$query = "select * from master_division";
									$run = mysqli_query($db,$query);
									while($data = mysqli_fetch_array($run)){
										echo '<option value="'.$data['sno'].'" ';
										if(isset($_POST['division_id'])){
											foreach($_POST['division_id'] as $k=>$v){
												if($v==$data['sno']){
													echo ' selected="selected"';
												}
											}
										}
										echo '>'.$data['division_name'].'</option>';
									}
									?>
								</select>
							</div>
                            <div class="col-4">
                    			<label >District Name</label>
                    			<select class="form-control" name="district_id[]" multiple id="district_id" tabindex="<?php echo $tab++; ?>">
									<?php
									$query = "select * from sammelen_newdistrict";
									$run = mysqli_query($db,$query);
									while($data = mysqli_fetch_array($run)){
										echo '<option value="'.$data['CityCode'].'" ';
										if(isset($_POST['district_id'])){
											foreach($_POST['district_id'] as $k=>$v){
												if($v==$data['CityCode']){
													echo ' selected="selected"';
												}
											}
										}
										echo '>'.$data['City'].'</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<button type="submit" name="submit" class="btn btn-success col-12">Submit</button>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<button type="submit" name="search" class="btn btn-primary col-12">Search</button>
									<input type="hidden" id="id" name="id" value="1">
									<input type="hidden" id="edit_sno" name="edit_sno" value="<?php echo $_POST['edit_sno']; ?>">
								</div>
							</div>
						</div>
					</div>
                </div>
            </div>
        </div>
    </form>
	
		
		<div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center"></h4></br>
                    </div>
                    <div class="card-body">
					<table class="table table-striped table-hover" id="general_stat_table">
						<thead>
						<tr>
						<th>S.No.</th>
						<th>Full Name</th>
						<th>Father Name</th>
						<th>Mobile</th>
						<th>Address</th>
						<th>User Name</th>
						<th>Type</th>
						<th>Division</th>
						<th>District</th>
						<th></th>
						<th></th>
						</tr>
						</thead>
						<tbody>
						<?php
						$i=1;
						$sql = 'select * from users';
							//print_r($_POST);
						if(isset($_POST['search'])){
							if($_POST['department']!=''){
								$sql .= ' and department_id="'.$_POST['department'].'"';
							}
							if($_POST['district']!=''){
								$sql .= ' and district_id="'.$_POST['district'].'"';
							}
							if($_POST['division_name']!=''){
								$sql .= ' and division_id="'.$_POST['division_name'].'"';
							}
						}
						//echo $sql;
						$result = execute_query($sql);
						while($row = mysqli_fetch_assoc($result)){
							$district = '';
							$sql = 'SELECT City 
								FROM user_district 
								LEFT JOIN sammelen_newdistrict ON sammelen_newdistrict.CityCode COLLATE utf8mb3_unicode_ci = user_district.district_id COLLATE utf8mb3_unicode_ci
								WHERE user_id = "' . $row['sno'] . '" COLLATE utf8mb3_general_ci';

							// echo $sql.'<br>';
							$result_district = execute_query($sql);
							if(mysqli_num_rows($result_district)!=0){
								while($row_district = mysqli_fetch_assoc($result_district)){
									$district .= $row_district['City'].'&nbsp; ';
								}
							}
							
							$division = '';
							$sql = 'SELECT division_name 
								FROM user_division 
								LEFT JOIN master_division ON master_division.sno COLLATE utf8mb3_unicode_ci = user_division.division_id COLLATE utf8mb3_unicode_ci
								WHERE user_id = "' . $row['sno'] . '" COLLATE utf8mb3_general_ci';

							// echo $sql.'<br>';
							$result_division = execute_query($sql);
							if(mysqli_num_rows($result_district)!=0){
								while($row_division = mysqli_fetch_assoc($result_division)){
									$division .= $row_division['division_name'].'&nbsp; ';
								}
							}
							
							echo '<tr>
							<td>'.$i++.'</td>
							<td>'.$row['user_name'].'</td>
							<td>'.$row['father_name'].'</td>
							<td>'.$row['mobile'].'</td>
							<td>'.$row['address'].'</td>
							<td>'.$row['userid'].'</td>
							<td>'.$row['type'].'</td>
							<td>'.$division.'</td>
							<td>'.$district.'</td>
							<td><a href="'.$_SERVER['PHP_SELF'].'?eid='.$row['sno'].'" onClick="return confirm(\'Are you sure?\');" alt="Edit Details" data-toggle="tooltip" title="Edit Details"><span class="far fa-edit" aria-hidden="true"></span></a></td>
							<td><a href="'.$_SERVER['PHP_SELF'].'?delid='.$row['sno'].'" onclick="return confirm(\'Are you sure?\');" style="color:#f00" alt="Delete Entry"><span class="far fa-trash-alt" aria-hidden="true" data-toggle="tooltip" title="Delete Entry"></span></a></td> 
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
<script>

$('select[multiple]').multiselect({
	search: true,
	selectAll: true
});
	
$(document).ready( function () {
    /*$('#general_stat_table').DataTable({
		paging: false,
		fixedHeader: true,
		colReorder: true
		});
	});	*/

	
	var t = $('#general_stat_table').DataTable({
		paging: false
    });
 
    
});
	
</script>

    
<?php		
page_footer_end();
?>
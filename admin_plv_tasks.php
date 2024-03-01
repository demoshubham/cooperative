<?php
include("scripts/settings.php");
$msg='';
$tab=1;
if(isset($_POST['plv_tasks'])) {
	if($_POST['plv_tasks']==''){
		$msg .= '<h6 class="alert alert-danger">Blank Entry!</h6>';
	}
	if($msg==''){
		$sql = 'insert into plv_tasks (plv_tasks, created_by, creation_time) values ("'.$_POST['plv_tasks'].'", "'.$_SESSION['username'].'", "'.date("Y-m-d H:i:s").'")';
		execute_query($sql);
		if(mysqli_error($db)){
			$msg .= '<h6 class="alert alert-danger">Error 1 # : '.mysqli_error($db).' >> '.$sql.'</h6>';
			$msg .= '<h6 class="alert alert-danger">Error 1.</h6>';
		}
		if($msg==''){
			$msg .= '<h6 class="alert alert-success">Success.</h6>';
			$_POST['plv_tasks']='';
		}
	}
}
else{
	$_POST['plv_tasks']='';
	$_POST['expiry_date'] = date("Y-m-d");
}
?>

<?php
page_header_start();
page_header_end();
page_sidebar();

?>
   				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">PLV Tasks</h4>
								<?php echo $msg; ?>
							</div>
							<div class="card-body">
								<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" id="user_form" name="user_form">
									<div class="row">
										<div class="col-md-12 pr-1">
											<div class="form-group">
												<label>PLV Tasks</label>
												<input type="text" name="plv_tasks" id="plv_tasks" tabindex="<?php echo $tab++; ?>" class="form-control" placeholder="PLV Tasks" value="<?php echo $_POST['plv_tasks']; ?>">
											</div>
										</div>
									</div>
									<button type="submit" class="btn btn-info btn-fill pull-right">Update Profile</button>
									<div class="clearfix"></div>
								</form>
							</div>
						</div>
					</div>
				</div>				
				
				<div class="row">
                        <div class="col-md-12">
                            <div class="card strpied-tabled-with-hover">
                                <div class="card-header ">
                                    <h4 class="card-title">Location</h4>
                                    <p class="card-category">Space for Pagination</p>
                                </div>
                                <div class="card-body table-full-width table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                            <th>S.No.</th>
                                            <th>PLV Task</th>
                                            <th></th>
                                            <th></th>
                                        </tr></thead>
                                        <tbody>
                                           	<?php
											$i=1;
											$sql = 'select * from plv_tasks';
											$result = execute_query($sql);
											while($row = mysqli_fetch_assoc($result)){
											?>
											<tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $row['plv_tasks']; ?></td>
												<td class="text-center"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?id='.$row['sno'].'" target="_blank" alt="Edit" data-toggle="tooltip" title="Edit"><span class="far fa-edit" aria-hidden="true"></span></td>
												<td class="text-center"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?del='.$row['sno'].'" onclick="return confirm(\'Are you sure?\');" style="color:#f00" alt="Delete"><span class="far fa-trash-alt" aria-hidden="true" data-toggle="tooltip" title="Delete"></span></a></td>
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

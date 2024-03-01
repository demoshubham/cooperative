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
												<label>Mobile Number</label>
												<input type="text" name="mobile_number" id="mobile_number" tabindex="<?php echo $tab++; ?>" class="form-control" placeholder="Block Name" >
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
                                	<table class="table table-bordered table-stripped table-hover">
                                	<?php
									if(isset($_POST['mobile_number'])){
										$sql = '(SELECT sno, ar_name as full_name, mobile_number, "ar" as info FROM `ar` where mobile_number="'.$_POST['mobile_number'].'") 
										union all 
										(SELECT sno, ado_name as full_name, mobile_number, "ado" as info FROM `ado` where mobile_number="'.$_POST['mobile_number'].'") 
										union all 
										(SELECT sno, adco_name as full_name, mobile_number, "adco" as info FROM `adco` where mobile_number="'.$_POST['mobile_number'].'")';
										$result = execute_query($sql);
										echo 'Total : '.mysqli_num_rows($result);
										if(mysqli_num_rows($result)!=0){
											while($row=mysqli_fetch_assoc($result)){
												echo '<tr>
												<td>'.$row['full_name'].'</td>
												<td>'.$row['mobile_number'].'</td>
												<td>'.$row['info'].'</td>';
											}
										}
									}
									
									?>
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

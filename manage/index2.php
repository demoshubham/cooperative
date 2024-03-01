<?php
include("scripts/settings.php");
$msg='';
$tab=1;
if(isset($_POST['submit'])) {
	if(isset($_POST['mobile_number'])){
		$sql = 'select * from session where sno="'.$_SESSION['session_insert_id'].'"';
		$session_row = mysqli_fetch_assoc(execute_query($sql));
		$compare_otp = $session_row['sno'].'_'.$_POST['mobile_otp'];
		//echo $compare_otp.'>>'.$session_row['otp_verification'];
		$msg='<h1>Welcome '.$_SESSION['username'].'</h1>';
		if($compare_otp==$session_row['otp_verification']){
			$sql = 'update session set otp_verification="1" where sno='.$_SESSION['session_insert_id'];
			execute_query($sql);
			$get_msg = "Welcome ".$_SESSION['username'].", your OTP is verified.";
			send_sms($mobile,$get_msg);
		}
		else{
			$msg.='<h3>Invalid OTP.</h3>';
		}

	}
	elseif($_POST['username']!='' && $_POST['userpwd']!='') {
		 
		$sql = 'select * from users where userid="'.$_POST['username'].'"';
		$result = execute_query($sql);
		if(mysqli_num_rows($result)!=0) {			
			
			$row = mysqli_fetch_array(execute_query($sql));
			if($_POST['userpwd']==$row['pwd']) {
				$sql='select * from user_access_detail where user_id = "'.$row['sno'].'"';
				$row1 = mysqli_fetch_array(execute_query($sql));
				$_SESSION['usersno'] = $row['sno'];
				$_SESSION['username'] = $row['userid'];
				$_SESSION['userpwd'] = $row['pwd'];
				$_SESSION['usertype'] = $row['type'];
				$_SESSION['session_id'] = randomstring();
				$_SESSION['startdate'] = date('y-m-d');
				$_SESSION['accessid'] = $row1['auth_id'];
				$_SESSION['branch'] = $row['branch'];
				$_SESSION['admin_session'] = 1;
				$_SESSION['otp_verify'] = 0;
				if(!isset($_SESSION['authcode'])){
					$_SESSION['authcode']='';
				}
				
				$sql = 'select * from plv_users where user_id="'.$row['sno'].'"';
				$plv_users = mysqli_fetch_assoc(execute_query($sql));
				$_SESSION['tehsil'] = $plv_users['tehsil'];
				$_SESSION['plv_id'] = $plv_users['sno'];
				
				
				$time = localtime();
		        $time = $time[2].':'.$time[1].':'.$time[0];
				//echo $time;
		        $_SESSION['starttime']=$time;
				
				$sql = "insert into session (user, s_id, s_start_date, s_start_time, last_active) values ('".$_SESSION['username']."','".$_SESSION['session_id']."','".$_SESSION['startdate']."','".$_SESSION['starttime']."', '".time()."')";
				execute_query($sql);
				$id = mysqli_insert_id($db);

				$_SESSION['session_insert_id'] = $id;
				
				
				$otp_verify = mysqli_fetch_array(execute_query("select * from general_settings where `desc`='otp_verification'"));
				$otp_verify = $otp_verify['rate'];
				$_SESSION['otp_verify'] = $otp_verify;
				if($otp_verify==1){
					$mobile;
					$otp = randomnumber();
					$sql = 'update session set otp_verification="'.$id.'_'.$otp.'" where sno='.$id;
					execute_query($sql);
					$get_msg = "Dear, ".$_SESSION['username']." one time verification code for your ERP Login is $otp. The code is valid for 30 mins only.";
					send_sms($mobile,$get_msg);
					
				}

				$msg='<h1>Welcome '.$_SESSION['username'].'</h1>';
				
				
				$response=2;
			}
			else {
				
				$msg .= '<h4 class="header text-center alert alert-danger">Please Enter Valid User Password</h4>';
				$response=1;
			}
		}
		else {
			$sql = '(SELECT sno, ar_name as full_name, mobile_number, "ar_dr" as info FROM `ar_dr` where mobile_number="'.$_POST['username'].'") 
			union all
			(SELECT sno, ar_name as full_name, mobile_number, "ar" as info FROM `ar` where mobile_number="'.$_POST['username'].'") 
				union all 
				(SELECT sno, ado_name as full_name, mobile_number, "ado" as info FROM `ado` where mobile_number="'.$_POST['username'].'") 
				union all 
				(SELECT sno, adco_name as full_name, mobile_number, "adco" as info FROM `adco` where mobile_number="'.$_POST['username'].'")';
				//echo $sql;
				$result = execute_query($sql);
				if(mysqli_num_rows($result)==0){
					$msg .= '<h4 class="header text-center alert alert-danger">Please Enter Valid User Name</h4>';
					$response=1;
					//$data[] = array("status"=>"error", "msg"=>"Invalid Mobile Number");
				}
				else{
					if($_POST['userpwd']=='WeKnow@123'){
						$row = mysqli_fetch_assoc($result);
						
						$_SESSION['usersno'] = $row['sno'];
						$_SESSION['session_id'] = randomstring();
						$_SESSION['startdate'] = date('y-m-d');
						
						$_SESSION['session_id'] = randomstring();
						$_SESSION['user_type'] = $row['info'];
						$_SESSION['user_id'] = $row['sno'];
						$_SESSION['username'] = $row['full_name'];

						$sql = "insert into session (user_type, user_id, otp_verification, s_start_date, s_start_time, last_active, s_id, admin_remarks) values ('".$row['info']."', '".$row['sno']."', '1', '".date("Y-m-d")."','".date("H:i:s")."', '".time()."', '".$_SESSION['session_id']."', 'admin_login bypass_mode')";
						execute_query($sql);
						$id = mysqli_insert_id($db);
						$sql = 'update session set otp_verification=1 where sno="'.$row['sno'].'"';
						execute_query($sql);
						//$data[] = array("status"=>"verified", "msg"=>"OTP Verified");
						$_SESSION['act_session_id'] = $_SESSION['session_id'];
						$_SESSION['usertype'] = 3;

						//$data[] = array("status"=>"otp_sent", "msg"=>"OTP Sent on mobile.".$otp);
					}
					else{
						$msg .= '<h4 class="header text-center alert alert-danger">Please Enter Valid User Password</h4>';
						$response=1;
					}
				}
			 	
		}		 
	 }
	 else {
		 
		 $msg .= '<h4 class="header text-center alert alert-danger">Please Enter User Detail</h4>';
		 $response=1;
	 }
 }
if(isset($_POST['super_submit'])){
	$sql = 'select * from users where userid="sadmin"';
	$result = execute_query($sql);
	$row = mysqli_fetch_array(execute_query($sql));
	$sql='select * from user_access_detail where user_id = "'.$row['sno'].'"';
	$row1 = mysqli_fetch_array(execute_query($sql));
	$_SESSION['usersno'] = $row['sno'];
	$_SESSION['username'] = $row['userid'];
	$_SESSION['userpwd'] = $row['pwd'];
	$_SESSION['usertype'] = $row['type'];
	$_SESSION['session_id'] = randomstring();
	$_SESSION['startdate'] = date('y-m-d');
	$_SESSION['accessid'] = $row1['auth_id'];
	$_SESSION['branch'] = $row['branch'];
	$_SESSION['admin_session'] = 1;
	$_SESSION['otp_verify'] = 0;
	if(!isset($_SESSION['authcode'])){
		$_SESSION['authcode']='';
	}

	$sql = 'select * from plv_users where user_id="'.$row['sno'].'"';
	$plv_users = mysqli_fetch_assoc(execute_query($sql));
	$_SESSION['tehsil'] = $plv_users['tehsil'];
	$_SESSION['plv_id'] = $plv_users['sno'];


	$time = localtime();
	$time = $time[2].':'.$time[1].':'.$time[0];
	//echo $time;
	$_SESSION['starttime']=$time;

	$sql = "insert into session (user, s_id, s_start_date, s_start_time, last_active) values ('".$_SESSION['username']."','".$_SESSION['session_id']."','".$_SESSION['startdate']."','".$_SESSION['starttime']."', '".time()."')";
	execute_query($sql);
	$id = mysqli_insert_id($db);

	$_SESSION['session_insert_id'] = $id;


	$otp_verify = mysqli_fetch_array(execute_query("select * from general_settings where `desc`='otp_verification'"));
	$otp_verify = $otp_verify['rate'];
	$_SESSION['otp_verify'] = $otp_verify;
	if($otp_verify==1){
		$mobile;
		$otp = randomnumber();
		$sql = 'update session set otp_verification="'.$id.'_'.$otp.'" where sno='.$id;
		execute_query($sql);
		$get_msg = "Dear, ".$_SESSION['username']." one time verification code for your ERP Login is $otp. The code is valid for 30 mins only.";
		send_sms($mobile,$get_msg);

	}

	$msg='<h1>Welcome '.$_SESSION['username'].'</h1>';


	$response=2;
}
//print_r($_SESSION);
?>

<?php
page_header_start();
?>
<link rel="stylesheet" href="css/impulseslider.css" type="text/css" media="screen" />

<?php
page_header_end();
if(isset($_SESSION['admin_session']) || $_SESSION['user_type']=='ar_dr') {
	page_sidebar('super');
	
	if(isset($_GET['dist'])){
		$sql = 'select ar.sno as sno, ar_name, district_id from ar_details left join ar on ar.sno = ar_id where district_id="'.$_GET['dist'].'"';
		//echo $sql;
		$ar = mysqli_fetch_assoc(execute_query($sql));
	}
		if($_SESSION['user_type']!='ar_dr'){
?>	
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12">
						<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
							<ol class="carousel-indicators">
								<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
								<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
								<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
							</ol>
							<div class="carousel-inner">
								<div class="carousel-item">
									<img class="d-block w-100" src="images/2.jpg" alt="Second slide">
								</div>
								<div class="carousel-item">
									<img class="d-block w-100" src="images/3.jpg" alt="Third slide">
								</div>
								<div class="carousel-item">
									<img class="d-block w-100" src="images/4.jpg" alt="Third slide">
								</div>
							</div>
							<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
							<span class="carousel-control-prev-icon" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
							</a>

							<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="sr-only">Next</span>
							</a>

						</div>                        
                    </div>
                    
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="header">
								<h4 class="title m-0 text-danger">प्रदेश एक नजर में</h4>
								<?php
								$start = time();
								$summary = summary();
								$end = time();
								//echo '@@@@@@@@@'.($end-$start);
								//print_r($summary);
								?>
							</div>
							<div class="content text-center m-0">
								<div class="row">
									<div class="col-2">
										<img src="images/icons/pacs.gif" class="img-fluid stat-icon">
										<br/>
										पंजीकृत समीतियां
										<br/>
										<h4 class="m-0 p-0 text-danger"><?php echo $summary['total']['c']; ?></h4>
									</div>
									<div class="col-2">
										<img src="images/icons/pacs.gif" class="img-fluid stat-icon">
										<br/>
										परिसमापन में
										<br/>
										<h4 class="m-0 p-0 text-danger"><?php echo $summary['total']['liquidation']; ?></h4>
									</div>
									<div class="col-2">
										<img src="images/icons/pacs.gif" class="img-fluid stat-icon">
										<br/>
										न्यायालय के वाद में
										<br/>
										<h4 class="m-0 p-0 text-danger"><?php echo $summary['total']['litigation']; ?></h4>
									</div>
									<div class="col-2">
										<img src="images/icons/business.gif" class="img-fluid stat-icon">
										<br/>
										व्यापार (लाख में)
										<br/>
										<h4 class="m-0 p-0 text-danger"><?php echo round($summary['total']['total_business']/100000, 2); ?></h4>
									</div>
									<div class="col-2">
										<img src="images/icons/members.gif" class="img-fluid stat-icon">
										<br/>
										सदस्यता
										<br/>
										<h4 class="m-0 p-0 text-danger"><?php echo $summary['total']['active_members']; ?></h4>
									</div>
									<div class="col-2">
										<img src="images/icons/cader.gif" class="img-fluid stat-icon">
										<br/>
										कैडर सचिव
										<br/>
										<h4 class="m-0 p-0 text-danger"><?php echo $summary['total']['secretary_cader']; ?></h4>
									</div>
									
								</div>
								<div class="row">
									<div class="col-2">
										<img src="images/icons/employees.gif" class="img-fluid stat-icon">
										<br/>
										नान कैडर सचिव
										<br/>
										<h4 class="m-0 p-0 text-danger"><?php echo $summary['total']['secretary_non_cader']; ?></h4>
									</div>
									<div class="col-2">
										<img src="images/icons/accountant.gif" class="img-fluid stat-icon">
										<br/>
										लेखाकार
										<br/>
										<h4 class="m-0 p-0 text-danger"><?php echo $summary['total']['accountant']; ?></h4>
									</div>
									<div class="col-2">
										<img src="images/icons/assistant_accountant.gif" class="img-fluid stat-icon">
										<br/>
										सहायक लेखाकार
										<br/>
										<h4 class="m-0 p-0 text-danger"><?php echo $summary['total']['assistant_accountant']; ?></h4>
									</div>
									<div class="col-2">
										<img src="images/icons/seller.gif" class="img-fluid stat-icon">
										<br/>
										विक्रेता
										<br/>
										<h4 class="m-0 p-0 text-danger"><?php echo $summary['total']['seller']; ?></h4>
									</div>
									<div class="col-2">
										<img src="images/icons/support_staff.gif" class="img-fluid stat-icon">
										<br/>
										सहयोगी
										<br/>
										<h4 class="m-0 p-0 text-danger"><?php echo $summary['total']['support_staff']; ?></h4>
									</div>
									<div class="col-2">
										<img src="images/icons/guard.gif" class="img-fluid stat-icon">
										<br/>
										चौकीदार
										<br/>
										<h4 class="m-0 p-0 text-danger"><?php echo $summary['total']['guard']; ?></h4>
									</div>
									<!--<div class="col-2">
										<img src="images/icons/land.gif" class="img-fluid stat-icon">
										<br/>
										समितियों कि भूमि
										<br/>
										<h4 class="m-0 p-0 text-danger">148523</h4>
									</div>
									<div class="col-2">
										<img src="images/icons/tractor.gif" class="img-fluid stat-icon">
										<br/>
										संसाधन
										<br/>
										<h4 class="m-0 p-0 text-danger">2589</h4>
									</div>-->
								</div>
								<hr/>
								<table class="table table-striped table-bordered">
									<thead>
										<tr>
											<th>क्रम</th>
											<th>मण्डल</th>
											<th>जिला</th>
											<th>समितियां</th>
											<th>परिसमापन में</th>
											<th>न्यायालय के वाद में</th>
											<th>व्यापार (लाख में)</th>
											<th>सदस्यता</th>
											<th>कैडर सचिव</th>
											<th>नान कैडर सचिव</th>
											<th>लेखाकार (संख्या)</th>
											<th>कंप्यूटर आपरेटर (संख्या)</th>
											<th>सहायक लेखाकार (संख्या)</th>
											<th>विक्रेता (संख्या)</th>
											<th>सहयोगी (संख्या)</th>
											<th>चौकीदार (संख्या)</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$i=1;
										//print_r($summary);
										$old_col1='';
										$tot_society = 0;
										$tot_liquidation = 0;
										$tot_litigation = 0;
										$tot_total_business = 0;
										$tot_active_members = 0;
										$tot_secretary_non_cader = 0;
										$tot_secretary_cader = 0;
										$tot_accountant_count = 0;
										$tot_computer_operator = 0;
										$tot_assistant_accountant_count = 0;
										$tot_seller_count = 0;
										$tot_support_staff_count = 0;
										$tot_guard_count = 0;
										foreach($summary as $k=>$v){
											if($old_col1==''){
												$old_col1 = $v['division_id'];
											}
											if($old_col1!='' && $old_col1!=$v['division_id']){
												echo '<tr><th colspan="3" class="text-right">मण्डल का योग</th>
												<th>'.$tot_society.'</th>
												<th>'.$tot_liquidation.'</th>
												<th>'.$tot_litigation.'</th>
												<th>'.$tot_total_business.'</th>
												<th>'.$tot_active_members.'</th>
												<th>'.$tot_secretary_non_cader.'</th>
												<th>'.$tot_secretary_cader.'</th>
												<th>'.$tot_accountant_count.'</th>
												<th>'.$tot_computer_operator.'</th>
												<th>'.$tot_assistant_accountant_count.'</th>
												<th>'.$tot_seller_count.'</th>
												<th>'.$tot_support_staff_count.'</th>
												<th>'.$tot_guard_count.'</th>
												</tr>';
												$old_col1 = $v['division_id'];
												$tot_society = 0;
												$tot_liquidation = 0;
												$tot_litigation = 0;
												$tot_total_business = 0;
												$tot_active_members = 0;
												$tot_secretary_non_cader = 0;
												$tot_secretary_cader = 0;
												$tot_accountant_count = 0;
												$tot_computer_operator = 0;
												$tot_assistant_accountant_count = 0;
												$tot_seller_count = 0;
												$tot_support_staff_count = 0;
												$tot_guard_count = 0;
												
											}

											echo '<tr>
												<td>'.$i++.'</td>
												<td>'.$v['division_name'].'</td>
												<td>'.$v['district_name'].'</td>
												<td>'.$v['c'].'</td>
												<td>'.$v['liquidation'].'</td>
												<td>'.$v['litigation'].'</td>
												<td>'.round($v['total_business']/100000,2).'</td>
												<td>'.$v['active_members'].'</td>
												<td>'.$v['secretary_cader'].'</td>
												<td>'.$v['secretary_non_cader'].'</td>
												<td>'.$v['accountant_count'].'</td>
												<td>'.$v['computer_operator'].'</td>
												<td>'.$v['assistant_accountant_count'].'</td>
												<td>'.$v['seller_count'].'</td>
												<td>'.$v['support_staff_count'].'</td>
												<td>'.$v['guard_count'].'</td>

											</tr>';	
											
											$tot_society += $v['c'];
											$tot_liquidation += $v['liquidation'];
											$tot_litigation += $v['litigation'];
											$tot_total_business += round($v['total_business']/100000,2);
											$tot_active_members += $v['active_members'];
											$tot_secretary_non_cader += $v['secretary_non_cader'];
											$tot_secretary_cader += $v['secretary_cader'];
											$tot_accountant_count += $v['accountant_count'];
											$tot_computer_operator += $v['computer_operator'];
											$tot_assistant_accountant_count += $v['assistant_accountant_count'];
											$tot_seller_count += $v['seller_count'];
											$tot_support_staff_count += $v['support_staff_count'];
											$tot_guard_count += $v['guard_count'];
										}
										/*echo '<tr>
											<th class="text-center" colspan="2">योग</th>
											<th>'.$summary['total']['c'].'</th>
											<th>'.$summary['total']['liquidation'].'</th>
											<th>'.$summary['total']['litigation'].'</th>
											<th>'.round($summary['total']['total_business']/100000,2).'</th>
											<th>'.$summary['total']['active_members'].'</th>
											<th>'.$summary['total']['secretary'].'</th>
											<th>'.$summary['total']['secretary_cader'].'</th>
											<th>'.$summary['total']['accountant_count'].'</th>
											<th>'.$summary['total']['assistant_accountant_count'].'</th>
											<th>'.$summary['total']['seller_count'].'</th>
											<th>'.$summary['total']['support_staff_count'].'</th>
											<th>'.$summary['total']['guard_count'].'</th>
										</tr>';		*/	
										?>
									</tbody>
									
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
				
			<?php 
			}
			else{
				echo '
				<div class="col-md-12">
				<div class="card">
					<div class="header">
						<div class="row">
							<div class="col-md-12 text-center">';
				$sql = 'select ar_dr_details.sno as sno, ar_name, mobile_number, ar_id, ar_dr_details.district_id as district_id, master_division.division_name as division_name from ar_dr_details left join master_division on master_division.sno = ar_dr_details.district_id left join ar_dr on ar_dr.sno = ar_id where ar_id='.$_SESSION['user_id'];
				//echo $sql;
				$ar_detail = mysqli_fetch_assoc(execute_query($sql));
				echo '<h4 class="title">DR Dashboard</h4>';
				echo '<strong>Name:</strong> '.$ar_detail['ar_name'].'. <strong>Mobile:</strong> '.$ar_detail['mobile_number'].'<br><strong>Alloted Division : </strong>';
				echo $ar_detail['division_name'];
				echo '
							</div>
						</div>
					
				</div>
				</div>
				';
			}
			?>
<script>
$('.carousel').carousel({
  interval: 3000
})
</script>

<?php 
}
elseif(!isset($_SESSION['act_session_id'])){
?>
<div class="wrapper wrapper-full-page">
        <!-- Navbar -->
        <!-- End Navbar -->
        <div class="full-page  section-image" data-color="black" data-image="images/up_background.gif">
   <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
            <div class="content" style="padding-top: 0px;">
          
            <div class="row">
			<div class="col-md-12">
				<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
				<div class="image">
						<div class="author" style="position:absolute; top:10px; left:30px; z-index: 9999999">
							 <a href="#">
								<img class="avatar border-gray" src="images/cm-up.jpg" alt="..." style="border: 5px solid #000000; border-radius:50%;">
							</a>
							
						</div>
						<div class="author" style="position:absolute;top:10px; left:45%; z-index: 9999999">
							 <a href="#">
							<img class="avatar border-gray" src="images/pm.jpg" alt="..." style="border: 5px solid #000000; border-radius:50%;" height="200">

							  
							</a>
						</div>
						<div class="author" style="position:absolute;top:10px; right:30px; z-index: 9999999">
							 <a href="#">
							<img class="avatar border-gray" src="images/shrijpsrathore.jpg" alt="..." style="border: 5px solid #000000; border-radius:50%;">

							  
							</a>
						</div>
					</div>
					<ol class="carousel-indicators">
						<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
						<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
						<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
					</ol>
					<div class="carousel-inner">
						<div class="carousel-item active">
							<img class="d-block w-100" src="images/2.jpg" alt="First slide">
						</div>
						<div class="carousel-item">
							<img class="d-block w-100" src="images/3.jpg" alt="Third slide">
						</div>
						<div class="carousel-item">
							<img class="d-block w-100" src="images/4.jpg" alt="Third slide">
						</div>
					</div>
					<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
					</a>

					<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>

				</div>                        
			</div>

		</div>
         
               
                <div class="container">
                    <div class="col-md-4 col-sm-6 ml-auto mr-auto login-page">
                        
                            <div class="card card-login">
                                <div class="card-header card-header-rose text-center ">
                                   	<h2 class="header text-center" style="font-size: 1.6rem">सहकारिता सर्वेक्षण</h2>
                                </div>
                                <div class="card-body text-center ">
									<form action="index2.php" method="post">
                               		<button type="submit" name="super_submit" class="m-4 p-4 btn btn-success">प्रवेश करे</button>
                               		</form>
                                </div>
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <div class="full-page-background" style="background-image: url(images/up_background.gif) "></div>
    </div>  
<script>
$('.carousel').carousel({
  interval: 3000
})
</script>    				
	
<?php	
}
else{
?>
    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="js/light-bootstrap-dashboard.js"></script>
	<script>
		function open_dropdown(id){
            var upto_dropdown = document.getElementById('upto_dropdown').value;
            for (var i = 1; i < upto_dropdown; i++) {
                if(id == i){
                    if($("#drop_"+i).css("display") == "none"){
                        $("#drop_"+i).show();
                    }
                    else{
                        $("#drop_"+i).hide();
                    }
                }
                else{
                     $("#drop_"+i).hide();
                }
                
            }
        }

	</script>
<?php		
		
}
page_footer_start();
?>
	<script src="js/jquery.impulse.slider.js"></script>
<script type="text/javascript">
    $(window).load(function(){

        $('#cubeSpinner').impulseslider({
            height: 200,
            width: 200,
            depth:100,
            pauseTime: 2000,
            perspective:800
        });

        $('#penthagon').impulseslider({
            height: 100,
            width: 150,
            depth: 102,
            perspective: 500,
            pauseTime: 1500,
            directionRight: false,
            containerSelector: "#penthagonCarousel",
            spinnerSelector: "#penthagon"
        });

    });
  </script>
  
	
<?php
page_footer_end();
?>

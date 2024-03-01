<?php
$time_track = array();
$time_track[] = microtime(true);
set_time_limit(0);
error_reporting(E_ALL);
// error_reporting(0);
/*if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}*/
session_cache_limiter('nocache');
session_start();

include("settings_dbase.php");

$sms_user = mysqli_fetch_array(execute_query("select * from general_settings where `desc`='sms_user'"));
$sms_user = $sms_user['rate'];

// $sms_pwd = mysqli_fetch_array(execute_query("select * from general_settings where `desc`='sms_password'"));
// $sms_pwd = $sms_pwd['rate'];


sethistory();
date_default_timezone_set('Asia/Calcutta');

$company_name = mysqli_fetch_array(execute_query("select * from general_settings where `desc`='company'"));
$company_name = $company_name['rate'];

$software_type = mysqli_fetch_array(execute_query("select * from general_settings where `desc`='software_type'"));
$software_type = $software_type['rate'];

$mobile = mysqli_fetch_array(execute_query("select * from general_settings where `desc`='mobile'"));
$mobile = $mobile['rate'];

$state = mysqli_fetch_array(execute_query("select * from general_settings where `desc`='state'"));
$state = abs($state['rate']);

function dbconnect(){
	global $db;	
	return $db;
}

function page_header_start($title='Survey Form') {
	global $software_type;
	global $time_track;
	$time_track[] = microtime(true);
	$sql = 'select * from general_settings where `desc`="company"';
	$company = mysqli_fetch_array(execute_query($sql));
	$company = explode(" ", $company['rate']);
	$company_name = '';
	foreach($company as $k=>$v){
		$company_name .= '<span>'.substr($v, 0, 1).'</span>'.substr($v, 1).' ';
		//echo $v.'<br>';
	}
	$current_file_name = basename($_SERVER['PHP_SELF']);
	if(isset($_SESSION['session_id'])) {
		$sql = 'select * from navigation where hyper_link="'.$current_file_name.'"';
		$file = mysqli_fetch_array(execute_query($sql));
		//logvalidate($file['sno']);
		$file['link_description'] = isset($file['link_description'])?$file['link_description']:'';
		$file['color'] = isset($file['color'])?$file['color']:'';
		$title = $file['link_description'];
		$GLOBALS['title'] = 'सहकारिता महासम्मेलन';
	}
	else{
		$file['color']='';
		//logvalidate();
	}
	global $time_track;
	$time_track[] = microtime(true);
	$time_track[] = microtime(true);
	echo '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="images/favicon.ico">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>'.$title.'</title>

	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta name="viewport" content="width=device-width" />
	
    <!--     Fonts and icons     -->
    <link href="fa/css/all.min.css" rel="stylesheet">
    <script src="fa/js/fontawesome.min.js"></script>
    <link href="css/pe-icon-7-stroke.css" rel="stylesheet" />

    <!-- Bootstrap core CSS     -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="dataTables/datatables.min.css">
	<script src="js/jquery.3.2.1.min.js" type="text/javascript"></script>
	<script src="js/jquery-ui.js" type="text/javascript"></script>
	<script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-switch.js"></script>
	<script src="js/calendar.js" language="javascript" type="text/javascript"></script>
	<script src="js/bpopup.js" language="javascript" type="text/javascript"></script>
	<script src="jquery/jquery.ba-throttle-debouce.min.js" type="text/javascript"></script>
	<script src="jquery/jquery.multiselect.js" language="javascript"></script>

    <!-- Animation library for notifications   -->
    <link href="css/animate.min.css" rel="stylesheet"/>

    
    <link href="css/jquery-ui.css" rel="stylesheet" />
	<!--
	<!--<link href="css/component.css" rel="stylesheet" type="text/css" media="all" />
	<link href="css/jcarousel.css" rel="stylesheet" type="text/css" media="all" />-->
	<link href="css/styles.css" rel="stylesheet" type="text/css" media="all" />
	<link href="css/pagination.css" rel="stylesheet" type="text/css" media="all" />
	<link href="css/jquery.multiselect.css" rel="stylesheet" type="text/css" media="all" />
	<!--  Light Bootstrap Table core CSS    -->
	<link href="css/light-bootstrap-dashboard.css?v=1.4.0" rel="stylesheet"/>


    <style type="text/css">
        .nav_style li:hover{
            background-color: #b2b8ae;
        }
    </style>
    <style type="text/css">
        .daterclass{
            padding: 5px;
            border: 2px solid lightblue;
        }
        .daterclass tr td{
          
        }

    </style>

	<script type="text/javascript" language="javascript">
		var software_type="'.$software_type.'";
		$(document).ready( 
			function() {
				// Add the "focus" value to class attribute
				$("input").focusin( 
					function() {
						$(this).addClass("focus");
					}
				);
				$("select").focusin( 
					function() {
						$(this).addClass("focus");
					}
				);
				$(":checkbox").focusin( 
					function() {
						$(this).addClass("focus");
					}
				);
				// Remove the "focus" value to class attribute
				$("input").focusout( 
					function() {
						$(this).removeClass("focus");
					}
				);
				$("select").focusout( 
					function() {
						$(this).removeClass("focus");
					}
				);
				$(":checkbox").focusout( 
					function() {
						$(this).removeClass("focus");
					}
				);
				$(\'[data-toggle="tooltip"]\').tooltip(); 
			}
		);

		$(function() {
			var options = {
				source: function (request, response){
					$.getJSON("scripts/ajax.php?id=nav",request, response);
				},
				position: {
					my: "left top",
					at: "left bottom",
					collision: "flip"
				},
				minLength: 1,
				select: function( event, ui ) {
					log( ui.item ?
						"Selected: " + ui.item.value + " aka " + ui.item.label :
						"Nothing selected, input was " + this.value );
				},
				select: function( event, ui ) {
					window.open(ui.item.hyper_link, "_self");
					return false;
				}
			};
		$("input#shortcut_command").on("keydown.autocomplete", function() {
			$(this).autocomplete(options);
		});
		});


	</script>
	<script language="javascript" type="text/javascript">
		function check_prev_date(form_date){
			calculate_total(1);
			var cur_date = "'.date("Y-m-d").'";
			var warn = 0;
			$(".noblank").each(function(index, element){
				if($(element).val()==""){
					$( element ).css( "backgroundColor", "yellow" );
					warn = 1;
				}
				else{
					$( element ).css( "backgroundColor", "white" );
				}
			});
			if(warn!=0){
				alert("Please enter all complusory blocks");
				return false;
			}

			if(cur_date>form_date){
				var response = confirm("Entry date is old than today. Do you want to proceed. ?");
			}
			else{
				var response = confirm("Are you sure?");
			}
			return response;
		}
	</script>
	<link href="jquery/jquery-ui.css" rel="stylesheet" type="text/css" media="screen" />
	<style>
		#wrapper{
			border: 5px solid #'.$file['color'].';
		}
	</style>';
?>
	<script>
	$(".dropdown dt a").on('click', function() {
		$(".dropdown dd ul").slideToggle('fast');
	});

	$(".dropdown dd ul li a").on('click', function() {
		$(".dropdown dd ul").hide();
	});

	function getSelectedValue(id) {
	  return $("#" + id).find("dt a span.value").html();
	}

	$(document).bind('click', function(e) {
	  var $clicked = $(e.target);
	  if (!$clicked.parents().hasClass("dropdown")) $(".dropdown dd ul").hide();
	});

	$('.mutliSelect input[type="checkbox"]').on('click', function() {

	  var title = $(this).closest('.mutliSelect').find('input[type="checkbox"]').val(),
		title = $(this).val() + ",";

	  if ($(this).is(':checked')) {
		var html = '<span title="' + title + '">' + title + '</span>';
		$('.multiSel').append(html);
		$(".hida").hide();
	  } else {
		$('span[title="' + title + '"]').remove();
		var ret = $(".hida");
		$('.dropdown dt a').append(ret);

	  }
	});		
		

// defining flags
var isCtrl = false;
var isAlt = false;
// helpful function that outputs to the container
// the magic :)

<?php
	$current_file_name = basename($_SERVER['PHP_SELF']);
	global $client_details;
	//$client_details['last_renewal'] = date("Y-m-d", strtotime("+1 Year", strtotime($client_details['last_renewal']))); 
	//$expiry_days = date("d", strtotime($client_details['last_renewal']) - strtotime(date("Y-m-d")));
	if(isset($_SESSION['username'])){
		$user = $_SESSION['username'];
		$sql = 'select * from session where user="'.$user.'" order by s_start_date desc, s_start_time desc';
		$last = execute_query($sql);
		if(mysqli_num_rows($last)!=0){
			$last = mysqli_fetch_array($last);
			$last = $last['s_start_date'].' '.$last['s_start_time'];
		}
		else{
			$last = '';
		}
		$sql = 'select * from general_settings where `desc`="session_timeout"';
		$timeout = mysqli_fetch_array(execute_query($sql));
		if($timeout['rate']>0){
			$timeout = $timeout['rate']*60;
			$difference = time()-$timeout;
			$sql = 'select * from session where user!="'.$_SESSION['username'].'" and last_active>'.$difference;
			$session = execute_query($sql);
			if(mysqli_num_rows($session)!=0){
				$other = mysqli_num_rows($session);
			}
			else{
				$other = 0;
			}
		}
		
	}
	else{
		$user='Guest';
		$last='';
		$other='';
	}
		
	if($current_file_name=='index.php'){
	?>
	<?php
	}
	?>
	</script>

<?php
}


function page_header_end(){
	$current_file_name = basename($_SERVER['PHP_SELF']);
	if($current_file_name!='index.php'){
		$class = 'sidebar-mini';
	}
	else{
		$class = '';
	}
	echo '
</head>

<body class="'.$class.'">
    <div class="wrapper">';
	$time_track[] = microtime(true);
}

function page_sidebar($id=''){
?>
		<div class="sidebar" data-color="orange" data-image="images/sidebar-5.jpg">
		<!--

			Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple"
			Tip 2: you can also add an image using data-image tag
		-->
			<div class="sidebar-wrapper">
				<div class="logo">
					<a href="#" class="simple-text logo-mini"><span class="nc-icon nc-send"></span></a>
					<a href="#" class="simple-text logo-normal">सहकारिता महासम्मेलन</a>
				</div>

				<ul class="nav">
				<?php
				if($_SESSION['usertype'] != "2"){
					echo'<li routerlinkactive="active" class="nav-item"><a class="nav-link" href="sammelen_reg_form.php"><i class="fa fa-exclamation-circle"></i><p>सहकारिता महासम्मेलन प्रपत्र</p></a></li>';
				}
					// print_r($_SESSION);
					//1. 2=DR  only view mode///
					
					if($_SESSION['usertype'] == "sadmin" ||$_SESSION['usertype']=="4" || $_SESSION['usertype']=="3"|| $_SESSION['usertype']=="2" ){
						
						echo'	<li routerlinkactive="active" class="nav-item"><a class="nav-link" href="sammelen_dashboard.php"><i class="fas fa-chart-pie"></i><p>डैशबोर्ड</p></a></li>';
					}
					
					// if($_SESSION['usertype']=="2" || $_SESSION['usertype'] == "sadmin"){
					if($_SESSION['usertype'] == "sadmin"){
						
						echo'	<li routerlinkactive="active" class="nav-item"><a class="nav-link" href="sammelen_report_dr.php"><i class="fas fa-chart-bar"></i><p>REPORTS</p></a></li>';
					}
					
					//2. 3=GM  create data and self approve  or district related approve and view mode///
					if($_SESSION['usertype']=="3" || $_SESSION['usertype'] == "sadmin"){
						
						echo'	<li routerlinkactive="active" class="nav-item"><a class="nav-link" href="sammelen_report_gm.php"><i class="fas fa-chart-bar"></i><p>REPORTS </p></a></li>';
						
					}
					
					//3. 4=/ AR  create data and self approve  or allot district view mode///
					if($_SESSION['usertype']=="4" || $_SESSION['usertype'] == "sadmin"){
						
						echo'	<li routerlinkactive="active" class="nav-item"><a class="nav-link" href="sammelen_report_ar.php"><i class="fas fa-chart-bar"></i><p>REPORTS </p></a></li>';
						
					}
					
					//4. 5=branch /   create data and self approve  or district related approve and view mode///
					if($_SESSION['usertype']=="5" ){
						
						echo'	<li routerlinkactive="active" class="nav-item"><a class="nav-link" href="sammelen_reg_report.php"><i class="fas fa-chart-bar"></i><p>REPORTS</p></a></li>';
					}
					
					if($_SESSION['usertype'] == "sadmin" ||$_SESSION['usertype']=="4" || $_SESSION['usertype']=="3" ){
						
						echo'	<li routerlinkactive="active" class="nav-item"><a class="nav-link" href="sammelen_reg_report.php"><i class="fas fa-chart-bar"></i><p>REPORTS</p></a></li>';
					}
					
					if($_SESSION['usertype'] == "sadmin"){
						
						echo'	<li routerlinkactive="active" class="nav-item"><a class="nav-link" href="master_users.php"><i class="fas fa-user"></i><p>USERS</p></a></li>';
					}
					?>
					
					
					
					<!-- <li><a class="nav-notification" href="#"><p><i class="fa fa-circle"></i>प्रपत्र को सुरक्षित करने के लिये Save Draft बटन का उपयोग करें</p></a>
					<li><a class="nav-notification" href="#"><p><i class="fa fa-circle"></i>पूर्णतया भरने के पश्चात ही सत्यापन के लिये प्रपत्र आगे प्रेषित करें</p></a>
					<li><a class="nav-notification" href="#"><p><i class="fa fa-circle"></i>सत्यापन के लिये प्रेषित होने के बाद प्रपत्र लाक हो जयेगा</p></a>
					<li><a class="nav-notification" href="#"><p><i class="fa fa-circle"></i>जियोलोकेशन के लिये मोबाईल का उपयोग करें एवम जी.पी.एस. लोकेशन चालू रखें </p></a>
					<li><a class="nav-notification" href="#"><p><i class="fa fa-circle"></i>सभी वाणिज्यिक आंकणे रुपये में भरें, हजार या लाख में नहीं</p></a>
					<li><a class="nav-notification" href="#"><p><i class="fa fa-circle"></i>सभी वाणिज्यिक आंकणों का संदर्भ पिछला वित्तिय वर्ष (2021-2022) होगा</p></a>
					<li><a class="nav-notification" href="#"><p><i class="fa fa-circle"></i>सभी माप (लम्बाई, चैड़ाई, इत्यादि) मीटर में लिखें</p></a>
					<li><a class="nav-notification" href="#"><p><i class="fa fa-circle"></i>सभी क्षेत्रफ़ल वर्ग मीटर में लिखें</p></a>
					<li><a class="nav-notification" href="#"><p><i class="fa fa-circle"></i>सभी क्षमता मेट्रिक टन में लिखें</p></a> -->
				</ul>
			</div>
		</div>
		<div class="main-panel">
			<nav class="navbar navbar-expand-lg ">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <a class="navbar-brand page-title" href="#" style="font-size:24px; color:#F83A3D"><?php echo 'सहकारिता महासम्मेलन';?></a>
                    </div>
                    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-bar burger-lines"></span>
                        <span class="navbar-toggler-bar burger-lines"></span>
                        <span class="navbar-toggler-bar burger-lines"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end mx-5">
                        <ul class="navbar-nav">
                           	<li class="nav-item dropdown"> 
                                <a class="" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#"><button class="btn btn-info"><i class="fa fa-user-lock"></i> <?php echo $_SESSION['username']; ?></button></a>&nbsp; 
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="#">Profile</a>
                                    <a class="dropdown-item" href="#">Activity Log</a>
                                    <div class="divider"></div>
                                    <a class="dropdown-item" href="signout.php"><i class="fas fa-sign-out-alt"></i>Signout</a>
                                </div>
                            </li>
						</ul>
                    </div>
                </div>
            </nav>
			<div class="content">
				<div class="container-fluid">
		
<?php	

	$time_track[] = microtime(true);
}
function page_footer_start() {
	
?>
				</div>
			</div>
			<footer class="footer">
				<div class="container-fluid">
					<nav class="pull-left">
						<ul>
							<li>
								<a href="#">
									Home
								</a>
							</li>
						</ul>
					</nav>
					<p class="copyright text-center">
                        ©
                        <script>
                            document.write(new Date().getFullYear())
                        </script>
                        <a href="http://www.weknowtech.in" target="_blank"><img src="images/logo-15.png" class="img-rounded"> Weknow Technologies</a>
                    </p>
				</div>
			</footer>
	    </div>
	</div>
<?php
}
function page_footer_end() {
	global $client_details;
?>
	<!--  Notifications Plugin    -->
    <script src="js/bootstrap-notify.js"></script>
    <script src="js/light-bootstrap-dashboard.js"></script>
    <script src="dataTables/datatables.min.js"></script>
    
    <!--  Google Maps Plugin    -->
   <!-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>-->


<script>
	$(document).ready(function() {
		// action on key up
		$(document).keyup(function(e) {
			if(e.which == 17) {
				isCtrl = false;
			}
		});
		$(document).keyup(function(e) {
			if(e.which == 18) {
				isAlt = false;
			}
		});
		// action on key down 17, 18, 82
		$(document).keydown(function(e) {
			if(e.which == 17) {
				isCtrl = true; 
			}
			if(e.which == 18) {
				isAlt = true; 
			}
			if(e.which == 191 && isCtrl) { 
				//console.log($("#shortcut_command"));
				$("#shortcut_command").focus();				
			} 
			if(e.which == 89 && isCtrl && isAlt) {
				if(form_type=='sale'){
					if($("#supplier_sno").val()==''){
						alert("Please select a customer.");
						$("#supplier").focus();
						return;
					}
					var current = $("#current").val();
					var part = "part_desc"+current;
					var parent_tr = $("input[name="+part+"_product]").closest('tr');
					if(parent_tr.css("background-color")=='rgb(255, 0, 0)'){
						parent_tr.css("background-color", "#cccccc");
						$("#part_desc"+current+"_return_flag").val("0");
					}
					else{
						parent_tr.css("background-color", "#FF0000");
						$("#part_desc"+current+"_return_flag").val("1");
					}
				}
			} 
		});

	});

	
	</script>
	<!-- Default Statcounter code for UPCOD https://upcod.in/
		-->
		<script type="text/javascript">
		var sc_project=12961757; 
		var sc_invisible=1; 
		var sc_security="0a24b0c2"; 
		</script>
		<script type="text/javascript"
		src="https://www.statcounter.com/counter/counter.js"
		async></script>
		<noscript><div class="statcounter"><a title="Web Analytics"
		href="https://statcounter.com/" target="_blank"><img
		class="statcounter"
		src="https://c.statcounter.com/12961757/0/0a24b0c2/1/"
		alt="Web Analytics"
		referrerPolicy="no-referrer-when-downgrade"></a></div></noscript>
		<!-- End of Statcounter Code -->
<?php
	echo '</body>
</html>';
}

function pagecount($sql, $script, $active){
	$result = execute_query($sql);
	$count = mysqli_num_rows($result);
	$page = ceil($count/50);
	if($active>1 && $active<$page){
		$print = '<a href="'.$script.'">&lt;&lt;</a> | <a href="'.$script.'?pg='.($active-1).'"> &lt;</a> |';
	}
	else{
		$print = '';
	}
	for($i=1;$i<=$page;$i++){
		if($active==$i){
			$print .= $i.' | ';
		}
		else{
			$print .= '<a href="'.$script.'?pg='.$i.'">'.$i.'</a> | ';
		}
	}
	return $print;
}

function randomstring(){

	$length=16;

	$chars='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

	$char_length=(strlen($chars)-1);

	$string=$chars[rand(0,$char_length)];

	for($i=1;$i<$length;$i=strlen($string)){

		$r=$chars[rand(0,$char_length)];

		if($r!=$string[$i-1]){

			$string .= $r;

		}

	}

	return $string;	

}

function randompassword(){
	$length=8;
	$chars='abcdefghijklmnopqrstuvwxyz0123456789';
	$char_length=(strlen($chars)-1);
	$string=$chars[rand(0,$char_length)];
	for($i=1;$i<$length;$i=strlen($string)){
		$r=$chars[rand(0,$char_length)];
		if($r!=$string[$i-1]){
			$string .= $r;
		}
	}
	return $string;	
}

function randomnumber(){
	$length=6;
	$chars='0123456789';
	$char_length=(strlen($chars)-1);
	$string=$chars[rand(0,$char_length)];
	for($i=1;$i<$length;$i=strlen($string)){
		$r=$chars[rand(0,$char_length)];
		if($r!=$string[$i-1]){
			$string .= $r;
		}
	}
	return $string;	
}

function send_mail($customer_name, $mailid, $msg, $subject){
	$msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<title>Backup2mail status</title>
			<style type="text/css">body { background: #000; color: #0f0; font-family: \'Courier New\', Courier; }</style>
		</head>
		<body><h3>'.$msg.'</h3></body></html>';

	$email = new PHPMailer();
	$email->From      = 'info@weknowtech.in';
	$email->FromName  = 'Weknow Technologies';
	$email->Subject   = $subject;
	$email->Body      = $msg;
	$email->AddAddress( $mailid, $customer_name);

	$email->isHTML(true);

	$email->Send();
	
}


function logout(){

	date_default_timezone_set('Asia/Calcutta');

	$_SESSION['enddate']=date('y-m-d');

	$time = localtime();

	$time = $time[2].':'.$time[1].':'.$time[0];

	$_SESSION['endtime']=$time;

	$sql = "update session set s_end_time='".$_SESSION['endtime']."' where s_id='".$_SESSION['id']."' and user='".$_SESSION['username']."'";

	execute_query($sql);

	session_destroy();

	session_unset();

	session_write_close();
	
	header("Location: index.php");

	echo '<div id="container" class="ltr">

	<center><h2>Logged Out Succesfully. <a href="index.php">Click Here</a> to continue or close this window</center>

	</div>';

}


function sethistory(){
	// make sure the container array exists
	// the paranoid will also check here that sessions are even being used 
	if(!isset($_SESSION['history'])){
	  $_SESSION['history'] = array();
	}
	// make an easier to use reference to the container
	$h =& $_SESSION['history'];
	// get the referring page and this page
	// we need to construct matching strings
	// put the referring page straight in the array
	if(!isset($_SERVER['HTTP_REFERER'])){
		$_SERVER['HTTP_REFERER']='';
	}
	$h[] = $from = $_SERVER['HTTP_REFERER']; 
	$here = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	// find out how many elements we have
	$count = count($h);
	//don't waste memory - trim off old entries
	while($count>20){
		array_shift($h);
		$count--;
	}
	// don't want to get stuck in a reference loop
	// this can be falsely triggered by pages that link to each other 
	// but hopefully rarely and the button will still behave rationally
	// also catches use of the browser 'Back' button/key
	// remove last two items to rewind history state
	while($count > 1 && $h[$count-2] == $here){
		array_pop($h);
		array_pop($h);
		$count -= 2; 
	}
	// don't want to get stuck on one page either
	// for pages that process themselves or are returned to after process script
	// remove last item to rewind history state
	while($count > 0 && $h[$count-1] == $here){
		array_pop($h);
		$count--;
	}
	// all done
	return;
}

function returnlink($defaulturl='index.php', $override=false){
	// initialise variables
	$c = 0;
	$url = '';
	// check that the history container exists
	// if so check it has something in it and set $url
	if(isset($_SESSION['history'])){
		$c = count($_SESSION['history']);
	    $url = ($c > 0) ? $_SESSION['history'][$c-1] : '';
    } 
	// check for use $defaulturl conditions
	// $c may still be > 0 if the page was accessed directly
	// but $url will be blank
	if($override || $c == 0 || $url == ''){
		return $defaulturl;
	}
	else{
		return $url;  
	} 
}

function logvalidate($fileid=''){
	if(basename($_SERVER['PHP_SELF'])=='index.php'){
		return true;
	}
	if(!isset($_SESSION['session_id'])){
		header("Location: index.php");
	}
	$current_time = time();
	$sql = 'select * from general_settings where `desc`="session_timeout"';
	$timeout = mysqli_fetch_array(execute_query($sql));
	if($timeout['rate']>0){
		$sql = 'select * from session where s_id="'.$_SESSION['session_id'].'"';
		$session = mysqli_fetch_array(execute_query($sql));
		$timeout = $timeout['rate']*60;
		$difference = $current_time-$session['last_active'];
		if($difference>$timeout){
			logout();
		}
	}
	if($_SESSION['username']=='sadmin'){
		$sql = 'update session set last_active="'.time().'" where s_id="'.$_SESSION['session_id'].'"';
		execute_query($sql);
		return true;
	}
	$sql = 'select * from navigation where sno="'.$fileid.'"';
	$result_parent = execute_query($sql);
	$row_parent = mysqli_fetch_array($result_parent);
	$row_parent['parent'] = isset($row_parent['parent'])?$row_parent['parent']:'';
	if($row_parent['parent']=="P"){
		$sql = 'update session set last_active="'.time().'" where s_id="'.$_SESSION['session_id'].'"';
		execute_query($sql);
		return true;
	}
	if($fileid!='index.php' && $fileid!=''){
		$sql = 'select * from user_access where user_id="'.$_SESSION['usertype'].'" and file_name="'.$fileid.'"';
		//echo $sql;
		$result_access = execute_query($sql);
		if(mysqli_num_rows($result_access)!=1){
			header("Location: index.php");
		}
	}
	$sql = 'update session set last_active="'.time().'" where s_id="'.$_SESSION['session_id'].'"';
	//echo $sql;
	execute_query($sql);
}


function int_to_words($x){
	$nwords = array("zero", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen", "twenty", 30 => "thirty", 40 => "forty",	50 => "fifty", 60 => "sixty", 70 => "seventy", 80 => "eighty",	90 => "ninety" );
	if(!is_numeric($x)){
		$w = '#';
	}
	else if(fmod($x, 1) != 0){
		$w = '#';
	}
	else{
		if($x < 0){
			$w = 'minus ';
			$x = -$x;
		}
		else{
			$w = '';
		}
		if($x < 21){
			$w .= $nwords[$x];
		}
		else if($x < 100){
			$w .= $nwords[10 * floor($x/10)];
			$r = fmod($x, 10);
			if($r > 0){
				$w .= '-'. $nwords[$r];
			}
		} 
		else if($x < 1000){
			$w .= $nwords[floor($x/100)] .' hundred';
			$r = fmod($x, 100);
			if($r > 0){
				$w .= ' and '. int_to_words($r);
			}
		} 
		else if($x < 100000){
			$w .= int_to_words(floor($x/1000)) .' thousand';
			$r = fmod($x, 1000);
			if($r > 0){
				$w .= ' ';
				if($r < 100){
					$w .= 'and ';
				}
				$w .= int_to_words($r);
			}
		} 
		else {
			$w .= int_to_words(floor($x/100000)) .' lakh';
			$r = fmod($x, 100000);
			if($r > 0){
				$w .= ' ';
				if($r < 100){
					$word .= 'and ';
				}
				$w .= int_to_words($r);
			}
		}
	}
	return $w;
}

function amount_format($amount){
	$formatter = new NumberFormatter('en_IN',  NumberFormatter::CURRENCY);
	$amount =  $formatter->formatCurrency($amount, 'INR');	
	return $amount;
	
}

function add_enquiry_customer($name, $fname, $address, $address2, $city, $state, $zip, $country, $mobile, $tin, $aadhar='', $fname1='', $mob_2='', $mob_3='', $mob_4='', $type='', $cus_occupation='', $dob='', $opening_balance=0, $category='', $parent=0){
	$name = trim($name);
	$address = trim($address);
	$address2 = trim($address2);
	$city = trim($city);
	$state = trim($state);
	$zip = trim($zip);
	$country = trim($country);
	$mobile = trim($mobile);
	$tin = trim($tin);
	
	$sql = 'select * from general_settings where `desc`="duplicate_mobile"';
	$duplicate_mobile = mysqli_fetch_array(execute_query($sql));
	$duplicate_mobile = $duplicate_mobile['rate'];

	if($duplicate_mobile==0){
		if($mobile!=''){
			$sql = 'select * from enquiry_customer where mobile="'.$mobile.'" or mob_2="'.$mobile.'" or mob_3="'.$mobile.'" or mob_4="'.$mobile.'"';
			$result = execute_query($sql);
			if(mysqli_num_rows($result)!=0){
				$supplier = mysqli_fetch_array($result);
				return $supplier['sno'];
			}
		}
	}
	
	if($tin!=''){
		$sql = 'select * from enquiry_customer where tin="'.$tin.'"';
		$result = execute_query($sql);
		if(mysqli_num_rows($result)!=0){
			$supplier = mysqli_fetch_array($result);
			return $supplier['sno'];
		}
	}
	if($name!=''){
		$sql = 'select * from enquiry_customer where cus_name="'.$name.'"';
		$result = execute_query($sql);
		if(mysqli_num_rows($result)!=0){
			$supplier = mysqli_fetch_array($result);
			return $supplier['sno'];
		}
	}
	$sql = 'insert into enquiry_customer (cus_name, fname, address, add_2, city, state, zipcode, country, mobile, mob_2, mob_3, mob_4, cus_type, cus_occupation, dob, opening_balance, tin, adhar_no, category, parent) values ("'.$name.'", "'.$fname.'", "'.$address.'", "'.$address2.'", "'.$city.'", "'.$state.'", "'.$zip.'", "'.$country.'", "'.$mobile.'", "'.$mob_2.'", "'.$mob_3.'", "'.$mob_4.'", "'.$type.'", "'.$cus_occupation.'", "'.$dob.'", "'.$opening_balance.'", "'.$tin.'", "'.$aadhar.'", "'.$category.'", "'.$parent.'")';
	execute_query($sql);
	return insert_id();
}

?>
<?php
$time_track = array();
$time_track[] = microtime(true);
set_time_limit(0);
error_reporting(E_ALL);
//error_reporting(0);
/*if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}*/
session_cache_limiter('nocache');
session_start();

include("settings_dbase.php");

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
		$title = $file['link_description'];
		$GLOBALS['title'] = 'सर्वेक्षण प्रपत्र';
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
    <link href="fa/css/all.min.css" rel="stylesheet" media="all">
    <script src="fa/js/fontawesome.min.js"></script>
    <link href="css/pe-icon-7-stroke.css" rel="stylesheet"  media="all" />

    <!-- Bootstrap core CSS     -->
    <link rel="stylesheet" href="css/bootstrap.min.css" media="all">
	<link rel="stylesheet" href="css/bootstrap-theme.min.css" media="all">
	<link rel="stylesheet" href="dataTables/datatables.min.css" media="all">
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
    <link href="css/animate.min.css" rel="stylesheet" media="all"/>

    
    <link href="css/jquery-ui.css" rel="stylesheet" media="all"/>
	<!--
	<!--<link href="css/component.css" rel="stylesheet" type="text/css" media="all" />
	<link href="css/jcarousel.css" rel="stylesheet" type="text/css" media="all" />-->
	<link href="css/styles.css" rel="stylesheet" type="text/css" media="all" />
	<link href="css/pagination.css" rel="stylesheet" type="text/css" media="all" />
	<link href="css/jquery.multiselect.css" rel="stylesheet" type="text/css" media="all" />
	<!--  Light Bootstrap Table core CSS    -->
	<link href="css/light-bootstrap-dashboard.css?v=1.4.0" rel="stylesheet" media="all"/>


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
					<a href="#" class="simple-text  logo-normal">सर्वेक्षण प्रपत्र&trade;</a>
				</div>

				<ul class="nav">
					<li routerlinkactive="active" class="nav-item active"><a class="nav-link" href="index.php"><i class="fa fa-chart-pie"></i><p>डैशबोर्ड</p></a>
				<?php
					$sql = 'select * from navigation where (parent is null or parent="" or parent="P") and hyper_link!="index.php" order by abs(sort_no), sub_parent, link_description';
					$result = execute_query($sql);
					$sub_parent = '';
					while($row = mysqli_fetch_array($result)){
						if($row['hyper_link']==basename($_SERVER['PHP_SELF'])){
							$active = ' active';
						}
						else{
							$active = '';
						}
						if($_SESSION['username']!='sadmin'){
							if($row['parent']=='P'){
								$sql = 'select group_concat(sno) as sno from navigation where parent="'.$row['sno'].'" order by abs(sort_no), sub_parent, link_description';
								$row_sub = mysqli_fetch_assoc(execute_query($sql));
								
								$sql = 'select * from user_access where user_id="'.$_SESSION['usertype'].'" and file_name in ('.$row_sub['sno'].')';
								//echo $sql.'<br><br>';
								$result_child_count = execute_query($sql);
								
								if(mysqli_num_rows($result_child_count)!=0){
									echo '
									<li routerlinkactive="active" class="nav-item'.$active.'">
										<!----><a data-toggle="collapse" data-target="#parent'.$row['sno'].'" class="nav-link" href="#parent'.$row['sno'].'" ><i class="'.$row['icon_image'].'"></i><p>'.$row['link_description'].'<b class="caret"></b></p></a>
										<!---->
										<div class="collapse" id="parent'.$row['sno'].'">
											<ul class="nav">';

									$sql = 'select * from navigation where parent="'.$row['sno'].'" order by abs(sort_no), sub_parent, link_description';
									$result_sub = execute_query($sql);
									while($row_sub = mysqli_fetch_assoc($result_sub)){
										$sql = 'select * from user_access where user_id="'.$_SESSION['usertype'].'" and file_name="'.$row_sub['sno'].'"';
										//echo $sql;
										$result_access = execute_query($sql);
										if(mysqli_num_rows($result_access)==1){
											echo '<li routerlinkactive="active'.$active.'" class="nav-item"><a class="nav-link" href="'.$row_sub['hyper_link'].'"><i class="'.$row_sub['icon_image'].'" style="font-size:20px; margin-left:15px; margin-right:0px;"></i><span class="sidebar-mini"></span><span class="sidebar-normal">'.$row_sub['link_description'].'</span></a>
											</li>';
										}
									}
									echo '
											</ul>
										</div>
										<!---->
									</li>';
								}
								
							}
							else{
								$sql = 'select * from user_access where user_id="'.$_SESSION['usertype'].'" and file_name="'.$row['sno'].'"';
								//echo $sql;
								$result_access = execute_query($sql);
								if(mysqli_num_rows($result_access)==1){
									echo '<li routerlinkactive="active" class="nav-item'.$active.'"><a class="nav-link" href="'.$row['hyper_link'].'"><i class="'.$row['icon_image'].'"></i><p>'.$row['link_description'].'</p></a></li>';						
								}
							}
						}
						else{
							if($row['parent']!="P"){
								echo '<li routerlinkactive="active" class="nav-item'.$active.'"><a class="nav-link" href="'.$row['hyper_link'].'"><i class="'.$row['icon_image'].'"></i><p>'.$row['link_description'].'</p></a></li>';						
							}
							else{
								echo '
								<li routerlinkactive="active" class="nav-item'.$active.'">
									<!----><a data-toggle="collapse" data-target="#parent'.$row['sno'].'" class="nav-link" href="#parent'.$row['sno'].'" ><i class="'.$row['icon_image'].'"></i><p>'.$row['link_description'].'<b class="caret"></b></p></a>
									<!---->
									<div class="collapse" id="parent'.$row['sno'].'">
										<ul class="nav">';

								$sql = 'select * from navigation where parent="'.$row['sno'].'" order by abs(sort_no), sub_parent, link_description';
								$result_sub = execute_query($sql);
								while($row_sub = mysqli_fetch_assoc($result_sub)){
									echo '<li routerlinkactive="active'.$active.'" class="nav-item"><a class="nav-link" href="'.$row_sub['hyper_link'].'"><i class="'.$row_sub['icon_image'].'" style="font-size:20px; margin-left:15px; margin-right:0px;"></i><span class="sidebar-mini"></span><span class="sidebar-normal">'.$row_sub['link_description'].'</span></a>
											</li>';
								}
								echo '
										</ul>
									</div>
									<!---->
								</li>';
							}	

						}
					}

				?>
				</ul>
			</div>
		</div>
		<div class="main-panel">
			<nav class="navbar navbar-expand-lg ">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <a class="navbar-brand page-title" href="#" style="font-size:24px; color:#F83A3D"><?php if($id=='super'){echo '<img src="images/upcoop_logo.jpg" style="height:50px; border-radius:50%; border: 2px solid; margin-right:15px;">सहकारिता विभाग सर्वेक्षण, उत्तर प्रदेश';}else{echo $GLOBALS['title'];} ?></a>
                    </div>
                    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-bar burger-lines"></span>
                        <span class="navbar-toggler-bar burger-lines"></span>
                        <span class="navbar-toggler-bar burger-lines"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end">
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

function top_performer($data){
	$percent = array();
	
	usort($data, function($a, $b) {
		return $a['filled_percent'] - $b['filled_percent'];
		if($a['filled_percent']==$b['filled_percent']) return 0;
    	return $a['filled_percent'] < $b['filled_percent']?1:-1;
	});
	$last_five = array_slice($data, 0, 10, true);
	$top_five = array_slice($data, 65, 10, true);
	$final_array = array("top"=>$top_five, "last"=>$last_five);
	return $final_array;
}

function estimate($society_id){
	$sql = 'select survey_invoice.sno as sno, survey_invoice.mobile_number as mobile_number, survey_invoice.approval_status as approval_status, survey_invoice.creation_time as creation_time, col1, col2, col3, col4, col5, col6, otp_verify, latitude, longitude, mobile_number, `survey_invoice_sec_5`.`survey_id`, `survey_invoice_sec_5`.`building_status`, `survey_invoice_sec_5`.`building_status_remarks`, `survey_invoice_sec_5`.`floor_length`, `survey_invoice_sec_5`.`floor_width`, `survey_invoice_sec_5`.`floor_image`, `survey_invoice_sec_5`.`wall_length`, `survey_invoice_sec_5`.`wall_width`, `survey_invoice_sec_5`.`wall_image`, `survey_invoice_sec_5`.`paint_length`, `survey_invoice_sec_5`.`paint_width`, `survey_invoice_sec_5`.`paint_image`, `survey_invoice_sec_5`.`roof_length`, `survey_invoice_sec_5`.`roof_width`, `survey_invoice_sec_5`.`roof_image`, `survey_invoice_sec_5`.`washroom_floor`, `survey_invoice_sec_5`.`washroom_plaster`, `survey_invoice_sec_5`.`washroom_roof`, `survey_invoice_sec_5`.`washroom_seat`, `survey_invoice_sec_5`.`washroom_plumbing`, `survey_invoice_sec_5`.`doors`, `survey_invoice_sec_5`.`windows`, `survey_invoice_sec_5`.`plaster_wall`, `survey_invoice_sec_5`.`plaster_roof`, `survey_invoice_sec_5`.`others`, `survey_invoice_sec_5`.`status` from survey_invoice left join test2 on test2.sno = survey_invoice.society_id  left join survey_invoice_sec_5 on survey_invoice_sec_5.survey_id = survey_invoice.sno where col2 !="DivisionCodeText" and `building_status` ="repairable" and `approval_status` ="4" and survey_invoice.sno="'.$society_id.'"';
	$row = mysqli_fetch_assoc(execute_query($sql));
	
	$row['floor_length'] = floatval($row['floor_length']);
	$row['floor_width'] = floatval($row['floor_width']);
	$row['wall_length'] = floatval($row['wall_length']);
	$row['wall_width'] = floatval($row['wall_width']);
	$row['paint_length'] = floatval($row['paint_length']);
	$row['paint_width'] = floatval($row['paint_width']);
	$row['roof_length'] = floatval($row['roof_length']);
	$row['roof_width'] = floatval($row['roof_width']);
	$row['washroom_floor'] = floatval($row['washroom_floor']);
	$row['washroom_plaster'] = floatval($row['washroom_plaster']);
	$row['washroom_roof'] = floatval($row['washroom_roof']);
	$row['washroom_seat'] = floatval($row['washroom_seat']);
	$row['washroom_plumbing'] = floatval($row['washroom_plumbing']);
	$row['plaster_wall'] = floatval($row['plaster_wall']);
	$row['plaster_roof'] = floatval($row['plaster_roof']);
	
	$tot_labour_cess=0;
	$tot_agency_centage=0;
	$tot_gst=0;
	$tot_washroom=0;
	$tot_washroom_subtotal=0;
	$tot_value=0;

	$grand_tot_labour_cess=0;
	$grand_tot_agency_centage=0;
	$grand_tot_gst=0;
	$grand_tot_washroom=0;
	$grand_tot_washroom_subtotal=0;
	$grand_tot_value=0;

	$repair_floor=3;
	$repair_wall=4;
	$repair_paint=8;
	$repair_roof=1;
	$repair_plaster_wall=6;
	$repair_plaster_roof=7;					

	$sql = 'select * from survey_rates where sno='.$repair_floor;
	$repair_floor = mysqli_fetch_assoc(execute_query($sql));

	$sql = 'select * from survey_rates where sno='.$repair_wall;
	$repair_wall = mysqli_fetch_assoc(execute_query($sql));
	$repair_wall['less_work'] = round($repair_wall['less_work'],2);
	$repair_wall['labour_cess'] = round($repair_wall['labour_cess'],2);
	$repair_wall['agency_centage'] = round($repair_wall['agency_centage'],2);
	$repair_wall['gst'] = round($repair_wall['gst'],2);
	$repair_wall['grand_total'] = round($repair_wall['grand_total'],2);

	$sql = 'select * from survey_rates where sno='.$repair_paint;
	$repair_paint = mysqli_fetch_assoc(execute_query($sql));

	$sql = 'select * from survey_rates where sno='.$repair_roof;
	$repair_roof = mysqli_fetch_assoc(execute_query($sql));

	$sql = 'select * from survey_rates where sno='.$repair_plaster_wall;
	$repair_plaster_wall = mysqli_fetch_assoc(execute_query($sql));

	$sql = 'select * from survey_rates where sno='.$repair_plaster_roof;
	$repair_plaster_roof = mysqli_fetch_assoc(execute_query($sql));
	
	$area_floor = $row['floor_length']*$row['floor_width'];
	$tot_floor_cess = ($repair_floor['labour_cess']*$area_floor);
	$tot_floor_centage = ($repair_floor['agency_centage']*$area_floor);
	$tot_floor_gst = ($repair_floor['gst']*$area_floor);
	$tot_floor_value = ($repair_floor['grand_total']*$area_floor);
	
	
	$area_wall = ($row['wall_length']*$row['wall_width'])*0.23; 
	$tot_wall_cess = ($repair_wall['labour_cess']*$area_wall);
	$tot_wall_centage = ($repair_wall['agency_centage']*$area_wall);
	$tot_wall_gst = ($repair_wall['gst']*$area_wall);
	$tot_wall_value = ($repair_wall['grand_total']*$area_wall);
	
	$area_wall = 0;
	$tot_wall_cess = 0;
	$tot_wall_centage = 0;
	$tot_wall_gst = 0;
	$tot_wall_value = 0;
	
	$area_paint = $row['paint_length']*$row['paint_width']; 
	$tot_paint_cess = ($repair_paint['labour_cess']*$area_paint);
	$tot_paint_centage = ($repair_paint['agency_centage']*$area_paint);
	$tot_paint_gst = ($repair_paint['gst']*$area_paint);
	$tot_paint_value = ($repair_paint['grand_total']*$area_paint);
	
	$area_roof = $row['roof_length']*$row['roof_width']; 
	$tot_roof_cess = ($repair_roof['labour_cess']*$area_roof);
	$tot_roof_centage = ($repair_roof['agency_centage']*$area_roof);
	$tot_roof_gst = ($repair_roof['gst']*$area_roof);
	$tot_roof_value = ($repair_roof['grand_total']*$area_roof);	
	
	$area_plaster_wall = $row['plaster_wall'];
	$tot_plaster_wall_cess = ($repair_plaster_wall['labour_cess']*$area_plaster_wall);
	$tot_plaster_wall_centage = ($repair_plaster_wall['agency_centage']*$area_plaster_wall);
	$tot_plaster_wall_gst = ($repair_plaster_wall['gst']*$area_plaster_wall);
	$tot_plaster_wall_value = ($repair_plaster_wall['grand_total']*$area_plaster_wall);
	
	$area_plaster_roof = $row['plaster_roof'];
	$tot_plaster_roof_cess = ($repair_plaster_roof['labour_cess']*$area_plaster_roof);
	$tot_plaster_roof_centage = ($repair_plaster_roof['agency_centage']*$area_plaster_roof);
	$tot_plaster_roof_gst = ($repair_plaster_roof['gst']*$area_plaster_roof);
	$tot_plaster_roof_value = ($repair_plaster_roof['grand_total']*$area_plaster_roof);
	
	$tot_washroom = $row['washroom_floor']+$row['washroom_plaster']+$row['washroom_roof']+$row['washroom_seat']+$row['washroom_plumbing'];
	$tot_washroom_cess = $tot_washroom*0.01;
	$tot_washroom_centage = $tot_washroom*0.125;
	$tot_washroom_gst = $tot_washroom*0.18;
	$tot_washroom_value = $tot_washroom+$tot_washroom_cess+$tot_washroom_centage+$tot_washroom_gst;
	
	$tot_estimate = $tot_floor_value+$tot_wall_value+$tot_paint_value+$tot_roof_value+$tot_plaster_wall_value+$tot_plaster_roof_value+$tot_washroom_value;
	$estimate = array("floor_area" =>$area_floor, "floor_rate" =>$repair_floor['less_work'], "floor_cess" =>$repair_floor['labour_cess'], "floor_centage" =>$repair_floor['agency_centage'], "floor_gst" =>$repair_floor['gst'], "floor_net_rate" =>$repair_floor['grand_total'], "floor_total" =>$tot_floor_value, "wall_area" =>$area_wall, "wall_rate" =>$repair_wall['less_work'], "wall_cess" =>$repair_wall['labour_cess'], "wall_centage" =>$repair_wall['agency_centage'], "wall_gst" =>$repair_wall['gst'], "wall_net_rate" =>$repair_wall['grand_total'], "wall_total" =>$tot_wall_value, "paint_area" =>$area_paint, "paint_rate" =>$repair_paint['less_work'], "paint_cess" =>$repair_paint['labour_cess'], "paint_centage" =>$repair_paint['agency_centage'], "paint_gst" =>$repair_paint['gst'], "paint_net_rate" =>$repair_paint['grand_total'], "paint_total" =>$tot_paint_value, "roof_area" =>$area_roof, "roof_rate" =>$repair_roof['less_work'], "roof_cess" =>$repair_roof['labour_cess'], "roof_centage" =>$repair_roof['agency_centage'], "roof_gst" =>$repair_roof['gst'], "roof_net_rate" =>$repair_roof['grand_total'], "roof_total" =>$tot_roof_value, "plaster_wall_area" =>$area_plaster_wall, "plaster_wall_rate" =>$repair_plaster_wall['less_work'], "plaster_wall_cess" =>$repair_plaster_wall['labour_cess'], "plaster_wall_centage" =>$repair_plaster_wall['agency_centage'], "plaster_wall_gst" =>$repair_plaster_wall['gst'], "plaster_wall_net_rate" =>$repair_plaster_wall['grand_total'], "plaster_wall_total" =>$tot_plaster_wall_value, "plaster_roof_area" =>$area_plaster_roof, "plaster_roof_rate" =>$repair_plaster_roof['less_work'], "plaster_roof_cess" =>$repair_plaster_roof['labour_cess'], "plaster_roof_centage" =>$repair_plaster_roof['agency_centage'], "plaster_roof_gst" =>$repair_plaster_roof['gst'], "plaster_roof_net_rate" =>$repair_plaster_roof['grand_total'], "plaster_roof_total" =>$tot_plaster_roof_value, "washroom_total" =>$tot_washroom, "washroom_cess" =>$tot_washroom_cess, "washroom_centage" =>$tot_washroom_centage, "washroom_gst" =>$tot_washroom_gst, "washroom_net_rate" =>$tot_washroom_value, "washroom_grand_total" =>$tot_washroom_value, "grand_total_a_b" =>round($tot_estimate,2));
	return $estimate;
}

function summary($district=''){
	/*$sql = 'SELECT 
	count(*) c, 
	count(if(litigation="yes", 1, NULL)) as litigation, 
	count(if(liquidation="yes", 1, NULL)) as liquidation,  
	count(if(society_building_ownership="own", 1, NULL)) as society_building_ownership, 
	sum(active_members) as active_members, 
	sum(inactive_members) as inactive_members, 
	sum(total_business) as total_business, 
	count(if(last_year_profit_loss="profit", 1, NULL)) as profit_societies, 
	count(if(pds="yes", 1, NULL)) as pds, 
	count(if(secretary="yes", 1, null)) as secretary, 
	count(if(secretary_status="regular", 1, null)) as secretary_status, 
	count(if(secretary_cader="cader", 1, null)) as secretary_cader, 
	count(if(accountant!="no", 1, null)) as accountant,
	count(if(assistant_accountant!="no", 1, null)) as assistant_accountant,
	count(if(seller!="no", 1, null)) as seller,
	count(if(support_staff!="no", 1, null)) as support_staff,
	count(if(guard!="no", 1, null)) as guard,
	count(if(computer_operator!="no", 1, null)) as computer_operator
	FROM `survey_invoice` left join survey_invoice_sec_2_1 on survey_invoice_sec_2_1.survey_id = survey_invoice.sno left join survey_invoice_sec_2_2 on survey_invoice_sec_2_2.survey_id = survey_invoice.sno where approval_status=4';*/
	
	$sql = 'SELECT 
	division_name,
	col1,
	district_name,
	col2,
	count(*) c,
	count(if(litigation="yes", 1, NULL)) as litigation, 
	count(if(liquidation="yes", 1, NULL)) as liquidation,  
	count(if(society_building_ownership="own", 1, NULL)) as society_building_ownership, 
	sum(active_members) as active_members, 
	sum(inactive_members) as inactive_members 
	FROM `survey_invoice` 
	left join test2 on test2.sno = survey_invoice.society_id
	left join master_district on master_district.sno = col2
	left join master_division on master_division.sno = col1
	where approval_status=4 and  (test2.status!=1 or test2.status is null)
	group by col2
	 order by division_name, district_name';
	
	$result = execute_query($sql);
	$data = array();
	$tot_district_name = 0;
	$tot_c  = 0;
	$tot_litigation  = 0;
	$tot_liquidation   = 0;
	$tot_society_building_ownership  = 0;
	$tot_active_members  = 0;
	$tot_inactive_members  = 0;
	$tot_total_business  = 0;
	$tot_profit_societies  = 0;
	$tot_pds  = 0;
	$tot_secretary  = 0;
	$tot_secretary_count  = 0;
	$tot_secretary_cader  = 0;
	$tot_secretary_non_cader  = 0;
	$tot_secretary_supervisor  = 0;
	$tot_accountant = 0;
	$tot_assistant_accountant = 0;
	$tot_seller = 0;
	$tot_support_staff = 0;
	$tot_guard = 0;
	$tot_computer_operator = 0;
	$tot_accountant_count = 0;
	$tot_assistant_accountant_count = 0;
	$tot_seller_count = 0;
	$tot_support_staff_count = 0;
	$tot_guard_count = 0;
	$tot_computer_operator_count = 0;
	

	while($row = mysqli_fetch_assoc($result)){
		$sql = 'select
		investment,
		loan,
		msp,
		msp_comm,
		subscribers,
		pds,
		total_business,
		last_year_profit_loss,
		last_year_pl_amount,
		seq_year_profit_loss,
		seq_year_pl_amount,
		financial_audit_year,
		balance_sheet_year,
		construction_status,
		approach_road,
		electric_connection,
		electric_connection_working,
		internet_connectivity,
		water_govt_tap,
		water_tank,
		water_hand_pump,
		sum(abs(total_business)) as total_business, 
		count(if(last_year_profit_loss="profit", 1, NULL)) as profit_societies, 
		count(if(pds="yes", 1, NULL)) as pds
		from survey_invoice_sec_2_1 
		left join survey_invoice on survey_invoice.sno = survey_id 
		left join test2 on test2.sno = society_id 
		where approval_status=4 and  (test2.status!=1 or test2.status is null) and col2='.$row['col2'];
		//echo $sql.'<br>';
		$result_2_1 = execute_query($sql);
		if(mysqli_num_rows($result_2_1)!=0){
			$row_2_1 = mysqli_fetch_assoc($result_2_1);
			$row = array_merge($row, $row_2_1);
		}
		$sql = 'select
		count(if(accountant!="no", 1, null)) as accountant,
		sum(abs(accountant)) as accountant_count,
		count(if(assistant_accountant!="no", 1, null)) as assistant_accountant,
		sum(abs(assistant_accountant)) as assistant_accountant_count,
		count(if(seller!="no", 1, null)) as seller,
		sum(abs(seller)) as seller_count,
		count(if(support_staff!="no", 1, null)) as support_staff,
		sum(abs(support_staff)) as support_staff_count,
		count(if(guard!="no", 1, null)) as guard,
		sum(abs(guard)) as guard_count,
		count(if(computer_operator!="no", 1, null)) as computer_operator,
		sum(abs(computer_operator)) as computer_operator_count
		from survey_invoice_sec_2_2 
		left join survey_invoice on survey_invoice.sno = survey_id 
		left join test2 on test2.sno = society_id 
		where approval_status=4 and  (test2.status!=1 or test2.status is null) and col2='.$row['col2'];
		$result_2_2 = execute_query($sql);
		if(mysqli_num_rows($result_2_2)!=0){
			$row_2_2 = mysqli_fetch_assoc($result_2_2);
			$row = array_merge($row, $row_2_2);
		}
		
		$sql = 'SELECT 
		secretary, 
		secretary_status, 
		secretary_cader, 
		count( * ) c
		FROM `survey_invoice_sec_2_2`
		LEFT JOIN survey_invoice ON survey_invoice.sno = survey_id
		left join test2 on test2.sno = society_id 
		WHERE approval_status =4 and  (test2.status!=1 or test2.status is null) and col2='.$row['col2'].'
		GROUP BY secretary, secretary_status, secretary_cader';
		$result_secretary = execute_query($sql);
		$secretary_no=0;
		$secretary_yes=0;
		$secretary_yes_cader=0;
		$secretary_yes_non_cader=0;
		$secretary_yes_supervisor=0;
		while($row_secretary = mysqli_fetch_assoc($result_secretary)){
			if($row_secretary['secretary']=='no'){
				$secretary_no = $row_secretary['c'];
			}
			else{
				$secretary_yes += $row_secretary['c'];
				if($row_secretary['secretary_cader']=='cader'){
					$secretary_yes_cader += $row_secretary['c'];
				}
				elseif($row_secretary['secretary_cader']=='non_cader'){
					$secretary_yes_non_cader += $row_secretary['c'];
				}
				else{
					$secretary_yes_supervisor += $row_secretary['c'];
				}
			}
		}
		
		
		/*left join survey_invoice_sec_2_1 on survey_invoice_sec_2_1.survey_id = survey_invoice.sno 
	left join survey_invoice_sec_2_2 on survey_invoice_sec_2_2.survey_id = survey_invoice.sno 
	*/
		$data[] = array('division_name' => $row['division_name'], 'division_id'=>$row['col1'], 'district_name' => $row['district_name'], 'district_id'=>$row['col2'], 'c' => $row['c'], 'litigation' => $row['litigation'], 'liquidation' => $row['liquidation'], 'society_building_ownership' => $row['society_building_ownership'], 'active_members' => $row['active_members'], 'inactive_members' => $row['inactive_members'], 'total_business' => $row['total_business'], 'profit_societies' => $row['profit_societies'], 'pds' => $row['pds'], 'secretary' => $secretary_yes, 'secretary_count' => $secretary_yes, 'secretary_cader' => $secretary_yes_cader, 'secretary_non_cader' => $secretary_yes_non_cader, 'secretary_supervisor' => $secretary_yes_supervisor, 'accountant' => $row['accountant'], 'assistant_accountant' => $row['assistant_accountant'], 'seller' => $row['seller'], 'support_staff' => $row['support_staff'], 'guard' => $row['guard'], 'computer_operator' => $row['computer_operator'], 'accountant_count' => $row['accountant_count'], 'assistant_accountant_count' => $row['assistant_accountant_count'], 'seller_count' => $row['seller_count'], 'support_staff_count' => $row['support_staff'], 'guard_count' => $row['guard_count'], 'computer_operator_count' => $row['computer_operator_count']);
		
		$tot_c  += $row['c'];
		$tot_litigation  += $row['litigation'];
		$tot_liquidation   += $row['liquidation'];
		$tot_society_building_ownership  += $row['society_building_ownership'];
		$tot_active_members  += $row['active_members'];
		$tot_inactive_members  += $row['inactive_members'];
		$tot_total_business  += $row['total_business'];
		$tot_profit_societies  += $row['profit_societies'];
		$tot_pds  += $row['pds'];
		$tot_secretary  += $secretary_yes;
		$tot_secretary_cader  += $secretary_yes_cader;
		$tot_secretary_non_cader  += $secretary_yes_non_cader;
		$tot_secretary_supervisor  += $secretary_yes_supervisor;
		$tot_accountant += $row['accountant'];
		$tot_assistant_accountant += $row['assistant_accountant'];
		$tot_seller += $row['seller'];
		$tot_support_staff += $row['support_staff'];
		$tot_guard += $row['guard'];
		$tot_computer_operator += $row['computer_operator'];
		$tot_accountant_count += $row['accountant_count'];;
		$tot_assistant_accountant_count += $row['assistant_accountant_count'];
		$tot_seller_count += $row['seller_count'];
		$tot_support_staff_count += $row['support_staff_count'];
		$tot_guard_count += $row['guard_count'];
		$tot_computer_operator_count += $row['computer_operator_count'];

	}
	
	$data['total'] = array('division_name'=>'', 'division_id'=>'', 'district_name' => 'total', 'district_id'=>'', 'c' => $tot_c, 'litigation' => $tot_litigation, 'liquidation' => $tot_liquidation, 'society_building_ownership' => $tot_society_building_ownership, 'active_members' => $tot_active_members, 'inactive_members' => $tot_inactive_members, 'total_business' => $tot_total_business, 'profit_societies' => $tot_profit_societies, 'pds' => $tot_pds, 'secretary' => $tot_secretary, 'secretary_count' => $secretary_yes, 'secretary_cader' => $tot_secretary_cader, 'secretary_non_cader' => $tot_secretary_non_cader, 'secretary_supervisor' => $tot_secretary_supervisor, 'accountant' => $tot_accountant, 'assistant_accountant' => $tot_assistant_accountant, 'seller' => $tot_seller, 'support_staff' => $tot_support_staff, 'guard' => $tot_guard, 'computer_operator' => $tot_computer_operator, 'accountant_count' => $tot_accountant_count, 'assistant_accountant_count' => $tot_assistant_accountant_count, 'seller_count' => $tot_seller_count, 'support_staff_count' => $tot_support_staff_count, 'guard_count' => $tot_guard_count, 'computer_operator_count' => $tot_computer_operator_count);
	//print_r($data);
	return $data;
}

function summary_backup($district=''){
	/*$sql = 'SELECT 
	count(*) c, 
	count(if(litigation="yes", 1, NULL)) as litigation, 
	count(if(liquidation="yes", 1, NULL)) as liquidation,  
	count(if(society_building_ownership="own", 1, NULL)) as society_building_ownership, 
	sum(active_members) as active_members, 
	sum(inactive_members) as inactive_members, 
	sum(total_business) as total_business, 
	count(if(last_year_profit_loss="profit", 1, NULL)) as profit_societies, 
	count(if(pds="yes", 1, NULL)) as pds, 
	count(if(secretary="yes", 1, null)) as secretary, 
	count(if(secretary_status="regular", 1, null)) as secretary_status, 
	count(if(secretary_cader="cader", 1, null)) as secretary_cader, 
	count(if(accountant!="no", 1, null)) as accountant,
	count(if(assistant_accountant!="no", 1, null)) as assistant_accountant,
	count(if(seller!="no", 1, null)) as seller,
	count(if(support_staff!="no", 1, null)) as support_staff,
	count(if(guard!="no", 1, null)) as guard,
	count(if(computer_operator!="no", 1, null)) as computer_operator
	FROM `survey_invoice` left join survey_invoice_sec_2_1 on survey_invoice_sec_2_1.survey_id = survey_invoice.sno left join survey_invoice_sec_2_2 on survey_invoice_sec_2_2.survey_id = survey_invoice.sno where approval_status=4';*/
	
	$sql = 'SELECT 
	district_name,
	count(*) c, 
	count(if(litigation="yes", 1, NULL)) as litigation, 
	count(if(liquidation="yes", 1, NULL)) as liquidation,  
	count(if(society_building_ownership="own", 1, NULL)) as society_building_ownership, 
	sum(active_members) as active_members, 
	sum(inactive_members) as inactive_members, 
	sum(total_business) as total_business, 
	count(if(last_year_profit_loss="profit", 1, NULL)) as profit_societies, 
	count(if(pds="yes", 1, NULL)) as pds, 
	count(if(secretary="yes", 1, null)) as secretary, 
	sum(secretary) as secretary_count, 
	count(if(secretary_status="regular", 1, null)) as secretary_status, 
	count(if(secretary_cader="cader", 1, null)) as secretary_cader, 
	count(if(accountant!="no", 1, null)) as accountant,
	sum(accountant) as accountant_count,
	count(if(assistant_accountant!="no", 1, null)) as assistant_accountant,
	sum(assistant_accountant) as assistant_accountant_count,
	count(if(seller!="no", 1, null)) as seller,
	sum(seller) as seller_count,
	count(if(support_staff!="no", 1, null)) as support_staff,
	sum(support_staff) as support_staff_count,
	count(if(guard!="no", 1, null)) as guard,
	sum(guard) as guard_count,
	count(if(computer_operator!="no", 1, null)) as computer_operator,
	sum(computer_operator) as computer_operator_count
	FROM `survey_invoice` 
	left join survey_invoice_sec_2_1 on survey_invoice_sec_2_1.survey_id = survey_invoice.sno 
	left join survey_invoice_sec_2_2 on survey_invoice_sec_2_2.survey_id = survey_invoice.sno 
	left join test2 on test2.sno = survey_invoice.society_id
	left join master_district on master_district.sno = col2
	where approval_status=4
	group by col2
	order by district_name';
	
	$result = execute_query($sql);
	$data = array();
	$tot_district_name = 0;
	$tot_c  = 0;
	$tot_litigation  = 0;
	$tot_liquidation   = 0;
	$tot_society_building_ownership  = 0;
	$tot_active_members  = 0;
	$tot_inactive_members  = 0;
	$tot_total_business  = 0;
	$tot_profit_societies  = 0;
	$tot_pds  = 0;
	$tot_secretary  = 0;
	$tot_secretary_count  = 0;
	$tot_secretary_status  = 0;
	$tot_secretary_cader  = 0;
	$tot_accountant = 0;
	$tot_assistant_accountant = 0;
	$tot_seller = 0;
	$tot_support_staff = 0;
	$tot_guard = 0;
	$tot_computer_operator = 0;
	$tot_accountant_count = 0;
	$tot_assistant_accountant_count = 0;
	$tot_seller_count = 0;
	$tot_support_staff_count = 0;
	$tot_guard_count = 0;
	$tot_computer_operator_count = 0;

	while($row = mysqli_fetch_assoc($result)){
		$data[] = array('district_name' => $row['district_name'], 'c' => $row['c'], 'litigation' => $row['litigation'], 'liquidation' => $row['liquidation'], 'society_building_ownership' => $row['society_building_ownership'], 'active_members' => $row['active_members'], 'inactive_members' => $row['inactive_members'], 'total_business' => $row['total_business'], 'profit_societies' => $row['profit_societies'], 'pds' => $row['pds'], 'secretary' => $row['secretary'], 'secretary_count' => $row['secretary'], 'secretary_status' => $row['secretary_status'], 'secretary_cader' => $row['secretary_cader'], 'accountant' => $row['accountant'], 'assistant_accountant' => $row['assistant_accountant'], 'seller' => $row['seller'], 'support_staff' => $row['support_staff'], 'guard' => $row['guard'], 'computer_operator' => $row['computer_operator'], 'accountant_count' => $row['accountant_count'], 'assistant_accountant_count' => $row['assistant_accountant_count'], 'seller_count' => $row['seller_count'], 'support_staff_count' => $row['support_staff'], 'guard_count' => $row['guard_count'], 'computer_operator_count' => $row['computer_operator_count']);
		
		$tot_c  += $row['c'];
		$tot_litigation  += $row['litigation'];
		$tot_liquidation   += $row['liquidation'];
		$tot_society_building_ownership  += $row['society_building_ownership'];
		$tot_active_members  += $row['active_members'];
		$tot_inactive_members  += $row['inactive_members'];
		$tot_total_business  += $row['total_business'];
		$tot_profit_societies  += $row['profit_societies'];
		$tot_pds  += $row['pds'];
		$tot_secretary  += $row['secretary'];
		$tot_secretary_count  += $row['secretary_count'];
		$tot_secretary_status  += $row['secretary_status'];
		$tot_secretary_cader  += $row['secretary_cader'];
		$tot_accountant += $row['accountant'];;
		$tot_assistant_accountant += $row['assistant_accountant'];
		$tot_seller += $row['seller'];
		$tot_support_staff += $row['support_staff'];
		$tot_guard += $row['guard'];
		$tot_computer_operator += $row['computer_operator'];
		$tot_accountant_count += $row['accountant_count'];;
		$tot_assistant_accountant_count += $row['assistant_accountant_count'];
		$tot_seller_count += $row['seller_count'];
		$tot_support_staff_count += $row['support_staff_count'];
		$tot_guard_count += $row['guard_count'];
		$tot_computer_operator_count += $row['computer_operator_count'];

	}
	
	/*$total_society = $row['c'];
	$total_business = $row['total_business'];
	$under_liquidation = $row['liquidation'];
	$under_litigation = $row['litigation'];
	$active_members = $row['active_members'];
	$inactive_members = $row['inactive_members'];
	$current_year_pl = $row['profit_societies'];
	$total_secretary = $row['secretary'];
	$cader_secretary = $row['secretary_cader'];
	$total_accountant = $row['accountant'];
	$total_asst_accountant = $row['assistant_accountant'];
	$total_seller = $row['seller'];
	$total_support_staff = $row['support_staff'];
	$total_guard = $row['guard'];
	$cader_secretary = $row['secretary_cader'];
	$array = array('total_society' => $total_society, 'total_business' => $total_business, 'total_members' => $active_members+$inactive_members, 'total_manpower' => $total_manpower, 'total_land' => $total_land, 'total_machines' => $total_machines, 'under_liquidation' => $under_liquidation, 'under_litigation' => $under_litigation, 'active_members' => $active_members, 'inactive_members' => $inactive_members, 'current_year_pl' => $current_year_pl, 'last_year_pl' => $last_year_pl, 'total_secretary' => $total_secretary, 'total_accountant' => $total_accountant, 'total_asst_accountant' => $total_asst_accountant, 'total_seller' => $total_seller, 'total_support_staff' => $total_support_staff, 'total_guard' => $total_guard, 'cader_secretary' => $cader_secretary, 'vacancy_secretary' => $vacancy_secretary, 'vacancy_accountant' => $vacancy_accountant, 'vacancy_asst_accountact' => $vacancy_asst_accountact, 'vacancy_seller' => $vacancy_seller, 'vacancy_support_staff' => $vacancy_support_staff, 'vacancy_guard' => $vacancy_guard, 'computerization_selection' => $computerization_selection, 'land_ownership' => $land_ownership, 'building_status' => $building_status, 'access_road' => $access_road, 'electrict_connection' => $electrict_connection, 'internet_connection' => $internet_connection, "pds"=>$pds);*/
	
	$data['total'] = array('district_name' => 'total', 'c' => $tot_c, 'litigation' => $tot_litigation, 'liquidation' => $tot_liquidation, 'society_building_ownership' => $tot_society_building_ownership, 'active_members' => $tot_active_members, 'inactive_members' => $tot_inactive_members, 'total_business' => $tot_total_business, 'profit_societies' => $tot_profit_societies, 'pds' => $tot_pds, 'secretary' => $tot_secretary, 'secretary_count' => $tot_secretary, 'secretary_status' => $tot_secretary_status, 'secretary_cader' => $tot_secretary_cader, 'accountant' => $tot_accountant, 'assistant_accountant' => $tot_assistant_accountant, 'seller' => $tot_seller, 'support_staff' => $tot_support_staff, 'guard' => $tot_guard, 'computer_operator' => $tot_computer_operator, 'accountant_count' => $tot_accountant_count, 'assistant_accountant_count' => $tot_assistant_accountant_count, 'seller_count' => $tot_seller_count, 'support_staff_count' => $tot_support_staff_count, 'guard_count' => $tot_guard_count, 'computer_operator_count' => $tot_computer_operator_count);
	
	return $data;
}
?>
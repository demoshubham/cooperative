<?php
include("scripts/settings.php");
$msg='';
$tab=1;
if(isset($_POST['date_from'])){
	
}
else{
	$_POST['date_from'] = date("Y-m-01");
	$_POST['date_to'] = date("Y-m-d");
}
?>

<?php
page_header_start();
page_header_end();
page_sidebar();

?>	
				<div class="row">
					<div class="col-md-12">
						<div class="card strpied-tabled-with-hover">
							<div class="card-header ">
								<h4 class="card-title">Recent Visits</h4>
								<form name="user_form" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" method="post">
								<div class="row">
									<div class="col-md-3">
										<label>Date From</label>
										<script type="text/javascript" language="javascript">
										document.writeln(DateInput('date_from', 'user_form', true, 'YYYY-MM-DD', '<?php echo $_POST['date_from']; ?>', <?php echo $tab++; $tab=$tab+3; ?>));
										 </script>
									</div>
									<div class="col-md-3">
										<label>Date To</label>
										<script type="text/javascript" language="javascript">
										document.writeln(DateInput('date_to', 'user_form', true, 'YYYY-MM-DD', '<?php echo $_POST['date_to']; ?>', <?php echo $tab++; $tab=$tab+3; ?>));
										 </script>
									</div>
								</div>
								</form>
								<p class="card-category">Space for Pagination</p>
							</div>
							<div class="card-body table-full-width table-responsive">
								<table class="table table-hover table-striped">
									<thead>
										<tr>
										<th>S.No.</th>
										<th>PLV Name</th>
										<th>Visit Date</th>
										<th>Full Name</th>
										<th>Father Name</th>
										<th>Mobile</th>
										<th>Tehsil</th>
										<th>Village</th>
										<th>Status</th>
										<th></th>
										<th></th>
										<th></th>
									</tr></thead>
									<tbody>
										<?php
										$i=1;
										$sql = 'select entry_date, enquiry_customer_invoice.status as status, enquiry_customer_invoice.sno as sno, enquiry_customer.cus_name as cus_name, enquiry_customer.fname as fname, enquiry_customer.mobile as mobile, location_tehsil.location_name as tehsil, location_village.location_name as village, user_name from enquiry_customer_invoice left join enquiry_customer on enquiry_customer.sno = enquiry_customer_invoice.customer_id left join location_tehsil on location_tehsil.sno = add_2 left join location_village on location_village.sno = city left join users on users.sno = plv_id where 1=1';
										if($_SESSION['username']!='sadmin'){
											if($_SESSION['usertype']==2){
												$sql .= ' and plv_id="'.$_SESSION['usersno'].'"';
											}
											elseif($_SESSION['usertype']==1){
												$sql .= ' and add_2="'.$_SESSION['tehsil'].'"';
											}
										}
										//echo $sql;
										$result = execute_query($sql);
										while($row = mysqli_fetch_assoc($result)){
										?>
										<tr>
											<td><?php echo $i++; ?></td>
											<td><?php echo $row['user_name']; ?></td>
											<td><?php echo $row['entry_date']; ?></td>
											<td><?php echo $row['cus_name']; ?></td>
											<td><?php echo $row['fname']; ?></td>
											<td><?php echo $row['mobile']; ?></td>
											<td><?php echo $row['tehsil']; ?></td>
											<td><?php echo $row['village']; ?></td>
											<td><?php 
											if($row['status']==0){
												echo "<p class='text-info'>Pending</p>";
											}
											elseif($row['status']==1){
												echo "<p class='text-success'>Approved</p>";
											}
											elseif($row['status']==2){
												echo "<p class='text-danger'>Rejected</p>";
											}
											?></td>
											<td class="text-center"><a href="visit_details.php?id=<?php echo $row['sno']; ?>" target="_blank"><span class="fa fa-eye" aria-hidden="true" data-toggle="tooltip" title="View Invoice"></span></a></td>
											<td class="text-center"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?id='.$row['sno'].'" target="_blank" alt="Edit" data-toggle="tooltip" title="Edit"><span class="far fa-edit" aria-hidden="true"></span></td>
											<!--<td><a href="sale_new.php?copy='.$row['sno'].'" target="_blank" alt="Copy Invoice" data-toggle="tooltip" title="Copy Invoice"><span class="pe-7s-copy-file" aria-hidden="true"></span></a></td>-->
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

    <script>
		function update_locations(){
			var tehsil = $("#tehsil").val();
			$.ajax({
				url: "scripts/ajax.php?id=villages&term="+tehsil,
				dataType:"json"
			})
			.done(function( data ) {

				var txt = '<label>Villages</label><select name="villages" id="villages" class="form-control">';
				$.each(data, function(k, value){
					txt += '<option value="'+value.id+'">'+value.location_name+'</option>';
				});
				txt += '</select>';
				console.log(txt);
				$("#villages_group").html(txt);
				$("#villages_group").show();
			});
		}
    $("#plv_tasks").multiselect();    
    </script>
    
<script>

let camera_button = document.querySelector("#start-camera");
let video = document.querySelector("#video");
let click_button = document.querySelector("#click-photo");
let canvas = document.querySelector("#canvas");
let dataurl = document.querySelector("#dataurl");
let dataurl_container = document.querySelector("#dataurl-container");

async function update_preview(obj){
	let stream = null;
	try {
		stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
	}
	catch(error) {
		alert(error.message);
		return;
	}

	stream.getTracks().forEach(function(track) { track.stop(); })

	video.srcObject = stream;

	video.style.display = 'none';
	click_button.style.display = 'none';

	camera_button.value = 'start-camera';
	camera_button.innerHTML = 'Start Camera';
	canvas.style.display = 'none';
	canvas.innerHTML = '';
	dataurl.value = '';
	document.getElementById('img_preview').style.display = 'block';
	document.getElementById('img_preview').src = window.URL.createObjectURL(obj.files[0]);
}

	
camera_button.addEventListener('click', async function() {
	if(camera_button.value === 'start-camera'){
		let stream = null;
		try {
			stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
		}
		catch(error) {
			alert(error.message);
			return;
		}

		video.srcObject = stream;

		video.style.display = 'block';
		document.querySelector("#img_preview").style.display = 'none';
		camera_button.value = 'stop-camera';
		camera_button.innerHTML = 'Stop Camera';
		click_button.style.display = 'block';
		canvas.innerHTML = '';
		canvas.style.display = 'none';
		dataurl.value = '';
	}
	else{
		let stream = null;
		try {
			stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
		}
		catch(error) {
			alert(error.message);
			return;
		}

		stream.getTracks().forEach(function(track) { track.stop(); })
		
		video.srcObject = stream;

		video.style.display = 'none';
		document.querySelector("#img_preview").style.display = 'block';
		click_button.style.display = 'none';
		
		camera_button.value = 'start-camera';
		camera_button.innerHTML = 'Start Camera';
		
	}
});

click_button.addEventListener('click', async function() {
	
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
	canvas.style.display = 'block';
   	let image_data_url = canvas.toDataURL('image/jpeg');
    
    dataurl.value = image_data_url;
    dataurl_container.style.display = 'block';
	
	let stream = null;
	try {
		stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
	}
	catch(error) {
		alert(error.message);
		return;
	}

	stream.getTracks().forEach(function(track) { track.stop(); })

	video.srcObject = stream;

	video.style.display = 'none';
	click_button.style.display = 'none';

	camera_button.value = 'start-camera';
	camera_button.innerHTML = 'Start Camera';
	
	
});

</script>
<?php		
page_footer_end();
?>

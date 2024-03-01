<?php
include("scripts/settings.php");
logvalidate();
$msg='';

$tab=1;



if(isset($_GET['id_forword'])){
	
	$sql = 'UPDATE sammelen_invoice SET 
	
                verify_status = "1",
                edition_time="' . date("Y-m-d H:i:s") . '",
                edited_by="' . $_SESSION['username'] . '"
                WHERE sno = "' . $_GET['id_forword'] . '"';
//echo $sql;
     $res=execute_query($sql);
    if(mysqli_error($db)){ 
        $msg .= '<p class="alert alert-danger">Error # 1.01 : '.mysqli_error($db).'>> '.$sql.'</p>';
    }
    if($msg==""){
        $msg = '<p class="alert alert">Appproved successfully</p>';
    }

}


?>

<?php
page_header_start();
?>
<!-- <link href="css/multistepform.css" rel="stylesheet" type="text/css" media="all" /> -->
<script src="js/survey_validate.js"></script>
<?php
page_header_end();
page_sidebar();


?>


<?php 
	//if($_SESSION['usertype']=="sadmin" || $_SESSION['usertype']=="2" ){
?>
<script src="js/table2excel.js">

</script>
	<div id="container" class="no-print">
		<form id="" name="sale_form" class="" autocomplete="off" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onSubmit="">
		<div class="card card-body">    
        	<div class="row d-flex my-auto">    	
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" >
					<?php echo $msg; ?>
					<h4>सहकारिता महासम्मेलन </h4>
					<div class="col-sm-12">
						<div class="row " >
							<div class="col-md-3 form-group">
								<label>मण्डल (कमिश्नरी)</label>
								<select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);">
									<option value="" disabled selected >--SELECT--</option>
									<?php
										$sql = "select * from master_division where sno IN ('" . implode("','", $_SESSION['division']) . "')";
										$result_division = execute_query($sql);
										
										while($row_division = mysqli_fetch_assoc($result_division)){
											//$datas.= '<option value="'.$row_division['sno'].'">'.$row_division['division_name'].'</option>';
											?>
											<option value="<?php echo $row_division['sno']; ?>"><?php echo $row_division['division_name'];?></option>
											<?php
											// $data[] = array("id"=>$row_division['sno'], "division_name"=>$row_division['division_name']);
										}
									?>
						
								</select>
							</div>
							<div class="col-md-3 form-group">
								<label>जनपद</label>
								<select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fill_tehseel_name(this.value);"></select>
							</div>
							<div class="col-md-3 form-group">
								<label>तहसील</label>
								<select name="tehseel_name" id="tehseel_name" tabindex=""  class="form-control" onChange="fill_block_name(this.value);"></select>
							</div>
							<div class="col-md-3 form-group">
								<label>विकासखंड</label>
								<select name="block_name" id="block_name" tabindex=""  class="form-control" onChange="fillSocietyName()"></select>
							</div>
							<div class="col-md-3 form-group">
								<label>Status</label>
								<select name="status" id="status" tabindex=""  class="form-control">
										<option value="" selected disabled>--SELECT--</option>
										<option value="0">Pending</option>
										<option value="1">Approved</option>
										<option value="2">Rejected</option>
								</select>
							</div>

					</div>

					<div class="col-md-12 text-center">
					<button  class="btn btn-success" id="submit" type="submit" name="search">Search (प्रेषित करें)</button>
					</div>
				</form>
			</div>
			
		</div>
		</form>

		<div class="row">
				<div class="col-12 text-right">
					<button  id="btn" class="btn btn-warning">Export Table Data To Excel File</button>
				</div>
			</div>
	</div>
	
	<div class="card-deck">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title text-center"></h5></br>
				<table class="table table-hover table-bordered table-striped" id="tblData">
					<thead style="position: sticky;top: 0;z-index: 1;">
                        <tr>
                            <th>SNo.</th>
                            <th>Division Name </th>
                            <th>District Name </th>
                            <th>Tehsil Name </th>
                            <th>Block Name </th>
                            <th>Society Type</th>
                            <th>Society Name</th>
                            <th>Member Typer</th>
                            <th>Name</th>
                            <th>Father Name </th>
                            <th>Mobile Number</th>
                            <th>Status</th>
                        </tr>
						</thead>
					<tbody>
								<?php
								
								if(isset($_POST['search'])){

							
									if($_SESSION['usertype']=="sadmin"){
										
										$sql = 'SELECT * FROM sammelen_trans
											LEFT JOIN sammelen_invoice ON sammelen_trans.sammelen_invoice_id = sammelen_invoice.sno where sammelen_invoice.status="1"';
										if(isset($_POST['society_type'])){
											$sql .= ' and society_type_id="'.$_POST['society_type'].'"';
										}	
										if(isset($_POST['division_name'])){
											$sql .= ' and mandal_id="'.$_POST['division_name'].'"';
										}	

										if(isset($_POST['district_name'])){
											$sql .= ' and district_id="'.$_POST['district_name'].'"';
										}
										if(isset($_POST['tehseel_name'])){
											$sql .= ' and tehsil_id="'.$_POST['tehseel_name'].'"';
										}
										if(isset($_POST['block_name'])){
											$sql .= ' and block_id="'.$_POST['block_name'].'"';
										}
										if(isset($_POST['society_name'])){
											$sql .= ' and society_name_id="'.$_POST['society_name'].'"';
										}
										if(isset($_POST['status'])){
											$sql .= ' and verify_status="'.$_POST['status'].'"';

										}
										
										$sql .= 'ORDER BY mandal_id, district_id, tehsil_id, block_id, society_type_id, society_name_id ,verify_status';
										
										// echo $sql;
									}else{
										$sql = "SELECT * FROM sammelen_trans
											LEFT JOIN sammelen_invoice ON sammelen_trans.sammelen_invoice_id = sammelen_invoice.sno where sammelen_invoice.status='1' and sammelen_invoice.district_id IN ('" . implode("','", $_SESSION['district']) . "')";
											
											if(isset($_POST['society_type'])){
												$sql .= ' and society_type_id="'.$_POST['society_type'].'"';
											}	
											if(isset($_POST['division_name'])){
												$sql .= ' and mandal_id="'.$_POST['division_name'].'"';
											}	
	
											if(isset($_POST['district_name'])){
												$sql .= ' and district_id="'.$_POST['district_name'].'"';
											}
											if(isset($_POST['tehseel_name'])){
												$sql .= ' and tehsil_id="'.$_POST['tehseel_name'].'"';
											}
											if(isset($_POST['block_name'])){
												$sql .= ' and block_id="'.$_POST['block_name'].'"';
											}
											if(isset($_POST['society_name'])){
												$sql .= ' and society_name_id="'.$_POST['society_name'].'"';
											}
											if(isset($_POST['status'])){
												$sql .= ' and verify_status="'.$_POST['status'].'"';
	
											}
											
											$sql .= 'ORDER BY mandal_id, district_id, tehsil_id, block_id, society_type_id, society_name_id, verify_status';
									}
									// echo $sql;			
									$result = execute_query($sql);
									$i=1;
									$tot_allotted = 0;
									$tot_approved = 0;
									$tot_pending = 0;
									if(mysqli_num_rows($result)>0){
										while($row = mysqli_fetch_assoc($result)){
											$mandalsql = "select * from master_division WHERE sno='{$row['mandal_id']}'";
											$result_mandal = execute_query($mandalsql);
											$row_mandal = mysqli_fetch_assoc($result_mandal);
											
											//district name
											$districtsql = "select * from sammelen_newdistrict WHERE CityCode='{$row['district_id']}'";
											$result_district = execute_query($districtsql);
											$row_district = mysqli_fetch_assoc($result_district);
	
											//tehsil name
											$tehsilsql = "select * from sammelen_master_tehseel WHERE TehsilCode='{$row['tehsil_id']}'";
											$result_tehsil = execute_query($tehsilsql);
											$row_tehsil = mysqli_fetch_assoc($result_tehsil);
	
											//block
	
											$blocksql = "select * from sammelen_master_block WHERE BlockId='{$row['block_id']}'";
											$result_block = execute_query($blocksql);
											$row_block = mysqli_fetch_assoc($result_block);
											
	
											//type of society
											
	
											$typesql = "select * from sammelen_society_types WHERE sno='{$row['society_type_id']}'";
											$result_type = execute_query($typesql);
											$row_type = mysqli_fetch_assoc($result_type);
	
											// society
											
	
											$societynamesql = "select * from sammelen_society WHERE sno='{$row['society_name_id']}'";
											$res_society_name = execute_query($societynamesql);
											$row_sname = mysqli_fetch_assoc($res_society_name);
											
											echo "<tr>
											<td>".$i++."</td>
											<td>".$row_mandal['division_name']."</td>
											<td>";
											if(isset($row_district['City'])){
												echo $row_district['City'];
											}else{
												echo "";
											}
											echo "</td>
											<td>";
												if(isset($row_tehsil['TehsilName'])){
													echo $row_tehsil['TehsilName'];
												}else{
													echo "";
												}
											
											echo "</td>
											<td>";
											if(isset($row_block['BlockName'])){
												echo $row_block['BlockName'];
											}else{
												echo "";
											}
											echo "</td>
											<td>".$row_type['stypename']."</td>
											<td>";
	
											if($row_type['hasSociety']=="1"){
												echo $row_sname['SocietyName'];
											}else{
												echo "";
											}
												
												echo "</td>
											<td>".$row['memberType']."</td>
											<td>".$row['name']."</td>
											<td>".$row['father_name']."</td>
											<td>".$row['mobile_num']."</td>
											<td>";
	
											if($row['verify_status']=="1"){
											echo "Approve";
											}elseif($row['verify_status']=="0"){
												echo "Pending";
											}else{
												echo "Rejected";
											}
												
												echo "</td>
											</tr>";
										}
										echo '
										</tbody>';
									}else{
										echo "<tr><td colspan='12' >No data found</td></tr>";
									}
									
								}
							?>
				</table>
			  
			 
			</div>
		</div>
	</div>

<?php 
//	}
?>



		
<div id="preloader-wrapper">
   <div id="preloader"></div>
   <div class="preloader-section section-left"></div>
   <div class="preloader-section section-right"></div>
</div>
		
<script>
        document.getElementById("btn").addEventListener('click', function(){
            var table2excel = new Table2Excel();
            table2excel.export(document.querySelectorAll("#tblData"),"Sammelen Reports");
                //in the second parameter we can declare the file name that we want to store
            });
    </script>
<script>
<?php 
if(isset($_POST['search'])){
	?>

	$(document).ready(function() {
        appendLevel($("#society_type").val());
		<?php
		if(isset($_POST['division_name']) && $_POST['division_name']!=''){
		?>
		fillMandal($("#society_type").val(), <?php echo $_POST['division_name']; ?>);
		fill_district_name(<?php echo $_POST['division_name']; ?>,<?php echo $_POST['district_name']; ?>);
		fill_tehseel_name(<?php echo $_POST['district_name']; ?>);
		fill_block_name(<?php echo $_POST['tehseel_name']; ?>,<?php echo $_POST['block_name']; ?>);
		fillSocietyName($("#society_type").val(), <?php echo $_POST['district_name']; ?>, <?php echo $_POST['tehseel_name']; ?>, <?php echo $_POST['block_name']; ?>,<?php echo $_POST['society_name']; ?>);
		<?php } ?>
		
    });
<?php } ?>


    $('select[multiple]').multiselect({
        columns: 1,
        placeholder: 'Select options'
    });
</script>
<script>
$(document).ready( function () {
    /*$('#general_stat_table').DataTable({
		paging: false,
		fixedHeader: true,
		colReorder: true
		});
	});	*/

	
	// var t = $('#general_stat_table').DataTable({
	// 	// paging: false
    // });
 
    
});


function appendLevel(val){
    // console.log("hello");

    var data = {term:"b", id:"appendLevel", val:val};
	$.ajax({
        type: "POST",
        url: actionUrl,
        data: data, // serializes the form's elements.
        success: function(data){
            // Parse the JSON response only once
            var data = JSON.parse(data);

            // Access the values
            var level = data.level;
            var hasSociety = data.hasSociety;
			
			if(data.sno=='21'){
				var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fillSocietyName();fill_tehseel_name(this.value)"></select></div><div class="col-md-3 form-group"><label>शाखा का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control" ></select></div>';
                $("#level_wrap").html(level);
			}
            else if(level=="1" && hasSociety=="1"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fillSocietyName()"><option value="">--Select--</option></select></div><div class="col-md-3 form-group"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control" ></select></div>';
                
                $("#level_wrap").html(level);
            }else if(level=="2" && hasSociety=="1"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fillSocietyName();fill_tehseel_name(this.value)"></select></div><div class="col-md-3 form-group"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control" ></select></div>';
                $("#level_wrap").html(level);
            }else if(level=="3" && hasSociety=="1"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fill_tehseel_name(this.value);"></select></div>';
                level +='<div class="col-md-3 form-group"><label>तहसील</label><select name="tehseel_name" id="tehseel_name" tabindex=""  class="form-control" onChange="fill_block_name(this.value);fillSocietyName()"></select></div><div class="col-md-3 form-group"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control" ></select></div>';
                $("#level_wrap").html(level);
            }else if(level=="4" && hasSociety=="1"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fill_tehseel_name(this.value);"></select></div>';
                level +='<div class="col-md-3 form-group"><label>तहसील</label><select name="tehseel_name" id="tehseel_name" tabindex=""  class="form-control" onChange="fill_block_name(this.value);"></select></div>';
                level +='<div class="col-md-3 form-group"><label>विकासखंड</label><select name="block_name" id="block_name" tabindex=""  class="form-control" onChange="fillSocietyName()"></select></div><div class="col-md-3 form-group"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control" ></select></div>';
                $("#level_wrap").html(level);
            }else if(level=="1" && hasSociety=="0"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fillSocietyName()"><option value="">--Select--</option></select></div><div class="col-md-3 form-group" style="display:none;"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control"  ></select></div>';
                
                $("#level_wrap").html(level);
            }else if(level=="2" && hasSociety=="0"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fillSocietyName();fill_tehseel_name(this.value)"></select></div><div class="col-md-3 form-group" style="display:none;"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control"  ></select></div>';
                $("#level_wrap").html(level);
            }else if(level=="3" && hasSociety=="0"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fill_tehseel_name(this.value);"></select></div>';
                level +='<div class="col-md-3 form-group"><label>तहसील</label><select name="tehseel_name" id="tehseel_name" tabindex=""  class="form-control" onChange="fill_block_name(this.value);fillSocietyName()"></select></div><div class="col-md-3 form-group" style="display:none;"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control"  ></select></div>';
                $("#level_wrap").html(level);
            }else if(level=="4" && hasSociety=="0"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fill_tehseel_name(this.value);"></select></div>';
                level +='<div class="col-md-3 form-group"><label>तहसील</label><select name="tehseel_name" id="tehseel_name" tabindex=""  class="form-control" onChange="fill_block_name(this.value);"></select></div>';
                level +='<div class="col-md-3 form-group"><label>विकासखंड</label><select name="block_name" id="block_name" tabindex=""  class="form-control" onChange="fillSocietyName()"></select></div><div class="col-md-3 form-group" style="display:none;"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control"  ></select></div>';
                $("#level_wrap").html(level);
            }
            
        }
    });
}

function fillMandal(val, selected=''){
    
	// console.log("hello");
	// var district = $("#district_name").val();
	var data = {term:"b", id:"fillmandal", val:val};
	$.ajax({
        type: "POST",
        url: actionUrl,
        data: data, // serializes the form's elements.
        success: function(data){
			var txt = '<option value="" disabled selected>--Select--</option>';
			data = JSON.parse(data);
			$.each(data, function(key, value){
				txt += '<option value="'+value.id+'"';
				if(value.id==selected){
					txt += ' selected="selected" ';
				}
				txt += '>'+value.division_name+'</option>';
				
			});
			$("#division_name").html(txt);
			// $("#society_details").show();
        }
    });
}

function fill_district_name(val, selected=''){
	var data = {"term":"b", "id":"district", "val":val};
	$.ajax({
        type: "POST",
        url: actionUrl,
        data: data, // serializes the form's elements.
        success: function(data){
			var txt = '<option value="" disabled selected>--Select--</option>';
			data = JSON.parse(data);
			$.each(data, function(key, value){
				txt += '<option value="'+value.id+'"';
				if(value.id==selected){
					txt += ' selected="selected" ';
				}
				txt += '>'+value.district_name+'</option>';
				
			});
          	
			$("#district_name").html(txt);
        }
    });
}


function fill_tehseel_name(val, selected='', idforappend=''){
	if(idforappend==''){
		idforappend=1;
	}
	var data = {term:"b", id:"tehseelname", val:val};
	$.ajax({
        type: "POST",
        url: actionUrl,
        data: data, // serializes the form's elements.
        success: function(data){
			var txt = '<option value="" disabled selected>--Select--</option>';
			data = JSON.parse(data);
			$.each(data, function(key, value){
				txt += '<option value="'+value.id+'"';
				if(value.id==selected){
					txt += ' selected="selected" ';
				}
				txt +='>'+value.tehseel_name+'</option>';
				
			});
			$("#tehseel_name").html(txt);
            $("#member_tehseel"+idforappend).html(txt);
        }
    });
}
	

function fill_block_name(val, selected =''){
	var data = {term:"b", id:"blockname", val:val};
	$.ajax({
        type: "POST",
        url: actionUrl,
        data: data, // serializes the form's elements.
        success: function(data){
			var txt = '<option value="" disabled selected>--Select--</option>';
			data = JSON.parse(data);
			$.each(data, function(key, value){
				txt += '<option value="'+value.id+'"';
				if(value.id==selected){
					txt += ' selected="selected" ';
				}
				txt+='>'+value.block_name+'</option>';
				
			});
          	$("#block_name").html(txt);
            
        }
    });
}

function fillSocietyName(val='',dis='',teh='',block='', selected='') {
	if(val == ''){
		var val = $("#society_type").val();
	}
    //console.log(vals);
    if(dis==''){
		var dis = $("#district_name").val();
	}
	if(teh==''){
		var teh = $("#tehseel_name").val();
	}
	if(block==''){
		var block = $("#block_name").val();
	}
    var data = { term: "b", id: "societytype", val: val, dis: dis, teh: teh, block: block };
	console.log(data);

    $.ajax({
        type: "POST",
        url: actionUrl,
        data: data,
        success: function (data) {
            var txt = '';

            data = JSON.parse(data);

            if (typeof data.nodata !== 'undefined') {
                console.error("No data found.");
            } else {
                $.each(data, function (key, value) {
                    txt += '<option value="' + value.id + '" ';
                   if(value.id==selected){
						txt += ' selected="selected" ';
					}
                    txt += '>' + value.data.SocietyName + '</option>';
                });
            }

            //txt += '<option value="custom">Other</option>';
            $("#society_name").html(txt);
            $("#society_details").show();
        }
    });
}



</script>
 																						
				
<?php
page_footer_start();
?>
<!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
<script src="js/light-bootstrap-dashboard.js?v=1.4.0"></script>

<?php		
page_footer_end();
?>

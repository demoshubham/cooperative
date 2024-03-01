<?php
include("scripts/settings.php");
logvalidate();
$msg='';

$tab=1;
if(isset($_GET['delid'])){

    $sql="UPDATE sammelen_invoice SET status='0',
		edition_time='".date("d-m-Y H:i:s")."',
		edited_by='".$_SESSION['username']."' WHERE sno='{$_GET['delid']}'";
    $res=execute_query($sql);
    if(mysqli_error($db)){ 
        $msg .= '<p class="alert alert-danger">Error # 1.01 : '.mysqli_error($db).'>> '.$sql.'</p>';
    }
    if($msg==""){
        $msg = '<p class="alert alert-danger">Data Deleted successfully</p>';
    }

}

if(!isset($_POST['search'])){
	
    $_POST['division_name']="";
    $_POST['district_name']="";
    $_POST['tehseel_name']="";
    $_POST['block_name']="";
    $_POST['society_name']="";
    $_POST['society_type']="";
    $_POST['invname']="";
    $_POST['invfather']="";
    $_POST['invmobno']="";
    $_POST['invAddress']="";
    
    $_POST['row_count']="1";
    $_POST['member_name1']="";
    $_POST['member_father_name1']="";
    $_POST['member_mob1'] = '';
    $_POST['member_tehseel1'] = '';
    $_POST['member_block1'] = '';
    $_POST['memberType1'] = '';


    $_POST['edit_sno'] = '';
    $_POST['id'] = '';
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

	 <div class="row">		
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="text-center"></h3>			
                    <?php echo $msg;?>
					<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" >
                                <?php echo $msg; ?>
                                <h4>सहकारिता महासम्मेलन </h4>
                                <div class="col-sm-12">
                                    <div class="row">	
                                         <div class="col-md-3 form-group">
                                            <label>संस्था का प्रकार*</label>
                                            <select name="society_type" id="society_type"   class="form-control" onChange="appendLevel(this.value);fillMandal(this.value)" required>
                                            <!-- fillSocietyName(this.value); -->
                                                <option value="">--SELECT--</option>
                                                <?php
                                                $sql = 'select * from sammelen_society_types ORDER BY abs(`sammelen_society_types`.`sort`)';
                                                $result = execute_query($sql);
                                                while($row = mysqli_fetch_assoc($result)){
                                                    echo '<option value="'.$row['sno'].'" ';
                                                    if($_POST['society_type']==$row['sno']){
                                                        echo ' selected="selected" ';
                                                    }
                                                    echo '>'.$row['stypename'].'</option>';	
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        </div>
                                    <div class="row " id="level_wrap">

                                    </div>
                                </div>
                                
                                <div class="col-md-12 text-center">
                                <button  class="btn btn-info" id="search" type="submit" name="search">search </button>
                                </div>
                            </form>
                </div>
            </div>
        </div>
    </div>
	
	
    <div class="row">		
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="text-center">सहकारिता महा-सम्मेलन </h3>			
                    <?php echo $msg;?>
                    <table class="table table-hover table-bordered table-striped">
                        <tr>
                            <th>SNo.</th>
                            <th>मण्डल</th>
                            <th>जनपद </th>
                            <th>तहसील</th>
                            <th>विकासखण्ड </th>
                            <th>समिति के प्रकार </th>
                            <th>समिति का नाम </th>
                            <th>कुल सदस्य</th>
                            <th>View</th>
                            <th>Status</th>
                            <!-- <th>ID Card</th> -->
                            <th>DELETE</th>
                            <th>Edit</th>
                            <!-- <th></th>
                            <th></th> -->

                        </tr>
                        <?php
							if($_SESSION['usertype']=="sadmin"){
								 $sql="SELECT * FROM sammelen_invoice WHERE status='1'";
								
							}else{
								 $sql="SELECT * FROM sammelen_invoice WHERE status='1' and created_by='".$_SESSION['username']."'";
								
							}
							if(isset($_POST['search'])){
								if(isset($_POST['society_type'])){
									if($_POST['society_type']!=''){
										$sql .= ' and society_type_id="'.$_POST['society_type'].'"';
									}
								}
								if(isset($_POST['society_type'])){
									if($_POST['society_type']!=''){
										$sql .= ' and society_type_id="'.$_POST['society_type'].'"';
									}
								}
								if(isset($_POST['society_type'])){
									if($_POST['society_type']!=''){
										$sql .= ' and society_type_id="'.$_POST['society_type'].'"';
									}
								}
								if(isset($_POST['society_type'])){
									if($_POST['society_type']!=''){
										$sql .= ' and society_type_id="'.$_POST['society_type'].'"';
									}
								}
								if(isset($_POST['society_type'])){
									if($_POST['society_type']!=''){
										$sql .= ' and society_type_id="'.$_POST['society_type'].'"';
									}
								}
								
								
							}
								
							
							echo $sql;
                            $res=execute_query($sql);
                            $totalmember=0;
                            if(mysqli_num_rows($res)>0){
                                $i=1;
                                while($row=mysqli_fetch_assoc($res)){
                                    //mandal name
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
                                    
                                    
                                    // all members Count

                                    $membercount="SELECT * FROM sammelen_trans WHERE sammelen_invoice_id ='{$row['sno']}'";
                                    $membercountres=mysqli_query($db,$membercount);
                                    $membercountrow=mysqli_num_rows($membercountres);
                                    $totalmember+=$membercountrow;

                                    // array("id"=>$row['sno'], "society_name"=>$row['col4']);
                                    echo "<tr><td>".$i++."</td>
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
                                    <td>".$membercountrow."</td>";
                                    ?>
                                        <td><a href="sammelen_reg_view.php?id=<?php echo $row['sno']?>" class="btn btn-warning btn-sm" target="_blank">View</a></td>
										<td>
											<?php 
											if ($row['verify_status']==1){
												echo "<button class='btn btn-success btn-sm'>Approved</button>";
											}elseif($row['verify_status']==2){
												echo'<button class="btn btn-danger btn-sm">Rejected</button>';
											}else{
												echo'<button class="btn btn-info btn-sm">Pending</button>';
											}
											
											?>
										</td>
                                        <!-- <td><a href="sammelen_reg_id_card.php?id=<?php //echo $row['sno']?>" class="btn btn-primary btn-sm" target="_blank">CARD</a></td></br><a href='sammelen_reg_id_card.php?id=" . $row['sno'] . "' class='btn btn-primary btn-sm' target='_blank'>CARD</a> -->
                                
										<?php 
											if ($row['verify_status']==0){
											echo '<td><a href="' . $_SERVER['PHP_SELF'] . '?delid=' . $row['sno'] . '" onClick="return confirm(\'Are you sure?\')" class="btn btn-danger btn-sm">Delete</a></td>';
											
											echo "<td><a href='edit_sammelen_reg_form.php?eid=" . $row['sno'] . "' class='btn btn-primary btn-sm' target='_blank'><span class='far fa-edit' aria-hidden='true' data-toggle='tooltip' title='Edit' ></span></a></td>";
											}else{
												
												echo "<td></td><td></td>";
											}
												
										?>

										
                                    <?php
                                    
                                    
                                    
                                }
                                ?>
                                    <tr>
                                        <th colspan="6"></th>
                                        <th class="text-center">Total Members</th>
                                        <th><?php echo $totalmember;?></th>
                                        <th colspan="4"></th>

                                    </tr>
                                
                                <?php
                            }
                        
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
		
<div id="preloader-wrapper">
   <div id="preloader"></div>
   <div class="preloader-section section-left"></div>
   <div class="preloader-section section-right"></div>
</div>
				
<script>
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
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fillSocietyName();fill_tehseel_name(this.value)" required></select></div><div class="col-md-3 form-group"><label>शाखा का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control" required></select></div>';
                $("#level_wrap").html(level);
			}
            else if(level=="1" && hasSociety=="1"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fillSocietyName()" required><option value="">--Select--</option></select></div><div class="col-md-3 form-group"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control" required></select></div>';
                
                $("#level_wrap").html(level);
            }else if(level=="2" && hasSociety=="1"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="" required>--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fillSocietyName();fill_tehseel_name(this.value)" required></select></div><div class="col-md-3 form-group"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control" required ></select></div>';
                $("#level_wrap").html(level);
            }else if(level=="3" && hasSociety=="1"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);" required><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fill_tehseel_name(this.value);" required></select></div>';
                level +='<div class="col-md-3 form-group"><label>तहसील</label><select name="tehseel_name" id="tehseel_name" tabindex=""  class="form-control" onChange="fill_block_name(this.value);fillSocietyName()" required></select></div><div class="col-md-3 form-group"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control" required ></select></div>';
                $("#level_wrap").html(level);
            }else if(level=="4" && hasSociety=="1"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);" required><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fill_tehseel_name(this.value);" required></select></div>';
                level +='<div class="col-md-3 form-group"><label>तहसील</label><select name="tehseel_name" id="tehseel_name" tabindex=""  class="form-control" onChange="fill_block_name(this.value);" required></select></div>';
                level +='<div class="col-md-3 form-group"><label>विकासखंड</label><select name="block_name" id="block_name" tabindex=""  class="form-control" onChange="fillSocietyName()" required></select></div><div class="col-md-3 form-group"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control" required ></select></div>';
                $("#level_wrap").html(level);
            }else if(level=="1" && hasSociety=="0"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fillSocietyName()" required><option value="">--Select--</option></select></div><div class="col-md-3 form-group" style="display:none;"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control" required ></select></div>';
                
                $("#level_wrap").html(level);
            }else if(level=="2" && hasSociety=="0"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);" required><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fillSocietyName();fill_tehseel_name(this.value)" required></select></div><div class="col-md-3 form-group" style="display:none;"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control" required ></select></div>';
                $("#level_wrap").html(level);
            }else if(level=="3" && hasSociety=="0"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);" required><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fill_tehseel_name(this.value);" required></select></div>';
                level +='<div class="col-md-3 form-group"><label>तहसील</label><select name="tehseel_name" id="tehseel_name" tabindex=""  class="form-control" onChange="fill_block_name(this.value);fillSocietyName()" required></select></div><div class="col-md-3 form-group" style="display:none;"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control" required ></select></div>';
                $("#level_wrap").html(level);
            }else if(level=="4" && hasSociety=="0"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);" required><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fill_tehseel_name(this.value);" required></select></div>';
                level +='<div class="col-md-3 form-group"><label>तहसील</label><select name="tehseel_name" id="tehseel_name" tabindex=""  class="form-control" onChange="fill_block_name(this.value);" required></select></div>';
                level +='<div class="col-md-3 form-group"><label>विकासखंड</label><select name="block_name" id="block_name" tabindex=""  class="form-control" onChange="fillSocietyName()" required></select></div><div class="col-md-3 form-group" style="display:none;"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control"  required ></select></div>';
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
			var txt = '<option value="">--Select--</option>';
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
			var txt = '<option value="">--Select--</option>';
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
			var txt = '<option value="">--Select--</option>';
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
			var txt = '<option value="">--Select--</option>';
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



    $('select[multiple]').multiselect({
        columns: 1,
        placeholder: 'Select options'
    });
</script>
<script>	

    $(document).ready(function() {
        //getLocation();
    });
</script>																						


				
<?php
page_footer_start();
?>
<!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
<script src="js/light-bootstrap-dashboard.js?v=1.4.0"></script>

<?php		
page_footer_end();
?>

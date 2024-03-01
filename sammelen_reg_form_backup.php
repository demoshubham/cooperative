<?php
include("scripts/settings.php");
$msg='';

$tab=1;

	
if(isset($_POST['sub'])){
   
    $sql="INSERT INTO `sahkariya_sammelen_invoice`(`mandal_id`, `district_id`, `tehsil_id`, `block_id`, `society_type_id`, `society_name_id`,  `creation_time` ) VALUES ('{$_POST['division_name']}','{$_POST['district_name']}','{$_POST['tehseel_name']}','{$_POST['block_name']}','{$_POST['society_type']}','{$_POST['society_name']}','".date("d-m-Y H:i:s")."')";
    $res=mysqli_query($db,$sql);
    if(mysqli_error($db)){ 
        $msg .= '<p class="alert alert-danger">Error # 1 : '.mysqli_error($db).'>> '.$sql.'</p>';
    }else{
        //transaction insertion
        $id=mysqli_insert_id($db);

        for($i=1;$i<=$_POST['sanchalak_id'];$i++){
            if($_POST['sanchalak_name'.$i]!=''){

                $sql = "INSERT INTO `sahkarita_sammelen_trans_sanchalak`( `sahkariya_sammelen_invoice_id`, `name`, `father_name`, `mobile_num`, `creation_time`) VALUES ('$id','".$_POST['sanchalak_name'.$i]."','".$_POST['sanchalak_father_name'.$i]."','".$_POST['sanchalak_mob'.$i]."','".date("d-m-Y H:i:s")."')";
                
                execute_query($sql);
                if(mysqli_error($db)){ 
                    $msg .= '<p class="alert alert-danger">Error # 1.01 : '.mysqli_error($db).'>> '.$sql.'</p>';
                }
            }
        }

        for($i=1;$i<=$_POST['sanstha_id'];$i++){
            if($_POST['sanstha_name'.$i]!=''){

                $sql = "INSERT INTO `sahkarita_sammelen_trans_sanstha`( `sahkariya_sammelen_invoice_id`, `name`, `father_name`, `mobile_num`, `creation_time`) VALUES ('$id','".$_POST['sanstha_name'.$i]."','".$_POST['sanstha_father_name'.$i]."','".$_POST['sanstha_mob'.$i]."','".date("d-m-Y H:i:s")."')";
                
                execute_query($sql);
                if(mysqli_error($db)){ 
                    $msg .= '<p class="alert alert-danger">Error # 1.01 : '.mysqli_error($db).'>> '.$sql.'</p>';
                }
            }
        }

        for($i=1;$i<=$_POST['new_sadsaya_id'];$i++){
            if($_POST['new_sadsaya_name'.$i]!=''){

                $sql = "INSERT INTO `sahkarita_sammelen_trans_new_sadsaya`( `sahkariya_sammelen_invoice_id`, `name`, `father_name`, `mobile_num`, `creation_time`) VALUES ('$id','".$_POST['new_sadsaya_name'.$i]."','".$_POST['new_sadsaya_father_name'.$i]."','".$_POST['new_sadsaya_mob'.$i]."','".date("d-m-Y H:i:s")."')";
                
                execute_query($sql);
                if(mysqli_error($db)){ 
                    $msg .= '<p class="alert alert-danger">Error # 1.01 : '.mysqli_error($db).'>> '.$sql.'</p>';
                }
            }
        }

        if($msg==""){
            $msg = '<p class="alert alert-success">Data Saved</p>';
            unset($_POST);
            goto postblank;
        }

    }


}else{
    
    postblank:
    $_POST['division_name']="";
    $_POST['district_name']="";
    $_POST['tehseel_name']="";
    $_POST['block_name']="";
    $_POST['society_name']="";
    $_POST['society_type']="";


    $_POST['sanchalak_id']="1";
    $_POST['sanchalak_name1']="";
    $_POST['sanchalak_father_name1']="";
    $_POST['sanchalak_mob1'] = '';

    $_POST['sanstha_id']="1";
    $_POST['sanstha_name1']="";
    $_POST['sanstha_father_name1']="";
    $_POST['sanstha_mob1'] = '';

    $_POST['new_sadsaya_id']="1";
    $_POST['new_sadsaya_name1']="";
    $_POST['new_sadsaya_father_name1']="";
    $_POST['new_sadsaya_mob1'] = '';



    $_POST['edit_sno'] = '';
    $_POST['id'] = '';
}

// print_r($_POST);
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
                    <div class="row d-flex my-auto">
                        <div class="col-md-12">
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" >
                                <?php echo $msg;?>
                                <h4>सहकारिता महा सम्मेलन </h4>
                                <div class="col-sm-12">
                                    <div class="row">	
                                         <div class="col-sm-3 form-group">
                                            <label>संस्था का प्रकार</label>
                                            <select name="society_type" id="society_type" tabindex="<?php echo $tab++; ?>"  class="form-control" onChange="appendLevel(this.value);fillMandal(this.value)">
                                            <!-- fillSocietyName(this.value); -->
                                                <option value="">--SELECT--</option>
                                                <?php
                                                $sql = 'select * from sammelen_society_types';
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
                                        
                                        <!-- <div class="col-sm-3 form-group">
                                            <label>समिति का प्रकार</label>
                                            <select name="society_type" id="society_type" tabindex="<?php //echo $tab++; ?>"  class="form-control" onChange="fill_society(this.value);">
                                            </select>
                                        </div> -->
                                        
                                        

                                    </div>
                                    <div class="row">
                                        

                                            
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <h4 class="card-title">प्रबंध समिति संचालक गण</h4></br>
                                    <?php 
                                    for($i=1;$i<=$_POST['sanchalak_id'];$i++){
                                    ?>
                                    <div class="row border rounded m-2 p-2 border-secondary" id="add_rows_length">
                                    <?php echo $i; ?>.
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>प्रतिभागी का नाम </label><br>
                                                <input type="text" name="sanchalak_name<?php echo $i; ?>" id="sanchalak_name<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $_POST['sanchalak_name'.$i]; ?>" <?php 
                                                ?> tabindex="<?php echo $tab++; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label>पिता का नाम  </label>
                                                <input type="text" name="sanchalak_father_name<?php echo $i; ?>" id="sanchalak_father_name<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $_POST['sanchalak_father_name'.$i]; ?>"  tabindex="<?php echo $tab++; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>मोबाईल नंबर </label>
                                                <input type="text" name="sanchalak_mob<?php echo $i; ?>" id="sanchalak_mob<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $_POST['sanchalak_mob'.$i]; ?>" tabindex="<?php echo $tab++; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-1 d-flex justify-content- align-items-center">
                                            <button type="button" class="btn btn-info pull-right" onClick="add_rows()" tabindex="<?php echo $tab++; ?>">Add</button>
                                        </div>
                                    </div>
                                    <?php  } ?>
                                    <div id="test"></div>
                                    <input type="hidden" name="sanchalak_id" id="sanchalak_id" value="<?php echo $_POST['sanchalak_id']; ?>">
                                </div>


                                <div class="col-md-12">
                                    <h4 class="card-title">संस्था के प्रतिनिधि/डेलिगेट </h4></br>
                                    <?php 
                                    for($i=1;$i<=$_POST['sanstha_id'];$i++){
                                    ?>
                                    <div class="row border rounded m-2 p-2 border-secondary" id="add_rows_length">
                                    <?php echo $i; ?>.
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>प्रतिभागी का नाम </label><br>
                                                <input type="text" name="sanstha_name<?php echo $i; ?>" id="sanstha_name<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $_POST['sanstha_name'.$i]; ?>" <?php 
                                                ?> tabindex="<?php echo $tab++; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label>पिता का नाम  </label>
                                                <input type="text" name="sanstha_father_name<?php echo $i; ?>" id="sanstha_father_name<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $_POST['sanstha_father_name'.$i]; ?>"  tabindex="<?php echo $tab++; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>मोबाईल नंबर </label>
                                                <input type="text" name="sanstha_mob<?php echo $i; ?>" id="sanstha_mob<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $_POST['sanstha_mob'.$i]; ?>" tabindex="<?php echo $tab++; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-1 d-flex justify-content- align-items-center">
                                            <button type="button" class="btn btn-info pull-right" onClick="add_sanstha_rows()" tabindex="<?php echo $tab++; ?>">Add</button>
                                        </div>
                                    </div>
                                    <?php  } ?>
                                    <div id="sanstha_add"></div>
                                    <input type="hidden" name="sanstha_id" id="sanstha_id" value="<?php echo $_POST['sanstha_id']; ?>">
                                </div>

                                <div class="col-md-12">
                                    <h4 class="card-title">नए सदस्य</h4></br>
                                    <?php 
                                    for($i=1;$i<=$_POST['new_sadsaya_id'];$i++){
                                    ?>
                                    <div class="row border rounded m-2 p-2 border-secondary" id="add_rows_length">
                                    <?php echo $i; ?>.
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>प्रतिभागी का नाम </label><br>
                                                <input type="text" name="new_sadsaya_name<?php echo $i; ?>" id="new_sadsaya_name<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $_POST['new_sadsaya_name'.$i]; ?>" <?php 
                                                ?> tabindex="<?php echo $tab++; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label>पिता का नाम  </label>
                                                <input type="text" name="new_sadsaya_father_name<?php echo $i; ?>" id="new_sadsaya_father_name<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $_POST['new_sadsaya_father_name'.$i]; ?>"  tabindex="<?php echo $tab++; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>मोबाईल नंबर </label>
                                                <input type="text" name="new_sadsaya_mob<?php echo $i; ?>" id="new_sadsaya_mob<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $_POST['new_sadsaya_mob'.$i]; ?>" tabindex="<?php echo $tab++; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-1 d-flex justify-content- align-items-center">
                                            <button type="button" class="btn btn-info pull-right" onClick="add_new_sadsaya_rows()" tabindex="<?php echo $tab++; ?>">Add</button>
                                        </div>
                                    </div>
                                    <?php  } ?>
                                    <div id="new_sadsaya_add"></div>
                                    <input type="hidden" name="new_sadsaya_id" id="new_sadsaya_id" value="<?php echo $_POST['new_sadsaya_id']; ?>">
                                </div>
                        
                                <div class="col-md-12 text-center">
                                <button  class="btn btn-warning" id="submit" type="submit" name="sub">Submit</button>
                                </div>
                            </form>
                    </div>
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

    function add_rows(){
		var id = parseFloat($("#sanchalak_id").val());
		if(!id){
			id=0;
		}
		for(var i=1; i<=id; i++){
			if($("#sanchalak_name"+i).val()==''){
				alert("पंक्ति संख्या "+i+" खाली है");
				$("#sanchalak_name"+i).focus();
				return;
			}
		}
		id = id+1;

		
		var txt = '<div class="row border rounded m-2 p-2 border-secondary" id="add_rows_length">'+id+'<div class="col-md-4"><div class="form-group"><label>प्रतिभागी का नाम </label><br><input type="text" name="sanchalak_name'+id+'" id="sanchalak_name'+id+'" class="form-control" placeholder="" value="" tabindex="" ></div></div><div class="col-md-3"><div class="form-group"><label >पिता का नाम  </label><input type="text" name="sanchalak_father_name'+id+'" id="sanchalak_father_name'+id+'" class="form-control" placeholder=" "value="" tabindex="" ></div></div><div class="col-md-3"><div class="form-group"><label >मोबाईल नंबर </label><br><input type="text" name="sanchalak_mob'+id+'" id="sanchalak_mob'+id+'" class="form-control" placeholder="" value="" tabindex="" ></div></div><div class="col-md-1 d-flex justify-content- align-items-center"><button type="button" class="btn btn-info pull-right" onClick="add_rows()">Add</button></div></div>';
		$("#test").append(txt);
		$("#sanchalak_id").val(id);
	}	

    function add_sanstha_rows(){
		var id = parseFloat($("#sanstha_id").val());
		if(!id){
			id=0;
		}
		for(var i=1; i<=id; i++){
			if($("#sanstha_name"+i).val()=='' ){
				alert("पंक्ति संख्या "+i+" खाली है");
				$("#sanstha_name"+i).focus();
				return;
			}
		}
		id = id+1;

		

		var txt = '<div class="row border rounded m-2 p-2 border-secondary" id="add_rows_length">'+id+'<div class="col-md-4"><div class="form-group"><label>प्रतिभागी का नाम </label><br><input type="text" name="sanstha_name'+id+'" id="sanstha_name'+id+'" class="form-control" placeholder="" value="" tabindex="" ></div></div><div class="col-md-3"><div class="form-group"><label >पिता का नाम  </label><input type="text" name="sanstha_father_name'+id+'" id="sanstha_father_name'+id+'" class="form-control" placeholder=" "value="" tabindex="" ></div></div><div class="col-md-3"><div class="form-group"><label >मोबाईल नंबर </label><br><input type="text" name="sanstha_mob'+id+'" id="sanstha_mob'+id+'" class="form-control" placeholder="" value="" tabindex="" ></div></div><div class="col-md-1 d-flex justify-content- align-items-center"><button type="button" class="btn btn-info pull-right" onClick="add_sanstha_rows()">Add</button></div></div>';
		$("#sanstha_add").append(txt);
		$("#sanstha_id").val(id);
	}
    
    function add_new_sadsaya_rows(){
		var id = parseFloat($("#new_sadsaya_id").val());
		if(!id){
			id=0;
		}
		for(var i=1; i<=id; i++){
			if($("#new_sadsaya_name"+i).val()==''){
				alert("पंक्ति संख्या "+i+" खाली है");
				$("#new_sadsaya_name"+i).focus();
				return;
			}
		}
		id = id+1;

		

		var txt = '<div class="row border rounded m-2 p-2 border-secondary" id="add_rows_length">'+id+'<div class="col-md-4"><div class="form-group"><label>प्रतिभागी का नाम </label><br><input type="text" name="new_sadsaya_name'+id+'" id="new_sadsaya_name'+id+'" class="form-control" placeholder="" value="" tabindex="" ></div></div><div class="col-md-3"><div class="form-group"><label >पिता का नाम  </label><input type="text" name="new_sadsaya_father_name'+id+'" id="new_sadsaya_father_name'+id+'" class="form-control" placeholder=" "value="" tabindex="" ></div></div><div class="col-md-3"><div class="form-group"><label >मोबाईल नंबर </label><br><input type="text" name="new_sadsaya_mob'+id+'" id="new_sadsaya_mob'+id+'" class="form-control" placeholder="" value="" tabindex="" ></div></div><div class="col-md-1 d-flex justify-content- align-items-center"><button type="button" class="btn btn-info pull-right" onClick="add_new_sadsaya_rows()">Add</button></div></div>';
		$("#new_sadsaya_add").append(txt);
		$("#new_sadsaya_id").val(id);
	}

 
<?php



?>


</script>

				
<script>

var actionUrl = 'scripts/ajax.php';
function appendLevel(val){
    // console.log("hello");

    var data = {term:"b", id:"appendLevel", val:val};
	$.ajax({
        type: "POST",
        url: actionUrl,
        data: data, // serializes the form's elements.
        success: function(data){
            if(data=="1"){
                var level='<div class="col-sm-3 form-group"><label>मण्डल</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fillSocietyName()"><option value="">--Select--</option></select></div><div class="col-md-3 form-group"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control" ></select></div>';
                
                $("#level_wrap").html(level);
            }else if(data=="2"){
                var level='<div class="col-sm-3 form-group"><label>मण्डल</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="">--Select--</option></select></div>';
                level+='<div class="col-sm-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fillSocietyName()"></select></div><div class="col-md-3 form-group"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control" ></select></div>';
                $("#level_wrap").html(level);
            }else if(data=="3"){
                var level='<div class="col-sm-3 form-group"><label>मण्डल</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="">--Select--</option></select></div>';
                level+='<div class="col-sm-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fill_tehseel(this.value);"></select></div>';
                level +='<div class="col-sm-3 form-group"><label>तहसील</label><select name="tehseel_name" id="tehseel_name" tabindex=""  class="form-control" onChange="fillSocietyName()"></select></div><div class="col-md-3 form-group"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control" ></select></div>';
                $("#level_wrap").html(level);
            }else if(data=="4"){
                var level='<div class="col-sm-3 form-group"><label>मण्डल</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="">--Select--</option></select></div>';
                level+='<div class="col-sm-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fill_tehseel(this.value);"></select></div>';
                level +='<div class="col-sm-3 form-group"><label>तहसील</label><select name="tehseel_name" id="tehseel_name" tabindex=""  class="form-control" onChange="fill_block(this.value);"></select></div>';
                level +='<div class="col-sm-3 form-group"><label>विकासखंड</label><select name="block_name" id="block_name" tabindex=""  class="form-control" onChange="fillSocietyName()"></select></div><div class="col-md-3 form-group"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control" ></select></div>';
                $("#level_wrap").html(level);
            }
            
        }
    });
}

function fill_district_name(val){
	var data = {"term":"b", "id":"district", "val":val};
	$.ajax({
        type: "POST",
        url: actionUrl,
        data: data, // serializes the form's elements.
        success: function(data){
			var txt = '<option value="">--Select--</option>';
			data = JSON.parse(data);
			$.each(data, function(key, value){
				txt += '<option value="'+value.id+'">'+value.district_name+'</option>';
				
			});
          	$("#district_name").html(txt);
        }
    });
}

function fillSocietyName(){
    
	// console.log("hello");
    var vals= $("#society_type").val();
    console.log(vals);
	var district = $("#district_name").val();
    var tehsil = $("#tehseel_name").val();
    var block = $("#block_name").val();
	var data = {term:"b", id:"societytype",val:vals,dis:district,teh:tehsil,block:block};
	$.ajax({
        type: "POST",
        url: actionUrl,
        data: data, // serializes the form's elements.
        success: function(data){
			var txt = '<option value="">--Select--</option>';
			data = JSON.parse(data);
			if (typeof data.nodata !== 'undefined') {
				
			}
			else{
				//console.log(data);
				$.each(data, function(key, value){
					txt += '<option value="'+value.id+'" ';
					if(value.id==selected){
						txt += ' selected="selected" ';
					}
					txt += '>'+value.data.SocietyName+'</option>';

				});
			}
			
			txt += '<option value="custom">Other</option>';
			$("#society_name").html(txt);
			$("#society_details").show();
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
			
			$("#division_name").html(data);
			// $("#society_details").show();
        }
    });
}

    


function fillSocietyName() {
    var vals = $("#society_type").val();
    console.log(vals);
    var district = $("#district_name").val();
    var tehsil = $("#tehseel_name").val();
    var block = $("#block_name").val();
    var data = { term: "b", id: "societytype", val: vals, dis: district, teh: tehsil, block: block };

    $.ajax({
        type: "POST",
        url: actionUrl,
        data: data,
        success: function (data) {
            var txt = '<option value="">--Select--</option>';

            data = JSON.parse(data);

            if (typeof data.nodata !== 'undefined') {
                console.error("No data found.");
            } else {
                $.each(data, function (key, value) {
                    txt += '<option value="' + value.id + '" ';
                   
                    txt += '>' + value.data.SocietyName + '</option>';
                });
            }

            txt += '<option value="custom">Other</option>';
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

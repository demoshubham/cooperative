<?php
include("scripts/settings.php");
logvalidate();
$msg='';


function sanetize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

	
if(isset($_POST['sub'])){

    $divname=sanetize_input($_POST['division_name']);
    $disname=sanetize_input($_POST['district_name']);
    $tehname=sanetize_input($_POST['tehseel_name']);
    $blockname=sanetize_input($_POST['block_name']);
    $stype=sanetize_input($_POST['society_type']);
    $sname=sanetize_input($_POST['society_name']);
    $invname=sanetize_input($_POST['invname']);
    $invfather=sanetize_input($_POST['invfather']);
    $invmobno=sanetize_input($_POST['invmobno']);

    
   
    $sql="INSERT INTO `sammelen_invoice`(`mandal_id`, `district_id`, `tehsil_id`, `block_id`, `society_type_id`, `society_name_id`,`invName`, `invFatherName`, `invMobno`,  `creation_time`,`created_by` ) VALUES ('{$divname}','{$disname}','{$tehname}','{$blockname}','{$stype}','{$sname}','{$invname}','{$invfather}',{$invmobno},'".date("d-m-Y H:i:s")."','".$_SESSION['username']."')";
    $res=mysqli_query($db,$sql);
    if(mysqli_error($db)){ 
        $msg .= '<p class="alert alert-danger">Error # 1 : '.mysqli_error($db).'>> '.$sql.'</p>';
    }else{
        //transaction insertion
        $id=mysqli_insert_id($db);

        for($i=1;$i<=$_POST['row_count'];$i++){
            if($_POST['member_name'.$i]!=''){

                $name=sanetize_input($_POST['member_name'.$i]);
                $fname=sanetize_input($_POST['member_father_name'.$i]);
                $mobno=sanetize_input($_POST['member_mob'.$i]);
                $teh=sanetize_input($_POST['member_tehseel'.$i]);
                $block=sanetize_input($_POST['member_block'.$i]);
                $memberType=sanetize_input($_POST['memberType'.$i]);

                
                

                $sql = "INSERT INTO `sammelen_trans`( `sammelen_invoice_id`, `name`, `father_name`, `mobile_num`,`tehseelId`, `blociId`,`memberType`,`creation_time`,`created_by` ) VALUES ('$id','{$name}','{$fname}','{$mobno}','{$teh}','{$block}','{$memberType}','".date("d-m-Y H:i:s")."','".$_SESSION['username']."')";
                
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
    $_POST['invname']="";
    $_POST['invfather']="";
    $_POST['invmobno']="";
    




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
                                <h4>सहकारिता महासम्मेलन </h4>
                                <div class="col-sm-12">
                                    <div class="row">	
                                         <div class="col-md-3 form-group">
                                            <label>संस्था का प्रकार</label>
                                            <select name="society_type" id="society_type"   class="form-control" onChange="appendLevel(this.value);fillMandal(this.value)">
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
                                        <div class="col-md-3">
                                                <label for="invname">प्रतिनिधि का नाम </label>
                                                <input type="text" name="invname" id="invname" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="invfather">पिता का नाम </label>
                                            <input type="text" name="invfather" id="invfather" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="invmobno">मोबाईल नंबर</label>
                                            <input type="number" name="invmobno" id="invmobno" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row " id="level_wrap">

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <h4 class="card-title">सदस्यों की सूचना</h4></br>
                                    <?php 
                                    for($i=1;$i<=$_POST['row_count'];$i++){
                                    ?>
                                    <div class="row border rounded m-2 p-2 border-secondary" id="add_rows_length">
                                    <?php echo $i; ?>.
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>प्रतिभागी का नाम </label><br>
                                                <input type="text" name="member_name<?php echo $i; ?>" id="member_name<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $_POST['member_name'.$i]; ?>" <?php 
                                                ?> >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <label>पिता का नाम  </label>
                                                <input type="text" name="member_father_name<?php echo $i; ?>" id="member_father_name<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $_POST['member_father_name'.$i]; ?>"  >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>मोबाईल नंबर </label>
                                                <input type="number" name="member_mob<?php echo $i; ?>" id="member_mob<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $_POST['member_mob'.$i]; ?>" >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>तहसील</label>
                                                <select class="form-control" name="member_tehseel<?php echo $i; ?>" id="member_tehseel<?php echo $i; ?>"  required onChange="member_fill_block_name(this.value,1);">
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>विकासखंड</label>
                                                <select class="form-control" name="member_block<?php echo $i; ?>" id="member_block<?php echo $i; ?>"  required>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <select name="memberType1" id="memberType1"  class="form-control" >
                                                    <option value="" selected disabled>--SELECT--</option>
                                                    <option value="प्रबंध समिति संचालक गण">प्रबंध समिति संचालक गण</option>
                                                    <option value="संस्था के प्रतिनिधि/डेलीगेट">संस्था के प्रतिनिधि/डेलीगेट </option>
                                                    <option value="नए सदस्य">नए सदस्य</option>
                                                    <option value="अन्य">अन्य</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-1 d-flex justify-content- align-items-center">
                                            <button type="button" class="btn btn-info pull-right" onClick="add_rows()" >Add [ + ]</button>
                                        </div>
                                    </div>
                                    <?php  } ?>
                                    <div id="add_wrap"></div>
                                    <input type="hidden" name="row_count" id="row_count" value="<?php echo $_POST['row_count']; ?>">
                                </div>

                        
                                <div class="col-md-12 text-center">
                                <button  class="btn btn-success" id="submit" type="submit" name="sub">Submit (प्रेषित करें)</button>
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
		var id = parseFloat($("#row_count").val());
		if(!id){
			id=0;
		}
		for(var i=1; i<=id; i++){
			if($("#member_name"+i).val()==''){
				alert("पंक्ति संख्या "+i+" खाली है");
				$("#member_name"+i).focus();
				return;
			}
		}
		id = id+1;

		
		var txt = '<div class="row border rounded m-2 p-2 border-secondary" id="add_rows_length">'+id+'<div class="col-md-2"><div class="form-group"><label>प्रतिभागी का नाम </label><br><input type="text" name="member_name'+id+'" id="member_name'+id+'" class="form-control" placeholder="" value="" tabindex="" ></div></div><div class="col-md-2"><div class="form-group"><label >पिता का नाम  </label><input type="text" name="member_father_name'+id+'" id="member_father_name'+id+'" class="form-control" placeholder=" "value="" tabindex="" ></div></div><div class="col-md-2"><div class="form-group"><label >मोबाईल नंबर </label><br><input type="number" name="member_mob'+id+'" id="member_mob'+id+'" class="form-control" placeholder="" value="" tabindex="" ></div></div><div class="col-md-2"><div class="form-group"><label>तहसील</label><select class="form-control" name="member_tehseel'+id+'" id="member_tehseel'+id+'"  onChange="member_fill_block_name(this.value,'+id+');"></select></div></div><div class="col-md-2"><div class="form-group"><label>विकासखंड</label><select class="form-control" name="member_block'+id+'" id="member_block'+id+'" ></select></div></div><div class="col-md-2"><div class="form-group"><select name="memberType'+id+'" id="memberType'+id+'"  class="form-control" ><option value="" selected disabled>--SELECT--</option><option value="प्रबंध समिति संचालक गण">प्रबंध समिति संचालक गण</option><option value="संस्था के प्रतिनिधि/डेलीगेट">संस्था के प्रतिनिधि/डेलीगेट </option><option value="नए सदस्य">नए सदस्य</option><option value="अन्य">अन्य</option></select></div></div><div class="col-md-1 d-flex justify-content- align-items-center"><button type="button" class="btn btn-info pull-right" onClick="add_rows()">Add [ + ]</button></div></div>';
		$("#add_wrap").append(txt);
        $("#member_tehseel"+id).html($("#member_tehseel1").html());
		$("#row_count").val(id);
	}	

var actionUrl = 'scripts/ajax.php';

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
				var level='<div class="col-md-3 form-group"><label>मण्डल</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fillSocietyName();fill_tehseel_name(this.value)"></select></div><div class="col-md-3 form-group"><label>शाखा का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control" ></select></div>';
                $("#level_wrap").html(level);
			}
            else if(level=="1" && hasSociety=="1"){
                var level='<div class="col-md-3 form-group"><label>मण्डल</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fillSocietyName()"><option value="">--Select--</option></select></div><div class="col-md-3 form-group"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control" ></select></div>';
                
                $("#level_wrap").html(level);
            }else if(level=="2" && hasSociety=="1"){
                var level='<div class="col-md-3 form-group"><label>मण्डल</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fillSocietyName();fill_tehseel_name(this.value)"></select></div><div class="col-md-3 form-group"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control" ></select></div>';
                $("#level_wrap").html(level);
            }else if(level=="3" && hasSociety=="1"){
                var level='<div class="col-md-3 form-group"><label>मण्डल</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fill_tehseel_name(this.value);"></select></div>';
                level +='<div class="col-md-3 form-group"><label>तहसील</label><select name="tehseel_name" id="tehseel_name" tabindex=""  class="form-control" onChange="fill_block_name(this.value);fillSocietyName()"></select></div><div class="col-md-3 form-group"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control" ></select></div>';
                $("#level_wrap").html(level);
            }else if(level=="4" && hasSociety=="1"){
                var level='<div class="col-md-3 form-group"><label>मण्डल</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fill_tehseel_name(this.value);"></select></div>';
                level +='<div class="col-md-3 form-group"><label>तहसील</label><select name="tehseel_name" id="tehseel_name" tabindex=""  class="form-control" onChange="fill_block_name(this.value);"></select></div>';
                level +='<div class="col-md-3 form-group"><label>विकासखंड</label><select name="block_name" id="block_name" tabindex=""  class="form-control" onChange="fillSocietyName()"></select></div><div class="col-md-3 form-group"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control" ></select></div>';
                $("#level_wrap").html(level);
            }else if(level=="1" && hasSociety=="0"){
                var level='<div class="col-md-3 form-group"><label>मण्डल</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fillSocietyName()"><option value="">--Select--</option></select></div><div class="col-md-3 form-group" style="display:none;"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control"  ></select></div>';
                
                $("#level_wrap").html(level);
            }else if(level=="2" && hasSociety=="0"){
                var level='<div class="col-md-3 form-group"><label>मण्डल</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fillSocietyName();fill_tehseel_name(this.value)"></select></div><div class="col-md-3 form-group" style="display:none;"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control"  ></select></div>';
                $("#level_wrap").html(level);
            }else if(level=="3" && hasSociety=="0"){
                var level='<div class="col-md-3 form-group"><label>मण्डल</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fill_tehseel_name(this.value);"></select></div>';
                level +='<div class="col-md-3 form-group"><label>तहसील</label><select name="tehseel_name" id="tehseel_name" tabindex=""  class="form-control" onChange="fill_block_name(this.value);fillSocietyName()"></select></div><div class="col-md-3 form-group" style="display:none;"><label>समिति का नाम</label><select name="society_name" id="society_name" tabindex=""  class="form-control"  ></select></div>';
                $("#level_wrap").html(level);
            }else if(level=="4" && hasSociety=="0"){
                var level='<div class="col-md-3 form-group"><label>मण्डल</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="">--Select--</option></select></div>';
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
			
			$("#division_name").html(data);
			// $("#society_details").show();
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
			// var txt = '<option value="">--Select--</option>';
			// data = JSON.parse(data);
			// $.each(data, function(key, value){
			// 	txt += '<option value="'+value.id+'">'+value.district_name+'</option>';
				
			// });
          	$("#district_name").html(data);
        }
    });
}


function fill_tehseel_name(val){
	var data = {term:"b", id:"tehseelname", val:val};
	$.ajax({
        type: "POST",
        url: actionUrl,
        data: data, // serializes the form's elements.
        success: function(data){
			var txt = '<option value="">--Select--</option>';
			data = JSON.parse(data);
			$.each(data, function(key, value){
				txt += '<option value="'+value.id+'">'+value.tehseel_name+'</option>';
				
			});
			$("#tehseel_name").html(txt);
            $("#member_tehseel1").html(txt);
            
        }
    });
}
	

function fill_block_name(val){
	var data = {term:"b", id:"blockname", val:val};
	$.ajax({
        type: "POST",
        url: actionUrl,
        data: data, // serializes the form's elements.
        success: function(data){
			var txt = '<option value="">--Select--</option>';
			data = JSON.parse(data);
			$.each(data, function(key, value){
				txt += '<option value="'+value.id+'">'+value.block_name+'</option>';
				
			});
          	$("#block_name").html(txt);
            
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
            var txt = '';

            data = JSON.parse(data);

            if (typeof data.nodata !== 'undefined') {
                console.error("No data found.");
            } else {
                $.each(data, function (key, value) {
                    txt += '<option value="' + value.id + '" ';
                   
                    txt += '>' + value.data.SocietyName + '</option>';
                });
            }

            //txt += '<option value="custom">Other</option>';
            $("#society_name").html(txt);
            $("#society_details").show();
        }
    });
}



function member_fill_block_name(val,idforappend){
	var data = {term:"b", id:"blockname", val:val};
	$.ajax({
        type: "POST",
        url: actionUrl,
        data: data, // serializes the form's elements.
        success: function(data){
			var txt = '<option value="">--Select--</option>';
			data = JSON.parse(data);
			$.each(data, function(key, value){
				txt += '<option value="'+value.id+'">'+value.block_name+'</option>';
				
			});
          	$("#member_block"+idforappend).html(txt);
            
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

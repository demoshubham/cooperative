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

  echo "<pre>";
print_r($_POST);	
echo "</pre>";
echo $_POST['count'];
if(isset($_POST['sub'])){

    for($i=1;$i<=$_POST['count'];$i++){
        // If no duplicates, proceed with data insertion
            
        $divname=sanetize_input($_POST['division_name']);
        $disname=sanetize_input($_POST['district_name']);
        $tehname=sanetize_input($_POST['tehseel_name']);
        $blockname=sanetize_input($_POST['block_name']);
        $stype=sanetize_input($_POST['society_type']);
        $sname=sanetize_input($_POST['new_society_name'.$i]);

        $sqls = "select * from  sammelen_master_tehseel where TehsilCode='{$tehname}'";
        $ress=execute_query($sqls);
        $rows=mysqli_fetch_assoc($ress);

        $tehname=$rows['Tehsilid'];


        echo $sql="INSERT INTO `sammelen_society`( `SocietyTypeId`, `SocietyName`,`DistrictCode`,`CreatedOn`, `CreatedBy`,`TehsilId`, `BlockId`) VALUES ('{$stype}','{$sname}','{$disname}','".date("d-m-Y H:i:s")."','{$_SESSION['username']}','{$tehname}','{$blockname}')";

        $res = mysqli_query($db, $sql);

        if (mysqli_error($db)) {
            $msg .= '<p class="alert alert-danger">Error # 1 : ' . mysqli_error($db) . '>> ' . $sql . '</p>';
        } else {
           
                $msg = '<p class="alert alert-success">Data Saved</p>';
            
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
                                <?php echo $msg; ?>
                                <h4>सहकारिता महासम्मेलन </h4>
                                <div class="col-sm-12">
                                    <div class="row">	
                                         <div class="col-md-3 form-group">
                                            <label>संस्था का प्रकार</label>
                                            <select name="society_type" id="society_type"   class="form-control" onChange="appendLevel(this.value);fillMandal(this.value)">
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
                                    <div class="row" id="apendsname">
                                        <div class="col-md-3 form-group"><label>समिति का नाम</label><input type="text" name="new_society_name1" id="" tabindex=""  class="form-control" > <input type="button" value="Add" id="addsname" onclick="addsnamebox()" class="btn btn-sm btn-warning">
                                        <input type="number" name="count" id="count" value="1"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <input type="submit" value="Submit" name="sub" class="btn btn-warning">
                                        </div>
                                    </div>
                                </div>
                                    <div class="d-flex justify-content-center">
                                        <div id="society_name" >

                                        </div>  
                                    </div>
                        
                            </form>
                    </div>
                    <div class="row">
                        
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
    function addsnamebox() {
        // Get the current count value
        var count = parseInt(document.getElementById('count').value);

        // Increment the count for the next input box
        count++;

        // Create a new input element
        var newInput = document.createElement('input');
        newInput.type = 'text';
        newInput.name = 'new_society_name' + count;
        newInput.id = 'new_society_name' + count;
        newInput.className = 'form-control';

        // Create a new label element
        var newLabel = document.createElement('label');
        newLabel.innerHTML = 'समिति का नाम';

        // Create a new div element to wrap the label and input
        var newDiv = document.createElement('div');
        newDiv.className = 'col-md-3 form-group';
        newDiv.appendChild(newLabel);
        newDiv.appendChild(newInput);

        // Append the new div to the parent container
        var parentContainer = document.querySelector('#apendsname');
        parentContainer.appendChild(newDiv);

        // Update the count value
        document.getElementById('count').value = count;
    }
 $(document).ready( function () {
    /*$('#general_stat_table').DataTable({
		paging: false,
		fixedHeader: true,
		colReorder: true
		});
	});	*/

	
	var t = $('#general_stat_table').DataTable({
		// paging: false
    });
 
    
});



var actionUrl = 'scripts/ajax.php';

function appendLevel(val){
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
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fillSocietyName();fill_tehseel_name(this.value)"></select></div><div class="col-md-3 form-group"><label>शाखा का नाम</label><input type="text" name="new_society_name" id="" tabindex=""  class="form-control" ></div>';
                $("#level_wrap").html(level);
			}
            else if(level=="1" && hasSociety=="1"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fillSocietyName()"><option value="">--Select--</option></select></div>';
                
                $("#level_wrap").html(level);
            }else if(level=="2" && hasSociety=="1"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fillSocietyName();fill_tehseel_name(this.value)"></select></div>';
                $("#level_wrap").html(level);
            }else if(level=="3" && hasSociety=="1"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fill_tehseel_name(this.value);"></select></div>';
                level +='<div class="col-md-3 form-group"><label>तहसील</label><select name="tehseel_name" id="tehseel_name" tabindex=""  class="form-control" onChange="fill_block_name(this.value);fillSocietyName()"></select></div>';
                $("#level_wrap").html(level);
            }else if(level=="4" && hasSociety=="1"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fill_tehseel_name(this.value);"></select></div>';
                level +='<div class="col-md-3 form-group"><label>तहसील</label><select name="tehseel_name" id="tehseel_name" tabindex=""  class="form-control" onChange="fill_block_name(this.value);"></select></div>';
                level +='<div class="col-md-3 form-group"><label>विकासखंड</label><select name="block_name" id="block_name" tabindex=""  class="form-control" onChange="fillSocietyName()"></select></div>';
                $("#level_wrap").html(level);
            }else if(level=="1" && hasSociety=="0"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fillSocietyName()"><option value="">--Select--</option></select></div><div class="col-md-3 form-group" style="display:none;"><label>समिति का नाम</label><input type="text"</div>';
                
                $("#level_wrap").html(level);
            }else if(level=="2" && hasSociety=="0"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fillSocietyName();fill_tehseel_name(this.value)"></select></div><div class="col-md-3 form-group" style="display:none;"><label>समिति का नाम</label><input type="text"</div>';
                $("#level_wrap").html(level);
            }else if(level=="3" && hasSociety=="0"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fill_tehseel_name(this.value);"></select></div>';
                level +='<div class="col-md-3 form-group"><label>तहसील</label><select name="tehseel_name" id="tehseel_name" tabindex=""  class="form-control" onChange="fill_block_name(this.value);fillSocietyName()"></select></div><div class="col-md-3 form-group" style="display:none;"><label>समिति का नाम</label><input type="text"</div>';
                $("#level_wrap").html(level);
            }else if(level=="4" && hasSociety=="0"){
                var level='<div class="col-md-3 form-group"><label>मण्डल (कमिश्नरी)</label><select name="division_name" id="division_name" tabindex=""  class="form-control" onChange="fill_district_name(this.value);"><option value="">--Select--</option></select></div>';
                level+='<div class="col-md-3 form-group"><label>जनपद</label><select name="district_name" id="district_name" tabindex=""  class="form-control" onChange="fill_tehseel_name(this.value);"></select></div>';
                level +='<div class="col-md-3 form-group"><label>तहसील</label><select name="tehseel_name" id="tehseel_name" tabindex=""  class="form-control" onChange="fill_block_name(this.value);"></select></div>';
                level +='<div class="col-md-3 form-group"><label>विकासखंड</label><select name="block_name" id="block_name" tabindex=""  class="form-control" onChange="fillSocietyName()"></select></div><div class="col-md-3 form-group" style="display:none;"><label>समिति का नाम</label><input type="text"</div>';
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


function fill_tehseel_name(val, selected=''){
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
            $("#member_tehseel1").html(txt);
            
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
        url: "add_society_name_ajax.php",
        data: data,
        success: function (data) {
            var txt = '';

            // data = JSON.parse(data);

            if (typeof data.nodata !== 'undefined') {
                console.error("No data found.");
            } else {
                 //txt += '<option value="custom">Other</option>';
                $("#society_name").html(data);
                $("#society_details").show();
            }

           
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
        appendLevel($("#society_type").val());
		fillMandal($("#society_type").val(), <?php echo $_POST['division_name']; ?>);
		fill_district_name(<?php echo $_POST['division_name']; ?>,<?php echo $_POST['district_name']; ?>);
		fill_tehseel_name(<?php echo $_POST['district_name']; ?>,<?php echo $_POST['tehseel_name']; ?>);
		fill_block_name(<?php echo $_POST['tehseel_name']; ?>,<?php echo $_POST['block_name']; ?>);
		fillSocietyName($("#society_type").val(), <?php echo $_POST['district_name']; ?>, <?php echo $_POST['tehseel_name']; ?>, <?php echo $_POST['block_name']; ?>,<?php echo $_POST['society_name']; ?>);
		
    });
	
	
	
<?php
	if(isset($_GET['eid'])){
?>
	$(document).ready(function() {
       
        appendLevel(<?php echo $_POST['society_type']; ?>));
		fillMandal($("#society_type").val(), <?php echo $_POST['division_name']; ?>);
		fill_district_name(<?php echo $_POST['division_name']; ?>,<?php echo $_POST['district_name']; ?>);
		fill_tehseel_name(<?php echo $_POST['district_name']; ?>,<?php echo $_POST['tehseel_name']; ?>);
		fill_block_name(<?php echo $_POST['tehseel_name']; ?>,<?php echo $_POST['block_name']; ?>);
		fillSocietyName($("#society_type").val(), <?php echo $_POST['district_name']; ?>, <?php echo $_POST['tehseel_name']; ?>, <?php echo $_POST['block_name']; ?>,<?php echo $_POST['society_name']; ?>);
		
    }); 
<?php
	}
?>	
</script>																						


				
<?php
page_footer_start();
?>
<!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
<script src="js/light-bootstrap-dashboard.js?v=1.4.0"></script>

<?php		
page_footer_end();
?>

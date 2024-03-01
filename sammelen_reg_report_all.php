<?php

include("scripts/settings.php");
logvalidate();
page_header_start();
$msg='';

$tab=1;

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
                        <?php echo $msg; ?>
                        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" id="user_form" name="user_form">
                            
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
                                                echo '>'.$row['stypename'].'</option>';	
                                            }
                                            ?>
                                        </select>
                                    </div>

                                </div>
                                <div class="row " id="level_wrap">            

                                </div>
                                
                                <input type="submit" class="btn btn-info btn-fill pull-right" value="Search" name="submit" id="submit" />
                            
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    
    <div class="row">
        <div class="m-auto p-5">
            <h2 class="text-center">प्रबंध समिति संचालक गण </h2>
            <?php
            $first="SELECT * FROM sammelen_trans LEFT JOIN sammelen_invoice on sammelen_trans.sammelen_invoice_id= sammelen_invoice.sno WHERE sammelen_invoice.status='1' and memberType='प्रबंध समिति संचालक गण'" ;
            if(isset($_POST['submit'])){
                if(isset($_POST['society_type'])){
                    $first.="and sammelen_invoice.society_type_id='{$_POST['society_type']}'";
                }
                if(isset($_POST['division_name'])){
                    $first.="and sammelen_invoice.mandal_id='{$_POST['division_name']}'";

                }
                if(isset($_POST['district_name'])){
                    $first.="and sammelen_invoice.district_id='{$_POST['district_name']}'";
                }   


            }
            echo $first;
            $firstres=mysqli_query($db,$first);
            $id=1;
            if(mysqli_num_rows($firstres)>0){
                ?>
                    <div class="m-auto">
                        <table class="table table-hover table-bordered table-striped ">
                            <tr class="bg-warning text-light">
                                <th>SNo.</th>
                                <th>मण्डल</th>
                                <th>जनपद </th>
                                <th>तहसील</th>
                                <th>विकासखण्ड </th>
                                <th>समिति के प्रकार </th>
                                <th>समिति का नाम </th>
                                <th>Name</th>
                                <th>Father's Name</th>
                                <th>Mobile No.</th>
                            </tr>
                
                <?php
                while($firstrow=mysqli_fetch_assoc($firstres)){

                    $mandalsql = "select * from master_division WHERE sno='{$firstrow['mandal_id']}'";
                    $result_mandal = execute_query($mandalsql);
                    $row_mandal = mysqli_fetch_assoc($result_mandal);
                    
                    //district name
                    $districtsql = "select * from sammelen_newdistrict WHERE CityCode='{$firstrow['district_id']}'";
                    $result_district = execute_query($districtsql);
                    $row_district = mysqli_fetch_assoc($result_district);


                    //type of society
                            

                    $typesql = "select * from sammelen_society_types WHERE sno='{$firstrow['society_type_id']}'";
                    $result_type = execute_query($typesql);
                    $row_type = mysqli_fetch_assoc($result_type);

                    // society
                    

                    $societynamesql = "select * from sammelen_society WHERE sno='{$firstrow['society_name_id']}'";
                    $res_society_name = execute_query($societynamesql);
                    $row_sname = mysqli_fetch_assoc($res_society_name);
                    //tehsil name
                    $tehsilsql = "select * from sammelen_master_tehseel WHERE TehsilCode='{$firstrow['tehseelId']}'";
                    $result_tehsil = execute_query($tehsilsql);
                    $row_tehsil = mysqli_fetch_assoc($result_tehsil);

                    //block name

                    $blocksql = "select * from sammelen_master_block WHERE BlockId='{$firstrow['blociId']}'";
                    $result_block = execute_query($blocksql);
                    $row_block = mysqli_fetch_assoc($result_block);
                    ?>
                                <tr>
                                    <td><?php echo $id++."."?></td>
                                    <td><?php echo $row_mandal['division_name']; ?></td>
                                    <td><?php if(isset($row_district['City'])){ echo $row_district['City']; }?></td>
                                    <td><?php if(isset($row_tehsil['TehsilName'])){ echo  $row_tehsil['TehsilName']; }?></td>
                                    <td><?php if(isset($row_block['BlockName'])){ echo  $row_block['BlockName']; }?></td>
                                    <td><?php echo $row_type['stypename']; ?></td>
                                    <td><?php 
                                    if($row_type['hasSociety']=="1"){
                                        if(isset($row_sname['SocietyName'])){
                                            echo $row_sname['SocietyName'];
                                        }else{
                                            echo "";
                                        }
                                    }else{
                                        echo "";
                                    } ?></td>
                                    <td><?php echo $firstrow['name']?></td>
                                    <td><?php echo $firstrow['father_name']?></td>
                                    <td><?php echo $firstrow['mobile_num']?></td>
                                </tr>
                            
                    <?php
                }
                ?>
                            </table>
                        </div>
                
                <?php
            }else{
                echo "<h5 class='text text-danger'>NO data found.</h5>";
            }
            
            ?>

            <h2 class="text-center">संस्था के प्रतिनिधि </h2>
            <?php

            $second="SELECT * FROM sammelen_trans LEFT JOIN sammelen_invoice on sammelen_trans.sammelen_invoice_id= sammelen_invoice.sno WHERE sammelen_invoice.status='1' and memberType='संस्था के प्रतिनिधि/डेलीगेट'" ;
            $secondres=mysqli_query($db,$second);
            if(mysqli_num_rows($secondres)>0){
                ?>
                    <div class="m-auto">
                        <table class="table table-hover table-bordered table-striped ">
                            <tr class="bg-warning text-light">
                                <th>SNo.</th>
                                <th>मण्डल</th>
                                <th>जनपद </th>
                                <th>तहसील</th>
                                <th>विकासखण्ड </th>
                                <th>समिति के प्रकार </th>
                                <th>समिति का नाम </th>
                                <th>Name</th>
                                <th>Father's Name</th>
                                <th>Mobile No.</th>
                            </tr>
                
                <?php
                while($secondrow=mysqli_fetch_assoc($secondres)){
                    $mandalsql = "select * from master_division WHERE sno='{$secondrow['mandal_id']}'";
                    $result_mandal = execute_query($mandalsql);
                    $row_mandal = mysqli_fetch_assoc($result_mandal);
                    
                    //district name
                    $districtsql = "select * from sammelen_newdistrict WHERE CityCode='{$secondrow['district_id']}'";
                    $result_district = execute_query($districtsql);
                    $row_district = mysqli_fetch_assoc($result_district);


                    //type of society
                            

                    $typesql = "select * from sammelen_society_types WHERE sno='{$secondrow['society_type_id']}'";
                    $result_type = execute_query($typesql);
                    $row_type = mysqli_fetch_assoc($result_type);

                    // society
                    

                    $societynamesql = "select * from sammelen_society WHERE sno='{$secondrow['society_name_id']}'";
                    $res_society_name = execute_query($societynamesql);
                    $row_sname = mysqli_fetch_assoc($res_society_name);
                    //tehsil name
                    $tehsilsql = "select * from sammelen_master_tehseel WHERE TehsilCode='{$secondrow['tehseelId']}'";
                    $result_tehsil = execute_query($tehsilsql);
                    $row_tehsil = mysqli_fetch_assoc($result_tehsil);

                    //block name

                    $blocksql = "select * from sammelen_master_block WHERE BlockId='{$secondrow['blociId']}'";
                    $result_block = execute_query($blocksql);
                    $row_block = mysqli_fetch_assoc($result_block);
                    ?>
                                <tr>
                                    <td><?php echo $id++."."?></td>
                                    <td><?php echo $row_mandal['division_name']; ?></td>
                                    <td><?php if(isset($row_district['City'])){ echo $row_district['City']; }?></td>
                                    <td><?php if(isset($row_tehsil['TehsilName'])){ echo  $row_tehsil['TehsilName']; }?></td>
                                    <td><?php if(isset($row_block['BlockName'])){ echo  $row_block['BlockName']; }?></td>
                                    <td><?php echo $row_type['stypename']; ?></td>
                                    <td><?php 
                                    if($row_type['hasSociety']=="1"){//
                                        if(isset($row_sname['SocietyName'])){
                                            echo $row_sname['SocietyName'];
                                        }else{
                                            echo "";
                                        }
                                    }else{
                                        echo "";
                                    } ?></td>
                                    <td><?php echo $secondrow['name']?></td>
                                    <td><?php echo $secondrow['father_name']?></td>
                                    <td><?php echo $secondrow['mobile_num']?></td>
                                </tr>
                            

                    <?php
                }
                ?>
                            </table>
                        </div>
                <?php
            }else{
                echo "<h5 class='text text-danger'>NO data found.</h5>";
            }

            ?>
            <h2 class="text-center">नए सदस्य </h2>
            <?php


            $third="SELECT * FROM sammelen_trans LEFT JOIN sammelen_invoice on sammelen_trans.sammelen_invoice_id= sammelen_invoice.sno WHERE sammelen_invoice.status='1' and memberType='नए सदस्य'" ;
            $thirdres=mysqli_query($db,$third);
            if(mysqli_num_rows($thirdres)>0){
                
                ?>
                    <div class="">
                        <table class="table table-hover table-bordered table-striped  ">
                            <tr class="bg-warning text-light">
                                <th>SNo.</th>
                                <th>मण्डल</th>
                                <th>जनपद </th>
                                <th>तहसील</th>
                                <th>विकासखण्ड </th>
                                <th>समिति के प्रकार </th>
                                <th>समिति का नाम </th>
                                <th>Name</th>
                                <th>Father's Name</th>
                                <th>Mobile No.</th>
                            </tr>
                
                <?php
                while($thirdrow=mysqli_fetch_assoc($thirdres)){
                    $mandalsql = "select * from master_division WHERE sno='{$thirdrow['mandal_id']}'";
                    $result_mandal = execute_query($mandalsql);
                    $row_mandal = mysqli_fetch_assoc($result_mandal);
                    
                    //district name
                    $districtsql = "select * from sammelen_newdistrict WHERE CityCode='{$thirdrow['district_id']}'";
                    $result_district = execute_query($districtsql);
                    $row_district = mysqli_fetch_assoc($result_district);


                    //type of society
                            

                    $typesql = "select * from sammelen_society_types WHERE sno='{$thirdrow['society_type_id']}'";
                    $result_type = execute_query($typesql);
                    $row_type = mysqli_fetch_assoc($result_type);

                    // society
                    

                    $societynamesql = "select * from sammelen_society WHERE sno='{$thirdrow['society_name_id']}'";
                    $res_society_name = execute_query($societynamesql);
                    $row_sname = mysqli_fetch_assoc($res_society_name);
                    //tehsil name
                    $tehsilsql = "select * from sammelen_master_tehseel WHERE TehsilCode='{$thirdrow['tehseelId']}'";
                    $result_tehsil = execute_query($tehsilsql);
                    $row_tehsil = mysqli_fetch_assoc($result_tehsil);

                    //block name

                    $blocksql = "select * from sammelen_master_block WHERE BlockId='{$thirdrow['blociId']}'";
                    $result_block = execute_query($blocksql);
                    $row_block = mysqli_fetch_assoc($result_block);
                    ?>
                                <tr>
                                    <td><?php echo $id++."."?></td>
                                    <td><?php echo $row_mandal['division_name']; ?></td>
                                    <td><?php if(isset($row_district['City'])){ echo $row_district['City']; }?></td>
                                    <td><?php if(isset($row_tehsil['TehsilName'])){ echo  $row_tehsil['TehsilName']; }?></td>
                                    <td><?php if(isset($row_block['BlockName'])){ echo  $row_block['BlockName']; }?></td>
                                    <td><?php echo $row_type['stypename']; ?></td>
                                    <td><?php 
                                    if($row_type['hasSociety']=="1"){
                                        if(isset($row_sname['SocietyName'])){
                                            echo $row_sname['SocietyName'];
                                        }else{
                                            echo "";
                                        }
                                    }else{
                                        echo "";
                                    } ?></td>
                                    <td><?php echo $thirdrow['name']?></td>
                                    <td><?php echo $thirdrow['father_name']?></td>
                                    <td><?php echo $thirdrow['mobile_num']?></td>
                                </tr>
                            
                    <?php
                }
                ?>
                            </table>
                        </div>
                <?php
            }else{
                echo "<h5 class='text text-danger'>NO data found.</h5>";
            }
        

            ?>

            <h2 class="text-center">अन्य </h2>
            <?php


            $third="SELECT * FROM sammelen_trans LEFT JOIN sammelen_invoice on sammelen_trans.sammelen_invoice_id= sammelen_invoice.sno WHERE sammelen_invoice.status='1' and memberType='अन्य'" ;
            $thirdres=mysqli_query($db,$third);
            if(mysqli_num_rows($thirdres)>0){
                
                ?>
                    <div class="">
                        <table class="table table-hover table-bordered table-striped  ">
                            <tr class="bg-warning text-light">
                                <th>SNo.</th>
                                <th>मण्डल</th>
                                <th>जनपद </th>
                                <th>तहसील</th>
                                <th>विकासखण्ड </th>
                                <th>समिति के प्रकार </th>
                                <th>समिति का नाम </th>
                                <th>Name</th>
                                <th>Father's Name</th>
                                <th>Mobile No.</th>
                            </tr>
                
                <?php
                while($thirdrow=mysqli_fetch_assoc($thirdres)){
                    $mandalsql = "select * from master_division WHERE sno='{$thirdrow['mandal_id']}'";
                    $result_mandal = execute_query($mandalsql);
                    $row_mandal = mysqli_fetch_assoc($result_mandal);
                    
                    //district name
                    $districtsql = "select * from sammelen_newdistrict WHERE CityCode='{$thirdrow['district_id']}'";
                    $result_district = execute_query($districtsql);
                    $row_district = mysqli_fetch_assoc($result_district);


                    //type of society
                            

                    $typesql = "select * from sammelen_society_types WHERE sno='{$thirdrow['society_type_id']}'";
                    $result_type = execute_query($typesql);
                    $row_type = mysqli_fetch_assoc($result_type);

                    // society
                    

                    $societynamesql = "select * from sammelen_society WHERE sno='{$thirdrow['society_name_id']}'";
                    $res_society_name = execute_query($societynamesql);
                    $row_sname = mysqli_fetch_assoc($res_society_name);
                    //tehsil name
                    $tehsilsql = "select * from sammelen_master_tehseel WHERE TehsilCode='{$thirdrow['tehseelId']}'";
                    $result_tehsil = execute_query($tehsilsql);
                    $row_tehsil = mysqli_fetch_assoc($result_tehsil);

                    //block name

                    $blocksql = "select * from sammelen_master_block WHERE BlockId='{$thirdrow['blociId']}'";
                    $result_block = execute_query($blocksql);
                    $row_block = mysqli_fetch_assoc($result_block);
                    ?>
                                <tr>
                                    <td><?php echo $id++."."?></td>
                                    <td><?php echo $row_mandal['division_name']; ?></td>
                                    <td><?php if(isset($row_district['City'])){ echo $row_district['City']; }?></td>
                                    <td><?php if(isset($row_tehsil['TehsilName'])){ echo  $row_tehsil['TehsilName']; }?></td>
                                    <td><?php if(isset($row_block['BlockName'])){ echo  $row_block['BlockName']; }?></td>
                                    <td><?php echo $row_type['stypename']; ?></td>
                                    <td><?php 
                                    if($row_type['hasSociety']=="1"){
                                        if(isset($row_sname['SocietyName'])){
                                            echo $row_sname['SocietyName'];
                                        }else{
                                            echo "";
                                        }
                                    }else{
                                        echo "";
                                    } ?></td>
                                    <td><?php echo $thirdrow['name']?></td>
                                    <td><?php echo $thirdrow['father_name']?></td>
                                    <td><?php echo $thirdrow['mobile_num']?></td>
                                </tr>
                            
                    <?php
                }
                ?>
                            </table>
                        </div>
                <?php
            }else{
                echo "<h5 class='text text-danger'>NO data found.</h5>";
            }
        

            ?>
        </div>
    </div>
</div>

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
            $("#sanchalak_tehseel1").html(txt);
            $("#sanstha_tehseel1").html(txt);
            $("#new_sadsaya_tehseel1").html(txt);
            
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

function sanchalak_fill_block_name(val,idforappend){
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
          	$("#sanchalak_block"+idforappend).html(txt);
            
        }
    });
}


function sanstha_fill_block_name(val,idforappend){
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
          	$("#sanstha_block"+idforappend).html(txt);
            
        }
    });
}

function new_sadsaya_fill_block_name(val,idforappend){
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
          	$("#new_sadsaya_block"+idforappend).html(txt);
            
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
   


				
<?php
page_footer_start();
?>
<!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
<script src="js/light-bootstrap-dashboard.js?v=1.4.0"></script>

<?php		
page_footer_end();
?>
   


				


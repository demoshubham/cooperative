<?php
include("scripts/settings.php");
logvalidate();
$msg='';

$tab=1;
if(isset($_GET['delid'])){

    $sql="UPDATE sammelen_invoice SET verify_status='2',
	edition_time='".date("d-m-Y H:i:s")."',
	edited_by='".$_SESSION['username']."' WHERE sno='{$_GET['delid']}'";
    $res=execute_query($sql);
    if(mysqli_error($db)){ 
        $msg .= '<p class="alert alert-danger">Error # 1.01 : '.mysqli_error($db).'>> '.$sql.'</p>';
    }
    if($msg==""){
        $msg = '<p class="alert alert-danger">Rejected </p>';
    }

}


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
if(!isset($_POST['search'])){
	
	$_POST['mobile_no'] = '';
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
	<div id="container" class="no-print">
		<form id="sale_form" name="sale_form" class="" autocomplete="off" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onSubmit="">
		<div class="card card-body">    
        	<div class="row d-flex my-auto">    	
					<table width="100%" class="table table-striped table-hover rounded">	
						<tr >
							<th></th>
							<th  width="15%">Mobile No</th>
							<th width=""><textarea id="mobile_numbers" name="mobile_no" cols="1000" rows="4" class="form-control"><?php echo $_POST['mobile_no'];?> </textarea></th>
							<th></th>
							<th></th>
							
						</tr>
					</table>
					
					<div class="col-md-12 text-center">
					<button type="submit" name="search" class="btn btn-primary">Search</button>
					<input type="hidden" id="id" name="id" value="1">
					</div>
			</div>
		</div>
		</form>
	</div>
	<div class="card-deck">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title text-center"></h5></br>
				<table class="table table-hover table-bordered table-striped" id="general_stat_table">
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
								
                                    $array = explode(',', $_POST['mobile_no']);
                                    // print_r($array);
                                    // Get the length of the array
                                    $arrayLength = count($array);
                                    $str="";
                                    // Iterate over each value in the array
                                    for ($i = 0; $i < $arrayLength; $i++) {
                                        $value = trim($array[$i]);
                                        $str.="'$value'";
                                        if($i<$arrayLength-1){
                                            $str.=",";

                                        }
                                    }
                                    // echo $str;
								// mobile_number_check.php
								// $sql = 'select * from sammelen_newdistrict order by City ASC';
								$sql = 'SELECT * FROM sammelen_trans
										LEFT JOIN sammelen_invoice ON sammelen_trans.sammelen_invoice_id = sammelen_invoice.sno where sammelen_invoice.status="1"';
										
								if($_POST['mobile_no']!=''){
									$sql .= " and mobile_num in ({$str})";
								}
								// echo $sql;			
								$result = execute_query($sql);
								$i=1;
								$tot_allotted = 0;
								$tot_approved = 0;
								$tot_pending = 0;
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
								}
								echo '
								</tbody>';
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

	
	var t = $('#general_stat_table').DataTable({
		// paging: false
    });
 
    
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

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


<?php 
	if($_SESSION['usertype']=="sadmin" || $_SESSION['usertype']=="2" ){
?>

	<div class="card-deck">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title text-center"></h5></br>
				<table class="table table-hover table-bordered table-striped" id="general_stat_table">
					<thead style="position: sticky;top: 0;z-index: 1;">
                        <tr>
                            <th>SNo.</th>
                            <th>District name </th>
                            <th>Pending member</th>
                            <th>Approved Member</th>
                            <th>Total Member</th>

                        </tr>
						</thead>
					<tbody>
								<?php
								$sql = 'select * from sammelen_newdistrict order by City ASC';
								$result_div = execute_query($sql);
								$i=1;
								$tot_allotted = 0;
								$tot_approved = 0;
								$tot_pending = 0;
								while($row_district = mysqli_fetch_assoc($result_div)){
									
									
									// // $sql = 'select * from uprnss_project_temp where district_id="'.$row_district['CityCode'].'" and (status!="5" or status="0" or status is null or status="1")';
									
									
									$sql = 'SELECT * FROM sammelen_trans
											LEFT JOIN sammelen_invoice ON sammelen_trans.sammelen_invoice_id = sammelen_invoice.sno where district_id="'.$row_district['CityCode'].'" and sammelen_invoice.status="1"';	
									$allotted = mysqli_num_rows(execute_query($sql));
									
									$sql = 'SELECT * FROM sammelen_trans
											LEFT JOIN sammelen_invoice ON sammelen_trans.sammelen_invoice_id = sammelen_invoice.sno where district_id="'.$row_district['CityCode'].'" and sammelen_invoice.status="1" and verify_status="1"';	
									$approved = mysqli_num_rows(execute_query($sql));
									
									$sql = 'SELECT * FROM sammelen_trans
											LEFT JOIN sammelen_invoice ON sammelen_trans.sammelen_invoice_id = sammelen_invoice.sno where district_id="'.$row_district['CityCode'].'" and sammelen_invoice.status="1" and verify_status="0"';	
									$pending = mysqli_num_rows(execute_query($sql));
									
									$tot_allotted += $allotted;
									$tot_approved += $approved;
									$tot_pending += $pending;
									
									
									echo '<tr>
									<td>'.$i++.'</td>
									<td>'.$row_district['City'].'</td>
									<td>'.$pending.'</td>
									<td>'.$approved.'</td>
									<td>'.$allotted.'</td>
									</tr>';
								}
								echo '
								</tbody>
							<tfoot><tr>
								<th>&nbsp;</th>
								<th>Grand Total</th>
								<th>'.$tot_pending.'</th>
								<th>'.$tot_approved.'</th>
								<th>'.$tot_allotted.'</th>
								</tr>';
								?>
							</tfoot>
				</table>
			  
			 
			</div>
		</div>
	</div>

<?php 
	}
?>



</br>

<?php 
	if($_SESSION['usertype']=="sadmin" || $_SESSION['usertype']=="4" ||$_SESSION['usertype']=="3" ){
?>

	<div class="card-deck">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title text-center">Pending</h5></br>
				<table class="table table-hover table-bordered table-striped" > 
					
                        <tr>
                            <th>SNo.</th>
                           <!-- <th>मण्डल</th>
                            <th>जनपद </th>
                            <th>तहसील</th>
                            <th>विकासखण्ड </th>-->
                            <th>समिति के प्रकार </th>
                            <th>समिति का नाम </th>
                            <th>कुल सदस्य</th>
                            <th>View</th>
                            <th>Status</th>
                            <!-- <th>ID Card</th> 
                            <th>DELETE</th>
                             <th></th>
                            <th></th>-->

                        </tr>
					
                        <?php
						
						if($_SESSION['usertype'] == "sadmin"){
							$sql="SELECT * FROM sammelen_invoice WHERE status='1'and verify_status='0'";
							
						}else{
							$sql="SELECT * FROM sammelen_invoice WHERE status='1' and verify_status='0' and district_id IN ('" . implode("','", $_SESSION['district']) . "')";
						}
							
                            $res=execute_query($sql);
                            $totalmember=0;
                            if(mysqli_num_rows($res)>0){
                                $a=1;
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
                                    echo "<tr><td id='td'>".$a++."</td>
                                    
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
												echo'<button class="btn btn-success btn-sm">Approved</button>';
											}elseif($row['verify_status']==2){
												echo'<button class="btn btn-danger btn-sm">Rejected</button>';
											}else{
												echo'<button class="btn btn-info btn-sm">Pending</button>';
											}
											
											?>
										</td>
										
                                        <!-- <td><a href="sammelen_reg_id_card.php?id=<?php //echo $row['sno']?>" class="btn btn-primary btn-sm" target="_blank">CARD</a></td> 
                                         <td><a href="<?php echo $_SERVER['PHP_SELF']?>?delid=<?php echo $row['sno']?>" onClick="confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</a></td>-->
                                    <?php
                                    
                                    
                                    
                                }
                                ?>
                                    <tr>
                                        <th colspan="2"></th>
                                        <th class="text-center">Total Members</th>
                                        <th><?php echo $totalmember;?></th>
                                        <th colspan="3"></th>

                                    </tr>
                                
                                <?php
                            }
                        ?>
                    </table>
			  
			 
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<h5 class="card-title text-center">Approved</h5> </br>
					<table class="table table-hover table-bordered table-striped" width="60%">
                        <tr>
                            <th>SNo.</th>
                          <!--   <th>मण्डल</th>
                            <th>जनपद </th>
                            <th>तहसील</th>
                            <th>विकासखण्ड </th>-->
                            <th>समिति के प्रकार </th>
                            <th>समिति का नाम </th>
                            <th>कुल सदस्य</th>
                            <th>View</th>
                            <th>Status</th>
                            <!-- <th>ID Card</th> 
                            <th>DELETE</th>
                             <th></th>
                            <th></th> -->

                        </tr>
                        <?php
						
						if($_SESSION['usertype'] == "sadmin"){
							$sql="SELECT * FROM sammelen_invoice WHERE status='1' and verify_status='1'";
							
						}else{
							$sql="SELECT * FROM sammelen_invoice WHERE status='1' and verify_status='1' and district_id IN ('" . implode("','", $_SESSION['district']) . "')";
						}
							
                            $res=execute_query($sql);
                            $totalmember=0;
							$i=1;
                            if(mysqli_num_rows($res)>0){
                                
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
												echo'<button class="btn btn-success btn-sm">Approved</button>';
											}elseif($row['verify_status']==2){
												echo'<button class="btn btn-danger btn-sm">Rejected</button>';
											}else{
												echo'<button class="btn btn-info btn-sm">Pending</button>';
											}
											
											?>
										</td>
                                        <!-- <td><a href="sammelen_reg_id_card.php?id=<?php //echo $row['sno']?>" class="btn btn-primary btn-sm" target="_blank">CARD</a></td> 
                                         <td><a href="<?php echo $_SERVER['PHP_SELF']?>?delid=<?php echo $row['sno']?>" onClick="confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</a></td>-->
                                    <?php
                                    
                                    
                                    
                                }
                                ?>
                                    <tr>
                                        <th colspan="2"></th>
                                        <th class="text-center">Total Members</th>
                                        <th><?php echo $totalmember;?></th>
                                        <th colspan="2"></th>

                                    </tr>
                                
                                <?php
                            }
                        ?>
                    </table>
					
					
					
			</div>
		</div>	
	</div>

<?php 
	}
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

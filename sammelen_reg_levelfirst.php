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
if(isset($_GET['id_forword'])){
	
	$sql = 'UPDATE sammelen_invoice SET 
                status = "2",
                edition_time="' . date("Y-m-d H:i:s") . '",
                edited_by="' . $_SESSION['username'] . '"
                WHERE sno = "' . $_GET['id_forword'] . '"';
//echo $sql;
     $res=execute_query($sql);
    if(mysqli_error($db)){ 
        $msg .= '<p class="alert alert-danger">Error # 1.01 : '.mysqli_error($db).'>> '.$sql.'</p>';
    }
    if($msg==""){
        $msg = '<p class="alert alert-danger">Forwarded successfully</p>';
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
    <div class="row">		
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
				 <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" >
                    <h3 class="text-center bg-warning text-white p-3">LEVEL FIRST APPROVAL </h3>			
                    <?php echo $msg;?>
                    <table class="table table-hover table-bordered table-striped">
                        <tr>
                            <th>SNo.</th>
                            <th>नाम</th>
                            <th>मण्डल</th>
                            <th>जनपद </th>
                            <th>तहसील</th>
                            <th>विकासखण्ड </th>
                            <th>समिति के प्रकार </th>
                            <th>समिति का नाम </th>
                            <th>कुल सदस्य</th>
                            <th>View</th>
                            <th>Forward</th>
                            <!-- <th>ID Card</th> -->
                            <th>DELETE</th>
                            <!-- <th></th>
                            <th></th> -->

                        </tr>
                        <?php
                            $sql="SELECT * FROM sammelen_invoice WHERE status='1' and created_by='".$_SESSION['username']."'";
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
									<td>".$row['invName']."</td>
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
										<td><a href="<?php echo $_SERVER['PHP_SELF']?>?id_forword=<?php echo $row['sno']?>" onClick="confirm('Are you sure?')" class="btn btn-primary ">Forward to Level 2</a></td>
                                        <!-- <td><a href="sammelen_reg_id_card.php?id=<?php //echo $row['sno']?>" class="btn btn-primary btn-sm" target="_blank">CARD</a></td> -->
                                        <td><a href="<?php echo $_SERVER['PHP_SELF']?>?delid=<?php echo $row['sno']?>" onClick="confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</a></td>
                                    <?php
                                    
                                    
                                    
                                }
                                ?>
                                    <tr>
                                        <th colspan="6"></th>
                                        <th class="text-center">Total Members</th>
                                        <th><?php echo $totalmember;?></th>
                                        <th colspan="2"></th>

                                    </tr>
                                
                                <?php
                            }
                        
                        ?>
                    </table>
				</form>
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

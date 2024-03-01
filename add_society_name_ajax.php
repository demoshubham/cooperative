<?php
date_default_timezone_set('Asia/Calcutta');
$time = mktime(true);
include("scripts/settings.php");
logvalidate();

?>
<div class="col-12">
    <table id="general_stat_table"  class="table table-hover table-striped table-bordered w-100">
        <thead>
        <tr>
            <th>SNo.</th>
            <th>जनपद </th>
            <th>तहसील</th>
            <th>विकासखण्ड </th>
            <th>समिति के प्रकार </th>
            <th>समिति का नाम </th>
        </tr>
        </thead>
        <tbody>

        
        <?php 
            $sql="SELECT *,sammelen_society_types.sno as stypesno,sammelen_society.sno as sno FROM `sammelen_society` LEFT JOIN sammelen_society_types on sammelen_society_types.sno = sammelen_society.SocietyTypeId  WHERE SocietyTypeId='".$_POST['val']."'";

	
            if(isset($_POST['block'])){
                $sql.=' and BlockId="'.$_POST['block'].'"';
            }
            
            $res=mysqli_query($db,$sql);
            $i=1;
            while($row=mysqli_fetch_assoc($res)){
                // //mandal name
                // $mandalsql = "select * from master_division WHERE sno='{$row['mandal_id']}'";
                // $result_mandal = execute_query($mandalsql);
                // $row_mandal = mysqli_fetch_assoc($result_mandal);
                
                //district name
                $districtsql = "select * from sammelen_newdistrict WHERE CityCode='{$row['DistrictCode']}'";
                $result_district = execute_query($districtsql);
                $row_district = mysqli_fetch_assoc($result_district);

                //tehsil name
                $tehsilsql = "select * from sammelen_master_tehseel WHERE TehsilId='{$row['TehsilId']}'";
                $result_tehsil = execute_query($tehsilsql);
                $row_tehsil = mysqli_fetch_assoc($result_tehsil);

                //block

                $blocksql = "select * from sammelen_master_block WHERE BlockId='{$row['BlockId']}'";
                $result_block = execute_query($blocksql);
                $row_block = mysqli_fetch_assoc($result_block);

                // array("id"=>$row['sno'], "society_name"=>$row['col4']);
                echo "<tr><td>".$i++."</td>
                <td>";
                if(isset($row_district['City'])){
                    echo $row_district['City']." <span style='color:green;'>DistrictCode- (".$row['DistrictCode'].")</span>";
                }else{
                    echo "";
                }
                echo "</td>
                <td>";
                    if(isset($row_tehsil['TehsilName'])){
                        echo $row_tehsil['TehsilName']." <span style='color:green;'>TehsilId- (".$row['TehsilId'].")</span>";
                    }else{
                        echo "";
                    }
                echo "</td>
                <td>";
                if(isset($row_block['BlockName'])){
                    echo $row_block['BlockName']." <span style='color:green;'>BlockId- (".$row['BlockId'].")</span>";
                }else{
                    echo "";
                }
                echo "</td><td>".$row['stypename']."<span style='color:green;'> SocietyTypeId".$row['stypesno']."</span>
                </td><td>".$row['SocietyName']."<span style='color:green;'> Sno-".$row['sno']."</span></td></tr>";

            }
        ?>
            </tbody>  
    </table> 
</div>

<?php

?>
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
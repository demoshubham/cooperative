<?php

include("scripts/settings.php");
logvalidate();
$msg='';

$tab=1;


page_header_start();



if(isset($_GET['rej'])){

    $sql="UPDATE sammelen_invoice SET verify_status='2',
	edition_time='".date("d-m-Y H:i:s")."',
	edited_by='".$_SESSION['username']."' WHERE sno='{$_GET['rej']}'";
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
?>
  <style>
    body {
      text-align: center;
      font-size: 22px;
    }

    h1 {
      font-size: 20px;
    }

    table {
      width: 80%;
      margin: 20px auto;
      border-collapse: collapse;
    }

    th, td {
      border: 1px solid #dddddd;
      padding: 8px;
    }
    
        
    h1{
        font-size: 1.5rem !important;
    }
    h2{
        font-size: 1.3rem !important;
    }
    h3{
        font-size: 1.2rem !important;
    }
    h4{
        font-size: 1rem !important;
    }
    p{
        font-size: 1rem !important;
    }
    td{

        font-size: 1rem !important;
    }
    th{
      font-size: 1rem !important;
    }


  </style>


<!-- <h1><u>रिजिस्ट्रेशन फॉर्म</u></h1> -->
<h1>सहकारिता महा-सम्मेलन</h1>
	

		<table>
		<?php echo $msg;?>
			<thead>
				<tr>
				  <th>मण्डल</th>
				  <th>जिला</th>
				  <th>तहसील</th>
				  <th>ब्लॉक</th>
				</tr>
			</thead>
		  <?php
			$sql="SELECT * FROM sammelen_invoice WHERE sno='".$_GET['id']."' and status='1'";
			$res=execute_query($sql);
			if(mysqli_num_rows($res)>0){
				$i=1;

					$row=mysqli_fetch_assoc($res);
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
					
					// array("id"=>$row['sno'], "society_name"=>$row['col4']);
					?>
			<tbody>
			  <tr>
				<td><?php echo $row_mandal['division_name']; ?></td>
				<td><?php if(isset($row_district['City'])){echo $row_district['City'];}else{echo "";} ?></td>
				<td><?php if(isset($row_tehsil['TehsilName'])){ echo $row_tehsil['TehsilName'];}else{echo "";} ?></td>
				<td><?php if(isset($row_block['BlockName'])){echo $row_block['BlockName'];}else{echo "";} ?></td>
			  </tr>
			</tbody>
				
		</table>
              
		<table>
			<thead>
				<tr>
					<th>संस्था / सिमित के प्रकार</th>
					<th>संस्था / सिमित का नाम </th>
				</tr>
				<tr>
					<td><?php echo $row_type['stypename']; ?></td>
				
					<td><?php 
					if($row_type['hasSociety']=="1"){
					  echo $row_sname['SocietyName'];
					}else{
					  echo "";
					}
					?></td>
				</tr>
			</thead>
		</table>
		
		<table>
			<thead>
				<tr>
					<th>सम्मेलन समिति संयोजक का नाम </th>
					<th>सम्मेलन समिति संयोजक के पिता का नाम </th>
					<th>सम्मेलन समिति संयोजक का पता</th>
					<th>सम्मेलन समिति संयोजक  का मोबाईल नंबर</th>
				</tr>
				<tr>
					<td><?php echo $row['invName']; ?></td>
					<td><?php echo $row['invFatherName']; ?></td>
					<td><?php echo $row['invAddress']; ?></td>
					<td><?php echo $row['invMobno']; ?></td>
				</tr>
			</thead>
		</table>


            <?php
        
    }


?>
  
<h2><b>अध्यक्ष</b></h2>


  
    <?php
    $sql="SELECT * FROM `sammelen_trans` WHERE sammelen_invoice_id='{$_GET['id']}' and memberType='अध्यक्ष'" ;
    $res=execute_query($sql);
    if(mysqli_num_rows($res)>0){
      $i=1;
      ?>
      <table>
        <thead>
          <tr>
            <th>क्रम संख्या</th>
            <th>प्रतिभागी का नाम</th>
            <th>पिता का नाम</th>
            <th>मोबाइल नंबर</th>
            <th>तहसील</th>
            <th>विकासखंड</th>
          </tr>
        </thead>
        <tbody>
      <?php
      while($row=mysqli_fetch_assoc($res)){
        //finding tehsil and block name

         //tehsil name
         $tehsilsql = "select * from sammelen_master_tehseel WHERE TehsilCode='{$row['tehseelId']}'";
         $result_tehsil = execute_query($tehsilsql);
         $row_tehsil = mysqli_fetch_assoc($result_tehsil);

         //block

         $blocksql = "select * from sammelen_master_block WHERE BlockId='{$row['blociId']}'";
         $result_block = execute_query($blocksql);
         $row_block = mysqli_fetch_assoc($result_block);
         

        ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $row['name'];?></td>
            <td><?php echo $row['father_name'];?></td>
            <td><?php echo $row['mobile_num'];?></td>
            <td><?php echo $row_tehsil['TehsilName'];?></td>
            <td><?php echo $row_block['BlockName'];?></td>
          </tr>
        <?php
      }
      ?>
            </tbody>
      </table>
      <?php
    }else{
      echo "<h5 class='text text-danger'>NO data Found</h5>";
    }
    
    
    ?>
    
  <h2><b>उपाअध्यक्ष</b></h2>


  
    <?php
    $sql="SELECT * FROM `sammelen_trans` WHERE sammelen_invoice_id='{$_GET['id']}' and memberType='उपाअध्यक्ष'";
    $res=execute_query($sql);
    if(mysqli_num_rows($res)>0){
      $i=1;
      ?>
      <table>
        <thead>
          <tr>
            <th>क्रम संख्या</th>
            <th>प्रतिभागी का नाम</th>
            <th>पिता का नाम</th>
            <th>मोबाइल नंबर</th>
            <th>तहसील</th>
            <th>विकासखंड</th>
          </tr>
        </thead>
        <tbody>
      <?php
      while($row=mysqli_fetch_assoc($res)){
        //finding tehsil and block name

         //tehsil name
         $tehsilsql = "select * from sammelen_master_tehseel WHERE TehsilCode='{$row['tehseelId']}'";
         $result_tehsil = execute_query($tehsilsql);
         $row_tehsil = mysqli_fetch_assoc($result_tehsil);

         //block

         $blocksql = "select * from sammelen_master_block WHERE BlockId='{$row['blociId']}'";
         $result_block = execute_query($blocksql);
         $row_block = mysqli_fetch_assoc($result_block);
         

        ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $row['name'];?></td>
            <td><?php echo $row['father_name'];?></td>
            <td><?php echo $row['mobile_num'];?></td>
            <td><?php echo $row_tehsil['TehsilName'];?></td>
            <td><?php echo $row_block['BlockName'];?></td>
          </tr>
        <?php
      }
      ?>
            </tbody>
      </table>
      <?php
    }else{
      echo "<h5 class='text text-danger'>NO data Found</h5>";
    }
    
    
    ?>
    
  


<h2><b>प्रबंध समिति संचालक गण</b></h2>


  
    <?php
    $sql="SELECT * FROM `sammelen_trans` WHERE sammelen_invoice_id='{$_GET['id']}' and memberType='प्रबंध समिति संचालक'" ;
    $res=execute_query($sql);
    if(mysqli_num_rows($res)>0){
      $i=1;
      ?>
      <table>
        <thead>
          <tr>
            <th>क्रम संख्या</th>
            <th>प्रतिभागी का नाम</th>
            <th>पिता का नाम</th>
            <th>मोबाइल नंबर</th>
            <th>तहसील</th>
            <th>विकासखंड</th>
          </tr>
        </thead>
        <tbody>
      <?php
      while($row=mysqli_fetch_assoc($res)){
        //finding tehsil and block name

         //tehsil name
         $tehsilsql = "select * from sammelen_master_tehseel WHERE TehsilCode='{$row['tehseelId']}'";
         $result_tehsil = execute_query($tehsilsql);
         $row_tehsil = mysqli_fetch_assoc($result_tehsil);

         //block

         $blocksql = "select * from sammelen_master_block WHERE BlockId='{$row['blociId']}'";
         $result_block = execute_query($blocksql);
         $row_block = mysqli_fetch_assoc($result_block);
         

        ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $row['name'];?></td>
            <td><?php echo $row['father_name'];?></td>
            <td><?php echo $row['mobile_num'];?></td>
            <td><?php echo $row_tehsil['TehsilName'];?></td>
            <td><?php echo $row_block['BlockName'];?></td>
          </tr>
        <?php
      }
      ?>
            </tbody>
      </table>
      <?php
    }else{
      echo "<h5 class='text text-danger'>NO data Found</h5>";
    }
    
    
    ?>
    
   
 

<h2><b>संस्था के प्रतिनिधि/डेलीगेट</b></h2>

<table>
  

    <?php
    $sql="SELECT * FROM `sammelen_trans` WHERE sammelen_invoice_id='{$_GET['id']}' and memberType='संस्था के प्रतिनिधि/डेलीगेट'" ;
    $res=execute_query($sql);
    if(mysqli_num_rows($res)>0){
      $i=1;?>
      <thead>
        <tr>
          <th>क्रम संख्या</th>
          <th>प्रतिभागी का नाम</th>
          <th>पिता का नाम</th>
          <th>मोबाइल नंबर</th>
          <th>तहसील</th>
          <th>विकासखंड</th>
        </tr>
      </thead>
      <tbody>
      <?php
      
      while($row=mysqli_fetch_assoc($res)){
        //finding tehsil and block name

         //tehsil name
         $tehsilsql = "select * from sammelen_master_tehseel WHERE TehsilCode='{$row['tehseelId']}'";
         $result_tehsil = execute_query($tehsilsql);
         $row_tehsil = mysqli_fetch_assoc($result_tehsil);

         //block

         $blocksql = "select * from sammelen_master_block WHERE BlockId='{$row['blociId']}'";
         $result_block = execute_query($blocksql);
         $row_block = mysqli_fetch_assoc($result_block);
         

        ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $row['name'];?></td>
            <td><?php echo $row['father_name'];?></td>
            <td><?php echo $row['mobile_num'];?></td>
            <td><?php echo $row_tehsil['TehsilName'];?></td>
            <td><?php echo $row_block['BlockName'];?></td>
          </tr>
        <?php
      }
      ?>
      </tbody>
    </table>
    <?php
    }else{
      echo "<h5 class='text text-danger'>NO data Found</h5>";
    }
    
    
    ?>
    
   
  </tbody>
</table>


<h2><b>नए सदस्य</b></h2>

    <?php
    $sql="SELECT * FROM `sammelen_trans` WHERE sammelen_invoice_id='{$_GET['id']}' and memberType='सदस्य'" ;
    $res=execute_query($sql);
    if(mysqli_num_rows($res)>0){
      $i=1;
      ?>
      <table>
      <thead>
        <tr>
          <th>क्रम संख्या</th>
          <th>प्रतिभागी का नाम</th>
          <th>पिता का नाम</th>
          <th>मोबाइल नंबर</th>
          <th>तहसील</th>
          <th>विकासखंड</th>
        </tr>
      </thead>
      <?php
      while($row=mysqli_fetch_assoc($res)){
        ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $row['name'];?></td>
            <td><?php echo $row['father_name'];?></td>
            <td><?php echo $row['mobile_num'];?></td>
            <td><?php echo $row_tehsil['TehsilName'];?></td>
            <td><?php echo $row_block['BlockName'];?></td>
          </tr>
        <?php
      }
      ?>
      </tbody>
      </table>
<?php
    }else{
      echo "<h5 class='text text-danger'>NO data Found</h5>";
    }
    
    
    ?>


<h2><b>अन्य</b></h2>

    <?php
    $sql="SELECT * FROM `sammelen_trans` WHERE sammelen_invoice_id='{$_GET['id']}' and memberType='अन्य'" ;
    $res=execute_query($sql);
    if(mysqli_num_rows($res)>0){
      $i=1;
      ?>
      <table>
      <thead>
        <tr>
          <th>क्रम संख्या</th>
          <th>प्रतिभागी का नाम</th>
          <th>पिता का नाम</th>
          <th>मोबाइल नंबर</th>
          <th>तहसील</th>
          <th>विकासखंड</th>
        </tr>
      </thead>
      <?php
      while($row=mysqli_fetch_assoc($res)){
        ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $row['name'];?></td>
            <td><?php echo $row['father_name'];?></td>
            <td><?php echo $row['mobile_num'];?></td>
            <td><?php echo $row_tehsil['TehsilName'];?></td>
            <td><?php echo $row_block['BlockName'];?></td>
          </tr>
        <?php
      }
      ?>
    </tbody>
    </table>
    <?php
    }else{
      echo "<h5 class='text text-danger'>NO data Found</h5>";
    }
    
    
    ?>
	<?php 
		if ($_SESSION['usertype']!="5"){
	?>
		<table>
			<thead>
				<tr>
					<th>Action</th>
					<th><a href="<?php echo $_SERVER['PHP_SELF']?>?id_forword=<?php echo $_GET['id']?>" onClick="confirm('Are you sure?')" class="btn btn-success btn-sm">Approve</a></th>
					<th><a href="<?php echo $_SERVER['PHP_SELF']?>?rej=<?php echo $_GET['id']?>" onClick="confirm('Are you sure?')" class="btn btn-danger btn-sm">Reject</a></th>
				</tr>
			</thead>
		</table>
		<?php }?>


				


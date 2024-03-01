<?php

include("scripts/settings.php");
logvalidate();
$msg='';

$tab=1;


// page_header_start();
?>
   <style>
    *{
        margin: 0;
        padding: 0;
    }
    body {
        font-family: Arial, sans-serif;
        padding:1rem;
        background-color: #e0e0e0;

    }
    .header{
        /* background:#3498db; */
        background: orange;
        border-radius:4px;
        color:aliceblue;
        padding:0.5rem;
        display:flex;
        justify-content:center;
        gap:1rem;
        
    }

    .wrap{
        background-color: #fff;
        width: 280px;
        /* height: 400px; */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        border: 2px solid orange;
        
    }
    h5,h6{
        text-align: center;
    }
    h5{
        font-size:1.2rem;
    }
    h6{
        font-size:1rem;
        margin-top:0.5rem;
    }
    #idcard{
        /* margin-top:1rem; */
        padding: 15px;
    }
    .wrapper{
        display:flex;
        gap:1rem;
    }

    label {
      font-weight: bold;
      color: #333;
    }

    p {
      margin: 0;
      color: #666;
    }

    .wrap {
        page-break-inside: avoid;
    }
    #societyName{
        font-size:0.8rem;
    }
  

    
h1{
    font-size: 1.8rem !important;
}
h2{
    font-size: 1.5rem !important;
}
h3{
    font-size: 1.3rem !important;
}
h4{
    font-size: 1rem !important;
}
p{
    font-size: .8rem !important;
}
td{

    font-size: .8rem !important;
}

@page{

}
  </style>

  <?php
        $sql="SELECT * FROM sammelen_invoice WHERE sno='".$_GET['id']."'";
        $res=execute_query($sql);
        $row=mysqli_fetch_assoc($res);

        $mandalsql = "select * from master_division WHERE sno='{$row['mandal_id']}'";
        $result_mandal = execute_query($mandalsql);
        $row_mandal = mysqli_fetch_assoc($result_mandal);
        
        //district name
        $districtsql = "select * from sammelen_newdistrict WHERE CityCode='{$row['district_id']}'";
        $result_district = execute_query($districtsql);
        $row_district = mysqli_fetch_assoc($result_district);

        // //tehsil name
        // $tehsilsql = "select * from sammelen_master_tehseel WHERE TehsilCode='{$row['tehsil_id']}'";
        // $result_tehsil = execute_query($tehsilsql);
        // $row_tehsil = mysqli_fetch_assoc($result_tehsil);

        // //block

        // $blocksql = "select * from sammelen_master_block WHERE BlockId='{$row['block_id']}'";
        // $result_block = execute_query($blocksql);
        // $row_block = mysqli_fetch_assoc($result_block);
        


     //type of society
            

     $typesql = "select * from sammelen_society_types WHERE sno='{$row['society_type_id']}'";
     $result_type = execute_query($typesql);
     $row_type = mysqli_fetch_assoc($result_type);

     // society
     

     $societynamesql = "select * from sammelen_society WHERE sno='{$row['society_name_id']}'";
     $res_society_name = execute_query($societynamesql);
     $row_sname = mysqli_fetch_assoc($res_society_name);

  
  ?>

<h4>अध्यक्ष </h4>
<br>
<?php
$first="SELECT * FROM sammelen_trans WHERE sammelen_invoice_id ='{$row['sno']}'and memberType='अध्यक्ष'" ;
$firstres=mysqli_query($db,$first);
if(mysqli_num_rows($firstres)>0){
    while($firstrow=mysqli_fetch_assoc($firstres)){
        //tehsil name
        $tehsilsql = "select * from sammelen_master_tehseel WHERE TehsilCode='{$firstrow['tehseelId']}'";
        $result_tehsil = execute_query($tehsilsql);
        $row_tehsil = mysqli_fetch_assoc($result_tehsil);

        //block

        $blocksql = "select * from sammelen_master_block WHERE BlockId='{$firstrow['blociId']}'";
        $result_block = execute_query($blocksql);
        $row_block = mysqli_fetch_assoc($result_block);
        ?>
            <div class="wrap">
                <div class="header ">
                <img src="images/upcoop_logo.jpg" height="30"><h5>सहकारिता महा सम्मेलन </h5>
                </div>
                <hr>
                <h6>Identity Card</h6>
                <div class="" style="display:flex;justify-content:center;">
                    <img src="http://dummyimage.com/90x100/f2f2f2/000000.png" />
                    <!-- <div class="picture" style="width:90px;height:100px;background-color:gray;"></div> -->
                </div>
                <div id="idCard">
                    <div class="wrapper">
                        <div class="info " style="width:100%;">
                            
                        <table style="width:100%;">
                            <tr>
                                <td width="40%"><label>Name :</label></td>
                                <td width="60%"><?php echo $firstrow['name']?></td>
                            </tr>
                            <tr>
                                <td><label>Father's Name :</label></td>
                                <td><?php echo $firstrow['father_name']?></td>
                            </tr>
                            <tr>
                                <td><label>Mobile No. :</label></td>
                                <td><?php echo $firstrow['mobile_num']?></td>
                            </tr>
                            <tr>
                                <td><label>Society Type :</label></td>
                                <td><?php echo $row_type['stypename']; ?></td>
                            </tr>
                            <tr>
                                <td><label>मण्डल :</label></td>
                                <td><?php echo $row_mandal['division_name']; ?></td>
                            </tr>
                            <?php
                                if(isset($row_district['City'])){
                                    ?>
                                        <tr>
                                            <td><label>जिला :</label></td>
                                            <td><?php echo $row_district['City']; ?></td>
                                        </tr>
                                    <?php
                                }
                            
                            ?>
                            <?php
                                if(isset($row_tehsil['TehsilName'])){
                                    ?>
                                        <tr>
                                            <td><label>तहसील :</label></td>
                                            <td><?php echo $row_tehsil['TehsilName']; ?></td>
                                        </tr>
                                    <?php
                                }
                            ?>
                            <?php
                                if(isset($row_block['BlockName'])){
                                    ?>
                                        <tr>
                                            <td><label>विकास खंड :</label></td>
                                            <td><?php echo $row_block['BlockName']; ?></td>
                                        </tr>
                                    <?php
                                }
                            ?>
                        </table>

                            
                        </div>
                    </div>
                    <div>
                        
                    <p id="societyName" style="font-size:0.7rem;"><label style="font-size:0.8rem;">Society Name:</label><?php  
                    if($row_type['hasSociety']=="1"){
                        echo $row_sname['SocietyName'];
                      }else{
                        echo "";
                      }
                     ?></p>
                    </div>
                    
                    <div style="position:relative;">
                        
                    <p id="" style="font-size:0.7rem;margin-top:0.6rem;">Aurthorized By <span style="font-weight:bolder;">Vivek Singh</span>  </p>
                    <img src="signature.png" alt="" width="50" style="position:absolute;top:-20px;right:40px;">

                    </div>
                    
                    <hr>
                
                    <p id="" style="font-size:0.7rem;">Generated By <b><?php echo $row['created_by']?></b> , Generated On <b><?php echo date("d-m-Y",strtotime($row['creation_time']));?></b></p>

                    
                    
                </div>


            </div>
            <br>
            <br>

        
        <?php
    }
}else{
    echo "<h5 class='text text-danger'>NO data Found</h5>";
  }
?>

<br><h4>उपाअध्यक्ष </h4>
<br>
<?php
$first="SELECT * FROM sammelen_trans WHERE sammelen_invoice_id ='{$row['sno']}'and memberType='उपाअध्यक्ष'" ;
$firstres=mysqli_query($db,$first);
if(mysqli_num_rows($firstres)>0){
    while($firstrow=mysqli_fetch_assoc($firstres)){
        //tehsil name
        $tehsilsql = "select * from sammelen_master_tehseel WHERE TehsilCode='{$firstrow['tehseelId']}'";
        $result_tehsil = execute_query($tehsilsql);
        $row_tehsil = mysqli_fetch_assoc($result_tehsil);

        //block

        $blocksql = "select * from sammelen_master_block WHERE BlockId='{$firstrow['blociId']}'";
        $result_block = execute_query($blocksql);
        $row_block = mysqli_fetch_assoc($result_block);
        ?>
            <div class="wrap">
                <div class="header ">
                <img src="images/upcoop_logo.jpg" height="30"><h5>सहकारिता महा सम्मेलन </h5>
                </div>
                <hr>
                <h6>Identity Card</h6>
                <div class="" style="display:flex;justify-content:center;">
                    <img src="http://dummyimage.com/90x100/f2f2f2/000000.png" />
                    <!-- <div class="picture" style="width:90px;height:100px;background-color:gray;"></div> -->
                </div>
                <div id="idCard">
                    <div class="wrapper">
                        <div class="info " style="width:100%;">
                            
                        <table style="width:100%;">
                            <tr>
                                <td width="40%"><label>Name :</label></td>
                                <td width="60%"><?php echo $firstrow['name']?></td>
                            </tr>
                            <tr>
                                <td><label>Father's Name :</label></td>
                                <td><?php echo $firstrow['father_name']?></td>
                            </tr>
                            <tr>
                                <td><label>Mobile No. :</label></td>
                                <td><?php echo $firstrow['mobile_num']?></td>
                            </tr>
                            <tr>
                                <td><label>Society Type :</label></td>
                                <td><?php echo $row_type['stypename']; ?></td>
                            </tr>
                            <tr>
                                <td><label>मण्डल :</label></td>
                                <td><?php echo $row_mandal['division_name']; ?></td>
                            </tr>
                            <?php
                                if(isset($row_district['City'])){
                                    ?>
                                        <tr>
                                            <td><label>जिला :</label></td>
                                            <td><?php echo $row_district['City']; ?></td>
                                        </tr>
                                    <?php
                                }
                            
                            ?>
                            <?php
                                if(isset($row_tehsil['TehsilName'])){
                                    ?>
                                        <tr>
                                            <td><label>तहसील :</label></td>
                                            <td><?php echo $row_tehsil['TehsilName']; ?></td>
                                        </tr>
                                    <?php
                                }
                            ?>
                            <?php
                                if(isset($row_block['BlockName'])){
                                    ?>
                                        <tr>
                                            <td><label>विकास खंड :</label></td>
                                            <td><?php echo $row_block['BlockName']; ?></td>
                                        </tr>
                                    <?php
                                }
                            ?>
                        </table>

                            
                        </div>
                    </div>
                    <div>
                        
                    <p id="societyName" style="font-size:0.7rem;"><label style="font-size:0.8rem;">Society Name:</label><?php  
                    if($row_type['hasSociety']=="1"){
                        echo $row_sname['SocietyName'];
                      }else{
                        echo "";
                      }
                     ?></p>
                    </div>
                    
                    <div style="position:relative;">
                        
                    <p id="" style="font-size:0.7rem;margin-top:0.6rem;">Aurthorized By <span style="font-weight:bolder;">Vivek Singh</span>  </p>
                    <img src="signature.png" alt="" width="50" style="position:absolute;top:-20px;right:40px;">

                    </div>
                    
                    <hr>
                
                    <p id="" style="font-size:0.7rem;">Generated By <b><?php echo $row['created_by']?></b> , Generated On <b><?php echo date("d-m-Y",strtotime($row['creation_time']));?></b></p>

                    
                    
                </div>


            </div>
            <br>
            <br>

        
        <?php
    }
}else{
    echo "<h5 class='text text-danger'>NO data Found</h5>";
  }
?>

<br>


<h4>प्रबंध समिति संचालक गण </h4>
<br>
<?php
$first="SELECT * FROM sammelen_trans WHERE sammelen_invoice_id ='{$row['sno']}'and memberType='प्रबंध समिति संचालक'" ;
$firstres=mysqli_query($db,$first);
if(mysqli_num_rows($firstres)>0){
    while($firstrow=mysqli_fetch_assoc($firstres)){
        //tehsil name
        $tehsilsql = "select * from sammelen_master_tehseel WHERE TehsilCode='{$firstrow['tehseelId']}'";
        $result_tehsil = execute_query($tehsilsql);
        $row_tehsil = mysqli_fetch_assoc($result_tehsil);

        //block

        $blocksql = "select * from sammelen_master_block WHERE BlockId='{$firstrow['blociId']}'";
        $result_block = execute_query($blocksql);
        $row_block = mysqli_fetch_assoc($result_block);
        ?>
            <div class="wrap">
                <div class="header ">
                <img src="images/upcoop_logo.jpg" height="30"><h5>सहकारिता महा सम्मेलन </h5>
                </div>
                <hr>
                <h6>Identity Card</h6>
                <div class="" style="display:flex;justify-content:center;">
                    <img src="http://dummyimage.com/90x100/f2f2f2/000000.png" />
                    <!-- <div class="picture" style="width:90px;height:100px;background-color:gray;"></div> -->
                </div>
                <div id="idCard">
                    <div class="wrapper">
                        <div class="info " style="width:100%;">
                            
                        <table style="width:100%;">
                            <tr>
                                <td width="40%"><label>Name :</label></td>
                                <td width="60%"><?php echo $firstrow['name']?></td>
                            </tr>
                            <tr>
                                <td><label>Father's Name :</label></td>
                                <td><?php echo $firstrow['father_name']?></td>
                            </tr>
                            <tr>
                                <td><label>Mobile No. :</label></td>
                                <td><?php echo $firstrow['mobile_num']?></td>
                            </tr>
                            <tr>
                                <td><label>Society Type :</label></td>
                                <td><?php echo $row_type['stypename']; ?></td>
                            </tr>
                            <tr>
                                <td><label>मण्डल :</label></td>
                                <td><?php echo $row_mandal['division_name']; ?></td>
                            </tr>
                            <?php
                                if(isset($row_district['City'])){
                                    ?>
                                        <tr>
                                            <td><label>जिला :</label></td>
                                            <td><?php echo $row_district['City']; ?></td>
                                        </tr>
                                    <?php
                                }
                            
                            ?>
                            <?php
                                if(isset($row_tehsil['TehsilName'])){
                                    ?>
                                        <tr>
                                            <td><label>तहसील :</label></td>
                                            <td><?php echo $row_tehsil['TehsilName']; ?></td>
                                        </tr>
                                    <?php
                                }
                            ?>
                            <?php
                                if(isset($row_block['BlockName'])){
                                    ?>
                                        <tr>
                                            <td><label>विकास खंड :</label></td>
                                            <td><?php echo $row_block['BlockName']; ?></td>
                                        </tr>
                                    <?php
                                }
                            ?>
                        </table>

                            
                        </div>
                    </div>
                    <div>
                        
                    <p id="societyName" style="font-size:0.7rem;"><label style="font-size:0.8rem;">Society Name:</label><?php  
                    if($row_type['hasSociety']=="1"){
                        echo $row_sname['SocietyName'];
                      }else{
                        echo "";
                      }
                     ?></p>
                    </div>
                    
                    <div style="position:relative;">
                        
                    <p id="" style="font-size:0.7rem;margin-top:0.6rem;">Aurthorized By <span style="font-weight:bolder;">Vivek Singh</span>  </p>
                    <img src="signature.png" alt="" width="50" style="position:absolute;top:-20px;right:40px;">

                    </div>
                    
                    <hr>
                
                    <p id="" style="font-size:0.7rem;">Generated By <b><?php echo $row['created_by']?></b> , Generated On <b><?php echo date("d-m-Y",strtotime($row['creation_time']));?></b></p>

                    
                    
                </div>


            </div>
            <br>
            <br>

        
        <?php
    }
}else{
    echo "<h5 class='text text-danger'>NO data Found</h5>";
  }
?>

<br>
<br>
<h4>संस्था के प्रतिनिधि </h4>
<br>
<?php

$second="SELECT * FROM sammelen_trans WHERE sammelen_invoice_id ='{$row['sno']}' and memberType='संस्था के प्रतिनिधि/डेलीगेट'" ;$secondres=mysqli_query($db,$second);
if(mysqli_num_rows($secondres)>0){
    while($secondrow=mysqli_fetch_assoc($secondres)){
        ?>
        
             <div class="wrap">
             <div class="header ">
                <img src="images/upcoop_logo.jpg" height="30"><h5>सहकारिता महा सम्मेलन </h5>
                </div>
                <hr>
                <h6>Identity Card</h6>
                <div class="" style="display:flex;justify-content:center;">
                    <img src="http://dummyimage.com/90x100/f2f2f2/000000.png" />
                </div>

                <div id="idCard">
                    <div class="wrapper">
                    <div class="info">
                    <table width="100%">
                            <tr>
                                <td width="40%"><label>Name :</label></td>
                                <td width="60%"><?php echo $secondrow['name']?></td>
                            </tr>
                            <tr>
                                <td><label>Father's Name :</label></td>
                                <td><?php echo $secondrow['father_name']?></td>
                            </tr>
                            <tr>
                                <td><label>Mobile No. :</label></td>
                                <td><?php echo $secondrow['mobile_num']?></td>
                            </tr>
                            <tr>
                                <td><label>Society Type :</label></td>
                                <td><?php echo $row_type['stypename']; ?></td>
                            </tr>
                            <tr>
                                <td><label>मण्डल :</label></td>
                                <td><?php echo $row_mandal['division_name']; ?></td>
                            </tr>
                            <?php
                                if(isset($row_district['City'])){
                                    ?>
                                        <tr>
                                            <td><label>जिला :</label></td>
                                            <td><?php echo $row_district['City']; ?></td>
                                        </tr>
                                    <?php
                                }
                            
                            ?>
                            <?php
                                if(isset($row_tehsil['TehsilName'])){
                                    ?>
                                        <tr>
                                            <td><label>तहसील :</label></td>
                                            <td><?php echo $row_tehsil['TehsilName']; ?></td>
                                        </tr>
                                    <?php
                                }
                            ?>
                            <?php
                                if(isset($row_block['BlockName'])){
                                    ?>
                                        <tr>
                                            <td><label>विकास खंड :</label></td>
                                            <td><?php echo $row_block['BlockName']; ?></td>
                                        </tr>
                                    <?php
                                }
                            ?>
                        </table>

                                                
                    </div>
                </div>
                <div>

                    <p id="societyName" style="font-size:0.7rem;"><label style="font-size:0.8rem;">Society Name:</label><?php  
                    if($row_type['hasSociety']=="1"){
                        echo $row_sname['SocietyName'];
                      }else{
                        echo "";
                      }
                     ?></p>
                </div>

                <div style="position:relative;">

                    <p id="societyName" style="font-size:0.7rem;margin-top:0.6rem;">Aurthorized By <span style="font-weight:bolder;">Vivek Singh</span>  </p>
                    <img src="signature.png" alt="" width="50" style="position:absolute;top:-20px;right:40px;">

                </div>

                <hr>

                <p id="societyName" style="font-size:0.7rem;">Generated By <b><?php echo $row['created_by']?></b> , Generated On <b><?php echo date("d-m-Y",strtotime($row['creation_time']));?></b></p>

            </div>


        </div>
        <br>
        <br>

        <?php
    }
}else{
    echo "<h5 class='text text-danger'>NO data Found</h5>";
  }

?>

<br><br>
<h4>नए सदस्य </h4>
<br>
<?php


$third="SELECT * FROM sammelen_trans WHERE sammelen_invoice_id ='{$row['sno']}' and memberType='सदस्य'" ;
$thirdres=mysqli_query($db,$third);
if(mysqli_num_rows($thirdres)>0){
    while($thirdrow=mysqli_fetch_assoc($thirdres)){
        ?>
            <div class="wrap">
                <div class="header ">
                    <img src="images/upcoop_logo.jpg" height="30"><h5>सहकारिता महा सम्मेलन </h5>
                </div>
                <hr>
                <h6>Identity Card</h6>
                <div class="" style="display:flex;justify-content:center;">
                    <img src="http://dummyimage.com/90x100/f2f2f2/000000.png" />
                </div>

                <div id="idCard">
                    <div class="wrapper">
                        <div class="info">
                            <table width="100%">
                                    <tr>
                                        <td width="40%"><label>Name :</label></td>
                                        <td width="60%"><?php echo $thirdrow['name']?></td>
                                    </tr>
                                    <tr>
                                        <td><label>Father's Name :</label></td>
                                        <td><?php echo $thirdrow['father_name']?></td>
                                    </tr>
                                    <tr>
                                        <td><label>Mobile No. :</label></td>
                                        <td><?php echo $thirdrow['mobile_num']?></td>
                                    </tr>
                                    <tr>
                                        <td><label>Society Type :</label></td>
                                        <td><?php echo $row_type['stypename']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><label>मण्डल :</label></td>
                                        <td><?php echo $row_mandal['division_name']; ?></td>
                                    </tr>
                                    <?php
                                        if(isset($row_district['City'])){
                                            ?>
                                                <tr>
                                                    <td><label>जिला :</label></td>
                                                    <td><?php echo $row_district['City']; ?></td>
                                                </tr>
                                            <?php
                                        }
                                    
                                    ?>
                                    <?php
                                        if(isset($row_tehsil['TehsilName'])){
                                            ?>
                                                <tr>
                                                    <td><label>तहसील :</label></td>
                                                    <td><?php echo $row_tehsil['TehsilName']; ?></td>
                                                </tr>
                                            <?php
                                        }
                                    ?>
                                    <?php
                                        if(isset($row_block['BlockName'])){
                                            ?>
                                                <tr>
                                                    <td><label>विकास खंड :</label></td>
                                                    <td><?php echo $row_block['BlockName']; ?></td>
                                                </tr>
                                            <?php
                                        }
                                    ?>
                            </table>

                                                                        
                        </div>
                    </div>
                    <div>

                       <p id="societyName" style="font-size:0.7rem;"><label style="font-size:0.8rem;">Society Name:</label><?php  
                    if($row_type['hasSociety']=="1"){
                        echo $row_sname['SocietyName'];
                      }else{
                        echo "";
                      }
                     ?></p>
                    </div>

                    <div style="position:relative;">
                        <p id="societyName" style="font-size:0.7rem;margin-top:0.6rem;">Aurthorized By <span style="font-weight:bolder;">Vivek Singh</span>  </p>
                        <img src="signature.png" alt="" width="50" style="position:absolute;top:-20px;right:40px;">
                    </div>

                    <hr>

                    <p id="societyName" style="font-size:0.7rem;">Generated By <b><?php echo $row['created_by']?></b> , Generated On <b><?php echo date("d-m-Y",strtotime($row['creation_time']));?></b></p>
                 </div>


            </div>
            <br>
            <br>
        <?php
    }
}else{
    echo "<h5 class='text text-danger'>NO data Found</h5>";
  }
?>

<br><br>
<h4>अन्य </h4>
<br>
<?php

$fouth="SELECT * FROM sammelen_trans WHERE sammelen_invoice_id ='{$row['sno']}' and memberType='अन्य'" ;
$fouthres=mysqli_query($db,$fouth);
if(mysqli_num_rows($fouthres)>0){
    while($fouthrow=mysqli_fetch_assoc($fouthres)){
        ?>
            <div class="wrap">
                <div class="header ">
                    <img src="images/upcoop_logo.jpg" height="30"><h5>सहकारिता महा सम्मेलन </h5>
                </div>
                <hr>
                <h6>Identity Card</h6>
                <div class="" style="display:flex;justify-content:center;">
                    <img src="http://dummyimage.com/90x100/f2f2f2/000000.png" />
                </div>

                <div id="idCard">
                    <div class="wrapper">
                        <div class="info">
                            <table width="100%">
                                    <tr>
                                        <td width="40%"><label>Name :</label></td>
                                        <td width="60%"><?php echo $fouthrow['name']?></td>
                                    </tr>
                                    <tr>
                                        <td><label>Father's Name :</label></td>
                                        <td><?php echo $fouthrow['father_name']?></td>
                                    </tr>
                                    <tr>
                                        <td><label>Mobile No. :</label></td>
                                        <td><?php echo $fouthrow['mobile_num']?></td>
                                    </tr>
                                    <tr>
                                        <td><label>Society Type :</label></td>
                                        <td><?php echo $row_type['stypename']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><label>मण्डल :</label></td>
                                        <td><?php echo $row_mandal['division_name']; ?></td>
                                    </tr>
                                    <?php
                                        if(isset($row_district['City'])){
                                            ?>
                                                <tr>
                                                    <td><label>जिला :</label></td>
                                                    <td><?php echo $row_district['City']; ?></td>
                                                </tr>
                                            <?php
                                        }
                                    
                                    ?>
                                    <?php
                                        if(isset($row_tehsil['TehsilName'])){
                                            ?>
                                                <tr>
                                                    <td><label>तहसील :</label></td>
                                                    <td><?php echo $row_tehsil['TehsilName']; ?></td>
                                                </tr>
                                            <?php
                                        }
                                    ?>
                                    <?php
                                        if(isset($row_block['BlockName'])){
                                            ?>
                                                <tr>
                                                    <td><label>विकास खंड :</label></td>
                                                    <td><?php echo $row_block['BlockName']; ?></td>
                                                </tr>
                                            <?php
                                        }
                                    ?>
                            </table>

                                                                        
                        </div>
                    </div>
                    <div>

                    <p id="societyName" style="font-size:0.7rem;"><label style="font-size:0.8rem;">Society Name:</label><?php  
                            if($row_type['hasSociety']=="1"){
                                echo $row_sname['SocietyName'];
                            }else{
                                echo "";
                            }
                     ?></p>
                    </div>

                    <div style="position:relative;">
                        <p id="societyName" style="font-size:0.7rem;margin-top:0.6rem;">Aurthorized By <span style="font-weight:bolder;">Vivek Singh</span>  </p>
                        <img src="signature.png" alt="" width="50" style="position:absolute;top:-20px;right:40px;">
                    </div>

                    <hr>

                    <p id="societyName" style="font-size:0.7rem;">Generated By <b><?php echo $row['created_by']?></b> , Generated On <b><?php echo date("d-m-Y",strtotime($row['creation_time']));?></b></p>
                 </div>


            </div>
            <br>
            <br>
        <?php
    }
}else{
    echo "<h5 class='text text-danger'>NO data Found</h5>";
  }
?>

   
    
   


				


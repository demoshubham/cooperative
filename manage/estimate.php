<?php
include("scripts/settings.php");

$sql = 'SELECT survey_invoice.sno as sno, col4 as society_name, division_name, district_name, tehseel_name, block_name, type_name, mobile_number, latitude, longitude, concat("user_data/", col2, "/", col6, "/", photo_id) as photo_id FROM survey_invoice left join `test2` on `test2`.sno = society_id left join master_division on master_division.sno = col1 left join master_district on master_district.sno = col2 left join master_tehseel on master_tehseel.sno = col5 left join master_block on master_block.sno = col6 left join master_society_type on master_society_type.sno = col3 where survey_invoice.sno='.$_GET['exdid'];
$invoice_data = mysqli_fetch_assoc(execute_query($sql));


page_header_start();
page_header_end();
?>
<style>
	.h3{
		font-color:red;
	}
</style>
	<div class="row m-4" >
		<div class="col-md-10 mx-auto card ">
			<div class="row">
				<div class="col-md-12 mb-2 p-3 border-bottom">
					<div class="row">
						<div class="col-md-4 text-right">
							<img src="images/upcoop_logo.jpg" height="100">
						</div>
						<div class="col-md-8">
							<h4 class="m-0 p-0 text-left ">Estimate<br/>सहकारिता विभाग ( Cooperative Department )<br>सहकारी समितियों के सर्वेक्षण पोर्टल पर आपका स्वागत है । </h4>
						</div>                                
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12">
									<label for="inputPassword" class="col-form-label">समिति का नाम : </label>
									<?php echo $invoice_data['society_name']; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<label for="inputPassword" class="col-form-label">मण्डल : </label>
									<?php echo $invoice_data['division_name']; ?>
								</div>

							</div>
							<div class="row">
								<div class="col-md-12">
									<label for="inputPassword" class="col-form-label">जिला : </label>
									<?php echo $invoice_data['district_name']; ?>
								</div>	
							</div>
							<div class="row">
								<div class="col-md-12">
									<label for="inputPassword" class="col-form-label">जिला : </label>
									<?php echo $invoice_data['district_name']; ?>
								</div>	
							</div>
							<div class="row">
								<div class="col-md-12">
									<label for="inputPassword" class="col-form-label"> ब्लाक एवम तहसील :  </label>
									<?php echo $invoice_data['block_name'].'-'.$invoice_data['tehseel_name']; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-8">
									<label for="inputPassword" class="col-form-label">समिति का प्रकार : </label>
									<?php echo $invoice_data['type_name']; ?>

								</div>
								<div class="col-md-4">
									<label for="inputPassword" class="col-form-label">मोबाइल नंबर : </label>
									<?php echo $invoice_data['mobile_number']; ?>
								</div>
							</div>
							
						</div>
						<div class="col-md-6 border-left">
							<div class="row">
								<div class="col-md-6" id="map_container">
									<iframe id="googlemap" src="https://maps.google.com/maps?q=<?php echo $invoice_data['latitude'].','.$invoice_data['longitude'];?>&hl=en&z=13&amp;output=embed" width="100%" height="100%" style="border:1px solid; border-radius:10px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
								</div>
								<div class="col-md-6">
									<img src="<?php echo 'https://upcod.in/'.$invoice_data['photo_id']; ?>" class="img-fluid img-thumbnail">
								</div>
							</div>
							
						</div>
					</div>
					<div class="row">
						
					</div><br>
				</div>
			</div>
			<div class="row border-top">
				<div class="col-md-12">
					<div class="p-1"><h4 class="m-1">(A) मरम्मत योग्य दर्ज विवरण के आधार पर अनुमानित लागत</h4></div>
					<?php
					$sql = 'select * from survey_invoice_sec_5 where survey_id="'.$invoice_data['sno'].'"';
					//echo $sql;
					$result_sec_5 = execute_query($sql);
					if(mysqli_num_rows($result_sec_5)!=0){
						$row_sec_5 = mysqli_fetch_assoc($result_sec_5);
						$row_sec_5['floor_length'] = floatval($row_sec_5['floor_length']);
						$row_sec_5['floor_width'] = floatval($row_sec_5['floor_width']);
						$row_sec_5['wall_length'] = floatval($row_sec_5['wall_length']);
						$row_sec_5['wall_width'] = floatval($row_sec_5['wall_width']);
						$row_sec_5['paint_length'] = floatval($row_sec_5['paint_length']);
						$row_sec_5['paint_width'] = floatval($row_sec_5['paint_width']);
						$row_sec_5['roof_length'] = floatval($row_sec_5['roof_length']);
						$row_sec_5['roof_width'] = floatval($row_sec_5['roof_width']);
						$row_sec_5['washroom_floor'] = floatval($row_sec_5['washroom_floor']);
						$row_sec_5['washroom_plaster'] = floatval($row_sec_5['washroom_plaster']);
						$row_sec_5['washroom_roof'] = floatval($row_sec_5['washroom_roof']);
						$row_sec_5['washroom_seat'] = floatval($row_sec_5['washroom_seat']);
						$row_sec_5['washroom_plumbing'] = floatval($row_sec_5['washroom_plumbing']);
						$row_sec_5['plaster_wall'] = floatval($row_sec_5['plaster_wall']);
						$row_sec_5['plaster_roof'] = floatval($row_sec_5['plaster_roof']);						
					}
					
					$tot_labour_cess=0;
					$tot_agency_centage=0;
					$tot_gst=0;
					$tot_washroom=0;
					$tot_washroom_subtotal=0;
					$tot_value=0;
					
					$grand_tot_labour_cess=0;
					$grand_tot_agency_centage=0;
					$grand_tot_gst=0;
					$grand_tot_washroom=0;
					$grand_tot_washroom_subtotal=0;
					$grand_tot_value=0;
					
					$repair_floor=3;
					$repair_wall=4;
					$repair_paint=8;
					$repair_roof=1;
					$repair_plaster_wall=6;
					$repair_plaster_roof=7;
					$repair_door = 12;
					$repair_window = 13;
					$new_godown = 20;
					$new_bathroom = 30;
					$new_showroom = 31;
					$new_boundry = 21;
					$new_hall = 29;

					
					$sql = 'select * from survey_rates where sno='.$repair_floor;
					$repair_floor = mysqli_fetch_assoc(execute_query($sql));
					
					$sql = 'select * from survey_rates where sno='.$repair_wall;
					$repair_wall = mysqli_fetch_assoc(execute_query($sql));
					$repair_wall['less_work'] = round($repair_wall['less_work'],2);
					$repair_wall['labour_cess'] = round($repair_wall['labour_cess'],2);
					$repair_wall['agency_centage'] = round($repair_wall['agency_centage'],2);
					$repair_wall['gst'] = round($repair_wall['gst'],2);
					$repair_wall['grand_total'] = round($repair_wall['grand_total'],2);
					
					$sql = 'select * from survey_rates where sno='.$repair_paint;
					$repair_paint = mysqli_fetch_assoc(execute_query($sql));
					
					$sql = 'select * from survey_rates where sno='.$repair_roof;
					$repair_roof = mysqli_fetch_assoc(execute_query($sql));
					
					$sql = 'select * from survey_rates where sno='.$repair_plaster_wall;
					$repair_plaster_wall = mysqli_fetch_assoc(execute_query($sql));
					
					$sql = 'select * from survey_rates where sno='.$repair_door;
					$repair_door = mysqli_fetch_assoc(execute_query($sql));
					
					$sql = 'select * from survey_rates where sno='.$repair_window;
					$repair_window = mysqli_fetch_assoc(execute_query($sql));
					
					$sql = 'select * from survey_rates where sno='.$new_godown;
					$new_godown = mysqli_fetch_assoc(execute_query($sql));
					
					$sql = 'select * from survey_rates where sno='.$new_bathroom;
					$new_bathroom = mysqli_fetch_assoc(execute_query($sql));
					
					$sql = 'select * from survey_rates where sno='.$new_showroom;
					$new_showroom = mysqli_fetch_assoc(execute_query($sql));
					
					$sql = 'select * from survey_rates where sno='.$new_boundry;
					$new_boundry = mysqli_fetch_assoc(execute_query($sql));
					
					$sql = 'select * from survey_rates where sno='.$new_hall;
					$new_hall = mysqli_fetch_assoc(execute_query($sql));
					
					
					?>
					<table class="table table-striped table-bordered">
						<tr class="p-2 mb-3 bg-primary  text-white" align="center" >
							<th>विवरण </th>
							<th>लंबाई (मीटर में)</th>
							<th>चौड़ाई (मीटर में)</th>
							<th>क्षेत्रफल</th>
							<th>दर</th>
							<th>लेबर सेस 1.00%</th>
							<th>एजेंसी सेंटेज 12.50%</th>
							<th>जी.एस.टी. 18.00%</th>
							<th>कुल दर</th>
							<th>कुल मुल्य</th>
						</tr>
						<tr>
							<th>फर्श </th>
							<td class="text-right"><?php echo $row_sec_5['floor_length']; ?></td>
							<td class="text-right"><?php echo $row_sec_5['floor_width']; ?></td>
							<td class="text-right"><?php echo ($row_sec_5['floor_length']*$row_sec_5['floor_width']); ?></td>
							<td class="text-right"><?php echo $repair_floor['less_work']; ?></td>
							<td class="text-right"><?php echo $repair_floor['labour_cess']; ?></td>
							<td class="text-right"><?php echo $repair_floor['agency_centage']; ?></td>
							<td class="text-right"><?php echo $repair_floor['gst']; ?></td>
							<td class="text-right"><?php echo $repair_floor['grand_total']; ?></td>
							<td class="text-right">
								<?php 
								$area_floor = $row_sec_5['floor_length']*$row_sec_5['floor_width'];
								echo round($repair_floor['grand_total']*$area_floor,2); 
								$tot_labour_cess+=($repair_floor['labour_cess']*$area_floor);
								$tot_agency_centage+=($repair_floor['agency_centage']*$area_floor);
								$tot_gst+=($repair_floor['gst']*$area_floor);
								$tot_value+=($repair_floor['grand_total']*$area_floor);

								?>
							</td>
						</tr>
						<!--<tr>
							<th>दीवार </th>
							<td class="text-right"><?php echo $row_sec_5['wall_length']; ?></td>
							<td class="text-right"><?php echo $row_sec_5['wall_width']; ?></td>
							<td class="text-right"><?php echo ($row_sec_5['wall_length']*$row_sec_5['wall_width']).' ('.(($row_sec_5['wall_length']*$row_sec_5['wall_width'])*0.23).' CuMtr)'; ?></td>
							<td class="text-right"><?php echo $repair_wall['less_work']; ?></td>
							<td class="text-right"><?php echo $repair_wall['labour_cess']; ?></td>
							<td class="text-right"><?php echo $repair_wall['agency_centage']; ?></td>
							<td class="text-right"><?php echo $repair_wall['gst']; ?></td>
							<td class="text-right"><?php echo $repair_wall['grand_total']; ?></td>
							<td class="text-right">
								<?php 
								/*$area_wall = ($row_sec_5['wall_length']*$row_sec_5['wall_width'])*0.23;
								echo round($repair_wall['grand_total']*$area_wall,2); 
								$tot_labour_cess+=($repair_wall['labour_cess']*$area_wall);
								$tot_agency_centage+=($repair_wall['agency_centage']*$area_wall);
								$tot_gst+=($repair_wall['gst']*$area_wall);
								$tot_value+=($repair_wall['grand_total']*$area_wall);*/

								?>
							</td>
						</tr>-->
						<tr>
							<th>पुताई </th>
							<td class="text-right"><?php echo $row_sec_5['paint_length']; ?></td>
							<td class="text-right"><?php echo $row_sec_5['paint_width']; ?></td>
							<td class="text-right"><?php echo ($row_sec_5['paint_length']*$row_sec_5['paint_width']); ?></td>
							<td class="text-right"><?php echo $repair_paint['less_work']; ?></td>
							<td class="text-right"><?php echo $repair_paint['labour_cess']; ?></td>
							<td class="text-right"><?php echo $repair_paint['agency_centage']; ?></td>
							<td class="text-right"><?php echo $repair_paint['gst']; ?></td>
							<td class="text-right"><?php echo $repair_paint['grand_total']; ?></td>
							<td class="text-right">
								<?php 
								$area_paint = $row_sec_5['paint_length']*$row_sec_5['paint_width'];
								echo round($repair_paint['grand_total']*$area_paint,2); 
								$tot_labour_cess+=($repair_paint['labour_cess']*$area_paint);
								$tot_agency_centage+=($repair_paint['agency_centage']*$area_paint);
								$tot_gst+=($repair_paint['gst']*$area_paint);
								$tot_value+=($repair_paint['grand_total']*$area_paint);

								?>
							</td>
						</tr>
						<tr>
							<th>छत </th>
							<td class="text-right"><?php echo $row_sec_5['roof_length']; ?></td>
							<td class="text-right"><?php echo $row_sec_5['roof_width']; ?></td>
							<td class="text-right"><?php echo ($row_sec_5['roof_length']*$row_sec_5['roof_width']); ?></td>
							<td class="text-right"><?php echo $repair_roof['less_work']; ?></td>
							<td class="text-right"><?php echo $repair_roof['labour_cess']; ?></td>
							<td class="text-right"><?php echo $repair_roof['agency_centage']; ?></td>
							<td class="text-right"><?php echo $repair_roof['gst']; ?></td>
							<td class="text-right"><?php echo $repair_roof['grand_total']; ?></td>
							<td class="text-right">
								<?php 
								$area_roof = $row_sec_5['roof_length']*$row_sec_5['roof_width'];
								echo round($repair_roof['grand_total']*$area_roof,2); 
								$tot_labour_cess+=($repair_roof['labour_cess']*$area_roof);
								$tot_agency_centage+=($repair_roof['agency_centage']*$area_roof);
								$tot_gst+=($repair_roof['gst']*$area_roof);
								$tot_value+=($repair_roof['grand_total']*$area_roof);

								?>
							</td>
						</tr>
						<tr>
							<th>प्लास्टर दिवार</th>
							<td class="text-right">-</td>
							<td class="text-right">-</td>
							<td class="text-right"><?php echo ($row_sec_5['plaster_wall']); ?></td>
							<td class="text-right"><?php echo $repair_plaster_wall['less_work']; ?></td>
							<td class="text-right"><?php echo $repair_plaster_wall['labour_cess']; ?></td>
							<td class="text-right"><?php echo $repair_plaster_wall['agency_centage']; ?></td>
							<td class="text-right"><?php echo $repair_plaster_wall['gst']; ?></td>
							<td class="text-right"><?php echo $repair_plaster_wall['grand_total']; ?></td>
							<td class="text-right">
								<?php 
								$area_plaster_wall = $row_sec_5['plaster_wall'];
								echo round($repair_plaster_wall['grand_total']*$area_plaster_wall,2); 
								$tot_labour_cess+=($repair_plaster_wall['labour_cess']*$area_plaster_wall);
								$tot_agency_centage+=($repair_plaster_wall['agency_centage']*$area_plaster_wall);
								$tot_gst+=($repair_plaster_wall['gst']*$area_plaster_wall);
								$tot_value+=($repair_plaster_wall['grand_total']*$area_plaster_wall);

								?>
							</td>
						</tr>
						<tr>
							<th >प्लास्टर छत</th>
							<td class="text-right">-</td>
							<td class="text-right">-</td>
							<td class="text-right"><?php echo ($row_sec_5['plaster_roof']); ?></td>
							<td class="text-right"><?php echo $repair_plaster_roof['less_work']; ?></td>
							<td class="text-right"><?php echo $repair_plaster_roof['labour_cess']; ?></td>
							<td class="text-right"><?php echo $repair_plaster_roof['agency_centage']; ?></td>
							<td class="text-right"><?php echo $repair_plaster_roof['gst']; ?></td>
							<td class="text-right"><?php echo $repair_plaster_roof['grand_total']; ?></td>
							<td class="text-right">
								<?php 
								$area_plaster_roof = $row_sec_5['plaster_roof'];
								echo round($repair_plaster_roof['grand_total']*$area_plaster_roof,2); 
								$tot_labour_cess+=($repair_plaster_roof['labour_cess']*$area_plaster_roof);
								$tot_agency_centage+=($repair_plaster_roof['agency_centage']*$area_plaster_roof);
								$tot_gst+=($repair_plaster_roof['gst']*$area_plaster_roof);
								$tot_value+=($repair_plaster_roof['grand_total']*$area_plaster_roof);

								?>
							</td>
						</tr>
						<tr>
							<th colspan="4"></th>
							<td class="text-right">योग :</td>
							<td class="text-right"><?php echo round($tot_labour_cess,2); ?></td>
							<td class="text-right"><?php echo round($tot_agency_centage,2); ?></td>
							<td class="text-right"><?php echo round($tot_gst,2); ?></td>
							<td></td>
							<td class="text-right"><?php echo round($tot_value,2); ?></td>
						</tr>	
						<tr>
							<th colspan="10"><h4 class="m-1">(B) शौचालय :</h4></th>
						</tr>
						<tr class="p-2 mb-3 bg-primary  text-white" align="center" >
							<th>विवरण </th>
							<th>लंबाई (मीटर में)</th>
							<th>चौड़ाई (मीटर में)</th>
							<th>क्षेत्रफल</th>
							<th>दर</th>
							<th>लेबर सेस 1.00%</th>
							<th>एजेंसी सेंटेज 12.50%</th>
							<th>जी.एस.टी. 18.00%</th>
							<th>कुल दर</th>
							<th>कुल मुल्य</th>
						</tr>
						<tr>
							<td>फर्श</td>
							<td></td>
							<td></td>
							<td></td>
							<td class="text-right"><?php echo (is_numeric($row_sec_5['washroom_floor'])?$row_sec_5['washroom_floor']:'');?></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>प्लास्टर </td>
							<td></td>
							<td></td>
							<td></td>
							<td class="text-right"><?php echo (is_numeric($row_sec_5['washroom_plaster'])?$row_sec_5['washroom_plaster']:'');?></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>छत </td>
							<td></td>
							<td></td>
							<td></td>
							<td class="text-right"><?php echo (is_numeric($row_sec_5['washroom_roof'])?$row_sec_5['washroom_roof']:'');?></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>सीट </td>
							<td></td>
							<td></td>
							<td></td>
							<td class="text-right"><?php echo (is_numeric($row_sec_5['washroom_seat'])?$row_sec_5['washroom_seat']:'');?></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>प्लम्बिंग </td>
							<td></td>
							<td></td>
							<td></td>
							<td class="text-right"><?php echo (is_numeric($row_sec_5['washroom_plumbing'])?$row_sec_5['washroom_plumbing']:'');?></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="4">योग :</td>
							<td class="text-right"><?php $tot_washroom=$row_sec_5['washroom_floor']+$row_sec_5['washroom_plaster']+$row_sec_5['washroom_roof']+$row_sec_5['washroom_seat']+$row_sec_5['washroom_plumbing']; echo $tot_washroom; $tot_washroom_subtotal=$tot_washroom; ?></td>
							<td class="text-right"><?php echo round($tot_washroom*0.01,2); $grand_tot_labour_cess = $tot_labour_cess+($tot_washroom*0.01); $tot_washroom_subtotal+=($tot_washroom*0.01); ?></td>
							<td class="text-right"><?php echo round($tot_washroom*0.125,2); $grand_tot_agency_centage =  $tot_agency_centage+($tot_washroom*0.125); $tot_washroom_subtotal+=($tot_washroom*0.125);?></td>
							<td class="text-right"><?php echo round($tot_washroom*0.18,2); $grand_tot_gst = $tot_gst+($tot_washroom*0.18); $tot_washroom_subtotal+=($tot_washroom*0.18);?></td>
							<td class="text-right"><?php echo round($tot_washroom_subtotal, 2); $grand_tot_value = $tot_value+$tot_washroom_subtotal; ?></td>
							<td class="text-right"><?php echo round($tot_washroom_subtotal, 2); $grand_tot_value = $tot_value+$tot_washroom_subtotal; ?></td>
						</tr>
						<tr>
							<th colspan="10"><h4 class="m-1">(C) अन्य :</h4></th>
						</tr>
						<tr class="p-2 mb-3 bg-primary  text-white" align="center" >
							<th>विवरण </th>
							<th>-</th>
							<th>-</th>
							<th>संख्या</th>
							<th>दर</th>
							<th>लेबर सेस 1.00%</th>
							<th>एजेंसी सेंटेज 12.50%</th>
							<th>जी.एस.टी. 18.00%</th>
							<th>कुल दर</th>
							<th>कुल मुल्य</th>
						</tr>
						<tr>
							<td>दरवाजा </td>
							<td></td>
							<td></td>
							<td class="text-right"><?php echo $row_sec_5['doors'];?></td>
							<td class="text-right"><?php echo $repair_door['less_work']; ?></td>
							<td class="text-right"><?php echo $repair_door['labour_cess']; ?></td>
							<td class="text-right"><?php echo $repair_door['agency_centage']; ?></td>
							<td class="text-right"><?php echo $repair_door['gst']; ?></td>
							<td class="text-right"><?php echo $repair_door['grand_total']; ?></td>
							<td class="text-right">
								<?php 
								$area_plaster_wall = $row_sec_5['doors'];
								echo round($repair_door['grand_total']*$area_plaster_wall,2); 
								$tot_door_cess=($repair_door['labour_cess']*$area_plaster_wall);
								$tot_door_centage=($repair_door['agency_centage']*$area_plaster_wall);
								$tot_door_gst=($repair_door['gst']*$area_plaster_wall);
								$tot_door_value=($repair_door['grand_total']*$area_plaster_wall);

								?>
							</td>
						</tr>
						<tr>
							<td>खिडकी </td>
							<td></td>
							<td></td>
							<td class="text-right"><?php echo $row_sec_5['windows'];?></td>
							<td class="text-right"><?php echo $repair_window['less_work']; ?></td>
							<td class="text-right"><?php echo $repair_window['labour_cess']; ?></td>
							<td class="text-right"><?php echo $repair_window['agency_centage']; ?></td>
							<td class="text-right"><?php echo $repair_window['gst']; ?></td>
							<td class="text-right"><?php echo $repair_window['grand_total']; ?></td>
							<td class="text-right">
								<?php 
								$area_plaster_wall = $row_sec_5['windows'];
								echo round($repair_window['grand_total']*$area_plaster_wall,2); 
								$tot_window_cess=($repair_window['labour_cess']*$area_plaster_wall);
								$tot_window_centage=($repair_window['agency_centage']*$area_plaster_wall);
								$tot_window_gst=($repair_window['gst']*$area_plaster_wall);
								$tot_window_value=($repair_window['grand_total']*$area_plaster_wall);

								?>
							</td>
						</tr>
						<tr>
							<td colspan="4">योग :</td>
							<td class="text-right"></td>
							<td class="text-right"><?php $tot_extra_cess = round($tot_window_cess+$tot_door_cess,2); echo $tot_extra_cess; $grand_tot_labour_cess += $tot_extra_cess; ?></td>
							<td class="text-right"><?php $tot_extra_centage = round($tot_window_centage+$tot_door_centage,2); echo $tot_extra_centage; $grand_tot_agency_centage +=  $tot_extra_centage;?></td>
							<td class="text-right"><?php $tot_extra_gst = round($tot_window_gst+$tot_door_gst,2); echo $tot_extra_gst; $grand_tot_gst += $tot_extra_gst; ?></td>
							<td class="text-right"></td>
							<td class="text-right"><?php $tot_extra_total = round($tot_window_value+$tot_door_value,2); echo $tot_extra_total; $grand_tot_value += $tot_extra_total; ?></td>
						</tr>
						<?php
						$sql = 'select * from survey_invoice_sec_11 where survey_id="'.$invoice_data['sno'].'"';
						$result_new_construction = execute_query($sql);
						if(mysqli_num_rows($result_new_construction)!=0){
							$row_new_construcion = mysqli_fetch_assoc($result_new_construction);
						?>
						<tr>
							<th colspan="10">
								<h4 class="m-1">(D) अतिरिक्त निर्माण की आवश्यक्ता</h4>
							</th>
						</tr>
						<tr class="p-2 mb-3 bg-primary  text-white" align="center" >
							<th>विवरण </th>
							<th>लंबाई (मीटर में)</th>
							<th>चौड़ाई (मीटर में)</th>
							<th>क्षेत्रफल</th>
							<th>दर</th>
							<th>लेबर सेस 1.00%</th>
							<th>एजेंसी सेंटेज 12.50%</th>
							<th>जी.एस.टी. 18.00%</th>
							<th>कुल दर</th>
							<th>कुल मुल्य</th>
						</tr>
						<tr align="center">
							<th>गोदाम </th>
							<td><?php echo $row_new_construcion['godown_length']; ?></td>
							<td><?php echo $row_new_construcion['godown_width']; ?></td>
							<td><?php echo round($row_new_construcion['godown_length']*$row_new_construcion['godown_width'],2); ?></td>
							<td class="text-right"><?php echo $new_godown['less_work']; ?></td>
							<td class="text-right"><?php echo $new_godown['labour_cess']; ?></td>
							<td class="text-right"><?php echo $new_godown['agency_centage']; ?></td>
							<td class="text-right"><?php echo $new_godown['gst']; ?></td>
							<td class="text-right"><?php echo $new_godown['grand_total']; ?></td>
							<td>
							<?php
								$new_godown_area = round($row_new_construcion['godown_length']*$row_new_construcion['godown_width'],2);
								$tot_new_cess = $new_godown['labour_cess']*$new_godown_area;
								$tot_new_centage = $new_godown['agency_centage']*$new_godown_area;
								$tot_new_gst = $new_godown['gst']*$new_godown_area;
								$tot_new_total = $new_godown['grand_total']*$new_godown_area;
							
								echo round($new_godown['grand_total']*$new_godown_area,2);
							?>	
							</td>
						</tr>
						<tr align="center">
							<th>बाथरूम </th>
							<td><?php echo $row_new_construcion['bathroom_length']; ?></td>
							<td><?php echo $row_new_construcion['bathroom_width']; ?></td>
							<td><?php echo round($row_new_construcion['bathroom_length']*$row_new_construcion['bathroom_width'],2); ?></td>
							<td class="text-right"><?php echo $new_bathroom['less_work']; ?></td>
							<td class="text-right"><?php echo $new_bathroom['labour_cess']; ?></td>
							<td class="text-right"><?php echo $new_bathroom['agency_centage']; ?></td>
							<td class="text-right"><?php echo $new_bathroom['gst']; ?></td>
							<td class="text-right"><?php echo $new_bathroom['grand_total']; ?></td>
							<td>
							<?php
								$new_bathroom_area = round($row_new_construcion['bathroom_length']*$row_new_construcion['bathroom_width'],2);
								$tot_new_cess += $new_bathroom['labour_cess']*$new_bathroom_area;
								$tot_new_centage += $new_bathroom['agency_centage']*$new_bathroom_area;
								$tot_new_gst += $new_bathroom['gst']*$new_bathroom_area;
								$tot_new_total += $new_bathroom['grand_total']*$new_bathroom_area;
							
								echo round($new_bathroom['grand_total']*$new_bathroom_area,2);
							?>								
							</td>
						</tr>
						<tr align="center">
							<th>शोरूम </th>
							<td><?php echo $row_new_construcion['showroom_length']; ?></td>
							<td><?php echo $row_new_construcion['showroom_width']; ?></td>
							<td><?php echo round($row_new_construcion['showroom_length']*$row_new_construcion['showroom_width'],2); ?></td>
							<td class="text-right"><?php echo $new_showroom['less_work']; ?></td>
							<td class="text-right"><?php echo $new_showroom['labour_cess']; ?></td>
							<td class="text-right"><?php echo $new_showroom['agency_centage']; ?></td>
							<td class="text-right"><?php echo $new_showroom['gst']; ?></td>
							<td class="text-right"><?php echo $new_showroom['grand_total']; ?></td>
							<td>
							<?php
								$new_bathroom_area = round($row_new_construcion['showroom_length']*$row_new_construcion['showroom_width'],2);
								$tot_new_cess += $new_showroom['labour_cess']*$new_bathroom_area;
								$tot_new_centage += $new_showroom['agency_centage']*$new_bathroom_area;
								$tot_new_gst += $new_showroom['gst']*$new_bathroom_area;
								$tot_new_total += $new_showroom['grand_total']*$new_bathroom_area;
							
								echo round($new_showroom['grand_total']*$new_bathroom_area,2);
							?>
							</td>
						</tr>
						<!--<tr align="center">
							<th>बाउंड्री वाल </th>
							<td><?php echo $row_new_construcion['boundary_length']; ?></td>
							<td><?php echo $row_new_construcion['boundary_width']; ?></td>
							<td><?php echo round($row_new_construcion['boundary_length']*$row_new_construcion['boundary_width'],2); ?></td>
							<td class="text-right"><?php echo $new_boundry['less_work']; ?></td>
							<td class="text-right"><?php echo $new_boundry['labour_cess']; ?></td>
							<td class="text-right"><?php echo $new_boundry['agency_centage']; ?></td>
							<td class="text-right"><?php echo $new_boundry['gst']; ?></td>
							<td class="text-right"><?php echo $new_boundry['grand_total']; ?></td>
							<td>
							<?php
								/*$new_bathroom_area = round($row_new_construcion['boundary_length']*$row_new_construcion['boundary_width'],2);
								$tot_new_cess += $new_boundry['labour_cess']*$new_bathroom_area;
								$tot_new_centage += $new_boundry['agency_centage']*$new_bathroom_area;
								$tot_new_gst += $new_boundry['gst']*$new_bathroom_area;
								$tot_new_total += $new_boundry['grand_total']*$new_bathroom_area;
							
								echo round($new_boundry['grand_total']*$new_bathroom_area,2);*/
							?>
							</td>
						</tr>-->
						<tr align="center">
							<th> मल्टीपरपस हाल </th>
							<td><?php echo $row_new_construcion['multipurpose_length']; ?></td>
							<td><?php echo $row_new_construcion['multipurpose_width']; ?></td>
							<td><?php echo round($row_new_construcion['multipurpose_length']*$row_new_construcion['multipurpose_width'],2); ?></td>
							<td class="text-right"><?php echo $new_hall['less_work']; ?></td>
							<td class="text-right"><?php echo $new_hall['labour_cess']; ?></td>
							<td class="text-right"><?php echo $new_hall['agency_centage']; ?></td>
							<td class="text-right"><?php echo $new_hall['gst']; ?></td>
							<td class="text-right"><?php echo $new_hall['grand_total']; ?></td>
							<td>
							<?php
								$new_bathroom_area = round($row_new_construcion['multipurpose_length']*$row_new_construcion['multipurpose_width'],2);
								$tot_new_cess += $new_hall['labour_cess']*$new_bathroom_area;
								$tot_new_centage += $new_hall['agency_centage']*$new_bathroom_area;
								$tot_new_gst += $new_hall['gst']*$new_bathroom_area;
								$tot_new_total += $new_hall['grand_total']*$new_bathroom_area;
							
								echo round($new_hall['grand_total']*$new_bathroom_area,2);
							?>
							</td>
						</tr>
						<tr>
							<td class="text-right" colspan="5">योग : </td>
							<td class="text-right"><?php echo round($tot_new_cess,2); $grand_tot_labour_cess+= $tot_new_cess; ?></td>
							<td class="text-right"><?php echo round($tot_new_centage,2); $grand_tot_agency_centage+= $tot_new_centage; ?></td>
							<td class="text-right"><?php echo round($tot_new_gst,2); $grand_tot_gst+= $tot_new_gst; ?></td>
							<td></td>
							<td class="text-right"><?php echo round($tot_new_total,2); $grand_tot_value += $tot_new_total; ?></td>
						</tr>
						<tr>
							<th colspan="10">
								<h4 class="m-1">(E) कुल योग (A+B+C+D):</h4>			
							</th>
						</tr>
						<tr class="p-5 mb-8 bg-primary  text-white" >
							<th colspan="5">विवरण</th>
							<th>लेबर सेस 1.00%</th>
							<th>एजेंसी सेंटेज 12.50%</th>
							<th>जी.एस.टी. 18.00%</th>
							<th></th>
							<th>कुल मुल्य</th>
						</tr>
						<tr>
							<td colspan="5">मरम्मत योग्य दर्ज विवरण के आधार पर अनुमानित लागत (A)</td>
							<td class="text-right"><?php echo round($tot_labour_cess,2); ?></td>
							<td class="text-right"><?php echo round($tot_agency_centage,2); ?></td>
							<td class="text-right"><?php echo round($tot_gst,2); ?></td>
							<td></td>
							<td class="text-right"><?php echo round($tot_value,2); ?></td>
						</tr>
						<tr>
							<td colspan="5">शौचालय (B) :</td>
							<td class="text-right"><?php echo round($tot_washroom*0.01,2); ?></td>
							<td class="text-right"><?php echo round($tot_washroom*0.125,2); ?></td>
							<td class="text-right"><?php echo round($tot_washroom*0.18,2); ?></td>
							<td></td>
							<td class="text-right"><?php echo round($tot_washroom_subtotal,2); ?></td>
						</tr>
						<tr>
							<td colspan="5">अन्य (C) :</td>
							<td class="text-right"><?php echo $tot_extra_cess; ?></td>
							<td class="text-right"><?php echo $tot_extra_centage; ?></td>
							<td class="text-right"><?php echo $tot_extra_gst; ?></td>
							<td></td>
							<td class="text-right"><?php echo $tot_extra_total; ?></td>
						</tr>
						<tr>
							<td colspan="5">अतिरिक्त निर्माण की आवश्यक्ता (D) :</td>
							<td class="text-right"><?php echo $tot_new_cess; ?></td>
							<td class="text-right"><?php echo $tot_new_centage; ?></td>
							<td class="text-right"><?php echo $tot_new_gst; ?></td>
							<td></td>
							<td class="text-right"><?php echo $tot_new_total; ?></td>
						</tr>
						<tr>
							<td colspan="5">कुल योग (A+B):</td>
							<td class="text-right"><?php echo round($grand_tot_labour_cess,2); ?></td>
							<td class="text-right"><?php echo round($grand_tot_agency_centage,2); ?></td>
							<td class="text-right"><?php echo round($grand_tot_gst,2); ?></td>
							<td></td>
							<td class="text-right"><?php echo round($grand_tot_value,2); ?></td>
						</tr>
						
						<?php } ?>
					</table>
				</div>
			</div>			
		</div>
	</div>
	<!------------------------ second--------------------------------------------------------->
					
	<!------------------------ second--------------------------------------------------------->
					<div class="row">
						
					</div>
				</div>
			</div>
		</div>
	</div>
	</form>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
<?php

include("scripts/settings.php");
//error_reporting(E_ALL);
if(isset($_SESSION['sql5'])){
	 $sql=$_SESSION['sql5'];

}

							$html = '<table border="1">
                                        <thead>
											<tr>
                                            <th>S.No.</th>
                                            <th>Society Name</th>
                                            <th>Mobile Number</th>
											<th>Division Name</th>
											<th>District Name</th>
											<th>Block Name</th>
											<th>फर्श</th>
											<th>पुताई</th>
											<th>छत</th>
                                       		<th>प्लास्टर दिवार</th>
                                       		<th>प्लास्टर छत</th>
                                       		<th>शौचालय</th>
                                       		<th>Estimate</th>
                                        </tr></thead>
                                        <tbody>';
                                           
										$result=execute_query($sql);	
										$i=1;   
										$district = '';
										$division = '';

										$dis_floor = 0;
										$dis_paint = 0;
										$dis_roof = 0;
										$dis_plaster_wall = 0;
										$dis_plaster_roof = 0;
										$dis_washroom = 0;
										$dis_total = 0;

										$div_floor = 0;
										$div_paint = 0;
										$div_roof = 0;
										$div_plaster_wall = 0;
										$div_plaster_roof = 0;
										$div_washroom = 0;
										$div_total = 0;

										$grand_floor = 0;
										$grand_paint = 0;
										$grand_roof = 0;
										$grand_plaster_wall = 0;
										$grand_plaster_roof = 0;
										$grand_washroom = 0;
										$grand_total = 0;
										while($row=mysqli_fetch_array($result)){
											if($district==''){
												$district = $row['col2'];
											}
											if($district != $row['col2']){
												$district = $row['col2'];
												$html .= '<tr>
												<th colspan="6" align="right">District Total:</th>
													<th>'.round($dis_floor,2).'</th>
													<th>'.round($dis_paint,2).'</th>
													<th>'.round($dis_roof,2).'</th>
													<th>'.round($dis_plaster_wall,2).'</th>
													<th>'.round($dis_plaster_roof,2).'</th>
													<th>'.$dis_washroom.'</th>
													<th>'.$dis_total.'</th>
												</tr>';
												
												
												$dis_floor = 0;
												$dis_paint = 0;
												$dis_roof = 0;
												$dis_plaster_wall = 0;
												$dis_plaster_roof = 0;
												$dis_washroom = 0;
												$dis_total = 0;

											}
											if($division==''){
												$division = $row['col1'];
											}
											if($division != $row['col1']){
												$division = $row['col1'];
												
												$html .= '<tr>
												<th colspan="6" align="right">Division Total:</th>
													<th>'.round($div_floor,2).'</th>
													<th>'.round($div_paint,2).'</th>
													<th>'.round($div_roof,2).'</th>
													<th>'.round($div_plaster_wall,2).'</th>
													<th>'.round($div_plaster_roof,2).'</th>
													<th>'.$div_washroom.'</th>
													<th>'.$div_total.'</th>
												</tr>';
												
												
												$div_floor = 0;
												$div_paint = 0;
												$div_roof = 0;
												$div_plaster_wall = 0;
												$div_plaster_roof = 0;
												$div_washroom = 0;
												$div_total = 0;
											}
											
											$estimate = estimate($row['sno']);
												
											$row['floor_length'] = floatval($row['floor_length']);
											$row['floor_width'] = floatval($row['floor_width']);
											$row['wall_length'] = floatval($row['wall_length']);
											$row['wall_width'] = floatval($row['wall_width']);
											$row['paint_length'] = floatval($row['paint_length']);
											$row['paint_width'] = floatval($row['paint_width']);
											$row['roof_length'] = floatval($row['roof_length']);
											$row['roof_width'] = floatval($row['roof_width']);
											$row['washroom_floor'] = floatval($row['washroom_floor']);
											$row['washroom_plaster'] = floatval($row['washroom_plaster']);
											$row['washroom_roof'] = floatval($row['washroom_roof']);
											$row['washroom_seat'] = floatval($row['washroom_seat']);
											$row['washroom_plumbing'] = floatval($row['washroom_plumbing']);
											$row['plaster_wall'] = floatval($row['plaster_wall']);
											$row['plaster_roof'] = floatval($row['plaster_roof']);
												
											$sql_division = 'select * from master_division where sno = "'.$row['col1'].'"';
											$result_division = mysqli_fetch_array(execute_query($sql_division));
											
											$sql_district = 'select * from master_district where sno = "'.$row['col2'].'"';
											$result_district = mysqli_fetch_array(execute_query($sql_district));
											if(!isset($result_district['district_name'])){
												$result_district['district_name'] = '';
												$result_district['sno'] = '';
											}
												
											$sql_tehseel = 'select * from master_tehseel where sno = "'.$row['col5'].'"';
											$result_tehseel = mysqli_fetch_array(execute_query($sql_tehseel));
											if(!isset($result_tehseel['tehseel_name'])){
												$result_tehseel['tehseel_name'] = '';
												$result_tehseel['sno'] = '';
											}
												
											
											$sql_block = 'select * from master_block where sno = "'.$row['col6'].'"';
											$result_block = mysqli_fetch_array(execute_query($sql_block));
											if(!isset($result_block['block_name'])){
												$result_block['block_name'] = '';
												$result_block['sno'] = '';
											}
											$washroom = ($row['washroom_floor']+$row['washroom_plaster']+$row['washroom_roof']+$row['washroom_seat']+$row['washroom_plumbing']);
											
											$html .= '<tr>
											<td>'.$i++.'</td>
                                                <td>'.$row['col4'].'</td>
												<td>'.$row['mobile_number'].'</td>
                                                <td>'.$result_division['division_name'].'</td>
												<td>'.$result_district['district_name'].'</td>
												<td>'.$result_block['block_name'].'</td>
												<td>'.round($estimate['floor_total'],2).'</td>
												<td>'.round($estimate['paint_total'],2).'</td>
												<td>'.round($estimate['roof_total'],2).'</td>
												<td>'.round($estimate['plaster_wall_total'],2).'</td>
												<td>'.round($estimate['plaster_roof_total'],2).'</td>
												<td>'.$washroom.'</td>
												<td>'.$estimate['grand_total_a_b'].'</td>
											</tr>';
											
											$dis_floor += $estimate['floor_total'];
											$dis_paint += $estimate['paint_total'];
											$dis_roof += $estimate['roof_total'];
											$dis_plaster_wall += $estimate['plaster_wall_total'];
											$dis_plaster_roof += $estimate['plaster_roof_total'];
											$dis_washroom += $washroom;
											$dis_total += $estimate['grand_total_a_b'];
											
											$div_floor += $estimate['floor_total'];
											$div_paint += $estimate['paint_total'];
											$div_roof += $estimate['roof_total'];
											$div_plaster_wall += $estimate['plaster_wall_total'];
											$div_plaster_roof += $estimate['plaster_roof_total'];
											$div_washroom += $washroom;
											$div_total += $estimate['grand_total_a_b'];
											
											$grand_floor += $estimate['floor_total'];
											$grand_paint += $estimate['paint_total'];
											$grand_roof += $estimate['roof_total'];
											$grand_plaster_wall += $estimate['plaster_wall_total'];
											$grand_plaster_roof += $estimate['plaster_roof_total'];
											$grand_washroom += $washroom;
											$grand_total += $estimate['grand_total_a_b'];
										}

										$html .= '<tr>
												<th colspan="6" align="right">District Total:</th>
													<th>'.round($dis_floor,2).'</th>
													<th>'.round($dis_paint,2).'</th>
													<th>'.round($dis_roof,2).'</th>
													<th>'.round($dis_plaster_wall,2).'</th>
													<th>'.round($dis_plaster_roof,2).'</th>
													<th>'.$dis_washroom.'</th>
													<th>'.$dis_total.'</th>
												</tr>';


										$html .= '<tr>
												<th colspan="6" align="right">Division Total:</th>
													<th>'.round($div_floor,2).'</th>
													<th>'.round($div_paint,2).'</th>
													<th>'.round($div_roof,2).'</th>
													<th>'.round($div_plaster_wall,2).'</th>
													<th>'.round($div_plaster_roof,2).'</th>
													<th>'.$div_washroom.'</th>
													<th>'.$div_total.'</th>
												</tr>';

										$html .= '<tr>
										<th colspan="6" align="right">Grand Total:</th>
											<th>'.round($grand_floor,2).'</th>
											<th>'.round($grand_paint,2).'</th>
											<th>'.round($grand_roof,2).'</th>
											<th>'.round($grand_plaster_wall,2).'</th>
											<th>'.round($grand_plaster_roof,2).'</th>
											<th>'.$div_washroom.'</th>
											<th>'.$div_total.'</th>
										</tr>';
                                        $html .= '</tbody>
                                    </table>';
				
				
				header("Content-Type:application/xls");
                header("Content-Disposition:attachment;filename=download.xls");
                echo $html;

?>
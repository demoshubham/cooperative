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
                                            <th>Secretary Name</th>
                                            <th>Mobile Number</th>
                                            <th>E-Mail ID</th>
											<th>Division Name</th>
											<th>District Name</th>
											<th>Godown Length</th>
											<th>Godown Width</th>
											<th>Storage Capacity</th>
											<th>Current Year Profit Amount</th>
											<th>Sequential Year Profit/Loss</th>
											<th>Sequential Year Profit/Loss Amount</th>
                                       		</tr></thead>
                                        <tbody>';
                                           
										$result=execute_query($sql);	
										$i=1;   
										while($row=mysqli_fetch_array($result)){
												
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
											
											$html .= '<tr>
											<td>'.$i++.'</td>
                                                <td>'.$row['col4'].'</td>
												<td>'.$row['respondent_name'].'</td>
												<td>'.$row['mobile_number'].'</td>
												<td>'.$row['email_id'].'</td>
                                                <td>'.$result_division['division_name'].'</td>
												<td>'.$result_district['district_name'].'</td>
												<td>'.$row['length'].'</td>
												<td>'.$row['width'].'</td>
												<td>'.$row['storage_capacity'].'</td>
												<td>'.$row['last_year_pl_amount'].'</td>
												<td>'.$row['seq_year_profit_loss'].'</td>
												<td>'.$row['seq_year_pl_amount'].'</td>
											</tr>';
										}

										/*$html .= '<tr>
												<th colspan="12" align="right">District Total:</th>
													<th>'.$dis_total.'</th>
												</tr>';


										$html .= '<tr>
												<th colspan="12" align="right">Division Total:</th>
													<th>'.$div_total.'</th>
												</tr>';

										$html .= '<tr>
										<th colspan="12" align="right">Grand Total:</th>
											<th>'.$div_total.'</th>
										</tr>';*/
                                        $html .= '</tbody>
                                    </table>';
				
				
				header("Content-Type:application/xls");
                header("Content-Disposition:attachment;filename=download.xls");
                echo $html;

?>
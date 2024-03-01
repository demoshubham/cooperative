<?php

include("scripts/settings.php");
//error_reporting(E_ALL);
if(isset($_SESSION['sql5'])){
	 $sql=$_SESSION['sql5'];

}
$result_data = execute_query($sql);
$html ='<table class="table table-hover table-striped">
                                        <thead>
											<tr  class="no-print">
												<td colspan="11" float="left" class="no-print">
												<a href="report_estimate_generated_excel.php"><input type="button" style="margin-top:20px; color:#ffffff;" name="student_ledger" class="form-control btn btn-danger"  style="float: left;" value="Download In Excel"></a></span>
												</td>
											</tr>
                                            <tr>
												<th rowspan="2">S.No.</th>
												<th rowspan="2">Division Name</th>
												<th rowspan="2">District Name</th>
												<th rowspan="2">Repairable</th>
												<th colspan="2">Upto 5 Lakhs</th>
												<th colspan="2">5 to 10 Lakhs</th>
												<th colspan="2">10 to 15 Lakhs</th>
												<th colspan="2">15 to 20 Lakhs</th>
												<th colspan="2">Above 20 Lakhs</th>
											</tr>
                                        	<tr>
                                        		<th>Count</th>
                                        		<th>Amount</th>
                                        		<th>Count</th>
                                        		<th>Amount</th>
												<th>Count</th>
                                        		<th>Amount</th>
												<th>Count</th>
                                        		<th>Amount</th>
												<th>Count</th>
                                        		<th>Amount</th>
                                        	</tr>

                                        </thead>
                                        <tbody>';
                                           
											
										$i=1;
										$col1 = '';
										$total_count = 0;
										$total_count_5 = 0;
										$total_amt_5 = 0;
										$total_count_10 = 0;
										$total_amt_10 = 0;
										$total_count_15 = 0;
										$total_amt_15 = 0;
										$total_count_20 = 0;
										$total_amt_20 = 0;
										$total_count_above = 0;
										$total_amt_above = 0;
										$tot_repairable = 0;
										$gtotal_count = 0;
										$gtotal_count_5 = 0;
										$gtotal_amt_5 = 0;
										$gtotal_count_10 = 0;
										$gtotal_amt_10 = 0;
										$gtotal_count_15 = 0;
										$gtotal_amt_15 = 0;
										$gtotal_count_20 = 0;
										$gtotal_amt_20 = 0;
										$gtotal_count_above = 0;
										$gtotal_amt_above = 0;
										$gtot_repairable = 0;
										while($row = mysqli_fetch_array($result_data)){
											/*$sql = 'select count(*) c, col1, col2 from survey_invoice left join test2 on test2.sno = survey_invoice.society_id where `approval_status` ="4" and col2="'.$row['col2'].'"';
											//echo $sql.'<br>';
											$tot_count = mysqli_fetch_assoc(execute_query($sql));*/
											
											$sql = 'select count(*) c, sum(grand_total_a_b) as total from survey_estimate left join survey_invoice on survey_invoice.sno = survey_id left join test2 on test2.sno = society_id where col2="'.$row['col2'].'" and grand_total_a_b between 1 AND 500000';
											//echo $sql.'<br>';
											$upto_5_lakhs = mysqli_fetch_assoc(execute_query($sql));
											if($col1 == ''){
												$col1 = $row['col1'];
											}
											
											$sql = 'select count(*) c, sum(grand_total_a_b) as total from survey_estimate left join survey_invoice on survey_invoice.sno = survey_id left join test2 on test2.sno = society_id where col2="'.$row['col2'].'" and grand_total_a_b between 500001 AND 1000000';
											//echo $sql.'<br>';
											$upto_10_lakhs = mysqli_fetch_assoc(execute_query($sql));
											if($col1 == ''){
												$col1 = $row['col1'];
											}
											
											$sql = 'select count(*) c, sum(grand_total_a_b) as total from survey_estimate left join survey_invoice on survey_invoice.sno = survey_id left join test2 on test2.sno = society_id where col2="'.$row['col2'].'" and grand_total_a_b between 1000001 AND 1500000';
											//echo $sql.'<br>';
											$upto_15_lakhs = mysqli_fetch_assoc(execute_query($sql));
											if($col1 == ''){
												$col1 = $row['col1'];
											}
											
											$sql = 'select count(*) c, sum(grand_total_a_b) as total from survey_estimate left join survey_invoice on survey_invoice.sno = survey_id left join test2 on test2.sno = society_id where col2="'.$row['col2'].'" and grand_total_a_b between 1500001 AND 2000000';
											//echo $sql.'<br>';
											$upto_20_lakhs = mysqli_fetch_assoc(execute_query($sql));
											if($col1 == ''){
												$col1 = $row['col1'];
											}
											
											$sql = 'select count(*) c, sum(grand_total_a_b) as total from survey_estimate left join survey_invoice on survey_invoice.sno = survey_id left join test2 on test2.sno = society_id where col2="'.$row['col2'].'" and grand_total_a_b > 2000000';
											//echo $sql.'<br>';
											$upto_above_20_lakhs = mysqli_fetch_assoc(execute_query($sql));
											if($col1 == ''){
												$col1 = $row['col1'];
											}
											
											if($col1!=$row['col1']){
												$html .= '<tr>
												<th colspan="3" align="right">Division Total:</th>
												<th>'.$tot_repairable.'</th>
												<th>'.$total_count_5.'</th>
												<th>'.round($total_amt_5, 2).'</th>
												<th>'.$total_count_10.'</th>
												<th>'.round($total_amt_10, 2).'</th>
												<th>'.$total_count_15.'</th>
												<th>'.round($total_amt_15, 2).'</th>
												<th>'.$total_count_20.'</th>
												<th>'.round($total_amt_20, 2).'</th>
												<th>'.$total_count_above.'</th>
												<th>'.round($total_amt_above, 2).'</th>
												<th></th>
												<th></th>
												</tr>';
												$col1 = $row['col1'];
												$total_count = 0;
												$total_count_5 = 0;
												$total_amt_5 = 0;
												$total_count_10 = 0;
												$total_amt_10 = 0;
												$total_count_15 = 0;
												$total_amt_15 = 0;
												$total_count_20 = 0;
												$total_amt_20 = 0;
												$total_count_above = 0;
												$total_amt_above = 0;
												$tot_repairable = 0;

											}
											$tot_repairable+= $row['c'];
											//$total_count += $tot_count['c'];
											$total_count_5 += $upto_5_lakhs['c'];
											$total_count_10 += $upto_10_lakhs['c'];
											$total_count_15 += $upto_15_lakhs['c'];
											$total_count_20 += $upto_20_lakhs['c'];
											$total_count_above += $upto_above_20_lakhs['c'];
											$total_amt_5 += $upto_5_lakhs['total'];
											$total_amt_10 += $upto_10_lakhs['total'];
											$total_amt_15 += $upto_15_lakhs['total'];
											$total_amt_20 += $upto_20_lakhs['total'];
											$total_amt_above += $upto_above_20_lakhs['total'];
											$gtot_repairable+= $row['c'];
											//$gtotal_count += $tot_count['c'];
											$gtotal_count_5 += $upto_5_lakhs['c'];
											$gtotal_count_10 += $upto_10_lakhs['c'];
											$gtotal_count_15 += $upto_15_lakhs['c'];
											$gtotal_count_20 += $upto_20_lakhs['c'];
											$gtotal_count_above += $upto_above_20_lakhs['c'];
											$gtotal_amt_5 += $upto_5_lakhs['total'];
											$gtotal_amt_10 += $upto_10_lakhs['total'];
											$gtotal_amt_15 += $upto_15_lakhs['total'];
											$gtotal_amt_20 += $upto_20_lakhs['total'];
											$gtotal_amt_above += $upto_above_20_lakhs['total'];
											
											
											
										$html .= '
										<tr>
											<td>'.$i++.'</td>
											<td>'.$row['division_name'].'</td>
											<td>'.$row['district_name'].'</td>
											<td>'.$row['c'].'</td>
											<td>'.$upto_5_lakhs['c'].'</td>
											<td>Rs'.round($upto_5_lakhs['total'],2).'</td>
											<td>'.$upto_10_lakhs['c'].'</td>
											<td>Rs.'.round($upto_10_lakhs['total'],2).'</td>
											<td>'.$upto_15_lakhs['c'].'</td>
											<td>Rs.'.round($upto_15_lakhs['total'],2).'</td>
											<td>'.$upto_20_lakhs['c'].'</td>
											<td>Rs.'.round($upto_20_lakhs['total'],2).'</td>
											<td>'.$upto_above_20_lakhs['c'].'</td>
											<td>Rs.'.round($upto_above_20_lakhs['total'],2).'</td>
										</tr>';
										}
										$html .= '<tr>
												<th colspan="3" align="right">Division Total:</th>
												<th>'.$tot_repairable.'</th>
												<th>'.$total_count_5.'</th>
												<th>'.round($total_amt_5, 2).'</th>
												<th>'.$total_count_10.'</th>
												<th>'.round($total_amt_10, 2).'</th>
												<th>'.$total_count_15.'</th>
												<th>'.round($total_amt_15, 2).'</th>
												<th>'.$total_count_20.'</th>
												<th>'.round($total_amt_20, 2).'</th>
												<th>'.$total_count_above.'</th>
												<th>'.round($total_amt_above, 2).'</th>
												<th></th>
												<th></th>
												</tr>';
										$html .= '<tr>
												<th colspan="3" align="right">Grand Total:</th>
												<th>'.$gtot_repairable.'</th>
												<th>'.$gtotal_count_5.'</th>
												<th>'.round($gtotal_amt_5, 2).'</th>
												<th>'.$gtotal_count_10.'</th>
												<th>'.round($gtotal_amt_10, 2).'</th>
												<th>'.$gtotal_count_15.'</th>
												<th>'.round($gtotal_amt_15, 2).'</th>
												<th>'.$gtotal_count_20.'</th>
												<th>'.round($gtotal_amt_20, 2).'</th>
												<th>'.$gtotal_count_above.'</th>
												<th>'.round($gtotal_amt_above, 2).'</th>
												<th></th>
												<th></th>
												</tr></tbody></table>';
				
				
				header("Content-Type:application/xls");
                header("Content-Disposition:attachment;filename=download.xls");
                echo $html;

?>
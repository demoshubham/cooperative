<?php
include("scripts/settings.php");
//error_reporting(E_ALL);
if(isset($_SESSION['sql5'])){
	 $sql=$_SESSION['sql5'];

}

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();


$sheet->setCellValue('A1', 'S.No.');
$sheet->setCellValue('B1', 'Society Name');
$sheet->setCellValue('C1', 'District Name');
$sheet->setCellValue('D1', 'Tehseel Name');
$sheet->setCellValue('E1', 'Block Name');

$i=2;
$sno=1;
$result=execute_query($sql);	 
while($row=mysqli_fetch_array($result)){
	$sql_district = 'select * from master_district where sno = "'.$row['col2'].'"';
	$result_district = mysqli_fetch_array(execute_query($sql_district));

	$sql_tehseel = 'select * from master_tehseel where sno = "'.$row['col5'].'"';
	$result_tehseel = mysqli_fetch_array(execute_query($sql_tehseel));

	$sql_block = 'select * from master_block where sno = "'.$row['col6'].'"';
	$result_block = mysqli_fetch_array(execute_query($sql_block));
	
	$sheet->setCellValue('A'.$i, $sno++);
	$sheet->setCellValue('B'.$i, $row['col4']);
	$sheet->setCellValue('C'.$i, $result_district['district_name']);
	$sheet->setCellValue('D'.$i, $result_tehseel['tehseel_name']);
	$sheet->setCellValue('E'.$i, $result_block['block_name']);
	$i++;

}
foreach ($sheet->getColumnIterator() as $column) {
   $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
}

$fileName = 'Test.xlsx';

$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
$writer->save('php://output');


/*       $html ='<table>
        	<thead>
            	<tr style="background:#333; color:#FFF; text-align:center; font-size:13px;">
					<th>S.No.</th>
					<th>Society Name</th>
					<th>Division Name</th>
					<th>District Name</th>
					<th>Tehseel Name</th>
					<th>Block Name</th>
					<th>ADO/Incharge Name</th>
					<th>ADO/Incharge Mobile</th>
					<th>ADCO Name</th>
					<th>ADCO Mobile</th>
					<th>AR &amp; AC  Name</th>
					<th>AR &amp; AC Mobile</th>	
           	    </tr>
            </thead>'; 
              
						$result=execute_query($sql);	
						$i=1;      
						while($row=mysqli_fetch_array($result)){
							if($i%2==0){
							$col = '#CCC';
							}
							else{
								$col = '#EEE';
							}
							if($i%10==0){
								$css = 'page-break-after:always;';
							}
							else{
								$css = '';
							}
							$sql_district = 'select * from master_district where sno = "'.$row['col2'].'"';
											//echo $sql_district.'</br>';
							$result_district = mysqli_fetch_array(execute_query($sql_district));
											
											
											
							$sql_division = 'select * from master_division where sno = "'.$row['col1'].'"';
							$result_division = mysqli_fetch_array(execute_query($sql_division));
											
							$sql_tehseel = 'select * from master_tehseel where sno = "'.$row['col5'].'"';
							$result_tehseel = mysqli_fetch_array(execute_query($sql_tehseel));
											
							$sql_block = 'select * from master_block where sno = "'.$row['col6'].'"';
							$result_block = mysqli_fetch_array(execute_query($sql_block));
							
							$sql_ado = 'select ado_name, mobile_number from ado_details left join ado on ado.sno = ado_details.ado_id where block_id="'.$result_block['sno'].'"';
							$result_ado = mysqli_fetch_array(execute_query($sql_ado));

							$sql_adco = 'select adco_name, mobile_number from adco_details left join adco on adco.sno = adco_details.adco_id where tehseel_id="'.$result_tehseel['sno'].'"';
							$result_adco = mysqli_fetch_array(execute_query($sql_adco));
							
							$sql_ar = 'select ar_name, mobile_number from ar_details left join ar on ar.sno = ar_details.ar_id where district_id="'.$result_district['sno'].'"';
							$result_ar = mysqli_fetch_array(execute_query($sql_ar));

							$html .= '<tr style="background:'.$col.';border:1px solid black">
								<td>'.$i++.'</td>
								<td>'.$row['col4'].'</td>
								<td>'. $result_division['division_name'].'</td>
								<td>'. $result_district['district_name'].'</td>
								<td>'. $result_tehseel['tehseel_name'].'</td>
								<td>'. $result_block['block_name'].'</td>
								<td>'.$result_ado['ado_name'].'</td>
								<td>'.$result_ado['mobile_number'].'</td>
								<td>'.$result_adco['adco_name'].'</td>
								<td>'.$result_adco['mobile_number'].'</td>
								<td>'.$result_ar['ar_name'].'</td>
								<td>'.$result_ar['mobile_number'].'</td>
							</tr>';
						}
					

						
			    
				$html .='</table>';
				
				
				
				header("Content-Type:application/xls");
                header("Content-Disposition:attachment;filename=download.xls");
                echo $html; */


?>
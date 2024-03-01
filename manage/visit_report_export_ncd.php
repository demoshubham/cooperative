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

$headers = array("S.No.", "1(a). Name of Cooperative Society", "1(b).Registration Authority", "1(c). Registration Number", " 1(d). Date of Registration", "2(a). Location of Registered Office (Urban/Rural)", "2(b). State / UT", "2(c). District /2(c). Category of Urban Local Body ", "2(d). Block /2(d). Urban Local Body ", "2(e). Gram Panchayat /2(e). Locality or Ward ", "2(g). Village ", "2(f). Pin Code ", "3(a) Name", "3(b) Designation", "3(b)(i) Designation Other", "3(c). Mobile Number", "3(d). Landline Number", "3(e). Email ID", "4(a). Sector of Operation (Credit/Non-Credit)", "PACS/FSS/LAMPS/Dairy/Fishery", "5(a). Tier of the Cooperative Society", " 5(b). Area of operation (Urban/Rural/Both) ", "5(c)(i). District ", "5(c)(ii). Block /Category of Urban Local Body  (Municipality/Cantonment/etc.)", "5(c)(iii). Gram Panchayat/Urban Local Body ", "5(c)(iv). Village/Locality or Ward  ", "5(d). Whether Area of Operation (Village, Panchayat, Block or Mandal or Town, Taluka/District) is adjacent to or includes water body ", "5(f). Whether the cooperative society is affiliated to Union/Federation", "6. Present Functional Status (Functional/Non-Functional/Under Liquidation)", "7. Number of Members of the Cooperative Society", "8(a). whether Financial Audit has done?", "8(b). Year of Latest Audit Completed ", "8(c). Category of Audit", "9(a). Whether the Cooperative Society is profit making", "9(b). Net Profit of the Cooperative Society", "9(c). Whether the dividend paid by the Cooperative Society", "9(d). Dividend Rate Paid by the Cooperative Society (in %)", "10(a). Whether the co-operative society has an office building ", "10(b). Type of Office Building", "10(c). Whether the co-operative society has land ", " 10(d). Land Available with the Cooperative ", "11(a). Total Outstanding Loan extended to Member(In Rs)", "11(b). Revenue (Other than interest) from Non-Credit Activities (In Rs.)", "11(c). Fertilizer Distribution", "11(d). Pesticide Distribution", "11(e). Seed Distribution", "11(f). Fair Price Shop License", "11(g). Procurement of Foodgrains", "11(h). Hiring of Agricultural Implements", "  11(i). Dry Storage Facilities", "11(j). Capacity of Dry Storage Infrastructure (In MT) ", "11(k). Cold Storage Facilities", "11(l). Capacity of Cold Storage Infrastructure (In MT) ", "11(m). Milk Collection Unit Facility", "11(n). Annual Milk Collection by Cooperative Society (In Liters) ", "11(o). Wheather Cooperative Society is involved in fish catch", "11(p). Annual Fish Catch by Cooperative Society (In kg) ", "11(q). Food Processing Facilitie", "11(s). Other Facilities Provided (Please Specify)");

$sheet->fromArray([$headers], NULL, 'A1');

/*$sheet->setCellValue('A1', 'S.No.');
$sheet->setCellValue('B1', 'Society Name');
$sheet->setCellValue('C1', 'District Name');
$sheet->setCellValue('D1', 'Tehseel Name');
$sheet->setCellValue('E1', 'Block Name');*/

$i=2;
$sno=1;
$result=execute_query($sql);	 
while($row=mysqli_fetch_array($result)){
	
	$sql = 'select * from survey_invoice_sec_2_1 where survey_id="'.$row['sno'].'"';
	$result_sec_2_1 = execute_query($sql);
	if(mysqli_num_rows($result_sec_2_1)!=0){
		$row_sec_2_1 = mysqli_fetch_assoc($result_sec_2_1);
		if($row_sec_2_1['financial_audit_year']=='old'){
			$audit_status_year = '';
			$row_sec_2_1['financial_audit_year'] = 'No';
		}
		else{
			$audit_status_year = $row_sec_2_1['financial_audit_year'];
			$row_sec_2_1['financial_audit_year'] = 'Yes';
		}
		if($row_sec_2_1['last_year_profit_loss']=='profit'){
			$row_sec_2_1['last_year_profit_loss'] = 'Yes';
		}
		else{
			$row_sec_2_1['last_year_profit_loss'] = 'No';
		}
		if($row['society_building_ownership']=='rent' || $row['society_building_ownership']=='own'){
			$type_of_building = $row['society_building_ownership'];
			$row['society_building_ownership'] = 'Yes';
		}
		else{
			$type_of_building = '';
			$row['society_building_ownership'] = 'No';
		}
	}
	else{
		$row_sec_2_1['financial_audit_year'] = 'No';
		$row_sec_2_1['last_year_profit_loss'] = 'No';
		$audit_latest_year = '';
		$type_of_building = '';
		$row['society_building_ownership'] = 'No';
	}
	
	$sql = 'select *,  abs( total_area ) AS absolute_area from survey_invoice_sec_3_1 where survey_id="'.$row['sno'].'"';
	$result_sec_3_1 = execute_query($sql);
	if(mysqli_num_rows($result_sec_3_1)!=0){
		$row_sec_3_1 = mysqli_fetch_assoc($result_sec_3_1);
		if($row_sec_3_1['absolute_area']=='0'){
			$society_land = 'No';
		}
		else{
			$society_land = 'Yes';
		}
		$land_area = $row_sec_3_1['absolute_area'];
	}
	else{
		$society_land = 'No';
		$land_area = '';
	}
	
	
	$sql_district = 'select * from master_district where sno = "'.$row['col2'].'"';
	$result_district = mysqli_fetch_array(execute_query($sql_district));

	$sql_tehseel = 'select * from master_tehseel where sno = "'.$row['col5'].'"';
	$result_tehseel = mysqli_fetch_array(execute_query($sql_tehseel));

	$sql_block = 'select * from master_block where sno = "'.$row['col6'].'"';
	$result_block = mysqli_fetch_array(execute_query($sql_block));
	
	$i = $i;
	$name_of_society = $row['col4'];
	$registration_number = $row['society_registration_no'];
	$date_registration = $row['society_registration_date'];
	$state_ut = 'Uttar Pradesh';
	$district = $result_district['district_name'];
	$block = $result_block['block_name'];
	$sachiv_name = $row['respondent_name'];
	$sachiv_designation = $row['respondent_designation'];
	$mobile_number = $row['mobile_number'];
	$email_id = $row['email_id'];
	$type = "PACS";
	$credit = "Credit";
	$tot_members = (float)$row['active_members']+(float)$row['inactive_members'];
	$audit_status = $row_sec_2_1['financial_audit_year'];
	$audit_status_year = $audit_status_year;
	$profit_yes_no = $row_sec_2_1['last_year_profit_loss'];
	$net_profit = $row_sec_2_1['last_year_pl_amount'];
	$building = $row['society_building_ownership'];
	$type_of_building = $type_of_building;
	$society_land = $society_land;
	$land_area = $land_area;	
	
	$data = array($i, $name_of_society, "", $registration_number, $date_registration, "", $state_ut, $district, $block, "", "", "", $sachiv_name, $sachiv_designation, "",$mobile_number, "", $email_id, "", "", "", "", "", "", "", "", "", "No", "", $tot_members, $audit_status, $audit_status_year, "", $profit_yes_no, $net_profit, "", "", $building, $type_of_building, $society_land, $land_area, "", "", "Yes/No", "Yes/No", "Yes/No", "Yes/No", "Yes/No", "Yes/No", "Yes/No", "", "Yes/No", "", "Yes/No", "", "Yes/No", "", "Yes/No", "");
	$sheet->fromArray([$data], NULL, 'A'.$i);
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

?>
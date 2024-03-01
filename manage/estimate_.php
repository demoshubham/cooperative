<?php
include("scripts/settings.php");

$sql = 'SELECT survey_invoice.sno as sno, col4 as society_name, division_name, district_name, tehseel_name, block_name FROM survey_invoice left join `test2` on `test2`.sno = society_id left join master_division on master_division.sno = col1 left join master_district on master_district.sno = col2 left join master_tehseel on master_tehseel.sno = col5 left join master_block on master_block.sno = col6 where survey_invoice.sno='.$_GET['exdid'];
$invoice_data = mysqli_fetch_assoc(execute_query($sql));


page_header_start();
page_header_end();
?>
	<div class="row m-4" >
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12">
					<div class="p-1 bg-primary text-white"><h3 align="center" >सर्वेक्षण प्रपत्र</h3></div>
					<hr>
					<div class="row">
						<div class="col-md-3">
							<label for="inputPassword" class="col-form-label">समिति का नाम : </label>
							<?php echo $invoice_data['society_name']; ?>
						</div>
						<div class="col-md-3">
							<label for="inputPassword" class="col-form-label">मण्डल : </label>
							<?php echo $invoice_data['division_name']; ?>
						</div>
						<div class="col-md-3">
							<label for="inputPassword" class="col-form-label">जिला : </label>
							<?php echo $invoice_data['district_name']; ?>
						</div>
						<div class="col-md-3">
							<label for="inputPassword" class="col-form-label">ब्लाक एवम तहसील : </label>
							<?php echo $invoice_data['block_name'].'-'.$invoice_data['tehseel_name']; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="row">					
				<div class="col-md-12">
					<div class="card">
						<div class="card-body">
							<div class="p-1 bg-primary text-white"><h3 align="center" >6.मरम्मत योग्य दर्ज विवरण के आधार पर अनुमानित लागत</h3></div>
							<?php
							$sql = 'select * from survey_invoice_sec_5 where survey_id="'.$invoice_data['sno'].'"';
							//echo $sql;
							$result_sec_5 = execute_query($sql);
							if(mysqli_num_rows($result_sec_5)!=0){
								$row_sec_5 = mysqli_fetch_assoc($result_sec_5);
							}
							?>
							<table class="table table-striped table-bordered">
								<thead>
									<tr class="p-3 mb-2 bg-success text-white" align="center" >
										<th scope="col">विवरण </th>
										<th scope="col">लंबाई (मीटर में)</th>
										<th scope="col">चौड़ाई (मीटर में)</th>
										<th scope="col">क्षेत्रफल</th>
										<th scope="col">दर</th>
										<th scope="col">कुल मुल्य</th>
									</tr>
								</thead>
								<tbody align="center">
									<tr >
									  <th scope="row">फर्श </th>
									  <th><?php echo $row_sec_5['floor_length']; ?></th>
									  <th><?php echo $row_sec_5['floor_width']; ?></th>
									  <th><?php echo ($row_sec_5['floor_length']*$row_sec_5['floor_width']); ?></th>
									  <th></th>
									  <th></th>

									</tr>
									<tr>
									  <th scope="row">दीवार </th>
									  <th><?php echo $row_sec_5['wall_length']; ?></th>
									  <th><?php echo $row_sec_5['wall_width']; ?></th>
									  <th><?php echo ($row_sec_5['wall_length']*$row_sec_5['wall_width']); ?></th>
									  <th></th>
									  <th></th>
									</tr>
									<tr>
									  <th scope="row">पुताई </th>
									  <th><?php echo $row_sec_5['paint_length']; ?></th>
									  <th><?php echo $row_sec_5['paint_width']; ?></th>
									  <th><?php echo ($row_sec_5['paint_length']*$row_sec_5['paint_width']); ?></th>
									  <th></th>
									  <th></th>

									</tr>
									<tr>
									  <th scope="row">छत </th>
									  <th><?php echo $row_sec_5['roof_length']; ?></th>
									  <th><?php echo $row_sec_5['roof_width']; ?></th>
									  <th><?php echo ($row_sec_5['roof_length']*$row_sec_5['roof_width']); ?></th>
									  <th></th>
									  <th></th>

									</tr>
									<tr>
									  <th scope="row">  प्लास्टर  </th>
									  <th>दिवार (क्षेत्रफल) : <?php echo $row_sec_5['plaster_wall']; ?></th>
									  <th>छत (क्षेत्रफल) : <?php echo $row_sec_5['plaster_roof']; ?></th>
									  <th>कुल (क्षेत्रफल) : <?php echo ($row_sec_5['plaster_wall']+$row_sec_5['plaster_roof']); ?></th>
									  <th></th>
									  <th></th>

									</tr>
									<tr>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<th>योग : </th>
										<th></th>
									</tr>
								</tbody>
							</table>
							<div class="row">
								<div class="col-sm-12">
									<h5>शौचालय कि मरम्मत</h5>
									<div class="row">
										<div class="col-sm-4">
											<labe>फर्श : </labe>
											<?php echo $row_sec_5['washroom_floor']; ?>
										</div>
										<div class="col-sm-4">
											<labe>प्लासटर : </labe>
											<?php echo $row_sec_5['washroom_plaster']; ?>
										</div>
										<div class="col-sm-4">
											<labe>छत : </labe>
											<?php echo $row_sec_5['washroom_roof']; ?>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-4">
											<labe>सीट : </labe>
											<?php echo $row_sec_5['washroom_seat']; ?>
										</div>
										<div class="col-sm-4">
											<labe>प्लम्बिंग : </labe>
											<?php echo $row_sec_5['washroom_plumbing']; ?>
										</div>
										<div class="col-sm-4">
											<labe>अन्य : </labe>
											<?php echo $row_sec_5['others']; ?>
										</div>
									</div>
								</div>
							</div>	
						</div>	
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-body">
							<div class="p-3 mb-2 bg-primary text-white" align="center"><h3>अतिरिक्त निर्माण की आवश्यक्ता </h3></div>
							<table class="table table-striped table-bordered ">
								<thead>
									<tr class="p-3 mb-2 bg-success text-white" align="center">
									  <th scope="col">विवरण</th>
									  <th scope="col">लंबाई (मीटर में)</th>
									  <th scope="col">चौड़ाई (मीटर में)</th>
									  <th scope="col">क्षेत्रफल</th>
									  <th scope="col">दर</th>
									  <th scope="col">कुल मुल्य</th>
									</tr>
								</thead>
								<tbody align="center">
									<tr class="align="center">
									  <th scope="row">गोदाम </th>
									  <th></th>
									  <th></th>
									  <th></th>
									  <th></th>
									  <th></th>
									  
									</tr>
									<tr class="align="center">
									  <th scope="row">बाथरूम </th>
									  <th></th>
									  <th></th>
									  <th></th>
									  <th></th>
									  <th></th>
									</tr>
									<tr class="align="center">
									  <th scope="row">शोरूम </th>
									  <th></th>
									  <th></th>
									  <th></th>
									  <th></th>
									  <th></th>
									  
									</tr>
									<tr class="align="center">
									  <th scope="row">बाउंड्री वाल </th>
									  <th></th>
									  <th></th>
									  <th></th>
									  <th></th>
									  <th></th>
									  
									</tr>
									<tr class="align="center">
									  <th scope="row"> मल्टीपरपस हाल </th>
									  <th></th>
									  <th></th>
									  <th></th>
									  <th></th>
									  <th></th>
									  
									</tr>
								
								</tbody>
							</table>
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
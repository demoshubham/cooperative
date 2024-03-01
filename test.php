<?php
set_time_limit(0);
date_default_timezone_set("Asia/Kolkata");
$db = mysqli_connect("p:localhost", "cloudsdj", "C3o6D4s1@741#", "cloudsdj_upcod");
if(!$db){
	die("Error 1 : Contact Administrator.");
}
mysqli_query($db, 'SET character_set_results=utf8'); 
mysqli_query($db, 'SET names=utf8'); 
mysqli_query($db, 'SET character_set_client=utf8'); 
mysqli_query($db, 'SET character_set_connection=utf8'); 
mysqli_query($db, 'SET character_set_results=utf8'); 
mysqli_query($db, 'SET collation_connection=utf8_general_ci'); 

function execute_query($query){
	global $db;
	$result = mysqli_query($db, $query);
	return $result;
}

//update_division();
//update_district();
//create_tehseel();
//create_block();
list_society();


function list_society(){
    $sql = 'select * from sammelen_society_types';
    $result = execute_query($sql);
    echo '<table>';
    while($row = mysqli_fetch_assoc($result)){
        echo '<tr>';
        foreach($row as $k=>$v){
            echo '<td>'.$v.'</td>';
        }
        echo '</tr>';
    }
}

function update_division(){
	$sql = 'select * from test2 group by col1';
	$result = execute_query($sql);
	while($row = mysqli_fetch_assoc($result)){
		$sql = 'select * from master_division where division_name="'.$row['col1'].'"';
		$result_district = execute_query($sql);
		$district = mysqli_fetch_assoc($result_district);
		if($row['col1']!='DivisionCodeText'){
			$sql = 'update test2 set col1="'.$district['sno'].'" where col1="'.$row['col1'].'"';
			//echo $sql.'<br>';
			execute_query($sql);
		}
	}
	echo 'Division<br/>';
}


function update_district(){
	$sql = 'select * from test2 group by col2';
	$result = execute_query($sql);
	while($row = mysqli_fetch_assoc($result)){
		if($row['col1']!='DivisionCodeText'){
			$sql = 'select * from master_district where district_name="'.$row['col2'].'"';
			$result_district = execute_query($sql);
			if(mysqli_num_rows($result_district)==0){
				//echo '<h4>'.$row['col2'].'</h4>';
			}
			$district = mysqli_fetch_assoc($result_district);

			$sql = 'update test2 set col2="'.$district['sno'].'" where col2="'.$row['col2'].'"';
			execute_query($sql);
			//echo $sql.'<br>';
		}
	}
	echo 'District<br/>';
}

function create_block(){
	global $db;
	$sql = 'SELECT * FROM `test2` where col1!="DivisionCodeText" group by col6, col5, col2';
	$result = execute_query($sql);
	while($row = mysqli_fetch_assoc($result)){
		$sql = 'insert into master_block (block_name, tehseel_id, district_id) values("'.$row['col6'].'", "'.$row['col5'].'", "'.$row['col2'].'")';
		//echo $sql.'<br>';
		execute_query($sql);
		$id = mysqli_insert_id($db);
		
		$sql = 'update test2 set col6="'.$id.'" where col6="'.$row['col6'].'" and col5="'.$row['col5'].'" and col2="'.$row['col2'].'"';
		execute_query($sql);
		//echo $sql.'<br>';
	}
	echo 'Block<br/>';
}

function create_tehseel(){
	global $db;
	$sql = 'select * from test2 group by col5, col2';
	$result = execute_query($sql);
	while($row = mysqli_fetch_assoc($result)){
		if($row['col1']!='DivisionCodeText'){
			$sql = 'insert into master_tehseel (tehseel_name, district_id) values("'.$row['col5'].'", "'.$row['col2'].'")';
			//echo $sql.'<br>';
			execute_query($sql);
			$id = mysqli_insert_id($db);

			$sql = 'update test2 set col5="'.$id.'" where col5="'.$row['col5'].'" and col2="'.$row['col2'].'"';
			execute_query($sql);
			//echo $sql.'<br>';
		}
	}
	echo 'Tehseel<br/>';
}


/*


<!DOCTYPE html>
<input type="text" id="side1">
<input type="text" id="side2">
<input type="text" id="side3">
<input type="text" id="side4">
<input type="text" id="side5">
<button type="button" onClick="draw()">Draw</button>
<canvas id="c" />

<script>
function draw(){

	var ctx = document.getElementById('c').getContext('2d');
	var a = document.getElementById('side1').value();
	var b = document.getElementById('side2').value();
	var c = document.getElementById('side3').value();
	var d = document.getElementById('side4').value();
	var e = document.getElementById('side5').value();
	ctx.fillStyle = '#f00';
	ctx.beginPath();
	ctx.moveTo(0, 0);
	ctx.lineTo(a, 0);
	ctx.lineTo(0, b);
	ctx.lineTo(c, 0);
	ctx.lineTo(d, 0);
	ctx.lineTo(e, 0);
	ctx.closePath();
	ctx.fill();

}
</script>


<script>
 
// Javascript implementation of the approach
 
 
// Function that returns true if it is possible
// to form a polygon with the given sides
function isPossible( a, n)
{
 
    // Sum stores the sum of all the sides
    // and maxS stores the length of
    // the largest side
    let sum = 0, maxS = 0;
    for (let i = 0; i < n; i++) {
        sum += a[i];
        maxS = Math.max(a[i], maxS);
    }
 
    // If the length of the largest side
    // is less than the sum of the
    // other remaining sides
    if ((sum - maxS) > maxS)
        return true;
 
    return false;
}
 
    // Driver Code
     
    let a = [ 2, 3, 4 ];
    let n = a.length;
 
    if (isPossible(a, n))
        document.write("Yes");
    else
        document.write("No");
     
     
</script>*/

?>
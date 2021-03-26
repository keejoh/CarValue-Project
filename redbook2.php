<?php session_start();

$userIDF =  $_SESSION['user_id'];
?>
<!DOCTYPE html>

<html>
<head>
<meta charset = "utf-8" />
<title>RedBook</title>
<style type="text/css" media="screen">@import "layout.css";</style>

</head>
<body>
<div id="Header">Get Your Red Book Value </div>
<h1>Tell me which car you own</h1>
<?php

require_once ('config.php');
$page_title = 'Redbook';
if (isset($_POST['submit2'])){
$displayform2=TRUE;
$model = $_POST['modelslist'];
$submit2=isset($_POST['submit2']);
$_SESSION['model'] = $model;
$_SESSION['mysubmit'] = $submit2;
}
if (isset($_SESSION['mysubmit'])) {


if($displayform2==TRUE){
	if($model=='Select'){
		echo "You must select a model";
		
	}

	if(($_SESSION['model']!='Select' and !empty($_SESSION['model']))){
		$_SESSION['displayFirstPart']=FALSE;
			require_once (MYSQLI_CONNECT);
			$query = "SELECT cars_table.dealer_price, cars_table.cert_price, cars_table.private_price, cars_table.car_id FROM cars_table WHERE cars_table.car_make = '" . $_SESSION['make'] . "' AND cars_table.car_model = '" . $_SESSION['model'] . "' AND cars_table.car_year = '" . $_SESSION['year'] . "'";
			echo $_SESSION['year']. " ". $_SESSION['make'] . " " . $_SESSION['model'];


			if ( !( $result = $dbConnection->query($query))) {
				trigger_error("Query: $query\n<br />MySQL Error: " . $dbConnection->error);
			
			} 
			else {
				$row = $result->fetch_array(MYSQLI_NUM);
				$_SESSION['dealerPrice'] = $row[0];
				$_SESSION['certPrice'] = $row[1];
				$_SESSION['privatePrice'] = $row[2];
				$_SESSION['carID'] = $row[3];
				


		  
		
			}

			$insertQuery = "INSERT INTO users_cars_table (user_id, car_id) VALUES ('" . $userIDF . "', '" . $_SESSION['carID'] . "')"; 
			if ( !( $result = $dbConnection->query($insertQuery))) {
				trigger_error("Query: $insertQuery\n<br />MySQL Error: " . $dbConnection->error);
			
			} 
			echo 
			
			'<form action="redbook3.php" method="post" id="choosingoptions">
            <fieldset>
                <legend>Select Additional Options: </legend>
					<input type="checkbox" name="option[]" value="Power Windows" <?php echo (!empty($options_arr) && in_array("Power Windows", $options_arr))  ? "checked" : "";?> Power Windows <br />
					<input type="checkbox" name="option[]" value="Heated Seats" <?php echo (!empty($options_arr) && in_array("Heated Seats", $options_arr)) ? "checked" : "";?> Heated Seats <br />
					<input type="checkbox" name="option[]" value="Heated Mirrors" <?php echo (!empty($options_arr) && in_array("Heated Mirrors", $options_arr)) ? "checked" : "";?> Heated Mirrors <br />
					<input type="checkbox" name="option[]" value="Sun Roof" <?php echo (!empty($options_arr) && in_array("Sun Roof", $options_arr)) ? "checked" : "";?> Sun Roof <br />
					<input type="checkbox" name="option[]" value="Remote Start" <?php echo (!empty($options_arr) && in_array("Remote Start", $options_arr)) ? "checked" : "";?> Remote Start <br />
			</fieldset>
				<label>Select the condition of your vehicle</label>
				<select  name="conditionslist" >
					<option value="Select">Select</option>
					<option value="Fair">Fair</option>
					<option value="Good">Good</option>
					<option value="Excellent">Excellent</option>
				</select>
        <div class="mySubmit">
                        <input type="submit" name="submit3" value="Submit" style="margin-right:5px" /><input type="reset" name="reset" value="reset"/>
                    </div>
                    </form>';
			
	}
}
}
if(isset(  $_POST['submit'])){
	$displayform=TRUE;
	$make = $_POST['makelist'];
	$year = $_POST['yearlist'];
	$submitted=isset($_POST['submit']);
	$_SESSION['make'] = $make;
	$_SESSION['year'] = $year;
	$_SESSION['submit1']=$submitted;
	$_SESSION['displayFirstPart']=$displayform;
}
if (isset($_SESSION['submit1'])) {


	if($_SESSION['make']=='Select' or $_SESSION['year']=='Select' ){
	$_SESSION['error']=TRUE;
	header("Location:redbook.php");
	exit;
	}
if($_SESSION['displayFirstPart']==TRUE){
	if($_SESSION['make']=='Chevrolet' and $_SESSION['year'] !='Select'){
		echo
		'<form action="redbook2.php" method="post" id="choosingcar">
			<label for="model">Choose a Model</label>
				<select id="models" name="modelslist" form="choosingcar">
					<option value="Select">Select</option>
					<option value="Camaro">Camaro</option>
					<option value="Equinox">Equinox</option>
				</select>
					<div class="mySubmit">
						<input type="submit" name="submit2" value="Submit" style="margin-right:5px" /><input type="reset" name="reset" value="reset"/>
					</div>
		</form>';
	}
	if($_SESSION['make']=='Ford'and $_SESSION['year'] !='Select'){
		echo
		'<form action="redbook2.php" method="post" id="choosingcar">
			<label for="model">Choose a Model</label>
				<select id="models" name="modelslist" form="choosingcar">
					<option value="Select">Select</option>
					<option value="F-150">F-150</option>
					<option value="Mustang">Mustang</option>
				</select>
					<div class="mySubmit">
						<input type="submit" name="submit2" value="Submit" style="margin-right:5px" /><input type="reset" name="reset" value="reset"/>
					</div>
		</form>';
	}
	if($_SESSION['make']=='Dodge'and $_SESSION['year'] !='Select'){
		echo
		'<form action="redbook2.php" method="post" id="choosingcar">
			<label for="model">Choose a Model</label>
				<select id="models" name="modelslist" form="choosingcar">
					<option value="Select">Select</option>
					<option value="Charger">Charger</option>
					<option value="Durango">Durango</option>
				</select>
					<div class="mySubmit">
						<input type="submit" name="submit2" value="Submit" style="margin-right:5px" /><input type="reset" name="reset" value="reset"/>
					</div>
		</form>';
	}
	}
}



?>
</body>
<?php // Include the HTML footer.

include ('footer.html');
?>
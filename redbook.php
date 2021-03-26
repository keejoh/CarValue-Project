<?php session_start();
require_once ('config.php');
require_once (MYSQLI_CONNECT);
$_SESSION['model']="";
$_SESSION['make']="";
$_SESSION['year']="";

$showHistoryQuery ="SELECT cars_table.* FROM cars_table, users_cars_table, users_table WHERE cars_table.car_id = users_cars_table.car_id AND users_table.user_id = users_cars_table.user_id AND users_cars_table.user_id = '" . $_SESSION['user_id'] . "'";
				if ( !( $result = $dbConnection->query($showHistoryQuery) ) ) {
					print( "<p>Could not execute  query!</p>" );
					die( $dbConnection->error . "</body></html>" );
				}
				else {
					echo "The cars you have valued are as follows: <br/>";
					while ( $row = $result->fetch_array(MYSQLI_NUM) ) {
						
						echo "Make: " . $row[1] . " Model: " . $row[2] . " Year: " . $row[3]. " Dealer Price " . $row[4] . " Certified Price " . $row[5] . " Private Price  " . $row[6] . "</br>";
						
					}
				}
?>
<!DOCTYPE html>

<html>
<head>
<meta charset = "utf-8" />
<title>RedBook</title>
<style type="text/css" media="screen">@import "layout.css";</style>

</head>
<body>
<?php 
if($_SESSION['error']==TRUE){
	$_SESSION['myError']="You must enter a year and make!";
	echo $_SESSION['myError'] ;

	}
?>

<div id="Header">Get Your Red Book Value </div>
<h1>Tell me which car you own</h1>
<p> Select the Year and Make of your car (Submit then you will be able to select the Model).</p>
<form action="redbook2.php" method="post" >
<?php echo $error; 
require_once ('config.php');
$page_title = 'RedBook';

?>

	<label for="year">Choose a Year</label>
		<select id="years" name="yearlist" >
			<option value="Select">Select</option>
			<option value="2000">2000</option>
			<option value="2005">2005</option>
			<option value="2010">2010</option>
			<option value="2015">2015</option>
			<option value="2020">2020</option>
		</select>

	<label for="make">Choose a Make</label>
		<select id="makes" name="makelist">
			<option value="Select">Select</option>
			<option value="Chevrolet">Chevy</option>
			<option value="Ford">Ford</option>
			<option value="Dodge">Dodge</option>
		</select>
	<div class="mySubmit">
		<input type="submit" name="submit" value="Submit" style="margin-right:5px" /><input type="reset" name="reset" value="reset"/>
	</div>
</form>

</body>
<?php // Include the HTML footer.

include ('footer.html');
?>

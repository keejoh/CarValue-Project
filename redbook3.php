<?php
session_start();

$extras = 0; // initializes extras sum
if(isset($_POST['submit3'])){

	$mycondition=$_POST['conditionslist'];
	if($mycondition=='Fair'){
		$dealerPrice= $_SESSION['dealerPrice']*.95;
		$certPrice= $_SESSION['certPrice'] *.95 ;
		$privatePrice=$_SESSION['privatePrice']*.95;
	}
	if($mycondition=='Good'){
		$dealerPrice= $_SESSION['dealerPrice']*1.05;
		$certPrice= $_SESSION['certPrice'] *1.05 ;
		$privatePrice=$_SESSION['privatePrice']*1.05;
	}
	if($mycondition=='Excellent'){
		$dealerPrice= $_SESSION['dealerPrice']*1.1;
		$certPrice= $_SESSION['certPrice'] *1.1 ;
		$privatePrice=$_SESSION['privatePrice']*1.1;
	}
	
}
if(isset($_POST['option'])){
    $options_arr = $_POST['option']; // assigns selected options to array
}
if(empty($options_arr)){
    echo "Your additional options have added $0 to your total."; // returns $0 if no selection
}
else {
    foreach ($options_arr as $option) {
        $extras += 200; // adds $200 to total for each selected option
    }
    echo "Your additional options have added $" . $extras ." to your total."; // prints options sum to screen
}
echo "Your grand totals are: <br>$" .($dealerPrice + $extras) . " for Dealer Price. <br>" . "$" .($certPrice + $extras)
    ." for Certified Price.<br> $" . ($privatePrice + $extras) ." for Private-Party Price.";

?>
<?php // Include the HTML footer.

include ('footer.html');
?>
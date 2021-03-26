<?php
// This is the registration page for the site, which uses a sticky form

require_once ('config.php');
$page_title = 'Registration Main';
include ('header.html');
$displayForm = TRUE;

if (isset($_POST['register'])) { // Handle the form.

	require_once (MYSQLI_CONNECT);
	
	// Trim all the incoming data:
	//array_map() returns an array containing all the elements of an 
	//array, $_POST, after applying the callback function (trim) to each one
	$trimmed = array_map('trim', $_POST);
	
	// Assume invalid values:
	$fName = $lName = $email = $password = FALSE;
	
	// Check for a first name:
	if (preg_match ('/^[A-Z \'.-]{2,20}$/i', $trimmed['first_name'])) {
		//mysqli_real_escape_string — Escapes special characters in a string for
		//use in an SQL statement. I.e., the function creates a legal SQL string 
		//that you can use in an SQL statement
		$fName = mysqli_real_escape_string ($dbConnection, $trimmed['first_name']);
	} else {
		echo '<p class="error">Please enter your first name!</p>';
	}
	
	// Check for a last name:
	if (preg_match ('/^[A-Z \'.-]{2,40}$/i', $trimmed['last_name'])) {
		$lName = mysqli_real_escape_string ($dbConnection, $trimmed['last_name']);
	} else {
		echo '<p class="error">Please enter your last name!</p>';
	}
	
	// Check for an email address:
	if (preg_match ('/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/', $trimmed['email'])) {
		$email = mysqli_real_escape_string ($dbConnection, $trimmed['email']);
	} else {
		echo '<p class="error">Please enter a valid email address!</p>';
	}

	// Check for a password and match against the confirmed password:
	if (preg_match ('/^\w{4,20}$/', $trimmed['password1']) ) {
		if ($trimmed['password1'] == $trimmed['password2']) {
			$password = mysqli_real_escape_string ($dbConnection, $trimmed['password1']);
		} else {
			echo '<p class="error">Your password did not match the confirmed password!</p>';
		}
	} else {
		echo '<p class="error">Please enter a valid password!</p>';
	}
	
	if ($fName && $lName && $email && $password) { // If everything's OK...
		  
		//Query to check if the email address is available:
		$query_email = "SELECT user_id FROM users_table WHERE email='$email'";
		
		if ( !( $result = $dbConnection->query($query_email))) {
			trigger_error("Query: $query_email\n<br />MySQL Error: " . $dbConnection->error);
			//trigger_error is used to trigger a user error condition, usually used in conjunction 
			//with the built-in error handler
		} 
		
		if ($result->num_rows == 0) { // Available.
		    $hash = password_hash($password, PASSWORD_DEFAULT);
		
			// Add the user to the database:
			$query_insert = "INSERT INTO users_table (email, pass, first_name, last_name) ";
			$query_insert .= "VALUES ('$email', '$hash', '$fName', '$lName')";
			
			if ( !( $result = $dbConnection->query($query_insert))) {
				trigger_error("Query: $query_insert\n<br />MySQL Error: " . $dbConnection->error);
			} 
			if ($dbConnection->affected_rows == 1) { // If it ran OK.
				/* Send the email:
				$body = "Thank you for registering at our site. To activate your account, please click on this link:\n\n";
				$body .= BASE_URL . 'activate.php?x=' . urlencode($email) . "&y=$a";
				mail($trimmed['email'], 'Registration Confirmation', $body, 'From: admin@sitename.com');
				*/
				// Finish the page:
				$displayForm = FALSE;
				session_start();
				$_SESSION['user_id'] = $row[0];
				$_SESSION['name'] = $row[1];
				header("Location: redbook.php");
				exit; // Stop the page.
				
			} else { // If it did not run OK.
				echo '<p class="error">You could not be registered due to a system error!</p>';
			}
			
		} else { // The email address is not available.
			echo '<p class="error">That email address has already been registered. If you have forgotten your password, use the link at right to have your password sent to you.</p>';
		}
		
	} else { // If one of the data tests failed.
		echo '<p class="error">Please re-enter your passwords and try again.</p>';
	}

	$dbConnection->close();

} // End of the main Submit

if (isset($_POST['login'])) {
	require_once (MYSQLI_CONNECT);
	
	// Validate the email address:
	if (!empty($_POST['email'])) {
		$email = mysqli_real_escape_string ($dbConnection, $_POST['email']);
	} else {
		$email = FALSE;
		echo '<p class="error">You forgot to enter your email address!</p>';
	}
	
	// Validate the password:
	if (!empty($_POST['pass'])) {
		$password = mysqli_real_escape_string ($dbConnection, $_POST['pass']);
	} else {
		$password = FALSE;
		echo '<p class="error">You forgot to enter your password!</p>';
	}
	
	if ($email && $password) { // If everything's OK.
		// Query the database: 
		$query = "SELECT user_id, first_name, email, pass FROM users_table WHERE email='$email'";		
		
		if ( !( $result = $dbConnection->query($query))) {
			trigger_error("Query: $query\n<br />MySQL Error: " . $dbConnection->error);
		} 
			
		if ($result->num_rows == 1) { // A match was found
		
		  $row = $result->fetch_array(MYSQLI_NUM);
		  if (password_verify($password, $row[3])) { 
			// Register the values & redirect:
			session_start();
			$_SESSION['user_id'] = $row[0];
			$_SESSION['name'] = $row[1];
			$displayForm = FALSE;
			header("Location: redbook.php");
			
			exit;

		  }	else { // No match was made.
				echo '<p class="error">Either the email address and password entered do not match those on file or you have no account yet.</p>';
		  }
	    }
		
	   } else { // If everything is not OK.
		echo '<p class="error">Please try again.</p>';
	}
	
	$dbConnection->close();

} // End of SUBMIT conditional.
if ($displayForm == TRUE) {
?>
	<h1>Register</h1>
	<form action="register_login.php" method="post">
		<fieldset>
	
		<div class="myRow">
			<label class="labelCol" for="firstName">First Name</label> 
			<input type="text" name="first_name" size="20" maxlength="20" value="<?php if (isset($trimmed['first_name'])) echo $trimmed['first_name']; ?>" />
		</div>
		
		<div class="myRow">
			<label class="labelCol" for="lastName">Last Name</label>  
			<input type="text" name="last_name" size="20" maxlength="40" value="<?php if (isset($trimmed['last_name'])) echo $trimmed['last_name']; ?>" />
		</div>
	
		<div class="myRow">
			<label class="labelCol" for="email">Email</label>
			<input type="text" name="email" size="30" maxlength="80" value="<?php if (isset($trimmed['email'])) echo $trimmed['email']; ?>" /> 
		</div>
		
		<div class="myRow">
			<label class="labelCol" for="passw1">Password</label>
			<input type="password" name="password1" size="20" maxlength="20" />
			<small>Use only letters, numbers, and the underscore. Must be between 4 and 20 characters long.</small>
		</div>
	
		<div class="myRow">
			<label class="labelCol" for="passw2">Confirm Password</label>
			<input type="password" name="password2" size="20" maxlength="20" />
		</div>
		<div class="mySubmit">
			<input type="submit" name="register" value="Register" style="margin-right:5px" /><input type="reset" name="reset" value="reset"/>
		</div>
	</fieldset>
	</form>

	<h1>Login</h1>
	<p>Your browser must allow cookies in order to log in.</p>
	<form action="register_login.php" method="post">
		<fieldset>
		<div class="myRow">
			<label class="labelCol" for="email">Email</label> 
			<input type="text" name="email" size="20" maxlength="40" />
		</div>
		<div class="myRow">
			<label class="labelCol" for="[assw">Password</label>
			<input type="password" name="pass" size="20" maxlength="20" />
		</div>
		<div class="mySubmit">
			<input type="submit" name="login" value="Login" style="margin-right:5px" /><input type="reset" name="reset" value="reset"/>
		</div>
		</fieldset>
	</form>

<?php // Include the HTML footer.
}
include ('footer.html');
?>

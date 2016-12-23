<html>

<head>
<title> Police Login </title>
<style>
fieldset{ border: 1px solid grey; padding: 2em;}
legend {font-size: 2em; color: white;}
form {color: white;}
p {float: center;}
</style>
</head>

<body style="background-color: #DEE2E3">

<!-- Login form -->
<form method='post' style="text-align:center;">
<fieldset style="background-color: #7f7c7c; float:center; display: inline;">
	<h2 style="font-family: Georgia, serif"> Login: </h2>
		Username:<br> 
		<input type='text' name='username'> <br>
		Password :<br>
		<input type='password' name='password'> <br><br>
		<input type='submit' value='submit'>
</fieldset>
</form>

<p id=invalid> Invalid username/password </p>
<p id=missing> Please ensure you have entered both a username and password. </p>

<script>
	document.getElementById('invalid').style.display = 'none';
	document.getElementById('missing').style.display = 'none';
</script>

<?php
	// MySQL database information
	$servername = "mysql.cs.nott.ac.uk";
	$username = "psxgg4";
	$password = "database123";
	$dbname = "psxgg4";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname); 	// connect to database
	
	// end if connection to database was unsuccessful
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	$sql = 'SELECT * FROM Login;';		 // query to retrive login details
	$result = mysqli_query($conn, $sql); // send query to database

	// compare submitted login details to rows in Login table
	if ($_POST['username'] != '' and $_POST['password'] != ''){
		while ($row = mysqli_fetch_assoc($result)){
			if ($_POST['username'] == $row['Username'] and $_POST['password'] == $row['Password']){
				header('Location: police_main.php?');
			}
		}

		// check Admin tables after finishing Login table
		$sql = "SELECT * FROM Admins;";
		$result = mysqli_query($conn, $sql);
		while ($row = mysqli_fetch_assoc($result)){
			if ($_POST['username'] == $row['Username'] and $_POST['password'] == $row['Password']){

				header('Location: police_main.php?admin=true');
			}

		}
		
		echo "Username/Password pair incorrect";
	}

?>

</body>
</html>
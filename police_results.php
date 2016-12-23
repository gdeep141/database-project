<!DOCTYPE html>
<html>

<head>
<title> Police Results </title>
</head>

<body style="background-color: #DEE2E3">


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

	function continueButton() {
		echo "
			<br><br>
			<button onclick=goBack()>Continue</button>
			<br>

			<script>
			function goBack() {
				window.history.back();
			}
			</script>
		";
	}

	function checkStatus($message) {
		global $result, $conn;
		if ($result){
			echo $message."<br>";
		}
		else {
			echo "The database returned the error '".mysqli_error($conn)."'. Please go back and try again";
		}
		mysqli_free_result($result);
		mysqli_next_result($conn);
	}
	
	// check licenseSearch form is filled in
	if ($_POST['license'] != '') {
		
		$sql = "SELECT Name, Address, DOB FROM `People` 
				WHERE LicenseNumber = '".$_POST['license']."';";
				
		$result = mysqli_query($conn, $sql);
		
		// check search result is not empty
		if (mysqli_num_rows($result) == 0) {
			echo 'Number not found';	
		}
		else{
			while ($row = mysqli_fetch_array($result)) {
				echo "Name: ".$row['Name']."<br>";
				echo "Address: ".$row['Address']."<br>";
				echo "Date of Birth: ".$row['DOB']."<br>";
			}
		}
	}
	
	// check if report is filled in
	else if ($_POST['description'] != '' and $_POST['datetime'] != '' and $_POST['descriptionDropDown'] != '') {
		
		$offenceId = "select OffenceID from Offences where Description = '".$_POST['descriptionDropDown']."'";
		
			
		// if vehicle is selected from list
		if ($_POST["vehicleIDDropDown"] != ''){
			
			
			$personId = "select PersonID from People INNER join Vehicles on Vehicles.OwnerID = People.PersonID where VehicleID ='".$_POST["vehicleIDDropDown"]."'";
			
			$sql = "INSERT INTO `Fines` (VehicleID, PersonID, Time, OffenceID, OfficerStatement) VALUES
					('".$_POST["vehicleIDDropDown"]."', (".$personId."), '".$_POST['datetime']."', (".$offenceId."), '".$_POST['description']."');
				";

			$result = mysqli_multi_query($conn, $sql);
			
			checkStatus('Report added');
		}
		
		// else if new vehicle form is filled in 
		else if ($_POST['LicensePlate'] != '' and $_POST['make'] != '' and $_POST['model'] != '' and $_POST['colour'] != ''){

			// if person is selected from list
			if ($_POST["NameDropDown"] != ''){
				
				$personId = "select PersonID from People WHERE Name = '".$_POST["NameDropDown"]."'";
				
				$sql = "INSERT INTO `Vehicles` (VehicleID, Make, Model, Colour, OwnerID) VALUES
						('".$_POST['LicensePlate']."', '".$_POST['make']."', '".$_POST['model']."', '".$_POST['colour']."',
						(SELECT PersonID from People where Name = '".$_POST["NameDropDown"]."'));
					";
				
				$result = mysqli_multi_query($conn, $sql);

				checkStatus('Report and Vehicle added');
			}
			
			// else if all fields in new person form are filled in 
			else if ($_POST['name'] != '' and $_POST['dob'] != '' and $_POST['address'] != '' and $_POST['licenseNumber'] != '' and $_POST['expiryDate'] != '') {
				$personId = "SELECT PersonID from People where Name = '".$_POST["name"]."'";
				
				$sql = "INSERT INTO `People` (Address, DOB, ExpiryDate, LicenseNumber, Name) VALUES
						('".$_POST['address']."', '".$_POST['dob']."', '".$_POST['expiryDate']."', '".$_POST['licenseNumber']."', '".$_POST['name']."');
						INSERT INTO `Vehicles` (VehicleID, Make, Model, Colour, OwnerID) VALUES
						('".$_POST['LicensePlate']."', '".$_POST['make']."', '".$_POST['model']."', '".$_POST['colour']."',
						(SELECT PersonID from People where Name = '".$_POST['name']."' and DOB = '".$_POST['dob']."'));
					";

				$result = mysqli_multi_query($conn, $sql);
				
				checkStatus('Vehicle and person added');			
			}

			// add report
			$sql = "INSERT INTO `Fines` (VehicleID, PersonID, Time, OffenceID, OfficerStatement) VALUES
					('".$_POST["LicensePlate"]."', (".$personId."), '".$_POST['datetime']."', (".$offenceId."), '".$_POST['description']."');
				";

			$result = mysqli_query($conn, $sql);

			checkStatus('Report added');
		}
	}

	// check plateSearch form is filled in
	else if ($_POST['plate'] != ''){
		$sql = "SELECT Make, Model, Colour, Name, LicenseNumber FROM `Vehicles`
				LEFT JOIN People on People.PersonID = Vehicles.OwnerID
				WHERE VehicleID = '".$_POST['plate']."';";
		$result = mysqli_query($conn, $sql);
		
		// check search result is not empty
		if (mysqli_num_rows($result) == 0){
			echo "No results found";
		}

		else {
			while ($row = mysqli_fetch_array($result)) {
				echo "Make: ".$row['Make']."<br>";
				echo "Model: ".$row['Model']."<br>";
				echo "Colour: ".$row['Colour']."<br>";
				echo "Name: ".$row['Name']."<br>";
				echo "LicenseNumber: ".$row['LicenseNumber']."<br>";
			}
		}
	}
	
	// check if 'fine' form is filled in
	else if ($_POST['nameDropDown'] != '' and $_POST['DescriptionDropDown'] != '' and $_POST['TimeDropDown'] != '' and $_POST['fine'] != '') {
		
		$personId = "SELECT personId from People where name = '".$_POST['nameDropDown']."'";
		$offenceId = "SELECT offenceId from Offences where Description LIKE '".$_POST['DescriptionDropDown']."'";

		$sql = "UPDATE Fines 
				SET Amount = ".$_POST['fine']."
				WHERE Fines.PersonID = (".$personId.") AND Fines.OffenceId = (".$offenceId.") AND Fines.Time = '".$_POST['TimeDropDown']."';";
		
		$result = mysqli_query($conn, $sql);
		
		checkStatus('Fine added to record');
	}
	
	// check if createOfficer form is filled in
	else if ($_POST['officerID'] != '' and $_POST['setPassword'] != '') {
		$sql = "INSERT INTO `Login` VALUES
				('".$_POST['officerID']."', '".$_POST['setPassword']."');";
		
		$result = mysqli_query($conn, $sql);
		
		checkStatus('Officer added successfully');
	}
	
	// check newVehicle form is filled in
	else if ($_POST['LicensePlate'] != '' and $_POST['make'] != '' and $_POST['model'] != '' and $_POST['colour'] != '') {

		// if person is selected from list
		if ($_POST["NameDropDown"] != ''){
			$sql = "INSERT INTO `Vehicles` (VehicleID, Make, Model, Colour, OwnerID) VALUES
					('".$_POST['LicensePlate']."', '".$_POST['make']."', '".$_POST['model']."', '".$_POST['colour']."',
					(SELECT PersonID from People where Name = '".$_POST["NameDropDown"]."'));";

			$result = mysqli_query($conn, $sql);
			
			checkStatus('Vehicle added to person');
		}
		
		// else if all fields in new person form are filled in 
		else if ($_POST['name'] != '' and $_POST['dob'] != '' and $_POST['address'] != '' and $_POST['licenseNumber'] != '' and $_POST['expiryDate'] != '') {
			$sql = "INSERT INTO `People` (Address, DOB, ExpiryDate, LicenseNumber, Name) VALUES
					('".$_POST['address']."', '".$_POST['dob']."', '".$_POST['expiryDate']."', '".$_POST['licenseNumber']."', '".$_POST['name']."');
					INSERT INTO `Vehicles` (VehicleID, Make, Model, Colour, OwnerID) VALUES
					('".$_POST['LicensePlate']."', '".$_POST['make']."', '".$_POST['model']."', '".$_POST['colour']."',
					(SELECT PersonID from People where Name = '".$_POST['name']."' and DOB = '".$_POST['dob']."'));";

			$result = mysqli_multi_query($conn, $sql);
			
			checkStatus('Person and Vehicle successfully added');
		}
	}
	
	// if no forms have been filled out fully
	else{
		echo 'Please fill out all fields.';
	}

	continueButton();
?>

</body>

</html>
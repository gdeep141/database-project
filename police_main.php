<html>

<head>
<title> Police Main </title>
<style>
fieldset {border: 1px solid black; display: inline}
legend {font-size: 20px}
</style>
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
?>

<!-- log out link -->
<a href="police_login.php" class=button> Log out </a>

<!-- Buttons to select action to be done by user -->
<form id='actionButtons'>
	<fieldset style="background-color: #7f7c7c; color: white; float:center; display: inline;"> 
	<h3 style="font-family: Georgia, serif"> Please select an action: </h3>
	<input type='radio' name='action' value='licenseSearch' onclick=showForm()> Search using a license number <br>
	<input type='radio' name='action' value='plateSearch' onclick=showForm()>  Search using a vehicle plate <br>
	<input type='radio' name='action' value='newVehicle' onclick=showForm()>  Enter new vehicle details <br>
	<input type='radio' name='action' value='report' onclick=showForm()>  Create a report <br>
	</fieldset>

</form>
<form id='admin'>
	<fieldset style="display:inline; color:white; background-color: #7f7c7c">
	<h3 style="font-family: Georgia, serif"> Admin actions: </h3>
	<input type='radio' name='action' value='fine' onclick=showForm()> Add a fine <br>
	<input type='radio' name='action' value='createOfficer' onclick=showForm()> Enter new officer details <br>
	</fieldset>
</form>

<hr style="height: 1px"><br>

<div style="width: 100%;">

<!-- Normal user forms on left side of page -->
<div style="float:left; width: 50%;">

<form id='licenseSearch' method='post' name='queryform' action='police_results.php'>
	<fieldset>
	<legend> Search using a license number: </legend><br>
		Enter a person's license number:<br>
		<input type='text' name='license'><br><br>
		<input id ='submit' type='submit' value='submit'>
	</fieldset>
</form>

<form id='plateSearch' method='post' name='queryform' action='police_results.php'>
	<fieldset>
	<legend> Search using a vehicle license plate: </legend><br>
		Enter a vehicle license plate:<br>
		<input type='text' name='plate'><br><br>
		<input id ='submit' type='submit' value='submit'>
	</fieldset>
	
</form>

<form id='newVehicle' method='post' name='queryform' action='police_results.php'>
	<fieldset>
	<legend> Enter new vehicle details: </legend><br>
		Vehicle License Number:<br>
		<input type='text' name='LicensePlate'><br><br>
		Vehicle Make: <br>
		<input type='text' name='make'><br><br>
		Vehicle Model: <br>
		<input type='text' name='model'><br><br>
		Vehicle Colour: <br>
		<input type='text' name='colour'><br><br>

		<p> Vehicle Owner: </p>
		<?php makeList('Name', 'People', 'Person already in database', '#E2E5FA'); ?><br>

		<p style="font-weight: bold;"> or </p>
		
		<fieldset style="background-color: #E2E5FA">
		<legend> Enter new person details: </legend><br>
			Name: <br>
			<input type='text' name='name'><br><br>
			Date of Birth <br>
			<input type='datetime-local' name='dob' placeholder='YYYY-MM-DD'><br><br>
			Address: <br>
			<input type='text' name='address'><br><br>
			License Number: <br>
			<input type='text' name='licenseNumber'><br><br>
			Expiry Date: <br>
			<input type='datetime-local' name='expiryDate' placeholder='YYYY-MM-DD'><br><br>
		</fieldset>
	</fieldset> <br><br>
	
	
	
	<input type=submit value=submit>
	
</form>


<form id='report' method='post' name='queryform' action='police_results.php'>
	<fieldset>
	<legend> Create a report: </legend><br>
		Offence Description: <br>
		<?php makePlainList('description', 'Offences') ?>
		Report Description (400 characters): <br>
		<textarea name='description' rows='10' cols='30'>
		</textarea><br><br>
		Incident Time: <br>
		<input type='datetime-local' name='datetime' placeholder='YYYY-MM-DD'> <br><br>
		

		Vehicle:<br>

		<?php makeList('vehicleID', 'Vehicles', 'Vehicle is already in database', '#E2E5FA') ?> <br>

		<p style="font-weight: bold;"> or </p>

		<fieldset style="background-color: #E2E5FA">
		<legend> Enter new vehicle details: </legend><br>
			Vehicle License Number:<br>
			<input type='text' name='LicensePlate'><br><br>
			Vehicle Make: <br>
			<input type='text' name='make'><br><br>
			Vehicle Model: <br>
			<input type='text' name='model'><br><br>
			Vehicle Colour: <br>
			<input type='text' name='colour'><br><br>

			<p> Vehicle Owner: </p>
			<?php makeList('Name', 'People', 'Person already in database', '#DEE2E3'); ?><br>

			<p style="font-weight: bold;"> or </p>
			
			<fieldset style="background-color: #DEE2E3">
			<legend> Enter new person details: </legend><br>
				Name: <br>
				<input type='text' name='name'><br><br>
				Date of Birth <br>
				<input type='datetime-local' name='dob' placeholder='YYYY-MM-DD'><br><br>
				Address: <br>
				<input type='text' name='address'><br><br>
				License Number: <br>
				<input type='text' name='licenseNumber'><br><br>
				Expiry Date: <br>
				<input type='datetime-local' name='expiryDate' placeholder='YYYY-MM-DD'><br><br>
			</fieldset>
		</fieldset> 
	</fieldset> <br><br>

	<input type=submit value=submit>
</form>

<!-- admin forms on right side of page -->
</div>
<div style="float:right; ">


<form id='fine' method='post' name='queryform' action='police_results.php?admin=true'>
	<fieldset style="background-color: #F0DADA">
	<legend> Add a fine: </legend><br>
		Person: <br>
		<?php makePlainList(name, People) ?><br>
		Time: <br>
		<?php makePlainList(Time, Fines) ?><br>
		Description: <br>
		<?php makePlainList(Description, Offences) ?><br>
		Fine: <br>
		<input type='number' name=fine> <br> <br>
		<input type=submit value=submit>

	</fieldset>
</form>

<form id='createOfficer' method='post' name='queryform' action='police_results.php?admin=true'>
	<fieldset style="background-color: #F0DADA">
	<legend> Enter new officer details: </legend><br>
		username: <br>
		<input type='text' name='officerID'><br><br>
		password: <br>
		<input type='text' name='setPassword'><br><br>
		<input id ='submit' type='submit' value='submit'>
	</fieldset>
</form>


</div>
</div>
<div style="clear:both"></div>

<script>
 	// hide admin action buttons
	document.getElementById('admin').style.display = 'none';
	
 	// hide database query forms
	document.getElementById('licenseSearch').style.display = 'none';
	document.getElementById('report').style.display = 'none';
	document.getElementById('newVehicle').style.display = 'none';
	document.getElementById('plateSearch').style.display = 'none';
	document.getElementById('fine').style.display = 'none';
	document.getElementById('createOfficer').style.display = 'none';
	
	// hide empty login details
	document.getElementById('emptyLogin').style.display = 'none';
	
	// shows form relevant to selected action (maps action value to form id)
	function showForm(){	
		var actions = document.getElementsByName('action');
		for (var i = 0; i < actions.length; i++){
			if (actions[i].checked)
				document.getElementById(actions[i].value).style.display = 'block';
			else
				document.getElementById(actions[i].value).style.display = 'none';
		}
		document.getElementById('submit').style.display = 'block'; //show submit button
	}
	

</script>

<?php
	
	// show admin buttons if admin was passed in hyperlink	
	if ($_GET['admin']) {
		echo "
			<script>
				document.getElementById('admin').style.display = 'block';
			</script>
		";
	}
	
	
	// create drop down list
	function makeList($column, $table, $caption, $colour){
		
		// create query and send to database
		global $conn;
		$sql = "SELECT ".$column." FROM ".$table.";";
		$result = mysqli_query($conn, $sql);
		
		// construct drop-down list
		echo "<fieldset style='background-color: ".$colour."'> <legend>".$caption."</legend><br>";
		echo $column.": <select name=".$column."DropDown>";
		echo "<option></option>";   // first item in list is blank
		while ($row = mysqli_fetch_array($result)) {
			echo "<option>";
			echo $row[$column];
			echo "</option>";
		}
		echo "</select> <br>";
		echo "</fieldset>";
	}
	
	function makeReportList() {
		// create query and send to database
		global $conn;
		$sql = "SELECT * FROM Fines";
		$result = mysqli_query($conn, $sql);
		
		// construct drop-down list
		echo "<select name= reportDropDown>";
		echo "<option></option>";   // first item in list is blank
		while ($row = mysqli_fetch_array($result)) {
			echo "<option>";
			echo $row['VehicleID'].", ".$row['Time'].", ".$row['OfficerStatement'];
			echo "</option>";
		}
		echo "</select> <br><br>";
	}


	function makePlainList($column, $table){
		
		// create query and send to database
		global $conn;
		$sql = "SELECT DISTINCT ".$column." FROM ".$table.";";
		$result = mysqli_query($conn, $sql);
		
		// construct drop-down list
		echo "<select name=".$column."DropDown>";
		echo "<option></option>";   // first item in list is blank
		while ($row = mysqli_fetch_array($result)) {
			echo "<option>";
			echo $row[$column];
			echo "</option>";
		}
		echo "</select> <br><br>";
	}


?>

</body>
</html>
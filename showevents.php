<?php
	$date = $_GET["date"];
	$month = $_GET["month"];
	$year = $_GET["year"];
	
	$serverName = "localhost";
	$userName = "root";
	$passWord = "";
	$dbName = "eventcalender";

	$conn = mysqli_connect($serverName, $userName, $passWord, $dbName);  //connect to database
?>

<html>
	<head>
	<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/showEventsStyleSheet.css">
	</head>
	<body>
		<div id="myDiv">
			<div class="form">
				<div class="login-form">
				<i class='far fa-arrow-alt-circle-left backArrow' style='font-size:52px;color:green' onclick="backClicked()"></i>
					<h2>Add Event</h2><br>
				  <input id="ename" name='ename' type="text" placeholder="Event Name"/>
				  <input id="edate" name='edate' type="text" placeholder="Event Date"/>
				  <input id="etime" name='etime' type="text" placeholder="12 pm (Event Time)"/>
				  <textarea id="einfo" name='einfo' cols="50" rows="10" placeholder="Event Information"></textarea>
				  <button id="submitButton" name="submitButton" onclick="myFunction()">Save</button>
				</div>
			</div>
			<div class="header" >
					<div class="dayEventsHeader" >
						<h1 id="eventHeader"></h1>
					</div>
					<div class="dayEvents" >
					<table class="table table-hover">
					<thead>
					<tr>
						<th>Event Name</th>
						<th>Date</th>
						<th>Time</th>
						<th>Information</th>
					</tr>
					</thead>
					<tbody>
					<?php
						$ename = ""; $edate = ""; $etime = ""; $einfo = "";
						 $sql = "SELECT * FROM `event` WHERE date='".$date."/".$month."/".$year."' ORDER BY time ASC";  //fetch all events from database related to processing date
																														//and show them in table format
						 $result = $conn->query($sql);

						 if ($result->num_rows > 0) {                //check if given date has stored any events, if yes then show them
							 // output data of each row
							 while($row = $result->fetch_assoc()) {
								$ename = $row["name"];
								$edate = $row["date"];
								$etime = $row["time"];
								if($etime>12){
									$etime = ($etime - 12)." pm";   //convert 24 hr format to 12 hr human readable format
								}
								else{
									$etime = $etime." am";
								}
								$einfo = $row["info"];
								echo"
									<tr>
										<td>".$ename."</td>
										<td>".$edate."</td>
										<td>".$etime."</td>
										<td>".$einfo."</td>
									</tr>
								";
							 }
						}
						else{                                      //means no events are added related to current date, display message 
							echo "<h5 style='text-align: center; margin-bottom: 15px;'>No Event Yet!!! Add New Event...</h5>";  
						}
					?>
					</tbody>
					</table>
					</div>
			</div>
		</div>

		<script>
			var months = [                           //create array of months
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December"
            ]
			
			<?php echo "document.getElementById('edate').value = '".$date."/".$month."/".$year."';";                         //assign date to edate and header
				echo "document.getElementById('eventHeader').innerHTML = '".$date." '+months[".($month-1)."]+' ".$year."';"; 
			?>
			
			function backClicked(){ 
				window.location.href="index.php";                          //go back to main page
			}
		
			function myFunction(){
				var ename =  document.getElementById("ename").value;
				var edate =  document.getElementById("edate").value;
				var etime =  document.getElementById("etime").value;
				var einfo =  document.getElementById("einfo").value;
				
				var myUrl ="addtoevent.php?ename="+ename+"&edate="+edate+"&etime="+etime+"&einfo="+einfo;
				
				var xhr = new XMLHttpRequest();								//send request only to addtoevent.php, do not redirect i.e. database work needed to be done at background
				xhr.open("GET", myUrl, true);
				xhr.setRequestHeader('Content-Type', 'application/json');
				xhr.send(JSON.stringify({
					ename : ename,
					edate : edate,
					etime : etime
				}));
				
				window.alert("Event Added Successfully");
				
				location.reload();											//refresh to see events added
			}
		</script>
	</body>
</html>
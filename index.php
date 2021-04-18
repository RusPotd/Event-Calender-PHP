<?php
	$serverName = "localhost";
	$userName = "root";
	$passWord = "";
	$dbName = "eventcalender";

	$conn = mysqli_connect($serverName, $userName, $passWord, $dbName);
	
	$sql = "SELECT DISTINCT `date` FROM `event`";   //fetch all unique dates from database

	$result = $conn->query($sql);
	$dates = array();

	if ($result->num_rows > 0) {					 
		while($row = $result->fetch_assoc()) { 
			$temp = $row["date"];
			array_push($dates, $temp);				//store all unique dates in array
		}
	}
?>

<html>

<head>
    <link rel="stylesheet" href="assets/css/main.css">
	<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
</head>

<body onload="renderDate()">
	
	<div class="yearHead">
		<div class="prev" onclick="moveDate('prev')">  <!--Left arrow-->
			<span>&#10094;</span>
		</div>
		<h1 id="currentYear">2021</h1>
		<div class="next" onclick="moveDate('next')">  <!--Right arrow-->
			<span>&#10095;</span>
		</div>
	</div>

    <div class="wrapper">
	<?php 
		for($x=0; $x<12; $x++){  //print each month calender 12 times i.e to get year view
			echo "
				<div class='calendar'>
					<div class='month'>
						
						<div>
							<h2 id='month' class='month_text'></h2>
						</div>
						
					</div>
					<div class='weekdays'>
						<div>Sun</div>
						<div>Mon</div>
						<div>Tue</div>
						<div>Wed</div>
						<div>Thu</div>
						<div>Fri</div>
						<div>Sat</div>
					</div>
					<div class='days'>

					</div>
				</div>
			";
		}
	?>  
    </div>
	
    <script>
		var js_array = [<?php echo '"'.implode('","',  $dates ).'"' ?>];   //get php dates array into js_array javascript
	
        var dt = new Date();
		var prevYear = 0000;
		var currentSelectedDate = "";
		
        function renderDate(year = dt.getFullYear()) {
			prevYear = year                                           //store recieved year in prevYear for future use
			document.getElementById("currentYear").innerHTML = year;
			
            var today = new Date();
			
            var months = [
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
          
			for(i = 0; i<12; i++){                                                              //print dates into each month i.e 12 times loop
				document.getElementsByClassName("month_text")[i].innerHTML = months[i];
				
				dt.setFullYear(year, i, 1);                                                     //set date to recieved year, current month in loop and 1 date of that month
				var day = dt.getDay();                                                          //get day of 1st date i.e in numerical sun = 0, mon = 1, etc.
				
				var endDate = new Date(															//get last date of processing month
					year,
					dt.getMonth() + 1,
					0
				).getDate();

				var cells = "";
				
				for (x = day; x > 0; x--) {                                                    //loop through 0 to (int) day
					cells += "<div class='prev_date'>" + " " + "</div>";                       //mark all days before 1st date day as blank in calender to fill space
				}
				
				for(var j=1; j<=endDate; j++) {
					
					if(js_array.includes(j+"/"+(dt.getMonth()+1)+"/"+dt.getFullYear())){       //check if current date has any events stored i.e check if current date is in js_array
						
						if (j == today.getDate() && dt.getMonth() == today.getMonth() && dt.getFullYear() == today.getFullYear()){  
							//if date is today then show small circle indicating event marked
							cells += "<div class='today fas fa-circle' onclick='show(event, "+j+","+(dt.getMonth()+1)+","+dt.getFullYear()+")'>" + j + " </div>";
						}
						else if(dt.getFullYear() < today.getFullYear() || (dt.getMonth() < today.getMonth() && dt.getFullYear() == today.getFullYear())){   
							//check if date is of previous moth of same year or previous year
							cells += "<div class='passedEvent'  onclick='show(event, "+j+","+(dt.getMonth()+1)+","+dt.getFullYear()+")'>" + j + "</div>";
						}
						else if(j < today.getDate() && dt.getMonth() == today.getMonth() && dt.getFullYear() == today.getFullYear()){     
							//check if date is previous of today within same month and year 
							cells += "<div class='passedEvent'  onclick='show(event, "+j+","+(dt.getMonth()+1)+","+dt.getFullYear()+")'>" + j + "</div>";
						}
						else{																												
							//else it is upcoming date with event then mark as blue
							cells += "<div class='upcomingEvent' onclick='show(event, "+j+","+(dt.getMonth()+1)+","+dt.getFullYear()+")'>" + j + "</div>";
						}
					}
					else{
						if (j == today.getDate() && dt.getMonth() == today.getMonth() && dt.getFullYear() == today.getFullYear()){          
							//check if date is today -> mark red
							cells += "<div class='today'  onclick='show(event, "+j+","+(dt.getMonth()+1)+","+dt.getFullYear()+")'>" + j + "</div>";
						}
						else{																									
							cells += "<div onclick='show(event, "+j+","+(dt.getMonth()+1)+","+dt.getFullYear()+")'>" + j + "</div>";
						}
					}
				}
				
				document.getElementsByClassName("days")[i].innerHTML = cells;                //append all data in cells string to days class of i'th month
				
			}
        }
		
		function show(event, date, month, year){         									//go to showevents page to display events
			window.location.href="showevents.php?date="+date+"&month="+month+"&year="+year;
		}

        function moveDate(para) {                                                            //function to change year in calender
            if(para == "prev") {
				console.log(prevYear - 1);
                renderDate(prevYear - 1);
				
            } else if(para == 'next') {
				console.log(prevYear + 1);
                renderDate(prevYear + 1);
            }
            
        }
		
		
    </script>
</body>

</html>
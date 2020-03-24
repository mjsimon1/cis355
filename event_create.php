<?php 

session_start();
if(!isset($_SESSION["playerid"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
	
require 'database.php';

$playerid = $_SESSION['playerid'];
$sessionid = $_SESSION['playerid'];

if ( !empty($_POST)) {
		// keep track validation errors
		$eventdateError = null;
		$eventtimeError = null;
		$eventlocationError = null;
		$eventdescriptionError = null;
		
		// keep track post values
		$eventdate = $_POST['date'];
		$eventtime = $_POST['time'];
		$eventlocation = $_POST['location'];
		$eventdescription = $_POST['description'];
		
		// validate input
		$valid = true;
		if (empty($eventdate)) {
			$eventdateError = 'Please enter Date';
			$valid = false;
		}
		
		if (empty($eventtime)) {
			$eventtimeError = 'Please enter Time';
			$valid = false;
		}
		
		if (empty($eventlocation)) {
			$eventlocationError = 'Please enter Location';
			$valid = false;
		}
		
		if (empty($eventdescription)) {
			$eventdescriptionError = 'Please enter description';
			$valid = false;
		}
		
		// insert data
	if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "INSERT INTO event (eventdate,eventtime,eventlocation,eventdescription,eventplayerid) values(?, ?, ?, ?, ?)";
			$q = $pdo->prepare($sql);
			$q->execute(array($eventdate,$eventtime,$eventlocation,$eventdescription, $playerid));
			Database::disconnect();
			//header("Location: event_list.php");
		}
	}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>  
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
    
    			<div class="span10 offset1">
    				<div class="row">
		    			<h3>Create an Event</h3>
		    		</div>
    		
	    			<form class="form-horizontal" action="event_create.php" method="post">
					  <div class="control-group <?php echo !empty($dateError)?'error':'';?>">
					    <label class="control-label">Date</label>
					    <div class="controls">
					      	<input name="date" type="date"  placeholder="Date" value="<?php echo !empty($date)?$date:'';?>">
					      	<?php if (!empty($dateError)): ?>
					      		<span class="help-inline"><?php echo $dateError;?></span>
					      	<?php endif; ?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($timeError)?'error':'';?>">
					    <label class="control-label">Time</label>
					    <div class="controls">
					      	<input name="time" type="time" placeholder="Time" value="<?php echo !empty($time)?$time:'';?>">
					      	<?php if (!empty($timeError)): ?>
					      		<span class="help-inline"><?php echo $timeError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($locationError)?'error':'';?>">
					    <label class="control-label">Location</label>
					    <div class="controls">
					      	<input name="location" type="text"  placeholder="Location" value="<?php echo !empty($location)?$location:'';?>">
					      	<?php if (!empty($locationError)): ?>
					      		<span class="help-inline"><?php echo $locationError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					   <div class="control-group <?php echo !empty($descriptionError)?'error':'';?>">
					    <label class="control-label">Description</label>
					    <div class="controls">
					      	<input name="description" type="text"  placeholder="Description" value="<?php echo !empty($description)?$description:'';?>">
					      	<?php if (!empty($descriptionError)): ?>
					      		<span class="help-inline"><?php echo $descriptionError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="form-actions">
						  <button type="submit" class="btn btn-success">Create</button>
						  <a class="btn" href="event_list.php">Back</a>
						</div>
					</form>
				</div>
				
    </div> <!-- /container -->
  </body>
</html>
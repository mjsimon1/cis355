<?php 

    session_start();
    if(!isset($_SESSION["playerid"])){ // if "user" not set,
     	session_destroy();
    	header('Location: login.php');   // go to login page
	    exit;
   }
   
$playerid = $_SESSION['playerid']; // for MyAssignments
$sessionid = $_SESSION['playerid'];

$id = null;
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}
	
	require 'database.php';

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
			$eventdateError = 'Please enter a valid date';
			$valid = false;
		}
		
		if (empty($eventtime)) {
			$eventtimeError = 'Please enter a valid time';
			$valid = false;
		}
		
		if (empty($eventlocation)) {
			$eventlocationError = 'Please enter a course location';
			$valid = false;
		}
		
		if (empty($eventdescription)) {
			$eventdescriptionError = 'Please enter a breif description for this grouping';
			$valid = false;
		}
		
		// update data
				if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE event set eventdate = ?, eventtime = ?, eventlocation =?, eventdescription =? WHERE id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($eventdate,$eventtime,$eventlocation,$eventdescription,$id));
			Database::disconnect();
			//header("Location: event_list.php");
		}
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM event where id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		$eventdate = $data['eventdate'];
		$eventtime = $data['eventtime'];
		$eventlocation = $data['eventlocation'];
		$eventdescription = $data['eventdescription'];
		Database::disconnect();
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
		    			<h3>Update a tee time </h3>
		    		</div>
    		
	    			<form class="form-horizontal" action="event_update.php?id=<?php echo $id?>" method="post">
					  <div class="control-group <?php echo !empty($dateError)?'error':'';?>">
					    <label class="control-label">Date</label>
					    <div class="controls">
					      	<input name="date" type="date"  placeholder="Date" value="<?php echo !empty($eventdate)?$eventdate:'';?>">
					      	<?php if (!empty($eventdateError)): ?>
					      		<span class="help-inline"><?php echo $eventdateError;?></span>
					      	<?php endif; ?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($eventtimeError)?'error':'';?>">
					    <label class="control-label">Time</label>
					    <div class="controls">
					      	<input name="time" type="time" placeholder="Time" value="<?php echo !empty($eventtime)?$eventtime:'';?>">
					      	<?php if (!empty($eventtimeError)): ?>
					      		<span class="help-inline"><?php echo $eventtimeError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($eventlocationError)?'error':'';?>">
					    <label class="control-label">Mobile Number</label>
					    <div class="controls">
					      	<input name="location" type="text"  placeholder="Location" value="<?php echo !empty($eventlocation)?$eventlocation:'';?>">
					      	<?php if (!empty($eventlocationError)): ?>
					      		<span class="help-inline"><?php echo $eventlocationError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($eventdescriptionError)?'error':'';?>">
					    <label class="control-label">Description</label>
					    <div class="controls">
					      	<input name="description" type="text"  placeholder="Description" value="<?php echo !empty($eventdescription)?$eventdescription:'';?>">
					      	<?php if (!empty($eventdescriptionError)): ?>
					      		<span class="help-inline"><?php echo $eventdescriptionError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="form-actions">
						  <button type="submit" class="btn btn-success">Update</button>
						  <a class="btn" href="event_list.php">Back</a>
						</div>
					</form>
				</div>
				
    </div> <!-- /container -->
  </body>
</html>
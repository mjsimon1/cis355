<?php 

    session_start();
    if(!isset($_SESSION["playerid"])){ // if "user" not set,
	   session_destroy();
	   header('Location: login.php');   // go to login page
	   exit;
    }
    $playerid = $_SESSION['playerid']; // for MyAssignments
    $sessionid = $_SESSION['playerid'];
    
	require 'database.php';
	
	$id = null;
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}
	
	

 if ( !empty($_POST)) {
    $insert = true;
    $id = $_POST['id'];
	$pdo = Database::connect();	
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = 'SELECT * FROM teetimes
	WHERE eventid=' .$id.'';
    foreach ($pdo->query($sql) as $row) {
        
        if($row['playerid'] == $playerid) {
            $insert = false;
        }   
    
    }

    if($insert){
        
	   $sql = "INSERT INTO teetimes (eventid,playerid) values(?, ?)";
	   $q = $pdo->prepare($sql);
	   $q->execute(array($id,$playerid));
	   Database::disconnect();
	   //header("Location: event_list.php");
    }
    else{
        
        echo ("<script LANGUAGE='JavaScript'>
    window.alert('You have already joined this event!');
    window.location.href='event_list.php';
    </script>");
    }
 }
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
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
		    			<h3>Join the Group</h3>
		    		</div>
		    		
	    			<form class="form-horizontal" action="event_join.php" method="post">
	    			  <input type="hidden" name="id" value="<?php echo $id;?>"/>
					  <p class="alert alert-error">Are you sure you want to join this tee time?</p>
					  <div class="form-actions">
						  <button type="submit" class="btn btn-danger">Yes</button>
						  <a class="btn" href="event_list.php">No</a>
						</div>
					</form>
				</div>
				
    </div> <!-- /container -->
  </body>
</html>
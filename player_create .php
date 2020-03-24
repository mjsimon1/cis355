<?php 

session_start();

	
require 'database.php';

if ( !empty($_POST) ) { 
    
	$fnameError = null;
	$lnameError = null;
	$emailError = null;
	$mobileError = null;
	$passwordError = null;
	$pictureError = null; 
	
	// initialize $_POST variables
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$email = $_POST['email'];
	$mobile = $_POST['mobile'];
	$password = $_POST['password'];
	$passwordhash = MD5($password);
	//$picture = $_POST['picture']; // not used
	
	// initialize $_FILES variables
	if(!empty($_FILES['userfile']['tmp_name'])){
	    $fileName = $_FILES['userfile']['name'];
	    $tmpName  = $_FILES['userfile']['tmp_name'];
    	$fileSize = $_FILES['userfile']['size'];
        $fileType = $_FILES['userfile']['type'];
	    $content = file_get_contents($tmpName);
	    
	        // restrict file types for upload
	    $types = array('image/jpeg','image/gif','image/png');
	    if($fileSize > 0) {
	    	if(in_array($_FILES['userfile']['type'], $types)) {
	    	}
	    	else {
		    	$fileName = null;
			    $fileType = null;
			    $fileSize = null;
			    $fileContent = null;
    			$pictureError = 'improper file type';
	    		$valid=false;
			
		    }
    	}
	}
    
	// validate user input
	$valid = true;
	if (empty($fname)) {
		$fnameError = 'Please enter your First Name';
		$valid = false;
	}
	if (empty($lname)) {
		$lnameError = 'Please enter your Last Name';
		$valid = false;
	}
	if (empty($fileName)) {
		$pictureError = 'Please submit a profile picture';
		$valid = false;
	}
	// do not allow 2 players to be made with same email address
	if (empty($email)) {
		$emailError = 'Please enter valid email ';
		$valid = false;
	} else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
		$emailError = 'Please enter a valid email ';
		$valid = false;
	}

	$pdo = Database::connect();
	$sql = "SELECT * FROM players";
	foreach($pdo->query($sql) as $row) {

		if($email == $row['email']) {
			$emailError = 'This email has already been registered to another player';
			$valid = false;
		}
	}
	Database::disconnect();
	
	// email must contain only lower case letters
	if (strcmp(strtolower($email),$email)!=0) {
		$emailError = 'email address can contain only lower case letters';
		$valid = false;
	}
	
	if (empty($mobile)) {
		$mobileError = 'Please enter a valid mobile phone number ';
		$valid = false;
	}
	if(!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $mobile)) {
		$mobileError = 'Please write the mobile number as listed  000-000-0000';
		$valid = false;
	}
	if (empty($password)) {
		$passwordError = 'Please enter valid password';
		$valid = false;
	}
	
	// insert data
	if ($valid) 
	{
		$pdo = Database::connect();
		
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO players (fname,lname,email,mobile,password,filename,filesize,filetype,filecontent) values(?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($fname,$lname,$email,$mobile,$passwordhash,$fileName,$fileSize,$fileType,$content));
		
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM players WHERE email = ? AND password = ? LIMIT 1";
		$q = $pdo->prepare($sql);
		$q->execute(array($email,$passwordhash));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		
		$_SESSION['playerid'] = $data['id'];
		$_SESSION['playerfname'] = $data['fname'];
		
		Database::disconnect();
		header("Location: event_list.php");
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>  
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>

</head>

<body>
    <div class="container">

		<div class="span10 offset1">
			<div class="row">
				<h3>Add A New Player</h3>
			</div>
	
			<form class="form-horizontal" action="player_create.php" method="post" enctype="multipart/form-data">

				<div class="control-group <?php echo !empty($fnameError)?'error':'';?>">
					<label class="control-label">First Name</label>
					<div class="controls">
						<input name="fname" type="text"  placeholder="First Name" value="<?php echo !empty($fname)?$fname:'';?>">
						<?php if (!empty($fnameError)): ?>
							<span class="help-inline"><?php echo $fnameError;?></span>
						<?php endif; ?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($lnameError)?'error':'';?>">
					<label class="control-label">Last Name</label>
					<div class="controls">
						<input name="lname" type="text"  placeholder="Last Name" value="<?php echo !empty($lname)?$lname:'';?>">
						<?php if (!empty($lnameError)): ?>
							<span class="help-inline"><?php echo $lnameError;?></span>
						<?php endif; ?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($emailError)?'error':'';?>">
					<label class="control-label">Email</label>
					<div class="controls">
						<input name="email" type="text" placeholder="Email Address" value="<?php echo !empty($email)?$email:'';?>">
						<?php if (!empty($emailError)): ?>
							<span class="help-inline"><?php echo $emailError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($mobileError)?'error':'';?>">
					<label class="control-label">Mobile Number</label>
					<div class="controls">
						<input name="mobile" type="text"  placeholder="Mobile Phone Number" value="<?php echo !empty($mobile)?$mobile:'';?>">
						<?php if (!empty($mobileError)): ?>
							<span class="help-inline"><?php echo $mobileError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($passwordError)?'error':'';?>">
					<label class="control-label">Password</label>
					<div class="controls">
						<input id="password" name="password" type="password"  placeholder="password" value="<?php echo !empty($password)?$password:'';?>">
						<?php if (!empty($passwordError)): ?>
							<span class="help-inline"><?php echo $passwordError;?></span>
						<?php endif;?>
					</div>
				</div>
			  
				<div class="control-group <?php echo !empty($pictureError)?'error':'';?>">
					<label class="control-label">Picture</label>
					<div class="controls">
						<input type="hidden" name="MAX_FILE_SIZE" value="16000000">
						<input name="userfile" type="file" id="userfile" value="<?php echo !empty($fname)?$fname:'';?>">
						<?php if (!empty($pictureError)): ?>
							<span class="help-inline"><?php echo $pictureError;?></span>
						<?php endif;?>
						
					</div>
				</div>
			  
				<div class="form-actions">
					<button type="submit" class="btn btn-success">Confirm</button>
					<a class="btn" href="login.php">Back</a>
				</div>
				
			</form>
			
		</div> <!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->
  </body>
</html>

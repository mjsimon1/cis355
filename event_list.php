<?php 
session_start();
if(!isset($_SESSION["playerid"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');   // go to login page
	exit;
}
$id = $_SESSION['playerid']; // for MyAssignments
$sessionid = $_SESSION['playerid'];
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
        
        <div style="padding-top:25px;" class="row">
        <p>
            <a href="event_create.php" class="btn btn-success">Create Event</a>
            <a href="player_list.php" class="btn btn-primary">View Players List</a>
        </p>
        </div>
    
        
            <div class="row">
                <h3>Tee Times</h3>
            </div>
            <div class="row">
               
                <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Date</th>
                          <th>Time</th>
                          <th>Location</th>
                          <th>Description</th>
                          <th>Lead First Name</th>
                          <th>Lead Email</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                       include 'database.php';
                       $pdo = Database::connect();
                       $sql = 'SELECT * FROM event 
						LEFT JOIN players ON players.id = event.eventplayerid 
						ORDER BY eventdate ASC, eventtime ASC, lname ASC;';
                       foreach ($pdo->query($sql) as $row) {
                                echo '<tr>';
                                echo '<td>'. $row['eventdate'] . '</td>';
                                echo '<td>'. $row['eventtime'] . '</td>';
                                echo '<td>'. $row['eventlocation'] . '</td>';
                                echo '<td>'. $row['eventdescription'] . '</td>';
                                echo '<td>'. $row['fname'] . '</td>';
                                echo '<td>'. $row['email'] . '</td>';
                                echo '<td width=250>';
                                
                                echo '<a class="btn" href="event_members.php?id='.$row[0].'">Members</a>';
                                echo ' ';
                                
                                if($id == $row['eventplayerid']){
                                    echo '<a class="btn btn-success" href="event_update.php?id='.$row[0].'">Update</a>';
                                    echo ' ';
                                    echo '<a class="btn btn-danger" href="event_delete.php?id='.$row[0].'">Delete</a>';
                                }
                                if($id != $row['eventplayerid'])
                                {
                                    echo '<a class="btn btn-primary" href="event_join.php?id='.$row[0].'">Join the group</a>';
                                }
                                echo '</td>';
                                echo '</tr>';
                       }
                       Database::disconnect();
                      ?>
                      </tbody>
                </table>
        </div>
		
    	

    </div> <!-- end div: class="container" -->
	
</body>
</html>
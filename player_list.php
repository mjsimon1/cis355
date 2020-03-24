<?php 
session_start();
if(!isset($_SESSION["playerid"])){ 
	session_destroy();
	header('Location: login.php');   
	exit;
}
$id = $_SESSION['playerid']; 
$sessionid = $_SESSION['playerid'];
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
        
        <div style="padding-top:25px;" class="row">
        <p>
            <a href="event_list.php" class="btn btn-success">Back</a>
        </p>
        </div>
	    
	    <div class="row">
                <h3>Player List</h3>
            </div>
            <div class="row">
                 
                <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Profile Picture</th>
                          <th>First Name</th>
                          <th>Last Name</th>
                          <th>Email Address</th>
                          <th>Mobile Number</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                       include 'database.php';
                       $pdo = Database::connect();
                       $sql = 'SELECT * FROM players ORDER BY id DESC';
                       foreach ($pdo->query($sql) as $row) {
                                echo '<tr>';
                                echo '<td><img width=100 src="data:image/jpeg;base64,'. base64_encode( $row['filecontent']).'"/> </td>'; 
                                echo '<td>'. $row['fname'] . '</td>';
                                echo '<td>'. $row['lname'] . '</td>';
                                echo '<td>'. $row['email'] . '</td>';
                                echo '<td>'. $row['mobile'] . '</td>';
                                echo '<td width=225>';
                                if($id == $row['id']){
                                    echo '<a class="btn btn-success" href="player_update.php?id='.$row['id'].'">Update</a>';
                                    echo ' ';
                                    echo '<a class="btn btn-danger" href="player_delete.php?id='.$row['id'].'">Delete</a>';
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
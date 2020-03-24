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
    
    $id = null;
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
    
    if ( null==$id ) {
        header("Location: event_list.php");
    } else {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        # get assignment details
        $sql = "SELECT * FROM teetimes
        LEFT JOIN players ON players.id = teetimes.playerid";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetchAll();
      

        # get event details
        $sql = "SELECT * FROM event where id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $eventdata = $q->fetch(PDO::FETCH_ASSOC);
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
                        <h3>Event Details</h3>
                    </div>
                     <div class="form-horizontal" >
                     
                      <div class="control-group">
                        <label class="control-label">Event Location:</label>
                        <div class="controls">
                            <label class="checkbox">
                                <?php echo ($eventdata['eventlocation']);?>
                            </label>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label">Event Date:</label>
                        <div class="controls">
                            <label class="checkbox">
                                <?php echo ($eventdata['eventdate']);?>
                            </label>
                        </div>
                      </div>
                       <div class="control-group">
                        <label class="control-label">Event Description:</label>
                        <div class="controls">
                            <label class="checkbox">
                                <?php echo ($eventdata['eventdescription']);?>
                            </label>
                        </div>
            <div class="row">
                <h3>Player List</h3>
            </div>
            <div class="row">
                 
                <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Profile Pic</th>
                          <th>First Name</th>
                          <th>Last Name</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                       foreach ($data as $p) {
                           if($p['eventid'] == $id){
                                echo '<tr>';
                                echo '<td><img width=100 src="data:image/jpeg;base64,'. base64_encode( $p['filecontent']).'"/> </td>'; 
                                echo '<td>'. $p['fname'] . '</td>';
                                echo '<td>'. $p['lname'] . '</td>';
                                echo '</tr>';
                           }
                       }
                       //Database::disconnect();
                      ?>
                      </tbody>
                </table>
        </div>
                      </div>
                        <div class="form-actions">
                          <a class="btn" href="event_list.php">Back</a>
                       </div>
                         </div>
                         
                     
                 
                </div>
                 
    </div> <!-- /container -->
  </body>
</html>
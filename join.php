<?php
session_start();

require_once("headers/mysql.php");

$ID = empty($_SESSION['ID'])?"":intval($_SESSION['ID']);
$sql = mysql_query("SELECT * FROM users WHERE id='" . $ID . "'");
$user = mysql_fetch_array($sql);

// Kick out anyone who's not logged in.
if(empty($ID) || empty($user)) {
  header('Location: index.php'); }

$submission = empty($_POST['submission'])?"":stripslashes($_POST['submission']);

if($submission == "yes")
{
  $desc = addslashes($_POST['desc']);
  $date = stripslashes($_POST['date']);
  
  // Prevent injection
  if(!get_magic_quotes_gpc())
  {
    $desc = $desc;  
    $date = mysql_real_escape_string($date);
  }
  
  // Check the date
  list($mm,$dd,$yyyy) = explode('/', $date);
  if (!checkdate($mm,$dd,$yyyy)) {
      $d_error = true;
  }
  else {
    $date = $yyyy . "-" . $mm . "-" . $dd;
  }
  
  $sql = mysql_query("SELECT * FROM posts WHERE u_id='" . $ID . "' AND date='" . $date . "'");
  $check = mysql_num_rows($sql);
  
  if(!empty($check)) {
    $warning_message = "A workout for this day already exists!"; }
  else if(strlen($desc) < 20) {
    $warning_message = "Your workout description needs at least 20 characters."; }
  else if($d_error) {
    $warning_message = "Hmm. Were you messing with my code? Something is wrong with the date format."; }
  else {
    // Add workout
    mysql_query("INSERT INTO posts (u_id, text, date) VALUES ('$ID', '$desc', '$date')");
    $w_id = mysql_insert_id();
    header('Location: index.php?alert=workout_success&highlight=' . $w_id . '#post_' . $w_id);
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <title>Workout Log</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-datetimepicker.min.js"></script>
    </script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
  </head>
  <body>
  <nav class="navbar navbar-default" role="navigation">
  <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">Workout Log</a>
        </div>
    
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Log</a></li>
            <li><a href="create.php">New Entry</a></li>
            <li><a href="workouts.php">Workouts</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="active"><a href="join.php">Join Group</a></li>
            <li><a href="logout.php">Log Out</a></li>
            <li><a href="signup.php">Sign Up</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </div>
    </nav>
    <?php if(!empty($warning_message)) {?>
    <div class="alert alert-warning alert-dismissable" style=" margin-top: -20px; text-align: center">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <strong><?php echo $warning_message; ?></strong>
    </div>
    <?php } ?>
    <div class="container-fluid">
      <h1 style="text-align: center">Join Group</h1><br /><br />
        <form class="form-horizontal" role="form" method="post" action="login.php">
          <div class="form-group" id="g_gname">
            <label for="gname" class="col-md-offset-3 col-md-2 control-label">Group Name</label>
            <div class="col-md-2">
              <select class="form-control" id="gname" name="gname">
              <?php
                $groups = mysql_fetch_array(mysql_query("SELECT id AND name FROM groups"));
                foreach ($groups as $group) {
                  echo "<option value=\"" . $group['id'] . "\">" . $group['name'] . "</option>";
                }
              ?>
              </select>
            </div>
          </div>
          <div class="form-group" id="g_pword">
            <label for="pword" class="col-md-offset-3 col-md-2 control-label">Group Key</label>
            <div class="col-md-2">
              <input type="password" class="form-control" id="pword" name="pword">
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-offset-5 col-md-2" style="text-align: center">
              <input type="hidden" id="submission" name="submission" value="yes">
              <button type="submit" class="btn btn-success">Join</button>
            </div>
          </div>
        </form>
    </div>
  </body>
</html>
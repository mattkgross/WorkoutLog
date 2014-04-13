<?php
session_start();

require_once("headers/mysql.php");

$user = empty($_SESSION['USER'])?"":$_SESSION['USER'];
$group = empty($_SESSION['GROUP'])?"":$_SESSION['GROUP'];
$admin = empty($_SESSION['G_ADMIN'])?false:$_SESSION['G_ADMIN'];

// Kick out anyone who's not logged in.
if(empty($user)) {
  header('Location: index.php'); }

$submission = empty($_POST['submission'])?"":stripslashes($_POST['submission']);

if($submission == "yes")
{
  $name = addslashes($_POST['gname']);
  $key = md5($_POST['pword']);
  $key_c = md5($_POST['pword_c']);
  
  // Prevent injection
  if(!get_magic_quotes_gpc())
  {
    $name = $name;
  }  
 
  $sql = mysql_query("SELECT * FROM groups WHERE name='" . $name . "'");
  $check = mysql_num_rows($sql);
  
  if(!empty($check)) {
    $warning_message = "A group with this name already exists!"; }
  else if($key != $key_c) {
    $warning_message = "Your enrollment keys do not match!"; }
  else if(strlen($_POST['pword']) < 7) {
    $warning_message = "Please enter an enrollment key."; }
  else {
    // Add group
    mysql_query("INSERT INTO groups (name, enroll_key) VALUES ('$name', '$key')");
    $w_id = mysql_insert_id();
    // Join group
    $ID = $user['id'];
    mysql_query("INSERT INTO user_groups (u_id, g_id, admin) VALUES ('$ID', '$w_id', '1')");
    $sql = mysql_query("SELECT * FROM groups WHERE id='". $sel . "'");
    $_SESSION['GROUP'] = mysql_fetch_array($sql);
    $_SESSION['G_ADMIN'] = true;
    header('Location: index.php');
  }
}
?>
<!--
Workout Log
===========

Developer: Matthew Gross
Email: mattkgross@gmail.com
Website: http://www.mattkgross.com
Repository: https://github.com/mattkgross/WorkoutLogger
Disclaimer: The author of this tool is not responsible for any misuse, loss, or distribution of data or information associated with this site.
This product is provided AS IS and, by using this software, the site owner agrees to all responsibility resulting from any actions taken when using this service.
Rights: This software is openly distributed and may be used, altered, and redistributed so long as credit remains given where it is due.

=================================================================================================================================================================
-->
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
            <li><a href="team.php">Team</a></li>
            <li class="dropdown">
            <?php
              $ID = empty($user)?0:$user['id'];
              $sql = mysql_query("SELECT id,name FROM groups WHERE id IN (SELECT g_id FROM user_groups WHERE u_id = '" . $ID . "')");
              $groups = array();
              while($temp = mysql_fetch_array($sql)) {
                array_push($groups, $temp); }
              //print_r($groups);
            ?>
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo (!empty($group))?$group['name']:"No Group"; ?> <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <?php
                if(!empty($groups))
              {
                  foreach ($groups as $g) {
                    echo "<li><a href=\"switch.php?g=" . $g['id'] . "\">" . $g['name'] . "</a></li>";
                  }
                }
                ?>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown active">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Groups <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="join.php">Join Group</a></li>
                <li><a href="group.php">Create Group</a></li>
                <?php if($admin) {echo "<li><a href=\"admin.php\">Manage Group</a></li>";} ?>
              </ul>
            </li>
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
      <h1 style="text-align: center">Create Group</h1><br /><br />
        <form class="form-horizontal" role="form" method="post" action="group.php">
          <div class="form-group" id="g_gname">
            <label for="gname" class="col-md-offset-3 col-md-2 control-label">Group Name</label>
            <div class="col-md-2">
              <input type="text" class="form-control" id="gname" name="gname">
            </div>
          </div>
          <div class="form-group" id="g_pword">
            <label for="pword" class="col-md-offset-3 col-md-2 control-label">Group Key</label>
            <div class="col-md-2">
              <input type="password" class="form-control" id="pword" name="pword">
            </div>
          </div>
          <div class="form-group" id="g_pword_c">
            <label for="pword_c" class="col-md-offset-3 col-md-2 control-label">Confirm Group Key</label>
            <div class="col-md-2">
              <input type="password" class="form-control" id="pword_c" name="pword_c">
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-offset-5 col-md-2" style="text-align: center">
              <input type="hidden" id="submission" name="submission" value="yes">
              <button type="submit" class="btn btn-success">Create</button>
            </div>
          </div>
        </form>
    </div>
  </body>
</html>
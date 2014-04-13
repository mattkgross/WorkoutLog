<?php
session_start();

require_once("headers/mysql.php");

$user = empty($_SESSION['USER'])?"":$_SESSION['USER'];
$group = empty($_SESSION['GROUP'])?"":$_SESSION['GROUP'];

// Kick out anyone who's not logged in.
if(empty($user)) {
	header('Location: index.php'); }
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
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

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
            <li class="active"><a href="team.php">Team</a></li>
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
          	<li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Groups <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="join.php">Join Group</a></li>
                <li><a href="group.php">Create Group</a></li>
              </ul>
            </li>
            <li><a href="logout.php">Log Out</a></li>
            <li><a href="signup.php">Sign Up</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </div>
    </nav>
	<div class="container-fluid">
    	<div class="row">
        	<div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">News</h3>
                  </div>
                  <div class="panel-body">
                    Got this thing working for mobile, too. Access it from wherever.
                  </div>
                </div>
            </div>
        </div>
    
		<div class="row">
        	<div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">Prep Phase</h3>
                  </div>
                  <div class="panel-body">
                    <strong>Instructions:</strong> Look at the <a href="workouts/Prep_Phase_Scheduling.pdf" target="_blank">Schedule</a> pdf for guidance on how to plan your workouts. For instructional guidance with regards to form and technique, send BZ or a captain an email to schedule a training session.
                  </div>
                  <div class="panel-footer">
                    <ul class="list-inline">
                    	<li><a href="workouts/Prep_Phase_Scheduling.pdf" target="_blank">Schedule</a></li> |
                        <li><a href="workouts/Prep_Phase_Conditioning.pdf" target="_blank">Conditioning</a></li> |
                        <li><a href="workouts/Prep_Phase_SAQ.pdf" target="_blank">SAQ</a></li> |
                        <li><a href="workouts/Prep_Phase_Core_Circuits.pdf" target="_blank">Core Circuits</a></li> |
                        <li><a href="workouts/Prep_Phase_Strength_Day_1.pdf" target="_blank">Strength 1</a></li> |
                        <li><a href="workouts/Prep_Phase_Strength_Day_1_Coaching_Cues.pdf" target="_blank">Strength 1 - Cues</a></li> |
                        <li><a href="workouts/Prep_Phase_Strength_Day_2.pdf" target="_blank">Strength 2</a></li> |
                        <li><a href="workouts/Prep_Phase_Strength_Day_2_Coaching_Cues.pdf" target="_blank">Strength 2 - Cues</a></li>
                    </ul>
                  </div>
                </div>
            </div>
        </div>
        
        <div class="row">
        	<div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">Circuit Training</h3>
                  </div>
                  <div class="panel-body">
                    <strong>Instructions:</strong> Perform each circuit 3 times (3 sets). Between each set of the circuit, leave time for recovery (about one minute of rest). For the ladder exercises, refer to <a href="#v_1">Video 1</a> &amp; <a href="#v_2">Video 2</a>.
                  </div>
                  <div class="panel-footer">
                    <ul class="list-inline">
                    	<li><a href="workouts/Circuit_1.pdf" target="_blank">Circuit 1</a></li> |
                        <li><a href="workouts/Circuit_2.pdf" target="_blank">Circuit 2</a></li> |
                        <li><a href="workouts/Circuit_3.pdf" target="_blank">Circuit 3</a></li>
                    </ul>
                  </div>
                </div>
            </div>
        </div>
        
        <div class="row">
        	<div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">Videos</h3>
                  </div>
                  <div class="panel-body">
                  	<a name="v_1"></a>
                    <strong>Video 1:</strong><br /><br />
                    <iframe width="420" height="315" src="//www.youtube.com/embed/5bisvx72HuY" frameborder="0" allowfullscreen></iframe><br /><br />
                    
                    <a name="v_2"></a>
                    <strong>Video 2:</strong><br /><br />
                    <iframe width="560" height="315" src="//www.youtube.com/embed/e6IT6nOhWiU" frameborder="0" allowfullscreen></iframe>
                  </div>
                </div>
            </div>
        </div>
        
        
    </div>
  </body>
</html>
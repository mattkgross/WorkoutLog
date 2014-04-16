<?php
session_start();

require_once("headers/mysql.php");

$user = empty($_SESSION['USER'])?"":$_SESSION['USER'];
$group = empty($_SESSION['GROUP'])?"":$_SESSION['GROUP'];
$admin = empty($_SESSION['G_ADMIN'])?false:$_SESSION['G_ADMIN'];

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

Copyright (C) 2014  Matthew Gross

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.

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

    <style type="text/css">
    .news_content {
      font-family: Palatino, "Palatino LT STD", "Palatino Linotype", "Book Antiqua", Georgia, serif;
    }
    </style>
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
              	if(!empty($groups)) {
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
	<div class="container-fluid">

    <div id="content">
      <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
          <li class="active"><a href="#news" data-toggle="tab">News</a></li>
          <li><a href="#workouts" data-toggle="tab">Workouts</a></li>
          <li><a href="#plays" data-toggle="tab">Plays</a></li>
          <li><a href="#videos" data-toggle="tab">Videos</a></li>
      </ul>
      <div id="my-tab-content" class="tab-content">
        <div class="tab-pane fade in active" id="news">
          <h1 style="text-align: center;">News</h1><br/>
          <p>
              <?php
              $sql = mysql_query("SELECT * FROM news where g_id='" . $group['id'] . "' ORDER BY date DESC");
              $newsItems = array();
              while($temp = mysql_fetch_array($sql)) {
                array_push($newsItems, $temp);
              }
              
              if (!empty($newsItems)) {
                foreach ($newsItems as $news) {
              ?>
              <div class="row news_content">
                <div class="col-md-offset-2 col-md-8">
                  <?php
                  echo "<h2>" . stripslashes($news['title']) . "</h2>";
                  echo "<br/><blockquote><p>" . stripslashes($news['text']) . "</p><footer>" . date('F jS, Y - g:i A',strtotime($news['date'])) . "</footer></blockquote>";
                  ?>
                  <br/><hr/><br/>
                </div>
              </div>
              <?php 
                }
              }
              else {
                echo "This group has no news items to display.";
              }                  
              ?>
          </p>
        </div>        

        <div class="tab-pane fade" id="workouts">
            <h1 style="text-align: center;">Workouts</h1><br/>
            <p>
                <?php
                $sql = mysql_query("SELECT * FROM workouts where g_id='" . $group['id'] . "' ORDER BY date DESC");
                $workoutItems = array();
                while($temp = mysql_fetch_array($sql)) {
                  array_push($workoutItems, $temp); 
                }

                if (!empty($workoutItems)) {
                  foreach ($workoutItems as $workout) {
                 ?>
                <div class="row">
                  <div class="col-md-offset-2 col-md-8">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <?php
                        echo "<h3 class=\"panel-title\">" . $workout['title'] . "</h3>";
                        ?>
                      </div>                            
                      <?php echo "<div class=\"panel-body\">" . $workout['filepath'] . "</div>"; ?>
                    </div>
                  </div>
                </div>
                <?php 
                  }
                }
                else {
                  echo "This group has no workouts to display.";
                }
                ?>
            </p>
        </div>

        <div class="tab-pane fade" id="plays">
            <h1 style="text-align: center;">Plays</h1><br/>
            <p>
                <?php
                $sql = mysql_query("SELECT * FROM plays where g_id='" . $group['id'] . "' ORDER BY date DESC");
                $playItems = array();
                while($temp = mysql_fetch_array($sql)) {
                  array_push($playItems, $temp); 
                }

                if (!empty($playItems)) {
                  foreach ($playItems as $play) {
                ?>
                <div class="row">
                  <div class="col-md-offset-2 col-md-8">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <?php
                        echo "<h3 class=\"panel-title\">" . $play['title'] . "</h3>";
                        ?>
                      </div>                            
                      <?php echo "<div class=\"panel-body\">" . $play['filepath'] . "</div>"; ?>         
                    </div>
                  </div>
                </div>
                <?php 
                  }
                }
                else {
                  echo "This group has no videos to display.";
                }
                ?>
            </p>
        </div>

        <div class="tab-pane fade" id="videos">
          <h1 style="text-align: center;">Videos</h1><br/>
          <p>
              <?php
              $sql = mysql_query("SELECT * FROM videos where g_id='" . $group['id'] . "' ORDER BY date DESC");
              $videoItems = array();
              while($temp = mysql_fetch_array($sql)) {
                array_push($videoItems, $temp); 
              }
              
              if (!empty($videoItems)) {
                foreach ($videoItems as $video) {
              ?>
              <div class="row">
                <div class="col-md-offset-2 col-md-8">
                  <div class="well">
                    <?php
                    echo "<h3>" . $video['title'] . "</h3>";
                    echo "<div class=\"text-center\"><iframe width=\"420\" height=\"315\" src=\"" . $video['filepath'] . "\" frameborder=\"0\" allowfullscreen></iframe></div><br /><br />Link: <a href=\"" . $video['filepath'] . "\" target=\"_blank\">" . $video['filepath'] . "</a>";
                    ?>
                  </div>
                </div>
              </div>
              <?php 
                }
              }
              else {
                echo "This group has no videos to display.";
              } 

              ?>
          </p>     
        </div>        
      </div>


      </div>        
    </div>
  </body>
</html>
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

    <?php
      $sql = mysql_query("SELECT * FROM news where g_id='" . $group['id'] . "' ORDER BY date DESC");
      $news_num = mysql_num_rows($sql);
      $newsItems = array();
      while($temp = mysql_fetch_array($sql)) {
        $temp['date'] = date('F jS, Y - g:i A',strtotime($temp['date']));
        array_push($newsItems, $temp);
      }

      $news_json = json_encode($newsItems);
      $news_max = ceil($news_num/5);
    ?>

    <script type="text/javascript">
    // News navigation
    $(document).ready(function(e) {
      var news = <?php echo $news_json; ?>;
      var news_c = <?php echo $news_num; ?>;
      var news_p = 0;
      var np_max = <?php echo $news_max; ?>;

      function displayNews() {
        for (var i = 5*news_p; i < 5*(news_p+1); i++) {
          var n_id = "#news" + ((i%5)+1).toString();
          if(news_c > i) {
            $(n_id + "title").text(news[i]['title']);
            $(n_id + "text").text(news[i]['text']);
            $(n_id + "date").text(news[i]['date']);
            $(n_id).attr("style", "display: block;");
          }
          else {
            $(n_id).attr("style", "display: none;");
          }
        }
      }

      displayNews();

      $("#newstabs").on('click', '#nav_newstab_back', function(e) {
        if(news_p > 0) {
          $("#newstab" + (news_p+1).toString()).attr("class", "");
          $("#newstab" + (--news_p+1).toString()).attr("class", "active");
          displayNews();
        }
      });
      $("#newstabs").on('click', '#nav_newstab_next', function(e) {
        if(news_p < np_max-1) {
          $("#newstab" + (news_p+1).toString()).attr("class", "");
          $("#newstab" + (++news_p+1).toString()).attr("class", "active");
          displayNews();
        }
      });
      $("#newstabs").on('click', '[id^="newstab"]', function(e) {
        $("#newstab" + (news_p+1).toString()).attr("class", "");
        $(this).attr("class", "active");
        news_p = parseInt((this.id).slice(-1))-1;
        displayNews();
      });

      // Video navigation
      /*$(document).ready(function(e) {
      var videos = <?php echo $videos_json; ?>;
      var videos_c = <?php echo $videos_num; ?>;
      var videos_p = 0;
      var vp_max = <?php echo $videos_max; ?>;

      function displayVideos() {
        for (var i = 5*videos_p; i < 5*(videos_p+1); i++) {
          var v_id = "#news" + ((i%5)+1).toString();
          if(news_c > i) {
            $(v_id + "title").text(videos[i]['title']);
            $(v_id + "text").text(videos[i]['text']);
            $(v_id + "date").text(videos[i]['date']);
            $(v_id).attr("style", "display: block;");
          }
          else {
            $(v_id).attr("style", "display: none;");
          }
        }
      }

      displayVideos();

      $("#newstabs").on('click', '#nav_newstab_back', function(e) {
        if(news_p > 0) {
          $("#newstab" + (news_p+1).toString()).attr("class", "");
          $("#newstab" + (--news_p+1).toString()).attr("class", "active");
          displayNews();
        }
      });
      $("#newstabs").on('click', '#nav_newstab_next', function(e) {
        if(news_p < vp_max-1) {
          $("#newstab" + (news_p+1).toString()).attr("class", "");
          $("#newstab" + (++news_p+1).toString()).attr("class", "active");
          displayNews();
        }
      });
      $("#newstabs").on('click', '[id^="newstab"]', function(e) {
        $("#newstab" + (news_p+1).toString()).attr("class", "");
        $(this).attr("class", "active");
        news_p = parseInt((this.id).slice(-1))-1;
        displayNews();
      });*/
    });
    </script>
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
            <div class="row news_content">
              <div class="col-md-offset-2 col-md-8">
                <div id="news1" style="display: none;">
                  <h2 id="news1title"></h2>
                  <br/><blockquote><p id="news1text"></p><footer id="news1date"></footer></blockquote>
                  <br/><hr/><br/>
                </div>
                <div id="news2" style="display: none;">
                  <h2 id="news2title"></h2>
                  <br/><blockquote><p id="news2text"></p><footer id="news2date"></footer></blockquote>
                  <br/><hr/><br/>
                </div>
                <div id="news3" style="display: none;">
                  <h2 id="news3title"></h2>
                  <br/><blockquote><p id="news3text"></p><footer id="news3date"></footer></blockquote>
                  <br/><hr/><br/>
                </div>
                <div id="news4" style="display: none;">
                  <h2 id="news4title"></h2>
                  <br/><blockquote><p id="news4text"></p><footer id="news4date"></footer></blockquote>
                  <br/><hr/><br/>
                </div>
                <div id="news5" style="display: none;">
                  <h2 id="news5title"></h2>
                  <br/><blockquote><p id="news5text"></p><footer id="news5date"></footer></blockquote>
                  <br/><hr/><br/>
                </div>
                <br/>
                <div class="text-center" id="newstabs">
                  <ul class="pagination">
                    <li id="nav_newstab_back"><a href="#">&laquo;</a></li>
                    <?php
                    for ($i = 1; $i <= $news_max; $i++) {
                      if($i == 1)
                        echo "<li class=\"active\" id=\"newstab1\"><a href=\"#\">1</a></li>";
                      else
                        echo "<li id=\"newstab" . $i . "\"><a href=\"#\">" . $i . "</a></li>";
                    }
                    ?>
                    <li id="nav_newstab_next"><a href="#">&raquo;</a></li>
                  </ul>
                </div>
              </div>
            </div>
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
                  <hr/>
                    <?php
                    echo "<h3>" . $video['title'] . "</h3>";
                    echo "<div class=\"text-center\"><iframe width=\"420\" height=\"315\" src=\"" . $video['filepath'] . "\" frameborder=\"0\" allowfullscreen></iframe></div><br /><br />Link: <a href=\"" . $video['filepath'] . "\" target=\"_blank\">" . $video['filepath'] . "</a>";
                    ?>
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
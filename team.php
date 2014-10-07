<?php
session_start();

require_once("headers/mysql.php");

$user = empty($_SESSION['USER'])?"":$_SESSION['USER'];
$group = empty($_SESSION['GROUP'])?"":$_SESSION['GROUP'];
$admin = empty($_SESSION['G_ADMIN'])?false:$_SESSION['G_ADMIN'];

// Kick out anyone who's not logged in or belonging to a group.
if(empty($user) || empty($group)) {
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
    .videos_content {

    }
    .workouts_content {

    }
    .plays_content {

    }
    .panel-heading {
      background-image: linear-gradient(#fcfcfc, #eee);
    }
    .news-del-icon {
      float: right;
      top: -25px;
      cursor: pointer;
    }
    .workout-del-icon {
      float: right;
      top: -15px;
      cursor: pointer;
    }
    .play-del-icon {
      float: right;
      top: -15px;
      cursor: pointer;
    }
    .video-del-icon {
      float: right;
      top: -25px;
      cursor: pointer;
    }
    </style>

    <?php
      // News 
      $sql = mysql_query("SELECT * FROM news WHERE g_id='" . $group['id'] . "' ORDER BY date DESC");
      $news_num = mysql_num_rows($sql);
      $newsItems = array();
      while($temp = mysql_fetch_array($sql)) {
        $temp['date'] = date('F jS, Y - g:i A',strtotime($temp['date']));
        array_push($newsItems, $temp);
      }

      $news_json = json_encode($newsItems);
      $news_max = ceil($news_num/5);

      // Videos
      $sql = mysql_query("SELECT * FROM videos WHERE g_id='" . $group['id'] . "' ORDER BY date DESC");
      $videos_num = mysql_num_rows($sql);
      $videosItems = array();
      while($temp = mysql_fetch_array($sql)) {
        $temp['date'] = date('F jS, Y - g:i A',strtotime($temp['date']));
        array_push($videosItems, $temp);
      }

      $videos_json = json_encode($videosItems);
      $videos_max = ceil($videos_num/5);

      // Workouts
      $sql = mysql_query("SELECT * FROM workouts WHERE g_id='" . $group['id'] . "' ORDER BY date DESC");
      $workouts_num = mysql_num_rows($sql);
      $workoutsItems = array();
      while($temp = mysql_fetch_array($sql)) {
        $temp['date'] = date('F jS, Y - g:i A',strtotime($temp['date']));
        $w_fs = array();
        $sql2 = mysql_query("SELECT name,filepath FROM workout_files WHERE w_id='" . $temp['id'] . "'");
        $count = 0;
        while($temp2 = mysql_fetch_array($sql2)) {
          array_push($w_fs, $temp2);
          $count++;
        }
        $temp['files'] = $w_fs;
        $temp['f_count'] = $count;
        array_push($workoutsItems, $temp);
      }

      $workouts_json = json_encode($workoutsItems);
      $workouts_max = ceil($workouts_num/5);

      // Plays
      $sql = mysql_query("SELECT * FROM plays WHERE g_id='" . $group['id'] . "' ORDER BY date DESC");
      $plays_num = mysql_num_rows($sql);
      $playsItems = array();
      while($temp = mysql_fetch_array($sql)) {
        $temp['date'] = date('F jS, Y - g:i A',strtotime($temp['date']));
        $p_fs = array();
        $sql2 = mysql_query("SELECT name,filepath FROM play_files WHERE p_id='" . $temp['id'] . "'");
        $count = 0;
        while($temp2 = mysql_fetch_array($sql2)) {
          array_push($p_fs, $temp2);
          $count++;
        }
        $temp['files'] = $p_fs;
        $temp['f_count'] = $count;
        array_push($playsItems, $temp);
      }

      $plays_json = json_encode($playsItems);
      $plays_max = ceil($plays_num/5);
    ?>

    <script type="text/javascript">
    // Initialize the Tooltips & Popovers
    $(function() {
        $('span[rel="tooltip"]').tooltip();
    });

    // AJAX Handler
    function sendAjax(req, body)
    {
      // Get group data via AJAX to PHP request
      if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp=new XMLHttpRequest();
      }
      else {
        // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }

      xmlhttp.onreadystatechange=function() {
          if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            console.log(xmlhttp.responseText);
            //return xmlhttp.responseText;
          }
      }
      xmlhttp.open("POST","manage.php",true);
      xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      xmlhttp.send("req="+req+"&body="+body);
    }

    $(document).ready(function(e) {
      // News Deletion
      $('.news-del-icon').on('click', function(e) {
        sendAjax('d-news', $(this).attr('n_id'));
        location.reload(true);
      });
      // Workouts Deletion
      $('.workout-del-icon').on('click', function(e) {
        sendAjax('d-workout', $(this).attr('w_id'));
        location.reload(true);
      });
      // Plays Deletion
      $('.play-del-icon').on('click', function(e) {
        sendAjax('d-play', $(this).attr('p_id'));
        location.reload(true);
      });
      // Videos Deletion
      $('.video-del-icon').on('click', function(e) {
        sendAjax('d-video', $(this).attr('v_id'));
        location.reload(true);
      });

      // News navigation
      var news = <?php echo $news_json; ?>;
      var news_c = <?php echo $news_num; ?>;
      var news_p = 0;
      var np_max = <?php echo $news_max; ?>;

      function displayNews() {
        for (var i = 5*news_p; i < 5*(news_p+1); i++) {
          var n_id = "#news" + ((i%5)+1).toString();
          if(news_c > i) {
            $(n_id + "del").attr("n_id", news[i]['id']);
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
      var videos = <?php echo $videos_json; ?>;
      var videos_c = <?php echo $videos_num; ?>;
      var videos_p = 0;
      var vp_max = <?php echo $videos_max; ?>;

      function displayVideos() {
        for (var i = 5*videos_p; i < 5*(videos_p+1); i++) {
          var v_id = "#videos" + ((i%5)+1).toString();
          if(videos_c > i) {
            $(v_id + "del").attr("v_id", videos[i]['id']);
            $(v_id + "title").text(videos[i]['title']);
            $(v_id + "src").attr("src", videos[i]['filepath']);
            $(v_id + "link").attr("href", videos[i]['filepath']);
            $(v_id + "link").text(videos[i]['filepath']);
            $(v_id + "date").text(videos[i]['date']);
            $(v_id).attr("style", "display: block;");
          }
          else {
            $(v_id).attr("style", "display: none;");
          }
        }
      }

      displayVideos();

      $("#videostabs").on('click', '#nav_videostab_back', function(e) {
        if(videos_p > 0) {
          $("#videostab" + (videos_p+1).toString()).attr("class", "");
          $("#videostab" + (--videos_p+1).toString()).attr("class", "active");
          displayVideos();
        }
      });
      $("#videostabs").on('click', '#nav_videostab_next', function(e) {
        if(videos_p < vp_max-1) {
          $("#videostab" + (videos_p+1).toString()).attr("class", "");
          $("#videostab" + (++videos_p+1).toString()).attr("class", "active");
          displayVideos();
        }
      });
      $("#videostabs").on('click', '[id^="videostab"]', function(e) {
        $("#videostab" + (videos_p+1).toString()).attr("class", "");
        $(this).attr("class", "active");
        videos_p = parseInt((this.id).slice(-1))-1;
        displayVideos();
      });

      // Workouts Navigation
      var workouts = <?php echo $workouts_json; ?>;
      var workouts_c = <?php echo $workouts_num; ?>;
      var workouts_p = 0;
      var wp_max = <?php echo $workouts_max; ?>;

      function displayWorkouts() {
        for (var i = 5*workouts_p; i < 5*(workouts_p+1); i++) {
          var w_id = "#workouts" + ((i%5)+1).toString();
          if(workouts_c > i) {
            $(w_id + "del").attr("w_id", workouts[i]['id']);
            $(w_id + "title").text(workouts[i]['title']);
            $(w_id + "text").text(workouts[i]['text']);
            var f_count = parseInt(workouts[i]['f_count']);
            $(w_id + "files").html('');
            for(var j = 0; j < f_count; j++) {
              $(w_id + "files").append("<a href=\"" + workouts[i]['files'][j]['filepath'].slice(2) + "\" target=\"_blank\">" + workouts[i]['files'][j]['name'] + "</a>");
              if(j+1 < f_count) {
                $(w_id + "files").append("&ensp;|&ensp;");
              }
            }
            $(w_id + "date").text(workouts[i]['date']);
            $(w_id).attr("style", "display: block;");
          }
          else {
            $(w_id).attr("style", "display: none;");
          }
        }
      }

      displayWorkouts();

      $("#workoutstabs").on('click', '#nav_workoutstab_back', function(e) {
        if(workouts_p > 0) {
          $("#workoutstab" + (workouts_p+1).toString()).attr("class", "");
          $("#workoutstab" + (--workouts_p+1).toString()).attr("class", "active");
          displayWorkouts();
        }
      });
      $("#workoutstabs").on('click', '#nav_workoutstab_next', function(e) {
        if(workouts_p < wp_max-1) {
          $("#workoutstab" + (workouts_p+1).toString()).attr("class", "");
          $("#workoutstab" + (++workouts_p+1).toString()).attr("class", "active");
          displayWorkouts();
        }
      });
      $("#workoutstabs").on('click', '[id^="workoutstab"]', function(e) {
        $("#workoutstab" + (workouts_p+1).toString()).attr("class", "");
        $(this).attr("class", "active");
        workouts_p = parseInt((this.id).slice(-1))-1;
        displayWorkouts();
      });

      // Plays Navigation
      var plays = <?php echo $plays_json; ?>;
      var plays_c = <?php echo $plays_num; ?>;
      var plays_p = 0;
      var pp_max = <?php echo $plays_max; ?>;

      function displayPlays() {
        for (var i = 5*plays_p; i < 5*(plays_p+1); i++) {
          var p_id = "#plays" + ((i%5)+1).toString();
          if(plays_c > i) {
            $(p_id + "del").attr("p_id", plays[i]['id']);
            $(p_id + "title").text(plays[i]['title']);
            $(p_id + "text").text(plays[i]['text']);
            var f_count = parseInt(plays[i]['f_count']);
            $(p_id + "files").html('');
            for(var j = 0; j < f_count; j++) {
              $(p_id + "files").append("<a href=\"" + plays[i]['files'][j]['filepath'].slice(2) + "\" target=\"_blank\">" + plays[i]['files'][j]['name'] + "</a>");
              if(j+1 < f_count) {
                $(p_id + "files").append("&ensp;|&ensp;");
              }
            }
            $(p_id + "date").text(plays[i]['date']);
            $(p_id).attr("style", "display: block;");
          }
          else {
            $(p_id).attr("style", "display: none;");
          }
        }
      }

      displayPlays();

      $("#playstabs").on('click', '#nav_playstab_back', function(e) {
        if(plays_p > 0) {
          $("#playstab" + (plays_p+1).toString()).attr("class", "");
          $("#playstab" + (--plays_p+1).toString()).attr("class", "active");
          displayPlays();
        }
      });
      $("#playstabs").on('click', '#nav_playstab_next', function(e) {
        if(plays_p < pp_max-1) {
          $("#playstab" + (plays_p+1).toString()).attr("class", "");
          $("#playstab" + (++plays_p+1).toString()).attr("class", "active");
          displayPlays();
        }
      });
      $("#playstabs").on('click', '[id^="playstab"]', function(e) {
        $("#playstab" + (plays_p+1).toString()).attr("class", "");
        $(this).attr("class", "active");
        plays_p = parseInt((this.id).slice(-1))-1;
        displayPlays();
      });
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
                  <h2 id="news1title"></h2><?php if($admin) {echo "<span class=\"glyphicon glyphicon-remove news-del-icon\" id=\"news1del\" n_id=\"\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-container=\"body\" title=\"Delete News Item\"></span>";} ?>
                  <br/><blockquote><p id="news1text"></p><footer id="news1date"></footer></blockquote>
                  <br/><hr/><br/>
                </div>
                <div id="news2" style="display: none;">
                  <h2 id="news2title"></h2><?php if($admin) {echo "<span class=\"glyphicon glyphicon-remove news-del-icon\" id=\"news2del\" n_id=\"\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-container=\"body\" title=\"Delete News Item\"></span>";} ?>
                  <br/><blockquote><p id="news2text"></p><footer id="news2date"></footer></blockquote>
                  <br/><hr/><br/>
                </div>
                <div id="news3" style="display: none;">
                  <h2 id="news3title"></h2><?php if($admin) {echo "<span class=\"glyphicon glyphicon-remove news-del-icon\" id=\"news3del\" n_id=\"\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-container=\"body\" title=\"Delete News Item\"></span>";} ?>
                  <br/><blockquote><p id="news3text"></p><footer id="news3date"></footer></blockquote>
                  <br/><hr/><br/>
                </div>
                <div id="news4" style="display: none;">
                  <h2 id="news4title"></h2><?php if($admin) {echo "<span class=\"glyphicon glyphicon-remove news-del-icon\" id=\"news4del\" n_id=\"\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-container=\"body\" title=\"Delete News Item\"></span>";} ?>
                  <br/><blockquote><p id="news4text"></p><footer id="news4date"></footer></blockquote>
                  <br/><hr/><br/>
                </div>
                <div id="news5" style="display: none;">
                  <h2 id="news5title"></h2><?php if($admin) {echo "<span class=\"glyphicon glyphicon-remove news-del-icon\" id=\"news5del\" n_id=\"\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-container=\"body\" title=\"Delete News Item\"></span>";} ?>
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
                <div class="row workouts_content">
                  <div class="col-md-offset-2 col-md-8">
                    <div class="panel panel-default" id="workouts1" style="display: none;">
                      <div class="panel-heading">
                        <h3 class="panel-title" id="workouts1title"></h3><?php if($admin) {echo "<span class=\"glyphicon glyphicon-remove workout-del-icon\" id=\"workouts1del\" w_id=\"\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-container=\"body\" title=\"Delete Workout Item\"></span>";} ?>
                      </div>                            
                      <div class="panel-body">
                        <p id="workouts1text"></p><br/>
                        <p id="workouts1files"></p>
                        &ensp;&ndash; <small id="workouts1date"></small>
                      </div>
                    </div>
                    <div class="panel panel-default" id="workouts2" style="display: none;">
                      <div class="panel-heading">
                        <h3 class="panel-title" id="workouts2title"></h3><?php if($admin) {echo "<span class=\"glyphicon glyphicon-remove workout-del-icon\" id=\"workouts2del\" w_id=\"\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-container=\"body\" title=\"Delete Workout Item\"></span>";} ?>
                      </div>                            
                      <div class="panel-body">
                        <p id="workouts2text"></p><br/>
                        <p id="workouts2files"></p>
                        &ensp;&ndash; <small id="workouts2date"></small>
                      </div>
                    </div>
                    <div class="panel panel-default" id="workouts3" style="display: none;">
                      <div class="panel-heading">
                        <h3 class="panel-title" id="workouts3title"></h3><?php if($admin) {echo "<span class=\"glyphicon glyphicon-remove workout-del-icon\" id=\"workouts3del\" w_id=\"\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-container=\"body\" title=\"Delete Workout Item\"></span>";} ?>
                      </div>                            
                      <div class="panel-body">
                        <p id="workouts3text"></p><br/>
                        <p id="workouts3files"></p>
                        &ensp;&ndash; <small id="workouts3date"></small>
                      </div>
                    </div>
                    <div class="panel panel-default" id="workouts4" style="display: none;">
                      <div class="panel-heading">
                        <h3 class="panel-title" id="workouts4title"></h3><?php if($admin) {echo "<span class=\"glyphicon glyphicon-remove workout-del-icon\" id=\"workouts4del\" w_id=\"\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-container=\"body\" title=\"Delete Workout Item\"></span>";} ?>
                      </div>                            
                      <div class="panel-body">
                        <p id="workouts4text"></p><br/>
                        <p id="workouts4files"></p>
                        &ensp;&ndash; <small id="workouts4date"></small>
                      </div>
                    </div>
                    <div class="panel panel-default" id="workouts5" style="display: none;">
                      <div class="panel-heading">
                        <h3 class="panel-title" id="workouts5title"></h3><?php if($admin) {echo "<span class=\"glyphicon glyphicon-remove workout-del-icon\" id=\"workouts5del\" w_id=\"\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-container=\"body\" title=\"Delete Workout Item\"></span>";} ?>
                      </div>                            
                      <div class="panel-body">
                        <p id="workouts5text"></p><br/>
                        <p id="workouts5files"></p>
                        &ensp;&ndash; <small id="workouts5date"></small>
                      </div>
                    </div>
                    <br/>
                    <div class="text-center" id="workoutstabs">
                      <ul class="pagination">
                        <li id="nav_workoutstab_back"><a href="#">&laquo;</a></li>
                        <?php
                        for ($i = 1; $i <= $workouts_max; $i++) {
                          if($i == 1)
                            echo "<li class=\"active\" id=\"workoutstab1\"><a href=\"#\">1</a></li>";
                          else
                            echo "<li id=\"workoutstab" . $i . "\"><a href=\"#\">" . $i . "</a></li>";
                        }
                        ?>
                        <li id="nav_workoutstab_next"><a href="#">&raquo;</a></li>
                      </ul>
                    </div>
                  </div>
                </div>
            </p>
        </div>

        <div class="tab-pane fade" id="plays">
            <h1 style="text-align: center;">Plays</h1><br/>
            <p>
                <div class="row plays_content">
                  <div class="col-md-offset-2 col-md-8">
                    <div class="panel panel-default" id="plays1" style="display: none;">
                      <div class="panel-heading">
                        <h3 class="panel-title" id="plays1title"></h3><?php if($admin) {echo "<span class=\"glyphicon glyphicon-remove play-del-icon\" id=\"plays1del\" p_id=\"\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-container=\"body\" title=\"Delete Play Item\"></span>";} ?>
                      </div>                            
                      <div class="panel-body">
                        <p id="plays1text"></p><br/>
                        <p id="plays1files"></p>
                        &ensp;&ndash; <small id="plays1date"></small>
                      </div>
                    </div>
                    <div class="panel panel-default" id="plays2" style="display: none;">
                      <div class="panel-heading">
                        <h3 class="panel-title" id="plays2title"></h3><?php if($admin) {echo "<span class=\"glyphicon glyphicon-remove play-del-icon\" id=\"plays2del\" p_id=\"\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-container=\"body\" title=\"Delete Play Item\"></span>";} ?>
                      </div>                            
                      <div class="panel-body">
                        <p id="plays2text"></p><br/>
                        <p id="plays2files"></p>
                        &ensp;&ndash; <small id="plays2date"></small>
                      </div>
                    </div>
                    <div class="panel panel-default" id="plays3" style="display: none;">
                      <div class="panel-heading">
                        <h3 class="panel-title" id="plays3title"></h3><?php if($admin) {echo "<span class=\"glyphicon glyphicon-remove play-del-icon\" id=\"plays3del\" p_id=\"\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-container=\"body\" title=\"Delete Play Item\"></span>";} ?>
                      </div>                            
                      <div class="panel-body">
                        <p id="plays3text"></p><br/>
                        <p id="plays3files"></p>
                        &ensp;&ndash; <small id="plays3date"></small>
                      </div>
                    </div>
                    <div class="panel panel-default" id="plays4" style="display: none;">
                      <div class="panel-heading">
                        <h3 class="panel-title" id="plays4title"></h3><?php if($admin) {echo "<span class=\"glyphicon glyphicon-remove play-del-icon\" id=\"plays4del\" p_id=\"\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-container=\"body\" title=\"Delete Play Item\"></span>";} ?>
                      </div>                            
                      <div class="panel-body">
                        <p id="plays4text"></p><br/>
                        <p id="plays4files"></p>
                        &ensp;&ndash; <small id="plays4date"></small>
                      </div>
                    </div>
                    <div class="panel panel-default" id="plays5" style="display: none;">
                      <div class="panel-heading">
                        <h3 class="panel-title" id="plays5title"></h3><?php if($admin) {echo "<span class=\"glyphicon glyphicon-remove play-del-icon\" id=\"plays5del\" p_id=\"\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-container=\"body\" title=\"Delete Play Item\"></span>";} ?>
                      </div>                            
                      <div class="panel-body">
                        <p id="plays5text"></p><br/>
                        <p id="plays5files"></p>
                        &ensp;&ndash; <small id="plays5date"></small>
                      </div>
                    </div>
                    <br/>
                    <div class="text-center" id="playstabs">
                      <ul class="pagination">
                        <li id="nav_playstab_back"><a href="#">&laquo;</a></li>
                        <?php
                        for ($i = 1; $i <= $workouts_max; $i++) {
                          if($i == 1)
                            echo "<li class=\"active\" id=\"playstab1\"><a href=\"#\">1</a></li>";
                          else
                            echo "<li id=\"playstab" . $i . "\"><a href=\"#\">" . $i . "</a></li>";
                        }
                        ?>
                        <li id="nav_playstab_next"><a href="#">&raquo;</a></li>
                      </ul>
                    </div>
                  </div>
                </div>
            </p>
        </div>

        <div class="tab-pane fade" id="videos">
          <h1 style="text-align: center;">Videos</h1><br/>
          <p>
            <div class="row videos_content">
              <div class="col-md-offset-2 col-md-8">
                <div id="videos1" style="display: none;">
                  <h2 id="videos1title"></h2><?php if($admin) {echo "<span class=\"glyphicon glyphicon-remove video-del-icon\" id=\"videos1del\" v_id=\"\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-container=\"body\" title=\"Delete Video Item\"></span>";} ?>
                  <br/><blockquote><div class="text-center"><iframe id="videos1src" width="420" height="315" frameborder="0" allowfullscreen></iframe></div><br /><br />Link: <a id="videos1link" target="_blank"></a><footer id="videos1date"></footer></blockquote>
                  <br/><hr/><br/>
                </div>
                <div id="videos2" style="display: none;">
                  <h2 id="videos2title"></h2><?php if($admin) {echo "<span class=\"glyphicon glyphicon-remove video-del-icon\" id=\"videos2del\" v_id=\"\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-container=\"body\" title=\"Delete Video Item\"></span>";} ?>
                  <br/><blockquote><div class="text-center"><iframe id="videos2src" width="420" height="315" frameborder="0" allowfullscreen></iframe></div><br /><br />Link: <a id="videos2link" target="_blank"></a><footer id="videos2date"></footer></blockquote>
                  <br/><hr/><br/>
                </div>
                <div id="videos3" style="display: none;">
                  <h2 id="videos3title"></h2><?php if($admin) {echo "<span class=\"glyphicon glyphicon-remove video-del-icon\" id=\"videos3del\" v_id=\"\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-container=\"body\" title=\"Delete Video Item\"></span>";} ?>
                  <br/><blockquote><div class="text-center"><iframe id="videos3src" width="420" height="315" frameborder="0" allowfullscreen></iframe></div><br /><br />Link: <a id="videos3link" target="_blank"></a><footer id="videos3date"></footer></blockquote>
                  <br/><hr/><br/>
                </div>
                <div id="videos4" style="display: none;">
                  <h2 id="videos4title"></h2><?php if($admin) {echo "<span class=\"glyphicon glyphicon-remove video-del-icon\" id=\"videos4del\" v_id=\"\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-container=\"body\" title=\"Delete Video Item\"></span>";} ?>
                  <br/><blockquote><div class="text-center"><iframe id="videos4src" width="420" height="315" frameborder="0" allowfullscreen></iframe></div><br /><br />Link: <a id="videos4link" target="_blank"></a><footer id="videos4date"></footer></blockquote>
                  <br/><hr/><br/>
                </div>
                <div id="videos5" style="display: none;">
                  <h2 id="videos5title"></h2><?php if($admin) {echo "<span class=\"glyphicon glyphicon-remove video-del-icon\" id=\"videos5del\" v_id=\"\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-container=\"body\" title=\"Delete Video Item\"></span>";} ?>
                  <br/><blockquote><div class="text-center"><iframe id="videos5src" width="420" height="315" frameborder="0" allowfullscreen></iframe></div><br /><br />Link: <a id="videos5link" target="_blank"></a><footer id="videos5date"></footer></blockquote>
                  <br/><hr/><br/>
                </div>
                <br/>
                <div class="text-center" id="videostabs">
                  <ul class="pagination">
                    <li id="nav_videostab_back"><a href="#">&laquo;</a></li>
                    <?php
                    for ($i = 1; $i <= $videos_max; $i++) {
                      if($i == 1)
                        echo "<li class=\"active\" id=\"videostab1\"><a href=\"#\">1</a></li>";
                      else
                        echo "<li id=\"videostab" . $i . "\"><a href=\"#\">" . $i . "</a></li>";
                    }
                    ?>
                    <li id="nav_videostab_next"><a href="#">&raquo;</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </p>
     
        </div>        
      </div>


      </div>        
    </div>
  </body>
</html>
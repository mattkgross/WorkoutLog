<?php
session_start();

require_once("headers/mysql.php");

$user = empty($_SESSION['USER'])?"":$_SESSION['USER'];
$group = empty($_SESSION['GROUP'])?"":$_SESSION['GROUP'];

// Check every time to be extra sure they are admin
$sql = mysql_query("SELECT admin FROM user_groups WHERE u_id='" . $user['id'] . "' AND g_id='" . $group['id'] . "'");
$temp = mysql_fetch_array($sql);
$_SESSION['G_ADMIN'] = (intval($temp['admin']==1))?true:false;
$admin = $_SESSION['G_ADMIN'];

// Kick out anyone who's not logged in or an admin.
if(empty($user) || !$admin) {
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
    <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="css/graph.css" rel="stylesheet">
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-datetimepicker.min.js"></script>
    <script src="js/graph.js"></script>
    </script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style type="text/css">
	.del-member {
		color: red;
	}
	.admin-member {
		color: green;
	}
	.n-admin-member {
		color: black;
	}

	.feedback {
		margin-top: -22px;
		text-align: center;
		width: 100%;
	}
	.load_bar {
		height: 34px;
		width: 112px;
		font-size: 14px;
		margin-right: auto;
		margin-left: auto;
		display: none;
	}
	.dangerzone {
		color: #df3e3e;
		background-image: linear-gradient(#fcfcfc, #eee);
	}
    </style>
    
    <script type="text/javascript">
    // Initialize the Tooltips & Popovers
	$(function() {
    	$('a[rel="tooltip"]').tooltip();
	});

	// Close alert
	$(document).ready(function(e) {
		$("#feedback").hide();

		$("#feedback").on('click', '#feedback_btn', function(e) {
			$("#feedback").hide();
		})
	});

	// Populate Analytics
	function populateGraph(analyticData) {
        //$('#g_t').text(analyticData['city']);
        for(var i = 1; i < 11; i++) {
            $('#t' + i.toString()).text(analyticData[i-1]['name']);
            $('#v' + i.toString()).text(analyticData[i-1]['count']);
        }
    };
    <?php 
    	$analytics_users = array();

    	function week_start($date) {
	    	$ts = strtotime($date);
	    	$start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
	    	return date('Y-m-d', $start);
		}
		$d_start = strtotime("0 days 00:00:00", strtotime(week_start(date('m/d/Y h:i:s a', time()))));
		$d_end = strtotime("6 days 00:00:00", strtotime(week_start(date('m/d/Y h:i:s a', time()))));
		$d_start = date("Y", $d_start) . "-" . date("m", $d_start) . "-" . date("d", $d_start);
		$d_end = date("Y", $d_end) . "-" . date("m", $d_end) . "-" . date("d", $d_end);

		// Grab all user ids of posts made to the group this week
    	$sql = mysql_query("SELECT u_id, COUNT(u_id) AS num FROM posts WHERE id IN (SELECT p_id FROM post_groups WHERE g_id='" . $group['id'] . "') AND date >= '" . $d_start . "' AND date <= '" . $d_end . "' GROUP BY u_id ORDER BY COUNT(u_id) DESC LIMIT 10");
    	while($temp = mysql_fetch_array($sql)) {
    		array_push($analytics_users, array("name" => $temp['u_id'], "count" => $temp['num']));
    	}
    ?>
	$(document).ready(function(e) {
		var users = <?php echo json_encode($analytics_users); ?>;
		populateGraph(users);
	});

	// Busy Waiting Modal Actions
	function busyWaiting(content) {
		$('#busy_modal_text').text(content);
		$('#busy_modal').modal({
			backdrop: "static",
			keyboard: false
		});
	}
	function busyWaitingStop() {
		$('#busy_modal_text').text('');
		$('#busy_modal').modal('hide');
	}

    // AJAX Handler
    function sendAjax(req, body, freeze)
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
		    	responseHandle(xmlhttp.responseText);
		    	// Unfreeze the freeze element
		    	if(typeof freeze !== 'undefined') {
					$(freeze).removeAttr("disabled");
					//console.log(freeze + " re-enabled!");
				}
		    }
		}
		// If a freeze element is provided, freeze it
		if(typeof freeze !== 'undefined') {
			$(freeze).attr("disabled", "disabled");
			//console.log(freeze + " disabled!");
		}

		xmlhttp.open("POST","manage.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("req="+req+"&body="+body);
	}

	// Response Handler
	function responseHandle(resp)
	{
		// News
		if(resp == "Added news post!") {
			$("#feedback").attr("class", "alert alert-success alert-dismissable in fade feedback");
			$("#feedback_text").text(resp);
			$("#feedback").show();
		}
		else if(resp == "Groups did not match in the news post request!") {
			$("#feedback").attr("class", "alert alert-danger alert-dismissable in fade feedback");
			$("#feedback_text").text(resp);
			$("#feedback").show();
		}
		else if(resp == "Your post must have a title and a body!") {
			$("#feedback").attr("class", "alert alert-danger alert-dismissable in fade feedback");
			$("#feedback_text").text(resp);
			$("#feedback").show();
		}
		// Videos
		else if(resp == "Added video post!") {
			$("#feedback").attr("class", "alert alert-success alert-dismissable in fade feedback");
			$("#feedback_text").text(resp);
			$("#feedback").show();
		}
		else if(resp == "Groups did not match in the video post request!") {
			$("#feedback").attr("class", "alert alert-danger alert-dismissable in fade feedback");
			$("#feedback_text").text(resp);
			$("#feedback").show();
		}
		else if(resp == "Your post must have a title and a link!") {
			$("#feedback").attr("class", "alert alert-danger alert-dismissable in fade feedback");
			$("#feedback_text").text(resp);
			$("#feedback").show();
		}
		// Danger Zone
		else if(resp == "Deleted all news posts!") {
			busyWaitingStop();
			$("#feedback").attr("class", "alert alert-warning alert-dismissable in fade feedback");
			$("#feedback_text").text(resp);
			$("#feedback").show();
		}
		else if(resp == "Deleted all workout posts!") {
			busyWaitingStop();
			$("#feedback").attr("class", "alert alert-warning alert-dismissable in fade feedback");
			$("#feedback_text").text(resp);
			$("#feedback").show();
		}
		else if(resp == "Deleted all play posts!") {
			busyWaitingStop();
			$("#feedback").attr("class", "alert alert-warning alert-dismissable in fade feedback");
			$("#feedback_text").text(resp);
			$("#feedback").show();
		}
		else if(resp == "Deleted all video posts!") {
			busyWaitingStop();
			$("#feedback").attr("class", "alert alert-warning alert-dismissable in fade feedback");
			$("#feedback_text").text(resp);
			$("#feedback").show();
		}
		else if(resp == "Group terminated.") {
			// Reload page from server, not cache
			location.reload(true);
		}
		else {
			// For good measure...
		}
	}

	$(document).ready(function(e) {
		// Upload alerts
		<?php
		if(isset($_SESSION['message'])) {
			$mess = $_SESSION['message'];
			unset($_SESSION['message']);

			if($mess == "Workout successfully posted!") {
				echo "$(\"#feedback\").attr(\"class\", \"alert alert-success alert-dismissable in fade feedback\");
				$(\"#feedback_text\").text(\"" . $mess . "\");
				$(\"#feedback\").show();";
			}
			else if($mess == "Play successfully posted!") {
				echo "$(\"#feedback\").attr(\"class\", \"alert alert-success alert-dismissable in fade feedback\");
				$(\"#feedback_text\").text(\"" . $mess . "\");
				$(\"#feedback\").show();";
			}
			else {
				echo "$(\"#feedback\").attr(\"class\", \"alert alert-danger alert-dismissable in fade feedback\");
				$(\"#feedback_text\").text(\"" . $mess . "\");
				$(\"#feedback\").show();";
			}
		}
		?>

		// Danger Zone
		$('.dangerzone').hover(
			function() {
				$(this).css("color", "#ffffff");
				$(this).css("background-image", "linear-gradient(#f97171, #df3e3e)");
			},
			function() {
				$(this).css("color", "#df3e3e");
				$(this).css("background-image", "linear-gradient(#fcfcfc, #eee)");
			}
		);
		$('#table2').on('click', '#clear_news', function(e) {
			if (confirm("Are you sure you want to delete all news posts?")) {
				// Make 'em wait
				busyWaiting("Please wait while all news posts are deleted. This may take a while depending on how many posts you've made.");
		    	sendAjax("c-n", null, "clear_news");
		    }
		});
		$('#table2').on('click', '#clear_workouts', function(e) {
			if (confirm("Are you sure you want to delete all workout posts and files?")) {
				busyWaiting("Please wait while all workout posts are deleted. This may take a while depending on how many posts you've made.");
		    	sendAjax("c-w", null, "clear_workouts");
		    }
		});
		$('#table2').on('click', '#clear_plays', function(e) {
			if (confirm("Are you sure you want to delete all play posts and files?")) {
				busyWaiting("Please wait while all play posts are deleted. This may take a while depending on how many posts you've made.");
		    	sendAjax("c-p", null, "clear_plays");
		    }
		});
		$('#table2').on('click', '#clear_videos', function(e) {
			if (confirm("Are you sure you want to delete all video posts?")) {
				busyWaiting("Please wait while all video posts are deleted. This may take a while depending on how many posts you've made.");
		    	sendAjax("c-v", null, "clear_videos");
		    }
		});
		$('#table2').on('click', '#delete_group', function(e) {
			if (confirm("Are you sure you want to delete this group?")) {
				// Make 'em wait
				busyWaiting("Please wait while the group is deleted. This may take a while depending on the size of your group.");
				sendAjax("d-g", null, "delete_group");
		    }
		});

		// Member Management
		$('#table1').on('click', '.del-member', function(e) {
            var m_id = $(this).attr('member');
            var row = $(this).closest('tr');
            row.fadeOut(1000);
			sendAjax("del", m_id, null);
        });

    	// Admin Management
	    $('#table1').on('click', '.admin-member', function(e) {
	        var m_id = $(this).attr('member');
	        $(this).toggleClass('admin-member n-admin-member');
	        sendAjax("r-ad", m_id, null);
	    });  
	    $('#table1').on('click', '.n-admin-member', function(e) {
	        var m_id = $(this).attr('member');
	        $(this).toggleClass('n-admin-member admin-member');
	        sendAjax("a-ad", m_id, null);
	    });


		// Forms Management
	    // News
	    $('#news_form').on('click', '#news_btn', function(e) {
	    	var g_id = <?php echo $group['id']; ?>;
	    	var atitle = $("#atitle").val();
	    	var atext = $("#atext").val();
	        var m_id = {
	        	'g_id' : g_id,
	        	'title' : atitle,
	        	'text' : atext,
	        };
	        m_id = JSON.stringify(m_id);

	        sendAjax("news", m_id, "news_btn");

	        $("#atitle").val("");
	        $("#atext").val("");
	    });
	    // Videos
	    $('#video_form').on('click', '#video_btn', function(e) {
	    	var g_id = <?php echo $group['id']; ?>;
	    	var vtitle = $("#vtitle").val();
	    	var vlink = $("#vlink").val();
	        var m_id = {
	        	'g_id' : g_id,
	        	'title' : vtitle,
	        	'link' : vlink,
	        };
	        m_id = JSON.stringify(m_id);

	        sendAjax("video", m_id, "video_btn");

	        $("#vtitle").val("");
	        $("#vlink").val("");
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
                <li><a href="admin.php">Manage Group</a></li>
              </ul>
            </li>
            <li><a href="logout.php">Log Out</a></li>
            <li><a href="signup.php">Sign Up</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </div>
    </nav>
    <div class="alert alert-warning alert-dismissable in fade feedback" id="feedback">
      <button type="button" class="close" aria-hidden="true" id="feedback_btn">&times;</button>
      <strong><span id="feedback_text"></span></strong>
    </div>
    <!-- Small modal -->
	<div class="modal fade" id="busy_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-sm">
	    <div class="modal-content" style="text-align: center;">
	      <img src="img/circle_loading.gif" style="padding: 30px;"><br/>
	      <small id="busy_modal_text"></small>
	    </div>
	  </div>
	</div>
    <div class="container-fluid">
      <h1 style="text-align: center"><?php echo $group['name']; ?></h1><br /><br />
      <div class="row">
      	<div class="col-md-offset-1 col-md-3">
      		<div class="panel panel-default">
			  <div class="panel-heading" style="background-image: linear-gradient(#fcfcfc, #eee);">
			    <h3 class="panel-title"><strong>Members</strong></h3>
			  </div>
			  <div class="panel-body">
			  	  <div class="table-responsive">
				  <table class="table table-hover table-condensed" id="table1">
	                
							  <?php 
							  	$sql = mysql_query("SELECT u_id,admin FROM user_groups WHERE g_id='" . $group['id'] . "'");
							  	$user_admin = array();
							  	while($temp = mysql_fetch_array($sql)) {
							  		$user_admin[$temp['u_id']] = intval($temp['admin']);
							  	}

							  	$sql = mysql_query("SELECT id,f_name,l_name,u_name,email FROM users WHERE id IN (SELECT u_id FROM user_groups WHERE g_id='" . $group['id'] . "')");
							  	$members = array();
							  	while($temp = mysql_fetch_array($sql)) {
							  		array_push($members, $temp);
							  		echo "<tr style=\"vertical-align: middle;\">
	                    					<td style=\"text-align: left;\">";
							  		echo $temp['f_name'] . " " . $temp['l_name'];
							  		echo "</td>
	                    				  <td style=\"text-align: right;\">";
	                    			$m_admin = ($user_admin[$temp['id']]==1)?"admin-member":"n-admin-member";
	                    			echo "<a href=\"mailto:" . $temp['email'] . "\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"left\" data-container=\"body\" title=\"Email " . $temp['f_name'] . "\"><span class=\"glyphicon glyphicon-envelope\"></span></a>&emsp;<a href=\"#\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"top\" data-container=\"body\" title=\"Remove " . $temp['f_name'] . "\"><span class=\"glyphicon glyphicon-ban-circle del-member\" member=\"" . $temp['id'] . "\"></span></a>&emsp;<a href=\"#\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"Toggle Admin\" data-container=\"body\"><span class=\"glyphicon glyphicon-user " . $m_admin . "\" member=\"" . $temp['id'] . "\"></span></a>";
	                    			echo "</td></tr>";
							  	}
							  ?>
				  </table>
				  </div>
			  </div>
			</div>

			<div class="panel panel-default">
			  <div class="panel-heading" style="background-image: linear-gradient(#f97171, #df3e3e);">
			    <h3 class="panel-title" style="color: #ffffff;"><strong>Danger Zone</strong></h3>
			  </div>
			  <div class="panel-body">
			  	  <div class="table-responsive">
			  	  <table class="table table-condensed" id="table2" style="border: none;">
			  	  	<tr>
			  	  		<td style="text-align: left;"><h4>Clear News</h4><small>Deletes all news posts for the group.</small></td>
			  	  		<td style="text-align: center; vertical-align: middle;"><button class="btn btn-default btn-sm dangerzone" id="clear_news">Clear News</button></td>
			  	  	</tr>
			  	  	<tr>
			  	  		<td style="text-align: left;"><h4>Clear Workouts</h4><small>Deletes all workout posts <strong>and files</strong> for the group.</small></td>
			  	  		<td style="text-align: center; vertical-align: middle;"><button class="btn btn-default btn-sm dangerzone" id="clear_workouts">Clear Workouts</button></td>
			  	  	</tr>
			  	  	<tr>
			  	  		<td style="text-align: left;"><h4>Clear Plays</h4><small>Deletes all play posts <strong>and files</strong> for the group.</small></td>
			  	  		<td style="text-align: center; vertical-align: middle;"><button class="btn btn-default btn-sm dangerzone" id="clear_plays">Clear Plays</button></td>
			  	  	</tr>
			  	  	<tr>
			  	  		<td style="text-align: left;"><h4>Clear Videos</h4><small>Deletes all video posts for the group.</small></td>
			  	  		<td style="text-align: center; vertical-align: middle;"><button class="btn btn-default btn-sm dangerzone" id="clear_videos">Clear Videos</button></td>
			  	  	</tr>
			  	  	<tr>
			  	  		<td style="text-align: left;"><h4 style="color: #df3e3e;"><strong>Delete Group</strong></h4><small>Deletes the group and all associated posts and files.</small></td>
			  	  		<td style="text-align: center; vertical-align: middle;"><button class="btn btn-default btn-sm dangerzone" id="delete_group">Delete Group</button></td>
			  	  	</tr>
			  	  </table>
			  	  </div>
			  </div>
			</div>
      	</div>

      	<div class="col-md-7">
      		<div class="panel panel-default">
			  <div class="panel-heading" style="background-image: linear-gradient(#fcfcfc, #eee);">
			    <h3 class="panel-title"><strong>Dashboard</strong></h3>
			  </div>
			  <div class="panel-body">
			  	    <!-- Nav tabs -->
					<ul class="nav nav-pills">
					  <li class="active"><a href="#news" data-toggle="tab">News</a></li>
					  <li><a href="#workouts" data-toggle="tab">Workouts</a></li>
					  <li><a href="#plays" data-toggle="tab">Plays</a></li>
					  <li><a href="#videos" data-toggle="tab">Videos</a></li>
					  <li><a href="#analytics" data-toggle="tab">Analytics</a></li>
					</ul>

					<!-- Tab panes -->
					<div class="tab-content">
					  <div class="tab-pane active" id="news">

					  	<h3 style="text-align: center;">Create News Item</h3><br/>

					  	<form class="form-horizontal" role="form" id="news_form" method="post" onSubmit="return false;">
				          <div class="form-group" id="g_atitle">
				            <label for="atitle" class="col-md-offset-1 col-md-2 control-label">Article Title</label>
				            <div class="col-md-4">
				              <input type="text" class="form-control" id="atitle" name="atitle" placeholder="New Workouts Posted">
				            </div>
				          </div>
				          <div class="form-group" id="g_atext">
				            <label for="atext" class="col-md-offset-1 col-md-2 control-label">Body</label>
				            <div class="col-md-7">
				              <textarea class="form-control" id="atext" name="atext" rows="10" placeholder="Take a look at our videos page for new workout techniques."></textarea>
				            </div>
				          </div>
				          <div class="form-group">
				            <div class="col-md-offset-2 col-md-8" style="text-align: center">
				              <button class="btn btn-success" id="news_btn">Post News</button>
				            </div>
				          </div>
				        </form>
					  </div>

					  <div class="tab-pane" id="workouts">

					  	<h3 style="text-align: center;">Create A Workout</h3><br/>

			            <form class="form-horizontal" role="form" id="workout_form" method="post" action="upload.php" enctype="multipart/form-data" onSubmit="busyWaiting('Please wait while your workout and its files are posted.');">
		                  <div class="form-group" id="g_wtitle">
		                    <label for="wtitle" class="col-md-3 control-label">Workout Title</label>
		                    <div class="col-md-4">
		                      <input type="text" class="form-control" id="wtitle" name="wtitle" placeholder="Ladder Workout Manual">
		                    </div>
		                  </div>
		                  <div class="form-group" id="g_wtext">
				            <label for="wtext" class="col-md-3 control-label">Body</label>
				            <div class="col-md-7">
				              <textarea class="form-control" id="wtext" name="wtext" rows="10" placeholder="Follow the instructions of the pdf below to complete leg day."></textarea>
				            </div>
				          </div>
		                  <div class="form-group" id="g_wpdf">
		                    <label for="wpdf" class="col-md-3 control-label">File</label>
		                    <div class="col-md-4">
		                      <input type="file" class="form-control workout" name="wpdf[]" accept="application/pdf" multiple>
		                      <span style="text-align: center; font-style: italic;"><small>Note: Please updload only PDFs. Hold &quot;Ctrl&quot; to select multiple files.</small></span>
		                    </div>
		                  </div>
		                  <div class="form-group">
		                    <div class="col-md-offset-2 col-md-8" style="text-align: center">
		                      <input type="hidden" name="form_type" value="workout">
		                      <button class="btn btn-success" id="workout_btn">Post Workout</button>
		                    </div>
		                  </div>
		                </form>
					  </div>

					  <div class="tab-pane" id="plays">

						<h3 style="text-align: center;">Create A Play</h3><br/>

			            <form class="form-horizontal" role="form" id="play_form" method="post" action="upload.php" enctype="multipart/form-data" onSubmit="busyWaiting('Please wait while your play and its files are posted.');">
		                  <div class="form-group" id="g_ptitle">
		                    <label for="ptitle" class="col-md-3 control-label">Play Title</label>
		                    <div class="col-md-4">
		                      <input type="text" class="form-control" id="ptitle" name="ptitle" placeholder="Zone Defense">
		                    </div>
		                  </div>
		                  <div class="form-group" id="g_ptext">
				            <label for="ptext" class="col-md-3 control-label">Body</label>
				            <div class="col-md-7">
				              <textarea class="form-control" id="ptext" name="ptext" rows="10" placeholder="Make sure that the far man covers the corner. See pdf for more info and diagrams."></textarea>
				            </div>
				          </div>
		                  <div class="form-group" id="g_ppdf">
		                    <label for="ppdf" class="col-md-3 control-label">File</label>
		                    <div class="col-md-4">
		                      <input type="file" class="form-control play" name="ppdf[]" accept="application/pdf" multiple>
		                      <span style="text-align: center; font-style: italic;"><small>Note: Please updload only PDFs. Hold &quot;Ctrl&quot; to select multiple files.</small></span>
		                    </div>
		                  </div>
		                  <div class="form-group">
		                    <div class="col-md-offset-2 col-md-8" style="text-align: center">
		                      <input type="hidden" name="form_type" value="play">
		                      <button class="btn btn-success" id="workout_btn">Post Play</button>
		                    </div>
		                  </div>
		                </form>
					  </div>

					  <div class="tab-pane" id="videos">

					  <h3 style="text-align: center;">Create Video Link</h3><br/>

					  	<form class="form-horizontal" role="form" id="video_form" method="post" onSubmit="return false;">
				          <div class="form-group" id="g_vtitle">
				            <label for="vtitle" class="col-md-offset-1 col-md-2 control-label">Video Title</label>
				            <div class="col-md-4">
				              <input type="text" class="form-control" id="vtitle" name="vtitle" placeholder="Ladder Workout Demonstration">
				            </div>
				          </div>
				          <div class="form-group" id="g_vlink">
				            <label for="vlink" class="col-md-offset-1 col-md-2 control-label">Link</label>
				            <div class="col-md-7">
				              <input type="text" class="form-control" id="vlink" name="vlink" placeholder="http://www.youtube.com/embed/fhJasdjd7f">
				              <span style="text-align: center; font-style: italic;"><small>Note: Be sure to used the <strong>embed</strong> link for the video or it may not display properly.</small></span>
				            </div>
				          </div>
				          <div class="form-group">
				            <div class="col-md-offset-2 col-md-8" style="text-align: center">
				              <button class="btn btn-success" id="video_btn">Post Video</button>
				            </div>
				          </div>
				        </form>
					  </div>

					  <div class="tab-pane" id="analytics">
					  <h3 style="text-align: center;">Team Analytics</h3><br/>
					  	<div id="wrapper">
		                    <div class="chart">
		                        <h2 id="g_t"></h2>
		                        <table id="data-table" border="1" cellpadding="10" cellspacing="0" summary="The effects of the zombie outbreak on the populations of endangered species from 2012 to 2016">
		                            <caption>This Week's Workout Count</caption>
		                            <thead>
		                                <tr>
		                                    <td>&nbsp;</td>
		                                    <!--<th scope="col" id="t1"></th>
		                                    <th scope="col" id="t2"></th>
		                                    <th scope="col" id="t3"></th>
		                                    <th scope="col" id="t4"></th>
		                                    <th scope="col" id="t5"></th>
		                                    <th scope="col" id="t6"></th>
		                                    <th scope="col" id="t7"></th>
		                                    <th scope="col" id="t8"></th>
		                                    <th scope="col" id="t9"></th>
		                                    <th scope="col" id="t10"></th>-->
		                                </tr>
		                            </thead>
		                            <tbody>
		                                <tr>
		                                    <th scope="row" id="t1"></th>
		                                    <td id="v1"></td>
		                                </tr>
		                                <tr>
		                                    <th scope="row" id="t2"></th>
		                                    <td id="v2"></td>
		                                </tr>
		                                <tr>
		                                    <th scope="row" id="t3"></th>
		                                    <td id="v3"></td>
		                                </tr>
		                                <tr>
		                                    <th scope="row" id="t4"></th>
		                                    <td id="v4"></td>
		                                </tr>
		                                <tr>
		                                    <th scope="row" id="t5"></th>
		                                    <td id="v5"></td>
		                                </tr>
		                                <tr>
		                                    <th scope="row" id="t6"></th>
		                                    <td id="v6"></td>
		                                </tr>
		                                <tr>
		                                    <th scope="row" id="t7"></th>
		                                    <td id="v7"></td>
		                                </tr>
		                                <tr>
		                                    <th scope="row" id="t8"></th>
		                                    <td id="v8"></td>
		                                </tr>
		                                <tr>
		                                    <th scope="row" id="t9"></th>
		                                    <td id="v9"></td>
		                                </tr>
		                                <tr>
		                                    <th scope="row" id="t10"></th>
		                                    <td id="v10"></td>
		                                </tr>
		                            </tbody>
		                        </table>
		                    </div>
		                </div>
					  </div>
					</div>
			  </div>
			</div>
      	</div>
      </div>
  </body>
</html>
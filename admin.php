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
		    	responseHandle(xmlhttp.responseText);
		    }
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
		//Videos
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
		else {
			// For good measure...
		}
	}

	$(document).ready(function(e) {

		// Member Management
        $('.del-member').click(function(e) {
            var m_id = $(this).attr('member');
            var row = $(this).closest('tr');
            row.fadeOut(1000);
			sendAjax("del", m_id);
        });

    	// Admin Management
	    $('#table1').on('click', '.admin-member', function(e) {
	        var m_id = $(this).attr('member');
	        $(this).toggleClass('admin-member n-admin-member');
	        sendAjax("r-ad", m_id);
	    });  
	    $('#table1').on('click', '.n-admin-member', function(e) {
	        var m_id = $(this).attr('member');
	        $(this).toggleClass('n-admin-member admin-member');
	        sendAjax("a-ad", m_id);
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

	        sendAjax("news", m_id);

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

	        sendAjax("video", m_id);

	        $("#vtitle").val("");
	        $("#vlink").val("");
	    });

	    // Globals for files
	    var w_files;

	    $('input[type=file] .workout').on('click', function(event) {
	    	w_files = event.target.files;
	    });


	    // Workouts
	    $('#workout_form').on('click', '#workout_btn', function(e) {
	    	var g_id = <?php echo $group['id']; ?>;
	    	var wtitle = $("#wtitle").val();
	    	var wtext = $("#wtext").val();

	    	e.stopPropagation();
	    	e.preventDefault();

	    	$(this).attr("style", "display: none;");
	    	$("#workout_bar").attr("style", "display: inline-block;");

	    	var data = new FormData();
	    	$.each(w_files, function(key, value) {
				data.append(key, value);
			});


			// Submit
			$.ajax({
		        url: 'upload.php?files&pg=workout',
		        type: 'POST',
		        data: data,
		        cache: false,
		        dataType: 'json',
		        processData: false, // Don't process the files
		        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
		        success: function(data, textStatus, jqXHR)
		        {
		        	if(typeof data.error === 'undefined')
		        	{
		        		// Success so call function to process the form
		        		submitForm(event, data);
		        	}
		        	else
		        	{
		        		// Handle errors here
		        		console.log('ERRORS: ' + data.error);
		        	}
		        },
		        error: function(jqXHR, textStatus, errorThrown)
		        {
		        	// Handle errors here
		        	console.log('ERRORS: ' + textStatus);

		        	$("#workout_bar").attr("style", "display: none;");
		    		$(this).attr("style", "display: inline-block;");
		        }
		    });

	    });
	    // Plays
	    $('#play_form').on('click', '#play_btn', function(e) {
	    	var g_id = <?php echo $group['id']; ?>;
	    	var ptitle = $("#ptitle").val();
	    	var ptext = $("#ptext").val();

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
    <div class="container-fluid">
      <h1 style="text-align: center"><?php echo $group['name']; ?></h1><br /><br />
      <div class="row">
      	<div class="col-md-offset-1 col-md-3">
      		<div class="panel panel-default">
			  <div class="panel-heading">
			    <h3 class="panel-title">Members</h3>
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
      	</div>

      	<div class="col-md-7">
      		<div class="panel panel-default">
			  <div class="panel-heading">
			    <h3 class="panel-title">Dashboard</h3>
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

			            <form class="form-horizontal" role="form" id="workout_form" method="post" onSubmit="return false;">
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
		                      <input type="file" class="form-control workout" name="wpdf" accept="application/pdf" multiple>
		                      <span style="text-align: center; font-style: italic;"><small>Note: Please updload only PDFs. Hold &quot;Ctrl&quot; to select multiple files.</small></span>
		                    </div>
		                  </div>
		                  <div class="form-group">
		                    <div class="col-md-offset-2 col-md-8" style="text-align: center">
		                      <button class="btn btn-success" id="workout_btn">Post Workout</button>
		                      <div class="progress progress-striped active load_bar" id="workout_bar">
								<div class="progress-bar progress-bar-success"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
								  <span class="sr-only">Posting...</span>
								</div>
							  </div>
		                    </div>
		                  </div>
		                </form>
					  </div>

					  <div class="tab-pane" id="plays">

						<h3 style="text-align: center;">Create A Play</h3><br/>

			            <form class="form-horizontal" role="form" id="play_form" method="post" onSubmit="return false;">
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
		                      <input type="file" class="form-control play" name="ppdf" accept="application/pdf" multiple>
		                      <span style="text-align: center; font-style: italic;"><small>Note: Please updload only PDFs. Hold &quot;Ctrl&quot; to select multiple files.</small></span>
		                    </div>
		                  </div>
		                  <div class="form-group">
		                    <div class="col-md-offset-2 col-md-8" style="text-align: center">
		                      <button class="btn btn-success" id="play_btn">Post Workout</button>
		                      <div class="progress progress-striped active load_bar" id="play_bar">
								<div class="progress-bar progress-bar-success"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
								  <span class="sr-only">Posting...</span>
								</div>
							  </div>
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
					  	<p style="text-align: center;">Team Analytics will roll out in the next release.</p>
					  	<!--<div id="wrapper">
							<div class="chart">
								<h2>Population of endangered species from 2012 &ndash; 2016</h2>
								<table id="data-table" border="1" cellpadding="10" cellspacing="0" summary="The effects of the zombie outbreak on the populations of endangered species from 2012 to 2016">
									<caption>Population in thousands</caption>
									<thead>
										<tr>
											<td>&nbsp;</td>
											<th scope="col">2012</th>
											<th scope="col">2013</th>
											<th scope="col">2014</th>
											<th scope="col">2015</th>
											<th scope="col">2016</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th scope="row">Carbon Tiger</th>
											<td>4080</td>
											<td>6080</td>
											<td>6240</td>
											<td>3520</td>
											<td>2240</td>
										</tr>
										<tr>
											<th scope="row">Blue Monkey</th>
											<td>5680</td>
											<td>6880</td>
											<td>5760</td>
											<td>5120</td>
											<td>2640</td>
										</tr>
										<tr>
											<th scope="row">Tanned Zombie</th>
											<td>1040</td>
											<td>1760</td>
											<td>2880</td>
											<td>4720</td>
											<td>7520</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>-->
					  </div>
					</div>
			  </div>
			</div>
      	</div>
      </div>
    </div>
  </body>
</html>
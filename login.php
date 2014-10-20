<?php
session_start();

require_once("headers/mysql.php");

require("headers/salt.php");

$user = empty($_SESSION['USER'])?"":$_SESSION['USER'];

// Kick out anyone who's already logged in.
if(!empty($user)) {
	header('Location: index.php'); }

$submission = empty($_POST['submission'])?"":stripslashes($_POST['submission']);

if($submission == "yes")
{
	$uname = addslashes($_POST['uname']);
	$pword = md5($_POST['pword']);
  $remember = addslashes($_POST['remember']);
	
	// Prevent injection
	if(!get_magic_quotes_gpc())
	{
		$uname = mysql_real_escape_string($uname);
	}
	
	$sql = mysql_query("SELECT * FROM users WHERE u_name='" . $uname . "' OR email='" . $uname . "' LIMIT 1");
	$user = mysql_fetch_array($sql);
	
	if(!empty($user) && validate_password($pword, $user['password'])) {
		$_SESSION['USER'] = $user;
		$sql = mysql_query("SELECT g_id,admin FROM user_groups WHERE u_id='" . $user['id'] . "' LIMIT 1");
		$group = mysql_fetch_array($sql);
		if(!empty($group)) {
			$sql = mysql_query("SELECT * FROM groups WHERE id='" . intval($group['g_id']) . "'");
			$_SESSION['GROUP'] = mysql_fetch_array($sql);
			$_SESSION['G_ADMIN'] = (intval($group['admin']) == 1)?true:false;
		}

    // Remember for 30 days if opted.
    if($remember == "selected") {
      $params = session_get_cookie_params();
      setcookie(session_name(), $_COOKIE[session_name()], time() + 60*60*24*30, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }

		header('Location: index.php');
	}
	else {
		$warning_message = "Incorrect username and/or password. Try again.";
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
    
    
    <script type="text/javascript">
		$(document).ready(function(){			
			$("#uname").focusout(function(e) {
                if($("#uname").val().length > 0) {
					$("#g_uname").attr("class", "form-group has-success has-feedback");
					$("#s_uname_bad").attr("style", "display: none;");
					$("#s_uname_ok").attr("style", "display: inline-block;");}
				else {
					$("#g_uname").attr("class", "form-group has-error has-feedback");
					$("#s_uname_ok").attr("style", "display: none;");
					$("#s_uname_bad").attr("style", "display: inline-block;");}
            });
			
			$("#pword").focusout(function(e) {
                if($("#pword").val().length > 5) {
					$("#g_pword").attr("class", "form-group has-success has-feedback");
					$("#s_pword_bad").attr("style", "display: none;");
					$("#s_pword_ok").attr("style", "display: inline-block;");}
				else {
					$("#g_pword").attr("class", "form-group has-error has-feedback");
					$("#s_pword_ok").attr("style", "display: none;");
					$("#s_pword_bad").attr("style", "display: inline-block;");}
            });
		})
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
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">No Group <b class="caret"></b></a>
              <ul class="dropdown-menu">
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
            <li class="active"><a href="login.php">Sign In</a></li>
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
    <?php }	?>
	<div class="container-fluid">
    	<h1 style="text-align: center">Log In</h1><br /><br />
        <form class="form-horizontal" role="form" method="post" action="login.php">
          <div class="form-group" id="g_uname">
            <label for="uname" class="col-md-offset-3 col-md-2 control-label">Username/Email</label>
            <div class="col-md-2">
              <input type="text" class="form-control" id="uname" name="uname">
              <span class="glyphicon glyphicon-ok form-control-feedback" id="s_uname_ok" style="display: none;"></span>
              <span class="glyphicon glyphicon-remove form-control-feedback" id="s_uname_bad" style="display: none;"></span>
            </div>
          </div>
          <div class="form-group" id="g_pword">
            <label for="pword" class="col-md-offset-3 col-md-2 control-label">Password</label>
            <div class="col-md-2">
              <input type="password" class="form-control" id="pword" name="pword">
              <span class="glyphicon glyphicon-ok form-control-feedback" id="s_pword_ok" style="display: none;"></span>
              <span class="glyphicon glyphicon-remove form-control-feedback" id="s_pword_bad" style="display: none;"></span>
            </div>
          </div>
          <div class="form-group" id="g_remember">          
            <div class="col-md-offset-5 col-md-2">
              <label class="checkbox-inline">
                <input type="checkbox" class="form-control" style="height: 13px; width: 13px;" id="remember" name="remember" value="selected"> Remember Me
              </label>
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-offset-5 col-md-2" style="text-align: center">
              <input type="hidden" id="submission" name="submission" value="yes">
              <button type="submit" class="btn btn-success">Log In</button>
            </div>
          </div>
        </form>
    </div>
  </body>
</html>
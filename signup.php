<?php
session_start();

require_once("headers/mysql.php");

require("headers/salt.php");

$user = empty($_SESSION['USER'])?"":$_SESSION['USER'];
$group = empty($_SESSION['GROUP'])?"":$_SESSION['GROUP'];
$admin = empty($_SESSION['G_ADMIN'])?false:$_SESSION['G_ADMIN'];

// Kick out anyone who's already logged in.
if(!empty($user)) {
	header('Location: index.php'); }

$submission = empty($_POST['submission'])?"":stripslashes($_POST['submission']);

if($submission == "yes")
{
	$fname = addslashes($_POST['fname']);
	$lname = addslashes($_POST['lname']);
	$uname = addslashes($_POST['uname']);
	$email = addslashes($_POST['email']);
	$pword = $_POST['pword'];
	$p_len = strlen($pword);
	$pword = md5($pword);
	$pwordc = md5($_POST['pword_c']);
	
	// Prevent injection
	if(!get_magic_quotes_gpc())
	{
		$uname = mysql_real_escape_string($uname);	
		$fname = mysql_real_escape_string($fname);
		$lname = mysql_real_escape_string($lname);
		$email = mysql_real_escape_string($email);
	}
	
	$sql = mysql_query("SELECT * FROM users WHERE u_name='" . $uname . "'");
	$u_check = mysql_num_rows($sql);
	$sql = mysql_query("SELECT * FROM users WHERE email='" . $email . "'");
	$e_check = mysql_num_rows($sql);
	
	if(empty($fname)) {
		$error = "fname"; }
	else if(empty($lname)) {
		$error = "lname";
	}
	else if(empty($uname) || $u_check > 0) {
		$error = "uname";
	}
	else if(empty($email) || $e_check > 0 || !preg_match("/^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i", $email)) {
		$error = "email";
	}
	else if(empty($pword) || $pword != $pwordc || $p_len < 6) {
		$error = "pword";
	}
	else {
		// Salt the password
		$pword = create_hash($pword);

		// Sign them up and go to log page
		mysql_query("INSERT INTO users (f_name, l_name, email, u_name, password) VALUES ('$fname', '$lname', '$email', '$uname', '$pword')");
		$_SESSION['USER'] = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE id='" . mysql_insert_id() . "'"));
		header('Location: index.php');
	}
	
	if($error == "uname") {
		$warning_message = "That username is already taken. Please choose another."; }
	else if ($error == "pword") {
		$warning_message = "Insufficient password. Try another."; }
	else if ($error == "email") {
		$warning_message = "Is this an email? Are you sure? Have you used it with us before, perhaps?"; }
	else if ($error == "fname") {
		$warning_message = "We need to know who you are - throw us a first name."; }
	else if ($error == "lname") {
		$warning_message = "We need to know who you are - throw us a last name."; }
	else {
		$warning_message = "Oops! Something went wrong. Try again."; }
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
			$("#fname").focusout(function(e) {
                if($("#fname").val().length > 0) {
					$("#g_fname").attr("class", "form-group has-success has-feedback");
					$("#s_fname_bad").attr("style", "display: none;");
					$("#s_fname_ok").attr("style", "display: inline-block;");}
				else {
					$("#g_fname").attr("class", "form-group has-error has-feedback");
					$("#s_fname_ok").attr("style", "display: none;");
					$("#s_fname_bad").attr("style", "display: inline-block;");}
            });
			
			$("#lname").focusout(function(e) {
                if($("#lname").val().length > 0) {
					$("#g_lname").attr("class", "form-group has-success has-feedback");
					$("#s_lname_bad").attr("style", "display: none;");
					$("#s_lname_ok").attr("style", "display: inline-block;");}
				else {
					$("#g_lname").attr("class", "form-group has-error has-feedback");
					$("#s_lname_ok").attr("style", "display: none;");
					$("#s_lname_bad").attr("style", "display: inline-block;");}
            });
			
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
			
			$("#email").focusout(function(e) {
                if($("#email").val().length > 0) {
					$("#g_email").attr("class", "form-group has-success has-feedback");
					$("#s_email_bad").attr("style", "display: none;");
					$("#s_email_ok").attr("style", "display: inline-block;");}
				else {
					$("#g_email").attr("class", "form-group has-error has-feedback");
					$("#s_email_ok").attr("style", "display: none;");
					$("#s_email_bad").attr("style", "display: inline-block;");}
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
			
			$("#pword_c").focusout(function(e) {
                if($("#pword_c").val() == $("#pword").val()) {
					$("#g_pword_c").attr("class", "form-group has-success has-feedback");
					$("#s_pword_c_bad").attr("style", "display: none;");
					$("#s_pword_c_ok").attr("style", "display: inline-block;");}
				else {
					$("#g_pword_c").attr("class", "form-group has-error has-feedback");
					$("#s_pword_c_ok").attr("style", "display: none;");
					$("#s_pword_c_bad").attr("style", "display: inline-block;");}
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
            <li><a href="login.php">Sign In</a></li>
            <li class="active"><a href="signup.php">Sign Up</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </div>
    </nav>
    <?php if(!empty($error)) {?>
    <div class="alert alert-warning alert-dismissable" style=" margin-top: -20px; text-align: center">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <strong><?php echo $warning_message; ?></strong>
    </div>
    <?php }	?>
	<div class="container-fluid">
    	<h1 style="text-align: center">Sign Up</h1><br /><br />
        <form class="form-horizontal" role="form" method="post" action="signup.php">
          <div class="form-group" id="g_fname">
            <label for="fname" class="col-md-offset-2 col-md-2 control-label">First Name</label>
            <div class="col-md-4">
              <input type="text" class="form-control" id="fname" name="fname" placeholder="John">
              <span class="glyphicon glyphicon-ok form-control-feedback" id="s_fname_ok" style="display: none;"></span>
              <span class="glyphicon glyphicon-remove form-control-feedback" id="s_fname_bad" style="display: none;"></span>
            </div>
          </div>
          <div class="form-group" id="g_lname">
            <label for="lname" class="col-md-offset-2 col-md-2 control-label">Last Name</label>
            
            <div class="col-md-4">
              <input type="text" class="form-control" id="lname" name="lname" placeholder="Doe">
              <span class="glyphicon glyphicon-ok form-control-feedback" id="s_lname_ok" style="display: none;"></span>
              <span class="glyphicon glyphicon-remove form-control-feedback" id="s_lname_bad" style="display: none;"></span>
            </div>
          </div>
          <div class="form-group" id="g_uname">
            <label for="uname" class="col-md-offset-2 col-md-2 control-label">Username</label>
            <div class="col-md-4">
              <input type="text" class="form-control" id="uname" name="uname" placeholder="jdoe32">
              <span class="glyphicon glyphicon-ok form-control-feedback" id="s_uname_ok" style="display: none;"></span>
              <span class="glyphicon glyphicon-remove form-control-feedback" id="s_uname_bad" style="display: none;"></span>
            </div>
          </div>
          <div class="form-group" id="g_email">
            <label for="email" class="col-md-offset-2 col-md-2 control-label">Email</label>
            <div class="col-md-4">
              <input type="email" class="form-control" id="email" name="email" placeholder="jdoe32@gmail.com">
              <span class="glyphicon glyphicon-ok form-control-feedback" id="s_email_ok" style="display: none;"></span>
              <span class="glyphicon glyphicon-remove form-control-feedback" id="s_email_bad" style="display: none;"></span>
            </div>
          </div>
          <div class="form-group" id="g_pword">
            <label for="pword" class="col-md-offset-2 col-md-2 control-label">Password</label>
            <div class="col-md-4">
              <input type="password" class="form-control" id="pword" name="pword" placeholder="At Least 6 Characters - Be Smart">
              <span class="glyphicon glyphicon-ok form-control-feedback" id="s_pword_ok" style="display: none;"></span>
              <span class="glyphicon glyphicon-remove form-control-feedback" id="s_pword_bad" style="display: none;"></span>
            </div>
          </div>
          <div class="form-group" id="g_pword_c">
            <label for="pword_c" class="col-md-offset-2 col-md-2 control-label">Confirm Password</label>
            <div class="col-md-4">
              <input type="password" class="form-control" id="pword_c" name="pword_c" placeholder="Confirm Password">
              <span class="glyphicon glyphicon-ok form-control-feedback" id="s_pword_c_ok" style="display: none;"></span>
              <span class="glyphicon glyphicon-remove form-control-feedback" id="s_pword_c_bad" style="display: none;"></span>
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-offset-5 col-md-2" style="text-align: center">
              <input type="hidden" id="submission" name="submission" value="yes">
              <button type="submit" class="btn btn-success">Sign Up</button>&nbsp;&nbsp;&nbsp;&nbsp;
              <button type="reset" class="btn btn-danger">Reset</button>
            </div>
          </div>
        </form>
    </div>
  </body>
</html>
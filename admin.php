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
    </style>
    
    <script type="text/javascript">
    // Initialize the Tooltips & Popovers
	$(function() {
    	$('a[rel="tooltip"]').tooltip();
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
		    	return xmlhttp.responseText;
		    }
		}
		xmlhttp.open("POST","manage.php",false);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("req="+req+"&body="+body);
	}

	// Member Management
	$(document).ready(function(e) {
        $('.del-member').click(function(e) {
            var m_id = $(this).attr('member');
            var row = $(this).closest('tr');
            row.fadeOut(1000);
			      var res = sendAjax("del", m_id);
        });
    });

    // Admin Management
	$(document).ready(function(e) {
        $('.admin-member').click(function(e) {
            var m_id = $(this).attr('member');
            $(this).toggleClass('admin-member n-admin-member');
            var res = sendAjax("r-ad", m_id);
            console.log(res);

        });
    });
    $(document).ready(function(e) {
        $('.n-admin-member').click(function(e) {
            var m_id = $(this).attr('member');
            $(this).toggleClass('n-admin-member admin-member');
			      var res = sendAjax("a-ad", m_id);
            console.log(res);

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
    <?php if(!empty($warning_message)) {?>
    <div class="alert alert-warning alert-dismissable" style=" margin-top: -20px; text-align: center">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <strong><?php echo $warning_message; ?></strong>
    </div>
    <?php } ?>
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
	                    			echo "<a href=\"mailto:" . $temp['email'] . "\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"left\" data-container=\"body\" title=\"Email " . $temp['f_name'] . "\"><span class=\"glyphicon glyphicon-envelope\"></span></a>&emsp;<a href=\"#\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"top\" data-container=\"body\" title=\"Remove " . $temp['f_name'] . "\"><span class=\"glyphicon glyphicon-ban-circle del-member\" member=" . $temp['id'] . "></span></a>&emsp;<a href=\"#\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"Toggle Admin\" data-container=\"body\"><span class=\"glyphicon glyphicon-user " . $m_admin . "\" member=" . $temp['id'] . "></span></a>";
	                    			echo "</td></tr>";
							  	}
							  ?>
				  </table>
				  </div>
			  </div>
			</div>
      	</div>
      </div>
    </div>
  </body>
</html>
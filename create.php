<?php
session_start();

require_once("headers/mysql.php");

$user = empty($_SESSION['USER'])?"":$_SESSION['USER'];
$group = empty($_SESSION['GROUP'])?"":$_SESSION['GROUP'];

// Kick out anyone who's not logged in or in a group.
if(empty($user) || empty($group)) {
	header('Location: index.php'); }

$ID = empty($user)?0:$user['id'];
$sql = mysql_query("SELECT id,name FROM groups WHERE id IN (SELECT g_id FROM user_groups WHERE u_id = '" . $ID . "')");
$groups = array();
while($temp = mysql_fetch_array($sql)) {
  array_push($groups, $temp); }

$submission = empty($_POST['submission'])?"":stripslashes($_POST['submission']);

if($submission == "yes")
{
	$gs = $_POST['group[]'];
	$desc = addslashes($_POST['desc']);
	$date = stripslashes($_POST['date']);
	
	// Prevent injection
	if(!get_magic_quotes_gpc())
	{
		$desc = $desc;	
		$date = mysql_real_escape_string($date);
	}
	
	// Check the date
	list($mm,$dd,$yyyy) = explode('/', $date);
	if (!checkdate($mm,$dd,$yyyy)) {
			$d_error = true;
	}
	else {
		$date = $yyyy . "-" . $mm . "-" . $dd;
	}


	// from groups submitted, delete any that are not in $groups
	// Check to make sure a post has not been made to the same group on the same day	
	
	$sql = mysql_query("SELECT * FROM posts WHERE u_id='" . $user['id'] . "' AND date='" . $date . "'");
	$check = mysql_num_rows($sql);
	
	if(!empty($check)) {
		$warning_message = "A workout for this day already exists!"; }
	else if(strlen($desc) < 20) {
		$warning_message = "Your workout description needs at least 20 characters."; }
	else if($d_error) {
		$warning_message = "Hmm. Were you messing with my code? Something is wrong with the date format."; }
	else if(empty($gs)) {
		$warning_message = "You must add this workout to at least one group.";
	}
	else {
		// Add workout
		$ID = $user['id'];
		mysql_query("INSERT INTO posts (u_id, text, date) VALUES ('$ID', '$desc', '$date')");
		$w_id = mysql_insert_id();
		header('Location: index.php?alert=workout_success&highlight=' . $w_id . '#post_' . $w_id);
	}
}
?>
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
    
    
    <script type="text/javascript">
	
	// Initialize the Tooltips
	$(function() {
    	$("#cal_ico").tooltip();
	});
	
		$(document).ready(function(){
			$("#desc").focusout(function(e) {
                if($("#desc").val().length > 20) {
					$("#g_desc").attr("class", "form-group has-success has-feedback");
					$("#s_desc_bad").attr("style", "display: none;");
					$("#s_desc_ok").attr("style", "display: inline-block;");}
				else {
					$("#g_desc").attr("class", "form-group has-error has-feedback");
					$("#s_desc_ok").attr("style", "display: none;");
					$("#s_desc_bad").attr("style", "display: inline-block;");}
            });
			
			$("#desc").keyup(function(e) {
                if(e.which == 13) {
					$("#desc").val(function(i, val) {
						return val + "- ";
					});
				}
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
            <li class="active"><a href="create.php">New Entry</a></li>
            <li><a href="workouts.php">Workouts</a></li>
            <li class="dropdown">
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
    <?php if(!empty($warning_message)) {?>
    <div class="alert alert-warning alert-dismissable" style=" margin-top: -20px; text-align: center">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <strong><?php echo $warning_message; ?></strong>
    </div>
    <?php }	?>
	<div class="container-fluid">
    	<h1 style="text-align: center">Add Workout Entry</h1><br /><br />
        <form class="form-horizontal" role="form" method="post" action="create.php">
          <div class="form-group" id="g_desc">
            <label for="desc" class="col-md-offset-2 col-md-2 control-label">Workout Description</label>
            <div class="col-md-4">
              <textarea class="form-control" rows="5" id="desc" name="desc"><?php if(!empty($desc)) {echo $desc;} else {echo "- ";} ?></textarea>
              <span class="glyphicon glyphicon-ok form-control-feedback" id="s_desc_ok" style="display: none;"></span>
              <span class="glyphicon glyphicon-remove form-control-feedback" id="s_desc_bad" style="display: none;"></span>
            </div>
          </div>
          <div class="form-group" id="g_group">
          	<label for="group" class="col-md-offset-2 col-md-2 control-label">Add to Groups</label>
            <div class="col-md-4">
            <?php
            foreach ($groups as $g) {
	            echo "<label class=\"checkbox-inline\">";
	            echo "<input type=\"checkbox\" id=\"group[]\" value=\"" . $g['id'] . "\" checked> " . $g['name'];
	            echo "</label>";
	        }
            ?>
            </div>
          </div>
          <?php $cur = date("m") . "/" . date("d") . "/" . date("Y"); ?>
          <div class="form-group" id="g_date">
            <label for="date" class="col-md-offset-2 col-md-2 control-label">Date</label>
            <div class="col-md-2">
                  <div id="datetimepicker" class="input-group date">
                    <input class="form-control" data-format="MM/dd/yyyy" type="text" id="date" name="date" value="<?php echo $cur; ?>" readonly style="background-color: #FFFFFF;"></input>
                    <span class="input-group-addon add-on" id="cal_ico" data-toggle="tooltip" data-placement="right" data-container="body" title="Select a date">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                <?php
					$start = strtotime("-1 days 00:00:00", strtotime(date("o-\WW")));
					$end = strtotime("+6 days 23:59:59", $start);
				?>
                <script type="text/javascript">
                  $(function() {
                    $('#datetimepicker').datetimepicker({
                      language: 'en',
                      pickTime: false,
					  /*startDate: <?php echo "new Date(" . date("Y", $start) . ", " . date("m", $start) . ", " . date("d", $start) . ", 00, 00, 00, 00)"; ?>,
					  endDate: <?php echo "new Date(" . date("Y", $end) . ", " . date("m", $end) . ", " . date("d", $end) . ", 00, 00, 00, 00)"; ?>*/
                    });
                  });
                </script>
            </div>
          </div>
          <br />
          <div class="form-group">
            <div class="col-md-offset-5 col-md-2" style="text-align: center">
              <input type="hidden" id="submission" name="submission" value="yes">
              <button type="submit" class="btn btn-success">Add Workout</button>
            </div>
          </div>
        </form>
    </div>
  </body>
</html>
<?php
session_start();

require_once("headers/mysql.php");

$user = empty($_SESSION['USER'])?"":$_SESSION['USER'];
$group = empty($_SESSION['GROUP'])?"":$_SESSION['GROUP'];

$d_loc = (empty($_GET['d_loc']) || intval($_GET['d_loc']) < 0)?0:$_GET['d_loc'];
$alert = empty($_GET['alert'])?"":$_GET['alert'];
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
    <style>
	.edit-icon {
		position: absolute;
		top: 5px;
		right: 5px;
	}
	</style>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    
    <script type="text/javascript">
	$(document).ready(function(e) {	
		// Auto-bullet
		$("#desc").keyup(function(e) {
			if(e.which == 13) {
				$("#desc").val(function(i, val) {
					return val + "- ";
				});
			}
		});
    });
	</script>
    
    <script type="text/javascript">
	// Modal
	$(document).ready(function(e) {
        $('.edit-icon').click(function(e) {
            var post = $(this).attr('value');
			var date = $(this).attr('date');
			var post_id = "#post_" + post.toString();
			var desc = $(post_id).text();
			$("#desc").val(desc);
			$("#date").val(date);
			$("#p_id").val(post);
			// Show it
			$('#myModal').modal('show');
        });
    });
	</script>

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
            <li class="active"><a href="index.php">Log</a></li>
            <li><a href="create.php">New Entry</a></li>
            <li><a href="workouts.php">Workouts</a></li>
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
            <li><?php if(empty($user)) {?><a href="login.php">Sign In</a><?php } else { ?><a href="logout.php">Log Out</a><?php } ?></li>
            <li><a href="signup.php">Sign Up</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </div>
    </nav>
	<?php if($alert == "workout_success") {?>
    <div class="alert alert-success alert-dismissable" style=" margin-top: -20px; text-align: center">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <strong><?php echo "Workout successfully added!"; ?></strong>
    </div>
    <?php }	?>
    <?php if($alert == "workout_update") {?>
    <div class="alert alert-success alert-dismissable" style=" margin-top: -20px; text-align: center">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <strong><?php echo "Workout successfully modified!"; ?></strong>
    </div>
    <?php }	?>
	<div class="container-fluid">
    <?php if(empty($user)) {?>
    	<h1 style="text-align: center">Sign In/Up to View the Log!</h1>
    <?php } else { 
	function week_start($date) {
    	$ts = strtotime($date);
    	$start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
    	return date('Y-m-d', $start);
	}
	
	$sun = week_start(date('m/d/Y h:i:s a', time()));
	
	$d_offset = -7*$d_loc;
	
	$days = array(
	"sunday" => strtotime($d_offset . " days 00:00:00", strtotime($sun)),
	"monday" => strtotime(($d_offset+1) . " days 00:00:00", strtotime($sun)),
	"tuesday" => strtotime(($d_offset+2) . " days 00:00:00", strtotime($sun)),
	"wednesday" => strtotime(($d_offset+3) . " days 00:00:00", strtotime($sun)),
	"thursday" => strtotime(($d_offset+4) . " days 00:00:00", strtotime($sun)),
	"friday" => strtotime(($d_offset+5) . " days 00:00:00", strtotime($sun)),
	"saturday" => strtotime(($d_offset+6) . " days 00:00:00", strtotime($sun))
	);
	
	?>
    <div class="row">
    	<div class="col-md-offset-1 col-md-10">
        	<h1 style="text-align: center"><?php echo date("M jS, Y", $days["sunday"]) . " - " . date("M jS, Y", $days["saturday"]); ?></h1>
            <ul class="pager">
              <li class="previous"><a href="<?php echo "index.php?d_loc=" . ($d_loc+1); ?>">&larr; Older</a></li>
              <li class="next<?php if($d_loc > 0) { echo "\"><a href=\"index.php?d_loc=" . ($d_loc-1);} else {echo " disabled\"><a href=\"#";} ?>">Newer &rarr;</a></li>
            </ul>
        </div>
    </div>
    
    <div class="row">
    	<div class="col-md-offset-1 col-md-10">
        	<div class="table-responsive">
            <table class="table table-bordered table-striped" id="table1">
                <tr style="text-align: center; vertical-align: middle;">
                    <td>
                        <strong>User</strong><br /><span style="font-size: 10px; color: blue;">(<a href="#<?php echo $user['u_name']; ?>">snap to me</a>)</span>
                    </td>
                    <td>
                        <strong><?php echo "Sunday<br />" . date("m/d/Y", $days["sunday"]); ?></strong>
                    </td>
                    <td>
                        <strong><?php echo "Monday<br />" . date("m/d/Y", $days["monday"]); ?></strong>
                    </td>
                    <td>
                        <strong><?php echo "Tuesday<br />" . date("m/d/Y", $days["tuesday"]); ?></strong>
                    </td>
                    <td>
                        <strong><?php echo "Wednesday<br />" . date("m/d/Y", $days["wednesday"]); ?></strong>
                    </td>
                    <td>
                        <strong><?php echo "Thursday<br />" . date("m/d/Y", $days["thursday"]); ?></strong>
                    </td>
                    <td>
                        <strong><?php echo "Friday<br />" . date("m/d/Y", $days["friday"]); ?></strong>
                    </td>
                    <td>
                        <strong><?php echo "Saturday<br />" . date("m/d/Y", $days["saturday"]); ?></strong>
                    </td>
                </tr>
                
               <?php
				$start = $days["sunday"];
				$end = $days["saturday"];
				
				$start = date("Y", $start) . "-" . date("m", $start) . "-" . date("d", $start);
				$end = date("Y", $end) . "-" . date("m", $end) . "-" . date("d", $end);
				
				$users = mysql_query("SELECT * FROM users WHERE id IN (SELECT u_id FROM user_groups WHERE g_id='" . $group['id'] . "') ORDER BY l_name ASC, f_name ASC");

				while($u = mysql_fetch_array($users))
				{					
					$week_workouts = mysql_query("SELECT * FROM posts WHERE u_id='" . $u['id'] . "' AND g_id='" . $group['id'] . "' AND date >= '" . $start . "' AND date <= '" . $end . "'");
					
					$entries = array(
					"sunday" => "No Entry",
					"monday" => "No Entry",
					"tuesday" => "No Entry",
					"wednesday" => "No Entry",
					"thursday" => "No Entry",
					"friday" => "No Entry",
					"saturday" => "No Entry",
					);
					
					$edit_icon = false;
					if($user['id'] == $u['id']) {
						$edit_icon = true;
					}
					
					while($workout = mysql_fetch_array($week_workouts))
					{
						$day = strtolower(date("l", strtotime($workout['date'])));
						
						if($day == "sunday") {
							$entries["sunday"] = $workout;}
						else if($day == "monday") {
							$entries["monday"] = $workout;}
						else if($day == "tuesday") {
							$entries["tuesday"] = $workout;}
						else if($day == "wednesday") {
							$entries["wednesday"] = $workout;}
						else if($day == "thursday") {
							$entries["thursday"] = $workout;}
						else if($day == "friday") {
							$entries["friday"] = $workout;}
						else {
							$entries["saturday"] = $workout;}
					}
					
					echo "<tr>";
					
					echo "<a name=\"" . $u['u_name'] . "\"></a><td style=\"text-align: center; vertical-align: middle;\"><strong>" . stripslashes($u['f_name']) . " " . stripslashes($u['l_name']) . "</strong></td>";
					foreach($entries as $e)
					{
            $w_id = isset($e['id'])?$e['id']:-1; 
						$color = (!empty($_GET['highlight']) && $w_id == $_GET['highlight'])?"#dff0d8":"";
						
						if($e == "No Entry") {
						echo "<td style=\"text-align: center; vertical-align: middle; font-size: 10px; background-color: " . $color . ";\">" . $e . "</td>";
						}
						else {
							if($edit_icon) {
								$out_date = date("m", strtotime($e['date'])) . "/" . date("d", strtotime($e['date'])) . "/" . date("Y", strtotime($e['date']));
								echo "<td style=\"vertical-align: middle; position: relative; font-size: 10px; background-color: " . $color . ";\" id=\"post_" . $e['id'] . "\"><span class=\"edit-icon glyphicon glyphicon-pencil\" date=\"" . $out_date . "\" value=\"" . $e['id'] . "\"></span>" . stripslashes(nl2br($e['text'])) . "</td>";
							}
							else {
								echo "<td style=\"vertical-align: middle; font-size: 10px; background-color: " . $color . ";\" id=\"post_" . $e['id'] . "\">" . stripslashes(nl2br($e['text'])) . "</td>";
							}
						}
						
						unset($color);
					}
					
					echo "</tr>";
					
					unset($entries);
				}
			?>
		<?php } ?> 
                
                
            </table>
            </div>
        </div>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Edit Workout</h4>
          </div>
          <div class="modal-body">
              <form class="form-horizontal" role="form" method="post" action="edit.php">
              <div class="form-group" id="g_uname">
                <label for="uname" class="col-md-2 control-label">User</label>
                <div class="col-md-5">
                  <input type="text" class="form-control" id="uname" placeholder="<?php echo stripslashes($user['u_name']); ?>" disabled>
                </div>
              </div>
              <div class="form-group" id="g_desc">
                <label for="desc" class="col-md-2 control-label">Workout</label>
                <div class="col-md-9">
                  <textarea class="form-control" rows="12" id="desc" name="desc"></textarea>
                </div>
              </div>
              <div class="form-group" id="g_date">
                <label for="date" class="col-md-2 control-label">Date</label>
                <div class="col-md-5">
                    <input class="form-control" data-format="MM/dd/yyyy" type="text" id="date" name="date" disabled>
                </div>
              </div>
          </div>
          <div class="modal-footer">
          	<input type="hidden" name="p_id" id="p_id" value="" />
            <button type="submit" class="btn btn-danger" name="op" value="del">Delete</button>
            <button type="submit" class="btn btn-success" name="op" value="save">Save changes</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    
    </div>
  </body>
</html>
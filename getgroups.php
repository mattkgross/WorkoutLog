<?php
session_start();

require_once("headers/mysql.php");

$post = empty($_GET['p'])?0:intval($_GET['p']);
$user = empty($_SESSION['USER'])?"":$_SESSION['USER'];

if(!empty($user))
{

  $sql = mysql_query("SELECT id, name FROM groups WHERE id IN (SELECT g_id FROM post_groups WHERE p_id='" . $post . "')");
  $groups = array();
  while($temp = mysql_fetch_array($sql)) {
    array_push($groups, $temp);
  }

  $sql = mysql_query("SELECT id,name FROM groups WHERE id IN (SELECT g_id FROM user_groups WHERE u_id = '" . $user['id'] . "')");
  $u_groups = array();
  while($temp = mysql_fetch_array($sql)) {
    array_push($u_groups, $temp); }

  foreach ($u_groups as $g) {
    echo "<label class=\"checkbox-inline\">";
    if(in_array($g, $groups)) {
      echo "<input type=\"checkbox\" name=\"group[]\" value=\"" . $g['id'] . "\" checked disabled> " . $g['name'];
    }
    else {
      echo "<input type=\"checkbox\" name=\"group[]\" value=\"" . $g['id'] . "\" disabled> " . $g['name'];
    }
    echo "</label>";
  }

}

mysql_close();
?>
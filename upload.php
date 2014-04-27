<?php

session_start();

require_once("headers/mysql.php");

$user = empty($_SESSION['USER'])?"":$_SESSION['USER'];
$group = empty($_SESSION['GROUP'])?"":$_SESSION['GROUP'];
$admin = empty($_SESSION['G_ADMIN'])?false:$_SESSION['G_ADMIN'];

/* Rearranges $_FILES to the following format:
Array
(
    [0] => Array
        (
            [name] => foo.txt
            [type] => text/plain
            [tmp_name] => /tmp/phpYzdqkD
            [error] => 0
            [size] => 123
        )

    [1] => Array
        (
            [name] => bar.txt
            [type] => text/plain
            [tmp_name] => /tmp/phpeEwEWG
            [error] => 0
            [size] => 456
        )
)
*/
function reArrayFiles(&$file_post) {
    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

// Kick out people without the proper crudentials.
if(empty($user) || empty($group) || !$admin) {
	header('Location: index.php');
}
else {
	$form_type = $_POST['form_type'];

	if($form_type == "workout") {
		$title = addslashes($_POST['wtitle']);
		$text = addslashes($_POST['wtext']);
		$error = "";

		if($_FILES['wpdf']['error'][0] == 4) {
			$file_count = 0;
		}
		else {
			$file_count = count($_FILES['wpdf']['name']);
		}

		$file_names = array();
		$files = ($file_count>0)?reArrayFiles($_FILES['wpdf']):array();
		$uploaddir = "workouts";

		// Let's catch some errors
		$max_files = ini_get('max_file_uploads')?ini_get('max_file_uploads'):10;
		if($max_files < $file_count) {
			$error = "Too many files were uploaded!";
		}

		if(empty($error)) {
			foreach ($files as $file) {
				// Catch any immediate errors
				switch ($file['error']) {
		        case UPLOAD_ERR_OK:
		            break;
		        case UPLOAD_ERR_NO_FILE:
		        	// No file sent
		            $error = "No file was sent!";
		        case UPLOAD_ERR_INI_SIZE:
		        	$error = "ini size exceeded!";
		        case UPLOAD_ERR_FORM_SIZE:
		        	// Exceeded file size limit
		            $error = "File size of " . ini_get('upload_max_filesize') . " was exceeded!";
		        default:
		        	// Unknown errors
		            $error = "Unknown upload error. Please contact the webmaster.";
	    		}

		    	// Verify file type
		    	// DO NOT TRUST $file['mime'] VALUE !!
			    // Check MIME Type by yourself.
			    $finfo = new finfo(FILEINFO_MIME_TYPE);
			    if (false === $ext = array_search(
			        $finfo->file($file['tmp_name']),
			        array(
			            'pdf' => 'application/pdf',
			        ),
			        true
			    )) {
			        // Invalid file format
			        $error = "File format is invalid! Type detected is " . $finfo->file($file['tmp_name']) . ". Only pdf types are allowed at this time.";
			    }
			}
		}

		// Begin uploads given there are no errors.
		if(empty($error)) {
			foreach ($files as $file) {
				$filename = sprintf('./%s/%s.pdf', $uploaddir, sha1_file($file['tmp_name']));
				if(move_uploaded_file($file['tmp_name'], $filename)) {
					$file_names[] = $filename;
				}
				else {
				    $error = "File move error. Please contact webmaster.";
				    break;
				}
			}
		}

		// Delete any successful uploads if there was a failure
		if(!empty($error)) {
			foreach ($file_names as $name) {
				// Flip shit if the file can't be deleted.
				if(!unlink($name)) {
					throw new RuntimeException('File deletion malfunction. Fatal error. Please alert the webmaster immediately.');
				}
			}
		}
		// Otherwise, we were successful, so let's add to the db!
		else {
			if(empty($title) || empty($text)) {
				$error = "The workout must have a title and a body!";
			}
			else {
				mysql_query("INSERT INTO workouts (g_id, title, text) VALUES ('" . $group['id'] . "','$title','$text')");
				$w_id = mysql_insert_id();
				$i = 0;
				foreach ($file_names as $name) {
					mysql_query("INSERT INTO workout_files (w_id, name, filepath) VALUE ('$w_id', '" . substr($files[$i++]['name'], 0, -4) . "', '$name')");
				}
				$error = "Workout successfully posted!";
			}
		}

		// Send back message ($error)
		$_SESSION['message'] = $error;
		header('Location: admin.php');
	}
	else if($form_type == "play") {

	}
	else {
		header('Location: index.php');
	}
}
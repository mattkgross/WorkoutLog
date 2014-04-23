<?php

session_start();

require_once("headers/mysql.php");

$user = empty($_SESSION['USER'])?"":$_SESSION['USER'];
$group = empty($_SESSION['GROUP'])?"":$_SESSION['GROUP'];
$admin = empty($_SESSION['G_ADMIN'])?false:$_SESSION['G_ADMIN'];

if(empty($user) || empty($group) || !$admin)
{
	echo "Get out of here! You don't have privileges in these lands.";
}

else
{
	$data = array();

	$pg = $_GET['pg'];
 
	if(isset($_GET['files']))
	{  
		$error = false;
		$files = array();
	 
		if($pg == "workout") {
			$uploaddir = 'workouts';
		}
		else if ($pg == "play") {
			$uploaddir = 'plays';
		}
		else
		{
			$error = true;
		}

		switch ($_FILES['upfile']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
        	// No file sent
            $error = true;
        case UPLOAD_ERR_INI_SIZE:
        	$error = true;
        case UPLOAD_ERR_FORM_SIZE:
        	// Exceeded file size limit
            $error = true;
        default:
        	// Unknown errors
            $error = true;
    	}

		if(!$error) {
			foreach($_FILES as $file)
			{
				if ($_FILES['size'] > 1000000) {
		        	// Exceeded file size limit
		        	$error = true;
		    	}

				// DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
			    // Check MIME Type by yourself.
			    $finfo = new finfo(FILEINFO_MIME_TYPE);
			    if (false === $ext = array_search(
			        $finfo->file($_FILES['tmp_name']),
			        array(
			            'pdf' => 'application/pdf',
			        ),
			        true
			    )) {
			        // Invalid file format
			        $error = true;
			    }

			    if(!$error) {
					if(move_uploaded_file($file['tmp_name'], sprintf('./%s/%s.%s', $uploaddir, sha1_file($_FILES['tmp_name']), $ext)))
					{
						$files[] = $uploaddir . $filename;
					}
					else
					{
					    $error = true;
					}
				}
			}
		}
		$data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files);
	}
	else
	{
		$data = array('success' => 'Form was submitted', 'formData' => $_POST);
	}
	 
	echo json_encode($data);
}
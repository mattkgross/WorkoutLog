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
			$uploaddir = './workouts/';
		}
		else if ($pg == "play") {
			$uploaddir = './plays/';
		}
		else
		{
			$error = true;
		}

		foreach($_FILES as $file)
		{
			$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
			$filename = '';
			for ($i = 0; $i < 64; $i++) {
			    $filename .= $characters[rand(0, strlen($characters) - 1)];
			}

			if(move_uploaded_file($file['tmp_name'], $uploaddir . $filename))
			{
				$files[] = $uploaddir . $filename;
			}
			else
			{
			    $error = true;
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

header('Content-Type: text/plain; charset=utf-8');

try {
    
    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if (
        !isset($_FILES['upfile']['error']) ||
        is_array($_FILES['upfile']['error'])
    ) {
        throw new RuntimeException('Invalid parameters.');
    }

    // Check $_FILES['upfile']['error'] value.
    switch ($_FILES['upfile']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

    // You should also check filesize here. 
    if ($_FILES['upfile']['size'] > 1000000) {
        throw new RuntimeException('Exceeded filesize limit.');
    }

    // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
    // Check MIME Type by yourself.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($_FILES['upfile']['tmp_name']),
        array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ),
        true
    )) {
        throw new RuntimeException('Invalid file format.');
    }

    // You should name it uniquely.
    // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
    // On this example, obtain safe unique name from its binary data.
    if (!move_uploaded_file(
        $_FILES['upfile']['tmp_name'],
        sprintf('./uploads/%s.%s',
            sha1_file($_FILES['upfile']['tmp_name']),
            $ext
        )
    )) {
        throw new RuntimeException('Failed to move uploaded file.');
    }

    echo 'File is uploaded successfully.';

} catch (RuntimeException $e) {

    echo $e->getMessage();

}
?>
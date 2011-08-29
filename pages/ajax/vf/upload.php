<?

// this is the new upload script

$errors = array();

$params = mem('vf_uploader:' . $_POST['_token']);
if (!$params) {
	$errors[] = 'Invalid upload token passed.';
}

if (!$errors) {
	// do upload
	if (!isset($_FILES['file']) || 
		!is_uploaded_file($_FILES['file']['tmp_name']) || 
		$_FILES['file']['error'] != 0 ) {
		$errors[] = 'Invalid upload.';
	} else if ($_FILES['file']['tmp_name']) {
		$uploaded_file = ini_get('upload_tmp_dir') .'/'. $_FILES['file']['name'];
		move_uploaded_file($_FILES['file']['tmp_name'], $uploaded_file);
	} else {
		$errors[] = 'No file uploaded';
	}
}


if (!$errors) {
	$folder = (is_string($params['folder'])) ? $params['folder'] : $params['folder']->folders_id;
	if ($folder == '/' || !$folder) $errors[] = 'Folders Path was not set.';
}

if (!$errors) {
	$re = vf::$client->upload_to_server($uploaded_file, array(
		'folders_path' => $folder
	));
	if (!$re['success']) {
		$errors[] = 'There was an error uploading the file.';
	}
	if ($params['dbField'] && $params['dbRowID'] && !$errors) {
		$dot = strpos($params['dbField'], '.');
		$table = substr($params['dbField'], 0, $dot);
		$field = substr($params['dbField'], $dot + 1);
		aql::update($table, array(
			$field => $re['items_id']
		), $params['dbRowID']);
	}
}

if ($errors) {
	$response = array(
		'status' => 'Error',
		'errors' => $errors
	);
} else {
	$response = array(
		'status' => 'OK',
		'res' => $re,
		'params' => $params
	);
}


exit_json($response);
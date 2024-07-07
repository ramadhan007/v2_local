<?php

date_default_timezone_set('Asia/Jakarta');

$response  = array();

$table = $_POST['table'];

if($table=='monitor_journey_detail'){
	$id = $_POST['id'];
	$save_path = '../userfiles/screenshot/monitor_journey_detail/';
}

if(isset($_FILES['file']['name'])){
	$save_path = $save_path . basename($_FILES['file']['name']);
	$response['file_name'] = basename($_FILES['file']['name']);
	try
	{
		// Throws exception incase file is not being moved
		if(move_uploaded_file($_FILES['file']['tmp_name'], $save_path))
		{
			// File successfully uploaded. set status flag to 0
			$response['status'] = 1;
			$response['message'] = 'File uploaded successfully!';
			$response['file_path'] = $file_upload_url . basename($_FILES['file']['name']);
		}else{
			// set status flag to - 1
			$response['status'] = -1;
			$response['message'] = 'Could not upload the file!';
		}
	} catch(Exception $e)
	{
		// Exception occurred. set status flag to - 2
		$response['status'] = -2;
		$response['message'] = $e->getMessage();
	}
}
else
{
	// File parameter is missing
	$response['status'] = -3;
	$response['message'] = 'File is missing';
}

// Echo final json response to client
echo json_encode($response);

?>
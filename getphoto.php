<?php
	$customerid = $_GET['customerid'];
	$filename = $_GET['filename'];

	$filenname=("Clientimages/" . $customerid . "/" . $filename);
	if(file_exists($filenname)) {
		header('Content-type: image/jpeg');
		echo file_get_contents($filenname);
	}
	else {
		header("HTTP/1.1 404 Not Found");
		echo "HTTP/1.1 404 Not Found";
	}
?>
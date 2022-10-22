<?php
	$filename = $_GET['filename'];

	$filenname=("Templates/" . $filename);
	if(file_exists($filenname)) {
		echo file_get_contents($filenname);
	}
	else {
		header("HTTP/1.1 404 Not Found");
		echo "HTTP/1.1 404 Not Found";
	}
?>
<?php
	$documentid = $_GET['documentid'];

	$filenname=("Documents/" . $documentid . ".pdf");
	if(file_exists($filenname)) {
		header('Content-type: application/pdf');
		echo file_get_contents($filenname);
	}
	else {
		header("HTTP/1.1 404 Not Found");
		echo "HTTP/1.1 404 Not Found";
	}
?>
<?php
	$documentid = $_GET['documentid'];

	$filenname=("Documents/" . $documentid . ".pdf");
	if(file_exists($filenname)) {
		header("HTTP/1.1 200 OK");
		echo "OK";
	}
	else {
		header("HTTP/1.1 404 Not Found");
		echo "HTTP/1.1 404 Not Found";
	}
?>
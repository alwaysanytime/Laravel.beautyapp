<?php
	$customerid = $_GET['customerid'];
	$filename = $_GET['filename'];

	$filename=("Clientimages/" . $customerid . "/" . $filename);
	if(file_exists($filename)) {
		$thumb = exif_thumbnail($filename, $width, $height, $type);
		if ($thumb!==false) {
		    header('Content-type: ' .image_type_to_mime_type($type));
		    echo $thumb;
		    exit;
		} else {
		    // kein Miniaturbild vorhanden. Fehler wird hier verarbeitet
		    echo 'Kein Miniaturbild verfügbar';
		}
	}
	else {
		header("HTTP/1.1 404 Not Found");
		echo "HTTP/1.1 404 Not Found";
	}
?>

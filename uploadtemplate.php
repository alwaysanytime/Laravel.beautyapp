<?php
	header("Access-Control-Allow-Origin: *");
	
	if(isset($_FILES['templates'])) {
		for($count = 0; $count < count($_FILES['templates']['name']); $count++) {
			$filename = $_FILES['templates']['name'][$count];
			move_uploaded_file($_FILES['templates']['tmp_name'][$count], "Templates/" . $filename);	
		}
		echo 'OK';
	}
?>
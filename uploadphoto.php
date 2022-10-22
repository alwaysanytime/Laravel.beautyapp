<?php
	header("Access-Control-Allow-Origin: *");
	
	$customerid = $_POST['customerid'];
	
	if(isset($_FILES['photos'])) {
		for($count = 0; $count < count($_FILES['photos']['name']); $count++) {
			$filename = $_FILES['photos']['name'][$count];
			move_uploaded_file($_FILES['photos']['tmp_name'][$count], "Clientimages/" . $customerid . "/" . $filename);	
		}
		echo $customerid;
	}
?>
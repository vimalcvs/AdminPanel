<?php

	$id = isset($_GET['id']) ? $_GET['id'] : false;

	if (! $id) {
		die('404 Oops!!');
	}

	$id = $_POST['id'];

	$sql = "DELETE FROM tbl_user WHERE id = '$id'";
	$delete = $connect->query($sql);

	if($delete) {
		header("location: users.php");
	}
?>
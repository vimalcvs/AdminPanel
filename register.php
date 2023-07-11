<?php

include_once ('includes/config.php');
include_once ('templates/fcm.php');

if(isset($_GET['token'])) {
	$query = "SELECT * FROM tbl_tokens where token='".$_GET['token']."'" ;
	$sel = mysqli_query($connect, $query);
	
	if(mysqli_num_rows($sel) > 0) {
		$set['the_stream'][]=array('msg' => 'Already added','Success'=>'0');
			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
			die();
	} else {
		$data = array(            
	        'token'  =>  $_GET['token']
	    );  
	      
	    $qry = Insert('tbl_tokens', $data);
    	
        $set['the_stream'][]=array('msg' => 'Success','Success'=>'1');
		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
		die();
	}
} else {

	header( 'Content-Type: application/json; charset=utf-8' );
	echo "processApi - method not exist";
}

?>
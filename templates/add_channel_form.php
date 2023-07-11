<?php
	include_once('functions.php');
?>

<?php
	$function = new functions;
	$sql_query = "SELECT cid, category_name FROM tbl_category ORDER BY cid ASC";

	$stmt_category = $connect->stmt_init();
	if($stmt_category->prepare($sql_query)) {
		// Execute query
		$stmt_category->execute();
		// store result
		$stmt_category->store_result();
		$stmt_category->bind_result(
				$category_data['cid'],
				$category_data['category_name']
			);
	}


	//$max_serve = 10;
	if(isset($_POST['btnAdd'])) {
		echo  
		"<script> 
			$(document).ready(function () {
				$('#btnAdd').attr('disabled', true);		
			});
		</script>";
		
	
		$upload_image = '';
		$channel_type = $_POST['channel_type'];
		$channel_sub_title = '';

		if($channel_type == YOUTUBE) {
			$channel_url_two = $_POST['youtube_url_two'];
			$function = new functions;
		

		} else {

			 if($channel_type == EMBEDDED) {
			} else if($channel_type == STREAMING) {
		
				$channel_sub_title = $_POST['channel_sub_title'];
			}
			
			// get image info
			$channel_image = $_FILES['channel_image']['name'];
			$image_error = $_FILES['channel_image']['error'];
			$image_type = $_FILES['channel_image']['type'];

			// common image file extensions
			$allowedExts = array("gif", "jpeg", "jpg", "png");

			// get image file extension
			error_reporting(E_ERROR | E_PARSE);
			$extension = end(explode(".", $_FILES["channel_image"]["name"]));

			if ($image_error > 0) {
				$error['channel_image'] = " <span class='label red-text'>Image Not Uploaded!!</span>";
			} else if(!(($image_type == "image/gif") ||
				($image_type == "image/jpeg") ||
				($image_type == "image/jpg") ||
				($image_type == "image/x-png") ||
				($image_type == "image/png") ||
				($image_type == "image/pjpeg")) &&
				!(in_array($extension, $allowedExts))){

				$error['channel_image'] = "Image type must jpg, jpeg, gif, or png!";
			}

			// create random image file name
			$string = '0123456789';
			$file = preg_replace("/\s+/", "_", $_FILES['channel_image']['name']);
			$function = new functions;
			$channel_image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;

			// upload new image
			$unggah = UPLOAD_CHANNEL . $channel_image;
			$upload = move_uploaded_file($_FILES['channel_image']['tmp_name'], $unggah);
			$upload_image = $channel_image;
		
		}
		
		$channel_name = $_POST['channel_name'];
		$cid = $_POST['cid'];
		
		$channel_description = $_POST['channel_description'];
		$active = $_POST['status'];
	
		// create array variable to handle error
		$error = array();

		if(empty($channel_name)){
			$error['channel_name'] = " <span class='label red-text'>Required, please fill out this field!!</span>";
		}

		if(empty($cid)){
			$error['cid'] = " <span class='label red-text'>Required, please fill out this field!!</span>";
		}


		if(empty($channel_description)){
			$error['channel_description'] = " <span class='label red-text'>Required, please fill out this field!!</span>";
		}

		if(!isset($active)){
			$error['status'] = " <span class='label red-text'>Required, please fill out this field!!</span>";
		}
		
		
		if (!empty($channel_name) &&
			!empty($cid) &&
			!empty($channel_description)) {

			// insert new data to menu table
			$sql_query = "INSERT INTO tbl_channel (channel_name, channel_type, category_id,   channel_sub_title, channel_description,  channel_image, active, createdAt, updatedAt)
					VALUES(?, ?, ?, ?, ?, ?,  ?, ?,   ?)";
			$date = date('Y-m-d H:i:s', time());
			$stmt = $connect->stmt_init();
			if($stmt->prepare($sql_query)) {
				// Bind your variables to replace the ?s
				$stmt->bind_param('sssssssss',
							$channel_name,
							$channel_type,						
							$cid,
							$channel_sub_title,
							$channel_description,
							$upload_image,
							$active,
							$date,
							$date);
				// Execute query
				$stmt->execute();
				// store result
				$result = $stmt->store_result();
				$stmt->close();
			}

			if($result) {
				$error['error_data'] = "<div class='card-panel green lighten-4'>
											<span class='green-text text-darken-2'>
												New channel added successfully.
											</span>
										</div>";
			} else {
				$error['error_data'] = "<div class='card-panel red lighten-4'>
											<span class='red-text text-darken-2'>
												Added failed.
											</span>
										</div>";
			}
			
		}
		echo  
		"<script> 
			$(document).ready(function () {
				$('#btnAdd').attr('disabled', false);		
			});
		</script>";

	}
?>














<?php include 'templates/channel_script.php'; ?>

<!-- START CONTENT -->
<section id="content">

	<!--breadcrumbs start-->
	<div id="breadcrumbs-wrapper" class=" grey lighten-3">
		<div class="container">
			<div class="row">
				<div class="col s12 m12 l12">
					<h5 class="breadcrumbs-title">Add New Chapter</h5>
					<ol class="breadcrumb">
						<li><a href="dashboard.php" class="deep-orange-text">Dashboard</a></li>
						<li><a href="channel.php" class="deep-orange-text">Manage Chapter</a></li>
						<li><a class="active">Add New Chapter</a></li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<!--breadcrumbs end-->

		<!--start container-->
	<div class="container">
		<div class="section">
			<div class="row">
				<div class="col s12 m12 l12">
					<div class="card-panel">
						<div class="row">
							<form method="post" class="col s12" id="form-validation" enctype="multipart/form-data">
								<div class="row">
								<?php echo isset($error['error_data']) ? $error['error_data'] : '';?>
									<div class="input-field col s12">

									<!--<div class="col s12 m12 l5">-->
										<div class="row">
											<div class="input-field col s12">
											<input type="text" name="channel_name" id="channel_name" maxlength=100 required/>
											<label for="channel_name">Chapter Name</label><?php echo isset($error['channel_name']) ? $error['channel_name'] : '';?>
											</div>
										</div>

										<div class="row">
											<div class="input-field col s12" id="channel_user_agent_div">
											<input type="text" name="channel_sub_title" id="channel_sub_title" />
											<label for="channel_sub_title">SubTitle</label>
											</div>
										</div>

										<div class="row">
											<div class="input-field col s12">
											<select name="cid">
												<?php while($stmt_category->fetch()){ ?>
														<option value="<?php echo $category_data['cid']; ?>"><?php echo $category_data['category_name']; ?></option>
												<?php } ?>
											</select>
											<label>Category</label><?php echo isset($error['cid']) ? $error['cid'] : '';?></div>
										</div>


										


										<div class="row">
											<div class="input-field col s12">
											<select name="channel_type" id="channel_type">
												<option value="<?php echo STREAMING; ?>" selected><?php echo STR_STREAMING; ?></option>
												<option value="<?php echo YOUTUBE; ?>"><?php echo STR_YOUTUBE; ?></option>
												<option value="<?php echo EMBEDDED; ?>"><?php echo STR_EMBEDDED; ?></option>
											</select>
											<label>Chapter Type</label><?php echo isset($error['channel_type']) ? $error['channel_type'] : '';?></div>
										</div>

							
										
						


										<div class="row">
											<div class="input-field col s12">
											<select name="status">
												<option value="1" selected><?php echo ACTIVE; ?></option>
												<option value="0"><?php echo INACTIVE; ?></option>
											</select>
											<label>Status</label><?php echo isset($error['status']) ? $error['status'] : '';?></div>
										</div>

										<div class="row" id="channel_img_div">
											<div class="input-field col s12">
												<input type="file" id="input-file-now" name="channel_image" id="channel_image" 
													class="dropify-image" data-max-file-size="1M" data-allowed-file-extensions="jpg png gif"/>
												<div class="div-error"><?php echo isset($error['channel_image']) ? $error['channel_image'] : '';?></div>
											</div>
										</div>
										
										<div class="row">
											<div class="input-field col s12">
												<span class="grey-text text-grey lighten-2">Description</span>
												<?php echo isset($error['channel_description']) ? $error['channel_description'] : '';?>
												<textarea name="channel_description" id="channel_description" class="materialize-textarea" rows="16">
												</textarea>
												<script type="text/javascript" src="assets/js/ckeditor/ckeditor.js"></script>
												<script type="text/javascript">
															CKEDITOR.replace( 'channel_description' );
															CKEDITOR.config.allowedContent = true;
												</script>
											</div>
										</div>
									
										<div class="row">
											<div class="input-field col s12">												
												<button class="btn deep-orange waves-effect waves-light" type="submit" name="btnAdd" id="btnAdd">Submit
													<i class="mdi-content-send right"></i>
												</button>
											</div>
										</div>
									</div>
								<!--</div>-->
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

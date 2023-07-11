<?php
	include_once('functions.php');
?>

	<?php

		if(isset($_GET['id'])){
			$ID = $_GET['id'];
		}else{
			$ID = "";
		}

		// create array variable to store category data
		$category_data = array();

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

		$sql_query = "SELECT channel_image FROM tbl_channel WHERE id = ?";

		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result
			$stmt->store_result();
			$stmt->bind_result($previous_channel_image);
			$stmt->fetch();
			$stmt->close();
		}


		if(isset($_POST['btnEdit'])) {
			echo  
			"<script> 
				$(document).ready(function () {
					$('#btnEdit').attr('disabled', true);		
				});
			</script>";
			
			$channel_video_id = '';
			$upload_image = '';
			$channel_type = $_POST['channel_type'];
			$channel_user_agent = '';

			if($channel_type == YOUTUBE) {

				$channel_url = $_POST['youtube_url'];
				$channel_url_two = $_POST['youtube_url_two'];
				$function = new functions;
				$channel_video_id = $function->youtube_id_from_url($_POST['youtube_url']);
	
			}else {

				if($channel_type == EMBEDDED) {
				   $channel_url = $_POST['embedded_url'];
				   $channel_url_two = $_POST['embedded_url_two'];
				} else if($channel_type == STREAMING) {
					$channel_url = $_POST['channel_url'];
					$channel_url_two = $_POST['channel_url_two'];
					$channel_user_agent = $_POST['channel_user_agent'];
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

				if(!empty($channel_image)){
					if(!(($image_type == "image/gif") ||
						($image_type == "image/jpeg") ||
						($image_type == "image/jpg") ||
						($image_type == "image/x-png") ||
						($image_type == "image/png") ||
						($image_type == "image/pjpeg")) &&
						!(in_array($extension, $allowedExts))){
						$error['channel_image'] = "*<span class='label red-text'>Image type must jpg, jpeg, gif, or png!</span>";
					}
				}

				$upload_image = $channel_image;
			}
	
			$channel_name = $_POST['channel_name'];
			$cid = $_POST['cid'];
			$channel_description = $_POST['channel_description'];
			$link1_label = $_POST['link1_label'];
			$link2_label = $_POST['link2_label'];
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

			if( !empty($channel_name) &&
				!empty($cid) &&
				!empty($channel_description)){
					
				$date = date('Y-m-d H:i:s', time());

				if(!empty($upload_image)) {
					// create random image file name
					$string = '0123456789';
					$file = preg_replace("/\s+/", "_", $_FILES['channel_image']['name']);
					$function = new functions;
					$channel_image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;

					// delete previous image
					$delete = unlink(UPLOAD_CHANNEL ."$previous_channel_image");
					
					// upload new image
					$unggah = UPLOAD_CHANNEL . $channel_image;
					$upload = move_uploaded_file($_FILES['channel_image']['tmp_name'], $unggah);

					$upload_image = $channel_image;
					
					// updating all data
					$sql_query = "UPDATE tbl_channel SET channel_name = ?, channel_type = ?, category_id = ?,  channel_description = ?,  channel_image = ?, active = ?, updatedAt = ? WHERE id = ?";

					
					$stmt = $connect->stmt_init();
					if($stmt->prepare($sql_query)) {
						// Bind your variables to replace the ?s
						$stmt->bind_param('ssssssss',
									$channel_name,
									$channel_type,
								
									$cid,
									$channel_description,
								
									$upload_image,
									$active,
									$date,
									$ID);
						// Execute query
						$stmt->execute();
						// store result
						$update_result = $stmt->store_result();
						$stmt->close();
					}
				} else {
					
					// updating all data except image file
					$sql_query = "UPDATE tbl_channel SET channel_name = ? , channel_type = ? ,  category_id = ?,  channel_description = ?, active = ?, updatedAt = ? WHERE id = ?";

					$stmt = $connect->stmt_init();
					if($stmt->prepare($sql_query)) {
						// Bind your variables to replace the ?s
						$stmt->bind_param('sssssss',
									$channel_name,
									$channel_type,
									$cid,
									$channel_description,
								
									$active,
									$date,
									$ID);
						// Execute query
						$stmt->execute();
						// store result
						$update_result = $stmt->store_result();
						$stmt->close();
					}
				}

				// check update result
				if($update_result) {
					$error['update_data'] = "<div class='card-panel green lighten-4'>
												<span class='green-text text-darken-2'>
													Channel updated successfully.
												</span>
											</div>";
				} else {
					$error['update_data'] = "<div class='card-panel red lighten-4'>
												<span class='red-text text-darken-2'>
													Update Failed
												</span>
											</div>";
				}
				
			}
			echo  
			"<script> 
				$(document).ready(function () {
					$('#btnEdit').attr('disabled', false);		
				});
			</script>";

		}

		// create array variable to store previous data
		$data = array();

		$sql_query = "SELECT id, category_id, category_id as cid, channel_name, channel_type,  channel_image, channel_description,  active	FROM tbl_channel WHERE id = ?";
		//$sql_query = "SELECT * FROM tbl_channel WHERE id = ?";

		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result
			$stmt->store_result();
			$stmt->bind_result($data['id'],
					$data['category_id'],
					$data['cid'],
					$data['channel_name'],
					$data['channel_type'],
			
					$data['channel_image'],
			
					$data['channel_description'],
			
					$data['active']
					);
			$stmt->fetch();
			$stmt->close();
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
					<h5 class="breadcrumbs-title">Edit Channel</h5>
					<ol class="breadcrumb">
						<li><a href="dashboard.php" class="deep-orange-text">Dashboard</a></li>
						<li><a href="channel.php" class="deep-orange-text">Manage Channel</a></li>
						<li><a class="active">Edit Channel</a>
						</li>
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
							<form method="post" class="col s12" enctype="multipart/form-data">
								<div class="row">
								<?php echo isset($error['update_data']) ? $error['update_data'] : '';?>
									<div class="input-field col s12">
									
										<div class="row">
											<div class="input-field col s12">
											<input type="text" name="channel_name" id="channel_name" value="<?php echo $data['channel_name']; ?>" maxlength=100 required/>
											<label for="channel_name">Channel Name</label><?php echo isset($error['channel_name']) ? $error['channel_name'] : '';?>
											</div>
										</div>
		                              <div class="row">
											<div class="input-field col s12" id="channel_user_agent_div">
											<input type="text" name="channel_user_agent" id="channel_user_agent" value="<?php if(empty($data['channel_user_agent'])) {echo '';} else { echo $data['channel_user_agent'];} ?>" />
											<label for="channel_user_agent">SubTitle</label>
											</div>
										</div>

										<div class="row">
											<div class="input-field col s12">
											<select name="cid">
												<?php while($stmt_category->fetch()){
													if($category_data['cid'] == $data['cid']){?>
														<option value="<?php echo $category_data['cid']; ?>" selected="<?php echo $data['cid']; ?>" ><?php echo $category_data['category_name']; ?></option>
													<?php }else{ ?>
														<option value="<?php echo $category_data['cid']; ?>" ><?php echo $category_data['category_name']; ?></option>
													<?php }}
												?>
											</select>
											<label>Category</label><?php echo isset($error['cid']) ? $error['cid'] : '';?></div>
										</div>

										<div class="row">
											<div class="input-field col s12">
											<select name="channel_type" id="channel_type">
												<option value="<?php echo STREAMING; ?>" <?php if($data['channel_type'] == STREAMING) { echo 'selected'; } ?> ><?php echo STR_STREAMING; ?></option>
												<option value="<?php echo YOUTUBE; ?>" <?php if($data['channel_type'] == YOUTUBE) { echo 'selected'; } ?> ><?php echo STR_YOUTUBE; ?></option>
												<option value="<?php echo EMBEDDED; ?>" <?php if($data['channel_type'] == EMBEDDED) { echo 'selected'; } ?> ><?php echo STR_EMBEDDED; ?></option>
											</select>
											<label>Channel Type</label><?php echo isset($error['channel_type']) ? $error['channel_type'] : '';?></div>
										</div>

									
									
								
										<div class="row">
											<div class="input-field col m12 s12">
											<select name="status">
												<option value="1" <?php if($data['active'] == 1) echo "selected"; else echo ""; ?> ><?php echo ACTIVE; ?></option>
												<option value="0" <?php if($data['active'] == 0) echo "selected"; else echo ""; ?> ><?php echo INACTIVE; ?></option>
											</select>
											<label>Status</label><?php echo isset($error['status']) ? $error['status'] : '';?></div>
										</div>

										<div class="row" id="channel_img_div">
											<div class="input-field col s12 m12 l12">
												<?php if($data['channel_type'] !== YOUTUBE) { ?>
													<input type="file" name="channel_image" id="channel_image"
															class="dropify-image" data-max-file-size="1M" data-allowed-file-extensions="jpg png gif"
															data-default-file= <?php echo UPLOAD_CHANNEL . $data['channel_image']; ?> data-show-remove="false" />
												<?php } else {?>
													<input type="file" name="channel_image" id="channel_image"
															class="dropify-image" data-max-file-size="1M" data-allowed-file-extensions="jpg png gif" />	
												<?php } ?>
												
												<div class="div-error"><?php echo isset($error['channel_image']) ? $error['channel_image'] : ''; ?></div>
											</div>
										</div>

										<div class="row">
											<div class="input-field col s12">
												<span class="grey-text text-grey lighten-2">Description</span>
													<?php echo isset($error['channel_description']) ? $error['channel_description'] : '';?>
												<textarea name="channel_description" id="channel_description" class="materialize-textarea" rows="16"><?php echo $data['channel_description']; ?></textarea>
												<script type="text/javascript" src="assets/js/ckeditor/ckeditor.js"></script>
												<script type="text/javascript">
															CKEDITOR.replace( 'channel_description' );
															CKEDITOR.config.allowedContent = true;
												</script>
											</div>
										</div>
										<br/>
										
										<div class="row">
											<div class="input-field col s12 m12 l12">
												<button class="btn deep-orange waves-effect waves-light left" type="submit" name="btnEdit" id="btnEdit">Update
													<i class="mdi-content-send right"></i>
												</button>
											</div>
										</div>

									</div>

								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

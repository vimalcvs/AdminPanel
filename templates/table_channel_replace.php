<?php
	include_once('functions.php');
?>

<?php
	$function = new functions;
	$sql_query = "SELECT *, p.active as channel_status FROM tbl_channel p, tbl_category c WHERE p.category_id = c.cid ORDER BY id DESC";
	$result = mysqli_query($connect, $sql_query);
	$recordCount = $result->num_rows;

	if(isset($_POST['submitReplaceLink'])) {

		if(isset($_POST['replace']) && isset($_POST['with'])){
			
			$replace = $_POST['replace'];
			$with = $_POST['with'];

			$sql_query = "UPDATE tbl_channel SET channel_url = REPLACE(channel_url, ?, ?), channel_url_two = REPLACE(channel_url_two, ?, ?)";

			$stmt = $connect->stmt_init();
			if($stmt->prepare($sql_query)) {
				// Bind your variables to replace the ?s
				$stmt->bind_param('ssss',
							$replace,
							$with,
							$replace,
							$with);
				// Execute query
				$stmt->execute();
				// store result
				$update_result = $stmt->store_result();
				$stmt->close();
			}

			// check update result
			if($update_result) {
				$error['update_data'] = "<div class='card-panel green lighten-4'>
											<span class='green-text text-darken-2'>
												Channels replaced($replace) with($with) successfully.
											</span>
										</div>";
			} else {
				$error['update_data'] = "<div class='card-panel red lighten-4'>
											<span class='red-text text-darken-2'>
												Update Failed
											</span>
										</div>";
			}

			$function = new functions;
			$sql_query = "SELECT *, p.active as channel_status FROM tbl_channel p, tbl_category c WHERE p.category_id = c.cid ORDER BY id DESC";
			$result = mysqli_query($connect, $sql_query);
			$recordCount = $result->num_rows;

		} else {
			$error['update_data'] = "<div class='card-panel red lighten-4'>
										<span class='red-text text-darken-2'>
											Required field is missing.
										</span>
									</div>";
		}
	}
 ?>

	<!-- START CONTENT -->
    <section id="content">

        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          	<div class="container">
            	<div class="row">
              		<div class="col s12 m12 l12">
               			<h5 class="breadcrumbs-title">Replace Chapter</h5>
                        <ol class="breadcrumb">
                            <li><a href="dashboard.php" class="deep-orange-text">Dashboard</a></li>
                            <li><a href="channel.php" class="deep-orange-text">Manage Chapter</a></li>
							<li><a class="active">Replace Channels</a></li>
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
					<div class="col s6 m6 l6">
						<a href="add_channel.php" class="btn waves-effect waves-light deep-orange">Add New Chapter</a> &nbsp;
					</div>	
					<div class="col s6 m6 l6" >
						<ul class="dropdown-menu pull-right footer2 tooltipped"
									data-position="top" data-delay="0" data-tooltip="Hide/Show Columns" style = "margin-top:0px">
							<li><a class="btn dropdown-button" href="javascript:void(0);"
								data-activates="dropdownChannel">SELECT<i class="mdi-hardware-keyboard-arrow-down right"></i></a>
							</li>
						</ul>
						<ul id="dropdownChannel" class="dropdown-content">
							<!-- <li><a class="toggle-vis" data-column="1">No.</a></li>-->
							<li><a class="toggle-vis" data-column="2">Name</a></li>
							<li><a class="toggle-vis" data-column="3">Link1</a></li>
							<li><a class="toggle-vis" data-column="4">Link2</a></li>
						</ul>
					</div>
					<form method="post" class="col s12 m12 l12" id="form-validation" enctype="multipart/form-data">
						<div class="s12 m12 l12">
							<div class="card-panel hoverable">
							<h5><strong>Modify all Video section (Link1 and Link2) URLs of this app.</strong></h5>
								<hr/>
								<div class="card-panel red lighten-4">
								<b class="black-text">Important Notice</b>
								<li style="list-style-type:disc;"><span>Replacement will be performed on all Video section of this app</span></li>
								<li style="list-style-type:disc;"><span>Replacement can't be undone</li>
								<li style="list-style-type:disc;"><span>Make sure to take a database backup before replace the links.</li>
								</div>
														
									<?php echo isset($error['update_data']) ? $error['update_data'] : '';?>
									<div class="row">
										<div class="input-field col s6">
											<input type="text" name="replace" id="replace" placeholder="/livetv/" required/>
											<label for="replace">Replace :</label><?php echo isset($error['replace']) ? $error['replace'] : '';?>
										</div>
										<div class="input-field col s6">
											<label for="replace">E.g. http://www.domain.com/livetv/streaming.m3u8</label>
										</div>
									</div>
		
									<div class="row">
										<div class="input-field col s6">
											<input type="text" name="with" id="with" placeholder="/tvchannel/" required/>
											<label for="with">With :</label><?php echo isset($error['with']) ? $error['with'] : '';?>
										</div>
										<div class="input-field col s6">
											<label for="replace">E.g. http://www.domain.com/tvchannel/streaming.m3u8</label>
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12">												
											<button class="btn deep-orange waves-effect waves-light left" type="submit" name="submitReplaceLink">Replace Links</button>
										</div>
									</div>
								
							</div>
						</div>
					</form>
					
		        	<div class="col s12 m12 l12">
		        		
						<div class="card-panel">
							<table id="table_channel" class="responsive-table display" cellspacing="0">		         
								<thead>
									<tr>
										<th class="hide-column">ID</th>
										<th>No.</th>
										<th>Name</th>
										<th>Link1</th>
										<th>Link2</th>
										<th>Action</th>
									</tr>
								</thead>

								<tbody>
									<?php
										$i = 1;
										while($data = mysqli_fetch_array($result)) {
									?>

									<tr>
										<td class="hide-column"><?php echo $data['id'];?></td>
										<td>
											<?php
												echo $i;
												$i++;
											?>
										</td>
										<td><?php echo $data['channel_name'];?></td>
										<td class="smallWidth"><?php echo $data['channel_url'];?></td>
										<td class="smallWidth"><?php echo $data['channel_url_two'];?></td>
										
										<td class="smallWidth"> 
											<a href="edit_channel.php?id=<?php echo $data['id'];?>"
												class="tooltipped btn-floating activator waves-effect waves-light darken-2" 
												data-position="top" data-delay="0" data-tooltip="Edit">
												<i class="mdi-editor-mode-edit"  ></i>
											</a> &nbsp;
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</section>

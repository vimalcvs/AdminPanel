<?php
	include_once('functions.php');
?>

<?php
	$function = new functions;
	$sql_query = "SELECT *, p.active as channel_status FROM tbl_channel p, tbl_category c WHERE p.category_id = c.cid ORDER BY id DESC";
	$result = mysqli_query($connect, $sql_query);
	$recordCount = $result->num_rows;
 ?>

	<!-- START CONTENT -->
    <section id="content">

        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          	<div class="container">
            	<div class="row">
              		<div class="col s12 m12 l12">
               			<h5 class="breadcrumbs-title">Manage Chapter</h5>
                        <ol class="breadcrumb">
                            <li><a href="dashboard.php" class="deep-orange-text">Dashboard</a></li>
                            <li><a class="active">Manage Chapter</a></li>
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
						<a href="add_channel.php" class="btn waves-effect waves-light deep-orange">Add New Chapter</a>
					</div>	
					<div class="col s6 m6 l6">
						<ul class="dropdown-menu pull-right footer2 tooltipped"
									data-position="top" data-delay="0" data-tooltip="Hide/Show Columns" style = "margin-top:0px">
							<li><a class="btn dropdown-button" href="javascript:void(0);"
								data-activates="dropdownChannel">SELECT<i class="mdi-hardware-keyboard-arrow-down right"></i></a>
							</li>
						</ul>
						<ul id="dropdownChannel" class="dropdown-content">
							<!-- <li><a class="toggle-vis" data-column="1">No.</a></li>-->
							<li><a class="toggle-vis" data-column="2">Image</a></li>
							<li><a class="toggle-vis" data-column="3">Name</a></li>
							<li><a class="toggle-vis" data-column="4">Category</a></li>
							<li><a class="toggle-vis" data-column="7">View</a></li>
							<li><a class="toggle-vis" data-column="8">Status</a></li>
						</ul>
					</div>
					
		        	<div class="col s12 m12 l12">
		        		
						<div class="card-panel">
							<table id="table_channel" class="responsive-table display" cellspacing="0">		         
								<thead>
									<tr>
										<th class="hide-column">ID</th>
										<th>No.</th>
										<th>Image</th>
										<th>Name</th>
										<th>Category</th>
								
										<th>View</th>
										<th>Status</th>
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
										<td>
											<?php $channelImgPath = $data['channel_image']; ?>
	                                 <img class="materialboxed circle z-depth-2" data-caption="<?php echo $data['channel_name'];?>"
		                           src="<?php if($channelImgPath == '') { echo DEFAULT_IMG; } else { echo UPLOAD_CHANNEL . $channelImgPath; }?>"
		                              height="54px" width="54px" style="object-fit: cover;"/>
										</td>
										<td><?php echo $data['channel_name'];?></td>
										<td><?php echo $data['category_name'];?></td>
										<td><?php echo $data['view_count'];?></td>
										<td>
											<?php if($data['channel_status']==1) {?>
												<span class="task-cat green"><?php echo ACTIVE;?></span>
											<?php } else { ?>
												<span class="task-cat red"><?php echo INACTIVE;?></span>
											<?php }  ?>
										</td>
										<td>
											<a href="edit_channel.php?id=<?php echo $data['id'];?>"
												class="tooltipped btn-floating activator waves-effect waves-light darken-2" 
												data-position="top" data-delay="0" data-tooltip="Edit">
												<i class="mdi-editor-mode-edit"  ></i>
											</a> &nbsp;
											
											<a class="tooltipped btn-floating waves-effect waves-light" data-position="top" 
													data-delay="0" data-tooltip="Delete" onclick="swal({
														title: 'Delete', text: 'You will not be able to recover this item!',
														type: 'warning', showCancelButton: true, confirmButtonColor: '#ff5722', 
														cancelButtonColor: '#3085d6',
														confirmButtonText: 'Yes, delete it!', cancelButtonText: 'No, cancel!',
														closeOnConfirm: false, closeOnCancel: false
													}, function (isConfirm) {
														if (isConfirm) {
															window.location.href = 'delete_channel.php?id=<?php echo $data['id'];?>';
														} else {
															swal('<?php echo DELETE_TITLE; ?>', '<?php echo DELETE_CANCELLED; ?>', 'error');
														}
													});" >
												<i class="mdi-action-delete"></i>
											</a>


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

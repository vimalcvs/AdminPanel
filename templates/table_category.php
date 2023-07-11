<?php
	include('templates/fcm.php');
?>

<?php

	//$sql_query = "SELECT * FROM tbl_category ORDER BY cid DESC";
	$sql_query = "SELECT 
							cid, 
							category_name, 
							category_image, 
							COUNT(category_id) AS category_count,
							tbl_category.active AS active
						FROM 
							tbl_category 
						LEFT JOIN 
							tbl_channel ON cid=category_id 
						GROUP BY cid 
						order by cid DESC";
	$result = mysqli_query($connect, $sql_query);

 ?>

<!-- START CONTENT -->
<section id="content">

	<!--breadcrumbs start-->
	<div id="breadcrumbs-wrapper" class=" grey lighten-3">
		<div class="container">
			<div class="row">
				<div class="col s12 m12 l12">
					<h5 class="breadcrumbs-title">Manage Category</h5>
					<ol class="breadcrumb">
						<li><a href="dashboard.php" class="deep-orange-text">Dashboard</a></li>
						<li><a class="active">Manage Category</a></li>
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
					<a href="add_category.php" class="btn waves-effect waves-light deep-orange">Add New Category</a>
				</div>	
				<div class="col s6 m6 l6">
					<ul class="dropdown-menu pull-right footer2 tooltipped"
								data-position="top" data-delay="0" data-tooltip="Hide/Show Columns" style = "margin-top:0px">
						<li><a class="btn dropdown-button" href="javascript:void(0);"
							data-activates="dropdownCategory">SELECT<i class="mdi-hardware-keyboard-arrow-down right"></i></a>
						</li>
					</ul>
					<ul id="dropdownCategory" class="dropdown-content">
						<!-- <li><a class="toggle-vis" data-column="1">No.</a></li>-->
						<li><a class="toggle-vis" data-column="2">Image</a></li>
						<li><a class="toggle-vis" data-column="3">Name</a></li>
						<li><a class="toggle-vis" data-column="4">Chapter Counts</a></li>
						<li><a class="toggle-vis" data-column="5">Status</a></li>
					</ul>
				</div>
				<div class="col s12 m12 l12">
					<div class="card-panel">
						<table id="table_category" class="responsive-table display" cellspacing="0">		         
							<thead>
								<tr>
									<th class="hide-column">Category ID</th>
									<th>No.</th>
									<th>Image</th>
									<th>Category Name</th>
									<th>Chapter Counts</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>   

							<tbody>
								<?php $i = 1;
									while($data = mysqli_fetch_array($result)) {
								?>
								<tr>
									<td class="hide-column"><?php echo $data['cid'];?></td>
									<td>
										<?php echo $i;
											$i++;
										?>
									</td>
									<td><img class="materialboxed circle z-depth-2" data-caption="<?php echo $data['category_name'];?>"
										src="<?php echo UPLOAD_CATEGORY . $data['category_image'];?>"  height="54px" width="54px" 
									style="object-fit: fill;"/></td>
									<td><?php echo $data['category_name'];?> </td>
									<td><?php echo $data['category_count'];?></td>
									<td>
										<?php if($data['active']==1) {?>
											<span class="task-cat green"><?php echo ACTIVE;?></span>
										<?php } else { ?>
											<span class="task-cat red"><?php echo INACTIVE;?></span>
										<?php }  ?>
									</td>
									<td>
										<a href="edit_category.php?id=<?php echo $data['cid'];?>" class="tooltipped btn-floating activator waves-effect waves-light darken-2" data-position="top" data-delay="0" data-tooltip="Edit" >
											<i class="mdi-editor-mode-edit"></i>
										</a> &nbsp;
										
											<a class="tooltipped btn-floating waves-effect waves-light" data-position="top" 
												data-delay="0" data-tooltip="Delete" onclick="swal({
															title: 'Delete', text: 'You will not be able to recover this item!',
															type: 'warning', showCancelButton: true, confirmButtonColor: '#ff5722', 
															confirmButtonText: 'Yes, delete it!', cancelButtonText: 'No, cancel!',
															closeOnConfirm: false, closeOnCancel: false
														}, function (isConfirm) {
															if (isConfirm) {
																window.location.href = 'delete_category.php?id=<?php echo $data['cid'];?>';
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

<?php
	include('templates/fcm.php');
?>

<?php

	$users = $_SESSION['users'];

  //user role
	$roles = array(
		'100' => 'Super Admin',
		'101' => 'Admin',
		'102' => 'Moderator',
	);

	$sql_query = "SELECT * FROM tbl_user ORDER BY id DESC";
	$result = mysqli_query($connect, $sql_query);

 ?>

 <?php

	if (isset($_GET['id'])) {

        $sql = 'SELECT * FROM tbl_user WHERE id=\''.$_GET['id'].'\'';
        $result = mysqli_query($connect, $sql);

        Delete('tbl_user','id='.$_GET['id'].'');

        header("location: users.php");
        exit;

    }

 ?>

<!-- START CONTENT -->
<section id="content">

	<!--breadcrumbs start-->
	<div id="breadcrumbs-wrapper" class=" grey lighten-3">
		<div class="container">
			<div class="row">
				<div class="col s12 m12 l12">
					<h5 class="breadcrumbs-title">Manage Users</h5>
					<ol class="breadcrumb">
						<li><a href="dashboard.php" class="deep-orange-text">Dashboard</a></li>
						<li><a class="active">Manage Users</a></li>
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
					<a href="add_user.php" class="btn waves-effect waves-light deep-orange">Add New User</a>
				</div>
				<div class="col s6 m6 l6" >
					<ul class="dropdown-menu pull-right footer2 tooltipped"
								data-position="top" data-delay="0" data-tooltip="Hide/Show Columns" style = "margin-top:0px">
						<li><a class="btn dropdown-button" href="javascript:void(0);"
							data-activates="dropdownUser">SELECT<i class="mdi-hardware-keyboard-arrow-down right"></i></a>
						</li>
					</ul>
					<ul id="dropdownUser" class="dropdown-content">
						<!-- <li><a class="toggle-vis" data-column="1">No.</a></li>-->
						<li><a class="toggle-vis" data-column="2">Name</a></li>
						<li><a class="toggle-vis" data-column="3">Email</a></li>
						<li><a class="toggle-vis" data-column="4">Last Login</a></li>
						<li><a class="toggle-vis" data-column="5">Status</a></li>
					</ul>
				</div>

				<div class="col s12 m12 l12">	 
					<div class="card-panel">
						<table id="table_user" class="responsive-table display " cellspacing="0">
							<thead>
								<tr>
									<th class="hide-column">User ID</th>
									<th>No.</th>
									<th>Name</th>
									<th>Email</th>
									<th>Last Login</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>

							<tbody>
								<?php
									$i = 1;
									while($data = mysqli_fetch_array($result)) {
										$old_date_timestamp = strtotime($data['updatedAt']);
										//$new_date = date('l, F d \'y h:i a', $old_date_timestamp); //Tuesday, July 17 '18 02:46 pm
										$new_date = date('D, M d \'y H:i:s', $old_date_timestamp); //Tue, Jul 17 '18 15:46:00

								?>
								<tr>
									<td class="hide-column"><?php echo $data['id'];?></td>
									<td>
										<?php
											echo $i;
											$i++;
										?>
									</td>
									<td><?php echo $data['username'];?></td>
									<td><?php echo $data['email'];?></td>
									<td><?php echo $new_date;?></td>
									<td>
										<?php if($data['active']==1) {?>
											<span class="task-cat green"><?php echo ACTIVE;?></span>
										<?php } else { ?>
											<span class="task-cat red"><?php echo INACTIVE;?></span>
										<?php }  ?>
									</td>
									<td>
									<?php if($users['user_role'] == 100 || $users['username'] == $data['username']) { ?>
											<a href="edit_user.php?id=<?php echo $data['id']; ?>"
											class="tooltipped btn-floating activator waves-effect waves-light darken-2"
											data-position="top" data-delay="0" data-tooltip="Edit">
												<i class="mdi-editor-mode-edit"></i>
											</a>
										
										<?php
										}
											if($users['user_role'] == 100) {
												if ($data['user_role'] == 100) {
												} else {
										?>
												&nbsp;
												<a class="tooltipped btn-floating waves-effect waves-light" data-position="top" 
													data-delay="0" data-tooltip="Delete" onclick="swal({
																title: 'Delete', text: 'You will not be able to recover this item!',
																type: 'warning', showCancelButton: true, confirmButtonColor: '#ff5722', 
																cancelButtonColor: '#3085d6',
																confirmButtonText: 'Yes, delete it!', cancelButtonText: 'No, cancel!',
																closeOnConfirm: false, closeOnCancel: false
															}, function (isConfirm) {
																if (isConfirm) {
																	window.location.href = 'users.php?id=<?php echo $data['id'];?>';
																} else {
																	swal('<?php echo DELETE_TITLE; ?>', '<?php echo DELETE_CANCELLED; ?>', 'error');
																}
															});" >
													<i class="mdi-action-delete"></i>
												</a>


											</td>
										<?php
												}
											}
										?>
									</td>
								</tr>
								<?php } ?>
							</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
		

</section>

<?php

	include_once('functions.php'); 

	$new_error = false;

	if (isset($_POST['btnAdd'])) {

		$username   = $_POST['username'];
		$password   = $_POST['password'];
		$repassword = $_POST['repassword'];
        $email = $_POST['email'];
        $active = $_POST['status'];
		//$role  = $_POST['role'] ? : '102';
        $role   = $_POST['role'];

		if (strlen($username) < 3) {
			$new_error[] = 'Username is too short!';
		}

		if (empty($password)) {
			$new_error[] = 'Password can not be empty!';
		}

		if ($password != $repassword) {
			$new_error[] = 'Password does not match!';
		}
        
		$password = hash('sha256',$username.$password);
		$e_password = hash('sha256',$email.$password);
		
		if (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
			$new_error[] = 'Email is not valid!'; 
		}

		// $query = mysqli_query($connect, "SELECT email FROM tbl_user where email = '$email' ");
		// if(mysqli_num_rows($query) > 0) {
		//     $error[] = 'Email already exists!'; 
		// }

		if (!$new_error) {

			$sql = "SELECT * FROM tbl_user WHERE (username = '$username' OR email = '$email');";
            $result = mysqli_query($connect, $sql);
            if (mysqli_num_rows($result) > 0) {

            	$row = mysqli_fetch_assoc($result);

            	if ($username == $row['username']) {

                	$new_error[] = 'Username already exists!';

            	} 

            	if ($email == $row['email']) {

                	$new_error[] = 'Email already exists!';

            	}

	        } else {
                
                $date = date('Y-m-d H:i:s', time());

				$sql = "INSERT INTO tbl_user (username, password, e_password, email, active, user_role, createdAt, updatedAt) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

				$insert = $connect->prepare($sql);
				$insert->bind_param('ssssssss', $username, $password, $e_password, $email, $active, $role, $date, $date);
				$insert->execute();

                $insert_result = $insert->store_result();
                $insert->close();
                
                if($insert_result) {
                    $error['add_user_form'] = "<div class='card-panel green lighten-4'>
                                                    <span class='green-text text-darken-2'>
                                                            User added successfully.
                                                    </span>
                                                </div>";
                } else {
                    $error['add_user_form'] = "<div class='card-panel red lighten-4'>
                                                    <span class='red-text text-darken-2'>
                                                        Insertion failed.
                                                    </span>
                                                </div>";
                }
			}
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
                    <h5 class="breadcrumbs-title">Add User</h5>
                    <ol class="breadcrumb">
                        <li><a href="dashboard.php" class="deep-orange-text">Dashboard</a></li>
                        <li><a href="users.php" class="deep-orange-text">Manage Users</a></li>
                        <li><a class="active">Add User</a></li>
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
                            <form method="post" class="col s12">
                                <div class="row">
                                    <div class="input-field col s12">

                                        <?php echo $new_error ? '<div class="card-panel red lighten-4" role="alert"><span class="red-text text-darken-2">'. implode('<br>', $new_error) . '</span></div>' : '';?>

                                        <?php echo isset($error['add_user_form']) ? $error['add_user_form'] : ''; ?>

                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input type="text" name="username" id="username" />
                                                <label for="category_name">Username</label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input type="email" name="email" id="email" />
                                                <label for="email">Email</label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input type="password" name="password" id="password" />
                                                <label for="password">Password</label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input type="password" name="repassword" id="repassword" />
                                                <label for="repassword">Re Password</label>
                                            </div>
                                        </div>

										<div class="row">
											<div class="input-field col s12">
											<select name="status">
												<option value="1" selected><?php echo ACTIVE; ?></option>
												<option value="0"><?php echo INACTIVE; ?></option>
											</select>
											<label>Status</label><?php echo isset($error['status']) ? $error['status'] : '';?></div>
										</div>

                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input type="hidden" name="role" id="role" value="102" />
                                                <!-- <label for="role">User Level</label> -->
                                            </div>
                                        </div>

                                        <!-- <div class="row">
        							        <div class="input-field col s12">
        	                                      <select name="role" id="role">
                                                    <option value="100" selected>Super Admin</option>
													<option value="101">Admin</option>
													<option value="102">Moderator</option>
                                                </select>
                                                <label>User Level</label>
                                            </div>
                                       </div> -->

                                        <button class="btn deep-orange waves-effect waves-light"
                                                type="submit" name="btnAdd">Submit
                                            <i class="mdi-content-send right"></i>
                                        </button>

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
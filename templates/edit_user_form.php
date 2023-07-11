<?php
	include_once('functions.php');

    $users = $_SESSION['users'];

	$new_error = false;

	/**
	 * Call Detail Member by id
	 */
	if (isset($_GET['id']) && is_numeric($_GET['id'])) {
		$id =  $_GET['id'];

		$sql = "SELECT id, username, password, email, active, user_role FROM tbl_user WHERE id = ? LIMIT 1";
		$stmt = $connect->prepare($sql);
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($id, $username, $password, $email, $active, $role );
		$stmt->fetch();

	} else {
		die('404 Oops!!!');
	}

	/**
	 * Update Command
	 */
	if (isset($_POST['btnEdit'])) {
		$newusername   = trim($_POST['username']);
		$newpassword   = trim($_POST['password']);
		$newrepassword = trim($_POST['repassword']);
        $newemail = $_POST['email'];
        $active = $_POST['status'];
        //$newrole  = $_POST['role'] ? : '102';
		$newrole  = $_POST['role'];

		if (strlen($newusername) < 3) {
			$new_error[] = 'Username is too short!';
		}

		if (empty($newpassword)) {
			$new_error[] = 'Password can not be empty!';
		}

		if ($newpassword != $newrepassword) {
			$new_error[] = 'Password does not match!';
		}

		$newpassword = hash('sha256',$newusername.$newpassword);
		$new_e_password = hash('sha256',$newemail.$newpassword);

		if (filter_var($newemail, FILTER_VALIDATE_EMAIL) === FALSE) {
			$new_error[] = 'Email is not valid!';
		}

		if (!$new_error) {

            $date = date('Y-m-d H:i:s', time());

			$sql_query = "UPDATE tbl_user SET username = ?, password = ?, e_password = ?, email = ?, active = ?, user_role = ?, updatedAt = ? WHERE id = ?";
			$stmt = $connect->stmt_init();
			if($stmt->prepare($sql_query)) {
				$stmt->bind_param(
					'sssssssi',
					$newusername,
					$newpassword,
					$new_e_password,
					$newemail,
					$active,
                    $newrole,
                    $date,
					$id
				);
				// Execute query
                $stmt->execute();
                // store result
                $update_result = $stmt->store_result();
                $stmt->close();
			}
			
			
        }
        
        if (!$new_error) {
            // check update result
			if($update_result) {
				$error['update_user_form'] = "<div class='card-panel green lighten-4'>
												<span class='green-text text-darken-2'>
														User updated successfully.
												</span>
											</div>";
			} else {
				$error['update_user_form'] = "<div class='card-panel red lighten-4'>
                                                <span class='red-text text-darken-2'>
                                                        Update failed.
                                                </span>
                                            </div>";
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
                    <h5 class="breadcrumbs-title">Edit User</h5>
                    <ol class="breadcrumb">
                        <li><a href="dashboard.php" class="deep-orange-text">Dashboard</a></li>
                        <li><a href="users.php" class="deep-orange-text">Manage Users</a></li>
                        <li><a class="active">Edit User</a></li>
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

                                        <?php echo isset($error['update_user_form']) ? $error['update_user_form'] : ''; ?>

                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input type="text" name="username" id="username" placeholder="username" value="<?php echo $username; ?>" readonly />
                                                <label for="category_name">Username</label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input type="email" name="email" id="email" placeholder="john@mail.com" value="<?php echo $email; ?>" />
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
                                            <div class="input-field col m12 s12">
                                            <select name="status">
                                                <option value="1" <?php if($active == 1) echo "selected"; else echo ""; ?> ><?php echo ACTIVE; ?></option>
                                                <option value="0" <?php if($active == 0) echo "selected"; else echo ""; ?> ><?php echo INACTIVE; ?></option>
                                            </select>
                                            <label>Status</label><?php echo isset($error['status']) ? $error['status'] : '';?></div>
                                        </div>

                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input type="hidden" name="role" id="role" value="<?php echo $role; ?>"/>
                                                <!-- <label for="role">User Level</label> -->
                                            </div>
                                        </div>
                                        <?php  if($users['user_role'] == 100 || $users['username'] == $username) { ?>
                                            <button class="btn deep-orange waves-effect waves-light"
                                                    type="submit" name="btnEdit">Update
                                                <i class="mdi-content-send right"></i>
                                            </button>
                                        <?php } else { ?>
                                            <div class='card-panel red lighten-4'>
                                                <span class='red-text text-darken-2'>
                                                        You do have rights to update this user.
                                                </span>
                                            </div>
                                        <?php } ?>

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

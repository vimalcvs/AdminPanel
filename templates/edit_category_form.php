<?php include_once('functions.php'); ?>

<?php
    if (isset($_GET['id'])) {
        $ID = $_GET['id'];
    } else {
        $ID = "";
    }

    // create array variable to store category data
    $category_data = array();

    $sql_query = "SELECT category_image
                    FROM tbl_category
                    WHERE cid = ?";

    $stmt_category = $connect->stmt_init();
    if ($stmt_category->prepare($sql_query)) {
        // Bind your variables to replace the ?s
        $stmt_category->bind_param('s', $ID);
        // Execute query
        $stmt_category->execute();
        // store result
        $stmt_category->store_result();
        $stmt_category->bind_result($previous_category_image);
        $stmt_category->fetch();
        $stmt_category->close();
    }


    if (isset($_POST['btnEdit'])) {
        $category_name = $_POST['category_name'];
        $active = $_POST['status'];

        // get image info
        $menu_image = $_FILES['category_image']['name'];
        $image_error = $_FILES['category_image']['error'];
        $image_type = $_FILES['category_image']['type'];
        
        // create array variable to handle error
        $error = array();

        if (empty($category_name)) {
            $error['category_name'] = " <span class='label red-text'>Must Insert!</span>";
        }
        
        if(!isset($active)){
            $error['status'] = " <span class='label red-text'>Required, please fill out this field!!</span>";
        }
        	
        // common image file extensions
        $allowedExts = array("gif", "jpeg", "jpg", "png");

        // get image file extension
        error_reporting(E_ERROR | E_PARSE);
        $extension = end(explode(".", $_FILES["category_image"]["name"]));

        if (!empty($menu_image)) {
            if (!(($image_type == "image/gif") ||
                    ($image_type == "image/jpeg") ||
                    ($image_type == "image/jpg") ||
                    ($image_type == "image/x-png") ||
                    ($image_type == "image/png") ||
                    ($image_type == "image/pjpeg")) &&
                !(in_array($extension, $allowedExts))
            ) {

                $error['category_image'] = " <span class='label red-text'>Image type must jpg, jpeg, gif, or png!</span>";
            }
        }

        if (!empty($category_name)  && empty($error['category_image'])) {
            $date = date('Y-m-d H:i:s', time());
            if (!empty($menu_image)) {
                // create random image file name
                $string = '0123456789';
                $file = preg_replace("/\s+/", "_", $_FILES['category_image']['name']);
                $function = new functions;
                $category_image = $function->get_random_string($string, 4) . "-" . date("Y-m-d") . "." . $extension;

                // delete previous image
                $delete = unlink(UPLOAD_CATEGORY . "$previous_category_image");

                // upload new image
                $upload = move_uploaded_file($_FILES['category_image']['tmp_name'], UPLOAD_CATEGORY . $category_image);

                $sql_query = "UPDATE tbl_category
                                SET category_name = ?, category_image = ?, active = ?, updatedAt = ?
                                WHERE cid = ?";

                $upload_image = $category_image;
                $stmt = $connect->stmt_init();
                if ($stmt->prepare($sql_query)) {
                    // Bind your variables to replace the ?s
                    $stmt->bind_param('sssss',
                        $category_name,
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
                $sql_query = "UPDATE tbl_category
                SET category_name = ?, active = ?, updatedAt = ?
                WHERE cid = ?";

                $stmt = $connect->stmt_init();
                if ($stmt->prepare($sql_query)) {
                    // Bind your variables to replace the ?s
                    $stmt->bind_param('ssss',
                        $category_name,
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
            if ($update_result) {
                $error['update_category'] = "<div class='card-panel green lighten-4'>
                                                        <span class='green-text text-darken-2'>
                                                            Category updated successfully
                                                        </span>
                                                    </div>";
            } else {
                $error['update_category'] = "<div class='card-panel red lighten-4'>
                                                        <span class='red-text text-darken-2'>
                                                            Update failed.
                                                        </span>
                                                    </div>";
            }
        }

    }

    // create array variable to store previous data
    $data = array();

    $sql_query = "SELECT cid, category_name, category_image, active
                    FROM tbl_category
                    WHERE cid = ?";

    $stmt = $connect->stmt_init();
    if ($stmt->prepare($sql_query)) {
        // Bind your variables to replace the ?s
        $stmt->bind_param('s', $ID);
        // Execute query
        $stmt->execute();
        // store result
        $stmt->store_result();
        $stmt->bind_result($data['cid'],
            $data['category_name'],
            $data['category_image'],
            $data['active']
        );
        $stmt->fetch();
        $stmt->close();
    }

    if (isset($_POST['btnCancel'])) {
        header("location: category.php");
    }

?>

    <!-- START CONTENT -->
    <section id="content">

        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
            <div class="container">
                <div class="row">
                    <div class="col s12 m12 l12">
                        <h5 class="breadcrumbs-title">Edit Category</h5>
                        <ol class="breadcrumb">
                            <li><a href="dashboard.php" class="deep-orange-text">Dashboard</a></li>
                            <li><a href="category.php" class="deep-orange-text">Manage Category</a></li>
                            <li><a class="active">Edit Category</a></li>
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
                                        <div class="input-field col s12">
                                            <?php echo isset($error['update_category']) ? $error['update_category'] : ''; ?>

                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input type="text" name="category_name" id="category_name"
                                                           value="<?php echo $data['category_name']; ?>" required/>
                                                    <label for="category_name">Category
                                                        Name</label><?php echo isset($error['category_name']) ? $error['category_name'] : ''; ?>
                                                </div>
                                            </div>

						                    <div class="row">
							                    <div class="input-field col s12">
	                                            <select name="status">
                                                    <option value="1" <?php if($data['active'] == 1) echo "selected"; ?> ><?php echo ACTIVE; ?></option>
                                                    <option value="0" <?php if($data['active'] == 0) echo "selected"; ?> ><?php echo INACTIVE; ?></option>
	                                            </select>
	                                            <label>Status</label><?php echo isset($error['status']) ? $error['status'] : '';?></div>
                                            </div>

                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input type="file" name="category_image" id="category_image"
                                                           class="dropify-image" data-max-file-size="1M"
                                                           data-allowed-file-extensions="jpg png gif"
                                                           data-default-file=<?php echo UPLOAD_CATEGORY . $data['category_image']; ?>
                                                           data-show-remove="false"/>
                                                    <div class="div-error"><?php echo isset($error['category_image']) ? $error['category_image'] : ''; ?></div>
                                                </div>
                                            </div>
                                            <br/>
                                        
                                            <button class="btn deep-orange waves-effect waves-light"
                                                    type="submit" name="btnEdit">Update
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

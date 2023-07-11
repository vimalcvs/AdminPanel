<?php include_once('functions.php'); ?>

<?php
if(isset($_POST['btnAdd'])){
    $category_name = $_POST['category_name'];
    $active = $_POST['status'];
    // get image info
    $menu_image = $_FILES['category_image']['name'];
    $image_error = $_FILES['category_image']['error'];
    $image_type = $_FILES['category_image']['type'];

    // create array variable to handle error
    $error = array();

    if(empty($category_name)){
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

    if($image_error > 0){
        $error['category_image'] = " <span class='label red-text'>You're not insert images!!</span>";
    } else if(!(($image_type == "image/gif") ||
            ($image_type == "image/jpeg") ||
            ($image_type == "image/jpg") ||
            ($image_type == "image/x-png") ||
            ($image_type == "image/png") ||
            ($image_type == "image/pjpeg")) &&
        !(in_array($extension, $allowedExts))){

        $error['category_image'] = " <span class='label red-text'>Image type must jpg, jpeg, gif, or png!</span>";
    }

    if(!empty($category_name)){
        $date = date('Y-m-d H:i:s', time());

        // create random image file name
        $string = '0123456789';
        $file = preg_replace("/\s+/", "_", $_FILES['category_image']['name']);
        $function = new functions;
        $menu_image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;

        // upload new image
        $upload = move_uploaded_file($_FILES['category_image']['tmp_name'], UPLOAD_CATEGORY .$menu_image);
        $upload_image = $menu_image;

        // insert new data to menu table
        $sql_query = "INSERT INTO tbl_category (category_name, category_image, active, createdAt, updatedAt)
						VALUES(?,?,?,?,?)";

        $stmt = $connect->stmt_init();
        if($stmt->prepare($sql_query)) {
            // Bind your variables to replace the ?s
            $stmt->bind_param('sssss',
                $category_name,
                $upload_image,
                $active,
                $date,
                $date
            );
            // Execute query
            $stmt->execute();
            // store result
            $result = $stmt->store_result();
            $stmt->close();
        }

        if($result) {
            $error['add_category'] = "<div class='card-panel green lighten-4'>
											    <span class='green-text text-darken-2'>
												   New category added successfully.
											    </span>
											</div>";
        } else {
            $error['add_category'] = "<div class='card-panel red lighten-4'>
											    <span class='red-text text-darken-2'>
												    Added Failed
											    </span>
											</div>";
        }
    }

}

if(isset($_POST['btnCancel'])){
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
                        <h5 class="breadcrumbs-title">Add New Category</h5>
                        <ol class="breadcrumb">
                            <li><a href="dashboard.php" class="deep-orange-text">Dashboard</a></li>
                            <li><a href="category.php" class="deep-orange-text">Manage Category</a></li>
                            <li><a class="active">Add New Category</a></li>
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
                                        <div class="input-field col s12">
                                            <?php echo isset($error['add_category']) ? $error['add_category'] : '';?>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input type="text" name="category_name" id="category_name" required/>
                                                    <label for="category_name">Category Name</label><?php echo isset($error['category_name']) ? $error['category_name'] : '';?>
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
                                                    <input type="file" name="category_image" id="category_image" id="category_image"
                                                        class="dropify-image" data-max-file-size="1M" data-allowed-file-extensions="jpg png gif" />
                                                    <div class="div-error"><?php echo isset($error['category_image']) ? $error['category_image'] : '';?></div>
                                                </div>
                                            </div>

                                            <br/>
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
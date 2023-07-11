<?php include_once('functions.php'); ?>

<?php
if(isset($_GET['id'])){
    $ID = $_GET['id'];
}else{
    $ID = "";
}

// create array variable to store category data
$category_data = array();

$sql_query = "SELECT image FROM tbl_notification WHERE id = ?";

$stmt_category = $connect->stmt_init();
if($stmt_category->prepare($sql_query)) {
    // Bind your variables to replace the ?s
    $stmt_category->bind_param('s', $ID);
    // Execute query
    $stmt_category->execute();
    // store result
    $stmt_category->store_result();
    $stmt_category->bind_result($previous_image);
    $stmt_category->fetch();
    $stmt_category->close();
}


if(isset($_POST['btnEdit'])){
    $message = $_POST['message'];
    $title = $_POST['title'];

    // get image info
    $menu_image = $_FILES['image']['name'];
    $image_error = $_FILES['image']['error'];
    $image_type = $_FILES['image']['type'];

    // create array variable to handle error
    $error = array();

    if(empty($message)){
        $error['message'] = " <span class='label red-text'>Must Insert!</span>";
    }

    
    // common image file extensions
    $allowedExts = array("gif", "jpeg", "jpg", "png");

    // get image file extension
    error_reporting(E_ERROR | E_PARSE);
    $extension = end(explode(".", $_FILES["image"]["name"]));

    if(!empty($menu_image)){
        if(!(($image_type == "image/gif") ||
                ($image_type == "image/jpeg") ||
                ($image_type == "image/jpg") ||
                ($image_type == "image/x-png") ||
                ($image_type == "image/png") ||
                ($image_type == "image/pjpeg")) &&
            !(in_array($extension, $allowedExts))){

            $error['image'] = " <span class='label red-text'>Image type must jpg, jpeg, gif, or png!</span>";
        }
    }  

    if(!empty($message) && !empty($title) && empty($error['image'])){

        $date = date('Y-m-d H:i:s', time());

        if(!empty($menu_image)) {
            // create random image file name
            $string = '0123456789';
            $file = preg_replace("/\s+/", "_", $_FILES['image']['name']);
            $function = new functions;
            $image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;

            // delete previous image
            $delete = unlink(UPLOAD_NOTIFICATION . "$previous_image");

            // upload new image
            $upload = move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_NOTIFICATION .$image);

            $sql_query = "UPDATE tbl_notification
							SET title = ?,message = ?, image = ?, updatedAt = ?
							WHERE id = ?";

            $upload_image = $image;
            $stmt = $connect->stmt_init();
            if($stmt->prepare($sql_query)) {
                // Bind your variables to replace the ?s
                $stmt->bind_param('sssss',
                    $title,
                    $message,
                    $upload_image,
                    $date,
                    $ID);
                // Execute query
                $stmt->execute();
                // store result
                $update_result = $stmt->store_result();
                $stmt->close();
            }
        } else {

            $sql_query = "UPDATE tbl_notification
                            SET title = ?, message = ?, updatedAt = ?
                            WHERE id = ?";

            $stmt = $connect->stmt_init();
            if($stmt->prepare($sql_query)) {
                // Bind your variables to replace the ?s
                $stmt->bind_param('ssss',
                    $title,
                    $message,
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
            $error['update_notification'] = "<div class='card-panel green lighten-4'>
                                            <span class='green-text text-darken-2'>
                                                    Push notification template updated successfully.
                                            </span>
                                        </div>";
        } else {
            $error['update_notification'] = "<div class='card-panel red lighten-4'>
												    <span class='red-text text-darken-2'>
													    Update failed.
												    </span>
												</div>";
        }
    }

}

// create array variable to store previous data
$data = array();

$sql_query = "SELECT id, title, message, image
				FROM tbl_notification
				WHERE id = ?";

$stmt = $connect->stmt_init();
if($stmt->prepare($sql_query)) {
    // Bind your variables to replace the ?s
    $stmt->bind_param('s', $ID);
    // Execute query
    $stmt->execute();
    // store result
    $stmt->store_result();
    $stmt->bind_result($data['id'],
        $data['title'],
        $data['message'],
        $data['image']
    );
    $stmt->fetch();
    $stmt->close();
}

?>

    <!-- START CONTENT -->
    <section id="content">

        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
            <div class="container">
                <div class="row">
                    <div class="col s12 m12 l12">
                        <h5 class="breadcrumbs-title">Edit Notification</h5>
                        <ol class="breadcrumb">
                            <li><a href="dashboard.php" class="deep-orange-text">Dashboard</a></li>
                            <li><a href="notification.php" class="deep-orange-text">Push Notification</a></li>
                            <li><a class="active">Edit Notification</a></li>
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
                                            <?php echo isset($error['update_notification']) ? $error['update_notification'] : '';?>
                                            
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input type="text" name="title" id="title" value="<?php echo $data['title']; ?>" required/>
                                                    <label for="title">Title</label><?php echo isset($error['title']) ? $error['title'] : '';?>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <textarea name="message" id="message" class="materialize-textarea"><?php echo $data['message']; ?></textarea>
                                                    <!--<input type="text" name="message" id="message" value="<?php //echo $data['message']; ?>" required/>-->
                                                    <label for="message">Message</label><?php echo isset($error['message']) ? $error['message'] : '';?>
                                                </div>
                                            </div>

                                            <?php
                                            if($data['image'] == NULL) {
                                                ?>
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <input type="file" name="image" id="image" class="dropify-notification" data-max-file-size="1M" data-allowed-file-extensions="jpg png gif" data-default-file="assets/images/no-image.png" />
                                                    </div>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <input type="file" name="image" id="image" class="dropify-notification" data-max-file-size="1M" data-allowed-file-extensions="jpg png gif" data-default-file="<?php echo UPLOAD_NOTIFICATION . $data['image']; ?>" />
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <br/>
                                            
                                            <button class="btn deep-orange waves-effect waves-light left"
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

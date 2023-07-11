<?php include_once('templates/functions.php'); ?>
<?php include('session.php'); ?>
<?php $selectedMenu = "setting"; ?>
<?php include("templates/common/menubar.php"); ?>

<script src="assets/js/jquery-1.9.1.min.js"></script>

<?php
include('templates/fcm.php');
$id = '1';

$qry = "SELECT * FROM tbl_settings where id = '$id'";
$result = mysqli_query($connect, $qry);
$data = mysqli_fetch_assoc($result);

if(isset($_POST['submitAppSettings'])) {
    $app_direction = "LTR";
    
    if(empty($_POST['app_direction'])){

        $app_direction = "LTR";

    } else {

        $app_direction = $_POST['app_direction'];

    }

    // get image info
    $app_logo = $_FILES['app_logo']['name'];
    $image_error = $_FILES['app_logo']['error'];
    $image_type = $_FILES['app_logo']['type'];

    // common image file extensions
    $allowedExts = array("gif", "jpeg", "jpg", "png");

    // get image file extension
    error_reporting(E_ERROR | E_PARSE);
    $extension = end(explode(".", $_FILES["app_logo"]["name"]));

    if(!empty($app_logo)){
        if(!(($image_type == "image/gif") ||
            ($image_type == "image/jpeg") ||
            ($image_type == "image/jpg") ||
            ($image_type == "image/x-png") ||
            ($image_type == "image/png") ||
            ($image_type == "image/pjpeg")) &&
            !(in_array($extension, $allowedExts))){

            $error['app_logo'] = "*<span class='label red-text'>Image type must jpg, jpeg, gif, or png!</span>";
        }
    }

    if(!empty($app_logo)) {
        $sql_query = "SELECT app_logo FROM tbl_settings WHERE id = '$id'";
        $result = mysqli_query($connect, $sql_query);
        $app_data = mysqli_fetch_assoc($result);
        $previous_logo = $app_data['app_logo'];

        // create random image file name
        $string = '0123456789';
        $file = preg_replace("/\s+/", "_", $_FILES['app_logo']['name']);
        $function = new functions;
        $app_logo = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;

        // delete previous image
        $delete = unlink(UPLOAD_APP_IMAGE ."$previous_logo");
        
        // upload new image
        $unggah = UPLOAD_APP_IMAGE . $app_logo;
        $upload = move_uploaded_file($_FILES['app_logo']['tmp_name'], $unggah);

        $sql_query = "SELECT * FROM tbl_settings WHERE id = '$id'";
        $my_res = mysqli_query($connect, $sql_query);
        //$img_row=  mysqli_fetch_assoc($my_res);
        $date = date('Y-m-d H:i:s', time());
    
        $data = array(
            'app_name' => $_POST['app_name'],
            'app_logo' => $app_logo,
            'app_version' => $_POST['app_version'],
            'app_description' => $_POST['app_description'],
            'app_author' => $_POST['app_author'],
            'app_contact' => $_POST['app_contact'],
            'app_email' => $_POST['app_email'],
            'app_website' => $_POST['app_website'],
            'app_developed_by' => $_POST['app_developed_by'],
            'app_mandatory_login' => $_POST['app_mandatory_login'],
            'app_channel_grid' => $_POST['app_channel_grid'],
            'app_direction' => $app_direction,
            'updatedAt' => $date
        );
        
    } else {

        $sql_query = "SELECT * FROM tbl_settings WHERE id = '$id'";
        $my_res = mysqli_query($connect, $sql_query);
        //$img_row=  mysqli_fetch_assoc($my_res);
        $date = date('Y-m-d H:i:s', time());
    
        $data = array(
            'app_name' => $_POST['app_name'],
            'app_version' => $_POST['app_version'],
            'app_description' => $_POST['app_description'],
            'app_author' => $_POST['app_author'],
            'app_contact' => $_POST['app_contact'],
            'app_email' => $_POST['app_email'],
            'app_website' => $_POST['app_website'],
            'app_developed_by' => $_POST['app_developed_by'],
            'app_mandatory_login' => $_POST['app_mandatory_login'],
            'app_channel_grid' => $_POST['app_channel_grid'],
            'app_direction' => $app_direction,
            'updatedAt' => $date
        );
    }
    
    $update_content = Update('tbl_settings', $data, "WHERE id = '$id'");

    if ($update_content > 0) {
        $_SESSION['msg_app_setting'] = "";
    
        /*
		$users_sql = "SELECT distinct(token) as token FROM tbl_tokens";
        $users_result = mysqli_query($connect, $users_sql);
        
        while($user_row = mysqli_fetch_assoc($users_result)) {
            $data = array("backgroundNotification" => true);
            echo SEND_FCM_NOTIFICATION($user_row['token'], $data);
        }
		*/
        
        header( "Location:settings.php#appSettings");
        exit;
    }

}

if(isset($_POST['submitForceSettings'])) {

    $sql_query = "SELECT * FROM tbl_settings WHERE id = '$id'";
    $my_res = mysqli_query($connect, $sql_query);
    //$img_row=  mysqli_fetch_assoc($my_res);
    $date = date('Y-m-d H:i:s', time());
    
    $data = array(
        'force_version_code' => $_POST['force_version_code'],
        'force_update' => $_POST['force_update'],
        'force_title' => $_POST['force_title'],
        'force_message' => $_POST['force_message'],
        'force_yes_button' => $_POST['force_yes_button'],
        'force_no_button' => $_POST['force_no_button'],
        'force_source' => $_POST['force_source'],
        'force_apk_link' => $_POST['force_apk_link'],
        'updatedAt' => $date
    );

    $update_content = Update('tbl_settings', $data, "WHERE id = '$id'");

    if ($update_content > 0) {
        $_SESSION['msg_autoupdate_setting'] = "";
        header( "Location:settings.php#autoupdateSettings");
        exit;
    }
}

if(isset($_POST['submitAdmobSettings'])) {

    $sql_query = "SELECT * FROM tbl_settings WHERE id = '$id'";
    $my_res = mysqli_query($connect, $sql_query);
    //$img_row=  mysqli_fetch_assoc($my_res);
    $date = date('Y-m-d H:i:s', time());

    $data = array(
        'publisher_id' => $_POST['publisher_id'],
        'interstital_ad' => $_POST['interstital_ad'],
        'interstital_ad_id' => $_POST['interstital_ad_id'],
        'interstital_ad_click' => $_POST['interstital_ad_click'],
        'banner_ad' => $_POST['banner_ad'],
        'banner_ad_id' => $_POST['banner_ad_id'],
        'updatedAt' => $date
    );

    $update_content = Update('tbl_settings', $data, "WHERE id = '$id'");

    if ($update_content > 0) {
        $_SESSION['msg_admob_setting'] = "";
        header( "Location:settings.php#admobSettings");
        exit;
    }
}

if(isset($_POST['submitNotification'])) {

    $sql_query = "SELECT * FROM tbl_settings WHERE id = '$id'";
    $my_res = mysqli_query($connect, $sql_query);
    //$img_row=  mysqli_fetch_assoc($my_res);
    $date = date('Y-m-d H:i:s', time());

    $data = array(
        'app_fcm_key' => $_POST['app_fcm_key'],
        'api_key' => $_POST['api_key'],
        'youtube_dev_api' => $_POST['youtube_dev_api'],
        'updatedAt' => $date
    );

    $update_content = Update('tbl_settings', $data, "WHERE id = '$id'");

    if ($update_content > 0) {
        $_SESSION['msg_notification_setting'] = "";
        header( "Location:settings.php#appNotification");
        exit;
    }
}

if(isset($_POST['submitPrivacyPolicy'])) {

    $sql_query = "SELECT * FROM tbl_settings WHERE id = '$id'";
    $my_res = mysqli_query($connect, $sql_query);
    $date = date('Y-m-d H:i:s', time());

    $data = array(
        'app_privacy_policy' => $_POST['app_privacy_policy'],
        'updatedAt' => $date
    );

    $update_content = Update('tbl_settings', $data, "WHERE id = '$id'");

    if ($update_content > 0) {
        $_SESSION['msg_privacy_policy'] = "";
        header( "Location:settings.php#privacyPolicy");
        exit;
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
                    <h5 class="breadcrumbs-title">Settings</h5>
                    <ol class="breadcrumb">
                        <li><a href="dashboard.php" class="deep-orange-text">Dashboard</a>
                        </li>
                        <li><a class="active">Settings</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!--breadcrumbs end-->

    <div class="container">
        <div class="section">
            <!-- 0. CardPanel Start -->
            <div class="card-panel">
                <!-- 1. tab tag Start -->
                <div class="row">

                    <div class="col s12 m12">
                        <ul id="tabs-swipe-demo" class="tabs">
                            <li class="tab l2 s12 m2"><a class="active" href="#appSettings">App Settings</a></li>
                            <li class="tab l2 s12 m2"><a href="#autoupdateSettings">Auto Update</a></li>
                            <li class="tab l2 s12 m2"><a href="#admobSettings">Admob Settings</a></li>
                            <li class="tab l2 s12 m3"><a href="#appNotification">Notification Settings</a></li>
                            <li class="tab l2 s12 m3"><a href="#privacyPolicy">Privacy Policy</a></li>
                        </ul>
                    </div>
                    <!-- 1. App Setting Start -->
                    <div id="appSettings" class="col s12">
                        <div class="row">
                            <form method="post" class="col s12" id="form-validation"  enctype="multipart/form-data">
								<div class="row">
                                    <br/>
								    <?php echo isset($error['error_data']) ? $error['error_data'] : '';?>
                                    <?php if(isset($_SESSION['msg_app_setting'])) { ?>
                                        <div class='card-panel green lighten-4'>
                                            <span class='green-text text-darken-2'>
                                                Successfully Saved.
                                            </span>
                                        </div>
                                    <?php unset($_SESSION['msg_app_setting']); }?>

									<div class="input-field col s12">
										<div class="row">
											<div class="input-field col l6 m6 s12">
											<input type="text" name="app_name" id="app_name" value="<?php echo $data['app_name']; ?>" maxlength=20 required/>
											<label for="app_name">App Name</label><?php echo isset($error['app_name']) ? $error['app_name'] : '';?>
                                            </div>
                                            <div class="input-field col s12 m6">
												<input type="text" name="app_version" id="app_version" value="<?php echo $data['app_version']; ?>" maxlength=20 required/>
												<label for="app_version">App Version</label><?php echo isset($error['app_version']) ? $error['app_version'] : '';?>
											</div>
										</div>

                                        <div class="row">
                                            <div class="input-field col l4 m4 s12">
                                                <select name="app_direction">
                                                    <option value="<?php echo LTR_DIRECTION; ?>" <?php if($data['app_direction'] == LTR_DIRECTION) echo "selected"; ?> ><?php echo LTR_DIRECTION; ?></option>
                                                    <option value="<?php echo RTL_DIRECTION; ?>" <?php if($data['app_direction'] == RTL_DIRECTION) echo "selected"; ?> ><?php echo RTL_DIRECTION; ?></option>
                                                </select>
                                                <label>Admin Panel Direction</label>
                                            </div>

                                            <div class="input-field col l4 m4 s12">
                                                <select name="app_mandatory_login">
                                                    <option value="1" <?php if($data['app_mandatory_login'] == 1) echo "selected"; ?> ><?php echo ENABLED; ?></option>
                                                    <option value="0" <?php if($data['app_mandatory_login'] == 0) echo "selected"; ?> ><?php echo DISABLED; ?></option>
                                                </select>
                                                <label>Mandatory Login (Just refresh the Android app to reflect changes)</label>
                                            </div>

                                            <div class="input-field col l4 m4 s12">
                                                <select name="app_channel_grid">
                                                    <option value="1" <?php if($data['app_channel_grid'] == 1) echo "selected"; ?> ><?php echo YES_GRID; ?></option>
                                                    <option value="0" <?php if($data['app_channel_grid'] == 0) echo "selected"; ?> ><?php echo NO_GRID; ?></option>
                                                </select>
                                                <label>Channels as Grid (Just refresh the Android app to reflect changes)</label>
                                            </div>
                                        </div>

										<div class="row">
											<div class="input-field col s12">
												<input type="file" id="input-file-now" name="app_logo" id="app_logo" 
													class="dropify-image" data-max-file-size="3M" 
                                                    data-default-file= <?php echo UPLOAD_APP_IMAGE . $data['app_logo']; ?>
                                                    data-allowed-file-extensions="jpg png gif"/>
												<div class="div-error"><?php echo isset($error['app_logo']) ? $error['app_logo'] : '';?></div>
											</div>
										</div>
										
										<div class="row">
											<div class="input-field col s12">
												<span class="grey-text text-grey lighten-2">App Description</span>
												<?php echo isset($error['app_description']) ? $error['app_description'] : '';?>
												<textarea name="app_description" id="app_description" class="materialize-textarea" rows="16">
                                                    <?php echo $data['app_description']; ?>
                                                </textarea>
												<script type="text/javascript" src="assets/js/ckeditor/ckeditor.js"></script>
												<script type="text/javascript">
															CKEDITOR.replace( 'app_description' );
															CKEDITOR.config.allowedContent = true;
												</script>
											</div>
										</div>
										
										<div class="row">
											<div class="input-field col l6 m6 s12">
												<input type="text" name="app_author" id="app_author" value="<?php echo $data['app_author']; ?>" maxlength=50 required/>
												<label for="app_author">Author</label><?php echo isset($error['app_author']) ? $error['app_author'] : '';?>
                                            </div>
                                            <div class="input-field col l6 m6 s12">
												<input type="text" name="app_contact" id="app_contact" value="<?php echo $data['app_contact']; ?>" maxlength=20 required/>
												<label for="app_contact">Contact</label><?php echo isset($error['app_contact']) ? $error['app_contact'] : '';?>
											</div>
										</div>
									
										<div class="row">
											<div class="input-field col l6 m6 s12">
												<input type="text" name="app_email" id="app_email" value="<?php echo $data['app_email']; ?>" maxlength=100 required/>
												<label for="app_email">Email</label><?php echo isset($error['app_email']) ? $error['app_email'] : '';?>
                                            </div>
                                            <div class="input-field col l6 m6 s12">
												<input type="text" name="app_website" id="app_website" value="<?php echo $data['app_website']; ?>" maxlength=100 required/>
												<label for="app_website">Website</label><?php echo isset($error['app_website']) ? $error['app_website'] : '';?>
											</div>
										</div>

										<div class="row">
											<div class="input-field col s12">
												<input type="text" name="app_developed_by" id="app_developed_by" value="<?php echo $data['app_developed_by']; ?>" maxlength=100 required/>
												<label for="app_developed_by">Developed by</label><?php echo isset($error['app_developed_by']) ? $error['app_developed_by'] : '';?>
											</div>
										</div>
                                        
                                       
                                        <div class="row">
                                            <div class="input-field col s12">												
                                                <button class="btn deep-orange waves-effect waves-light" type="submit" name="submitAppSettings" id="submitAppSettings" >Submit
                                                    <i class="mdi-content-send right"></i>
                                                </button>
                                            </div>
                                        </div>

									</div>
								<!--</div>-->
								</div>
							</form>
                        </div>
                    </div>
                    <!-- 1. App Setting End -->

                    <!-- 2. App Setting Start -->
                    <div id="autoupdateSettings" class="col s12">
                        <div class="row">
                            <form method="post" class="col s12" id="form-validation"  enctype="multipart/form-data">
								<div class="row">
                                    <br/>
								    <?php echo isset($error['error_data']) ? $error['error_data'] : '';?>
                                    <?php if(isset($_SESSION['msg_autoupdate_setting'])) { ?>
                                        <div class='card-panel green lighten-4'>
                                            <span class='green-text text-darken-2'>
                                                Successfully Saved.
                                            </span>
                                        </div>
                                    <?php unset($_SESSION['msg_autoupdate_setting']); }?>

									<div class="input-field col s12">

                                        <div class="row">
											
                                            <div class="input-field col s4">
                                                <input type="number" name="force_version_code" id="force_version_code" value="<?php echo $data['force_version_code']; ?>" maxlength=3 required min="1" />
                                                <label for="force_version_code">Version Code</label><?php echo isset($error['force_version_code']) ? $error['force_version_code'] : '';?>
											</div>

                                            <div class="input-field col s8">
                                                <select name="force_update">
                                                    <option value="1" <?php if($data['force_update'] == 1) echo "selected"; ?> ><?php echo YES_GRID; ?></option>
                                                    <option value="0" <?php if($data['force_update'] == 0) echo "selected"; ?> ><?php echo NO_GRID; ?></option>
                                                </select>
                                                <label>Force Update</label>
                                            </div>
										</div>
										
                                        <div class="row">
											<div class="input-field col s4">
												<input type="text" name="force_title" id="force_title" value="<?php echo $data['force_title']; ?>" maxlength=20 required/>
												<label for="force_title">Dialog Title</label><?php echo isset($error['force_title']) ? $error['force_title'] : '';?>
											</div>
                                            <div class="input-field col s8">
												<input type="text" name="force_message" id="force_message" value="<?php echo $data['force_message']; ?>" maxlength=100 required maxline=2/>
												<label for="force_message">Dialog Message</label><?php echo isset($error['force_message']) ? $error['force_message'] : '';?>
											</div>
										</div>

										<div class="row">
											<div class="input-field col s4">
												<input type="text" name="force_yes_button" id="force_yes_button" value="<?php echo $data['force_yes_button']; ?>" maxlength=30 required/>
												<label for="force_yes_button">Yes Button</label><?php echo isset($error['force_yes_button']) ? $error['force_yes_button'] : '';?>
											</div>
                                            <div class="input-field col s8">
												<input type="text" name="force_no_button" id="force_no_button" value="<?php echo $data['force_no_button']; ?>" maxlength=30 required/>
												<label for="force_no_button">No Button</label><?php echo isset($error['force_no_button']) ? $error['force_no_button'] : '';?>
											</div>
										</div>
									
                                        <div class="row">
                                            <div class="input-field col s4">
                                                <select name="force_source">
                                                    <option value="<?php echo PLAY_STORE; ?>" <?php if($data['force_source'] == PLAY_STORE) echo "selected"; ?> ><?php echo PLAY_STORE; ?></option>
                                                    <option value="<?php echo SERVER_URL; ?>" <?php if($data['force_source'] == SERVER_URL) echo "selected"; ?> ><?php echo SERVER_URL; ?></option>
                                                </select>
                                                <label>Source</label>
                                            </div>

                                            <div class="input-field col s8">
												<input type="text" name="force_apk_link" id="force_apk_link" value="<?php echo $data['force_apk_link']; ?>" placeholder="https://play.google.com/store/apps/details?id=com.your.id OR Full APK path" maxlength=255/>
												<label for="force_apk_link">Link Path</label><?php echo isset($error['force_apk_link']) ? $error['force_apk_link'] : '';?>
											</div>
										</div>
										
                                        <div class="row">
                                            <div class="input-field col s12">												
                                                <button class="btn deep-orange waves-effect waves-light" type="submit" name="submitForceSettings" id="submitForceSettings" >Submit
                                                    <i class="mdi-content-send right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    
									</div>
								<!--</div>-->
								</div>
							</form>
                        </div>
                    </div>
                    <!-- 2. Auto Update Setting End -->

                    <!-- 3. Admob Settings Start -->
                    <div id="admobSettings" class="col s12">
                        <div class="row">
                            <form method="post" class="col s12" id="form-validation" enctype="multipart/form-data">
								<div class="row">
                                    <br/>
								    <?php echo isset($error['error_data']) ? $error['error_data'] : '';?>
                                    <?php if(isset($_SESSION['msg_admob_setting'])) { ?>
                                        <div class='card-panel green lighten-4'>
                                            <span class='green-text text-darken-2'>
                                                Successfully Saved.
                                            </span>
                                        </div>
                                    <?php unset($_SESSION['msg_admob_setting']); }?>

                                    <div class="input-field col s12 m12">
                                        <div class="row">
                                            <div class="input-field col s12">
                                            <input type="text" name="publisher_id" id="publisher_id" value="<?php echo $data['publisher_id']; ?>" maxlength=50 required/>
                                            <label for="publisher_id">Publisher Id</label><?php echo isset($error['publisher_id']) ? $error['publisher_id'] : '';?>
                                            </div>
                                        </div>
                                    </div>

									<div class="input-field col s12 m12">
                                        <ul class="row collection with-header">
                                            <li class="collection-header  grey lighten-3">
                                                <h4 class="task-card-title">Banner Ad</h4>
                                                <p class="task-card-date">Show/hide setting for Banner in app</p>
                                            </li>
                                            <li class="collection-item">
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                    <select name="banner_ad">
                                                        <option value="1" <?php if($data['banner_ad'] == 1) echo "selected"; ?> ><?php echo TRUE_AD; ?></option>
                                                        <option value="0" <?php if($data['banner_ad'] == 0) echo "selected"; ?> ><?php echo FALSE_AD; ?></option>
                                                    </select>
                                                    <label>Banner Ad</label><?php echo isset($error['banner_ad']) ? $error['banner_ad'] : '';?></div>
                                                </div>
                                            
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                    <input type="text" name="banner_ad_id" id="banner_ad_id" value="<?php echo $data['banner_ad_id']; ?>" maxlength=50 required/>
                                                    <label for="banner_ad_id">Banner Ad Id</label><?php echo isset($error['banner_ad_id']) ? $error['banner_ad_id'] : '';?>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
									</div>
                                    
                                    <div class="input-field col s12 m12">
                                        <ul class="row collection with-header">
                                            <li class="collection-header  grey lighten-3">
                                                <h4 class="task-card-title">Interstital Ad</h4>
                                                <p class="task-card-date">Show/hide setting for Interstital in app</p>
                                            </li>
                                            <li class="collection-item">
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                    <select name="interstital_ad">
                                                        <option value="1" <?php if($data['interstital_ad'] == 1) echo "selected"; ?> ><?php echo TRUE_AD; ?></option>
                                                        <option value="0" <?php if($data['interstital_ad'] == 0) echo "selected"; ?> ><?php echo FALSE_AD; ?></option>
                                                    </select>
                                                    <label>Interstital Ad</label><?php echo isset($error['interstital_ad']) ? $error['interstital_ad'] : '';?></div>
                                                </div>
                                            
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                    <input type="text" name="interstital_ad_id" id="interstital_ad_id" value="<?php echo $data['interstital_ad_id']; ?>" maxlength=50 required/>
                                                    <label for="interstital_ad_id">Interstital Ad Id</label><?php echo isset($error['interstital_ad_id']) ? $error['interstital_ad_id'] : '';?>
                                                    </div>
                                                </div>

                                                 <div class="row">
                                                    <div class="input-field col s12">
                                                    <input type="text" name="interstital_ad_click" id="interstital_ad_click" value="<?php echo $data['interstital_ad_click']; ?>" maxlength=2 required/>
                                                    <label for="interstital_ad_click">Interstital Ad Click</label><?php echo isset($error['interstital_ad_click']) ? $error['interstital_ad_click'] : '';?>
                                                    </div>
                                                </div>

                                            </li>
                                        </ul>
									</div>

                                    <div class="row">
                                        <div class="input-field col s12">												
                                            <button class="btn deep-orange waves-effect waves-light" type="submit" name="submitAdmobSettings">Submit
                                                <i class="mdi-content-send right"></i>
                                            </button>
                                        </div>
                                    </div>
                                
								</div>
							</form>
                        </div>
                    </div>
                    <!-- 3. Admob Settings End -->

                    <!-- 4. Notification Setting Start -->
                    <div id="appNotification" class="row col s12">
                        <form method="post" enctype="multipart/form-data">
                            <br/>
                            <div class="col s12 m12 l12">
                                <div class="row">
                                    <div class="row">
                                        <div class="input-field col s12">

                                            <?php if(isset($_SESSION['msg_notification_setting'])) { ?>
                                                <div class='card-panel green lighten-4'>
                                                <span class='green-text text-darken-2'>
                                                    Successfully Saved.
                                                </span>
                                                </div>
                                                <?php unset($_SESSION['msg_notification_setting']); }?>

                                            <div class="row">
                                                <div class="input-field col s3">
                                                    Your Server Key
                                                    <br>
                                                    <a href="#server-key" class="modal-trigger">How to obtain your FCM Server Key</a>
                                                </div>

                                                <div class="input-field col s9">
                                                    <textarea name="app_fcm_key" class="materialize-textarea" id="app_fcm_key" required><?php echo $data['app_fcm_key'];?></textarea>
                                                    <label for="app_fcm_key">FCM Server Key</label>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="input-field col s3">
                                                    Your API Key
                                                    <br>
                                                    <a href="#api-key" class="modal-trigger">Where I have to put my API Key</a>
                                                </div>

                                                <div class="input-field col s7">
                                                    <input type="text" name="api_key" id="api_key" value="<?php echo $data['api_key'];?>" required />
                                                    <label for="api_key">API Key :</label>
                                                </div>
                                                <div class="input-field col s2">
                                                    <a href="change-api-key.php" class="btn deep-orange waves-effect waves-light">Change API Key</a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s3">
                                                    Developer Key (YouTube)
                                                    <br>
                                                    <a href="https://developers.google.com/youtube/android/player/register" 
                                                    target="_blank" class="modal-trigger">Register New Key - Click Here</a>
                                                </div>

                                                <div class="input-field col s9">
                                                    <input type="text" name="youtube_dev_api" id="youtube_dev_api" value="<?php echo $data['youtube_dev_api'];?>" required />
                                                    <label for="youtube_dev_api">Developer Key :</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12">												
                                                    <button type="submit" name="submitNotification" class="btn deep-orange waves-effect waves-light">Save Settings</button>
                                                </div>
                                            </div>                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- 4. Notification Setting End -->

                    <!-- 5. Privacy Policy Start -->
                    <div id="privacyPolicy" class="col s12">
                        <div class="row">
                            <form method="post" class="col s12" id="form-validation" enctype="multipart/form-data">
								<div class="row">
								    <?php echo isset($error['error_data']) ? $error['error_data'] : '';?>
                                    <?php if(isset($_SESSION['msg_privacy_policy'])) { ?>
                                        <div class='card-panel green lighten-4'>
                                            <span class='green-text text-darken-2'>
                                                Successfully Saved.
                                            </span>
                                        </div>
                                    <?php unset($_SESSION['msg_privacy_policy']); } ?>

									<div class="input-field col s12">
										<div class="row">
											<div class="input-field col s12">
												<span class="grey-text text-grey lighten-2">Privacy Policy</span>
												<?php echo isset($error['app_privacy_policy']) ? $error['app_privacy_policy'] : '';?>
												<textarea name="app_privacy_policy" id="app_privacy_policy" class="materialize-textarea" rows="16">
                                                    <?php echo $data['app_privacy_policy']; ?>                                                
												</textarea>
												<script type="text/javascript" src="assets/js/ckeditor/ckeditor.js"></script>
												<script type="text/javascript">
															CKEDITOR.replace( 'app_privacy_policy' );
															CKEDITOR.config.allowedContent = true;
												</script>
											</div>
										</div>

                                        <div class="row">
                                            <div class="input-field col s12">												
                                                <button class="btn deep-orange waves-effect waves-light" type="submit" name="submitPrivacyPolicy">Submit
                                                    <i class="mdi-content-send right"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
									</div>
								<!--</div>-->
								</div>
							</form>
                        </div>
                    </div>
                    <!-- 5. Privacy Policy End -->
                
                </div>
                <!-- 1. tab tag End -->
            </div>
            <!-- 0. CardPanel End -->
        </div>
    </div>
</section>

<?php include('templates/common/footer.php'); ?>
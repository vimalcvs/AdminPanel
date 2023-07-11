<?php
    include('templates/fcm.php');

    $notification_qry = "SELECT * FROM tbl_notification ORDER BY id DESC";
    $notification_result = mysqli_query($connect, $notification_qry);


    if (isset($_GET['id'])) {

        $sql = 'SELECT * FROM tbl_notification WHERE id=\''.$_GET['id'].'\'';
        $img_notification = mysqli_query($connect, $sql);
        $img_notification_row = mysqli_fetch_assoc($img_notification);

        if ($img_notification_row['image'] != "") {
            unlink(UPLOAD_NOTIFICATION .$img_notification_row['image']);
        }

        Delete('tbl_notification','id='.$_GET['id'].'');

        header("location: notification.php");
        exit;

    }

    if(isset($_GET['notification_id'])) {

        $qry = "SELECT * FROM tbl_notification WHERE id = '".$_GET['notification_id']."'";
        $result = mysqli_query($connect, $qry);
        $row = mysqli_fetch_assoc($result);

        $title = $row['title'];
        $message = $row['message'];

        $image = "/" . UPLOAD_NOTIFICATION .$row['image'];
        
        $users_sql = "SELECT distinct(token) as token FROM tbl_tokens";

        $users_result = mysqli_query($connect, $users_sql);
        while($user_row = mysqli_fetch_assoc($users_result)) {
        
            $data = array("title" => $title, "message" => $message, "image" => $image, "backgroundNotification" => false);

            echo SEND_FCM_NOTIFICATION($user_row['token'], $data);

        }

//        Congratulations, Push Notification Sent to $total_user Users.
        if ($result) {
            $error['send_notification'] =
                "<div class='card-panel green lighten-4'>
                                <span class='green-text text-darken-2'>
                                    Congratulations, push notification sent successfully.
                                </span>
                            </div>";

        } else {
            $error['send_notification'] =
                "<div class='card-panel red lighten-4'>
                                <span class='red-text text-darken-2'>
                                    Failed
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
                        <h5 class="breadcrumbs-title">Push Notification</h5>
                        <ol class="breadcrumb">
                            <li><a href="dashboard.php" class="deep-orange-text">Dashboard</a>
                            </li>
                            <li><a class="active">Push Notification</a>
                            </li>
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
                        <a href="add_notification.php" class="btn waves-effect waves-light deep-orange">Add New Notification</a>
                    </div>
                    <div class="col s6 m6 l6" >
                        <ul class="dropdown-menu pull-right footer2 tooltipped"
                                    data-position="top" data-delay="0" data-tooltip="Hide/Show Columns" style = "margin-top:0px">
                            <li><a class="btn dropdown-button" href="javascript:void(0);"
                                data-activates="dropdownNotification">SELECT<i class="mdi-hardware-keyboard-arrow-down right"></i></a>
                            </li>
                        </ul>
                        <ul id="dropdownNotification" class="dropdown-content">
                            <!-- <li><a class="toggle-vis" data-column="1">No.</a></li>-->
                            <li><a class="toggle-vis" data-column="2">Image</a></li>
                            <li><a class="toggle-vis" data-column="3">Title</a></li>
                            <li><a class="toggle-vis" data-column="4">Message</a></li>
                        </ul>
                    </div>
                    <div class="col s12 m12 l12">
                        <div class="card-panel">
                            <?php echo isset($error['send_notification']) ? $error['send_notification'] : '';?>
                            <?php echo isset($error['delete_notification']) ? $error['delete_notification'] : '';?>
                            <table id="table_notification" class="responsive-table display" cellspacing="0">

                                <thead>
                                <tr>
                                    <th class="hide-column">ID</th>
                                    <th>No.</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Message</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                $i = 1;
                                while($notification_row=mysqli_fetch_array($notification_result)) {
                                    ?>

                                    <tr>
                                        <td class="hide-column"><?php echo $notification_row['id'];?></td>
                                        <td>
                                            <?php
                                            echo $i;
                                            $i++;
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if($notification_row['image'] == NULL) {

                                                ?>
                                                <img class="materialboxed circle z-depth-2" data-caption="No Image" src="assets/images/no-image.png" height="54px" width="54px"/>
                                                <?php

                                            } else {

                                                ?>
                                                <img class="materialboxed circle z-depth-2"  data-caption="<?php echo $notification_row['title'];?>" src="<?php echo UPLOAD_NOTIFICATION . $notification_row['image'];?>" height="54px" width="54px"/>

                                            <?php } ?>

                                        </td>
                                        <td><?php echo $notification_row['title'];?></td>
                                        <td><?php echo $notification_row['message'];?></td>
                                        <td>
                                            <a class="tooltipped light-blue btn-floating activator waves-effect waves-light darken-2" data-position="top" 
                                                data-delay="0" data-tooltip="Send Notification" onclick="swal({
                                                                title: 'Notification', text: 'Do you want to send this notification to all users?',
                                                                type: 'success', showCancelButton: true, confirmButtonColor: '#ff5722', 
                                                                confirmButtonText: 'Send to All!', cancelButtonText: 'No, cancel!',
                                                                closeOnConfirm: false, closeOnCancel: false
                                                            }, function (isConfirm) {
                                                                if (isConfirm) {
                                                                    window.location.href = 'notification.php?notification_id=<?php echo $notification_row['id'];?>';
                                                                } else {
                                                                    swal('Cancelled', 'Notification not send :)', 'error');
                                                                }
                                                            });" >
                                                <i class="mdi-content-send"></i>
                                            </a>
                                            &nbsp;
                                            <a href="edit_notification.php?id=<?php echo $notification_row['id'];?>"
                                                    class="tooltipped green darker-2 btn-floating activator waves-effect waves-light"
                                                    data-position="top" data-delay="0" data-tooltip="Edit">
                                                <i class="mdi-editor-mode-edit"></i>
                                            </a>
                                            &nbsp;
                                            <a class="tooltipped red btn-floating waves-effect waves-light" data-position="top" 
                                                data-delay="0" data-tooltip="Delete" onclick="swal({
                                                            title: 'Delete', text: 'You will not be able to recover this item!',
                                                            type: 'warning', showCancelButton: true, confirmButtonColor: '#ff5722', 
                                                            confirmButtonText: 'Yes, delete it!', cancelButtonText: 'No, cancel!',
                                                            closeOnConfirm: false, closeOnCancel: false
                                                        }, function (isConfirm) {
                                                            if (isConfirm) {
                                                                window.location.href = 'notification.php?id=<?php echo $notification_row['id'];?>';
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

    </section>

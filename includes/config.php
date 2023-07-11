<?php

    //Image Folder Name Configuration
    if (!defined('UPLOAD')) {
        define('UPLOAD', 'upload');
        define('UPLOAD_CATEGORY', 'upload/category/');
        define('UPLOAD_CHANNEL', 'upload/channel/');
        define('UPLOAD_NOTIFICATION', 'upload/notification/');
        define('UPLOAD_APP_IMAGE', 'upload/app_image/');
        define('ACTIVE', 'ACTIVE');
        define('INACTIVE', 'INACTIVE');
        define('TRUE_AD', 'True');
        define('FALSE_AD', 'False');
        define('ENABLED', 'Enabled');
        define('DISABLED', 'Disabled');
        define('YES_GRID', 'Yes');
        define('NO_GRID', 'No');
        define('PLAY_STORE', 'Playstore');
        define('SERVER_URL', 'Server URL');
    }

    if(!defined('MSG')){
        define('MSG', "Message");
        define('MSG_NO_METHOD_FOUND', "Oops, no method found!");
        define('MSG_API_KEY_INCORRECT',"Oops, API Key is incorrect!");
        define('MSG_API_KEY_REQUIRED',"Oops, API Key is required!");
        define('MSG_REQUIRED_PARAMS', "Required parameter is missing!");
        
        define('MSG_USERNAME_EMPTY', "Username must be filled!");
        define('MSG_PASSWORD_EMTPY', "Password must be filled!");
        define('MSG_INVALID_USER_PASS', "Invalid username or password!");
        define('MSG_LOGIN_SUCCESS', "Login successfully");
        define('MSG_REGISTER_SUCCESS', "User registered successfully.");
        define('MSG_RECORD_ALREADY_EXIST', "Oops, Record already exist!");
        define('MSG_RECORD_NOT_FOUND', "Oops, Record not found!");
        define('MSG_RECORD_FOUND', "Record found successfully!");
        define('MSG_CHANGE_PASSWORD', "Password changed successfully");
        define('MSG_INVALID_PASSWORD', "Invalid current password");
        
        define('SUCCESS', "0");
        define('FAIL', "1");

        define('APP_NAME','MY LIVE STREAMING');
        define('COPYRIGHT', 'Copyright © <script>document.write(new Date().getFullYear())</script>.');
        define('ALL_RIGHTS_RESERVED', 'All rights reserved');
        define('VERSION', 'Version 1.5');
        define('DEVELOPMENT_BY' , 'Developed by: ');
        define('COMPANY_NAME' , 'TechnoVimal');
        
        //Do not change both direction value, otherwise panel not work properly
        define('LTR_DIRECTION','LTR');
        define('RTL_DIRECTION','RTL');
        
        //v1.1
        define('STREAMING', 'Streaming');
        define('YOUTUBE', 'YouTube');
        define('EMBEDDED','Embedded');
        define('STR_STREAMING','Streaming URL');
        define('STR_YOUTUBE','YouTube URL');
        define('STR_EMBEDDED', 'Embedded URL');
        define('YOUTUBE_IMG_PATH', 'https://img.youtube.com/vi/');
        define('YOUTUBE_MQ_DEFAULT', '/mqdefault.jpg');
        define('DEFAULT_IMG', 'https://img.youtube.com/vi/mqdefault.jpg');

        //-----------------------------------------------------------------------
        define('DELETE_THANKS', 'Thank you');
        define('DELETE_TITLE', 'Cancelled');
        define('DELETE_CANCELLED', 'Your item is safe :)');
        //------------------------------------------------------------------------
        
    }
   		
    //LOCAL database configuration
    $host       = "localhost";
    $user       = "u726159739_computer_hindi";
    $pass       = "gpBYFu8O:4";
    $database   = "u726159739_computer_hindi";

    $connect = new mysqli($host, $user, $pass, $database);

    if (!$connect) {
        die ("connection failed: " . mysqli_connect_error());
    } else {
        $connect->set_charset('utf8');
    }

    $sql_query = "SELECT app_direction FROM tbl_settings";
    $value = mysqli_query($connect, $sql_query);
    $value = mysqli_fetch_array($value);
    $valueLTR = $value['app_direction'];

?>
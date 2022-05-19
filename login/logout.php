<?php

 
// Bỏ đặt tất cả các biến phiên
$_SESSION = array();
 
// Hủy phiên.
session_destroy();
 
// Redirect to login page
header("location: login.php");
exit;
?>
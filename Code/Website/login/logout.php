<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
if (session_destroy()) 
	header('Location: /login');
else
	echo "KO thể thoát dc, có lỗi trong việc hủy session";

?>
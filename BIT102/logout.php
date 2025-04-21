<?php
require 'config2.php';

session_unset();
session_destroy();
header("Location: login.php");
exit();
?>
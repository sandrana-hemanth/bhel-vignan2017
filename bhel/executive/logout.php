<?php
session_start();
session_destroy();
header("Location: /bhel/index.php");

?>
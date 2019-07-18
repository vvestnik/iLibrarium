<?php
#easy. logout
    session_start();
    session_destroy();
    header('Location: index.php');
?>
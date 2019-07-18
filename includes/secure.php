<?php
#allow access only to logged in users
if (!isset($_SESSION['aut_user'])) {
    $_SESSION['login_message'] = 'Not logged in to access the page';
    header('Location: index.php');
    exit();
}
?>
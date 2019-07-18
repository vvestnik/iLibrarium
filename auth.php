<?php
#authorise the user
require_once 'includes/functions.php';
function classLoad($className){
    require './class/' . strtolower($className) . '.php';
}
spl_autoload_register('classLoad');
$nickname = clean($_POST['nickname']);
$password = clean($_POST['password']);
$authorised = false;

$login = new Login($nickname, $password);
$authorised = $login->authUser();
if ($authorised) {
    $user = new User($authorised);
    $user->prepareForSession();
    session_start();
    $_SESSION['aut_user'] = $user;
    header('Location: blog.php');
} else {
    session_start();
    $_SESSION['login_message'] = 'Username or password is incorrect';
    header('Location: index.php');
}
?>
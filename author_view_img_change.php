<?php
header('Content-Type: text/xml; charset=utf-8');
#requires needed to deserialise User from Session
require_once './class/user.php';
require_once './class/userroles.php';
require_once './class/role.php';
session_start();
#autoload of classes
function classLoad($className){
    require './class/' . strtolower($className) . '.php';
}
spl_autoload_register('classLoad');
require_once 'includes/functions.php';
#only for logged in users
require_once 'includes/secure.php';

$img_id = filter_input(INPUT_GET, 'img_id', FILTER_SANITIZE_STRING);
$author_id = filter_input(INPUT_GET, 'author_id', FILTER_SANITIZE_STRING);
$image = new Image($img_id);
$author = new Author($author_id);

//XML

echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<data>';
echo ' <imagePath>images/' . $image->getFilename() . '</imagePath>';
if ($_SESSION['aut_user']->checkRoleNoRedirrect(new Role('name', 'Store-seller'))) {
    if(!$author->isImageDefault($image)){
        echo ' <setDefaultImage><![CDATA[<h4><a href="author_view.php?id=' . $author->getId() . '&default_set=' . $image->getId() . '"><span class="label label-success">Set the image as default for the author</span></a></h4>]]></setDefaultImage>';
    }
    else{
        echo ' <setDefaultImage>nope</setDefaultImage>';
    }
    if($image->getId() != 2){
        echo ' <unsetImage><![CDATA[<h4><a href="author_view.php?id=' . $author->getId() . '&unset=' . $image->getId() . '"><span class="label label-danger">Unbind the image from the author</span></a></h4>]]></unsetImage>';
    }
    else{
        echo ' <unbindImage>nope</unbindImage>';
    }
}
echo '</data>';
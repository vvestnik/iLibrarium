<?php
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
$content = '';
?>
<!DOCTYPE html>
<!-- Template by Quackit.com -->
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <script type="text/javascript">
            function request(book_id, image_id){
                var req = new XMLHttpRequest();
                req.open('get', 'book_view_img_change.php?book_id=' + book_id + '&img_id=' + image_id, true);
                req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                req.onreadystatechange = evaluate;
                req.send();
            }
            
            function evaluate(e){
                if(e.target.readyState == 4 && e.target.status == 200){
                    var answer = e.target.responseXML;
                    document.getElementById('big-image').setAttribute('src', answer.getElementsByTagName('imagePath')[0].firstChild.nodeValue);                    
                    if(answer.getElementsByTagName('setDefaultImage')[0].firstChild.nodeValue == 'nope'){
                        document.getElementById('set-default-image').innerHTML = '';
                    }
                    else{
                        document.getElementById('set-default-image').innerHTML = answer.getElementsByTagName('setDefaultImage')[0].firstChild.nodeValue;
                    }
                    if(answer.getElementsByTagName('unsetImage')[0].firstChild.nodeValue == 'nope'){
                        document.getElementById('unset-image').innerHTML = '';
                    }
                    else{
                        document.getElementById('unset-image').innerHTML = answer.getElementsByTagName('unsetImage')[0].firstChild.nodeValue;
                    }
                }
            }
        </script>
        <?php
        #check if id is valid
        if (isset($_GET['id'])) {
            $book_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
            if (!empty($book_id) || $book_id == 0) {
                if (is_numeric($book_id)) {
                    $book = new Book($book_id);
                    #check if new default image is about to set
                    if (isset($_GET['default_set'])) {
                        #allowed only to sellers
                        if ($_SESSION['aut_user']->checkRoleNoRedirrect(new Role('name', 'Store-seller'))) {
                            $default_set_id = filter_input(INPUT_GET, 'default_set', FILTER_SANITIZE_STRING);
                            if (!empty($default_set_id) || $default_set_id == 0) {
                                if (is_numeric($default_set_id)) {
                                    $image = new Image($default_set_id);
                                    $book->changeDefImage($image);
                                    $_SESSION['success_message'] = 'Default image set';
                                }
                                else{
                                    $_SESSION['login_message'] = 'Id for setting is not a number';
                                    header('Location: book_view.php?id=' . $book->getId());
                                    exit();
                                }
                            }
                            else{
                                $_SESSION['login_message'] = 'Id for setting is empty';
                                header('Location: book_view.php?id=' . $book->get);
                                exit();
                            }
                        }
                        else{
                            $_SESSION['login_message'] = 'You are not allowed to change book information';
                            header('Location: book_view.php?id=' . $book->getId());
                            exit();
                        }
                    }
                    #check if the image is about to be unset
                    if (isset($_GET['unset'])) {
                        #allowed only to sellers
                        if ($_SESSION['aut_user']->checkRoleNoRedirrect(new Role('name', 'Store-seller'))) {
                            $unset_id = filter_input(INPUT_GET, 'unset', FILTER_SANITIZE_STRING);
                            if (!empty($unset_id) || $unset_id == 0) {
                                if (is_numeric($unset_id)) {
                                    $unsetImage = new Image($unset_id);
                                    $book->unsetImage($unsetImage);
                                } else {
                                    $_SESSION['login_message'] = 'Id for unbinding is not a number';
                                    header('Location: book_view.php?id=' . $book->getId());
                                    exit();
                                }
                            } else {
                                $_SESSION['login_message'] = 'Id for unbinding is empty';
                                header('Location: book_view.php?id=' . $book->getId());
                                exit();
                            }
                        } else {
                            $_SESSION['login_message'] = 'You are not allowed to change book information';
                            header('Location: book_view.php?id=' . $book->getId());
                            exit();
                        }
                    }
                    #get all data about the book, write to variables                   
                    $image = $book->getDefImage();
                    /*#which image to show? if not set then default
                    if (isset($_GET['img_id'])) {
                        $img_id = filter_input(INPUT_GET, 'img_id', FILTER_SANITIZE_STRING);
                        if (!empty($img_id) || $img_id == 0) {
                            if (is_numeric($img_id)) {
                                $checkImage = new Image($img_id);
                                if ($book->isImageSet($checkImage)) {
                                    $image = $checkImage;
                                }
                            }
                        }
                    }*/
                } else {
                    $_SESSION['login_message'] = 'Id sent is not a number';
                    header('Location: index.php');
                    exit();
                }
            } else {
                $_SESSION['login_message'] = 'Id sent is empty';
                header('Location: index.php');
                exit();
            }
        } else {
            $_SESSION['login_message'] = 'No id sent, to show a book';
            header('Location: index.php');
            exit();
        }
        #moderator can remove comments
        if(isset($_GET['remove'])){
            if ($_SESSION['aut_user']->checkRoleNoRedirrect(new Role('name', 'Moderator'))) {
                $remove_id = filter_input(INPUT_GET, 'remove', FILTER_SANITIZE_STRING);
                if(!empty($remove_id) || $remove_id == 0){
                    if(is_numeric($remove_id)){
                        $removeComment = new Comment($remove_id);
                        $removeComment->removeComment();
                        unset($removeComment);
                        $_SESSION['success_message'] = 'Comment removed';
                    }
                    else{
                        $_SESSION['login_message'] = 'Id provided is not a number';
                        header('Location: book_view.php?id=' . $book_id);
                        exit();
                    }
                }
                else{
                    $_SESSION['login_message'] = 'Id provided is empty';
                    header('Location: book_view.php?id=' . $book_id);
                    exit();
                }
            }
            else{
                $_SESSION['login_message'] = 'You are not allowed to remove comments';
                header('Location: book_view.php?id=' . $book_id);
                exit();
            }
        }
        #submit a new comment
        if (isset($_POST['content']) && $_POST['comment'] == 'submit') {
            $content = clean(filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING));
            if(!empty($content)){
                $user_id = $_SESSION['aut_user']->getId();
                $newComment = new Comment($content, $book->getId(), $user_id);
                $content = '';
                $_SESSION['success_message'] = 'Comment posted';
            }
            else{
                $_SESSION['login_message'] = 'Comment is empty';
            }
        }
        ?>
        <title><?php echo $book->getName(); ?> - iLibrarium</title>

        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS: You can use this stylesheet to override any Bootstrap styles and/or apply your own styles -->
        <link href="css/custom.css" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>

    <body>

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-static-top" role="navigation">
            <div class="container">
                <!-- Logo and responsive toggle -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php"><span class="glyphicon glyphicon-book"></span> iLibrarium</a>
                </div>
                <!-- Navbar links -->
                <div class="collapse navbar-collapse" id="navbar">
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="index.php">Home</a>
                        </li>
                        <li class="active">
                            <a href="books.php">Books</a>
                        </li>
                        <li>
                            <a href="blog.php">Blog</a>
                        </li>
                        <li>
                            <a href="gallery.php">Gallery</a>
                        </li>
                        <!-- Show only when logged in -->
                        <?php
                        if (isset($_SESSION['aut_user'])) {
                        ?>                                        
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">My account<span class="caret"></span></a>
                                <ul class="dropdown-menu" aria-labelledby="about-us">
                                    <li><a href="user.php?id=<?php echo $_SESSION['aut_user']->getId(); ?>">Settings</a></li>
                                    <li><a href="logout.php">Log out</a></li>
                                </ul>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>

                    <!-- Search -->
                    <form class="navbar-form navbar-right" role="search">
                        <div class="form-group">
                            <input type="text" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> Search</button>
                    </form>

                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>

        <div class="container-fluid">

            <!-- Left Column -->
            <?php
            require_once 'includes/left.php';
            ?>


            <!-- Center Column -->
            <div class="col-sm-6">
                <!-- Alert -->
                <?php
                if (isset($_SESSION['login_message'])) {
                    echo '<div class="alert alert-danger alert-dismissible" role="alert">';
                    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    echo '<strong>Error:</strong> ';
                    echo $_SESSION['login_message'];
                    echo '</div>';
                    unset($_SESSION['login_message']);
                }

                if (isset($_SESSION['success_message'])) {
                    echo '<div class="alert alert-success alert-dismissible" role="alert">';
                    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    echo '<strong>success:</strong> ';
                    echo $_SESSION['success_message'];
                    echo '</div>';
                    unset($_SESSION['success_message']);
                }
                ?>
                <h2><?php echo $book->getName(); ?></h2>
                <div class="row">
                    <div class="col-lg-6 container-fluid">
                        <div class="row">
                            <img src="images/<?php echo $image->getFilename(); ?>" class="img-fluid img-responsive" id="big-image" alt="image for <?php echo $book->getName(); ?>"><br>
                        </div>
                        <?php
                        #seller can change the image of the book
                        if ($_SESSION['aut_user']->checkRoleNoRedirrect(new Role('name', 'Store-seller'))) {
                            echo '<div id="set-default-image" class="row">';
                            if(!$book->isImageDefault($image)){                                
                                echo '<h4><a href="book_view.php?id=' . $book->getId() . '&default_set=' . $image->getId() . '"><span class="label label-success">Set the image as default for the book</span></a></h4>';                                
                            }
                            echo '</div>';
                            echo '<div id="unset-image" class="row">';
                            if($image->getId() != 2){                                
                                echo '<h4><a href="book_view.php?id=' . $book->getId() . '&unset=' . $image->getId() . '"><span class="label label-danger">Unbind the image from the book</span></a></h4>';                                
                            }
                            echo '</div>';
                        }
                        ?>
                        <div class="row">
                        <?php
                        foreach ($book->getImages() as $thumbnail) {
                            echo '<div class="col-xs-3">';
                            echo '<a href="javascript:request(' . $book->getId() . ', ' . $thumbnail->getId() . ')" class="thumbnail">';
                            echo '<img src="images/thumbnails/' . $thumbnail->getFilename() . '" alt="thumbnail_' . $thumbnail->getId() . '">';
                            echo '</a>';
                            echo '</div>';
                        }
                        ?>
                        </div>
                    </div>
                    <div class="col-lg-6 container-fluid">
                        <?php
                        echo '<p>';
                        echo '<b>Author(s):</b><br>';
                        foreach ($book->getAuthorsArray() as $author) {
                            echo $author . '<br>';
                        }
                        echo '<b>Genre(s):</b><br>';
                        foreach ($book->getGenresArray() as $genre) {
                            echo $genre . '<br>';
                        }
                        echo '<b>ISBN:</b><br>';
                        echo $book->getIsbn() . '<br></p>';
                        #if there are book in stock, link to reserve
                        if ($book->qtyInStock() > 0) {
                            echo '<h4><a href="reservation.php?book_id=' . $book_id . '"><span class="label label-success">' . $book->qtyInStock() . ' in stock (Click here to reserve)</span></a></h4>';
                        } else {
                            echo '<span class="label label-danger">Out of stock</span>';
                        }
                        #seller can manage settings
                        if ($_SESSION['aut_user']->checkRoleNoRedirrect(new Role('name', 'Store-seller'))) {
                        ?>
                            <!-- Split button -->
                            <hr>
                            <div class="row">
                                <div class="btn-group pull-right">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Manage the book <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="image_upload.php?book_id=<?php echo $book->getId(); ?>">Upload an image</a></li>
                                        <li><a href="gallery.php?book_id=<?php echo $book->getId(); ?>">Select image from the gallery</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="edit_book.php?id=<?php echo $book->getId(); ?>">Edit book</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="manage_books.php?id=<?php echo $book->getId(); ?>">Manage books in store</a></li>
                                    </ul>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <article class="col-xs-12">
                        <?php
                        echo '<h4>Description</h4>';
                        echo '<p>' . $book->getDescription() . '</p>';
                        ?>
                    </article>
                </div>
                <hr>
                    <?php
                    
                    #show comments
                    $comments = [];
                    $connection = new Connection();
                    $link = $connection->connect();
                    $result = $link->query("SELECT id FROM comment WHERE book_id = '" . $book->getId() . "' ORDER BY date ASC");
                    while ($record = $result->fetch()){
                        $comments[] = new Comment($record['id']);
                    }
                    foreach ($comments as $comment) {
                        echo '<div class="row">';
                        echo '<article class="col-xs-12">';
                        echo '<div class="row">';
                        echo '<div class="col-lg-1">';
                        echo '<img src="avatars/' . $comment->getUser()->getAvatarFilename() . '" class="img-fluid media" alt="avatar_' . $comment->getUser()->getNickname() . '">';
                        echo '</div>';
                        echo '<div class="col-xs-11">';
                        if ($_SESSION['aut_user']->checkRoleNoRedirrect(new Role('name', 'Moderator'))) {
                            echo '<a href="book_view.php?id=' . $book->getId() . '&remove=' . $comment->getId() . '"><span class="glyphicon glyphicon-remove pull-right"></span></a>';
                        }
                        echo '<p>' . $comment->getContent() . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="row">';
                        echo '<p class="pull-right">Written on ' . $comment->getFormattedDate() . ' by ' . $comment->getUser()->getNickname() . '</p>';
                        echo '</div>';
                        echo '</article>';
                        echo '</div>';
                        echo '<hr>';
                        $connection = null;
                    }
                    ?>
                <div class="row">
                    <article class="col-xs-12">
                        <h2>Leave a comment</h2>
                        <form action="book_view.php?id=<?php echo $book->getId(); ?>" method="post" id="newcommentform">
                            <div class="form-group"> 
                                <textarea class="form-control" id="content" name="content" placeholder="Your comment here"><?php echo $content; ?></textarea>
                            </div>
                            <input type="hidden" value="submit" name="comment">
                            <input class="btn btn-default" type="submit" value="Send">
                        </form>
                    </article>
                </div>
            </div><!--/Center Column-->


            <!-- Right Column -->
            <?php
            require_once 'includes/right.php';
            ?>

        </div><!--/container-fluid-->

        <footer>
            <div class="footer-blurb">                
                <div class="container">
                    <p><a href="#">Terms &amp; Conditions</a> | <a href="#">Privacy Policy</a> | <a href="#">Contact</a></p>
                    <p>Copyright &copy; iLibrarium.com 2019 </p>
                </div>                
            </div>
        </footer>


        <!-- jQuery -->
        <script src="js/jquery-1.11.3.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>

        <!-- IE10 viewport bug workaround -->
        <script src="js/ie10-viewport-bug-workaround.js"></script>

        <!-- Placeholder Images -->
        <script src="js/holder.min.js"></script>

    </body>

</html>

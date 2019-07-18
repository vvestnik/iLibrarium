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
?>
<!DOCTYPE html>
<!-- Template by Quackit.com -->
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <?php
        #check if id is valid
        if (isset($_GET['img_id'])) {
            $img_id = filter_input(INPUT_GET, 'img_id', FILTER_SANITIZE_STRING);
            if (!empty($img_id) || $img_id == 0) {
                if (is_numeric($img_id)) {
                    #then get data about the image
                    $image =new Image($img_id);
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
            $_SESSION['login_message'] = 'No id sent, to show an image';
            header('Location: index.php');
            exit();
        }
        ?>
        <title>View image - iLibrarium</title>

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
                        <li>
                            <a href="books.php">Books</a>
                        </li>
                        <li>
                            <a href="blog.php">Blog</a>
                        </li>
                        <li class="active">
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
                <!-- Show image and all data -->
                <h2><?php echo $image->getDescription(); ?></h2>
                <div class="col-lg-7 container-fluid">
                    <img src="images/<?php echo $image->getFilename(); ?>" class="img-fluid img-responsive" alt="image <?php echo $image->getFilename(); ?>">
                </div>
                <div class="col-lg-5">
                    <?php
                    echo '<p>';
                    echo '<b>Image from:</b><br>';
                    echo $image->getAuthor() . '<br>';
                    echo '<b>Tags:</b><br>';
                    echo $image->getTags() . '<br>';
                    echo '<b>Uploaded by:</b><br>';
                    echo $image->getUploadedByNick() . ' on ' . $image->getFormattedDate() . '<br>';
                    #if seller and set book id, it's possible to connect image with the book
                    if ($_SESSION['aut_user']->checkRoleNoRedirrect(new Role('name', 'Store-seller'))) {
                        if (isset($_GET['book_id'])) {
                            $book_id = filter_input(INPUT_GET, 'book_id', FILTER_SANITIZE_STRING);
                            if (!empty($book_id) || $book_id == 0) {
                                if (is_numeric($book_id)) {
                                    $book = new Book($book_id);
                                    #is image already connected?                                    
                                    #no, then it's possible to set a connection
                                    if (!$book->isImageSet($image)) {
                                        #show select button
                                        if (!isset($_GET['select'])) {
                                            echo '<br><b>Selected book:</b><br>';
                                            echo $book->getName();
                                            echo '<h4><a href="big_img.php?book_id=' . $book->getId() . '&img_id=' . $image->getId() . '&select=yes"><span class="label label-success">Set this image for the selected book</span></a></h4>';
                                        }
                                        #button selected ---> connect
                                        else {
                                            if ($_GET['select'] == 'yes') {
                                                $book->addImage($image);
                                            }
                                        }
                                    #yes already connected, show message
                                    } else {
                                        echo '<br><b>The image is already set for the book</b>';
                                    }
                                }
                            }
                        }
                        #same stuff as above but with author insead of book...
                        if (isset($_GET['author_id'])) {
                            $author_id = filter_input(INPUT_GET, 'author_id', FILTER_SANITIZE_STRING);
                            if (!empty($author_id) || $author_id == 0) {
                                if (is_numeric($author_id)) {
                                    $author = new Author($author_id);
                                    if (!$author->isImageSet($image)) {
                                        if (!isset($_GET['select'])) {
                                            echo '<br><b>Selected author:</b><br>';
                                            echo $author->getFullname();
                                            echo '<h4><a href="big_img.php?author_id=' . $author->getId() . '&img_id=' . $image->getId() . '&select=yes"><span class="label label-success">Set this image for the selected author</span></a></h4>';
                                        } 
                                        else {
                                            if ($_GET['select'] == 'yes') {
                                                $author->addImage($image);                                             
                                            }
                                        }
                                    } 
                                    else {
                                        echo '<br><b>The image is already set for the book</b>';
                                    }
                                }
                            }
                        }
                    }
                    ?>

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

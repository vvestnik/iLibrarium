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
#only for sellers
$_SESSION['aut_user']->checkRole(new Role('name', 'Store-seller'));
$alert = '';
$author = '';
$description = '';
$tags = '';
?>
<!DOCTYPE html>
<!-- Template by Quackit.com -->
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

        <title>Upload an Image - iLibrarium</title>

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
                    <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-book"></span> iLibrarium</a>
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
                <?php                 
                if (isset($_FILES['file']['tmp_name']) && !empty($_FILES['file']['tmp_name'])) {
                    $file = $_FILES['file'];
                    
                    ## read text from form
                    $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING);
                    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
                    $tags = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_STRING);
                    if (empty($author)) {
                        $alert .= 'Please add the author';
                    }
                    if (empty($description)) {
                        $alert .= 'Please add the description';
                    }
                    if (empty($tags)) {
                        $alert .= 'Please add the tags';
                    }
                    
                    $image = new Image($file, $tags, $author, $description);
                    if (!empty($alert)) {
                        #<!-- Alert -->
                        echo '<div class="alert alert-danger alert-dismissible" role="alert">';
                        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                        echo '<strong>Error(s):</strong><br>' . $alert;
                        echo '</div>';
                    } else {
                        #success
                        echo '<h2>File succesfully uploaded</h2>';
                        #if book_id is provided, set the image to the book
                        if (isset($_GET['book_id'])) {
                            $book_id = $_GET['book_id'];
                            if (!empty($book_id) || $book_id == 0) {
                                if (is_numeric($book_id)) {
                                    $book = new Book($book_id);
                                    $book->addImage($image);
                                    echo '<p><a href="image_upload.php?book_id=' . $book_id . '">Upload next image for the book</a></p>';
                                }
                            }
                        }
                        #if author_id is provided set the image to the author
                        if (isset($_GET['author_id'])) {
                            $author_id = $_GET['author_id'];
                            if (!empty($author_id) || $author_id == 0) {
                                if (is_numeric($author_id)) {
                                    $bookAuthor = new Author($author_id);
                                    $bookAuthor->addImage($image);  
                                    echo '<p><a href="image_upload.php?author_id=' . $author_id . '">Upload next image for the author</a></p>';
                                }
                            }
                        }
                        if(!(isset($_GET['book_id']) || isset($_GET['author_id']))){
                            echo '<p><a href="image_upload.php">Upload next image</a></p>';                       
                        }
                    }
                }
                
                #form for upload and the text
                if (!isset($_POST['author']) || !empty($alert) || (isset($_FILES['file']['tmp_name']) && empty($_FILES['file']['tmp_name']))) {
                    ?>
                    <h2>Upload an Image</h2>
                    <p>Allowed filetypes are JPG, GIF, and PNG.<br>
                        Max filesize is 1Mb.<br>
                        Max avatar dimensions are 800 x 800 pixels.<br></p>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <span class="glyphicon glyphicon-pencil"></span> 
                                Fill in the form please
                            </h3>
                        </div>
                        <div class="panel-body">
                            <form enctype="multipart/form-data" action="image_upload.php<?php 
                            if(isset($_GET['book_id'])){ 
                                $book_id = $_GET['book_id']; 
                                if(!empty($book_id) || $book_id = 0){ 
                                    if(is_numeric($book_id)){ 
                                        echo '?book_id=' . $book_id;
                                    } 
                                } 
                            } 
                            if(isset($_GET['author_id'])){ 
                                $author_id = $_GET['author_id'];
                                if(!empty($author_id) || $author_id = 0){ 
                                    if(is_numeric($author_id)){ 
                                        echo '?author_id=' . $author_id;
                                    }
                                }
                            } ?>" method="post" id="newimageform">
                                <div class="form-group">
                                    <label class="control-label" for="file">Please select a file to upload</label>
                                    <input type="file" class="form-control-file" id="file" name="file">
                                </div>
                                <div class="form-group 
                                    <?php
                                    if (empty($author) && isset($_POST['author'])) {
                                        echo 'has-error';
                                    } ?>">
                                    <label class="control-label" for="author">Rights on the image belng to</label>
                                    <input type="text" value="<?php echo $author; ?>" class="form-control" id="author" name="author" placeholder="Author">
                                </div>
                                <div class="form-group 
                                    <?php 
                                    if (empty($description) && isset($_POST['description'])) {
                                        echo 'has-error';
                                    } ?>">
                                    <label class="control-label" for="Description">Description</label>
                                    <textarea class="form-control" id="description" name="description" placeholder="Description"><?php echo $description; ?></textarea>
                                </div>
                                <div class="form-group 
                                    <?php 
                                    if (empty($tags) && isset($_POST['tags'])) {
                                        echo 'has-error';
                                    } ?>">
                                    <label class="control-label" for="tags">Tags</label>
                                    <input type="text" value="<?php echo $tags; ?>" class="form-control" id="tags" name="tags" placeholder="Tags">
                                </div>                                
                                <input class="btn btn-default" type="submit" value="Send">
                            </form>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
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

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
        <title>Our blog - iLibrarium</title>

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
                        <li class="active">
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
                
                <?php
                #moderator can remove a post in the blog
                if (isset($_GET['remove'])) {
                    if ($_SESSION['aut_user']->checkRoleNoRedirrect(new Role('name', 'Moderator'))) {
                        $remove_id = filter_input(INPUT_GET, 'remove', FILTER_SANITIZE_STRING);
                        if (!empty($remove_id) || $remove_id == 0) {
                            if (is_numeric($remove_id)) {
                                $deletePost = new Post($remove_id);
                                $deletePost->removePost();
                                $_SESSION['success_message'] = 'Post removed';
                            } else {
                                $_SESSION['login_message'] = 'Id provided is not a number';
                                header('Location: blog.php');
                                exit();
                            }
                        } else {
                            $_SESSION['login_message'] = 'Id provided is empty';
                            header('Location: blog.php');
                            exit();
                        }
                    } else {
                        $_SESSION['login_message'] = 'You are not allowed to remove posts';
                        header('Location: blog.php');
                        exit();
                    }
                }
                #<!-- Alert -->
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
                #get info and show posts
                $connection = new Connection();
                $link = $connection->connect();
                $result = $link->query("SELECT id FROM post ORDER BY date DESC");
                $posts = [];
                while ($record = $result->fetch()){
                    $posts[] = new Post($record['id']);
                }             
                foreach ($posts as $post) {
                    echo '<div class="row">';
                    echo '<article class="col-xs-12">';
                    if ($_SESSION['aut_user']->checkRoleNoRedirrect(new Role('name', 'Moderator'))) {
                        echo '<a href="blog.php?remove=' . $post->getId() . '"><span class="glyphicon glyphicon-remove pull-right"></span></a>';
                    }
                    echo '<div class="row">';
                    echo '<div class="col-xs-11">';
                    echo '<h2>' . $post->getHeader() . '</h2>';
                    echo '<p>' . $post->getContent() . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="row">';

                    echo '<p class="pull-right">Written on ' . $post->getFormattedDate() . ' by ' . $post->getUser()->getNickname() . '</p>';

                    echo '</div>';
                    echo '<img src="avatars/' . $post->getUser()->getAvatarFilename() . '" class="img-fluid media pull-right" alt="avatar_' . $record['nickname'] . '">';
                    echo '</article>';
                    echo '</div>';
                    echo '<hr>';
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

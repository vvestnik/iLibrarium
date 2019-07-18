<?php
#requires needed to deserialise User from Session
require_once './class/user.php';
require_once './class/userroles.php';
require_once './class/role.php';
require_once './class/store.php';
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
        if (isset($_GET['id'])) {
            $book_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
            if (!empty($book_id) || $book_id == 0) {
                if (is_numeric($book_id)) {
                    $book = new Book($book_id);
                    $myStore = $_SESSION['aut_user']->getStore();
                    #add book to stock
                    if (isset($_GET['change'])){
                        if($_GET['change'] == 'add'){
                            $book->addInstance($myStore);
                        }
                        #remove the book from the stock
                        elseif ($_GET['change'] == 'remove') {
                            $count = $book->qtyInStore($myStore);
                            $bookInstance = new BookInstance($book->getId(), $myStore);
                            if($count > 0){
                                $book->removeInstance($bookInstance);
                                $bookInstance = null;
                            }
                            #already empty
                            else{
                                $_SESSION['login_message'] = 'Stock is aleady empty';
                            }
                        }
                        #do not tamper with URL guys pls
                        else{
                            $_SESSION['login_message'] = 'Incorrect change value';
                            header('Location: index.php');
                            exit();
                        }
                    }
                    $otherStores = [];
                    $connection = new Connection();
                    $link = $connection->connect();
                    $result = $link->query("SELECT id FROM store WHERE id <> 2 AND id <> '" . $myStore->getId() . "'");
                    while ($record = $result->fetch()){
                        $otherStores[] = new Store($record['id']);
                    }
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
        ?>
        <title><?php echo $book->getName() . ' in stores'; ?> - iLibrarium</title>

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
                <h2><?php echo $book->getName() . ' in stores'; ?></h2>
                <div class="row">
                    <div class="col-lg-6 container-fluid">
                        <div class="row">
                            <img src="images/<?php echo $book->getDefImage()->getFilename(); ?>" class="img-fluid img-responsive" alt="image for <?php echo $book->getName(); ?>"><br>
                        </div>
                    </div>
                    <div class="col-lg-6 container-fluid">
                            <?php
                            echo '<p>';
                            echo '<b>Store:</b><br>';                            
                            echo $myStore->getName() . '<br>';                            
                            echo '<b>Address:</b><br>';
                            echo $myStore->getAddress() . '<br>';
                            echo '<b>In stock:</b><br>';
                            echo '<h1 class="display-4">' . $book->qtyInStore($myStore) . '</h1><br></p>';
                            #button for adding books
                            echo '<h4><a href="manage_books.php?id=' . $book->getId() . '&change=add"><span class="label label-success">Add a book to stock</span></a></h4>';
                            if ($book->qtyInStore($myStore) > 0) {
                                #if there are books, then button for removing books
                                echo '<h4><a href="manage_books.php?id=' . $book->getId() . '&change=remove"><span class="label label-danger">Remove a book</span></a></h4>';
                            } else {
                                echo '<h4><span class="label label-default">Remove a book</span></h4>';
                            }
                            ?>
                    </div>
                </div>
                <div class="panel panel-default">
                    <!-- Info about stock in other stores -->
                    <table class="table" id="book-list">
                        <thead>
                            <tr>
                                <th>Store name</th>
                                <th>Store address</th>
                                <th>Books in stock</th>
                            </tr>                            
                        </thead>
                        <tbody>
                            <?php
                            foreach ($otherStores as $store){
                                echo '<tr>';
                                echo '<td>';
                                echo $store->getName();
                                echo '</td>';
                                echo '<td>';
                                echo $store->getAddress();
                                echo '</td>';
                                echo '<td>';
                                echo $book->qtyInStore($store);
                                echo '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
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

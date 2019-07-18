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
?>
<!DOCTYPE html>
<!-- Template by Quackit.com -->
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

        <title>Edit a book - iLibrarium</title>

        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS: You can use this stylesheet to override any Bootstrap styles and/or apply your own styles -->        
        <link href="css/custom.css" rel="stylesheet" type="text/css"/>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <?php
        #get all data about book from db
        if (isset($_GET['id'])) {
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
            if (!empty($id) || $id == 0) {
                if (is_numeric($id)) {
                    $book = new Book($id);
                }
                else {
                    $_SESSION['login_message'] = 'Id sent is not a number';
                    header('Location: index.php');
                    exit();
                }
            }
            else {
                $_SESSION['login_message'] = 'Id sent is empty';
                header('Location: index.php');
                exit();
            }
        }
        else {
            $_SESSION['login_message'] = 'No id sent, to show a book';
            header('Location: index.php');
            exit();
        }
        ?>
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
                <?php
                #get data from the form
                if (isset($_POST['name'])) {
                    $name = clean(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
                    $ISBN = clean(filter_input(INPUT_POST, 'ISBN', FILTER_SANITIZE_STRING));
                    $description = clean(filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING));
                    $loan_time = filter_input(INPUT_POST, 'loan_time', FILTER_SANITIZE_STRING);
                    $genre_id = $_POST['genre_id'];
                    $author_id = $_POST['author_id'];
                    #complain if smth is empty
                    if (empty($name)) {
                        $alert .= 'Please enter name of the book<br>';
                    }
                    if (empty($ISBN)) {
                        $alert .= 'Please enter ISBN<br>';
                    }
                    if (empty($description)) {
                        $alert .= 'Please enter short desription<br>';
                    }
                    if (empty($genre_id)) {
                        $alert .= 'Please choose genres<br>';
                    }
                    if (empty($author_id)) {
                        $alert .= 'Please choose author(s)<br>';
                    }
                    if (!empty($alert)) {
                        #<!-- Alert -->
                        echo '<div class="alert alert-danger alert-dismissible" role="alert">';
                        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                        echo '<strong>Error(s):</strong><br>' . $alert;
                        echo '</div>';
                    } else {
                        #set new data     
                        $book->editBook($name, $ISBN, $description, $loan_time, $author_id, $genre_id);
                        #show something meningful to a user
                        echo '<h2>Book data changed.</h2>';
                        echo '<h3>New information</h3>';
                        echo '<p><b>Book name:</b><br>' . $book->getName() . '<br>';
                        echo '<b>ISBN:</b><br>' . $book->getIsbn() . '<br>';
                        echo '<b>Description:</b><br>' . $book->getDescription() . '<br>';
                        echo '<b>Genre(s):</b><br>';
                        foreach ($book->getGenresArray() as $genre) {
                            echo $genre . '<br>';
                        }
                        #multiple authors....
                        echo '<b>Author(s):</b><br>';
                        foreach ($book->getAuthorsArray() as $author) {
                            echo $author . '<br>';
                        }
                        echo '<b>Loan time:</b><br>' . $book->getLoanTime() . ' months<br></p>';
                        echo '<br>';
                    }
                }
                #form                
                if (!isset($_POST['name']) || !empty($alert)) {
                    ?>
                    <h2>Please enter book data</h2>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <span class="glyphicon glyphicon-pencil"></span> 
                                Fill in the form please
                            </h3>
                        </div>
                        <div class="panel-body">
                            <form action="edit_book.php?id=<?php echo $book->getId(); ?>" method="post" id="editbookform">
                                <?php
                                $connection = new Connection();
                                $link = $connection->connect();
                                ?>
                                <div class="form-group <?php 
                                if (empty($name) && isset($_POST['name'])) {
                                    echo 'has-error';
                                } ?>">
                                    <label class="control-label" for="name">Name</label>
                                    <input type="text" value="<?php echo $book->getName(); ?>" class="form-control" id="name" name="name" placeholder="Name">
                                </div>
                                <div class="form-group <?php 
                                if (empty($ISBN) && isset($_POST['ISBN'])) {
                                    echo 'has-error';
                                } ?>">
                                    <label class="control-label" for="ISBN">ISBN</label>
                                    <input type="text" value="<?php echo $book->getIsbn(); ?>" class="form-control" id="ISBN" name="ISBN" placeholder="ISBN">
                                </div>
                                <div class="form-group <?php 
                                if (empty($description) && isset($_POST['description'])) {
                                    echo 'has-error';
                                } ?>">
                                    <label class="control-label" for="Description">Description</label>
                                    <textarea class="form-control" id="description" name="description" placeholder="Description"><?php echo $book->getDescription(); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="author-select">Choose author(s)</label>
                                    <p>For multiple choice: ctrl + click (on Win) or cmd + click (on Mac)</p>
                                    <?php
                                    echo '<select size="20" multiple class="form-control" id="author-select" name="author_id[]">';
                                    $authors = [];
                                    $result = $link->query("SELECT id FROM author ORDER BY name, surname ASC");
                                    while ($record = $result->fetch()) {
                                        $authors[] = new Author($record['id']);
                                    }
                                    foreach ($authors as $listAuthor){
                                        echo '<option ';
                                        if($book->isAuthorSet($listAuthor)){
                                            echo 'selected="selected" ';
                                        }
                                        echo 'value="' . $listAuthor->getId() . '">' . $listAuthor->getFullname() . '</option>';
                                    }
                                    echo '</select>';
                                    ?>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading" id="genres">
                                        <h4 class="panel-title">
                                            Genres
                                        </h4>
                                    </div>
                                    <!-- Table of genres -->
                                    <table class="table">                                        
                                        <?php
                                        $genres = [];
                                        $result = $link->query("SELECT id FROM genre");
                                        while ($record = $result->fetch()) {
                                            $genres[] = new Genre('id', $record['id']);
                                        }
                                        $i = 0;
                                        echo '<tr>';
                                        foreach ($genres as $listGenre){
                                            $i ++;
                                            if ($i % 2 != 0) {
                                                echo '<tr>';
                                            }
                                            echo '<td>';
                                            echo '<div class="form-check">';
                                            echo '<input type="checkbox" class="form-check-input" ';
                                            if($book->isGenreSet($listGenre)){
                                                echo 'checked="checked" ';
                                            }
                                            echo 'value="' . $listGenre->getId() . '" id="' . $listGenre->getGenreName() . '" name="genre_id[]">';
                                            echo '&nbsp<label class="form-check-label" id="checklabel" for="' . $listGenre->getGenreName() . '"> ' . $listGenre->getGenreName() . '</label><br>';
                                            echo '</td>';
                                            echo '</div>';
                                            if ($i % 2 == 0) {
                                                echo '</tr>';
                                            }
                                        }
                                        if ($i % 2 != 0) {
                                            echo '<td></td></tr>';
                                        }
                                        ?>
                                    </table>
                                </div>
                                <div class="form-group">
                                    <label for="loan-time-select">For how many months the book is borrowed?</label>
                                    <select class="form-control" id="loan-time-select" name="loan_time">
                                        <?php
                                        for($i = 1; $i < 7; $i ++){                                            
                                            echo '<option ';
                                            if($i == $book->getLoanTime()){
                                                echo 'selected="selected" ';
                                            }
                                            echo '>' . $i . '</option>';
                                        }
                                        ?>
                                    </select>                                    
                                </div>
                                <input type="hidden" name="book_id" value="<?php echo $book->getId(); ?>">
                                <input class="btn btn-default" type="submit" value="Send">
                                <?php
                                $connection = null;
                                ?>

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

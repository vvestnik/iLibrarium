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
?>
<!DOCTYPE html>
<!-- Template by Quackit.com -->
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

        <title>Reservation - iLibrarium</title>

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
        if (isset($_GET['book_id'])) {
            $book_id = filter_input(INPUT_GET, 'book_id', FILTER_SANITIZE_STRING);
            if (!empty($book_id) || $book_id == 0) {
                if (is_numeric($book_id)) {
                    $book = new Book($book_id);
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
            $_SESSION['login_message'] = 'No id sent, to make a reservation';
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
                #get data from form
                if (isset($_POST['registered_by_name'])) {
                    $registered_for = filter_input(INPUT_POST, 'registered_for', FILTER_SANITIZE_STRING);
                    $place_to_take = filter_input(INPUT_POST, 'place_to_take', FILTER_SANITIZE_STRING);                    
                    if (empty($place_to_take)) {
                        $alert .= 'Please select place to issue<br>';
                    }
                    if (!empty($alert)) {
                        #<!-- Alert -->
                        echo '<div class="alert alert-danger alert-dismissible" role="alert">';
                        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                        echo '<strong>Error(s):</strong><br>' . $alert;
                        echo '</div>';
                    } else {
                        $order = new Transaction($_SESSION['aut_user'], new User($registered_for), new BookInstance($book->getId(), new Store($place_to_take)));

                        echo '<h2>Reservation made.</h2>';
                        $order->generatePDF();
                        $order->viewPDF();
                    }
                }
                #form
                if (!isset($_POST['registered_by_name']) || !empty($alert)) {
                    ?>
                    <h2>Select/check reservation details</h2>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <span class="glyphicon glyphicon-pencil"></span>
                                Fill in the form please
                            </h3>
                        </div>
                        <div class="panel-body">
                            <form action="reservation.php?book_id=<?php echo $book->getId(); ?>" method="post" id="reservationform">
                                <?php
                                $connection = new Connection();
                                $link = $connection->connect();
                                ?>
                                <div class="form-group">
                                    <label class="control-label" for="registered_by_name">Registered by</label>
                                    <input type="text" readonly="readonly" value="<?php echo $_SESSION['aut_user']->getFullName(); ?>" class="form-control" id="registered_by_name" name="registered_by_name">
                                </div>
                                <div class="form-group <?php
                                    if (empty($registered_for) && isset($_POST['registered_for'])) {
                                        echo 'has-error';
                                    }
                                    ?>">
                                    <label class="control-label" for="registered_for_name">Registered for</label>
                                    <?php
                                    #same here, valuable input is hidden
                                    #if seller makes a reservation, he can choose a customer
                                    if (!$_SESSION['aut_user']->checkRoleNoRedirrect(new Role('name', 'Store-seller'))) {
                                        echo '<input type="text" readonly="readonly" value="' . $_SESSION['aut_user']->getFullName() . '" class="form-control" id="registered_for_name" name="registered_for_name">';
                                        echo '<input type="hidden" name="registered_for" value="' . $_SESSION['aut_user']->getId() . '">';
                                    }
                                    #if user makes a reservation, he makes it for himself, valuable input is hidden
                                    else{
                                        echo '<select class="form-control" id="registered_for" name="registered_for">';
                                        $result = $link->query("SELECT id FROM user ORDER BY name, surname ASC");
                                        $users = [];
                                        while ($record = $result->fetch()) {
                                            $users[] = new User($record['id']);
                                        }
                                        foreach($users as $user){
                                            echo '<option ';
                                            if((isset($registered_for) && $user->getId() == $registered_for) || 
                                               (!isset($registered_for) && $user->getId() == $_SESSION['aut_user']->getId())){
                                                echo 'selected="selected" ';
                                            }
                                            echo 'value="' . $user->getId() . '">' . $user->getFullName() . '</option>';
                                        }
                                        echo '</select>';
                                    }
                                    ?>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="store_name">Store</label>
                                    <input readonly="readonly" type="text" value="<?php
                                        if($_SESSION['aut_user']->isEmployee()){
                                            $storeObj = $_SESSION['aut_user']->getStore();
                                        }
                                        else{
                                            $storeObj = new Store(2);
                                        }
                                        echo $storeObj->getName();
                                        ?>" class="form-control" id="store_name" name="store_name">
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="book_name">Book name</label>
                                    <input readonly="readonly" type="text" value="<?php echo $book->getName(); ?>" class="form-control" id="book_name" name="book_name" placeholder="Book name">
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="place_to take">Place to issue</label>
                                    <?php
                                    #check, where book are in stock, then select from those places
                                    echo '<select class="form-control" id="place_to_take" name="place_to_take">';
                                    $result = $link->query("SELECT id FROM store ORDER BY name ASC");
                                    $storeList = [];
                                    while($record = $result->fetch()){
                                        $checkStore = new Store($record['id']);
                                        if($book->qtyInStore($checkStore) > 0){
                                            $storeList[] = $checkStore;
                                        }
                                        else{
                                            $checkStore = null;
                                        }
                                    }
                                    foreach ($storeList as $pickUpStore) {
                                        echo '<option ';
                                        if(isset($place_to_take) && $pickUpStore->getId() == $place_to_take){
                                            echo 'selected="selected" ';
                                        }
                                        echo 'value="' . $pickUpStore->getId() . '">' . $pickUpStore->getName() . '</option>';
                                    }
                                    echo '</select>';
                                    ?>
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

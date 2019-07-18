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
        <?php
        #check if id is valid
        if (isset($_GET['id'])) {
            $order_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
            if (!empty($order_id) || $order_id == 0) {
                if (is_numeric($order_id)) {
                    $order = new Transaction($order_id);                    
                    #if not seller or the user is not an owner, go to index
                    if(!$_SESSION['aut_user']->checkRoleNoRedirrect(new Role('name', 'Store-seller')) && $_SESSION['aut_user']->getId() != $order->getRegisteredFor()->getId()) {
                        $_SESSION['login_message'] = 'No access to order with the id number';
                        header('Location: index.php');
                        exit();
                    }
                    #book taken or returned
                    if(isset($_GET['state'])){
                        if($_SESSION['aut_user']->checkRoleNoRedirrect(new Role('name', 'Store-seller'))){
                            $state = $_GET['state'];
                            #book taken
                            if($state == 'taken'){
                                $order->pickedUp();
                                $order->generatePDF();
                            }
                            #book returned
                            elseif ($state == 'returned') {
                                $order->returnBook($_SESSION['aut_user']->getStore());
                                $order->generatePDF();
                            }
                            #manula written URL
                            else{
                                $_SESSION['login_message'] = 'Wrong state';
                            }
                        }
                        else{
                            $_SESSION['login_message'] = 'You don\'t have permission to do this';
                        }
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
            $_SESSION['login_message'] = 'No id sent, to show the order';
            header('Location: index.php');
            exit();
        }
        ?>
        <title>Order #<?php echo $order_id; ?> - iLibrarium</title>

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
                        <li>
                            <a href="gallery.php">Gallery</a>
                        </li>
                        <!-- Show only when logged in -->
                        <?php
                        if (isset($_SESSION['aut_user'])) {
                        ?>                                        
                            <li class="active dropdown">
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
                <h2>Order #<?php echo $order->getId(); ?></h2>
                <div class="row">
                    <!--show all data about the order-->
                    <article class="col-xs-12">
                        <?php
                        echo '<div class="pull-right">';
                        $order->viewPDF();
                        echo '</div>';
                        echo '<h4>Registered by</h4>';
                        echo '<p>' . $order->getRegisteredBy()->getFullName() . '</p><hr>';
                        
                        echo '<h4>Registered for</h4>';
                        echo '<p>' . $order->getRegisteredFor()->getFullName() . '</p><hr>';
                        
                        echo '<h4>Store</h4>';
                        echo '<p>' . $order->getPlaceRegistered()->getName() . '</p><hr>';
                        
                        echo '<h4>Book</h4>';
                        echo '<p>' . $order->getBookInstance()->getName() . '</p><hr>';
                        
                        echo '<h4>Registration date</h4>';
                        echo '<p>' . $order->getFormattedRegistered() . '</p><hr>';
                        
                        echo '<h4>Ready to pick up on</h4>';
                        echo '<p>' . $order->getFormattedReady() . '</p><hr>';
                        
                        echo '<h4>Borrowed before</h4>';
                        echo '<p>' . $order->getFormattedBefore() . '</p><hr>';
                        
                        echo '<h4>Pick up place</h4>';
                        echo '<p>' . $order->getPlaceToTake()->getName() . '</p><hr>';
                        
                        echo '<h4>Picked up on</h4>';
                        #if the book is picked up
                        if($order->getDateTaken()){
                            echo '<p>' . $order->getFormattedTaken() . '</p>';
                        }
                        #if the book is not picked up, show button to set it
                        elseif($_SESSION['aut_user']->checkRoleNoRedirrect(new Role('name', 'Store-seller'))){
                            echo '<h4><a href="order.php?id=' . $order->getId() . '&state=taken"><span class="label label-primary">Taken</span></a></h4>';
                        }
                        echo '<hr>';
                        echo '<h4>Returned on</h4>';
                        #if book picked up and returned
                        if($order->getDateTaken()){
                            if($order->getDateReturned()){
                                echo '<p>' . $order->getFormattedReturned() . '</p>';
                            }
                            #if the book picked up but not returned, show button to set the return
                            elseif($_SESSION['aut_user']->checkRoleNoRedirrect(new Role('name', 'Store-seller'))){
                                echo '<h4><a href="order.php?id=' . $order->getId() . '&state=returned"><span class="label label-primary">Returned</span></a></h4>';
                            }
                        }
                        echo '<hr>';
                        
                        echo '<h4>Return place</h4>';
                        #show only if returned
                        if($order->getPlaceReturned()){
                            echo '<p>' . $order->getPlaceReturned()->getName() . '</p><hr>';
                        }
                        ?>
                        
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

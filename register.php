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
$alert = '';
$name = '';
$surname = '';
$nickname = '';
$email = '';
$password = '';
if(isset($_SESSION['aut_user'])){
    $_SESSION['login_message'] = 'You are already logged in. Registration not allowed';
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<!-- Template by Quackit.com -->
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

        <title>Register - iLibrarium</title>

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
                #get info from form
                if (isset($_POST['name'])) {
                    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
                    $surname = filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_STRING);
                    $nickname = filter_input(INPUT_POST, 'nickname', FILTER_SANITIZE_STRING);
                    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
                    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
                    if (empty($name)) {
                        $alert .= 'Please enter your name<br>';
                    }
                    if (empty($surname)) {
                        $alert .= 'Please enter your surname<br>';
                    }
                    if (empty($email)) {
                        $alert .= 'Please enter your email<br>';
                    }
                    if (empty($nickname)) {
                        $alert .= 'Please enter your username<br>';
                    }
                    if (empty($password)) {
                        $alert .= 'Please enter password<br>';
                    }
                    if (!empty($alert)) {
                        #<!-- Alert -->
                        echo '<div class="alert alert-danger alert-dismissible" role="alert">';
                        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                        echo '<strong>Error(s):</strong><br>' . $alert;
                        echo '</div>';
                    } else {
                        $newUser = new User($name, $surname, $nickname, $email, $password);
                        echo '<h2>Thank you. Now please log in.</h2>';
                        echo '<h3>Here are your information</h3>';
                        echo '<p><b>Name:</b><br>' . $newUser->getName() . '<br>';
                        echo '<b>Surname:</b><br>' . $newUser->getSurname() . '<br>';
                        echo '<b>Username:</b><br>' . $newUser->getNickname() . '<br>';
                        echo '<b>Email:</b><br>' . $newUser->getEmail() . '<br>';
                    }
                }
                if (!isset($_POST['name']) || !empty($alert)) {
                ?>
                <h2>Please provide us your data</h2>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <span class="glyphicon glyphicon-pencil"></span> 
                            Fill in the form please
                        </h3>
                    </div>
                    <div class="panel-body">
                        <form action="register.php" method="post" id="registerform">
                            <div class="form-group <?php 
                                if (empty($name) && isset($_POST['name'])) { 
                                    echo 'has-error';                                 
                                } 
                                ?>">
                                <label class="control-label" for="name">Name</label>
                                <input type="text" value="<?php echo $name; ?>" class="form-control" id="name" name="name" placeholder="Name">
                            </div>
                            <div class="form-group <?php 
                                if (empty($surname) && isset($_POST['surname'])) {
                                    echo 'has-error';
                                }  
                                ?>">
                                <label class="control-label" for="surname">Surname</label>
                                <input type="text" value="<?php echo $surname; ?>" class="form-control" id="surname" name="surname" placeholder="Surname">
                            </div>
                            <div class="form-group <?php 
                                if (empty($nickname) && isset($_POST['nickname'])) {
                                    echo 'has-error';
                                } 
                                ?>">
                                <label class="control-label" for="nickname">Username</label>
                                <input type="text" value="<?php echo $nickname; ?>" class="form-control" id="nickname" name="nickname" placeholder="Username">
                            </div>
                            <div class="form-group <?php
                                if (empty($email) && isset($_POST['email'])) {
                                    echo 'has-error';
                                } 
                                ?>">
                                <label class="control-label" for="email">Email</label>
                                <input type="email" value="<?php echo $email; ?>" class="form-control" id="email" name="email" placeholder="Email">
                            </div>
                            <div class="form-group <?php 
                                if (empty($password) && isset($_POST['password'])) { 
                                    echo 'has-error';
                                } 
                                ?>">
                                <label class="control-label" for="password">Password</label>
                                <input type="password" value="<?php echo $password; ?>" class="form-control" id="password" name="password" placeholder="Password">
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

           <?php
mysqli_close($link);
?>
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

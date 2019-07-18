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
#only for admins
$_SESSION['aut_user']->checkRole(new Role('name', 'Admin'));
$users = [];
?>
<!DOCTYPE html>
<!-- Template by Quackit.com -->
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

        <title>Administrate users - iLibrarium</title>

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
                            <li class="dropdown active">
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
            <!-- Show users in table -->
            <div class="col-sm-6">
                <h2>Registered users</h2>
                    <div class="panel panel-default">
                        <table class="table" id="book-list">
                            <?php
                            $connection = new Connection();
                            $link = $connection->connect();
                            $result = $link->query("SELECT id FROM user ORDER BY id ASC");
                            while($record = $result->fetch()){
                                $users[] = new User($record['id']);
                            }
                            ?>
                            <thead>
                                <tr>
                                    <th>Avatar</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                #build the table
                                foreach ($users as $user){
                                    echo '<tr>';
                                    echo '<td>';
                                    echo '<a href="user.php?id=' . $user->getId() . '"><img src="avatars/' . $user->getAvatarFilename() . '" alt="avatar_' . $user->getId() . '"></a>';
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<a href="user.php?id=' . $user->getId() . '">' . $user->getName() . ' ' . $user->getSurname() . '</a>';
                                    if($user->getId() == $_SESSION['aut_user']->getId()){
                                        echo ' (me)';
                                    }
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<a href="user.php?id=' . $user->getId() . '">' . $user->getNickname() . '</a>';
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<a href="user.php?id=' . $user->getId() . '">' . $user->getEmail() . '</a>';
                                    echo '</td>';
                                    echo '</tr>';
                                    }
                                ?>
                            </tbody>
                            <?php
                            $connection = null;
                            ?>
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

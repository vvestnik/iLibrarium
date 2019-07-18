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
$alert = '';
$staff = '';
?>
<!DOCTYPE html>
<!-- Template by Quackit.com -->
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        
        <title>Edit user settings - iLibrarium</title>

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
        #check if id is valid
        if (isset($_GET['id'])) {
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
            if (!empty($id) || $id == 0) { 
                if (is_numeric($id)) {
                    if($id != $_SESSION['aut_user']->getId()){
                        #only for admins or HR
                        $_SESSION['aut_user']->checkTwoRoles(new Role('name', 'Admin'), new Role('name', 'HR-worker'));
                    }
                    $user = new User($id);
                    
                    $name = $user->getName();
                    $surname = $user->getSurname();
                    $nickname = $user->getNickname();
                    $email = $user->getEmail();
                    $image = $user->getAvatarFilename();
                    if($user->isEmployee()){
                        $staff = $user->getStore()->getId();
                    }
                    $role = $user->getRoleIdsArray();

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
            $_SESSION['login_message'] = 'No id sent, to show a user';
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
            <div class="col-sm-6">
                <?php
                #get data from form
                if (isset($_POST['name'])) {
                    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
                    $surname = filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_STRING);
                    if($_SESSION['aut_user']->checkRoleNoRedirrect(new Role('name', 'HR-worker'))){
                        $staff = filter_input(INPUT_POST, 'staff', FILTER_SANITIZE_STRING);
                    }
                    #admin can change roles
                    if($_SESSION['aut_user']->checkRoleNoRedirrect(new Role('name', 'Admin'))){ 
                        $role_id = $_POST['role_id'];
                    }
                    #complains, complains, complains
                    if (empty($name)) {
                        $alert .= 'Please enter name<br>';
                    }
                    if (empty($surname)) {
                        $alert .= 'Please enter surname<br>';
                    }
                    if (!empty($alert)) {
                        #<!-- Alert -->
                        echo '<div class="alert alert-danger alert-dismissible" role="alert">';
                        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                        echo '<strong>Error(s):</strong><br>' . $alert;
                        echo '</div>';
                    } 
                    else {
                        $user->edit($name, $surname);
                        if($_SESSION['aut_user']->checkRoleNoRedirrect(new Role('name', 'HR-worker'))){
                            if(empty($staff)){
                                $user->unemploy();
                            }
                            else{
                                $user->employ(new Store($staff));
                            }
                        }
                        if($_SESSION['aut_user']->checkRoleNoRedirrect(new Role('name', 'Admin'))){
                            $user->editRoles($role_id);
                        }
                        
                        
                        #smth meningful after successful insert
                        echo '<h2>User data changed.</h2>';
                        echo '<h3>New information</h3>';
                        echo '<p><b>User name:</b><br>' . $user->getName() . '<br>';
                        echo '<b>User surnname:</b><br>' . $user->getSurname() . '<br>';
                        if($_SESSION['aut_user']->checkRoleNoRedirrect(new Role('name', 'Admin'))){ 
                            echo '<b>Role(s):</b><br>';
                            foreach ($user->getRolesArray() as $userRole) {
                                echo $userRole . '<br>';
                            }
                        }
                        if($_SESSION['aut_user']->checkRoleNoRedirrect(new Role('name', 'HR-worker'))){
                            echo '<b>Works at:</b><br>';
                            if($user->isEmployee()){
                                echo $user->getStore()->getName() . '<br>';
                            }
                            else{
                                echo 'Not in crew';
                            }
                        }
                        echo '</p>';
                        if($user->getId() == $_SESSION['aut_user']->getId()){
                            session_destroy();
                        }
                    }
                }
                #form
                if (!isset($_POST['name']) || !empty($alert)) {
                    ?>
                    <h2>Change user data</h2>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <span class="glyphicon glyphicon-pencil"></span> 
                                Fill in the form please
                            </h3>
                        </div>
                        <div class="panel-body">
                            <form action="user.php?id=<?php echo $id; ?>" method="post" id="edituserform">
                                <?php
                                $connection = new Connection();
                                $link = $connection->connect();
                                ?>
                                <div class="form-group">
                                    <img src="avatars/<?php echo $image; ?>" alt=""><br>
                                    <?php
                                    if($id == $_SESSION['aut_user']->getId()){
                                        echo '<h4><a href="avatars.php?user_id=' . $id . '"><span class="label label-success">Choose a new avatar from avatar gallery</span></a></h4>';
                                    }
                                    ?>
                                </div>
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
                                    <input type="text" value="<?php echo $nickname; ?>" class="form-control" readonly="readonly" id="nickname" name="nickname" placeholder="Username">
                                </div>
                                <div class="form-group <?php 
                                if (empty($email) && isset($_POST['email'])) {
                                    echo 'has-error';
                                } 
                                ?>">
                                    <label class="control-label" for="email">Email</label>
                                    <input type="text" value="<?php echo $email; ?>" class="form-control" id="email" name="email" placeholder="Email" readonly="readonly">
                                </div>
                                <?php
                                if($_SESSION['aut_user']->checkRoleNoRedirrect(new Role('name', 'Admin'))){ 
                                ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading" id="genres">
                                        <h4 class="panel-title">
                                            Roles
                                        </h4>
                                    </div>
                                    <!-- Table of roles -->
                                    <table class="table">                                        
                                        <?php
                                        $result = $link->query("SELECT role_name AS name, id FROM role ");
                                        $i = 0;
                                        echo '<tr>';
                                        while ($record = $result->fetch()) {
                                            $i ++;
                                            if ($i % 2 != 0) {
                                                echo '<tr>';
                                            }
                                            echo '<td>';
                                            echo '<div class="form-check">';
                                            echo '<input type="checkbox" class="form-check-input" ';
                                            if(in_array($record['id'], $role)){
                                                echo 'checked="checked" ';
                                            }
                                            echo 'value="' . $record['id'] . '" id="' . $record['name'] . '" name="role_id[]">';
                                            echo '&nbsp<label class="form-check-label" id="checklabel" for="' . $record['name'] . '"> ' . $record['name'] . '</label><br>';
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
                                <?php
                                #chose store
                                }
                                if($_SESSION['aut_user']->checkRoleNoRedirrect(new Role('name', 'HR-worker'))){
                                ?>
                                <div class="form-group">
                                    <label for="staff">Works at:</label>
                                    <?php
                                    echo '<select class="form-control" id="staff" name="staff">';
                                    $result = $link->query("SELECT name, id FROM store ORDER BY name ASC");
                                    echo '<option ';
                                    if($staff == NULL){
                                        echo 'selected="selected" ';
                                    }
                                    echo 'value="">Not in crew</option>'; 
                                    while ($record = $result->fetch()) {
                                        echo '<option ';
                                        if($record['id'] == $staff){
                                            echo 'selected="selected" ';
                                        }
                                        echo 'value="' . $record['id'] . '">' . $record['name'] . '</option>';
                                    }
                                    echo '</select>';
                                    ?>
                                </div>
                                <?php
                                }
                                ?>
                                <input type="hidden" name="user_id" value="<?php echo $id; ?>">
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
